<?php
session_start();
require_once '../../config/ini.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in and is a technician
if (!isset($_SESSION['uid']) || $_SESSION['user_type'] !== 'technician') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['appointment_id']) || !isset($input['new_status'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit();
}

$appointment_id = intval($input['appointment_id']);
$new_status = intval($input['new_status']);
$technician_id = $_SESSION['uid'];

// Validate that the new status is "In Progress" (status ID 5)
if ($new_status !== 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid status update']);
    exit();
}

try {
    // Debug: Log the input parameters
    error_log("Debug: appointment_id = $appointment_id, technician_id = $technician_id, new_status = $new_status");
    
    // First, check if appointment exists at all
    $debugQuery = $pdo->prepare("
        SELECT app_id, app_status_id, user_technician 
        FROM appointment 
        WHERE app_id = ?
    ");
    $debugQuery->execute([$appointment_id]);
    $debugResult = $debugQuery->fetch(PDO::FETCH_OBJ);
    
    if (!$debugResult) {
        echo json_encode(['success' => false, 'message' => 'Appointment not found in database']);
        exit();
    }
    
    error_log("Debug: Found appointment - ID: {$debugResult->app_id}, Status: {$debugResult->app_status_id}, Technician: {$debugResult->user_technician}");
    
    // Now verify that the appointment belongs to the current technician and is currently approved (status 1)
    $checkQuery = $pdo->prepare("
        SELECT app_id, app_status_id 
        FROM appointment 
        WHERE app_id = ? AND user_technician = ? AND app_status_id = 1
    ");
    $checkQuery->execute([$appointment_id, $technician_id]);
    $appointment = $checkQuery->fetch(PDO::FETCH_OBJ);
    
    if (!$appointment) {
        echo json_encode(['success' => false, 'message' => "Appointment not found or not eligible. Current status: {$debugResult->app_status_id}, Expected technician: $technician_id, Actual technician: {$debugResult->user_technician}"]);
        exit();
    }
    
    // Update the appointment status to "In Progress" (status ID 5)
    $updateQuery = $pdo->prepare("
        UPDATE appointment 
        SET app_status_id = ?, app_updated = NOW() 
        WHERE app_id = ? AND user_technician = ?
    ");
    $result = $updateQuery->execute([$new_status, $appointment_id, $technician_id]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Appointment status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update appointment status']);
    }
    
} catch (PDOException $e) {
    error_log("Database error in update_appointment_status.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>
