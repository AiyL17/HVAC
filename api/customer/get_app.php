<?php
header('Content-Type: application/json');

include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

// Get the type from the query parameters
$type = isset($_GET['type']) ? $_GET['type'] : 'all';
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$service_search = isset($_GET['service_search']) ? trim($_GET['service_search']) : '';
$payment_status = isset($_GET['payment_status']) ? $_GET['payment_status'] : 'all';

$offset = ($page - 1) * $limit;

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['uid'];

// Prepare and execute the query to fetch appointments
try {
    // First, get the total count for pagination
    $whereConditions = ['a.user_id = :user_id'];
    $params = [':user_id' => $user_id];
    
    if ($type != 'all') {
        $whereConditions[] = 'a.app_status_id = :type';
        $params[':type'] = $type;
    }
    
    if (!empty($service_search)) {
        $whereConditions[] = 's.service_type_name LIKE :service_search';
        $params[':service_search'] = '%' . $service_search . '%';
    }
    
    if ($payment_status != 'all') {
        $whereConditions[] = 'a.payment_status = :payment_status';
        $params[':payment_status'] = $payment_status;
    }
    
    $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
    
    $countSql = "SELECT COUNT(*) as total FROM appointment a 
                 LEFT JOIN service_type s ON a.service_type_id = s.service_type_id 
                 $whereClause";
    
    $countQuery = $pdo->prepare($countSql);
    foreach ($params as $key => $value) {
        $countQuery->bindValue($key, $value);
    }
    $countQuery->execute();
    $totalRecords = $countQuery->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Calculate pagination info
    $totalPages = ceil($totalRecords / $limit);
    $hasNextPage = $page < $totalPages;
    $hasPrevPage = $page > 1;
    
    // Now get the paginated results - use unified query with dynamic WHERE clause
    $mainSql = "SELECT
        a.*,
        COALESCE(a_s.app_status_name, 'Unknown Status') as app_status_name,
        COALESCE(u.user_name, 'Unknown') as user_name, 
        COALESCE(u.user_midname, '') as user_midname, 
        COALESCE(u.user_lastname, 'User') as user_lastname,
        COALESCE(ata.full_address, u.house_building_street) as customer_house_building_street,
        COALESCE(ata.barangay, u.barangay) as customer_barangay,
        COALESCE(ata.municipality_city, u.municipality_city) as customer_municipality_city,
        COALESCE(ata.province, u.province) as customer_province,
        COALESCE(ata.zip_code, u.zip_code) as customer_zip_code,
        COALESCE(ut.user_name, 'Unknown') AS tech_name,
        COALESCE(ut.user_midname, '') AS tech_midname,
        COALESCE(ut.user_lastname, 'Technician') AS tech_lastname,
        COALESCE(ut2.user_name, '') AS tech2_name,
        COALESCE(ut2.user_midname, '') AS tech2_midname,
        COALESCE(ut2.user_lastname, '') AS tech2_lastname,
        COALESCE(s.service_type_name, 'Unknown Service') as service_type_name,
        COALESCE(at.appliances_type_name, 'Not Specified') as appliances_type_name
    FROM
        appointment a
    LEFT JOIN
        appointment_status a_s ON a.app_status_id = a_s.app_status_id
    LEFT JOIN
        user u ON a.user_id = u.user_id
    LEFT JOIN
        appointment_transaction_address ata ON a.app_id = ata.app_id
    LEFT JOIN
        user ut ON a.user_technician = ut.user_id
    LEFT JOIN
        user ut2 ON a.user_technician_2 = ut2.user_id
    LEFT JOIN
        service_type s ON a.service_type_id = s.service_type_id
    LEFT JOIN
        appliances_type at ON a.appliances_type_id = at.appliances_type_id
    $whereClause
    ORDER BY 
        a.app_created DESC, a.app_id DESC
    LIMIT :limit OFFSET :offset";
    
    $query = $pdo->prepare($mainSql);
    
    // Bind all parameters
    foreach ($params as $key => $value) {
        $query->bindValue($key, $value);
    }
    $query->bindValue(':limit', $limit, PDO::PARAM_INT);
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $query->execute();
    $appointments = $query->fetchAll(PDO::FETCH_ASSOC);
    
    // Format dates for better client-side handling
    foreach ($appointments as &$app) {
        // Convert timestamps to ISO format for easier JavaScript parsing
        if (isset($app['app_schedule'])) {
            $schedule = new DateTime($app['app_schedule']);
            $app['app_schedule'] = $schedule->format('c'); // ISO 8601 format
        }
        
        if (isset($app['app_created'])) {
            $created = new DateTime($app['app_created']);
            $app['app_created'] = $created->format('c'); // ISO 8601 format
        }
    }
    
    // Prepare successful response with pagination info
    $response = [
        'success' => true,
        'appointments' => $appointments,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_records' => $totalRecords,
            'total_pages' => $totalPages,
            'has_next_page' => $hasNextPage,
            'has_prev_page' => $hasPrevPage,
            'showing_from' => $totalRecords > 0 ? $offset + 1 : 0,
            'showing_to' => min($offset + $limit, $totalRecords)
        ],
        'count' => count($appointments)
    ];
} catch (PDOException $e) {
    // Handle database errors
    $response = [
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ];
}

// Return the response as JSON
echo json_encode($response);
?>