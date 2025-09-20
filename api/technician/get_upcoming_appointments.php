<?php
header('Content-Type: application/json');
include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

// Get the technician ID from the session
$technician_id = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;

// Validate the technician ID
if (!$technician_id) {
    echo json_encode([
        'success' => false,
        'message' => 'Technician ID not found in session.'
    ]);
    exit;
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : null;
$period_filter = isset($_GET['period']) ? $_GET['period'] : null;
$month_filter = isset($_GET['month']) ? $_GET['month'] : null;

try {
    // Debug: Check what appointments exist for this technician
    $debug_query = "SELECT app_id, app_schedule, app_status_id, user_technician, user_technician_2 FROM appointment WHERE (user_technician = ? OR user_technician_2 = ?) ORDER BY app_schedule DESC LIMIT 10";
    $debug_stmt = $pdo->prepare($debug_query);
    $debug_stmt->execute([$technician_id, $technician_id]);
    $debug_appointments = $debug_stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("Debug - All appointments for technician " . $technician_id . ": " . json_encode($debug_appointments));
    
    // Base query to get appointments for the current technician
    $query = "
        SELECT 
            a.app_id,
            a.app_schedule,
            a.app_desc,
            a.app_status_id,
            a.user_technician,
            a.user_technician_2,
            a.payment_status,
            st.service_type_name,
            st.service_type_price_min,
            st.service_type_price_max,
            at.appliances_type_name,
            CONCAT(u.user_name, ' ', u.user_midname, ' ', u.user_lastname) as customer_name,
            CONCAT(
                COALESCE(u.house_building_street, ''), 
                CASE WHEN u.house_building_street IS NOT NULL AND COALESCE(ata.barangay, u.barangay) IS NOT NULL THEN ', ' ELSE '' END,
                COALESCE(ata.barangay, u.barangay, ''),
                CASE WHEN COALESCE(ata.barangay, u.barangay) IS NOT NULL AND COALESCE(ata.municipality_city, u.municipality_city) IS NOT NULL THEN ', ' ELSE '' END,
                COALESCE(ata.municipality_city, u.municipality_city, ''),
                CASE WHEN COALESCE(ata.municipality_city, u.municipality_city) IS NOT NULL AND COALESCE(ata.province, u.province) IS NOT NULL THEN ', ' ELSE '' END,
                COALESCE(ata.province, u.province, ''),
                CASE WHEN COALESCE(ata.province, u.province) IS NOT NULL AND COALESCE(ata.zip_code, u.zip_code) IS NOT NULL THEN ' ' ELSE '' END,
                COALESCE(ata.zip_code, u.zip_code, '')
            ) as customer_address,
            CONCAT(u3.user_name, ' ', u3.user_midname, ' ', u3.user_lastname) as primary_technician_name,
            CASE 
                WHEN a.user_technician_2 IS NOT NULL THEN 
                    CONCAT(u2.user_name, ' ', u2.user_midname, ' ', u2.user_lastname)
                ELSE NULL
            END as secondary_technician_name,
            CASE 
                WHEN a.user_technician = ? AND a.user_technician_2 IS NOT NULL THEN 
                    CONCAT(u2.user_name, ' ', u2.user_midname, ' ', u2.user_lastname)
                WHEN a.user_technician_2 = ? AND a.user_technician IS NOT NULL THEN 
                    CONCAT(u3.user_name, ' ', u3.user_midname, ' ', u3.user_lastname)
                ELSE NULL
            END as partner_technician
        FROM appointment a
        LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
        LEFT JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
        LEFT JOIN user u ON a.user_id = u.user_id
        LEFT JOIN appointment_transaction_address ata ON a.app_id = ata.app_id
        LEFT JOIN user u2 ON a.user_technician_2 = u2.user_id
        LEFT JOIN user u3 ON a.user_technician = u3.user_id
        WHERE (a.user_technician = ? OR a.user_technician_2 = ?)
        AND a.app_status_id IN (1, 5)
    ";
    
    $params = [$technician_id, $technician_id, $technician_id, $technician_id];
    
    // Add specific status filter if provided (overrides default filter)
    if ($status_filter !== null && $status_filter !== '') {
        // Remove the default IN (1, 5) filter and replace with specific status
        $query = str_replace("AND a.app_status_id IN (1, 5)", "", $query);
        
        // Handle comma-separated status values (e.g., "1,5")
        if (strpos($status_filter, ',') !== false) {
            $status_array = explode(',', $status_filter);
            $status_array = array_map('intval', $status_array);
            $placeholders = str_repeat('?,', count($status_array) - 1) . '?';
            $query .= " AND a.app_status_id IN ($placeholders)";
            $params = array_merge($params, $status_array);
        } else {
            $query .= " AND a.app_status_id = ?";
            $params[] = intval($status_filter);
        }
    }
    
    // Add period filter
    if ($period_filter) {
        switch ($period_filter) {
            case 'today':
                $query .= " AND DATE(a.app_schedule) = CURDATE()";
                break;
            case 'week':
            case 'this_week':
                $query .= " AND YEARWEEK(a.app_schedule, 1) = YEARWEEK(CURDATE(), 1)";
                break;
        }
    }
    
    // Add month filter
    if ($month_filter && $month_filter !== 'all_month') {
        // Handle both numeric (1-12) and string month inputs
        if (is_numeric($month_filter) && $month_filter >= 1 && $month_filter <= 12) {
            $query .= " AND MONTH(a.app_schedule) = ?";
            $params[] = intval($month_filter);
        } else {
            // Handle month names if needed
            $month_names = [
                'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4,
                'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
                'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12
            ];
            
            if (isset($month_names[strtolower($month_filter)])) {
                $month_number = $month_names[strtolower($month_filter)];
                $query .= " AND MONTH(a.app_schedule) = ?";
                $params[] = $month_number;
            }
        }
    }
    
    // Order by schedule date (nearest to current date first)
    $query .= " ORDER BY ABS(TIMESTAMPDIFF(SECOND, NOW(), a.app_schedule)) ASC, a.app_schedule ASC LIMIT 20";
    
    // Debug logging
    error_log("Final Query: " . $query);
    error_log("Final Params: " . json_encode($params));
    error_log("Technician ID: " . $technician_id);
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("Found appointments: " . count($appointments));
    if (count($appointments) > 0) {
        error_log("First appointment: " . json_encode($appointments[0]));
    }
    
    // Format the appointments data
    $formatted_appointments = [];
    foreach ($appointments as $appointment) {
        $formatted_appointments[] = [
            'app_id' => $appointment['app_id'],
            'app_schedule' => $appointment['app_schedule'],
            'app_desc' => $appointment['app_desc'],
            'app_status_id' => $appointment['app_status_id'],
            'service_type_name' => $appointment['service_type_name'],
            'appliances_type_name' => $appointment['appliances_type_name'],
            'customer_name' => $appointment['customer_name'],
            'customer_address' => $appointment['customer_address'],
            'payment_status' => $appointment['payment_status'],
            'service_type_price_min' => $appointment['service_type_price_min'],
            'service_type_price_max' => $appointment['service_type_price_max'],
            'primary_technician_name' => $appointment['primary_technician_name'],
            'secondary_technician_name' => $appointment['secondary_technician_name'],
            'partner_technician' => $appointment['partner_technician']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'appointments' => $formatted_appointments,
        'count' => count($formatted_appointments)
    ]);
    
} catch (PDOException $e) {
    error_log("Database error in get_upcoming_appointments.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Database error occurred'
    ]);
} catch (Exception $e) {
    error_log("General error in get_upcoming_appointments.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred while fetching appointments'
    ]);
}
?>
