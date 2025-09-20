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

$technician_id = $_SESSION['uid'];

// Debug: Add logging to track technician ID and session
error_log("Performance Stats API - Technician ID: " . $technician_id);
error_log("Performance Stats API - Session UID: " . $_SESSION['uid']);

try {
    // Debug: Check if technician exists in database
    $technicianCheckStmt = $pdo->prepare("SELECT user_id, user_name, user_lastname FROM user WHERE user_id = :technician_id");
    $technicianCheckStmt->bindParam(':technician_id', $technician_id);
    $technicianCheckStmt->execute();
    $technicianInfo = $technicianCheckStmt->fetch(PDO::FETCH_ASSOC);
    error_log("Performance Stats API - Technician Info: " . json_encode($technicianInfo));
    
    // Debug: Check all appointments for this technician
    $allAppointmentsStmt = $pdo->prepare("SELECT COUNT(*) as all_appointments FROM appointment WHERE user_technician = :technician_id");
    $allAppointmentsStmt->bindParam(':technician_id', $technician_id);
    $allAppointmentsStmt->execute();
    $allAppointments = $allAppointmentsStmt->fetch(PDO::FETCH_ASSOC);
    error_log("Performance Stats API - All appointments for technician: " . json_encode($allAppointments));
    
    // Get basic task counts (using same logic as app_count.php)
    // Total tasks = pending tasks (status 1)
    $totalTasksStmt = $pdo->prepare("
        SELECT COUNT(*) as total_tasks
        FROM appointment 
        WHERE user_technician = :technician_id AND app_status_id = 1
    ");
    $totalTasksStmt->bindParam(':technician_id', $technician_id);
    $totalTasksStmt->execute();
    $totalResult = $totalTasksStmt->fetch(PDO::FETCH_ASSOC);
    
    // In progress tasks (status 5)
    $inProgressStmt = $pdo->prepare("
        SELECT COUNT(*) as in_progress_tasks
        FROM appointment 
        WHERE user_technician = :technician_id AND app_status_id = 5
    ");
    $inProgressStmt->bindParam(':technician_id', $technician_id);
    $inProgressStmt->execute();
    $inProgressResult = $inProgressStmt->fetch(PDO::FETCH_ASSOC);
    
    // Completed tasks (status 3)
    $completedStmt = $pdo->prepare("
        SELECT COUNT(*) as completed_tasks
        FROM appointment 
        WHERE user_technician = :technician_id AND app_status_id = 3
    ");
    $completedStmt->bindParam(':technician_id', $technician_id);
    $completedStmt->execute();
    $completedResult = $completedStmt->fetch(PDO::FETCH_ASSOC);
    
    // Get average rating from completed tasks
    $ratingStmt = $pdo->prepare("
        SELECT AVG(CASE WHEN app_rating IS NOT NULL AND app_rating > 0 THEN app_rating ELSE NULL END) as average_rating
        FROM appointment 
        WHERE user_technician = :technician_id AND app_status_id = 3
    ");
    $ratingStmt->bindParam(':technician_id', $technician_id);
    $ratingStmt->execute();
    $ratingResult = $ratingStmt->fetch(PDO::FETCH_ASSOC);
    
    // Combine results
    $stats = [
        'total_tasks' => $totalResult['total_tasks'],
        'in_progress_tasks' => $inProgressResult['in_progress_tasks'], 
        'completed_tasks' => $completedResult['completed_tasks'],
        'average_rating' => $ratingResult['average_rating']
    ];

    // Calculate completion rate (completed vs all tasks)
    // Get total count of ALL tasks for completion rate calculation
    $allTasksStmt = $pdo->prepare("
        SELECT COUNT(*) as all_tasks
        FROM appointment 
        WHERE user_technician = :technician_id
    ");
    $allTasksStmt->bindParam(':technician_id', $technician_id);
    $allTasksStmt->execute();
    $allTasksResult = $allTasksStmt->fetch(PDO::FETCH_ASSOC);
    $allTasks = (int)($allTasksResult['all_tasks'] ?? 0);
    
    $totalTasks = (int)($stats['total_tasks'] ?? 0);
    $completedTasks = (int)($stats['completed_tasks'] ?? 0);
    $completionRate = $allTasks > 0 ? round(($completedTasks / $allTasks) * 100, 1) : 0;

    // Get this month's tasks
    $thisMonthStats = $pdo->prepare("
        SELECT COUNT(*) as this_month_tasks
        FROM appointment 
        WHERE user_technician = :technician_id 
        AND MONTH(app_schedule) = MONTH(CURDATE()) 
        AND YEAR(app_schedule) = YEAR(CURDATE())
        AND app_status_id = 3
    ");
    $thisMonthStats->bindParam(':technician_id', $technician_id);
    $thisMonthStats->execute();
    $thisMonth = $thisMonthStats->fetch(PDO::FETCH_ASSOC);

    // Get last month's tasks
    $lastMonthStats = $pdo->prepare("
        SELECT COUNT(*) as last_month_tasks
        FROM appointment 
        WHERE user_technician = :technician_id 
        AND MONTH(app_schedule) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
        AND YEAR(app_schedule) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
        AND app_status_id = 3
    ");
    $lastMonthStats->bindParam(':technician_id', $technician_id);
    $lastMonthStats->execute();
    $lastMonth = $lastMonthStats->fetch(PDO::FETCH_ASSOC);

    // Calculate monthly growth
    $thisMonthTasks = (int)($thisMonth['this_month_tasks'] ?? 0);
    $lastMonthTasks = (int)($lastMonth['last_month_tasks'] ?? 0);
    $monthlyGrowth = 0;
    if ($lastMonthTasks > 0) {
        $monthlyGrowth = round((($thisMonthTasks - $lastMonthTasks) / $lastMonthTasks) * 100, 1);
    } elseif ($thisMonthTasks > 0) {
        $monthlyGrowth = 100;
    }

    // Get service type breakdown
    $serviceBreakdown = $pdo->prepare("
        SELECT 
            st.service_type_name as service_type,
            COUNT(*) as count
        FROM appointment a
        JOIN service_type st ON a.service_type_id = st.service_type_id
        WHERE a.user_technician = :technician_id
        GROUP BY st.service_type_id, st.service_type_name
        ORDER BY count DESC
        LIMIT 5
    ");
    $serviceBreakdown->bindParam(':technician_id', $technician_id);
    $serviceBreakdown->execute();
    $serviceData = $serviceBreakdown->fetchAll(PDO::FETCH_ASSOC);

    // Get recent activity
    $recentActivity = $pdo->prepare("
        SELECT 
            st.service_type_name as service_type,
            CASE 
                WHEN a.app_status_id = 3 THEN 'Completed'
                WHEN a.app_status_id = 5 THEN 'In Progress'
                ELSE 'Pending'
            END as status,
            a.app_schedule as created_date
        FROM appointment a
        JOIN service_type st ON a.service_type_id = st.service_type_id
        WHERE a.user_technician = :technician_id
        ORDER BY a.app_schedule DESC
        LIMIT 5
    ");
    $recentActivity->bindParam(':technician_id', $technician_id);
    $recentActivity->execute();
    $activityData = $recentActivity->fetchAll(PDO::FETCH_ASSOC);

    // Get monthly breakdown for completed appointments (Jan-Dec)
    $monthlyBreakdown = [];
    $months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
    
    error_log("Performance Stats API - Starting monthly breakdown queries for technician: " . $technician_id);
    
    for ($i = 1; $i <= 12; $i++) {
        $monthlyStats = $pdo->prepare("
            SELECT COUNT(*) as month_completed
            FROM appointment 
            WHERE user_technician = :technician_id 
            AND MONTH(app_schedule) = :month_num
            AND YEAR(app_schedule) = YEAR(CURDATE())
            AND app_status_id = 3
        ");
        $monthlyStats->bindParam(':technician_id', $technician_id);
        $monthlyStats->bindParam(':month_num', $i);
        $monthlyStats->execute();
        $monthResult = $monthlyStats->fetch(PDO::FETCH_ASSOC);
        
        $monthlyBreakdown[$months[$i-1] . '_completed'] = (int)($monthResult['month_completed'] ?? 0);
        
        // Debug: Log monthly results
        if ($monthResult['month_completed'] > 0) {
            error_log("Performance Stats API - Month " . $i . " (" . $months[$i-1] . "): " . $monthResult['month_completed'] . " completed appointments");
        }
    }
    
    error_log("Performance Stats API - Monthly breakdown result: " . json_encode($monthlyBreakdown));

    // Get payment status (if payment tracking exists)
    $paymentStats = $pdo->prepare("
        SELECT 
            SUM(CASE WHEN payment_status = 'Paid' OR payment_status = 1 THEN 1 ELSE 0 END) as paid_tasks,
            SUM(CASE WHEN payment_status = 'Unpaid' OR payment_status = 0 OR payment_status IS NULL THEN 1 ELSE 0 END) as unpaid_tasks
        FROM appointment 
        WHERE user_technician = :technician_id
        AND app_status_id = 3
    ");
    $paymentStats->bindParam(':technician_id', $technician_id);
    $paymentStats->execute();
    $paymentData = $paymentStats->fetch(PDO::FETCH_ASSOC);

    // Prepare comprehensive response
    $response = [
        'success' => true,
        'debug_technician_id' => $technician_id, // Debug info
        'debug_all_tasks_count' => $allTasks, // Debug info
        'total_tasks' => $totalTasks,
        'in_progress_tasks' => (int)($stats['in_progress_tasks'] ?? 0),
        'completed_tasks' => $completedTasks,
        'average_rating' => round((float)($stats['average_rating'] ?? 0), 1),
        'completion_rate' => $completionRate,
        'this_month_tasks' => $thisMonthTasks,
        'last_month_tasks' => $lastMonthTasks,
        'monthly_growth' => $monthlyGrowth,
        'paid_tasks' => (int)($paymentData['paid_tasks'] ?? 0),
        'unpaid_tasks' => (int)($paymentData['unpaid_tasks'] ?? 0),
        'service_breakdown' => $serviceData,
        'recent_activity' => $activityData
    ];
    
    // Add monthly breakdown data to response
    $response = array_merge($response, $monthlyBreakdown);

} catch (PDOException $e) {
    // Handle database errors
    $response = [
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ];
    error_log("Technician performance stats error: " . $e->getMessage());
}

// Return the response as JSON
echo json_encode($response);
?>