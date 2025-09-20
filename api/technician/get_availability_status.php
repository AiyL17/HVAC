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
    // Get availability status and related information
    $query = "
        SELECT 
            availability_status,
            availability_date,
            availability_start_time,
            availability_end_time,
            availability_notes,
            last_activity
        FROM user 
        WHERE user_id = ?
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$technician_id]);
    $availability = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$availability) {
        throw new Exception('User not found');
    }
    
    // Format the data
    $formatted_availability = [
        'status' => ucfirst($availability['availability_status'] ?? 'available'),
        'availability_date' => $availability['availability_date'] ? 
            date('M j, Y', strtotime($availability['availability_date'])) : null,
        'availability_start_time' => $availability['availability_start_time'] ? 
            date('g:i A', strtotime($availability['availability_start_time'])) : null,
        'availability_end_time' => $availability['availability_end_time'] ? 
            date('g:i A', strtotime($availability['availability_end_time'])) : null,
        'availability_notes' => $availability['availability_notes'],
        'last_updated' => $availability['last_activity'] ? 
            date('M j, Y g:i A', strtotime($availability['last_activity'])) : 'Never'
    ];
    
    echo json_encode([
        'success' => true,
        'availability' => $formatted_availability
    ]);
    
} catch (PDOException $e) {
    error_log("Database error in get_availability_status.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Database error occurred'
    ]);
} catch (Exception $e) {
    error_log("General error in get_availability_status.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred while fetching availability status'
    ]);
}
?>
