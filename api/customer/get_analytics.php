<?php
// Prevent any output before JSON
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    ob_clean();
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

include_once __DIR__ . '/../../config/ini.php';

try {
    $pdo = pdo_init();
    $customer_id = $_SESSION['uid'];
    
    // Verify user is a customer by checking database
    $userCheck = $pdo->prepare("SELECT user_type_id FROM user WHERE user_id = ?");
    $userCheck->execute([$customer_id]);
    $user = $userCheck->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || $user['user_type_id'] != 4) { // 4 = customer user type
        ob_clean();
        echo json_encode(['error' => 'Unauthorized access - not a customer']);
        exit;
    }

    $timeframe = isset($_GET['timeframe']) ? intval($_GET['timeframe']) : 30;
    $type = isset($_GET['type']) ? $_GET['type'] : 'appointments';
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
    
    // Calculate date range based on selected year
    if ($year == date('Y')) {
        // Current year - use current date as end date
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime("-{$timeframe} days"));
    } else {
        // Historical year - use year-end as reference point
        $end_date = date('Y-m-d', strtotime("$year-12-31"));
        $start_date = date('Y-m-d', strtotime("$year-12-31 -{$timeframe} days"));
    }
    
    $response = [];
    
    if ($type === 'appointments') {
        // Always include spending data for appointments view (dual-line chart)
        // Get all unique dates from appointments
        $stmt = $pdo->prepare("
            SELECT DISTINCT DATE(app_schedule) as date
            FROM appointment a
            WHERE a.user_id = ? 
            AND DATE(a.app_schedule) BETWEEN ? AND ?
            ORDER BY date ASC
        ");
        $stmt->execute([$customer_id, $start_date, $end_date]);
        $allDates = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Get appointments count per date
        $stmt = $pdo->prepare("
            SELECT 
                DATE(app_schedule) as date,
                COUNT(*) as count
            FROM appointment a
            WHERE a.user_id = ? 
            AND DATE(a.app_schedule) BETWEEN ? AND ?
            GROUP BY DATE(app_schedule)
            ORDER BY date ASC
        ");
        $stmt->execute([$customer_id, $start_date, $end_date]);
        $appointmentsData = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Get spending per date (only completed appointments)
        $stmt = $pdo->prepare("
            SELECT 
                DATE(app_schedule) as date,
                SUM(CASE 
                    WHEN a.app_price IS NOT NULL AND a.app_price != '' 
                    THEN CAST(a.app_price AS DECIMAL(10,2)) 
                    ELSE 0 
                END) as total_spent
            FROM appointment a
            WHERE a.user_id = ? 
            AND DATE(a.app_schedule) BETWEEN ? AND ?
            AND a.app_status_id = 3
            GROUP BY DATE(app_schedule)
            ORDER BY date ASC
        ");
        $stmt->execute([$customer_id, $start_date, $end_date]);
        $spendingData = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Format data for dual-line chart
        $labels = [];
        $appointmentCounts = [];
        $spendingAmounts = [];
        
        foreach ($allDates as $date) {
            $labels[] = date('M j', strtotime($date));
            $appointmentCounts[] = intval($appointmentsData[$date] ?? 0);
            $spendingAmounts[] = floatval($spendingData[$date] ?? 0);
        }
        
        $response['chartData'] = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Appointments',
                    'data' => $appointmentCounts,
                    'borderColor' => '#007bff',
                    'backgroundColor' => 'rgba(0, 123, 255, 0.1)',
                    'yAxisID' => 'y',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Spending (₱)',
                    'data' => $spendingAmounts,
                    'borderColor' => '#28a745',
                    'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                    'yAxisID' => 'y1',
                    'tension' => 0.4,
                ]
            ]
        ];
        
    } elseif ($type === 'spending') {
        // Get ratings and services data over time (dual-line chart)
        // Get all unique dates from appointments
        $stmt = $pdo->prepare("
            SELECT DISTINCT DATE(app_schedule) as date
            FROM appointment a
            WHERE a.user_id = ? 
            AND DATE(a.app_schedule) BETWEEN ? AND ?
            ORDER BY date ASC
        ");
        $stmt->execute([$customer_id, $start_date, $end_date]);
        $allDates = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Get number of services rendered per date (completed appointments)
        $stmt = $pdo->prepare("
            SELECT 
                DATE(app_schedule) as date,
                COUNT(*) as services_count
            FROM appointment a
            WHERE a.user_id = ? 
            AND DATE(a.app_schedule) BETWEEN ? AND ?
            AND a.app_status_id = 3
            GROUP BY DATE(app_schedule)
            ORDER BY date ASC
        ");
        $stmt->execute([$customer_id, $start_date, $end_date]);
        $servicesData = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Get number of ratings per date (appointments with ratings)
        $stmt = $pdo->prepare("
            SELECT 
                DATE(app_schedule) as date,
                COUNT(*) as ratings_count
            FROM appointment a
            WHERE a.user_id = ? 
            AND DATE(a.app_schedule) BETWEEN ? AND ?
            AND a.app_status_id = 3
            AND a.app_rating IS NOT NULL 
            AND a.app_rating > 0
            GROUP BY DATE(app_schedule)
            ORDER BY date ASC
        ");
        $stmt->execute([$customer_id, $start_date, $end_date]);
        $ratingsData = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Format data for dual-line chart
        $labels = [];
        $servicesCounts = [];
        $ratingsCounts = [];
        
        foreach ($allDates as $date) {
            $labels[] = date('M j', strtotime($date));
            $servicesCounts[] = intval($servicesData[$date] ?? 0);
            $ratingsCounts[] = intval($ratingsData[$date] ?? 0);
        }
        
        $response['chartData'] = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Services Rendered',
                    'data' => $servicesCounts,
                    'borderColor' => '#007bff',
                    'backgroundColor' => 'rgba(0, 123, 255, 0.1)',
                    'yAxisID' => 'y',
                    'tension' => 0.4
                ],
                [
                    'label' => 'Ratings Given',
                    'data' => $ratingsCounts,
                    'borderColor' => '#ffc107',
                    'backgroundColor' => 'rgba(255, 193, 7, 0.1)',
                    'yAxisID' => 'y1',
                    'tension' => 0.4
                ]
            ]
        ];
        
    } elseif ($type === 'combined') {
        // Get combined appointments and spending data over time
        // First, get all unique dates from both appointments and spending
        $stmt = $pdo->prepare("
            SELECT DISTINCT DATE(app_schedule) as date
            FROM appointment a
            WHERE a.user_id = ? 
            AND DATE(a.app_schedule) BETWEEN ? AND ?
            ORDER BY date ASC
        ");
        $stmt->execute([$customer_id, $start_date, $end_date]);
        $allDates = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Get appointments count per date
        $stmt = $pdo->prepare("
            SELECT 
                DATE(app_schedule) as date,
                COUNT(*) as count
            FROM appointment a
            WHERE a.user_id = ? 
            AND DATE(a.app_schedule) BETWEEN ? AND ?
            GROUP BY DATE(app_schedule)
            ORDER BY date ASC
        ");
        $stmt->execute([$customer_id, $start_date, $end_date]);
        $appointmentsData = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Get spending per date (only completed appointments)
        $stmt = $pdo->prepare("
            SELECT 
                DATE(app_schedule) as date,
                SUM(CASE 
                    WHEN a.app_price IS NOT NULL AND a.app_price != '' 
                    THEN CAST(a.app_price AS DECIMAL(10,2)) 
                    ELSE 0 
                END) as total_spent
            FROM appointment a
            WHERE a.user_id = ? 
            AND DATE(a.app_schedule) BETWEEN ? AND ?
            AND a.app_status_id = 3
            GROUP BY DATE(app_schedule)
            ORDER BY date ASC
        ");
        $stmt->execute([$customer_id, $start_date, $end_date]);
        $spendingData = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Format data for dual-line chart
        $labels = [];
        $appointmentCounts = [];
        $spendingAmounts = [];
        
        foreach ($allDates as $date) {
            $labels[] = date('M j', strtotime($date));
            $appointmentCounts[] = intval($appointmentsData[$date] ?? 0);
            $spendingAmounts[] = floatval($spendingData[$date] ?? 0);
        }
        
        $response['chartData'] = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Appointments',
                    'data' => $appointmentCounts,
                    'borderColor' => '#007bff',
                    'backgroundColor' => 'rgba(0, 123, 255, 0.1)',
                    'yAxisID' => 'y'
                ],
                [
                    'label' => 'Spending (₱)',
                    'data' => $spendingAmounts,
                    'borderColor' => '#28a745',
                    'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                    'yAxisID' => 'y1'
                ]
            ]
        ];
        
    } elseif ($type === 'services') {
        // Get service types distribution
        $stmt = $pdo->prepare("
            SELECT 
                st.service_type_name,
                COUNT(*) as count
            FROM appointment a
            JOIN service_type st ON a.service_type_id = st.service_type_id
            WHERE a.user_id = ? 
            AND DATE(a.app_schedule) BETWEEN ? AND ?
            GROUP BY st.service_type_name
            ORDER BY count DESC
        ");
        $stmt->execute([$customer_id, $start_date, $end_date]);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format data for chart
        $labels = [];
        $data = [];
        
        foreach ($services as $service) {
            $labels[] = $service['service_type_name'];
            $data[] = intval($service['count']);
        }
        
        $response['chartData'] = [
            'labels' => $labels,
            'data' => $data,
            'label' => 'Service Count'
        ];
    }
    
    // Get key metrics
    // Total Services - filtered by date range
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total_services
        FROM appointment 
        WHERE user_id = ? AND DATE(app_schedule) BETWEEN ? AND ?
    ");
    $stmt->execute([$customer_id, $start_date, $end_date]);
    $response['totalServices'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_services'];
    
    // Total Spent - filtered by date range
    $stmt = $pdo->prepare("
        SELECT SUM(CASE 
            WHEN a.app_price IS NOT NULL AND a.app_price != '' 
            THEN CAST(a.app_price AS DECIMAL(10,2)) 
            ELSE 0 
        END) as total_spent
        FROM appointment a
        WHERE a.user_id = ? AND a.app_status_id = 3 AND DATE(app_schedule) BETWEEN ? AND ?
    ");
    $stmt->execute([$customer_id, $start_date, $end_date]);
    $response['totalSpent'] = floatval($stmt->fetch(PDO::FETCH_ASSOC)['total_spent'] ?? 0);
    
    // Average Service Time - filtered by date range
    $stmt = $pdo->prepare("
        SELECT AVG(DATEDIFF(a.app_schedule, a.app_created)) as avg_service_time
        FROM appointment a
        WHERE a.user_id = ? AND a.app_status_id = 3 AND DATE(app_schedule) BETWEEN ? AND ?
    ");
    $stmt->execute([$customer_id, $start_date, $end_date]);
    $response['avgServiceTime'] = intval($stmt->fetch(PDO::FETCH_ASSOC)['avg_service_time'] ?? 0);
    
    // Customer Satisfaction - get actual average rating from database
    $stmt = $pdo->prepare("
        SELECT AVG(a.app_rating) as avg_rating
        FROM appointment a
        WHERE a.user_id = ? AND a.app_rating IS NOT NULL AND DATE(app_schedule) BETWEEN ? AND ?
    ");
    $stmt->execute([$customer_id, $start_date, $end_date]);
    $avgRating = $stmt->fetch(PDO::FETCH_ASSOC)['avg_rating'];
    $response['satisfactionScore'] = $avgRating ? round($avgRating, 1) : 0;
    
    // Get insights
    // Most used service
    $stmt = $pdo->prepare("
        SELECT st.service_type_name, COUNT(*) as count
        FROM appointment a
        JOIN service_type st ON a.service_type_id = st.service_type_id
        WHERE a.user_id = ?
        GROUP BY st.service_type_name
        ORDER BY count DESC
        LIMIT 1
    ");
    $stmt->execute([$customer_id]);
    $mostUsed = $stmt->fetch(PDO::FETCH_ASSOC);
    $response['mostUsedService'] = $mostUsed ? $mostUsed['service_type_name'] : 'N/A';
    
    // Preferred month
    $stmt = $pdo->prepare("
        SELECT MONTH(app_schedule) as month, COUNT(*) as count
        FROM appointment
        WHERE user_id = ?
        GROUP BY MONTH(app_schedule)
        ORDER BY count DESC
        LIMIT 1
    ");
    $stmt->execute([$customer_id]);
    $preferredMonth = $stmt->fetch(PDO::FETCH_ASSOC);
    $months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $response['preferredMonth'] = $preferredMonth ? $months[intval($preferredMonth['month'])] : 'N/A';
    
    // Service frequency (average days between appointments)
    $stmt = $pdo->prepare("
        SELECT AVG(days_between) as avg_frequency
        FROM (
            SELECT DATEDIFF(
                LEAD(app_schedule) OVER (ORDER BY app_schedule),
                app_schedule
            ) as days_between
            FROM appointment
            WHERE user_id = ?
            ORDER BY app_schedule
        ) as freq_calc
        WHERE days_between IS NOT NULL
    ");
    $stmt->execute([$customer_id]);
    $frequency = $stmt->fetch(PDO::FETCH_ASSOC);
    $avgDays = intval($frequency['avg_frequency'] ?? 90);
    
    if ($avgDays <= 30) {
        $response['serviceFrequency'] = 'Monthly';
    } elseif ($avgDays <= 90) {
        $response['serviceFrequency'] = 'Every 3 months';
    } elseif ($avgDays <= 180) {
        $response['serviceFrequency'] = 'Every 6 months';
    } else {
        $response['serviceFrequency'] = 'Yearly';
    }
    
    // Recommended service (simple logic - if last was repair, suggest maintenance)
    $stmt = $pdo->prepare("
        SELECT st.service_type_name
        FROM appointment a
        JOIN service_type st ON a.service_type_id = st.service_type_id
        WHERE a.user_id = ?
        ORDER BY a.app_schedule DESC
        LIMIT 1
    ");
    $stmt->execute([$customer_id]);
    $lastService = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($lastService && strpos(strtolower($lastService['service_type_name']), 'repair') !== false) {
        $response['recommendedService'] = 'Maintenance';
    } elseif ($lastService && strpos(strtolower($lastService['service_type_name']), 'maintenance') !== false) {
        $response['recommendedService'] = 'Inspection';
    } else {
        $response['recommendedService'] = 'Maintenance';
    }
    
    ob_clean();
    echo json_encode($response);
    
} catch (Exception $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error', 
        'message' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => basename($e->getFile()),
        'customer_id' => $customer_id ?? 'unknown'
    ]);
}
?>
