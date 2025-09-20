<?php
header('Content-Type: application/json');

include '../../config/ini.php';

try {
    $pdo = pdo_init();
    
    // Get filter parameters
    $period_filter = $_GET['period'] ?? 'all';
    $month_filter = $_GET['month'] ?? 'all_month';
    
    // Build where clause based on filters
    $where_conditions = [];
    $params = [];
    
    // Status filter - only show approved, pending, and in progress appointments
    $where_conditions[] = "a.app_status_id IN (1, 2, 5)";
    
    // Period filter
    if ($period_filter !== 'all') {
        switch ($period_filter) {
            case 'today':
                $where_conditions[] = "DATE(a.app_schedule) = CURDATE()";
                break;
            case 'tomorrow':
                $where_conditions[] = "DATE(a.app_schedule) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
                break;
            case 'this_week':
                $where_conditions[] = "YEARWEEK(a.app_schedule, 1) = YEARWEEK(CURDATE(), 1)";
                break;
            case 'next_week':
                $where_conditions[] = "YEARWEEK(a.app_schedule, 1) = YEARWEEK(DATE_ADD(CURDATE(), INTERVAL 1 WEEK), 1)";
                break;
        }
    }
    
    // Month filter
    if ($month_filter !== 'all_month') {
        // Convert month names to numbers
        $month_map = [
            'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4,
            'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
            'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12
        ];
        
        if (isset($month_map[$month_filter])) {
            $where_conditions[] = "MONTH(a.app_schedule) = :month";
            $params[':month'] = $month_map[$month_filter];
        }
    }
    
    $where_clause = implode(' AND ', $where_conditions);
    
    // Query to get upcoming appointments
    $stmt = $pdo->prepare("
        SELECT 
            a.app_id,
            a.app_schedule,
            a.app_desc,
            a.app_status_id,
            a.payment_status,
            a.app_price,
            a.decline_justification,
            a.app_justification,
            a.app_rating,
            a.app_comment,
            a.service_type_id,
            CONCAT(u.user_name, ' ', COALESCE(u.user_midname, ''), ' ', u.user_lastname) as customer_name,
            u.user_contact as customer_contact,
            u.house_building_street as customer_house_building_street,
            COALESCE(ata.barangay, u.barangay) as customer_barangay,
            COALESCE(ata.municipality_city, u.municipality_city) as customer_municipality_city,
            COALESCE(ata.province, u.province) as customer_province,
            COALESCE(ata.zip_code, u.zip_code) as customer_zip_code,
            st.service_type_name,
            st.service_type_price_min,
            st.service_type_price_max,
            COALESCE(at.appliances_type_name, 'Not Specified') as appliances_type_name,
            CONCAT(t.user_name, ' ', COALESCE(t.user_midname, ''), ' ', t.user_lastname) as technician_name,
            CONCAT(t2.user_name, ' ', COALESCE(t2.user_midname, ''), ' ', t2.user_lastname) as technician_2_name,
            CASE 
                WHEN a.app_status_id = 1 THEN 'Approved'
                WHEN a.app_status_id = 2 THEN 'Pending'
                WHEN a.app_status_id = 3 THEN 'Completed'
                WHEN a.app_status_id = 4 THEN 'Declined'
                WHEN a.app_status_id = 5 THEN 'In Progress'
                ELSE 'Unknown'
            END as status,
            CASE 
                WHEN a.app_status_id = 1 THEN 'success'
                WHEN a.app_status_id = 2 THEN 'warning'
                WHEN a.app_status_id = 3 THEN 'primary'
                WHEN a.app_status_id = 4 THEN 'danger'
                WHEN a.app_status_id = 5 THEN 'info'
                ELSE 'secondary'
            END as status_color
        FROM appointment a
        LEFT JOIN user u ON a.user_id = u.user_id
        LEFT JOIN appointment_transaction_address ata ON a.app_id = ata.app_id
        LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
        LEFT JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
        LEFT JOIN user t ON a.user_technician = t.user_id
        LEFT JOIN user t2 ON a.user_technician_2 = t2.user_id
        WHERE $where_clause
        ORDER BY 
            CASE 
                WHEN a.app_schedule >= NOW() THEN 0 
                ELSE 1 
            END,
            ABS(TIMESTAMPDIFF(SECOND, NOW(), a.app_schedule)) ASC
    ");
    
    $stmt->execute($params);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the appointments for frontend display
    $formatted_appointments = [];
    foreach ($appointments as $app) {
        $schedule = new DateTime($app['app_schedule']);
        
        // Create formatted address string
        $addressParts = [];
        if (!empty($app['customer_house_building_street'])) $addressParts[] = $app['customer_house_building_street'];
        if (!empty($app['customer_barangay'])) $addressParts[] = $app['customer_barangay'];
        if (!empty($app['customer_municipality_city'])) $addressParts[] = $app['customer_municipality_city'];
        if (!empty($app['customer_province'])) $addressParts[] = $app['customer_province'];
        if (!empty($app['customer_zip_code'])) $addressParts[] = $app['customer_zip_code'];
        $customer_address = implode(', ', $addressParts);
        
        $formatted_appointments[] = [
            'app_id' => $app['app_id'],
            'date' => $schedule->format('M j'),
            'time' => $schedule->format('g:i A'),
            'full_date' => $schedule->format('F j, Y'),
            'full_time' => $schedule->format('g:i A'),
            'customer_name' => $app['customer_name'] ?: 'Unknown Customer',
            'customer_contact' => $app['customer_contact'] ?: 'N/A',
            'customer_address' => $customer_address ?: 'N/A',
            'customer_house_building_street' => $app['customer_house_building_street'] ?: '',
            'customer_barangay' => $app['customer_barangay'] ?: '',
            'customer_municipality_city' => $app['customer_municipality_city'] ?: '',
            'customer_province' => $app['customer_province'] ?: '',
            'customer_zip_code' => $app['customer_zip_code'] ?: '',
            'service_type' => $app['service_type_name'] ?: 'Unknown Service',
            'service_type_id' => $app['service_type_id'],
            'service_type_price_min' => $app['service_type_price_min'] ?: '',
            'service_type_price_max' => $app['service_type_price_max'] ?: '',
            'appliances_type' => $app['appliances_type_name'],
            'technician_name' => $app['technician_name'] ?: 'Not Assigned',
            'technician_2_name' => $app['technician_2_name'] ?: null,
            'status' => $app['status'],
            'status_color' => $app['status_color'],
            'app_status_id' => $app['app_status_id'],
            'payment_status' => $app['payment_status'] ?: 'Unpaid',
            'app_price' => $app['app_price'] ?: '',
            'app_justification' => $app['app_justification'] ?: '',
            'decline_justification' => $app['decline_justification'] ?: '',
            'app_rating' => $app['app_rating'] ?: 0,
            'app_comment' => $app['app_comment'] ?: '',
            'description' => $app['app_desc']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'appointments' => $formatted_appointments,
        'count' => count($formatted_appointments)
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching upcoming appointments: ' . $e->getMessage(),
        'appointments' => [],
        'count' => 0
    ]);
}
?>
