<?php
header('Content-Type: application/json');

include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$technician_id = $_SESSION['uid'];

try {
    // Get rating distribution for the technician (1-5 stars)
    $ratingDistributionStmt = $pdo->prepare("
        SELECT 
            SUM(CASE WHEN app_rating = 1 THEN 1 ELSE 0 END) as rating_1,
            SUM(CASE WHEN app_rating = 2 THEN 1 ELSE 0 END) as rating_2,
            SUM(CASE WHEN app_rating = 3 THEN 1 ELSE 0 END) as rating_3,
            SUM(CASE WHEN app_rating = 4 THEN 1 ELSE 0 END) as rating_4,
            SUM(CASE WHEN app_rating = 5 THEN 1 ELSE 0 END) as rating_5,
            COUNT(*) as total_ratings
        FROM appointment 
        WHERE user_technician = :technician_id 
        AND app_status_id = 3 
        AND app_rating IS NOT NULL 
        AND app_rating > 0
    ");
    $ratingDistributionStmt->bindParam(':technician_id', $technician_id);
    $ratingDistributionStmt->execute();
    $ratingData = $ratingDistributionStmt->fetch(PDO::FETCH_ASSOC);
    
    // Prepare response
    $response = [
        'success' => true,
        'rating_1' => (int)($ratingData['rating_1'] ?? 0),
        'rating_2' => (int)($ratingData['rating_2'] ?? 0),
        'rating_3' => (int)($ratingData['rating_3'] ?? 0),
        'rating_4' => (int)($ratingData['rating_4'] ?? 0),
        'rating_5' => (int)($ratingData['rating_5'] ?? 0),
        'total_ratings' => (int)($ratingData['total_ratings'] ?? 0)
    ];
    
    // Debug logging
    error_log("Rating Distribution API - Technician ID: " . $technician_id);
    error_log("Rating Distribution API - Response: " . json_encode($response));

} catch (PDOException $e) {
    // Handle database errors
    $response = [
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ];
    error_log("Technician rating distribution error: " . $e->getMessage());
}

// Return the response as JSON
echo json_encode($response);
?>
