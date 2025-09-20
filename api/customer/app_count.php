<?php
header('Content-Type: application/json');

include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

// Get the type from the query parameters
$type = isset($_GET['type']) ? $_GET['type'] : null;

if (!$type) {
    echo json_encode(['success' => false, 'message' => 'Type parameter is missing']);
    exit;
}
$user_id = $_SESSION['uid'];
// Prepare and execute the query based on the type
$stmt = $pdo->prepare('SELECT COUNT(*) AS app_count FROM appointment WHERE user_id = :user_id AND app_status_id = :status');
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':status', $type);
$stmt->execute();

// Fetch the result
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Prepare the response
$response = [
    'success' => true,
    'app_count' => $result['app_count']
];

// Return the response as JSON
echo json_encode($response);
?>
