<?php
header('Content-Type: application/json');
include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

// Get the technician ID from the session
$technician_id = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;

// Validate the technician ID
if (!$technician_id) {
    echo json_encode([
        'success' => false,
        'message' => 'Technician ID not found in session.'
    ]);
    exit;
}

try {
    // Get recent notifications/activities for this technician
    $query = "
        SELECT 
            event_description as description,
            created_at
        FROM notification 
        WHERE target_user_id = ?
        AND event_description IS NOT NULL
        AND event_description != ''
        ORDER BY created_at DESC 
        LIMIT 10
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$technician_id]);
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // If no notifications, get recent appointment activities
    if (empty($activities)) {
        $appointment_query = "
            SELECT 
                CONCAT('Appointment ', 
                    CASE 
                        WHEN app_status_id = 1 THEN 'approved'
                        WHEN app_status_id = 2 THEN 'pending'
                        WHEN app_status_id = 3 THEN 'completed'
                        WHEN app_status_id = 5 THEN 'started'
                        ELSE 'updated'
                    END,
                    ' for ', st.service_type_name, ' service'
                ) as description,
                COALESCE(app_completed_at, app_created) as created_at
            FROM appointment a
            LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
            WHERE (a.user_technician = ? OR a.user_technician_2 = ?)
            ORDER BY COALESCE(app_completed_at, app_created) DESC
            LIMIT 5
        ";
        
        $stmt = $pdo->prepare($appointment_query);
        $stmt->execute([$technician_id, $technician_id]);
        $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    echo json_encode([
        'success' => true,
        'activities' => $activities
    ]);
    
} catch (PDOException $e) {
    error_log("Database error in get_recent_activity.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Database error occurred'
    ]);
} catch (Exception $e) {
    error_log("General error in get_recent_activity.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred while fetching recent activity'
    ]);
}
?>
