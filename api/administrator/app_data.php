<?php
header('Content-Type: application/json');

include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

// Get parameters
$time_period = isset($_GET['period']) ? $_GET['period'] : 'monthly';
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n') - 1; // 0-based month index

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['uid'];

try {
    $data = [];
    
    if ($time_period === 'weekly') {
        // Weekly data for a specific month and year
        $firstDayOfMonth = sprintf("%04d-%02d-01", $year, $month + 1);
        $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));
        
        // Initialize weeks data structure (up to 6 weeks in a month)
        $weeklyData = [];
        for ($i = 1; $i <= 6; $i++) {
            $weeklyData["Week $i"] = 0;
        }
        
        // Query to get appointment counts by day
        // Using app_created as the date field and counting appointments
        $stmt = $pdo->prepare("
            SELECT 
                DATE(app_created) AS date,
                COUNT(*) AS count
            FROM 
                appointment
            WHERE 
                app_created BETWEEN :start_date AND :end_date
            GROUP BY 
                DATE(app_created)
            ORDER BY 
                date
        ");
        
        $stmt->execute([
            ':start_date' => $firstDayOfMonth,
            ':end_date' => $lastDayOfMonth
        ]);
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Calculate which week of the month this day belongs to
            $dayOfMonth = date('j', strtotime($row['date']));
            $weekOfMonth = ceil($dayOfMonth / 7);
            
            // Add to the appropriate week (using count since there's no fee column)
            $weeklyData["Week $weekOfMonth"] += intval($row['count']);
        }
        
        // Remove weeks with zero appointments if they're at the end
        $lastNonZeroWeek = 0;
        for ($i = 1; $i <= 6; $i++) {
            if ($weeklyData["Week $i"] > 0) {
                $lastNonZeroWeek = $i;
            }
        }
        
        // Keep only up to the last non-zero week (but at least 4 weeks)
        $lastWeekToKeep = max(4, $lastNonZeroWeek);
        foreach ($weeklyData as $week => $count) {
            $weekNumber = intval(substr($week, 5));
            if ($weekNumber <= $lastWeekToKeep) {
                $data[$week] = $count;
            }
        }
    } else {
        // Monthly data for a specific year
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        // Initialize with all months set to zero
        foreach ($monthNames as $month) {
            $data[$month] = 0;
        }
        
        // Query to get appointment counts by month
        $stmt = $pdo->prepare("
            SELECT 
                MONTH(app_created) AS month,
                COUNT(*) AS count
            FROM 
                appointment
            WHERE 
                YEAR(app_created) = :year
            GROUP BY 
                MONTH(app_created)
            ORDER BY 
                month
        ");
        
        $stmt->execute([':year' => $year]);
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $monthIndex = intval($row['month']) - 1; // Convert to 0-based index
            if (isset($monthNames[$monthIndex])) {
                $data[$monthNames[$monthIndex]] = intval($row['count']);
            }
        }
    }
    
    echo json_encode([
        'success' => true, 
        'period' => $time_period,
        'year' => $year,
        'month' => $time_period === 'weekly' ? $month : null,
        'labels' => array_keys($data),
        'data' => array_values($data)
    ]);
    
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}