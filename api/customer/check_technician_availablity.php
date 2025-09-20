<?php
header('Content-Type: application/json');

include '../../config/ini.php';

// Initialize the database connection
$pdo = pdo_init();

// Get the selected date and time from request
$selectedDate = isset($_GET['date']) ? $_GET['date'] : null;
$selectedTime = isset($_GET['time']) ? $_GET['time'] : null;

if (!$selectedDate) {
    echo json_encode(['error' => 'Date parameter is required']);
    exit;
}

try {
    // Get all technicians who are unavailable for the selected date and time
    $unavailableTechnicians = [];
    
    // If time is provided, check time-based availability
    if ($selectedTime) {
        // Calculate appointment end time (appointments are 1 hour long)
        $appointmentStart = $selectedTime;
        $appointmentEnd = date('H:i', strtotime($selectedTime . ' +1 hour'));
        
        // Find technicians who are unavailable during the selected appointment slot
        // This includes both 'available' status with time restrictions and 'unavailable' status with time ranges
        $timeBasedQuery = $pdo->prepare("
            SELECT u.user_id
            FROM user u
            WHERE u.user_type_id = 2 
            AND u.availability_date = :selectedDate
            AND u.availability_start_time IS NOT NULL
            AND u.availability_end_time IS NOT NULL
            AND (
                -- Case 1: Technician set as 'available' but appointment slot overlaps outside their available hours
                (u.availability_status = 'available' AND (
                    -- Appointment starts before technician's available time
                    :appointmentStart < u.availability_start_time 
                    -- OR appointment ends after technician's available time
                    OR :appointmentEnd > u.availability_end_time
                ))
                -- Case 2: Technician set as 'unavailable' and appointment slot overlaps with their unavailable hours
                OR (u.availability_status = 'unavailable' AND (
                    -- Check for time range overlap: appointment overlaps with unavailable period
                    -- Overlap occurs when: appointmentStart < unavailableEnd AND appointmentEnd > unavailableStart
                    :appointmentStart < u.availability_end_time 
                    AND :appointmentEnd > u.availability_start_time
                ))
            )
        ");
        
        $timeBasedQuery->execute([
            'selectedDate' => $selectedDate,
            'appointmentStart' => $appointmentStart,
            'appointmentEnd' => $appointmentEnd
        ]);
        $timeBasedResults = $timeBasedQuery->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($timeBasedResults as $row) {
            $unavailableTechnicians[] = $row['user_id'];
        }
    } else {
        // If no time is provided, only check for technicians who are unavailable for the entire day
        // (This would be technicians with 'unavailable' status but no specific time range)
        $dayUnavailableQuery = $pdo->prepare("
            SELECT u.user_id
            FROM user u
            WHERE u.user_type_id = 2 
            AND u.availability_date = :selectedDate 
            AND u.availability_status = 'unavailable'
            AND (u.availability_start_time IS NULL OR u.availability_end_time IS NULL)
        ");
        
        $dayUnavailableQuery->execute(['selectedDate' => $selectedDate]);
        $dayUnavailableResults = $dayUnavailableQuery->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($dayUnavailableResults as $row) {
            $unavailableTechnicians[] = $row['user_id'];
        }
    }
    
    // Query to find technicians who are unavailable due to ongoing appointments
    // This includes:
    // 1. Technicians with appointments on the selected date (same day booking conflict)
    // 2. Technicians with ongoing appointments that span multiple days (not yet completed)
    $bookedQuery = $pdo->prepare("
        SELECT DISTINCT
            a.user_technician,
            a.user_technician_2
        FROM
            appointment a
        WHERE
            (
                -- Case 1: Appointment scheduled on the selected date (same day conflict)
                DATE(a.app_schedule) = :selectedDate
                OR
                -- Case 2: Ongoing appointment that spans multiple days (not yet completed)
                -- Technician is unavailable from appointment start date until completion
                (
                    DATE(a.app_schedule) <= :selectedDate
                    AND a.app_completed_at IS NULL  -- Not yet completed
                )
            )
            AND a.app_status_id IN (1, 5) -- Only consider approved and in-progress appointments
    ");
    
    $bookedQuery->execute(['selectedDate' => $selectedDate]);
    $bookedResults = $bookedQuery->fetchAll(PDO::FETCH_ASSOC);
    
    // Extract booked technician IDs (both primary and secondary)
    $bookedTechnicians = [];
    foreach ($bookedResults as $row) {
        if ($row['user_technician']) {
            $bookedTechnicians[] = $row['user_technician'];
        }
        if ($row['user_technician_2']) {
            $bookedTechnicians[] = $row['user_technician_2'];
        }
    }
    
    // Remove duplicates and ensure sequential array indexing
    $bookedTechnicians = array_values(array_unique($bookedTechnicians));
    
    // Combine unavailable and booked technicians
    $allUnavailableTechnicians = array_values(array_unique(array_merge($unavailableTechnicians, $bookedTechnicians)));
    
    // Ensure unavailable technicians array is also sequential
    $unavailableTechnicians = array_values($unavailableTechnicians);
    
    // Get available technicians (those who have set themselves as available for the date)
    $availableQuery = $pdo->prepare("
        SELECT u.user_id, u.user_name, u.user_lastname, 
               u.availability_start_time, u.availability_end_time
        FROM user u
        WHERE u.user_type_id = 2 
        AND u.availability_date = :selectedDate
        AND u.availability_status = 'available'
    ");
    
    $availableQuery->execute(['selectedDate' => $selectedDate]);
    $availableResults = $availableQuery->fetchAll(PDO::FETCH_ASSOC);
    
    $availableTechnicians = [];
    foreach ($availableResults as $row) {
        $availableTechnicians[] = [
            'user_id' => $row['user_id'],
            'name' => $row['user_name'] . ' ' . $row['user_lastname'],
            'start_time' => $row['availability_start_time'],
            'end_time' => $row['availability_end_time']
        ];
    }
    
    // Add debugging information
    $debugInfo = [
        'query_executed' => true,
        'selected_date' => $selectedDate,
        'booked_results_count' => count($bookedResults),
        'booked_results_raw' => $bookedResults,
        'unavailable_by_preference_count' => count($unavailableTechnicians),
        'total_booked_count' => count($bookedTechnicians),
        'sql_query' => 'Check appointments where DATE(app_schedule) <= ' . $selectedDate . ' AND app_completed_at IS NULL'
    ];
    
    // Format the response
    $response = [
        'status' => 'success',
        'date' => $selectedDate,
        'time' => $selectedTime,
        'booked_technicians' => [
            'technician_ids' => $bookedTechnicians
        ],
        'unavailable_technicians' => [
            'technician_ids' => $unavailableTechnicians
        ],
        'all_unavailable' => [
            'technician_ids' => $allUnavailableTechnicians
        ],
        'available_technicians' => $availableTechnicians,
        'summary' => [
            'total_booked' => count($bookedTechnicians),
            'total_unavailable_by_preference' => count($unavailableTechnicians),
            'total_unavailable' => count($allUnavailableTechnicians),
            'total_available' => count($availableTechnicians)
        ]
    ];
    
    // Return the result as JSON
    echo json_encode($response);
    
} catch (PDOException $e) {
    error_log("Database error in check_technician_availablity.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'error' => 'Database error occurred'
    ]);
} catch (Exception $e) {
    error_log("General error in check_technician_availablity.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'error' => 'An error occurred while checking technician availability'
    ]);
}
?>
