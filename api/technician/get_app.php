<?php
// api/technician/get_app.php
header('Content-Type: application/json');
include '../../config/ini.php';

$pdo = pdo_init();
$customerId = $_GET['customer'] ?? null;
$technicianId = $_GET['technician'] ?? null;
$statusId = $_GET['status'] ?? null;
$appId = $_GET['app_id'] ?? null;

if (!$customerId || !$technicianId || !$statusId) {
    echo json_encode(['error' => 'Customer ID, Technician ID, and Status ID are required']);
    exit;
}

// Handle status filtering - support "all" to show all statuses
$whereClause = "u.user_id = ? AND (a.user_technician = ? OR a.user_technician_2 = ?)";
$params = [intval($customerId), intval($technicianId), intval($technicianId)];

// Only add status filter if not "all"
if ($statusId !== 'all') {
    // Handle multiple status IDs
    $statusIds = [];
    if (strpos($statusId, ',') !== false) {
        // Multiple status IDs separated by comma
        $statusIds = array_map('intval', explode(',', $statusId));
    } else {
        // Single status ID
        $statusIds = [intval($statusId)];
    }

    // Remove any invalid (0) values that might result from bad input
    $statusIds = array_filter($statusIds, function($id) {
        return $id > 0;
    });

    if (!empty($statusIds)) {
        // Create placeholders for the IN clause
        $placeholders = str_repeat('?,', count($statusIds) - 1) . '?';
        $whereClause .= " AND a.app_status_id IN ($placeholders)";
        $params = array_merge($params, $statusIds);
    }
}

// Add app_id filter if provided
if ($appId) {
    $whereClause .= " AND a.app_id = ?";
    $params[] = intval($appId);
}

$query = $pdo->prepare("SELECT
    a.*,
    a_s.app_status_name,
    u.user_name,
    u.user_midname,
    u.user_lastname,
    u.user_contact,
    u.house_building_street,
    COALESCE(ata.barangay, u.barangay) as barangay,
    COALESCE(ata.municipality_city, u.municipality_city) as municipality_city,
    COALESCE(ata.province, u.province) as province,
    COALESCE(ata.zip_code, u.zip_code) as zip_code,
    s.service_type_name,
    s.service_type_price_min,
    s.service_type_price_max,
    COALESCE(at.appliances_type_name, 'Not Specified') as appliances_type_name,
    ut.user_name AS tech_name,
    ut.user_midname AS tech_midname,
    ut.user_lastname AS tech_lastname,
    ut.user_contact AS tech_contact,
    ut.house_building_street AS tech_house_building_street,
    ut.barangay AS tech_barangay,
    ut.municipality_city AS tech_municipality_city,
    ut.province AS tech_province,
    ut.zip_code AS tech_zip_code,
    ut2.user_name AS tech2_name,
    ut2.user_midname AS tech2_midname,
    ut2.user_lastname AS tech2_lastname,
    ut2.user_contact AS tech2_contact,
    ut2.house_building_street AS tech2_house_building_street,
    ut2.barangay AS tech2_barangay,
    ut2.municipality_city AS tech2_municipality_city,
    ut2.province AS tech2_province,
    ut2.zip_code AS tech2_zip_code
FROM
    appointment a
JOIN
    appointment_status a_s ON a.app_status_id = a_s.app_status_id
JOIN
    user u ON a.user_id = u.user_id
LEFT JOIN
    appointment_transaction_address ata ON a.app_id = ata.app_id
JOIN
    service_type s ON a.service_type_id = s.service_type_id
LEFT JOIN
    appliances_type at ON a.appliances_type_id = at.appliances_type_id
JOIN
    user ut ON a.user_technician = ut.user_id
LEFT JOIN
    user ut2 ON a.user_technician_2 = ut2.user_id
WHERE
    $whereClause
ORDER BY
    a.app_schedule DESC
");

$query->execute($params);

$appointments = $query->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($appointments);
?>