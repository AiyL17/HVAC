<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once '../../config/ini.php';
require_once '../../class/userClass.php';

$userClass = new userClass();
$pdo = pdo_init();

// Check if user is logged in and is a customer
if (!isset($_SESSION['uid'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

$userDetails = $userClass->userDetails($_SESSION['uid']);
if (!$userDetails || $userDetails->user_type !== 'customer') {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$customer_id = $_SESSION['uid'];

try {
    // Debug: Log the customer ID
    error_log("Fetching activities for customer ID: " . $customer_id);
    
    // Get recent activities based on appointment actions and status changes
    $stmt = $pdo->prepare("
        SELECT 
            a.app_id,
            a.app_created,
            a.app_schedule,
            a.app_status_id,
            a.app_completed_at,
            st.service_type_name,
            at.appliances_type_name,
            CASE 
                WHEN a.app_status_id = 1 THEN 'approved'
                WHEN a.app_status_id = 2 THEN 'pending'
                WHEN a.app_status_id = 3 THEN 'completed'
                WHEN a.app_status_id = 4 THEN 'declined'
                WHEN a.app_status_id = 5 THEN 'in_progress'
                ELSE 'unknown'
            END as status_name,
            CASE 
                WHEN a.app_status_id = 1 THEN 'approved'
                WHEN a.app_status_id = 2 THEN 'created'
                WHEN a.app_status_id = 3 THEN 'completed'
                WHEN a.app_status_id = 4 THEN 'declined'
                WHEN a.app_status_id = 5 THEN 'in_progress'
                ELSE 'unknown'
            END as activity_type,
            CONCAT(u1.user_name, ' ', u1.user_lastname) as primary_technician_name,
            CONCAT(u2.user_name, ' ', u2.user_lastname) as secondary_technician_name
        FROM appointment a
        JOIN service_type st ON a.service_type_id = st.service_type_id
        LEFT JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
        LEFT JOIN user u1 ON a.user_technician = u1.user_id
        LEFT JOIN user u2 ON a.user_technician_2 = u2.user_id
        WHERE a.user_id = ?
        ORDER BY 
            CASE 
                WHEN a.app_status_id = 3 AND a.app_completed_at IS NOT NULL THEN a.app_completed_at
                ELSE a.app_created
            END DESC
        LIMIT 20
    ");
    
    $stmt->execute([$customer_id]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Debug: Log the number of appointments found
    error_log("Found " . count($appointments) . " appointments for customer " . $customer_id);
    
    $activities = [];
    
    foreach ($appointments as $appointment) {
        // Create activity based on appointment status
        $activity = [
            'activity_id' => $appointment['app_id'],
            'activity_type' => $appointment['activity_type'],
            'activity_description' => '',
            'activity_timestamp' => '',
            'activity_icon' => '',
            'activity_color' => '',
            'related_data' => [
                'service_type' => $appointment['service_type_name'],
                'appliance_type' => $appointment['appliances_type_name'],
                'technician' => $appointment['primary_technician_name'],
                'appointment_id' => $appointment['app_id']
            ]
        ];
        
        switch ($appointment['activity_type']) {
            case 'created':
                $activity['activity_description'] = "Created appointment for {$appointment['service_type_name']}";
                $activity['activity_timestamp'] = $appointment['app_created'];
                $activity['activity_icon'] = 'bi-calendar-plus-fill';
                $activity['activity_color'] = 'text-primary';
                break;
                
            case 'approved':
                $activity['activity_description'] = "Appointment for {$appointment['service_type_name']} was approved";
                $activity['activity_timestamp'] = $appointment['app_created']; // Using creation time as we don't have approval time
                $activity['activity_icon'] = 'bi-check-circle-fill';
                $activity['activity_color'] = 'text-success';
                break;
                
            case 'in_progress':
                $activity['activity_description'] = "Appointment for {$appointment['service_type_name']} is now in progress";
                $activity['activity_timestamp'] = $appointment['app_schedule'];
                $activity['activity_icon'] = 'bi-clock-fill';
                $activity['activity_color'] = 'text-info';
                break;
                
            case 'completed':
                $activity['activity_description'] = "Completed appointment for {$appointment['service_type_name']}";
                $activity['activity_timestamp'] = $appointment['app_completed_at'] ?: $appointment['app_schedule'];
                $activity['activity_icon'] = 'bi-check-circle-fill';
                $activity['activity_color'] = 'text-success';
                break;
                
            case 'declined':
                $activity['activity_description'] = "Appointment for {$appointment['service_type_name']} was declined";
                $activity['activity_timestamp'] = $appointment['app_created'];
                $activity['activity_icon'] = 'bi-x-circle-fill';
                $activity['activity_color'] = 'text-danger';
                break;
                
            default:
                $activity['activity_description'] = "Appointment for {$appointment['service_type_name']} status updated";
                $activity['activity_timestamp'] = $appointment['app_created'];
                $activity['activity_icon'] = 'bi-calendar-fill';
                $activity['activity_color'] = 'text-primary';
                break;
        }
        
        $activities[] = $activity;
    }
    
    // Sort activities by timestamp (most recent first)
    usort($activities, function($a, $b) {
        return strtotime($b['activity_timestamp']) - strtotime($a['activity_timestamp']);
    });
    
    // Debug: Log the activities being returned
    error_log("Returning " . count($activities) . " activities");
    if (!empty($activities)) {
        error_log("First activity: " . json_encode($activities[0]));
    }
    
    echo json_encode([
        'success' => true,
        'activities' => array_slice($activities, 0, 10), // Limit to 10 most recent
        'debug' => [
            'customer_id' => $customer_id,
            'appointments_found' => count($appointments),
            'activities_created' => count($activities)
        ]
    ]);
    
} catch (Exception $e) {
    error_log("Error fetching recent activities: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch recent activities'
    ]);
}
?>
