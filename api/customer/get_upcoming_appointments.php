<?php
// Prevent any output before JSON
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

include_once __DIR__ . '/../../config/ini.php';

try {
    $pdo = pdo_init();
    $customer_id = $_SESSION['uid'];
    
    // Verify user is a customer by checking database
    $userCheck = $pdo->prepare("SELECT user_type_id FROM user WHERE user_id = ?");
    $userCheck->execute([$customer_id]);
    $user = $userCheck->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || $user['user_type_id'] != 4) { // 4 = customer user type
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Unauthorized access - not a customer']);
        exit;
    }
    
    // Get filter parameters
    $timePeriod = $_GET['timePeriod'] ?? 'all';
    $month = $_GET['month'] ?? 'all';
    $showAll = isset($_GET['show_all']) && $_GET['show_all'] == '1';
    
    // Build WHERE conditions based on filters
    $whereConditions = ["a.user_id = ?"];
    $params = [$customer_id];
    
    // Always filter by status (Approved and In Progress only)
    $whereConditions[] = "a.app_status_id IN (1, 5)"; // 1 = Approved, 5 = In Progress
    // Remove payment status filter to show all appointments regardless of payment status
    
    // Apply time period filter
    switch ($timePeriod) {
        case 'today':
            $whereConditions[] = "DATE(a.app_schedule) = CURDATE()";
            break;
        case 'week':
            $whereConditions[] = "YEARWEEK(a.app_schedule, 1) = YEARWEEK(CURDATE(), 1)";
            break;
        case 'all':
            // No additional time period filter - show all time periods
            break;
    }
    
    // Apply month filter independently
    if ($month !== 'all') {
        $whereConditions[] = "MONTH(a.app_schedule) = ?";
        $params[] = intval($month);
    }
    
    $whereClause = implode(' AND ', $whereConditions);
    
    // Get total count for the current filters
    $countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM appointment a WHERE " . $whereClause);
    $countStmt->execute($params);
    $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Get appointments with filters
    $stmt = $pdo->prepare("
        SELECT 
            a.app_id,
            a.app_schedule,
            a.app_status_id,
            a.app_desc,
            a.app_price,
            a.payment_status,
            st.service_type_name,
            at.appliances_type_name,
            COALESCE(ata.full_address, cust.house_building_street) as customer_house_building_street,
            COALESCE(ata.barangay, cust.barangay) as customer_barangay,
            COALESCE(ata.municipality_city, cust.municipality_city) as customer_municipality_city,
            COALESCE(ata.province, cust.province) as customer_province,
            COALESCE(ata.zip_code, cust.zip_code) as customer_zip_code,
            CONCAT(TRIM(u1.user_name), ' ', 
                   CASE WHEN TRIM(u1.user_midname) != '' THEN CONCAT(TRIM(u1.user_midname), ' ') ELSE '' END,
                   TRIM(u1.user_lastname)) as primary_technician_name,
            CONCAT(TRIM(u2.user_name), ' ', 
                   CASE WHEN TRIM(u2.user_midname) != '' THEN CONCAT(TRIM(u2.user_midname), ' ') ELSE '' END,
                   TRIM(u2.user_lastname)) as secondary_technician_name,
            u1.user_contact as primary_technician_contact,
            u2.user_contact as secondary_technician_contact,
            CASE 
                WHEN a.app_status_id = 1 THEN 'Approved'
                WHEN a.app_status_id = 2 THEN 'Pending'
                WHEN a.app_status_id = 3 THEN 'Completed'
                WHEN a.app_status_id = 4 THEN 'Declined'
                WHEN a.app_status_id = 5 THEN 'In Progress'
                ELSE 'Unknown'
            END as status_name
        FROM appointment a
        JOIN service_type st ON a.service_type_id = st.service_type_id
        LEFT JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
        LEFT JOIN user cust ON a.user_id = cust.user_id
        LEFT JOIN appointment_transaction_address ata ON a.app_id = ata.app_id
        LEFT JOIN user u1 ON a.user_technician = u1.user_id
        LEFT JOIN user u2 ON a.user_technician_2 = u2.user_id
        WHERE $whereClause
        ORDER BY 
            CASE 
                WHEN a.app_schedule >= CURDATE() THEN 0 
                ELSE 1 
            END,
            ABS(DATEDIFF(a.app_schedule, CURDATE())),
            a.app_schedule ASC
    ");
    
    $stmt->execute($params);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Clean any output buffer before sending JSON
    ob_clean();
    echo json_encode([
        'success' => true,
        'appointments' => $appointments,
        'count' => (int)$totalCount
    ]);
    
} catch (Exception $e) {
    error_log("Error fetching upcoming appointments: " . $e->getMessage());
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch upcoming appointments'
    ]);
}
?>
