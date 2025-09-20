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

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    $pdo = pdo_init();
    $notificationHandler = new NotificationHandler($pdo);
    
    // Mark all notifications as read for admin/staff users
    $result = $notificationHandler->markAdminNotificationsAsRead($_SESSION['uid']);
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    } else {
        throw new Exception($result['message']);
    }
    
} catch (Exception $e) {
    error_log("Error marking admin notifications as read: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to mark notifications as read'
    ]);
}
?>
