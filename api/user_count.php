<?php
header('Content-Type: application/json');

include '../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

// Get the user type from the URL query parameters
$user_type = isset($_GET['user_type']) ? $_GET['user_type'] : null;

// Validate the user type
$allowed_types = ['customer', 'administrator', 'technician','staff'];
if (!$user_type || !in_array($user_type, $allowed_types)) {
    // Return an error if the user type is invalid
    echo json_encode([
        'success' => false,
        'message' => 'Invalid user type. Allowed values: ' . implode(', ', $allowed_types)
    ]);
    exit;
}

// Prepare and execute the query
$stmt = $pdo->prepare('SELECT COUNT(*) AS user_count 
    FROM user 
    JOIN user_type ON user.user_type_id = user_type.user_type_id 
    WHERE user_type.user_type_name = :user_type');
$stmt->bindParam(':user_type', $user_type, PDO::PARAM_STR);
$stmt->execute();

// Fetch the result
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Prepare the response
$response = [
    'success' => true,
    'user_type' => $user_type,
    'user_count' => $result['user_count']
];

// Return the response as JSON
echo json_encode($response);
?>
