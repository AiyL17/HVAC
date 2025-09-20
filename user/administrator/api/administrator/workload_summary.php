<?php
header('Content-Type: application/json');
include '../../../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

try {
    // Get ongoing jobs (accepted appointments for today and future dates)
    $ongoing_query = "SELECT COUNT(*) as count 
                      FROM appointment 
                      WHERE app_status_id = 2 
                        AND appointment_date >= CURDATE()";
    
    $stmt = $pdo->prepare($ongoing_query);
    $stmt->execute();
    $ongoing_result = $stmt->fetch(PDO::FETCH_ASSOC);
    $ongoing_count = $ongoing_result['count'];
    
    // Get pending jobs (pending appointments)
    $pending_query = "SELECT COUNT(*) as count 
                      FROM appointment 
                      WHERE app_status_id = 1";
    
    $stmt = $pdo->prepare($pending_query);
    $stmt->execute();
    $pending_result = $stmt->fetch(PDO::FETCH_ASSOC);
    $pending_count = $pending_result['count'];
    
    echo json_encode([
        'success' => true,
        'ongoing' => (int)$ongoing_count,
        'pending' => (int)$pending_count
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
