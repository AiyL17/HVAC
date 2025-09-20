<?php
include '../../config/ini.php';
$pdo = pdo_init();
$service_id = $_POST['service_id'] ?? null;
$appliance_id = $_POST['appliance_id'] ?? null;
if ($service_id && $appliance_id) {
    $stmt = $pdo->prepare("INSERT IGNORE INTO service_type_appliances (service_type_id, appliances_type_id) VALUES (?, ?)");
    $stmt->execute([$service_id, $appliance_id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
} 