<?php
session_start();
require_once '../../config/ini.php';
require_once '../../class/userClass.php';
require_once '../../class/notificationClass.php';

header('Content-Type: application/json');

// Check if user is logged in and is admin
if (!isset($_SESSION['uid'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userClass = new userClass();
$userDetails = $userClass->userDetails($_SESSION['uid']);

// Check if user is administrator (user_type_id = 1) or staff (user_type_id = 3)
if ($userDetails->user_type_id != 1 && $userDetails->user_type_id != 3) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['notification_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing notification_id']);
    exit;
}

try {
    $pdo = pdo_init();
    $notificationHandler = new NotificationHandler($pdo);
    
    $result = $notificationHandler->markAsRead($input['notification_id']);
    
    if ($result['success']) {
        echo json_encode(['success' => true, 'message' => $result['message']]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => $result['message']]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
