<?php
include '../../config/ini.php';
$pdo = pdo_init();

header('Content-Type: application/json');

try {
    $query = $pdo->prepare("
        SELECT appliances_type_id, appliances_type_name
        FROM appliances_type
        ORDER BY appliances_type_name
    ");
    $query->execute();
    $appliances = $query->fetchAll(PDO::FETCH_OBJ);
    
    echo json_encode(['success' => true, 'appliances' => $appliances]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch appliance types']);
}
?>
