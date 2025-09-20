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

// Validate app_id belongs to the logged-in user
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
    
    // Check if the appointment is in "To Rate" status (6)
    if ($appointment['app_status_id'] != 6) {
        echo json_encode(['success' => false, 'message' => 'This appointment cannot be marked as complete']);
        exit;
    }
    
    // Update appointment status to completed (3)
    $update_query = $pdo->prepare('UPDATE appointment 
        SET app_status_id = 3, app_completed_at = NOW() 
        WHERE app_id = :app_id');
    $update_query->bindParam(':app_id', $app_id);
    $update_query->execute();
    
    // Return success response
    echo json_encode(['success' => true, 'message' => 'Appointment marked as complete']);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>