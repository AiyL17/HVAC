<?php
header('Content-Type: application/json');

include '../../config/ini.php';
require_once '../../class/notificationClass.php';

// Initialize the database connection
$pdo = pdo_init();

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['uid'];

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (empty($data['app_schedule']) || empty($data['service_type_id']) || empty($data['user_technician'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Validate that both primary and secondary technicians are provided
if (empty($data['user_technician_2'])) {
    echo json_encode(['success' => false, 'message' => 'Both primary and secondary technicians are required']);
    exit;
}

// Validate that primary and secondary technicians are different
if ($data['user_technician'] === $data['user_technician_2']) {
    echo json_encode(['success' => false, 'message' => 'Primary and secondary technicians must be different']);
    exit;
}

try {
    // Get current datetime for app_created
    $created_date = date('Y-m-d H:i:s');
    
    // Set default status (1 = pending)
    $status_id = 2;
    
    // Get the next app_id by finding the maximum existing app_id and adding 1
    $maxIdStmt = $pdo->prepare("SELECT MAX(app_id) as max_id FROM appointment");
    $maxIdStmt->execute();
    $maxIdResult = $maxIdStmt->fetch(PDO::FETCH_ASSOC);
    $nextAppId = ($maxIdResult['max_id'] ?? 0) + 1;
    
    // Prepare SQL statement with explicit app_id and second technician
    $sql = "INSERT INTO appointment (
                app_id,
                app_schedule, 
                app_desc, 
                app_created, 
                app_status_id, 
                service_type_id, 
                appliances_type_id,
                user_id, 
                user_technician,
                user_technician_2,
                technician_justification
            ) VALUES (
                :app_id,
                :app_schedule, 
                :app_desc, 
                :app_created, 
                :app_status_id, 
                :service_type_id, 
                :appliances_type_id,
                :user_id, 
                :user_technician,
                :user_technician_2,
                :technician_justification
            )";
    
    $stmt = $pdo->prepare($sql);
    
    // Bind the app_id parameter
    $stmt->bindParam(':app_id', $nextAppId, PDO::PARAM_INT);
    
    // Handle appliances_type_id - allow NULL if not provided
    $appliances_type_id = !empty($data['appliances_type_id']) ? $data['appliances_type_id'] : null;
    
    // Handle user_technician_2 - allow NULL if not provided
    $user_technician_2 = !empty($data['user_technician_2']) ? $data['user_technician_2'] : null;
    
    // Handle technician_justification - allow NULL if not provided
    $technician_justification = !empty($data['technician_justification']) ? $data['technician_justification'] : null;
    
    // Bind parameters
    $stmt->bindParam(':app_schedule', $data['app_schedule']);
    $stmt->bindParam(':app_desc', $data['app_desc']);
    $stmt->bindParam(':app_created', $created_date);
    $stmt->bindParam(':app_status_id', $status_id);
    $stmt->bindParam(':service_type_id', $data['service_type_id']);
    $stmt->bindParam(':appliances_type_id', $appliances_type_id);
    $stmt->bindParam(':user_id', $user_id); // Use session user ID
    $stmt->bindParam(':user_technician', $data['user_technician']);
    $stmt->bindParam(':user_technician_2', $user_technician_2);
    $stmt->bindParam(':technician_justification', $technician_justification);
    
    // Execute the statement
    $stmt->execute();
    
    // Use the manually generated app_id instead of lastInsertId()
    $appointment_id = $nextAppId;
    
    // Store customer's current address as transaction address at appointment creation
    try {
        // Get customer's current address
        $addressQuery = $pdo->prepare('
            SELECT municipality_city, province, barangay, zip_code, house_building_street 
            FROM user 
            WHERE user_id = :user_id
        ');
        $addressQuery->execute(['user_id' => $user_id]);
        $addressData = $addressQuery->fetch(PDO::FETCH_ASSOC);
        
        if ($addressData) {
            // Insert transaction address at appointment creation
            $transactionAddressSQL = '
                INSERT INTO appointment_transaction_address 
                (app_id, user_id, municipality_city, province, barangay, zip_code, full_address, created_by) 
                VALUES (:app_id, :user_id, :municipality_city, :province, :barangay, :zip_code, :full_address, :created_by)
            ';
            
            $transactionParams = [
                'app_id' => $appointment_id,
                'user_id' => $user_id,
                'municipality_city' => $addressData['municipality_city'],
                'province' => $addressData['province'],
                'barangay' => $addressData['barangay'],
                'zip_code' => $addressData['zip_code'],
                'full_address' => $addressData['house_building_street'],
                'created_by' => $user_id // Customer created the appointment
            ];
            
            $transactionQuery = $pdo->prepare($transactionAddressSQL);
            $transactionResult = $transactionQuery->execute($transactionParams);
            
            if ($transactionResult) {
                error_log("Transaction address stored at appointment creation for appointment {$appointment_id}");
            } else {
                error_log("Failed to store transaction address at appointment creation for appointment {$appointment_id}");
            }
        }
    } catch (PDOException $e) {
        error_log("Error storing transaction address at appointment creation: " . $e->getMessage());
        // Don't fail the appointment creation if address storage fails
    }
    
    // Fetch the newly created appointment with all relation data including second technician
    $query = $pdo->prepare('SELECT
        a.*,
        a_s.app_status_name,
        u.user_name, u.user_midname, u.user_lastname,
        ut.user_name AS tech_name,
        ut.user_midname AS tech_midname,
        ut.user_lastname AS tech_lastname,
        ut2.user_name AS tech2_name,
        ut2.user_midname AS tech2_midname,
        ut2.user_lastname AS tech2_lastname,
        s.service_type_name
    FROM
        appointment a
    JOIN
        appointment_status a_s
        ON a.app_status_id = a_s.app_status_id
    JOIN
        user u
        ON a.user_id = u.user_id
    JOIN
        user ut
        ON a.user_technician = ut.user_id
    LEFT JOIN
        user ut2
        ON a.user_technician_2 = ut2.user_id
    JOIN
        service_type s
        ON a.service_type_id = s.service_type_id 
    WHERE 
        a.app_id = :appointment_id');
        
    $query->bindParam(':appointment_id', $appointment_id);
    $query->execute();
    
    $appointment = $query->fetch(PDO::FETCH_ASSOC);
    
    // Format dates for better client-side handling
    if (isset($appointment['app_schedule'])) {
        $app_schedule = new DateTime($appointment['app_schedule']);
        $appointment['app_schedule'] = $app_schedule->format('c'); // ISO 8601 format
    }
    
    if (isset($appointment['app_created'])) {
        $created = new DateTime($appointment['app_created']);
        $appointment['app_created'] = $created->format('c'); // ISO 8601 format
    }
    
    // Create notification for administrators
    try {
        $notificationHandler = new NotificationHandler($pdo);
        
        // Prepare data for notification
        $appointmentData = [
            'app_id' => $appointment_id,
            'app_schedule' => $data['app_schedule'],
            'user_id' => $user_id
        ];
        
        $customerData = [
            'user_name' => $appointment['user_name'],
            'user_midname' => $appointment['user_midname'],
            'user_lastname' => $appointment['user_lastname']
        ];
        
        $serviceData = [
            'service_type_name' => $appointment['service_type_name']
        ];
        
        $technicianData = [
            'user_name' => $appointment['tech_name'],
            'user_midname' => $appointment['tech_midname'],
            'user_lastname' => $appointment['tech_lastname']
        ];
        
        // Create the notification
        if (isset($data['is_rebook']) && $data['is_rebook']) {
            // For rebooking, use the special rebook notification method
            $notificationResult = $notificationHandler->createRebookNotification(
                $appointmentData,
                $customerData,
                $serviceData,
                $technicianData,
                $appointment['app_id']  // Original appointment ID
            );
        } else {
            $notificationResult = $notificationHandler->createAppointmentNotification(
                $appointmentData,
                $customerData,
                $serviceData,
                $technicianData
            );
        }
        
        // Log notification result for debugging (optional)
        if (!$notificationResult['success']) {
            error_log('Failed to create appointment notification: ' . $notificationResult['message']);
        }
        
        // Create technician assignment notifications
        $techNotificationResult = $notificationHandler->createTechnicianAssignmentNotification(
            $appointment_id,
            $data['user_technician'], // Primary technician
            $user_technician_2, // Secondary technician (can be null)
            $user_id, // Customer who created the appointment
            ['appointment_type' => isset($data['is_rebook']) && $data['is_rebook'] ? 'rebook' : 'new']
        );
        
        // Log technician notification result for debugging
        if (!$techNotificationResult['success']) {
            error_log('Failed to create technician assignment notifications: ' . $techNotificationResult['message']);
        } else {
            error_log('Technician assignment notifications created successfully for appointment ' . $appointment_id);
        }
        
    } catch (Exception $e) {
        // Log error but don't fail the appointment creation
        error_log('Error creating appointment notification: ' . $e->getMessage());
    }
    
    // Return success response with appointment data
    echo json_encode([
        'success' => true, 
        'message' => 'Appointment created successfully', 
        'appointment' => $appointment
    ]);
    
} catch (PDOException $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>