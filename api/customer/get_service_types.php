<?php
session_start();
require_once '../../config/ini.php';

header('Content-Type: application/json');

// Check if user is logged in and is a customer
if (!isset($_SESSION['uid']) || $_SESSION['user_type_id'] != 2) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

try {
    // Fetch all service types from database
    $query = "SELECT service_type_id, service_type_name FROM service_type ORDER BY service_type_name ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    $serviceTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'service_types' => $serviceTypes
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching service types: ' . $e->getMessage()
    ]);
}
?>
