<?php
header('Content-Type: application/json');
include '../../../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

try {
    // Get recent activities from the last 24 hours
    $activities = [];
    
    // Recent appointments (new bookings, status changes)
    $appointments_query = "SELECT 
                            a.appointment_id,
                            a_s.app_status_name,
                            a.created_at,
                            a.updated_at,
                            s.service_type_name,
                            CONCAT(c.user_name, ' ', c.user_midname, ' ', c.user_lastname) as customer_name,
                            CONCAT(t.user_name, ' ', t.user_midname, ' ', t.user_lastname) as technician_name
                          FROM appointment a
                          LEFT JOIN user c ON a.user_id = c.user_id
                          LEFT JOIN user t ON a.technician_id = t.user_id
                          LEFT JOIN service_type s ON a.service_type_id = s.service_type_id
                          LEFT JOIN appointment_status a_s ON a.app_status_id = a_s.app_status_id
                          WHERE a.updated_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                          ORDER BY a.updated_at DESC
                          LIMIT 15";
    
    $stmt = $pdo->prepare($appointments_query);
    $stmt->execute();
    $appointments_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($appointments_result as $row) {
        $time_ago = time_elapsed_string($row['updated_at']);
        
        // Determine activity type and description
        $icon = 'bi-calendar-plus';
        $color = 'primary';
        $description = '';
        $details = '';
        
        switch ($row['app_status_name']) {
            case 'Pending':
                if ($row['created_at'] == $row['updated_at']) {
                    $description = "New appointment booked";
                    $icon = 'bi-calendar-plus';
                    $color = 'success';
                } else {
                    $description = "Appointment status changed to pending";
                    $icon = 'bi-clock';
                    $color = 'warning';
                }
                break;
            case 'Accepted':
                $description = "Appointment accepted";
                $icon = 'bi-check-circle';
                $color = 'info';
                break;
            case 'Completed':
                $description = "Job completed";
                $icon = 'bi-check-circle-fill';
                $color = 'success';
                break;
            case 'Declined':
                $description = "Appointment declined";
                $icon = 'bi-x-circle';
                $color = 'danger';
                break;
        }
        
        $details = trim($row['customer_name']) . " - " . $row['service_type_name'];
        if ($row['technician_name']) {
            $details .= " (Technician: " . trim($row['technician_name']) . ")";
        }
        
        $activities[] = [
            'description' => $description,
            'details' => $details,
            'time_ago' => $time_ago,
            'icon' => $icon,
            'color' => $color,
            'timestamp' => $row['updated_at']
        ];
    }
    
    // Recent user registrations
    $users_query = "SELECT 
                      CONCAT(u.user_name, ' ', u.user_midname, ' ', u.user_lastname) as name,
                      ut.user_type_name,
                      u.created_at
                    FROM user u 
                    LEFT JOIN user_type ut ON u.user_type_id = ut.user_type_id
                    WHERE u.created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                      AND u.user_type_id IN (1, 2, 3)
                    ORDER BY u.created_at DESC
                    LIMIT 5";
    
    $stmt = $pdo->prepare($users_query);
    $stmt->execute();
    $users_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($users_result as $row) {
        $time_ago = time_elapsed_string($row['created_at']);
        
        $activities[] = [
            'description' => "New " . strtolower($row['user_type_name']) . " registered",
            'details' => trim($row['name']),
            'time_ago' => $time_ago,
            'icon' => 'bi-person-plus',
            'color' => 'success',
            'timestamp' => $row['created_at']
        ];
    }
    
    // Sort all activities by timestamp (most recent first)
    usort($activities, function($a, $b) {
        return strtotime($b['timestamp']) - strtotime($a['timestamp']);
    });
    
    // Remove timestamp from final output and limit to 10 items
    $activities = array_slice($activities, 0, 10);
    foreach ($activities as &$activity) {
        unset($activity['timestamp']);
    }
    
    echo json_encode([
        'success' => true,
        'activities' => $activities
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

// Helper function to calculate time elapsed
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $weeks = floor($diff->d / 7);
    $days = $diff->d - ($weeks * 7);

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    
    $values = array(
        'y' => $diff->y,
        'm' => $diff->m,
        'w' => $weeks,
        'd' => $days,
        'h' => $diff->h,
        'i' => $diff->i,
        's' => $diff->s,
    );
    
    foreach ($string as $k => &$v) {
        if ($values[$k]) {
            $v = $values[$k] . ' ' . $v . ($values[$k] > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}


?>
