<?php
header('Content-Type: application/json');

include '../../config/ini.php';
require_once '../../class/notificationClass.php';

// Initialize the database connection
$pdo = pdo_init();

try {
    // Get recent activities from the notification system (last 24 hours)
    $stmt = $pdo->prepare("
        SELECT 
            n.id as notification_id,
            n.event_type,
            n.event_description,
            n.created_at,
            n.additional_data,
            n.related_appointment_id,
            n.actor_user_id,
            CASE 
                WHEN n.event_type = 'appointment_created' THEN 'New Booking'
                WHEN n.event_type = 'appointment_status_changed' THEN 'Status Updated'
                WHEN n.event_type = 'appointment_accepted' THEN 'Approved'
                WHEN n.event_type = 'payment_status_changed' THEN 'Payment Updated'
                WHEN n.event_type = 'user_created' THEN 'New Registration'
                WHEN n.event_type = 'user_updated' THEN 'User Updated'
                ELSE 'System Activity'
            END as activity_type,
            CASE 
                WHEN n.event_type = 'appointment_created' THEN 'primary'
                WHEN n.event_type = 'appointment_status_changed' THEN 'info'
                WHEN n.event_type = 'appointment_accepted' THEN 'success'
                WHEN n.event_type = 'payment_status_changed' THEN 'success'
                WHEN n.event_type = 'user_created' THEN 'success'
                WHEN n.event_type = 'user_updated' THEN 'warning'
                ELSE 'secondary'
            END as activity_color
        FROM notification n
        WHERE n.created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
        AND n.event_type IN ('appointment_created', 'appointment_status_changed', 'appointment_accepted', 'payment_status_changed', 'user_created', 'user_updated')
        ORDER BY n.created_at DESC
        LIMIT 15
    ");
    
    $stmt->execute();
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format activities with time elapsed
    $formatted_activities = [];
    foreach ($activities as $activity) {
        $created_time = new DateTime($activity['created_at']);
        $now = new DateTime();
        $diff = $now->diff($created_time);
        
        // Calculate time elapsed string
        if ($diff->h > 0) {
            $time_elapsed = $diff->h . 'h ago';
        } elseif ($diff->i > 0) {
            $time_elapsed = $diff->i . 'm ago';
        } else {
            $time_elapsed = 'Just now';
        }
        
        // Parse additional data to extract customer and service information
        $additional_data = json_decode($activity['additional_data'], true) ?: [];
        $customer_name = 'Unknown';
        $service_type = 'Unknown Service';
        
        // Extract customer and service info from notification description or additional data
        if (!empty($additional_data['customer_name'])) {
            $customer_name = $additional_data['customer_name'];
        } elseif (!empty($additional_data['service_type'])) {
            // For appointment-related activities, try to extract from description
            if (preg_match('/Customer\s+([^c]+?)\s+created/', $activity['event_description'], $matches)) {
                $customer_name = trim($matches[1]);
            }
        }
        
        if (!empty($additional_data['service_type'])) {
            $service_type = $additional_data['service_type'];
        } elseif (preg_match('/Service:\s+([^,]+)/', $activity['event_description'], $matches)) {
            $service_type = trim($matches[1]);
        }
        
        // For user registration activities, extract user name from description
        if ($activity['event_type'] === 'user_created' && preg_match('/user created:\s+([^(]+)/', $activity['event_description'], $matches)) {
            $customer_name = trim($matches[1]);
            $service_type = 'New User Registration';
        }
        
        $formatted_activities[] = [
            'app_id' => $activity['related_appointment_id'],
            'notification_id' => $activity['notification_id'],
            'customer_name' => $customer_name,
            'service_type' => $service_type,
            'activity_type' => $activity['activity_type'],
            'activity_color' => $activity['activity_color'],
            'time_elapsed' => $time_elapsed,
            'created_at' => $activity['created_at'],
            'event_description' => $activity['event_description']
        ];
    }
    
    // Sort by created_at descending and limit to 8 most recent
    usort($formatted_activities, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    
    $formatted_activities = array_slice($formatted_activities, 0, 8);
    
    $response = [
        'success' => true,
        'activities' => $formatted_activities,
        'count' => count($formatted_activities)
    ];

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error fetching recent activity: ' . $e->getMessage(),
        'activities' => [],
        'count' => 0
    ];
}

echo json_encode($response);
?>
