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

try {
    // Get today's tasks
    $today_query = "
        SELECT COUNT(*) as count
        FROM appointment 
        WHERE (user_technician = ? OR user_technician_2 = ?)
        AND DATE(app_schedule) = CURDATE()
        AND app_status_id IN (1, 2, 5)
    ";
    $today_stmt = $pdo->prepare($today_query);
    $today_stmt->execute([$technician_id, $technician_id]);
    $today_tasks = $today_stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Get this week's tasks
    $this_week_query = "
        SELECT COUNT(*) as count
        FROM appointment 
        WHERE (user_technician = ? OR user_technician_2 = ?)
        AND YEARWEEK(app_schedule, 1) = YEARWEEK(CURDATE(), 1)
        AND app_status_id IN (1, 2, 5)
    ";
    $this_week_stmt = $pdo->prepare($this_week_query);
    $this_week_stmt->execute([$technician_id, $technician_id]);
    $this_week_tasks = $this_week_stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Get next week's tasks
    $next_week_query = "
        SELECT COUNT(*) as count
        FROM appointment 
        WHERE (user_technician = ? OR user_technician_2 = ?)
        AND YEARWEEK(app_schedule, 1) = YEARWEEK(DATE_ADD(CURDATE(), INTERVAL 1 WEEK), 1)
        AND app_status_id IN (1, 2, 5)
    ";
    $next_week_stmt = $pdo->prepare($next_week_query);
    $next_week_stmt->execute([$technician_id, $technician_id]);
    $next_week_tasks = $next_week_stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Get availability status
    $availability_query = "
        SELECT availability_status
        FROM user 
        WHERE user_id = ?
    ";
    $availability_stmt = $pdo->prepare($availability_query);
    $availability_stmt->execute([$technician_id]);
    $availability = $availability_stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'workload' => [
            'today_tasks' => (int)$today_tasks,
            'this_week' => (int)$this_week_tasks,
            'next_week' => (int)$next_week_tasks,
            'availability_status' => ucfirst($availability['availability_status'] ?? 'available')
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Database error in get_workload_summary.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Database error occurred'
    ]);
} catch (Exception $e) {
    error_log("General error in get_workload_summary.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred while fetching workload data'
    ]);
}
?>
