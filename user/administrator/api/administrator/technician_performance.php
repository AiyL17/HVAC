<?php
header('Content-Type: application/json');
include '../../../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

try {
    // Get jobs completed today
    $today_query = "SELECT COUNT(*) as count 
                    FROM appointment 
                    WHERE app_status_id = 3 
                      AND DATE(appointment_date) = CURDATE()";
    
    $stmt = $pdo->prepare($today_query);
    $stmt->execute();
    $today_result = $stmt->fetch(PDO::FETCH_ASSOC);
    $jobs_today = $today_result['count'];
    
    // Get jobs completed this week
    $week_query = "SELECT COUNT(*) as count 
                   FROM appointment 
                   WHERE app_status_id = 3 
                     AND YEARWEEK(appointment_date, 1) = YEARWEEK(CURDATE(), 1)";
    
    $stmt = $pdo->prepare($week_query);
    $stmt->execute();
    $week_result = $stmt->fetch(PDO::FETCH_ASSOC);
    $jobs_week = $week_result['count'];
    
    // Get top performer today
    $top_performer_query = "SELECT 
                              CONCAT(u.user_name, ' ', u.user_midname, ' ', u.user_lastname) as name,
                              COUNT(*) as jobs_completed
                            FROM appointment a
                            JOIN user u ON a.technician_id = u.user_id
                            WHERE a.app_status_id = 3 
                              AND DATE(a.appointment_date) = CURDATE()
                              AND u.user_type_id = 3
                            GROUP BY a.technician_id, u.user_name, u.user_midname, u.user_lastname
                            ORDER BY jobs_completed DESC
                            LIMIT 1";
    
    $stmt = $pdo->prepare($top_performer_query);
    $stmt->execute();
    $top_performer_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $top_performer = null;
    
    if (count($top_performer_results) > 0) {
        $top_performer = $top_performer_results[0];
        $top_performer['name'] = trim($top_performer['name']);
    }
    
    echo json_encode([
        'success' => true,
        'jobs_today' => (int)$jobs_today,
        'jobs_week' => (int)$jobs_week,
        'top_performer' => $top_performer
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
