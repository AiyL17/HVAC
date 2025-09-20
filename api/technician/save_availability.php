<?php
session_start();
include '../../config/ini.php';
$pdo = pdo_init();

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$user_id = $_SESSION['uid'];

// Verify user is a technician by checking user_type_id in database
$userCheckSql = "SELECT user_type_id FROM user WHERE user_id = ?";
$userCheckStmt = $pdo->prepare($userCheckSql);
$userCheckStmt->execute([$user_id]);
$userType = $userCheckStmt->fetch(PDO::FETCH_OBJ);

if (!$userType || $userType->user_type_id != 2) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access - not a technician']);
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($input['date']) || !isset($input['start_time']) || !isset($input['end_time']) || !isset($input['status'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$user_id = $_SESSION['uid'];
$availability_date = $input['date'];
$start_time = $input['start_time'];
$end_time = $input['end_time'];
$status = $input['status'];

// Validate date format
if (!DateTime::createFromFormat('Y-m-d', $availability_date)) {
    echo json_encode(['success' => false, 'message' => 'Invalid date format']);
    exit;
}

// Validate time format
if (!DateTime::createFromFormat('H:i', $start_time) || !DateTime::createFromFormat('H:i', $end_time)) {
    echo json_encode(['success' => false, 'message' => 'Invalid time format']);
    exit;
}

// Validate that end time is after start time
$start_datetime = DateTime::createFromFormat('H:i', $start_time);
$end_datetime = DateTime::createFromFormat('H:i', $end_time);

if ($end_datetime <= $start_datetime) {
    echo json_encode(['success' => false, 'message' => 'End time must be after start time']);
    exit;
}

// Validate status
if (!in_array($status, ['available', 'unavailable'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid status value']);
    exit;
}

// Validate that date is not in the past
$selected_date = DateTime::createFromFormat('Y-m-d', $availability_date);
$today = new DateTime();
$today->setTime(0, 0, 0);

if ($selected_date < $today) {
    echo json_encode(['success' => false, 'message' => 'Cannot set availability for past dates']);
    exit;
}

// Validate that date is a weekday (Monday to Friday)
$day_of_week = $selected_date->format('N'); // 1 = Monday, 7 = Sunday
if ($day_of_week > 5) {
    echo json_encode(['success' => false, 'message' => 'Availability can only be set for weekdays (Monday to Friday)']);
    exit;
}

try {
    // Update technician availability in user table
    $update_sql = "UPDATE user 
                  SET availability_status = ?, 
                      availability_date = ?, 
                      availability_start_time = ?, 
                      availability_end_time = ?,
                      last_activity = CURRENT_TIMESTAMP
                  WHERE user_id = ? AND user_type_id = 2";
    
    $update_stmt = $pdo->prepare($update_sql);
    $result = $update_stmt->execute([$status, $availability_date, $start_time, $end_time, $user_id]);
    
    if ($result && $update_stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Availability updated successfully',
            'data' => [
                'date' => $availability_date,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'status' => $status
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update availability or no changes made']);
    }
    
} catch (PDOException $e) {
    error_log("Database error in save_availability.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
} catch (Exception $e) {
    error_log("General error in save_availability.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while saving availability']);
}
?>
