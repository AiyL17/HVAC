<?php
include '../../config/ini.php';
$pdo = pdo_init();
$service_id = $_POST['service_id'] ?? null;
$appliance_id = $_POST['appliance_id'] ?? null;
if ($service_id && $appliance_id) {
    $stmt = $pdo->prepare("DELETE FROM service_type_appliances WHERE service_type_id=? AND appliances_type_id=?");
    $stmt->execute([$service_id, $appliance_id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
} 