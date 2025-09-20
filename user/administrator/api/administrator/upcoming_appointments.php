<?php
header('Content-Type: application/json');
include '../../../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

try {
    // Get upcoming appointments (next 7 days)
    $query = "SELECT 
                a.appointment_id,
                a.appointment_date,
                a.appointment_time,
                s.service_type_name,
                a_s.app_status_name,
                CONCAT(u.user_name, ' ', u.user_midname, ' ', u.user_lastname) as customer_name,
                CONCAT(ut.user_name, ' ', ut.user_midname, ' ', ut.user_lastname) as technician_name
              FROM appointment a
              LEFT JOIN user u ON a.user_id = u.user_id
              LEFT JOIN user ut ON a.technician_id = ut.user_id
              LEFT JOIN service_type s ON a.service_type_id = s.service_type_id
              LEFT JOIN appointment_status a_s ON a.app_status_id = a_s.app_status_id
              WHERE a.appointment_date >= CURDATE() 
                AND a.appointment_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                AND a.app_status_id IN (1, 2)
              ORDER BY a.appointment_date ASC, a.appointment_time ASC
              LIMIT 10";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    $appointments = [];
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($results as $row) {
        // Format date and time
        $date = date('M j', strtotime($row['appointment_date']));
        $time = date('g:i A', strtotime($row['appointment_time']));
        
        // Determine status color based on app_status_id
        $status_color = 'secondary';
        switch ($row['app_status_name']) {
            case 'Pending':
                $status_color = 'warning';
                break;
            case 'Accepted':
                $status_color = 'info';
                break;
            case 'Completed':
                $status_color = 'success';
                break;
            case 'Declined':
                $status_color = 'danger';
                break;
        }
        
        $appointments[] = [
            'id' => $row['appointment_id'],
            'date' => $date,
            'time' => $time,
            'customer_name' => trim($row['customer_name']),
            'service_type' => $row['service_type_name'],
            'technician_name' => $row['technician_name'] ? trim($row['technician_name']) : null,
            'status' => $row['app_status_name'],
            'status_color' => $status_color
        ];
    }
    
    echo json_encode([
        'success' => true,
        'appointments' => $appointments
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
