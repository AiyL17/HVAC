<?php
header('Content-Type: application/json');

include '../../config/ini.php';
require_once '../../class/notificationClass.php';

// Initialize the database connection
$pdo = pdo_init();

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['uid'];

// Get the POST data (from fetch API JSON)
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Check if app_id is present
if (!isset($data['app_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing appointment ID']);
    exit;
}

$app_id = $data['app_id'];

// Validate app_id belongs to the logged-in user and is in Approval status
try {
    $check_query = $pdo->prepare('SELECT user_id, app_status_id FROM appointment WHERE app_id = :app_id');
    $check_query->bindParam(':app_id', $app_id);
    $check_query->execute();
    $appointment = $check_query->fetch(PDO::FETCH_ASSOC);
    
    if (!$appointment) {
        echo json_encode(['success' => false, 'message' => 'Appointment not found']);
        exit;
    }
    
    if ($appointment['user_id'] != $user_id) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access to this appointment']);
        exit;
    }
    
    // Check if the appointment is in "Pending" status (2)
    if ($appointment['app_status_id'] != 2) {
        echo json_encode(['success' => false, 'message' => 'Only pending appointments can be cancelled']);
        exit;
    }
    
    // Update appointment status to cancelled (10 - Cancelled status ID)
    $update_query = $pdo->prepare('UPDATE appointment 
        SET app_status_id = 10 
        WHERE app_id = :app_id');
    $update_query->bindParam(':app_id', $app_id);
    $update_query->execute();
    
    // Create notification for administrators
    try {
        $notificationHandler = new NotificationHandler($pdo);
        $notificationResult = $notificationHandler->createStatusChangeNotification(
            $app_id,
            'Pending',
            'Cancelled',
            $user_id
        );
        
        // Log notification result for debugging (optional)
        if (!$notificationResult['success']) {
            error_log('Failed to create cancellation notification: ' . $notificationResult['message']);
        }
    } catch (Exception $e) {
        // Log error but don't fail the cancellation
        error_log('Error creating cancellation notification: ' . $e->getMessage());
    }
    
    // Return success response
    echo json_encode(['success' => true, 'message' => 'Appointment cancelled successfully']);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>