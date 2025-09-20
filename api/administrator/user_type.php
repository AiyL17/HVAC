<?php
header('Content-Type: application/json');

include '../../config/ini.php';

$pdo = pdo_init();

try {
    // Fetch all user types from the database
    $stmt = $pdo->query('SELECT * FROM user_type');
    $user_types = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare the response
    $response = [
        'success' => true,
        'user_types' => $user_types
    ];
} catch (PDOException $e) {
    // Handle any errors
    $response = [
        'success' => false,
        'message' => 'Failed to fetch user types: ' . $e->getMessage()
    ];
}

echo json_encode($response);
?>
