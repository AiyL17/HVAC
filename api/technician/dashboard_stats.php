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
    // Get basic task counts
    $basicStats = $pdo->prepare("
        SELECT 
            COUNT(*) as total_tasks,
            SUM(CASE WHEN app_status_id = 5 THEN 1 ELSE 0 END) as in_progress_tasks,
            SUM(CASE WHEN app_status_id = 3 THEN 1 ELSE 0 END) as completed_tasks,
            AVG(CASE WHEN app_rating IS NOT NULL AND app_rating > 0 THEN app_rating ELSE NULL END) as average_rating
        FROM appointment 
        WHERE user_technician = :technician_id
    ");
    $basicStats->bindParam(':technician_id', $technician_id);
    $basicStats->execute();
    $stats = $basicStats->fetch(PDO::FETCH_ASSOC);

    // Get chart data for different chart types
    $chartType = $_GET['chart_type'] ?? 'tasks';
    $chartData = [];

    switch($chartType) {
        case 'tasks':
            // Monthly task completion data
            $monthlyData = $pdo->prepare("
                SELECT 
                    MONTHNAME(app_schedule) as month,
                    COUNT(*) as count
                FROM appointment 
                WHERE user_technician = :technician_id 
                AND app_schedule >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                AND app_status_id = 3
                GROUP BY MONTH(app_schedule), YEAR(app_schedule)
                ORDER BY app_schedule
            ");
            $monthlyData->bindParam(':technician_id', $technician_id);
            $monthlyData->execute();
            $monthlyResults = $monthlyData->fetchAll(PDO::FETCH_ASSOC);
            
            $chartData = [
                'labels' => array_column($monthlyResults, 'month'),
                'data' => array_column($monthlyResults, 'count')
            ];
            break;

        case 'service':
            // Service type distribution
            $serviceData = $pdo->prepare("
                SELECT 
                    st.service_type_name as service_type,
                    COUNT(*) as count
                FROM appointment a
                JOIN service_type st ON a.service_type_id = st.service_type_id
                WHERE a.user_technician = :technician_id
                GROUP BY st.service_type_id, st.service_type_name
                ORDER BY count DESC
            ");
            $serviceData->bindParam(':technician_id', $technician_id);
            $serviceData->execute();
            $serviceResults = $serviceData->fetchAll(PDO::FETCH_ASSOC);
            
            $chartData = [
                'labels' => array_column($serviceResults, 'service_type'),
                'data' => array_column($serviceResults, 'count')
            ];
            break;

        case 'ratings':
            // Rating trends over time
            $ratingData = $pdo->prepare("
                SELECT 
                    MONTHNAME(app_schedule) as month,
                    AVG(app_rating) as avg_rating
                FROM appointment 
                WHERE user_technician = :technician_id 
                AND app_rating IS NOT NULL 
                AND app_rating > 0
                AND app_schedule >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY MONTH(app_schedule), YEAR(app_schedule)
                ORDER BY app_schedule
            ");
            $ratingData->bindParam(':technician_id', $technician_id);
            $ratingData->execute();
            $ratingResults = $ratingData->fetchAll(PDO::FETCH_ASSOC);
            
            $chartData = [
                'labels' => array_column($ratingResults, 'month'),
                'data' => array_map(function($rating) { 
                    return round($rating, 1); 
                }, array_column($ratingResults, 'avg_rating'))
            ];
            break;
    }

    // Prepare response
    $response = [
        'success' => true,
        'total_tasks' => (int)($stats['total_tasks'] ?? 0),
        'in_progress_tasks' => (int)($stats['in_progress_tasks'] ?? 0),
        'completed_tasks' => (int)($stats['completed_tasks'] ?? 0),
        'average_rating' => round((float)($stats['average_rating'] ?? 0), 1),
        'labels' => $chartData['labels'] ?? [],
        'data' => $chartData['data'] ?? []
    ];

} catch (PDOException $e) {
    // Handle database errors
    $response = [
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ];
    error_log("Technician dashboard stats error: " . $e->getMessage());
}

// Return the response as JSON
echo json_encode($response);
?>
