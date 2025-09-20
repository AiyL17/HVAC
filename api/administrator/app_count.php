<?php
header('Content-Type: application/json');

include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

try {
    // Count all appointments (excluding those with app_id = 0 which seem to be invalid)
    $stmt = $pdo->prepare('SELECT COUNT(*) AS app_count FROM appointment WHERE app_id > 0');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Prepare the response
    $response = [
        'success' => true,
        'app_count' => $result['app_count']
    ];

} catch (Exception $e) {
    // Handle errors
    $response = [
        'success' => false,
        'message' => 'Error fetching appointment count: ' . $e->getMessage(),
        'app_count' => 0
    ];
}

// Return the response as JSON
echo json_encode($response);
?>
