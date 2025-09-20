<?php
header('Content-Type: application/json');

include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

try {
    // Get jobs completed today
    $stmt_today = $pdo->prepare("
        SELECT COUNT(*) as jobs_today 
        FROM appointment 
        WHERE app_id > 0 
        AND app_status_id = 3
        AND DATE(app_completed_at) = CURDATE()
    ");
    $stmt_today->execute();
    $today_result = $stmt_today->fetch(PDO::FETCH_ASSOC);
    
    // Get jobs completed this week
    $stmt_week = $pdo->prepare("
        SELECT COUNT(*) as jobs_week 
        FROM appointment 
        WHERE app_id > 0 
        AND app_status_id = 3
        AND YEARWEEK(app_completed_at, 1) = YEARWEEK(CURDATE(), 1)
    ");
    $stmt_week->execute();
    $week_result = $stmt_week->fetch(PDO::FETCH_ASSOC);
    
    // Get top performer of the day (technician with most completed jobs today)
    $stmt_top = $pdo->prepare("
        SELECT 
            CONCAT(u.user_name, ' ', u.user_midname, ' ', u.user_lastname) as technician_name,
            COUNT(*) as completed_today
        FROM appointment a
        LEFT JOIN user u ON a.user_technician = u.user_id
        WHERE a.app_id > 0 
        AND a.app_status_id = 3
        AND DATE(a.app_completed_at) = CURDATE()
        AND u.user_type_id = 2
        GROUP BY a.user_technician, u.user_name, u.user_midname, u.user_lastname
        ORDER BY completed_today DESC
        LIMIT 1
    ");
    $stmt_top->execute();
    $top_result = $stmt_top->fetch(PDO::FETCH_ASSOC);
    
    $response = [
        'success' => true,
        'jobs_today' => (int)$today_result['jobs_today'],
        'jobs_week' => (int)$week_result['jobs_week'],
        'top_performer' => $top_result ? [
            'name' => $top_result['technician_name'],
            'completed_today' => (int)$top_result['completed_today']
        ] : [
            'name' => 'No completed jobs today',
            'completed_today' => 0
        ]
    ];

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error fetching technician performance: ' . $e->getMessage(),
        'jobs_today' => 0,
        'jobs_week' => 0,
        'top_performer' => [
            'name' => 'Error loading data',
            'completed_today' => 0
        ]
    ];
}

echo json_encode($response);
?>
