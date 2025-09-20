<?php
header('Content-Type: application/json');

include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['uid'];

// Prepare and execute the query to fetch appointments
try {
    // Query to fetch appointments
    $query = $pdo->prepare('SELECT
        a.user_technician,
        ut.user_name AS tech_name,
        ut.user_midname AS tech_midname,
        ut.user_lastname AS tech_lastname,
        COUNT(*) AS technician_count
    FROM
        appointment a
    JOIN
        appointment_status a_s
        ON a.app_status_id = a_s.app_status_id
    JOIN
        user u
        ON a.user_id = u.user_id
    JOIN
        user ut
        ON a.user_technician = ut.user_id
    JOIN
        service_type s
        ON a.service_type_id = s.service_type_id
    WHERE
        a.app_status_id = 3
        AND a.user_id = :user_id
    GROUP BY
        a.user_technician, ut.user_name, ut.user_midname, ut.user_lastname
    ORDER BY
        technician_count DESC, a.user_technician ASC;');

    $query->bindParam(':user_id', $user_id);
    $query->execute();

    $appointments = $query->fetchAll(PDO::FETCH_ASSOC);

    // Format dates for better client-side handling
    foreach ($appointments as &$app) {
        // Convert timestamps to ISO format for easier JavaScript parsing
        if (isset($app['app_schedule'])) {
            $schedule = new DateTime($app['app_schedule']);
            $app['app_schedule'] = $schedule->format('c'); // ISO 8601 format
        }

        if (isset($app['app_created'])) {
            $created = new DateTime($app['app_created']);
            $app['app_created'] = $created->format('c'); // ISO 8601 format
        }
    }

    // Query to fetch the top 2 most used technicians
    $techQuery = $pdo->prepare('SELECT
        a.user_technician,
        ut.user_name AS tech_name,
        ut.user_midname AS tech_midname,
        ut.user_lastname AS tech_lastname,
        COUNT(*) AS technician_count
    FROM
        appointment a
    JOIN
        user ut
        ON a.user_technician = ut.user_id
    WHERE
        a.app_status_id = 3
        AND a.user_id = :user_id
    GROUP BY
        a.user_technician, ut.user_name, ut.user_midname, ut.user_lastname
    ORDER BY
        technician_count DESC, a.user_technician ASC
    LIMIT 2');

    $techQuery->bindParam(':user_id', $user_id);
    $techQuery->execute();

    $mostUsedTechnicians = $techQuery->fetchAll(PDO::FETCH_ASSOC);
    
    // For backward compatibility, keep the single most selected technician
    $mostUsedTechnician = !empty($mostUsedTechnicians) ? $mostUsedTechnicians[0] : false;

    // Query to fetch the most used service type
    $serviceQuery = $pdo->prepare('SELECT
        a.service_type_id,
        st.service_type_name,
        COUNT(*) AS service_count
    FROM
        appointment a
    JOIN
        service_type st ON a.service_type_id = st.service_type_id
    WHERE
        a.app_status_id = 3
        AND a.user_id = :user_id
    GROUP BY
        a.service_type_id, st.service_type_name
    ORDER BY
        service_count DESC, a.service_type_id ASC
    LIMIT 1');

    $serviceQuery->bindParam(':user_id', $user_id);
    $serviceQuery->execute();
    $mostUsedService = $serviceQuery->fetch(PDO::FETCH_ASSOC);

    // Query to fetch the most used appliance type
    $applianceQuery = $pdo->prepare('SELECT
        a.appliances_type_id,
        at.appliances_type_name,
        COUNT(*) AS appliance_count
    FROM
        appointment a
    JOIN
        appliances_type at ON a.appliances_type_id = at.appliances_type_id
    WHERE
        a.app_status_id = 3
        AND a.user_id = :user_id
        AND a.appliances_type_id IS NOT NULL
    GROUP BY
        a.appliances_type_id, at.appliances_type_name
    ORDER BY
        appliance_count DESC, a.appliances_type_id ASC
    LIMIT 1');

    $applianceQuery->bindParam(':user_id', $user_id);
    $applianceQuery->execute();
    $mostUsedAppliance = $applianceQuery->fetch(PDO::FETCH_ASSOC);

    // Prepare successful response
    $response = [
        'success' => true,
        'appointments' => $appointments,
        'count' => count($appointments),
        'most_selected_technician' => $mostUsedTechnician,
        'top_technicians' => $mostUsedTechnicians,
        'most_used_service' => $mostUsedService ?: false,
        'most_used_appliance' => $mostUsedAppliance ?: false
    ];
} catch (PDOException $e) {
    // Handle database errors
    $response = [
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ];
}

// Return the response as JSON
echo json_encode($response);
?>
