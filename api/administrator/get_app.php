<?php
// api/get_appointments.php

header('Content-Type: application/json');
include '../../config/ini.php';

$pdo = pdo_init();

$customerId = $_GET['customer'] ?? null;

if (!$customerId) {
    echo json_encode(['error' => 'Customer ID is required']);
    exit;
}

$query = $pdo->prepare('SELECT
    a.*,
    a_s.app_status_name,
    u.user_name ,
    u.user_midname ,
    u.user_lastname ,
    u.user_contact,
    u.house_building_street,
    u.barangay,
    u.municipality_city,
    u.province,
    u.zip_code,
    s.service_type_name,
    ut.user_name AS tech_name,
    ut.user_midname AS tech_midname,
    ut.user_lastname AS tech_lastname,
    ut.user_contact AS tech_contact,
    ut.house_building_street AS tech_house_building_street,
    ut.barangay AS tech_barangay,
    ut.municipality_city AS tech_municipality_city,
    ut.province AS tech_province,
    ut.zip_code AS tech_zip_code
FROM
    appointment a
JOIN
    appointment_status a_s
    ON a.app_status_id = a_s.app_status_id
JOIN
    user u
    ON a.user_id = u.user_id
JOIN
    service_type s
    ON a.service_type_id = s.service_type_id
JOIN
    user ut
    ON a.user_technician = ut.user_id
WHERE
    u.user_id = :customerId AND a.app_status_id = 2
ORDER BY
    a.app_schedule DESC
');

$query->execute(['customerId' => $customerId]);
$appointments = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($appointments);
?>
