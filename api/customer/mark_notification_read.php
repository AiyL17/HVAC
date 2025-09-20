<?php
session_start();
require_once '../../config/ini.php';
require_once '../../class/userClass.php';
require_once '../../class/notificationClass.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userClass = new userClass();
$userDetails = $userClass->userDetails($_SESSION['uid']);

// Check if user is customer (user_type_id = 4)
if ($userDetails->user_type_id != 4) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Check if notification ID is provided (support both POST and JSON)
$notificationId = null;
if (isset($_POST['notification_id'])) {
    $notificationId = $_POST['notification_id'];
} elseif (isset($input['notification_id'])) {
    $notificationId = $input['notification_id'];
}

if (!$notificationId) {
    http_response_code(400);
    echo json_encode(['error' => 'Notification ID is required']);
    exit;
}

try {
    $pdo = pdo_init();
    $notificationHandler = new NotificationHandler($pdo);
    
    $notificationId = intval($notificationId);
    
    // Verify the notification belongs to this customer before marking as read
    $stmt = $pdo->prepare("SELECT target_user_id FROM notification WHERE notification_id = :notification_id");
    $stmt->bindParam(':notification_id', $notificationId, PDO::PARAM_INT);
    $stmt->execute();
    $notification = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$notification) {
        http_response_code(404);
        echo json_encode(['error' => 'Notification not found']);
        exit;
    }
    
    if ($notification['target_user_id'] != $_SESSION['uid']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied to this notification']);
        exit;
    }
    
    // Mark notification as read
    $result = $notificationHandler->markAsRead($notificationId);
    
    if ($result['success']) {
        echo json_encode(['success' => true, 'message' => 'Notification marked as read']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $result['message']]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
