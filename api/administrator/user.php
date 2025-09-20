<?php
header('Content-Type: application/json');

include '../../config/ini.php';

$pdo = pdo_init();

// Get pagination parameters from the query string
$page = isset($_GET['pg']) ? (int)$_GET['pg'] : 1;
$type = isset($_GET['type']) ? $_GET['type'] : 'All';
$status = isset($_GET['status']) ? $_GET['status'] : 'All';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 7;
$offset = ($page - 1) * $limit;

try {
    // Build status filter condition
    $statusCondition = '';
    $statusParams = [];
    if ($status !== 'All') {
        $statusValue = ($status === 'Active') ? 1 : 0;
        $statusCondition = ' WHERE user.is_active = :status';
        $statusParams[':status'] = $statusValue;
    }

    if ($type === 'All') {
        // Fetch the total number of users with status filter
        $countQuery = 'SELECT COUNT(*) AS total FROM user' . $statusCondition;
        $countStmt = $pdo->prepare($countQuery);
        foreach ($statusParams as $param => $value) {
            $countStmt->bindParam($param, $value, PDO::PARAM_INT);
        }
        $countStmt->execute();
        $totalUsers = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalUsers / $limit);

        // Fetch all users with pagination and status filter (active users first)
        // Consider users active only if they have activity within last 30 minutes
        $query = 'SELECT user.*, user_type.user_type_name, 
                  CASE 
                      WHEN user.last_activity IS NOT NULL AND user.last_activity >= DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND user.is_active = 1 
                      THEN 1 
                      ELSE 0 
                  END as is_currently_active
                  FROM user JOIN user_type ON user.user_type_id = user_type.user_type_id' . $statusCondition . ' ORDER BY is_currently_active DESC, user.user_id DESC LIMIT :limit OFFSET :offset';
        $stmt = $pdo->prepare($query);
        foreach ($statusParams as $param => $value) {
            $stmt->bindParam($param, $value, PDO::PARAM_INT);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $type = (int)$type;
        // Build combined filter condition for user type and status
        $typeCondition = ' WHERE user.user_type_id = :user_type';
        if ($status !== 'All') {
            $statusValue = ($status === 'Active') ? 1 : 0;
            $typeCondition .= ' AND user.is_active = :status';
        }
        
        // Fetch the total number of users for the specified user type and status
        $countQuery = 'SELECT COUNT(*) AS total FROM user' . $typeCondition;
        $countStmt = $pdo->prepare($countQuery);
        $countStmt->bindParam(':user_type', $type, PDO::PARAM_INT);
        if ($status !== 'All') {
            $countStmt->bindParam(':status', $statusValue, PDO::PARAM_INT);
        }
        $countStmt->execute();
        $totalUsers = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalUsers / $limit);

        // Fetch users with pagination, user type, and status filters (active users first)
        // Consider users active only if they have activity within last 30 minutes
        $query = 'SELECT user.*, user_type.user_type_name, 
                  CASE 
                      WHEN user.last_activity IS NOT NULL AND user.last_activity >= DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND user.is_active = 1 
                      THEN 1 
                      ELSE 0 
                  END as is_currently_active
                  FROM user JOIN user_type ON user.user_type_id = user_type.user_type_id' . $typeCondition . ' ORDER BY is_currently_active DESC, user.user_id DESC LIMIT :limit OFFSET :offset';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_type', $type, PDO::PARAM_INT);
        if ($status !== 'All') {
            $stmt->bindParam(':status', $statusValue, PDO::PARAM_INT);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Prepare the response
    $response = [
        'success' => true,
        'users' => $users,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ];
} catch (PDOException $e) {
    // Handle any errors
    $response = [
        'success' => false,
        'message' => 'Failed to fetch users: ' . $e->getMessage()
    ];
}

echo json_encode($response);
?>
