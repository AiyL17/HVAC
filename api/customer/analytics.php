<?php
session_start();

// Disable error reporting to prevent HTML output in JSON response
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once '../../config/ini.php';
require_once '../../class/userClass.php';

$userClass = new userClass();
$pdo = pdo_init();

// Check if user is logged in and is a customer
if (!isset($_SESSION['uid'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get user details to verify customer type
$userDetails = $userClass->userDetails($_SESSION['uid']);
if (!$userDetails || $userDetails->user_type !== 'customer') {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$customer_id = $_SESSION['uid'];

try {
    // 1. Basic appointment statistics
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_appointments,
            AVG(a.app_rating) as overall_satisfaction,
            SUM(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as lifetime_value,
            COUNT(DISTINCT YEAR(a.app_schedule)) as years_as_customer
        FROM appointment a
        WHERE a.user_id = ?
    ");
    $stmt->execute([$customer_id]);
    $loyaltyMetrics = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Service type breakdown
    $stmt = $pdo->prepare("
        SELECT 
            st.service_type_name,
            COUNT(*) as total_services,
            AVG(a.app_rating) as avg_satisfaction,
            COUNT(CASE WHEN a.app_status_id = 3 THEN 1 END) as completed_count
        FROM appointment a
        JOIN service_type st ON a.service_type_id = st.service_type_id
        WHERE a.user_id = ?
        GROUP BY a.service_type_id, st.service_type_name
        ORDER BY total_services DESC
    ");
    $stmt->execute([$customer_id]);
    $serviceEfficiency = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Monthly trends
    $stmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(a.app_schedule, '%Y-%m') as service_month,
            COUNT(*) as services_count,
            AVG(a.app_rating) as avg_rating
        FROM appointment a
        WHERE a.user_id = ? AND a.app_schedule >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(a.app_schedule, '%Y-%m')
        ORDER BY service_month ASC
    ");
    $stmt->execute([$customer_id]);
    $qualityTrends = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Technician relationships
    $stmt = $pdo->prepare("
        SELECT 
            u1.user_name as technician_name,
            COUNT(*) as times_served,
            AVG(a.app_rating) as avg_rating_given
        FROM appointment a
        JOIN user u1 ON a.user_technician = u1.user_id
        WHERE a.user_id = ? AND a.user_technician IS NOT NULL
        GROUP BY a.user_technician, u1.user_name
        ORDER BY times_served DESC
        LIMIT 5
    ");
    $stmt->execute([$customer_id]);
    $technicianRelationships = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 5. Equipment Health Score
    $stmt = $pdo->prepare("
        SELECT 
            AVG(a.app_rating) as avg_rating,
            COUNT(*) as total_services,
            COUNT(CASE WHEN a.app_rating >= 4 THEN 1 END) as good_services,
            COUNT(CASE WHEN a.app_rating <= 2 THEN 1 END) as poor_services
        FROM appointment a
        WHERE a.user_id = ? AND a.app_rating IS NOT NULL
    ");
    $stmt->execute([$customer_id]);
    $healthData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Calculate equipment health score (0-100)
    $healthScore = 0;
    if ($healthData['total_services'] > 0) {
        $goodRatio = $healthData['good_services'] / $healthData['total_services'];
        $avgRating = $healthData['avg_rating'];
        $healthScore = round(($goodRatio * 0.6 + ($avgRating / 5) * 0.4) * 100);
    }

    // 6. Enhanced Equipment Performance Trends (last 6 months)
    
    // 6a. Overall monthly performance trends
    $stmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(a.app_schedule, '%Y-%m') as month,
            AVG(a.app_rating) as avg_rating,
            COUNT(*) as service_count,
            COUNT(CASE WHEN a.app_rating >= 4 THEN 1 END) as reliable_services,
            COUNT(CASE WHEN a.app_status_id = 3 THEN 1 END) as completed_services,
            AVG(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as avg_cost
        FROM appointment a
        WHERE a.user_id = ? AND a.app_schedule >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(a.app_schedule, '%Y-%m')
        ORDER BY month ASC
    ");
    $stmt->execute([$customer_id]);
    $performanceTrends = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 6b. Equipment-specific performance trends
    $stmt = $pdo->prepare("
        SELECT 
            at.appliances_type_name as equipment_type,
            DATE_FORMAT(a.app_schedule, '%Y-%m') as month,
            AVG(a.app_rating) as avg_rating,
            COUNT(*) as service_count,
            COUNT(CASE WHEN a.app_rating >= 4 THEN 1 END) as reliable_services,
            COUNT(CASE WHEN a.app_rating <= 2 THEN 1 END) as poor_services,
            AVG(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as avg_cost
        FROM appointment a
        JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
        WHERE a.user_id = ? AND a.app_schedule >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY a.appliances_type_id, at.appliances_type_name, DATE_FORMAT(a.app_schedule, '%Y-%m')
        ORDER BY at.appliances_type_name, month ASC
    ");
    $stmt->execute([$customer_id]);
    $equipmentPerformanceTrends = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 6c. Service type performance analysis
    $stmt = $pdo->prepare("
        SELECT 
            st.service_type_name,
            DATE_FORMAT(a.app_schedule, '%Y-%m') as month,
            AVG(a.app_rating) as avg_rating,
            COUNT(*) as service_count,
            COUNT(CASE WHEN a.app_rating >= 4 THEN 1 END) as successful_services,
            AVG(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as avg_cost
        FROM appointment a
        JOIN service_type st ON a.service_type_id = st.service_type_id
        WHERE a.user_id = ? AND a.app_schedule >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY a.service_type_id, st.service_type_name, DATE_FORMAT(a.app_schedule, '%Y-%m')
        ORDER BY st.service_type_name, month ASC
    ");
    $stmt->execute([$customer_id]);
    $serviceTypePerformance = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 6d. Calculate reliability scores for each equipment type
    $equipmentReliabilityScores = [];
    foreach ($equipmentPerformanceTrends as $trend) {
        $equipmentType = $trend['equipment_type'];
        if (!isset($equipmentReliabilityScores[$equipmentType])) {
            $equipmentReliabilityScores[$equipmentType] = [
                'total_services' => 0,
                'reliable_services' => 0,
                'poor_services' => 0,
                'total_rating' => 0,
                'months' => []
            ];
        }
        
        $equipmentReliabilityScores[$equipmentType]['total_services'] += $trend['service_count'];
        $equipmentReliabilityScores[$equipmentType]['reliable_services'] += $trend['reliable_services'];
        $equipmentReliabilityScores[$equipmentType]['poor_services'] += $trend['poor_services'];
        $equipmentReliabilityScores[$equipmentType]['total_rating'] += ($trend['avg_rating'] * $trend['service_count']);
        $equipmentReliabilityScores[$equipmentType]['months'][] = [
            'month' => $trend['month'],
            'rating' => $trend['avg_rating'],
            'reliability' => $trend['service_count'] > 0 ? round(($trend['reliable_services'] / $trend['service_count']) * 100) : 0
        ];
    }
    
    // Calculate final reliability scores
    $equipmentReliability = [];
    foreach ($equipmentReliabilityScores as $equipment => $data) {
        if ($data['total_services'] > 0) {
            $reliabilityScore = round(($data['reliable_services'] / $data['total_services']) * 100);
            $avgRating = round($data['total_rating'] / $data['total_services'], 1);
            
            $equipmentReliability[] = [
                'equipment' => $equipment,
                'reliability_score' => $reliabilityScore,
                'avg_rating' => $avgRating,
                'total_services' => $data['total_services'],
                'trend' => $reliabilityScore >= 90 ? 'excellent' : ($reliabilityScore >= 75 ? 'good' : ($reliabilityScore >= 60 ? 'fair' : 'poor')),
                'monthly_data' => $data['months']
            ];
        }
    }

    // 7. Maintenance Predictions
    $stmt = $pdo->prepare("
        SELECT 
            at.appliances_type_name as equipment,
            MAX(a.app_schedule) as last_service,
            COUNT(*) as service_frequency,
            AVG(a.app_rating) as avg_rating
        FROM appointment a
        JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
        WHERE a.user_id = ?
        GROUP BY a.appliances_type_id, at.appliances_type_name
        ORDER BY last_service DESC
    ");
    $stmt->execute([$customer_id]);
    $maintenanceData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate next service predictions
    $maintenancePredictions = [];
    foreach ($maintenanceData as $equipment) {
        $lastService = new DateTime($equipment['last_service']);
        $today = new DateTime();
        $daysSinceService = $today->diff($lastService)->days;
        
        // Predict next service based on frequency (every 90-180 days typical)
        $recommendedInterval = 120; // days
        $nextServiceDate = clone $lastService;
        $nextServiceDate->add(new DateInterval('P' . $recommendedInterval . 'D'));
        $daysUntil = $today->diff($nextServiceDate)->days;
        
        // Determine priority
        $priority = 'Low';
        if ($daysSinceService > $recommendedInterval) {
            $priority = 'High';
        } elseif ($daysUntil <= 30) {
            $priority = 'Medium';
        }
        
        $maintenancePredictions[] = [
            'equipment' => $equipment['equipment'],
            'nextService' => $nextServiceDate->format('Y-m-d'),
            'priority' => $priority,
            'daysUntil' => $daysUntil
        ];
    }

    // 8. Equipment Reliability Analysis
    $stmt = $pdo->prepare("
        SELECT 
            at.appliances_type_name as equipment,
            COUNT(*) as total_services,
            COUNT(CASE WHEN a.app_rating >= 4 THEN 1 END) as successful_services,
            COUNT(CASE WHEN a.app_rating <= 2 THEN 1 END) as issues
        FROM appointment a
        JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
        WHERE a.user_id = ?
        GROUP BY a.appliances_type_id, at.appliances_type_name
        ORDER BY total_services DESC
    ");
    $stmt->execute([$customer_id]);
    $reliabilityData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate reliability scores
    $reliabilityAnalysis = [];
    foreach ($reliabilityData as $equipment) {
        $score = 0;
        if ($equipment['total_services'] > 0) {
            $score = round(($equipment['successful_services'] / $equipment['total_services']) * 100);
        }
        
        $reliabilityAnalysis[] = [
            'equipment' => $equipment['equipment'],
            'score' => $score,
            'trend' => $score >= 90 ? 'up' : ($score >= 70 ? 'stable' : 'down'),
            'issues' => $equipment['issues']
        ];
    }

    // 9. Enhanced Cost Analysis & Optimization
    
    // 9a. Service Type Cost Analysis (Repair vs Maintenance)
    $stmt = $pdo->prepare("
        SELECT 
            st.service_type_name,
            SUM(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as total_cost,
            COUNT(*) as service_count,
            AVG(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as avg_cost,
            COUNT(CASE WHEN a.app_status_id = 3 THEN 1 END) as completed_services,
            AVG(a.app_rating) as avg_satisfaction
        FROM appointment a
        JOIN service_type st ON a.service_type_id = st.service_type_id
        WHERE a.user_id = ? AND a.app_schedule >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY a.service_type_id, st.service_type_name
        ORDER BY total_cost DESC
    ");
    $stmt->execute([$customer_id]);
    $costAnalysis = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 9b. Equipment-specific cost breakdown
    $stmt = $pdo->prepare("
        SELECT 
            at.appliances_type_name as equipment,
            st.service_type_name,
            SUM(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as total_cost,
            COUNT(*) as service_count,
            AVG(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as avg_cost
        FROM appointment a
        JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
        JOIN service_type st ON a.service_type_id = st.service_type_id
        WHERE a.user_id = ? AND a.app_schedule >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY a.appliances_type_id, a.service_type_id, at.appliances_type_name, st.service_type_name
        ORDER BY total_cost DESC
    ");
    $stmt->execute([$customer_id]);
    $equipmentCostBreakdown = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 9c. Monthly cost trends
    $stmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(a.app_schedule, '%Y-%m') as month,
            SUM(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as monthly_cost,
            COUNT(*) as service_count,
            AVG(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as avg_monthly_cost
        FROM appointment a
        WHERE a.user_id = ? AND a.app_schedule >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(a.app_schedule, '%Y-%m')
        ORDER BY month ASC
    ");
    $stmt->execute([$customer_id]);
    $monthlyCostTrends = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 9d. Calculate cost optimization insights
    $repairCosts = 0;
    $maintenanceCosts = 0;
    $repairCount = 0;
    $maintenanceCount = 0;
    
    foreach ($costAnalysis as $service) {
        if (stripos($service['service_type_name'], 'repair') !== false) {
            $repairCosts += $service['total_cost'];
            $repairCount += $service['service_count'];
        } elseif (stripos($service['service_type_name'], 'maintenance') !== false) {
            $maintenanceCosts += $service['total_cost'];
            $maintenanceCount += $service['service_count'];
        }
    }
    
    // Calculate potential savings
    $totalCosts = $repairCosts + $maintenanceCosts;
    $repairRatio = $totalCosts > 0 ? ($repairCosts / $totalCosts) * 100 : 0;
    $maintenanceRatio = $totalCosts > 0 ? ($maintenanceCosts / $totalCosts) * 100 : 0;
    
    // Cost optimization recommendations
    $costOptimization = [
        'repair_vs_maintenance' => [
            'repair_costs' => $repairCosts,
            'maintenance_costs' => $maintenanceCosts,
            'repair_ratio' => round($repairRatio, 1),
            'maintenance_ratio' => round($maintenanceRatio, 1),
            'potential_savings' => $repairRatio > 70 ? round($repairCosts * 0.3, 2) : 0
        ],
        'insights' => [],
        'recommendations' => []
    ];
    
    // Generate insights based on cost patterns
    if ($repairRatio > 70) {
        $costOptimization['insights'][] = "High repair costs detected - " . round($repairRatio, 1) . "% of total spending";
        $costOptimization['recommendations'][] = "Consider increasing preventive maintenance to reduce repair costs";
    }
    
    if ($maintenanceRatio > 60) {
        $costOptimization['insights'][] = "Good maintenance investment - " . round($maintenanceRatio, 1) . "% of spending on prevention";
        $costOptimization['recommendations'][] = "Continue current maintenance schedule to prevent costly repairs";
    }
    
    if ($totalCosts > 0) {
        $avgMonthlySpend = $totalCosts / 12;
        if ($avgMonthlySpend > 500) {
            $costOptimization['insights'][] = "High monthly average spend: ₱" . number_format($avgMonthlySpend, 2);
            $costOptimization['recommendations'][] = "Review service frequency and consider bulk service packages";
        }
    }

    // 10. Enhanced Emergency & Risk Management
    
    // 10a. Comprehensive Risk Assessment
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(CASE WHEN a.app_rating <= 2 THEN 1 END) as poor_ratings,
            COUNT(CASE WHEN a.app_schedule > CURDATE() THEN 1 END) as upcoming_services,
            COUNT(CASE WHEN a.app_schedule < DATE_SUB(CURDATE(), INTERVAL 180 DAY) THEN 1 END) as overdue_maintenance,
            COUNT(CASE WHEN a.app_status_id = 4 THEN 1 END) as declined_services,
            COUNT(CASE WHEN a.app_status_id = 10 THEN 1 END) as cancelled_services,
            COUNT(*) as total_appointments,
            SUM(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as total_spent,
            AVG(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as avg_cost,
            COUNT(CASE WHEN a.payment_status = 'Unpaid' AND a.app_status_id = 3 THEN 1 END) as unpaid_completed
        FROM appointment a
        WHERE a.user_id = ?
    ");
    $stmt->execute([$customer_id]);
    $riskData = $stmt->fetch(PDO::FETCH_ASSOC);

    // 10b. Emergency Response Analytics
    $stmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(a.app_schedule, '%Y-%m') as month,
            COUNT(CASE WHEN a.app_rating <= 2 THEN 1 END) as emergency_calls,
            AVG(TIMESTAMPDIFF(DAY, a.app_created, a.app_schedule)) as avg_response_time,
            COUNT(*) as total_services,
            COUNT(CASE WHEN a.app_status_id = 3 THEN 1 END) as resolved_services
        FROM appointment a
        WHERE a.user_id = ? AND a.app_schedule >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(a.app_schedule, '%Y-%m')
        ORDER BY month ASC
    ");
    $stmt->execute([$customer_id]);
    $emergencyResponse = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 10c. Equipment Failure Patterns
    $stmt = $pdo->prepare("
        SELECT 
            at.appliances_type_name as appliance_type,
            COUNT(CASE WHEN a.app_rating <= 2 THEN 1 END) as failure_count,
            COUNT(*) as total_services,
            AVG(a.app_rating) as avg_rating,
            COUNT(CASE WHEN st.service_type_name LIKE '%repair%' OR st.service_type_name LIKE '%fix%' OR st.service_type_name LIKE '%maintenance%' THEN 1 END) as repair_frequency,
            MAX(a.app_schedule) as last_service_date,
            CASE 
                WHEN COUNT(CASE WHEN a.app_rating <= 2 THEN 1 END) > COUNT(*) * 0.3 THEN 'increasing'
                WHEN COUNT(CASE WHEN a.app_rating <= 2 THEN 1 END) < COUNT(*) * 0.1 THEN 'decreasing' 
                ELSE 'stable'
            END as trend
        FROM appointment a
        LEFT JOIN appliances_type at ON a.appliances_type_id = at.appliances_type_id
        LEFT JOIN service_type st ON a.service_type_id = st.service_type_id
        WHERE a.user_id = ? AND a.app_schedule >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY a.appliances_type_id, at.appliances_type_name
        HAVING total_services > 0
        ORDER BY failure_count DESC, repair_frequency DESC
    ");
    $stmt->execute([$customer_id]);
    $failurePatterns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 10d. Financial Risk Monitoring (with invoice integration)
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(CASE WHEN a.payment_status = 'Unpaid' AND a.app_status_id = 3 THEN 1 END) as unpaid_services,
            SUM(CASE WHEN a.payment_status = 'Unpaid' AND a.app_price IS NOT NULL AND a.app_price != '' 
                THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as overdue_amount,
            COUNT(CASE WHEN a.payment_status = 'Paid' THEN 1 END) as paid_services,
            AVG(CASE WHEN a.payment_status = 'Unpaid' AND a.app_status_id = 3 
                THEN TIMESTAMPDIFF(DAY, a.app_completed_at, CURDATE()) ELSE 0 END) as avg_payment_delay,
            COUNT(CASE WHEN a.app_schedule < CURDATE() AND a.app_status_id IN (1, 2) THEN 1 END) as overdue_appointments
        FROM appointment a
        WHERE a.user_id = ?
    ");
    $stmt->execute([$customer_id]);
    $financialRisk = $stmt->fetch(PDO::FETCH_ASSOC);

    // 10e. Enhanced Risk Calculations - only show if there's actual data
    $totalServices = $riskData['total_appointments'];
    
    $riskAssessment = [];
    
    // Only calculate risks if there are actual appointments
    if ($totalServices > 0) {
        // Equipment Failure Risk (based on poor ratings and repair frequency)
        $failureRate = ($riskData['poor_ratings'] / $totalServices) * 100;
        $equipmentFailureRisk = min($failureRate * 2, 100);
        
        // Service Disruption Risk (based on overdue maintenance and cancellations)
        $disruptionRate = (($riskData['overdue_maintenance'] + $riskData['cancelled_services']) / $totalServices) * 100;
        $serviceDisruptionRisk = min($disruptionRate * 1.5, 100);
        
        // Cost Overrun Risk (based on spending patterns and unpaid services)
        $avgMonthlySpend = $riskData['total_spent'] / 12;
        $unpaidRatio = $totalServices > 0 ? ($riskData['unpaid_completed'] / $totalServices) * 100 : 0;
        $costOverrunRisk = min(($avgMonthlySpend > 1000 ? 40 : 20) + $unpaidRatio, 100);

        $riskAssessment = [
            [
                'type' => 'Equipment Failure', 
                'level' => $equipmentFailureRisk >= 60 ? 'High' : ($equipmentFailureRisk >= 30 ? 'Medium' : 'Low'), 
                'probability' => round($equipmentFailureRisk),
                'factors' => [
                    'Poor service ratings: ' . $riskData['poor_ratings'],
                    'Failure rate: ' . round($failureRate, 1) . '%'
                ]
            ],
            [
                'type' => 'Service Disruption', 
                'level' => $serviceDisruptionRisk >= 60 ? 'High' : ($serviceDisruptionRisk >= 30 ? 'Medium' : 'Low'), 
                'probability' => round($serviceDisruptionRisk),
                'factors' => [
                    'Overdue maintenance: ' . $riskData['overdue_maintenance'],
                    'Cancelled services: ' . $riskData['cancelled_services']
                ]
            ],
            [
                'type' => 'Cost Overruns', 
                'level' => $costOverrunRisk >= 60 ? 'High' : ($costOverrunRisk >= 30 ? 'Medium' : 'Low'), 
                'probability' => round($costOverrunRisk),
                'factors' => [
                    'Monthly avg spend: ₱' . number_format($avgMonthlySpend, 2),
                    'Unpaid services: ' . $riskData['unpaid_completed']
                ]
            ]
        ];
    }


    // Return comprehensive analytics data
    echo json_encode([
        'success' => true,
        'data' => [
            'loyalty_metrics' => $loyaltyMetrics,
            'service_efficiency' => $serviceEfficiency,
            'quality_trends' => $qualityTrends,
            'technician_relationships' => $technicianRelationships,
            // New Equipment Health & Maintenance Intelligence data
            'equipment_health' => [
                'score' => $healthScore,
                'status' => $healthScore >= 80 ? 'Excellent' : ($healthScore >= 60 ? 'Good' : 'Needs Attention')
            ],
            'equipment_trends' => $performanceTrends,
            'equipment_performance_trends' => $equipmentPerformanceTrends,
            'service_type_performance' => $serviceTypePerformance,
            'equipment_reliability' => $equipmentReliability,
            'maintenance_predictions' => $maintenancePredictions,
            'reliability_analysis' => $reliabilityAnalysis,
            'cost_analysis' => $costAnalysis,
            'cost_optimization' => $costOptimization,
            'equipment_cost_breakdown' => $equipmentCostBreakdown,
            'monthly_cost_trends' => $monthlyCostTrends,
            // Enhanced Emergency & Risk Management data
            'risk_assessment' => $riskAssessment,
            'emergency_response' => $emergencyResponse,
            'equipment_failure_patterns' => $failurePatterns,
            'financial_risk' => [
                'total_overdue' => round($financialRisk['overdue_amount'], 2),
                'unpaid_services' => $financialRisk['unpaid_services'],
                'avg_payment_delay' => round($financialRisk['avg_payment_delay'], 1),
                'overdue_appointments' => $financialRisk['overdue_appointments'],
                'risk_level' => $costOverrunRisk >= 60 ? 'High' : ($costOverrunRisk >= 30 ? 'Medium' : 'Low')
            ],
            // Legacy empty arrays for backward compatibility
            'seasonal_patterns' => [],
            'peak_usage_insights' => [],
            'service_recommendations' => []
        ]
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>
