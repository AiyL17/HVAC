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

try {
    // Get availability data for the technician from user table
    $sql = "SELECT availability_status, availability_date, availability_start_time, availability_end_time, availability_notes 
            FROM user 
            WHERE user_id = ? AND user_type_id = 2";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    
    $availability = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($availability) {
        echo json_encode([
            'success' => true,
            'data' => [
                'status' => $availability['availability_status'],
                'date' => $availability['availability_date'],
                'start_time' => $availability['availability_start_time'],
                'end_time' => $availability['availability_end_time'],
                'notes' => $availability['availability_notes']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Technician not found'
        ]);
    }
    
} catch (PDOException $e) {
    error_log("Database error in get_availability.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
} catch (Exception $e) {
    error_log("General error in get_availability.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while loading availability data']);
}
?>
