<?php
header('Content-Type: application/json');
include '../../../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

try {
    // Get today's revenue (completed appointments)
    $today_revenue_query = "SELECT COALESCE(SUM(total_cost), 0) as revenue 
                           FROM appointment 
                           WHERE app_status_id = 3 
                             AND payment_status_id = 2
                             AND DATE(appointment_date) = CURDATE()";
    
    $stmt = $pdo->prepare($today_revenue_query);
    $stmt->execute();
    $today_result = $stmt->fetch(PDO::FETCH_ASSOC);
    $today_revenue = $today_result['revenue'];
    
    // Get this week's revenue
    $week_revenue_query = "SELECT COALESCE(SUM(total_cost), 0) as revenue 
                          FROM appointment 
                          WHERE app_status_id = 3 
                            AND payment_status_id = 2
                            AND YEARWEEK(appointment_date, 1) = YEARWEEK(CURDATE(), 1)";
    
    $stmt = $pdo->prepare($week_revenue_query);
    $stmt->execute();
    $week_result = $stmt->fetch(PDO::FETCH_ASSOC);
    $week_revenue = $week_result['revenue'];
    
    // Get pending payments (completed but unpaid appointments)
    $pending_payments_query = "SELECT COALESCE(SUM(total_cost), 0) as pending 
                              FROM appointment 
                              WHERE app_status_id = 3 
                                AND payment_status_id = 1";
    
    $stmt = $pdo->prepare($pending_payments_query);
    $stmt->execute();
    $pending_result = $stmt->fetch(PDO::FETCH_ASSOC);
    $pending_payments = $pending_result['pending'];
    
    // Calculate collection rate (paid vs total completed this week)
    $total_completed_query = "SELECT COALESCE(SUM(total_cost), 0) as total 
                             FROM appointment 
                             WHERE app_status_id = 3 
                               AND YEARWEEK(appointment_date, 1) = YEARWEEK(CURDATE(), 1)";
    
    $stmt = $pdo->prepare($total_completed_query);
    $stmt->execute();
    $total_completed_result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_completed = $total_completed_result['total'];
    
    $collection_rate = 0;
    if ($total_completed > 0) {
        $collection_rate = round(($week_revenue / $total_completed) * 100, 1);
    }
    
    // Set a monthly target (you can make this configurable)
    $monthly_target = 100000; // ₱100,000 monthly target
    
    echo json_encode([
        'success' => true,
        'today_revenue' => (float)$today_revenue,
        'week_revenue' => (float)$week_revenue,
        'pending_payments' => (float)$pending_payments,
        'collection_rate' => (float)$collection_rate,
        'monthly_target' => (float)$monthly_target
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
