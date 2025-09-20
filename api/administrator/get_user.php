<?php
header('Content-Type: application/json');

include '../../config/ini.php';

$pdo = pdo_init();

// Check if user ID is provided
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User ID is required'
    ]);
    exit;
}

$user_id = intval($_GET['user_id']);

try {
    // Prepare and execute the query to fetch a single user
    $stmt = $pdo->prepare('SELECT * FROM user WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // User found
        $response = [
            'success' => true,
            'user' => $user
        ];
    } else {
        // User not found
        $response = [
            'success' => false,
            'message' => 'User not found'
        ];
    }
} catch (PDOException $e) {
    // Handle any errors
    $response = [
        'success' => false,
        'message' => 'Failed to fetch user: ' . $e->getMessage()
    ];
}

echo json_encode($response);
?>