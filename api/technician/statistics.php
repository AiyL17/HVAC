<?php
header('Content-Type: application/json');

include '../../config/ini.php';

$pdo = pdo_init();
$response = array();

if (!isset($_SESSION['uid'])) {
    $response['success'] = false;
    $response['message'] = 'User not authenticated';
    echo json_encode($response);
    exit();
}

$technician_id = $_SESSION['uid'];

// Get parameters for enhanced analytics
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$timeframe = isset($_GET['timeframe']) ? intval($_GET['timeframe']) : 30;
$chart_type = isset($_GET['chart_type']) ? $_GET['chart_type'] : 'revenue';

try {
    // Get basic statistics
    $stats = array();
    
    // Total Revenue from completed jobs - filtered by year and timeframe
    $date_condition = "";
    $params = [$technician_id, $technician_id];
    
    if ($timeframe <= 30) {
        // For short timeframes, use days from current date or year-end
        if ($year == date('Y')) {
            $start_date = date('Y-m-d', strtotime("-$timeframe days"));
            $date_condition = "AND DATE(COALESCE(app_completed_at, app_created)) >= ?";
            $params[] = $start_date;
        } else {
            $end_date = "$year-12-31";
            $start_date = date('Y-m-d', strtotime("$end_date -$timeframe days"));
            $date_condition = "AND DATE(COALESCE(app_completed_at, app_created)) BETWEEN ? AND ?";
            $params[] = $start_date;
            $params[] = $end_date;
        }
    } else {
        // For longer timeframes, use the entire year
        $date_condition = "AND YEAR(COALESCE(app_completed_at, app_created)) = ?";
        $params[] = $year;
    }
    
    // Get revenue data (only for appointments with price)
    $stmt = $pdo->prepare("
        SELECT 
            COALESCE(SUM(CAST(app_price AS DECIMAL(10,2))), 0) as total_revenue,
            COUNT(*) as total_completed
        FROM appointment 
        WHERE (user_technician = ? OR user_technician_2 = ?) 
        AND app_status_id = 3 
        AND app_price IS NOT NULL 
        AND app_price != ''
        AND app_id > 0
        $date_condition
    ");
    $stmt->execute($params);
    $revenue_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get rating data separately (for all completed appointments with ratings)
    $stmt = $pdo->prepare("
        SELECT 
            COALESCE(AVG(app_rating), 0) as average_rating
        FROM appointment 
        WHERE (user_technician = ? OR user_technician_2 = ?) 
        AND app_status_id = 3 
        AND app_rating IS NOT NULL 
        AND app_rating > 0
        AND app_id > 0
        $date_condition
    ");
    $stmt->execute($params);
    $rating_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $stats['total_revenue'] = floatval($revenue_data['total_revenue']);
    $stats['average_rating'] = floatval($rating_data['average_rating']);
    
    // Completion Rate
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_assigned,
            SUM(CASE WHEN app_status_id = 3 THEN 1 ELSE 0 END) as completed
        FROM appointment 
        WHERE (user_technician = ? OR user_technician_2 = ?) 
        AND app_id > 0
    ");
    $stmt->execute([$technician_id, $technician_id]);
    $completion_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $total_assigned = intval($completion_data['total_assigned']);
    $completed = intval($completion_data['completed']);
    $stats['completion_rate'] = $total_assigned > 0 ? round(($completed / $total_assigned) * 100, 1) : 0;
    
    // Team Jobs (where technician is secondary)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as team_jobs
        FROM appointment 
        WHERE user_technician_2 = ? 
        AND app_id > 0
    ");
    $stmt->execute([$technician_id]);
    $team_data = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['team_jobs'] = intval($team_data['team_jobs']);
    
    // This Month Statistics
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as this_month_jobs,
            COALESCE(SUM(CAST(app_price AS DECIMAL(10,2))), 0) as this_month_revenue
        FROM appointment 
        WHERE (user_technician = ? OR user_technician_2 = ?) 
        AND app_status_id = 3 
        AND MONTH(app_completed_at) = MONTH(CURRENT_DATE())
        AND YEAR(app_completed_at) = YEAR(CURRENT_DATE())
        AND app_id > 0
    ");
    $stmt->execute([$technician_id, $technician_id]);
    $month_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $stats['this_month_jobs'] = intval($month_data['this_month_jobs']);
    $stats['this_month_revenue'] = floatval($month_data['this_month_revenue']);
    
    // Average Job Value
    $stats['avg_job_value'] = $completed > 0 ? round($stats['total_revenue'] / $completed, 2) : 0;
    
    // Top Service Type
    $stmt = $pdo->prepare("
        SELECT 
            st.service_type_name,
            COUNT(*) as job_count
        FROM appointment a
        JOIN service_type st ON a.service_type_id = st.service_type_id
        WHERE (a.user_technician = ? OR a.user_technician_2 = ?) 
        AND a.app_status_id = 3
        AND a.app_id > 0
        GROUP BY st.service_type_name
        ORDER BY job_count DESC
        LIMIT 1
    ");
    $stmt->execute([$technician_id, $technician_id]);
    $top_service = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['top_service_type'] = $top_service ? $top_service['service_type_name'] : 'None';
    
    // Weekly Jobs
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as weekly_jobs
        FROM appointment 
        WHERE (user_technician = ? OR user_technician_2 = ?) 
        AND app_status_id = 3
        AND WEEK(app_completed_at) = WEEK(CURRENT_DATE())
        AND YEAR(app_completed_at) = YEAR(CURRENT_DATE())
        AND app_id > 0
    ");
    $stmt->execute([$technician_id, $technician_id]);
    $week_data = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats['weekly_jobs'] = intval($week_data['weekly_jobs']);
    
    // Success Rate (jobs with rating >= 4)
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as rated_jobs,
            SUM(CASE WHEN app_rating >= 4 THEN 1 ELSE 0 END) as successful_jobs
        FROM appointment 
        WHERE (user_technician = ? OR user_technician_2 = ?) 
        AND app_status_id = 3 
        AND app_rating > 0
        AND app_id > 0
    ");
    $stmt->execute([$technician_id, $technician_id]);
    $success_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $rated_jobs = intval($success_data['rated_jobs']);
    $successful_jobs = intval($success_data['successful_jobs']);
    $stats['success_rate'] = $rated_jobs > 0 ? round(($successful_jobs / $rated_jobs) * 100, 1) : 0;
    
    // Top Three Frequent Customers
    $stmt = $pdo->prepare("
        SELECT 
            CONCAT(u.user_name, ' ', u.user_midname, ' ', u.user_lastname) as customer_name,
            COUNT(*) as appointment_count
        FROM appointment a
        JOIN user u ON a.user_id = u.user_id
        WHERE (a.user_technician = ? OR a.user_technician_2 = ?) 
        AND a.app_status_id = 3
        AND a.app_id > 0
        GROUP BY a.user_id, u.user_name, u.user_midname, u.user_lastname
        ORDER BY appointment_count DESC
        LIMIT 3
    ");
    $stmt->execute([$technician_id, $technician_id]);
    $top_customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $customer_names = array();
    foreach ($top_customers as $customer) {
        $customer_names[] = $customer['customer_name'];
    }
    
    $stats['frequent_customer'] = $customer_names[0] ?? 'No frequent customer';
    $stats['top_customers'] = $customer_names;
    
    // Estimated response and job duration (simplified)
    $stats['avg_response_time'] = 2; // Default 2 hours
    $stats['avg_job_duration'] = 4; // Default 4 hours
    
    // Monthly Revenue for selected year
    $monthly_revenue = array();
    for ($month = 1; $month <= 12; $month++) {
        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(CAST(app_price AS DECIMAL(10,2))), 0) as revenue
            FROM appointment 
            WHERE (user_technician = ? OR user_technician_2 = ?) 
            AND app_status_id = 3 
            AND app_price IS NOT NULL 
            AND app_price != ''
            AND MONTH(COALESCE(app_completed_at, app_created)) = ?
            AND YEAR(COALESCE(app_completed_at, app_created)) = ?
            AND app_id > 0
        ");
        $stmt->execute([$technician_id, $technician_id, $month, $year]);
        $month_revenue = $stmt->fetch(PDO::FETCH_ASSOC);
        $monthly_revenue[] = floatval($month_revenue['revenue']);
    }
    $stats['monthly_revenue'] = $monthly_revenue;
    
    // Enhanced Chart Data for different chart types and timeframes
    $chart_data = array();
    
    if ($chart_type === 'revenue') {
        // Revenue chart data based on timeframe
        if ($timeframe <= 30) {
            // Daily data for short timeframes
            $labels = array();
            $revenue_data = array();
            
            for ($i = $timeframe - 1; $i >= 0; $i--) {
                // Use current date if selected year is current year, otherwise use year-end
                if ($year == date('Y')) {
                    $target_date = date('Y-m-d', strtotime("-$i days"));
                } else {
                    $target_date = date('Y-m-d', strtotime("$year-12-31 -$i days"));
                }
                $date = date('M j', strtotime($target_date));
                $labels[] = $date;
                
                $stmt = $pdo->prepare("
                    SELECT COALESCE(SUM(CAST(app_price AS DECIMAL(10,2))), 0) as revenue
                    FROM appointment 
                    WHERE (user_technician = ? OR user_technician_2 = ?) 
                    AND app_status_id = 3 
                    AND app_price IS NOT NULL 
                    AND app_price != ''
                    AND DATE(COALESCE(app_completed_at, app_created)) = ?
                    AND app_id > 0
                ");
                $stmt->execute([$technician_id, $technician_id, $target_date]);
                $day_revenue = $stmt->fetch(PDO::FETCH_ASSOC);
                $revenue_data[] = floatval($day_revenue['revenue']);
            }
            
            $chart_data = array(
                'labels' => $labels,
                'datasets' => array(
                    array(
                        'label' => 'Daily Revenue (₱)',
                        'data' => $revenue_data,
                        'borderColor' => '#28a745',
                        'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                        'borderWidth' => 3,
                        'fill' => true,
                        'tension' => 0.4
                    )
                )
            );
        } else {
            // Monthly data for longer timeframes
            $chart_data = array(
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'datasets' => array(
                    array(
                        'label' => 'Monthly Revenue (₱)',
                        'data' => $monthly_revenue,
                        'borderColor' => '#28a745',
                        'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                        'borderWidth' => 3,
                        'fill' => true,
                        'tension' => 0.4
                    )
                )
            );
        }
    } elseif ($chart_type === 'jobs') {
        // Job completion chart data
        $labels = array();
        $job_data = array();
        
        for ($i = $timeframe - 1; $i >= 0; $i--) {
            // Use current date if selected year is current year, otherwise use year-end
            if ($year == date('Y')) {
                $target_date = date('Y-m-d', strtotime("-$i days"));
            } else {
                $target_date = date('Y-m-d', strtotime("$year-12-31 -$i days"));
            }
            $date = date('M j', strtotime($target_date));
            $labels[] = $date;
            
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as job_count
                FROM appointment 
                WHERE (user_technician = ? OR user_technician_2 = ?) 
                AND app_status_id = 3
                AND DATE(COALESCE(app_completed_at, app_created)) = ?
                AND app_id > 0
            ");
            $stmt->execute([$technician_id, $technician_id, $target_date]);
            $day_jobs = $stmt->fetch(PDO::FETCH_ASSOC);
            $job_data[] = intval($day_jobs['job_count']);
        }
        
        $chart_data = array(
            'labels' => $labels,
            'datasets' => array(
                array(
                    'label' => 'Jobs Completed',
                    'data' => $job_data,
                    'backgroundColor' => '#007bff',
                    'borderColor' => '#0056b3',
                    'borderWidth' => 2
                )
            )
        );
    } elseif ($chart_type === 'ratings') {
        // Rating trends chart data
        $labels = array();
        $rating_data = array();
        
        for ($i = $timeframe - 1; $i >= 0; $i--) {
            // Use current date if selected year is current year, otherwise use year-end
            if ($year == date('Y')) {
                $target_date = date('Y-m-d', strtotime("-$i days"));
            } else {
                $target_date = date('Y-m-d', strtotime("$year-12-31 -$i days"));
            }
            $date = date('M j', strtotime($target_date));
            $labels[] = $date;
            
            $stmt = $pdo->prepare("
                SELECT COALESCE(AVG(app_rating), 0) as avg_rating
                FROM appointment 
                WHERE (user_technician = ? OR user_technician_2 = ?) 
                AND app_status_id = 3
                AND app_rating > 0
                AND DATE(COALESCE(app_completed_at, app_created)) = ?
                AND app_id > 0
            ");
            $stmt->execute([$technician_id, $technician_id, $target_date]);
            $day_rating = $stmt->fetch(PDO::FETCH_ASSOC);
            $rating_data[] = floatval($day_rating['avg_rating']);
        }
        
        $chart_data = array(
            'labels' => $labels,
            'datasets' => array(
                array(
                    'label' => 'Average Rating',
                    'data' => $rating_data,
                    'borderColor' => '#ffc107',
                    'backgroundColor' => 'rgba(255, 193, 7, 0.1)',
                    'borderWidth' => 3,
                    'fill' => false,
                    'tension' => 0.4
                )
            )
        );
    }
    
    $stats['chart_data'] = $chart_data;
    
    // Additional metrics for the enhanced analytics - filtered by year and timeframe
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total_jobs
        FROM appointment 
        WHERE (user_technician = ? OR user_technician_2 = ?) 
        AND app_status_id = 3 
        AND app_id > 0
        $date_condition
    ");
    $stmt->execute($params);
    $job_count = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $stats['total_jobs'] = intval($job_count['total_jobs']);
    $stats['avg_revenue'] = $stats['total_jobs'] > 0 ? round($stats['total_revenue'] / $stats['total_jobs'], 2) : 0;
    
    // Service Expertise (average rating per service type)
    $stmt = $pdo->prepare("
        SELECT 
            st.service_type_name as service_type,
            AVG(a.app_rating) as avg_rating,
            COUNT(*) as job_count
        FROM appointment a
        JOIN service_type st ON a.service_type_id = st.service_type_id
        WHERE (a.user_technician = ? OR a.user_technician_2 = ?) 
        AND a.app_status_id = 3
        AND a.app_rating > 0
        AND a.app_id > 0
        GROUP BY st.service_type_name
        HAVING job_count >= 1
        ORDER BY avg_rating DESC
    ");
    $stmt->execute([$technician_id, $technician_id]);
    $service_expertise = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stats['service_expertise'] = array_map(function($item) {
        return [
            'service_type' => $item['service_type'],
            'avg_rating' => round(floatval($item['avg_rating']), 1),
            'job_count' => intval($item['job_count'])
        ];
    }, $service_expertise);
    
    // Rating Distribution
    $rating_distribution = [0, 0, 0, 0, 0]; // 1-5 stars
    $stmt = $pdo->prepare("
        SELECT 
            app_rating,
            COUNT(*) as count
        FROM appointment 
        WHERE (user_technician = ? OR user_technician_2 = ?) 
        AND app_status_id = 3 
        AND app_rating > 0
        AND app_id > 0
        GROUP BY app_rating
    ");
    $stmt->execute([$technician_id, $technician_id]);
    $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($ratings as $rating) {
        $star = intval($rating['app_rating']);
        if ($star >= 1 && $star <= 5) {
            $rating_distribution[$star - 1] = intval($rating['count']);
        }
    }
    $stats['rating_distribution'] = $rating_distribution;
    
    // Recent Jobs (all completed jobs)
    $stmt = $pdo->prepare("
        SELECT 
            a.app_completed_at as date,
            st.service_type_name as service_type,
            a.app_id as job_id,
            a.app_rating as rating,
            CAST(a.app_price AS DECIMAL(10,2)) as revenue
        FROM appointment a
        JOIN service_type st ON a.service_type_id = st.service_type_id
        WHERE (a.user_technician = ? OR a.user_technician_2 = ?) 
        AND a.app_status_id = 3
        AND a.app_id > 0
        ORDER BY a.app_completed_at DESC
    ");
    $stmt->execute([$technician_id, $technician_id]);
    $recent_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stats['recent_jobs'] = array_map(function($job) {
        return [
            'date' => $job['date'],
            'service_type' => $job['service_type'],
            'job_id' => $job['job_id'],
            'rating' => intval($job['rating']),
            'revenue' => floatval($job['revenue'])
        ];
    }, $recent_jobs);
    
    // Team Collaboration Analytics
    
    // Partnership Performance - Success rates with specific partners
    $stmt = $pdo->prepare("
        SELECT 
            CASE 
                WHEN a.user_technician = ? THEN CONCAT(u2.user_name, ' ', u2.user_lastname)
                ELSE CONCAT(u1.user_name, ' ', u1.user_lastname)
            END as partner_name,
            COUNT(*) as total_jobs,
            AVG(a.app_rating) as avg_rating,
            COUNT(CASE WHEN a.app_status_id = 3 THEN 1 END) as completed_jobs,
            AVG(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as avg_revenue
        FROM appointment a
        LEFT JOIN user u1 ON a.user_technician = u1.user_id
        LEFT JOIN user u2 ON a.user_technician_2 = u2.user_id
        WHERE (a.user_technician = ? OR a.user_technician_2 = ?)
        AND a.user_technician IS NOT NULL 
        AND a.user_technician_2 IS NOT NULL
        AND a.app_id > 0
        GROUP BY partner_name
        HAVING total_jobs >= 2
        ORDER BY avg_rating DESC, total_jobs DESC
        LIMIT 3
    ");
    $stmt->execute([$technician_id, $technician_id, $technician_id]);
    $partnership_performance = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stats['partnership_performance'] = array_map(function($partner) {
        $completion_rate = $partner['total_jobs'] > 0 ? round(($partner['completed_jobs'] / $partner['total_jobs']) * 100, 1) : 0;
        return [
            'partner_name' => $partner['partner_name'],
            'total_jobs' => intval($partner['total_jobs']),
            'avg_rating' => round(floatval($partner['avg_rating']), 1),
            'completion_rate' => $completion_rate,
            'avg_revenue' => round(floatval($partner['avg_revenue']), 2)
        ];
    }, $partnership_performance);
    
    // Team vs Solo Performance Comparison
    $stmt = $pdo->prepare("
        SELECT 
            CASE 
                WHEN a.user_technician_2 IS NOT NULL THEN 'Team'
                ELSE 'Solo'
            END as work_type,
            COUNT(*) as job_count,
            AVG(a.app_rating) as avg_rating,
            COUNT(CASE WHEN a.app_status_id = 3 THEN 1 END) as completed_jobs,
            AVG(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as avg_revenue,
            AVG(TIMESTAMPDIFF(DAY, a.app_created, a.app_completed_at)) as avg_completion_days
        FROM appointment a
        WHERE (a.user_technician = ? OR a.user_technician_2 = ?)
        AND a.app_status_id = 3
        AND a.app_id > 0
        GROUP BY work_type
    ");
    $stmt->execute([$technician_id, $technician_id]);
    $team_vs_solo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stats['team_vs_solo_performance'] = array_map(function($performance) {
        $completion_rate = $performance['job_count'] > 0 ? round(($performance['completed_jobs'] / $performance['job_count']) * 100, 1) : 0;
        return [
            'work_type' => $performance['work_type'],
            'job_count' => intval($performance['job_count']),
            'avg_rating' => round(floatval($performance['avg_rating']), 1),
            'completion_rate' => $completion_rate,
            'avg_revenue' => round(floatval($performance['avg_revenue']), 2),
            'avg_completion_days' => round(floatval($performance['avg_completion_days']), 1)
        ];
    }, $team_vs_solo);
    
    // Role Distribution (Primary vs Secondary Technician)
    $stmt = $pdo->prepare("
        SELECT 
            CASE 
                WHEN a.user_technician = ? THEN 'Primary'
                ELSE 'Secondary'
            END as role_type,
            COUNT(*) as job_count,
            AVG(a.app_rating) as avg_rating,
            COUNT(CASE WHEN a.app_status_id = 3 THEN 1 END) as completed_jobs,
            AVG(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as avg_revenue
        FROM appointment a
        WHERE (a.user_technician = ? OR a.user_technician_2 = ?)
        AND a.app_id > 0
        GROUP BY role_type
    ");
    $stmt->execute([$technician_id, $technician_id, $technician_id]);
    $role_distribution = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stats['role_distribution'] = array_map(function($role) {
        $completion_rate = $role['job_count'] > 0 ? round(($role['completed_jobs'] / $role['job_count']) * 100, 1) : 0;
        return [
            'role_type' => $role['role_type'],
            'job_count' => intval($role['job_count']),
            'avg_rating' => round(floatval($role['avg_rating']), 1),
            'completion_rate' => $completion_rate,
            'avg_revenue' => round(floatval($role['avg_revenue']), 2)
        ];
    }, $role_distribution);
    
    // Team Collaboration Metrics Summary
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(CASE WHEN a.user_technician_2 IS NOT NULL THEN 1 END) as team_jobs_count,
            COUNT(CASE WHEN a.user_technician_2 IS NULL THEN 1 END) as solo_jobs_count,
            COUNT(CASE WHEN a.user_technician = ? THEN 1 END) as primary_role_count,
            COUNT(CASE WHEN a.user_technician_2 = ? THEN 1 END) as secondary_role_count,
            COUNT(DISTINCT CASE WHEN a.user_technician = ? THEN a.user_technician_2 ELSE a.user_technician END) as unique_partners
        FROM appointment a
        WHERE (a.user_technician = ? OR a.user_technician_2 = ?)
        AND a.app_id > 0
    ");
    $stmt->execute([$technician_id, $technician_id, $technician_id, $technician_id, $technician_id]);
    $collaboration_summary = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $total_jobs = intval($collaboration_summary['team_jobs_count']) + intval($collaboration_summary['solo_jobs_count']);
    $team_job_percentage = $total_jobs > 0 ? round((intval($collaboration_summary['team_jobs_count']) / $total_jobs) * 100, 1) : 0;
    
    $stats['collaboration_summary'] = [
        'team_jobs_count' => intval($collaboration_summary['team_jobs_count']),
        'solo_jobs_count' => intval($collaboration_summary['solo_jobs_count']),
        'team_job_percentage' => $team_job_percentage,
        'primary_role_count' => intval($collaboration_summary['primary_role_count']),
        'secondary_role_count' => intval($collaboration_summary['secondary_role_count']),
        'unique_partners' => intval($collaboration_summary['unique_partners'])
    ];
    
    $response['success'] = true;
    $response = array_merge($response, $stats);
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
?>
