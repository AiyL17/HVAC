<?php
header('Content-Type: application/json');

include '../../config/ini.php';

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
if (empty($data['app_id']) || empty($data['app_schedule']) || empty($data['service_type_id']) || empty($data['user_technician'])) {
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
    // Prepare SQL statement for update - without any update timestamp
    $sql = "UPDATE appointment SET 
                app_schedule = :app_schedule, 
                app_desc = :app_desc, 
                service_type_id = :service_type_id, 
                appliances_type_id = :appliances_type_id,
                user_technician = :user_technician,
                user_technician_2 = :user_technician_2,
                technician_justification = :technician_justification
            WHERE 
                app_id = :app_id 
                AND user_id = :user_id";
    
    $stmt = $pdo->prepare($sql);
    
    // Handle appliances_type_id - allow NULL if not provided
    $appliances_type_id = !empty($data['appliances_type_id']) ? $data['appliances_type_id'] : null;
    
    // Handle user_technician_2 - allow NULL if not provided
    $user_technician_2 = !empty($data['user_technician_2']) ? $data['user_technician_2'] : null;
    
    // Handle technician_justification - allow NULL if not provided
    $technician_justification = !empty($data['technician_justification']) ? $data['technician_justification'] : null;
    
    // Bind parameters
    $stmt->bindParam(':app_schedule', $data['app_schedule']);
    $stmt->bindParam(':app_desc', $data['app_desc']);
    $stmt->bindParam(':service_type_id', $data['service_type_id']);
    $stmt->bindParam(':appliances_type_id', $appliances_type_id);
    $stmt->bindParam(':user_technician', $data['user_technician']);
    $stmt->bindParam(':user_technician_2', $user_technician_2);
    $stmt->bindParam(':technician_justification', $technician_justification);
    $stmt->bindParam(':app_id', $data['app_id']);
    $stmt->bindParam(':user_id', $user_id); // Ensures user can only update their own appointments
    
    // Execute the statement
    $success = $stmt->execute();
    
    // // Check if the appointment was found and updated
    // if ($stmt->rowCount() === 0) {
    //     echo json_encode(['success' => false, 'message' => 'Appointment not found or you do not have permission to update it']);
    //     exit;
    // }
    
    // Update transaction address when customer edits appointment
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
            // Check if transaction address already exists for this appointment
            $checkQuery = $pdo->prepare('SELECT app_id FROM appointment_transaction_address WHERE app_id = :app_id');
            $checkQuery->execute(['app_id' => $data['app_id']]);
            $existingAddress = $checkQuery->fetch();
            
            if ($existingAddress) {
                // Update existing transaction address
                $updateAddressSQL = '
                    UPDATE appointment_transaction_address 
                    SET municipality_city = :municipality_city, 
                        province = :province, 
                        barangay = :barangay, 
                        zip_code = :zip_code, 
                        full_address = :full_address,
                        updated_at = NOW()
                    WHERE app_id = :app_id
                ';
                
                $updateParams = [
                    'app_id' => $data['app_id'],
                    'municipality_city' => $addressData['municipality_city'],
                    'province' => $addressData['province'],
                    'barangay' => $addressData['barangay'],
                    'zip_code' => $addressData['zip_code'],
                    'full_address' => $addressData['house_building_street']
                ];
                
                $updateQuery = $pdo->prepare($updateAddressSQL);
                $updateResult = $updateQuery->execute($updateParams);
                
                if ($updateResult) {
                    error_log("Transaction address updated for appointment {$data['app_id']}");
                }
            } else {
                // Insert new transaction address if it doesn't exist
                $insertAddressSQL = '
                    INSERT INTO appointment_transaction_address 
                    (app_id, user_id, municipality_city, province, barangay, zip_code, full_address, created_by) 
                    VALUES (:app_id, :user_id, :municipality_city, :province, :barangay, :zip_code, :full_address, :created_by)
                ';
                
                $insertParams = [
                    'app_id' => $data['app_id'],
                    'user_id' => $user_id,
                    'municipality_city' => $addressData['municipality_city'],
                    'province' => $addressData['province'],
                    'barangay' => $addressData['barangay'],
                    'zip_code' => $addressData['zip_code'],
                    'full_address' => $addressData['house_building_street'],
                    'created_by' => $user_id
                ];
                
                $insertQuery = $pdo->prepare($insertAddressSQL);
                $insertResult = $insertQuery->execute($insertParams);
                
                if ($insertResult) {
                    error_log("Transaction address created for appointment {$data['app_id']}");
                }
            }
        }
    } catch (PDOException $e) {
        error_log("Error updating transaction address for appointment edit: " . $e->getMessage());
        // Don't fail the appointment update if address storage fails
    }
    
    // Fetch the updated appointment with all relation data including second technician
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
        
    $query->bindParam(':appointment_id', $data['app_id']);
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
    
    // Return success response with appointment data
    echo json_encode([
        'success' => true, 
        'message' => 'Appointment updated successfully', 
        'appointment' => $appointment
    ]);
    
} catch (PDOException $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>