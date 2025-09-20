<?php
header('Content-Type: application/json');

include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

try {
    // Get today's revenue (completed and paid appointments)
    $stmt_today = $pdo->prepare("
        SELECT COALESCE(SUM(CAST(app_price AS DECIMAL(10,2))), 0) as today_revenue 
        FROM appointment 
        WHERE app_id > 0 
        AND app_status_id = 3
        AND payment_status = 'Paid'
        AND DATE(app_completed_at) = CURDATE()
        AND app_price IS NOT NULL 
        AND app_price != ''
    ");
    $stmt_today->execute();
    $today_result = $stmt_today->fetch(PDO::FETCH_ASSOC);
    
    // Get this week's revenue
    $stmt_week = $pdo->prepare("
        SELECT COALESCE(SUM(CAST(app_price AS DECIMAL(10,2))), 0) as week_revenue 
        FROM appointment 
        WHERE app_id > 0 
        AND app_status_id = 3
        AND payment_status = 'Paid'
        AND YEARWEEK(app_completed_at, 1) = YEARWEEK(CURDATE(), 1)
        AND app_price IS NOT NULL 
        AND app_price != ''
    ");
    $stmt_week->execute();
    $week_result = $stmt_week->fetch(PDO::FETCH_ASSOC);
    
    // Get pending payments (completed but unpaid)
    $stmt_pending = $pdo->prepare("
        SELECT COALESCE(SUM(CAST(app_price AS DECIMAL(10,2))), 0) as pending_payments 
        FROM appointment 
        WHERE app_id > 0 
        AND app_status_id = 3
        AND payment_status = 'Unpaid'
        AND app_price IS NOT NULL 
        AND app_price != ''
    ");
    $stmt_pending->execute();
    $pending_result = $stmt_pending->fetch(PDO::FETCH_ASSOC);
    
    // Calculate collection rate (paid vs total completed)
    $stmt_collection = $pdo->prepare("
        SELECT 
            COUNT(CASE WHEN payment_status = 'Paid' THEN 1 END) as paid_count,
            COUNT(*) as total_completed
        FROM appointment 
        WHERE app_id > 0 
        AND app_status_id = 3
        AND app_price IS NOT NULL 
        AND app_price != ''
    ");
    $stmt_collection->execute();
    $collection_result = $stmt_collection->fetch(PDO::FETCH_ASSOC);
    
    $collection_rate = 0;
    if ($collection_result['total_completed'] > 0) {
        $collection_rate = round(($collection_result['paid_count'] / $collection_result['total_completed']) * 100, 1);
    }
    
    // Set a monthly target for progress calculation (can be made configurable later)
    $monthly_target = 50000; // PHP 50,000 monthly target
    
    $response = [
        'success' => true,
        'today_revenue' => (float)$today_result['today_revenue'],
        'week_revenue' => (float)$week_result['week_revenue'],
        'pending_payments' => (float)$pending_result['pending_payments'],
        'collection_rate' => $collection_rate,
        'monthly_target' => $monthly_target,
        'paid_jobs' => (int)$collection_result['paid_count'],
        'total_completed' => (int)$collection_result['total_completed']
    ];

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error fetching financial overview: ' . $e->getMessage(),
        'today_revenue' => 0,
        'week_revenue' => 0,
        'pending_payments' => 0,
        'collection_rate' => 0,
        'monthly_target' => 50000,
        'paid_jobs' => 0,
        'total_completed' => 0
    ];
}

echo json_encode($response);
?>
