<?php
include '../../config/ini.php';
$pdo = pdo_init();

header('Content-Type: application/json');

if (!isset($_GET['service_type_id'])) {
    echo json_encode(['error' => 'Service type ID is required']);
    exit;
}

$service_type_id = $_GET['service_type_id'];

try {
    $query = $pdo->prepare("
        SELECT at.appliances_type_id, at.appliances_type_name
        FROM appliances_type at
        INNER JOIN service_type_appliances sta ON at.appliances_type_id = sta.appliances_type_id
        WHERE sta.service_type_id = ?
        ORDER BY at.appliances_type_name
    ");
    $query->execute([$service_type_id]);
    $appliances = $query->fetchAll(PDO::FETCH_OBJ);
    
    echo json_encode(['success' => true, 'appliances' => $appliances]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch appliance types']);
}
?>
