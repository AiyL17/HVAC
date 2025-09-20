<?php
header('Content-Type: application/json');

include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

try {
    // Get in progress jobs (only in progress appointments)
    $stmt_ongoing = $pdo->prepare("
        SELECT COUNT(*) as ongoing_count 
        FROM appointment 
        WHERE app_id > 0 
        AND app_status_id = 5
        AND app_schedule >= NOW()
    ");
    $stmt_ongoing->execute();
    $ongoing_result = $stmt_ongoing->fetch(PDO::FETCH_ASSOC);
    
    // Get pending jobs (pending approval appointments)
    $stmt_pending = $pdo->prepare("
        SELECT COUNT(*) as pending_count 
        FROM appointment 
        WHERE app_id > 0 
        AND app_status_id = 2
    ");
    $stmt_pending->execute();
    $pending_result = $stmt_pending->fetch(PDO::FETCH_ASSOC);
    
    $response = [
        'success' => true,
        'ongoing' => (int)$ongoing_result['ongoing_count'],
        'pending' => (int)$pending_result['pending_count']
    ];

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error fetching workload summary: ' . $e->getMessage(),
        'ongoing' => 0,
        'pending' => 0
    ];
}

echo json_encode($response);
?>
