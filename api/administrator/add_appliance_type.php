<?php
include '../../config/ini.php';
$pdo = pdo_init();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appliance_type_name'])) {
    $name = trim($_POST['appliance_type_name']);
    if ($name === '') {
        echo json_encode(['success' => false, 'error' => 'Empty name']);
        exit;
    }
    // Check if already exists
    $stmt = $pdo->prepare('SELECT appliances_type_id FROM appliances_type WHERE appliances_type_name = ?');
    $stmt->execute([$name]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo json_encode(['success' => true, 'id' => $row['appliances_type_id'], 'name' => $name, 'existed' => true]);
        exit;
    }
    // Insert new
    $stmt = $pdo->prepare('INSERT INTO appliances_type (appliances_type_name) VALUES (?)');
    $stmt->execute([$name]);
    $id = $pdo->lastInsertId();
    echo json_encode(['success' => true, 'id' => $id, 'name' => $name, 'existed' => false]);
    exit;
}
echo json_encode(['success' => false, 'error' => 'Invalid request']); 