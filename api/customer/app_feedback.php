<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

include '../../config/ini.php';

try {
    // Initialize the database connection
    $pdo = pdo_init();

    // Get the data from the request body
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Log the received data for debugging
    error_log("Feedback API - Received data: " . $input);

    $app_id = isset($data['app_id']) ? intval($data['app_id']) : null;
    $comment = isset($data['comment']) ? trim($data['comment']) : null;
    $rating = isset($data['rating']) ? intval($data['rating']) : null;

    // Validate required parameters
    if (!$app_id || !$rating || $rating < 1 || $rating > 5) {
        echo json_encode([
            'success' => false, 
            'message' => 'Missing or invalid required parameters',
            'debug' => [
                'app_id' => $app_id,
                'rating' => $rating,
                'comment' => $comment
            ]
        ]);
        exit;
    }

    // Set default comment if empty
    if (!$comment || $comment === '') {
        $comment = 'No Comment';
    }

    // First, check if the appointment exists and is completed
    $checkStmt = $pdo->prepare('SELECT app_id, app_status_id FROM appointment WHERE app_id = :app_id');
    $checkStmt->bindParam(':app_id', $app_id, PDO::PARAM_INT);
    $checkStmt->execute();
    $appointment = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$appointment) {
        echo json_encode(['success' => false, 'message' => 'Appointment not found']);
        exit;
    }

    // Update the appointment with feedback
    $stmt = $pdo->prepare('UPDATE appointment SET app_comment = :comment, app_rating = :rating WHERE app_id = :app_id');
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindParam(':app_id', $app_id, PDO::PARAM_INT);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true, 
            'message' => 'Feedback submitted successfully',
            'data' => [
                'app_id' => $app_id,
                'rating' => $rating,
                'comment' => $comment
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to update appointment with feedback',
            'debug' => [
                'rows_affected' => $stmt->rowCount(),
                'error_info' => $stmt->errorInfo()
            ]
        ]);
    }

} catch (Exception $e) {
    error_log("Feedback API Error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Server error occurred',
        'error' => $e->getMessage()
    ]);
}
?>
