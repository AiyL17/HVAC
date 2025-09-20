<?php
header('Content-Type: application/json');

include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

// Get parameters
$timeframe = isset($_GET['timeframe']) ? (int)$_GET['timeframe'] : 30;
$chartType = isset($_GET['chart_type']) ? $_GET['chart_type'] : 'appointments';
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

try {
    $response = ['success' => true];
    
    // Calculate date range based on selected year
    if ($year == date('Y')) {
        // Current year - use current date as end date
        $startDate = date('Y-m-d', strtotime("-{$timeframe} days"));
        $endDate = date('Y-m-d');
    } else {
        // Historical year - use year-end as reference point
        $endDate = date('Y-m-d', strtotime("$year-12-31"));
        $startDate = date('Y-m-d', strtotime("$year-12-31 -{$timeframe} days"));
    }
    
    switch ($chartType) {
        case 'appointments':
            // Daily appointment counts
            $stmt = $pdo->prepare("
                SELECT 
                    DATE(a.app_created) as date,
                    COUNT(*) as count,
                    COUNT(CASE WHEN a.app_status_id = 3 THEN 1 END) as completed,
                    COUNT(CASE WHEN a.app_status_id = 2 THEN 1 END) as pending,
                    COUNT(CASE WHEN a.app_status_id = 1 THEN 1 END) as approved,
                    COUNT(CASE WHEN a.app_status_id = 5 THEN 1 END) as in_progress
                FROM appointment a
                WHERE a.app_id > 0 
                AND DATE(a.app_created) >= ? 
                AND DATE(a.app_created) <= ?
                GROUP BY DATE(a.app_created)
                ORDER BY DATE(a.app_created)
            ");
            $stmt->execute([$startDate, $endDate]);
            $dailyData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Fill in missing dates with zero counts
            $chartData = [];
            $labels = [];
            $datasets = [
                'total' => [],
                'completed' => [],
                'pending' => [],
                'approved' => [],
                'in_progress' => []
            ];
            
            for ($i = $timeframe - 1; $i >= 0; $i--) {
                if ($year == date('Y')) {
                    $date = date('Y-m-d', strtotime("-{$i} days"));
                } else {
                    $date = date('Y-m-d', strtotime("$year-12-31 -{$i} days"));
                }
                $labels[] = date('M j', strtotime($date));
                
                $found = false;
                foreach ($dailyData as $data) {
                    if ($data['date'] === $date) {
                        $datasets['total'][] = (int)$data['count'];
                        $datasets['completed'][] = (int)$data['completed'];
                        $datasets['pending'][] = (int)$data['pending'];
                        $datasets['approved'][] = (int)$data['approved'];
                        $datasets['in_progress'][] = (int)$data['in_progress'];
                        $found = true;
                        break;
                    }
                }
                
                if (!$found) {
                    $datasets['total'][] = 0;
                    $datasets['completed'][] = 0;
                    $datasets['pending'][] = 0;
                    $datasets['approved'][] = 0;
                    $datasets['in_progress'][] = 0;
                }
            }
            
            $response['chart_data'] = [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Total Appointments',
                        'data' => $datasets['total'],
                        'borderColor' => '#0d6efd',
                        'backgroundColor' => 'rgba(13, 110, 253, 0.1)',
                        'tension' => 0.4
                    ],
                    [
                        'label' => 'Completed',
                        'data' => $datasets['completed'],
                        'borderColor' => '#198754',
                        'backgroundColor' => 'rgba(25, 135, 84, 0.1)',
                        'tension' => 0.4
                    ],
                    [
                        'label' => 'Pending',
                        'data' => $datasets['pending'],
                        'borderColor' => '#ffc107',
                        'backgroundColor' => 'rgba(255, 193, 7, 0.1)',
                        'tension' => 0.4
                    ]
                ]
            ];
            break;
            
        case 'service_types':
            // Service types distribution
            $stmt = $pdo->prepare("
                SELECT 
                    st.service_type_name,
                    COUNT(a.app_id) as count
                FROM appointment a
                JOIN service_type st ON a.service_type_id = st.service_type_id
                WHERE DATE(a.app_created) BETWEEN ? AND ?
                GROUP BY st.service_type_id, st.service_type_name
                ORDER BY count DESC
            ");
            $stmt->execute([$startDate, $endDate]);
            $serviceTypeData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $labels = [];
            $data = [];
            $colors = [
                '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1'
            ];
            
            foreach ($serviceTypeData as $index => $item) {
                $labels[] = $item['service_type_name'];
                $data[] = (int)$item['count'];
            }
            
            $response['chart_data'] = [
                'labels' => $labels,
                'datasets' => [
                    [
                        'data' => $data,
                        'backgroundColor' => array_slice($colors, 0, count($data)),
                        'borderColor' => '#ffffff',
                        'borderWidth' => 2
                    ]
                ]
            ];
            break;
            
        case 'appliance_types':
            // Appliance types distribution
            $stmt = $pdo->prepare("
                SELECT 
                    at.appliances_type_name,
                    COUNT(a.app_id) as count
                FROM appointment a
                JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
                WHERE DATE(a.app_created) BETWEEN ? AND ?
                AND a.appliances_type_id IS NOT NULL
                GROUP BY at.appliances_type_id, at.appliances_type_name
                ORDER BY count DESC
            ");
            $stmt->execute([$startDate, $endDate]);
            $applianceTypeData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $labels = [];
            $data = [];
            $colors = [
                '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1', '#fd7e14', '#20c997', '#e83e8c', '#6610f2'
            ];
            
            foreach ($applianceTypeData as $index => $item) {
                $labels[] = $item['appliances_type_name'];
                $data[] = (int)$item['count'];
            }
            
            $response['chart_data'] = [
                'labels' => $labels,
                'datasets' => [
                    [
                        'data' => $data,
                        'backgroundColor' => array_slice($colors, 0, count($data)),
                        'borderColor' => '#ffffff',
                        'borderWidth' => 2
                    ]
                ]
            ];
            break;
            
        case 'revenue':
            // Daily revenue data
            $stmt = $pdo->prepare("
                SELECT 
                    DATE(a.app_created) as date,
                    SUM(CASE WHEN a.app_price > 0 THEN a.app_price ELSE 0 END) as revenue,
                    COUNT(CASE WHEN a.payment_status = 'Paid' THEN 1 END) as paid_count,
                    COUNT(CASE WHEN a.payment_status = 'Unpaid' AND a.app_status_id = 3 THEN 1 END) as unpaid_count
                FROM appointment a
                WHERE a.app_id > 0 
                AND DATE(a.app_created) >= ? 
                AND DATE(a.app_created) <= ?
                GROUP BY DATE(a.app_created)
                ORDER BY DATE(a.app_created)
            ");
            $stmt->execute([$startDate, $endDate]);
            $revenueData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $labels = [];
            $revenueDataset = [];
            $paidDataset = [];
            $unpaidDataset = [];
            
            for ($i = $timeframe - 1; $i >= 0; $i--) {
                if ($year == date('Y')) {
                    $date = date('Y-m-d', strtotime("-{$i} days"));
                } else {
                    $date = date('Y-m-d', strtotime("$year-12-31 -{$i} days"));
                }
                $labels[] = date('M j', strtotime($date));
                
                $found = false;
                foreach ($revenueData as $data) {
                    if ($data['date'] === $date) {
                        $revenueDataset[] = (float)$data['revenue'];
                        $paidDataset[] = (int)$data['paid_count'];
                        $unpaidDataset[] = (int)$data['unpaid_count'];
                        $found = true;
                        break;
                    }
                }
                
                if (!$found) {
                    $revenueDataset[] = 0;
                    $paidDataset[] = 0;
                    $unpaidDataset[] = 0;
                }
            }
            
            $response['chart_data'] = [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Revenue (₱)',
                        'data' => $revenueDataset,
                        'borderColor' => '#198754',
                        'backgroundColor' => 'rgba(25, 135, 84, 0.1)',
                        'tension' => 0.4,
                        'yAxisID' => 'y'
                    ],
                    [
                        'label' => 'Paid Jobs',
                        'data' => $paidDataset,
                        'borderColor' => '#0d6efd',
                        'backgroundColor' => 'rgba(13, 110, 253, 0.1)',
                        'tension' => 0.4,
                        'yAxisID' => 'y1'
                    ]
                ]
            ];
            break;
            
        case 'status':
            // Status distribution pie chart
            $stmt = $pdo->prepare("
                SELECT 
                    CASE 
                        WHEN a.app_status_id = 1 THEN 'Approved'
                        WHEN a.app_status_id = 2 THEN 'Pending'
                        WHEN a.app_status_id = 3 THEN 'Completed'
                        WHEN a.app_status_id = 4 THEN 'Declined'
                        WHEN a.app_status_id = 5 THEN 'In Progress'
                        ELSE 'Other'
                    END as status,
                    COUNT(*) as count
                FROM appointment a
                WHERE a.app_id > 0 
                AND DATE(a.app_created) >= ? 
                AND DATE(a.app_created) <= ?
                GROUP BY a.app_status_id
                ORDER BY count DESC
            ");
            $stmt->execute([$startDate, $endDate]);
            $statusData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $labels = [];
            $data = [];
            $colors = [];
            $colorMap = [
                'Completed' => '#198754',
                'Approved' => '#0d6efd', 
                'In Progress' => '#ffc107',
                'Pending' => '#fd7e14',
                'Declined' => '#dc3545',
                'Other' => '#6c757d'
            ];
            
            foreach ($statusData as $status) {
                $labels[] = $status['status'];
                $data[] = (int)$status['count'];
                $colors[] = $colorMap[$status['status']] ?? '#6c757d';
            }
            
            $response['chart_data'] = [
                'labels' => $labels,
                'datasets' => [
                    [
                        'data' => $data,
                        'backgroundColor' => $colors,
                        'borderWidth' => 2,
                        'borderColor' => '#ffffff'
                    ]
                ]
            ];
            break;
    }
    
    // Calculate key metrics
    $metricsStmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_appointments,
            COUNT(CASE WHEN a.app_status_id = 3 THEN 1 END) as completed_appointments,
            AVG(CASE WHEN a.app_rating > 0 THEN a.app_rating END) as avg_rating,
            AVG(CASE 
                WHEN a.app_status_id = 1 AND a.app_created IS NOT NULL 
                THEN TIMESTAMPDIFF(HOUR, a.app_created, NOW()) 
            END) as avg_response_time_hours
        FROM appointment a
        WHERE a.app_id > 0 
        AND DATE(a.app_created) >= ?
        AND DATE(a.app_created) <= ?
    ");
    $metricsStmt->execute([$startDate, $endDate]);
    $metrics = $metricsStmt->fetch(PDO::FETCH_ASSOC);
    
    $response['metrics'] = [
        'total_appointments' => (int)($metrics['total_appointments'] ?? 0),
        'completion_rate' => $metrics['total_appointments'] > 0 
            ? round(($metrics['completed_appointments'] / $metrics['total_appointments']) * 100, 1)
            : 0,
        'avg_response_time' => round($metrics['avg_response_time_hours'] ?? 0, 1),
        'customer_satisfaction' => round($metrics['avg_rating'] ?? 0, 1)
    ];
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Error fetching analytics data: ' . $e->getMessage(),
        'chart_data' => null,
        'metrics' => null
    ];
}

echo json_encode($response);
?>
