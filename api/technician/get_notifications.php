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

// Check if user is technician (user_type_id = 2)
if ($userDetails->user_type_id != 2) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

try {
    $pdo = pdo_init();
    $notificationHandler = new NotificationHandler($pdo);
    
    // Check if unlimited notifications are requested
    $unlimited = isset($_GET['unlimited']) && $_GET['unlimited'] === 'true';
    $limit = $unlimited ? 1000 : 15; // Use high limit for "unlimited" to avoid memory issues
    
    // Get technician-specific notifications from notification table
    $technicianNotifications = $notificationHandler->getTechnicianNotifications($_SESSION['uid'], $limit);
    
    if (!$technicianNotifications['success']) {
        throw new Exception($technicianNotifications['message']);
    }
    
    $notifications = [];
    
    // Process notifications from the database
    foreach ($technicianNotifications['notifications'] as $dbNotification) {
        // Determine icon and color based on event type
        $icon = 'bi-bell';
        $color = 'text-primary';
        $title = 'Notification';
        
        switch ($dbNotification['event_type']) {
            case 'technician_assigned':
                $icon = 'bi-person-check';
                $color = 'text-success';
                $title = 'Assignment Notification';
                break;
            case 'appointment_accepted':
                $icon = 'bi-check-circle';
                $color = 'text-success';
                $title = 'Appointment Accepted';
                break;
            case 'appointment_status_changed':
                $icon = 'bi-arrow-repeat';
                $color = 'text-warning';
                $title = 'Status Update';
                break;
            case 'payment_status_changed':
                $icon = 'bi-credit-card';
                $color = 'text-success';
                $title = 'Payment Update';
                break;
            case 'appointment_created':
                $icon = 'bi-calendar-plus';
                $color = 'text-info';
                $title = 'New Appointment';
                break;
        }
        
        $notifications[] = [
            'id' => 'notification_' . $dbNotification['notification_id'],
            'type' => $dbNotification['event_type'],
            'title' => $title,
            'message' => $dbNotification['event_description'],
            'time' => $dbNotification['created_at'],
            'icon' => $icon,
            'color' => $color,
            'is_read' => (bool)$dbNotification['is_read'],
            'priority' => $dbNotification['priority'],
            'additional_data' => $dbNotification['additional_data'] ? json_decode($dbNotification['additional_data'], true) : null
        ];
    }
    
    // Sort all notifications by time (newest first)
    usort($notifications, function($a, $b) {
        return strtotime($b['time']) - strtotime($a['time']);
    });
    
    // Apply limit only if not unlimited
    if (!$unlimited) {
        $notifications = array_slice($notifications, 0, 15);
    }
    
    // Add time formatting
    foreach ($notifications as &$notification) {
        $time = new DateTime($notification['time']);
        $now = new DateTime();
        $diff = $now->diff($time);
        
        if ($diff->days > 0) {
            $notification['timeAgo'] = $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' ago';
        } elseif ($diff->h > 0) {
            $notification['timeAgo'] = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        } elseif ($diff->i > 0) {
            $notification['timeAgo'] = $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
        } else {
            $notification['timeAgo'] = 'Just now';
        }
    }
    
    echo json_encode([
        'success' => true,
        'count' => count($notifications),
        'notifications' => $notifications
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
