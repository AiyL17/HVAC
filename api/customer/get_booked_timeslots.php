<?php
session_start();
require_once '../../config/connection.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if (!isset($_GET['date'])) {
    echo json_encode(['success' => false, 'message' => 'Date is required']);
    exit;
}

$date = $_GET['date'];

try {
    // Get all booked time slots for the specified date
    $query = $pdo->prepare("
        SELECT TIME(app_schedule) as booked_time
        FROM appointment 
        WHERE DATE(app_schedule) = ? 
        AND app_status NOT IN ('cancelled', 'rejected')
    ");
    $query->execute([$date]);
    $bookedSlots = $query->fetchAll(PDO::FETCH_COLUMN);
    
    // Format the times to match our time slot format (HH:MM)
    $formattedBookedSlots = array_map(function($time) {
        return substr($time, 0, 5); // Get HH:MM format
    }, $bookedSlots);
    
    echo json_encode(['success' => true, 'booked_slots' => $formattedBookedSlots]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch booked time slots']);
}
?>
