<style>
    .service-card,
    .technician-card {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
        position: relative;
    }

    .scroll-container::-webkit-scrollbar {
        height: 15px;
        padding: 10px;
    }

    .scroll-container::-webkit-scrollbar-track {
        background-color: rgba(var(--bs-dark-rgb), .05);
        border-radius: 10px;
        height: 5px;
        margin: 50px;
        border: 5px solid transparent;
        background-clip: padding-box;
    }

    .scroll-container::-webkit-scrollbar-thumb {
        height: 5px;
        background-clip: padding-box;
        border-radius: 10px;
        background-color: rgba(var(--bg-primary-rgb), .5);
        border: 5px solid transparent;
        background-clip: padding-box;
        transition: border 3s ease, background-color 3s ease !;
    }

    .scroll-container::-webkit-scrollbar-thumb:hover {
        background-color: rgba(var(--bg-primary-rgb), 1);
        border: 0;
    }

    .scroll-container::-webkit-scrollbar-thumb:active {
        background-color: rgba(var(--bg-primary-rgb), 1);
        border: 0;
    }

    .service-card input[type="checkbox"],
    .technician-card input[type="checkbox"] {
        display: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    input::-webkit-inner-spin-button,
    input::-webkit-calendar-picker-indicator {
        opacity: 0;
        -webkit-appearance: none;
    }

    .service-card input[type="checkbox"]+.card-body,
    .technician-card input[type="checkbox"]+.card-body {
        transition: transform 0.2s, box-shadow 0.2s;
        background-color: white;
    }

    .service-card input[type="checkbox"]:checked+.card-body,
    .technician-card input[type="checkbox"]:checked+.card-body {
        transition: transform 0.2s, box-shadow 0.2s;
        background-color: var(--bg-primary);
        color: white;
        transform: translateY(-4px);
        box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;
    }

    /* Loading spinner */
    .spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
        margin-left: 10px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Alert positioning */
    .alert-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        max-width: 400px;
    }
</style>
<style>
    /* Hide dropdown arrow from time select element */
    #appointment-time {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: none;
    }

    /* Hide dropdown arrow from appliance type select element */
    #appliance-type-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: none;
    }

    /* Style dropdown options with border radius */
    #appliance-type-select option {
        border-radius: 8px;
        padding: 8px 12px;
        margin: 2px 0;
    }
</style>
<style>
    .service-card,
    .technician-card {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
        position: relative;
    }

    .scroll-container::-webkit-scrollbar {
        height: 15px;
        padding: 10px;
    }

    .scroll-container::-webkit-scrollbar-track {
        background-color: rgba(var(--bs-dark-rgb), .05);
        border-radius: 10px;
        height: 5px;
        margin: 50px;
        border: 5px solid transparent;
        background-clip: padding-box;
    }

    .scroll-container::-webkit-scrollbar-thumb {
        height: 5px;
        background-clip: padding-box;
        border-radius: 10px;
        background-color: rgba(var(--bg-primary-rgb), .5);
        border: 5px solid transparent;
        background-clip: padding-box;
        transition: border 3s ease, background-color 3s ease !;
    }

    .scroll-container::-webkit-scrollbar-thumb:hover {
        background-color: rgba(var(--bg-primary-rgb), 1);
        border: 0;
    }

    .scroll-container::-webkit-scrollbar-thumb:active {
        background-color: rgba(var(--bg-primary-rgb), 1);
        border: 0;
    }

    .service-card input[type="checkbox"],
    .technician-card input[type="checkbox"] {
        display: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    input::-webkit-inner-spin-button,
    input::-webkit-calendar-picker-indicator {
        opacity: 0;
        -webkit-appearance: none;
    }

    .service-card input[type="checkbox"]+.card-body,
    .technician-card input[type="checkbox"]+.card-body {
        transition: transform 0.2s, box-shadow 0.2s;
        background-color: white;
    }

    .service-card input[type="checkbox"]:checked+.card-body,
    .technician-card input[type="checkbox"]:checked+.card-body {
        transition: transform 0.2s, box-shadow 0.2s;
        background-color: var(--bg-primary);
        color: white;
        transform: translateY(-4px);
        box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;
    }

    /* Loading spinner */
    .spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
        margin-left: 10px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Alert positioning */
    .alert-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        max-width: 400px;
    }

    /* Mobile modal scrollbar removal */
    @media (max-width: 768px) {
        .modal-dialog {
            max-height: 95vh;
            margin: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .modal-content {
            max-height: 90vh;
            overflow: hidden;
            border-radius: 15px;
        }
        
        .modal-body {
            overflow-y: auto;
            overflow-x: hidden;
            max-height: 75vh;
            padding: 1.5rem 1rem;
            /* Hide scrollbar completely */
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Hide scrollbar for webkit browsers on mobile */
        .modal-body::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }
        
        /* Ensure smooth scrolling */
        .modal-body {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Technician suggestion cards mobile optimization */
        .tech-suggestion-card {
            margin-bottom: 0.5rem;
        }
        
        /* Modal header and footer adjustments */
        .modal-header {
            padding: 1rem;
            border-bottom: none;
        }
        
        .modal-footer {
            padding: 1rem;
            border-top: none;
        }
        
        /* Remove any potential scrollbar from modal backdrop */
        .modal {
            overflow: hidden;
        }
        
        .modal-backdrop {
            overflow: hidden;
        }
    }
</style>

<style>
    /* Secondary technician card styles */
    .technician-card-2 {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
        position: relative;
    }

    .technician-card-2 input[type="checkbox"] {
        display: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .technician-card-2 input[type="checkbox"]+.card-body {
        transition: transform 0.2s, box-shadow 0.2s;
        background-color: white;
    }

    .technician-card-2 input[type="checkbox"]:checked+.card-body {
        transition: transform 0.2s, box-shadow 0.2s;
        background-color: var(--bg-primary);
        color: white;
        transform: translateY(-4px);
        box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;
    }
</style>

<?php
// Check if we're in edit or rebook mode and fetch appointment data if needed
$isEditMode = isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']);
$isRebookMode = isset($_GET['action']) && $_GET['action'] === 'rebook' && isset($_GET['id']);
$appointmentData = null;

if ($isEditMode || $isRebookMode) {
    $appointmentId = $_GET['id'];
    
    // For rebook mode, check if we have URL parameters for pre-selection
    if ($isRebookMode && (isset($_GET['service_type_id']) || isset($_GET['appliances_type_id']) || isset($_GET['user_technician']) || isset($_GET['user_technician_2']))) {
        // Create appointment data object from URL parameters
        $appointmentData = new stdClass();
        $appointmentData->app_id = $appointmentId;
        $appointmentData->service_type_id = $_GET['service_type_id'] ?? null;
        $appointmentData->appliances_type_id = $_GET['appliances_type_id'] ?? null;
        $appointmentData->user_technician = $_GET['user_technician'] ?? null;
        $appointmentData->user_technician_2 = $_GET['user_technician_2'] ?? null;
        $appointmentData->app_schedule = null; // Don't pre-fill date/time for rebook
        $appointmentData->app_desc = null; // Don't pre-fill description for rebook
    } else {
        // For edit mode or rebook without URL parameters, fetch from database
        $query = $pdo->prepare("SELECT
            a.app_id,
            a.app_schedule,
            a.app_desc,
            a.service_type_id,
            a.appliances_type_id,
            a.user_technician,
            a.user_technician_2,
            a.technician_justification
        FROM
            appointment a
        WHERE
            a.app_id = ?");
        $query->execute(array($appointmentId));
        $appointmentData = $query->fetch(PDO::FETCH_OBJ);

        // If no appointment found with this ID
        if (!$appointmentData) {
            echo "<div class='alert alert-danger'>Appointment not found!</div>";
            exit();
        }
    }
}
?>

<h3>
    <a onclick="window.location.href = document.referrer;" class="btn p-2">
        <i class="bi bi-chevron-left"></i>
    </a>
    <?= ucfirst($_GET['action']) ?> Appointment
</h3>

<!-- Alert container for notifications -->
<div class="alert-container"></div>

<style>
#service-type-select {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    background-image: none !important;
}
#service-type-select::-ms-expand {
    display: none;
}
</style>

<h6>Service Type</h6>
<div id="service-type-section">
    <div class="input-group align-items-center bg-white rounded-pill">
        <select class="form-select px-3 p-2 bg-transparent rounded-pill border-0" 
            style="margin-right: -50px; -webkit-appearance: none; -moz-appearance: none; appearance: none;" id="service-type-select" required>
            <option value="">Choose Service Type</option>
            <?php
            $query = $pdo->prepare("SELECT * FROM `service_type`");
            $query->execute(array());
            $list = $query->fetchAll(PDO::FETCH_OBJ);

            foreach ($list as $service) {
                $isSelected = ($isEditMode || $isRebookMode) && $appointmentData && $appointmentData->service_type_id == $service->service_type_id;
                
                // Use the correct column names and add error handling
                $min_price = isset($service->service_type_price_min) ? number_format((float) $service->service_type_price_min, 0, '.', ',') : '0';
                $max_price = isset($service->service_type_price_max) ? number_format((float) $service->service_type_price_max, 0, '.', ',') : '0';
                $formatted_price = $min_price . ' - ' . $max_price;
                
                echo "<option value='{$service->service_type_id}' data-price='{$formatted_price}' " . ($isSelected ? 'selected' : '') . ">";
                echo "{$service->service_type_name} (₱{$formatted_price})";
                echo "</option>";
            }
            ?>
        </select>
        <i class="bi bi-screwdriver me-1 text-bg-light p-1 px-3 rounded-pill"></i>
    </div>
    <div class="invalid-feedback" id="service-error">
        Please select a service type.
    </div>
</div>

<h6 class="mt-3">Appliance Type</h6>
<div id="appliance-type-section">
    <div class="input-group align-items-center bg-white rounded-pill">
        <select class="form-select px-3 p-2 bg-transparent rounded-pill border-0" 
            style="margin-right: -50px;" id="appliance-type-select" required>
            <option value="">Choose Appliances Type</option>
            <!-- Appliance type options will be loaded here dynamically -->
        </select>
        <i class="bi bi-wrench-adjustable me-1 text-bg-light p-1 px-3 rounded-pill"></i>
    </div>
    <div class="invalid-feedback" id="appliance-error">
        Please select an appliance type.
    </div>
</div>

<h6 class="mt-2">Schedule</h6>
<div class="gap-2 col-sm-6 d-flex ">
    <?php
    // Extract date and time from appointment data if in edit or rebook mode
    $date = '';
    $time = '';
    if (($isEditMode || $isRebookMode) && $appointmentData && $appointmentData->app_schedule) {
        $scheduleParts = explode(' ', $appointmentData->app_schedule);
        $date = $scheduleParts[0] ?? '';
        $time = isset($scheduleParts[1]) ? substr($scheduleParts[1], 0, 5) : ''; // Get HH:MM from time
    }
    
    // Set date restrictions: today or any future day (no past dates)
    $today = date('Y-m-d');
    ?>
    <div class="w-100">
        <div class="input-group align-items-center bg-white rounded-pill ">
            <input class="form-control px-3 p-2 fw-normal bg-transparent rounded-pill border-0" type="date"
                style="margin-right: -50px;" id="appointment-date" value="<?=$date ?>" 
                min="<?=$today ?>" required />
            <i class="bi bi-calendar3 me-1 text-bg-light p-1 px-3 rounded-pill"></i>
        </div>
        <div class="invalid-feedback " id="date-error">
            Please select a weekday (Monday to Friday).
        </div>
    </div>
    <div class="w-100">
        <div class="input-group align-items-center bg-white rounded-pill">
            <select class="form-select px-3 p-2 bg-transparent rounded-pill border-0" 
                style="margin-right: -50px;" id="appointment-time" required>
                <option value="">Select Time Slot</option>
                <?php
                // Define available time slots (8 AM to 5 PM, excluding 12-1 PM lunch break)
                $timeSlots = [
                    '08:00' => '8:00 AM - 9:00 AM',
                    '09:00' => '9:00 AM - 10:00 AM',
                    '10:00' => '10:00 AM - 11:00 AM',
                    '11:00' => '11:00 AM - 12:00 PM',
                    '13:00' => '1:00 PM - 2:00 PM',
                    '14:00' => '2:00 PM - 3:00 PM',
                    '15:00' => '3:00 PM - 4:00 PM',
                    '16:00' => '4:00 PM - 5:00 PM'
                ];
                
                foreach ($timeSlots as $value => $label) {
                    $selected = ($time && substr($time, 0, 5) === $value) ? 'selected' : '';
                    echo "<option value='$value' $selected>$label</option>";
                }
                ?>
            </select>
            <i class="bi bi-clock me-1 text-bg-light p-1 px-3 rounded-pill"></i>
        </div>
        <div class="invalid-feedback" id="time-error">
            Please select a time slot.
        </div>
    </div>
</div>

<h6 class="mt-3">Primary Technician <i class="bi bi-person-fill text-primary"></i></h6>
<p class="small text-muted mb-2">Select the main technician for this appointment</p>

<div class="d-flex gap-2 scroll-container pb-3 py-1"
    style="margin:0px -10px 0px -10px;flex-wrap: nowrap; overflow: overlay; padding-left: 10px; padding-right: 10px;">
    <?php
    $query = $pdo->prepare("SELECT
        u.user_id,
        u.user_name,
        u.user_midname,
        u.user_lastname,
        COALESCE(AVG(a.app_rating), 0) AS average_rating
    FROM
        user u
    LEFT JOIN
        appointment a ON u.user_id = a.user_technician
    WHERE
        u.user_type_id = 2
    GROUP BY
        u.user_id;
    ");
    $query->execute(array());
    $list = $query->fetchAll(PDO::FETCH_OBJ);
    
    // Function to sort technicians by availability status
    function sortTechniciansByAvailability($technicians, $pdo, $selectedDate = null, $selectedTime = null) {
        if (!$selectedDate) {
            // If no date is selected, return original order
            return $technicians;
        }
        
        try {
            // Get unavailable technicians for the selected date/time
            $unavailableTechnicians = [];
            $bookedTechnicians = [];
            
            // Check time-based availability if time is provided
            if ($selectedTime) {
                $appointmentStart = $selectedTime;
                $appointmentEnd = date('H:i', strtotime($selectedTime . ' +1 hour'));
                
                $timeBasedQuery = $pdo->prepare("
                    SELECT u.user_id
                    FROM user u
                    WHERE u.user_type_id = 2 
                    AND u.availability_date = :selectedDate
                    AND u.availability_start_time IS NOT NULL
                    AND u.availability_end_time IS NOT NULL
                    AND (
                        (u.availability_status = 'available' AND (
                            :appointmentStart < u.availability_start_time 
                            OR :appointmentEnd > u.availability_end_time
                        ))
                        OR (u.availability_status = 'unavailable' AND (
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
                // Check for day-unavailable technicians
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
            
            // Check for booked technicians
            $bookedQuery = $pdo->prepare("
                SELECT DISTINCT
                    a.user_technician,
                    a.user_technician_2
                FROM
                    appointment a
                WHERE
                    (
                        DATE(a.app_schedule) = :selectedDate
                        OR (
                            DATE(a.app_schedule) <= :selectedDate
                            AND a.app_completed_at IS NULL
                        )
                    )
                    AND a.app_status_id IN (1, 5)
            ");
            
            $bookedQuery->execute(['selectedDate' => $selectedDate]);
            $bookedResults = $bookedQuery->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($bookedResults as $row) {
                if ($row['user_technician']) {
                    $bookedTechnicians[] = $row['user_technician'];
                }
                if ($row['user_technician_2']) {
                    $bookedTechnicians[] = $row['user_technician_2'];
                }
            }
            
            // Combine all unavailable technician IDs
            $allUnavailableTechnicians = array_unique(array_merge($unavailableTechnicians, $bookedTechnicians));
            
            // Sort technicians: available first, unavailable last
            $availableTechs = [];
            $unavailableTechs = [];
            
            foreach ($technicians as $technician) {
                if (in_array($technician->user_id, $allUnavailableTechnicians)) {
                    $unavailableTechs[] = $technician;
                } else {
                    $availableTechs[] = $technician;
                }
            }
            
            // Sort each group by average rating (highest first)
            usort($availableTechs, function($a, $b) {
                return $b->average_rating <=> $a->average_rating;
            });
            
            usort($unavailableTechs, function($a, $b) {
                return $b->average_rating <=> $a->average_rating;
            });
            
            // Return available technicians first (sorted by rating), then unavailable ones (also sorted by rating)
            return array_merge($availableTechs, $unavailableTechs);
            
        } catch (Exception $e) {
            // If there's an error, return original order
            error_log("Error sorting technicians by availability: " . $e->getMessage());
            return $technicians;
        }
    }
    
    // Get selected date and time from URL parameters or form data if available
    $selectedDate = isset($_GET['date']) ? $_GET['date'] : (isset($_POST['date']) ? $_POST['date'] : null);
    $selectedTime = isset($_GET['time']) ? $_GET['time'] : (isset($_POST['time']) ? $_POST['time'] : null);
    
    // Sort technicians by availability
    $list = sortTechniciansByAvailability($list, $pdo, $selectedDate, $selectedTime);

    foreach ($list as $technician) {
        $widthPercentage = $technician->average_rating * 20;
        $isChecked = ($isEditMode || $isRebookMode) && $appointmentData && $appointmentData->user_technician == $technician->user_id;
        ?>
        <div class="technician-card round_lg" style="width: 11rem; flex-shrink: 0; position: relative;"
            data-technician-id="<?= $technician->user_id ?>">
            <input type="checkbox" name="technician" id="technician_<?= $technician->user_id ?>" <?= $isChecked ? 'checked' : '' ?>>
            <div class="card-body round_lg border-0 p-3 p-2" <?= $isChecked ? 'style="background-color: var(--bg-primary); color: white; transform: translateY(-4px); box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;"' : '' ?>>
                <img src="././img/tech.png" alt="" width="70" class="round_md bg-light border">
                <h6 class="m-0 mt-2">
                    <?= $technician->user_name ?>
                    <?= $technician->user_midname ?>
                    <?= $technician->user_lastname ?>
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <div class="col">
                        <div class="progress bg-light mt-1 border-1 border-light border" role="progressbar"
                            aria-label="Basic example" aria-valuenow="<?= $widthPercentage ?>" aria-valuemin="0"
                            aria-valuemax="100" style="height:8px">
                            <div class="progress-bar bg-primary rounded-pill" style="width: <?= $widthPercentage ?>%"></div>
                        </div>
                    </div>
                    <div class="col-3 small"><small><?= number_format($technician->average_rating, 1) ?> <i
                                class="bi text-warning bi-star-fill"></i></small></div>
                </div>
                <span class="badge bg-danger -subtle p-1 px-2 fw-normal rounded-pill"
                    style="display: none; position: absolute; top: 10px; right: 10px;">Unavailable</span>
            </div>
        </div>
    <?php } ?>
</div>

<h6 class="mt-3">Secondary Technician <i class="bi bi-person-plus-fill text-secondary"></i> <span class="badge bg-light text-dark"></span></h6>
<p class="small text-muted mb-2">Select an additional technician to assist</p>

<div class="d-flex gap-2 scroll-container pb-3 py-1"
    style="margin:0px -10px 0px -10px;flex-wrap: nowrap; overflow: overlay; padding-left: 10px; padding-right: 10px;">
    <?php
    // Reuse the same sorted technician list for secondary selection
    foreach ($list as $technician) {
        $widthPercentage = $technician->average_rating * 20;
        $isChecked = ($isEditMode || $isRebookMode) && $appointmentData && $appointmentData->user_technician_2 == $technician->user_id;
        ?>
        <div class="technician-card-2 round_lg" style="width: 11rem; flex-shrink: 0; position: relative;"
            data-technician-id="<?= $technician->user_id ?>">
            <input type="checkbox" name="technician2" id="technician2_<?= $technician->user_id ?>" <?= $isChecked ? 'checked' : '' ?>>
            <div class="card-body round_lg border-0 p-3 p-2" <?= $isChecked ? 'style="background-color: var(--bg-primary); color: white; transform: translateY(-4px); box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;"' : '' ?>>
                <img src="././img/tech.png" alt="" width="70" class="round_md bg-light border">
                <h6 class="m-0 mt-2">
                    <?= $technician->user_name ?>
                    <?= $technician->user_midname ?>
                    <?= $technician->user_lastname ?>
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <div class="col">
                        <div class="progress bg-light mt-1 border-1 border-light border" role="progressbar"
                            aria-label="Basic example" aria-valuenow="<?= $widthPercentage ?>" aria-valuemin="0"
                            aria-valuemax="100" style="height:8px">
                            <div class="progress-bar bg-primary rounded-pill" style="width: <?= $widthPercentage ?>%"></div>
                        </div>
                    </div>
                    <div class="col-3 small"><small><?= number_format($technician->average_rating, 1) ?> <i
                                class="bi text-warning bi-star-fill"></i></small></div>
                </div>
                <span class="badge bg-danger -subtle p-1 px-2 fw-normal rounded-pill"
                    style="display: none; position: absolute; top: 10px; right: 10px;">Unavailable</span>
            </div>
        </div>
    <?php } ?>
</div>

<?php if (!$isEditMode): ?>
<!-- Technician Justification Section (Hidden by default) -->
<div id="technician-justification-section" class="mb-4" style="display: none; margin-top: 20px">
    <h6 class="mb-2">
        Technician Justification
    </h6>
    <textarea id="technician-justification" 
              placeholder="Please explain why you are not selecting your frequently booked technician(s)..."
              class="form-control border-0 round_lg p-3" 
              rows="3"
              maxlength="500"></textarea>
    <div class="invalid-feedback" id="justification-error">
        Please provide a justification for not selecting your frequent technician(s).
    </div>
    <small class="text-muted mt-1 d-block">Maximum 500 characters</small>
</div>
<?php else: ?>
    <!-- Technician Justification Section (Editable in edit mode) -->
    <div id="technician-justification-section" class="mb-4" style="margin-top: 20px">
        <h6 class="mb-2">
            Technician Justification
        </h6>
        <textarea id="technician-justification" 
                  placeholder="Please explain why you are changing your technician selection..."
                  class="form-control border-0 round_lg p-3" 
                  rows="3"
                  maxlength="500"><?= ($isEditMode || $isRebookMode) && $appointmentData ? htmlspecialchars($appointmentData->technician_justification) : '' ?></textarea>
        <div class="invalid-feedback" id="justification-error">
            Please provide a justification for changing your technician selection.
        </div>
        <small class="text-muted mt-1 d-block">Maximum 500 characters</small>
    </div>
<?php endif; ?>

<h6 class="mt-2">Description</h6>
<div class="mb-5">
    <textarea id="appointment-description" rows="4" placeholder="Describe the service or issue..."
        class="form-control border-0 round_lg p-3"><?= ($isEditMode || $isRebookMode) && $appointmentData ? htmlspecialchars($appointmentData->app_desc) : '' ?></textarea>
    <br>
    <button id="submit-appointment"
        class="align-items-center d-flex justify-content-center fw-semibold btn btn-primary col-sm-3 col-12 border-0 rounded-pill px-3 p-2 p-sm-2">
        <small class="my-1 my-sm-0"><?= $isEditMode ? 'Update' : 'Set' ?> Appointment</small>
        <div class="spinner" id="submit-spinner"></div>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dateInput = document.getElementById('appointment-date');
        const timeInput = document.getElementById('appointment-time');
        const dateError = document.getElementById('date-error');
        const timeError = document.getElementById('time-error');

        // Set the minimum and maximum time
        timeInput.min = '08:00';
        timeInput.max = '17:00';

        // Function to check if a date is a weekday
        function isWeekday(date) {
            const day = date.getDay();
            return day !== 0 && day !== 6; // 0 is Sunday, 6 is Saturday
        }

        // Add an event listener to the date input to validate the selected date
        dateInput.addEventListener('change', function () {
            const selectedDate = new Date(this.value);
            if (!isWeekday(selectedDate)) {
                this.classList.remove('border-0');
                this.classList.add('border-danger', 'border-3',);
                dateError.style.display = 'block';
                dateError.style.wordWrap = 'break-word';
            } else {
                this.classList.add('border-0');
                this.classList.remove('border-danger');
                dateError.style.display = 'none';
            }
        });

        // Add an event listener to the time input to validate the selected time
        timeInput.addEventListener('change', function () {
            const selectedTime = this.value;
            if (selectedTime < '08:00' || selectedTime > '17:00') {
                this.classList.add('border-danger'); this.classList.remove('border-0');
                this.classList.add('border-danger', 'border-3',);
                timeError.style.display = 'block';
                timeError.style.wordWrap = 'break-word';
            } else {
                this.classList.add('border-0');
                this.classList.remove('border-danger');
                timeError.style.display = 'none';
            }
        });
    });

    async function getTechnicianAvailability(date, time = null) {
        try {
            let url = `api/customer/check_technician_availablity.php?date=${date}`;
            if (time) {
                url += `&time=${time}`;
            }
            
            const response = await fetch(url);
            const data = await response.json();

            if (data.status === 'success') {
                return {
                    unavailableTechnicians: data.all_unavailable.technician_ids.map(id => `technician_${id}`),
                    availableTechnicians: data.available_technicians,
                    bookedTechnicians: data.booked_technicians.technician_ids.map(id => `technician_${id}`),
                    unavailableByPreference: data.unavailable_technicians.technician_ids.map(id => `technician_${id}`),
                    summary: data.summary
                };
            } else {
                console.error('Error:', data.error);
                return {
                    unavailableTechnicians: [],
                    availableTechnicians: [],
                    bookedTechnicians: [],
                    unavailableByPreference: [],
                    summary: {}
                };
            }
        } catch (error) {
            console.error('Failed to fetch technician availability:', error);
            return {
                unavailableTechnicians: [],
                availableTechnicians: [],
                bookedTechnicians: [],
                unavailableByPreference: [],
                summary: {}
            };
        }
    }
    
    // Legacy function for backward compatibility
    async function getBookedTechnicians(date) {
        const availability = await getTechnicianAvailability(date);
        return availability.unavailableTechnicians;
    }

    function isElementFullyVisible(element, container) {
        const containerRect = container.getBoundingClientRect();
        const elementRect = element.getBoundingClientRect();

        return (
            elementRect.left >= containerRect.left &&
            elementRect.right <= containerRect.right
        );
    }

    function scrollToSelectedTechnician(checkbox, cardSelector = '.technician-card') {
        const selectedTechnician = checkbox;
        if (selectedTechnician) {
            const technicianCard = selectedTechnician.closest(cardSelector);
            const technicianContainer = document.querySelector('.scroll-container:has(' + cardSelector + ')');

            if (technicianContainer && !isElementFullyVisible(technicianCard, technicianContainer)) {
                const containerRect = technicianContainer.getBoundingClientRect();
                const cardRect = technicianCard.getBoundingClientRect();

                let scrollPosition = technicianContainer.scrollLeft;

                if (cardRect.left < containerRect.left) {
                    scrollPosition += cardRect.left - containerRect.left - 60;
                } else if (cardRect.right > containerRect.right) {
                    scrollPosition += cardRect.right - containerRect.right + 60;
                }

                technicianContainer.scrollTo({
                    left: scrollPosition,
                    behavior: 'smooth'
                });
            }
        }
    }

    function selectTechnicianFromModal(techId, techType, techName) {
        console.log('Selecting technician:', techId, techType, techName);
        
        // Add visual feedback to the clicked modal card
        const clickedCard = event.target.closest('.tech-suggestion-card');
        if (clickedCard) {
            clickedCard.style.border = '3px solid #007bff';
            clickedCard.style.backgroundColor = '#e3f2fd';
            clickedCard.style.transform = 'scale(1.02)';
            clickedCard.style.boxShadow = '0 4px 12px rgba(0, 123, 255, 0.3)';
        }
        
        if (techType === 'primary') {
            // Clear any existing primary technician selection
            document.querySelectorAll('.technician-card input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
                const card = cb.closest('.technician-card');
                if (card) {
                    card.querySelector('.card-body').style.backgroundColor = '';
                    card.querySelector('.card-body').style.color = '';
                    card.querySelector('.card-body').style.transform = '';
                    card.querySelector('.card-body').style.boxShadow = '';
                }
            });
            
            const checkbox = document.getElementById("technician_" + techId);
            if (checkbox) {
                checkbox.checked = true;
                
                // Apply visual selection styling
                const techCard = checkbox.closest('.technician-card');
                if (techCard) {
                    const cardBody = techCard.querySelector('.card-body');
                    cardBody.style.backgroundColor = '#007bff';
                    cardBody.style.color = 'white';
                    cardBody.style.transform = 'scale(1.02)';
                    cardBody.style.boxShadow = '0 4px 8px rgba(0, 123, 255, 0.3)';
                }
                
                scrollToSelectedTechnician(checkbox);
                console.log('Primary technician selected:', techId);
                
                // Update secondary technician availability
                if (typeof updateSecondaryTechnicianAvailability === 'function') {
                    updateSecondaryTechnicianAvailability();
                }
            } else {
                console.error('Primary technician checkbox not found:', 'technician_' + techId);
            }
        } else {
            // Clear any existing secondary technician selection
            document.querySelectorAll('.technician-card-2 input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
                const card = cb.closest('.technician-card-2');
                if (card) {
                    card.querySelector('.card-body').style.backgroundColor = '';
                    card.querySelector('.card-body').style.color = '';
                    card.querySelector('.card-body').style.transform = '';
                    card.querySelector('.card-body').style.boxShadow = '';
                }
            });
            
            const checkbox = document.getElementById("technician2_" + techId);
            if (checkbox) {
                checkbox.checked = true;
                
                // Apply visual selection styling (same blue as primary)
                const techCard = checkbox.closest('.technician-card-2');
                if (techCard) {
                    const cardBody = techCard.querySelector('.card-body');
                    cardBody.style.backgroundColor = '#007bff';
                    cardBody.style.color = 'white';
                    cardBody.style.transform = 'scale(1.02)';
                    cardBody.style.boxShadow = '0 4px 8px rgba(0, 123, 255, 0.3)';
                }
                
                scrollToSelectedTechnician(checkbox, '.technician-card-2');
                console.log('Secondary technician selected:', techId);
                
                // Update primary technician availability
                if (typeof updatePrimaryTechnicianAvailability === 'function') {
                    updatePrimaryTechnicianAvailability();
                }
            } else {
                console.error('Secondary technician checkbox not found:', 'technician2_' + techId);
            }
        }
        
        // Close the modal after a short delay to show feedback
        setTimeout(() => {
            const cancelButton = document.querySelector('.modal .btn-secondary');
            if (cancelButton) {
                cancelButton.click();
            }
            
            // Show success toast
            const techTypeLabel = techType === 'primary' ? 'Primary' : 'Secondary';
            if (typeof successToast === 'function') {
                successToast(`${techTypeLabel} technician selected: ${techName}`);
            }
        }, 300);
    }

    // Global variables to store frequent technician data
    let frequentPrimaryTechnicianId = null;
    let frequentSecondaryTechnicianId = null;
    let frequentTechniciansData = null;

    function checkTechnicianSelectionAndShowJustification() {
        // Skip justification check entirely if justification section doesn't exist (edit mode)
        const justificationSection = document.getElementById('technician-justification-section');
        if (!justificationSection) {
            console.log('Justification section not found - skipping check (edit mode)');
            return;
        }
        
        console.log('Checking technician justification...', {
            frequentPrimaryTechnicianId,
            frequentSecondaryTechnicianId,
            frequentTechniciansData
        });
        
        // Skip check if we don't have frequent technician data (new customer or no completed appointments)
        if (!frequentPrimaryTechnicianId) {
            console.log('No frequent technician data found, hiding justification section');
            justificationSection.style.display = 'none';
            return;
        }

        const selectedPrimary = document.querySelector('.technician-card input[type="checkbox"]:checked');
        const selectedSecondary = document.querySelector('.technician-card-2 input[type="checkbox"]:checked');
        
        const selectedPrimaryId = selectedPrimary ? selectedPrimary.closest('.technician-card').dataset.technicianId : null;
        const selectedSecondaryId = selectedSecondary ? selectedSecondary.closest('.technician-card-2').dataset.technicianId : null;
        
        // Check if customer deviated from frequent selections
        let needsJustification = false;
        let justificationReasons = [];
        
        // Convert IDs to strings for consistent comparison
        const frequentPrimaryId = frequentPrimaryTechnicianId ? String(frequentPrimaryTechnicianId) : null;
        const frequentSecondaryId = frequentSecondaryTechnicianId ? String(frequentSecondaryTechnicianId) : null;
        const selectedPrimaryIdStr = selectedPrimaryId ? String(selectedPrimaryId) : null;
        const selectedSecondaryIdStr = selectedSecondaryId ? String(selectedSecondaryId) : null;
        
        // Check primary technician
        if (frequentPrimaryId && selectedPrimaryIdStr !== frequentPrimaryId) {
            needsJustification = true;
            justificationReasons.push('primary technician');
            console.log('Primary technician mismatch:', {
                frequent: frequentPrimaryId,
                selected: selectedPrimaryIdStr
            });
        }
        
        // Check secondary technician (only if they have a frequent secondary technician)
        if (frequentSecondaryId && selectedSecondaryIdStr !== frequentSecondaryId) {
            needsJustification = true;
            justificationReasons.push('secondary technician');
            console.log('Secondary technician mismatch:', {
                frequent: frequentSecondaryId,
                selected: selectedSecondaryIdStr
            });
        }
        
        const justificationTextarea = document.getElementById('technician-justification');
        
        if (needsJustification) {
            // Update the notice message to be more specific
            const noticeText = justificationSection.querySelector('.text-muted');
            if (justificationReasons.length === 1) {
                noticeText.textContent = `You have not selected your frequently used ${justificationReasons[0]}. Please provide a reason for this change.`;
            } else {
                noticeText.textContent = `You have not selected your frequently used ${justificationReasons.join(' and ')}. Please provide a reason for this change.`;
            }
            
            justificationSection.style.display = 'block';
            justificationTextarea.required = true;
        } else {
            justificationSection.style.display = 'none';
            justificationTextarea.required = false;
            justificationTextarea.value = ''; // Clear any existing text
        }
    }

    async function getMostSelectedTech() {
        await fetch('api/customer/get_most_selected_technician.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const topTechnicians = data.top_technicians;
                const mostUsedService = data.most_used_service;
                const mostUsedAppliance = data.most_used_appliance;
                
                // Store frequent technician data globally
                frequentTechniciansData = data;
                if (topTechnicians && topTechnicians.length > 0) {
                    frequentPrimaryTechnicianId = topTechnicians[0].user_technician;
                    if (topTechnicians.length > 1) {
                        frequentSecondaryTechnicianId = topTechnicians[1].user_technician;
                    }
                }
                
                if (topTechnicians && topTechnicians.length > 0) {
                    let modalContent = '';
                    
                    if (topTechnicians.length === 1) {
                        // Single technician display
                        const tech = topTechnicians[0];
                        modalContent = `
                            <div class="text-center mb-3">
                                <img src="././img/tech.png" alt="" width="120" class="mb-2 round_md bg-light border">
                                <br><span class="fw-bold h5">${tech.tech_name} ${tech.tech_midname} ${tech.tech_lastname}</span>
                                <br><p class="m-0 mt-2">Your most frequently selected technician. Select them again for consistent service?</p>
                            </div>`;
                    } else {
                        // Two technicians display
                        modalContent = `
                            <div class="text-center mb-3">
                                <h5 class="mb-3">Your Most Selected Technicians</h5>
                                <p class="text-muted mb-4">Choose your preferred technicians for consistent service</p>
                            </div>
                            <div class="row g-3">`;
                        
                        topTechnicians.forEach((tech, index) => {
                            const label = index === 0 ? 'Primary Choice' : 'Secondary Choice';
                            const badgeClass = index === 0 ? 'bg-primary' : 'bg-success';
                            const techType = index === 0 ? 'primary' : 'secondary';
                            modalContent += `
                                <div class="col-6">
                                    <div class="card h-100 tech-suggestion-card" 
                                         onclick="selectTechnicianFromModal('${tech.user_technician}', '${techType}', '${tech.tech_name} ${tech.tech_midname} ${tech.tech_lastname}')" 
                                         style="cursor: pointer; transition: all 0.3s;"
                                         onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';"
                                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';"
                                         >
                                        <div class="card-body text-center p-3">
                                            <span class="badge ${badgeClass} mb-2">${label}</span>
                                            <img src="././img/tech.png" alt="" width="80" class="mb-2 round_md bg-light border">
                                            <h6 class="card-title mb-1">${tech.tech_name} ${tech.tech_midname} ${tech.tech_lastname}</h6>
                                            <small class="text-muted">${tech.technician_count} completed jobs</small>
                                        </div>
                                    </div>
                                </div>`;
                        });
                        
                        modalContent += `
                            </div>
                            <div class="text-center mt-4">
                                <small class="text-muted">Click on a technician card to select them, or choose 'Skip' to select manually</small>
                            </div>`;
                    }
                    
                    modalContent += `
                    <?php if ($isEditMode): ?>
                        <?php if (!empty($appointmentData->technician_justification)): ?>
                            <div class="mb-4">
                                <h6 class="mb-2">
                                    <i class="bi bi-chat-square-text text-primary me-1"></i>
                                    Previous Justification
                                </h6>
                                <div class="p-3 bg-light border round_lg">
                                    <?php echo htmlspecialchars($appointmentData->technician_justification); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    `;
                    
                    showDialog({
                        title: ' ',
                        message: modalContent,
                        confirmText: 'Confirm',
                        cancelText: 'Skip',
                        onConfirm: function () {
                            // Pre-select Service Type if available
                            if (mostUsedService && mostUsedService.service_type_id) {
                                const serviceTypeSelect = document.getElementById('service-type-select');
                                if (serviceTypeSelect) {
                                    serviceTypeSelect.value = mostUsedService.service_type_id;
                                    
                                    // Trigger change event to load appliance types
                                    const changeEvent = new Event('change', { bubbles: true });
                                    serviceTypeSelect.dispatchEvent(changeEvent);
                                }
                            }
                            
                            // Pre-select Appliance Type if available (with delay to allow service type to load appliances)
                            if (mostUsedAppliance && mostUsedAppliance.appliances_type_id) {
                                setTimeout(() => {
                                    const applianceTypeSelect = document.getElementById('appliance-type-select');
                                    if (applianceTypeSelect) {
                                        applianceTypeSelect.value = mostUsedAppliance.appliances_type_id;
                                    }
                                }, 500); // Wait for appliance types to load
                            }
                            
                            if (topTechnicians.length === 1) {
                                // Original single technician selection logic
                                const tech = topTechnicians[0];
                                const checkbox = document.getElementById("technician_" + tech.user_technician);
                                if (checkbox) {
                                    checkbox.checked = true;
                                    scrollToSelectedTechnician(checkbox);
                                }
                            } else {
                                // For two technicians, apply any selections made by clicking cards
                                const selectedPrimary = document.querySelector('.technician-card input[type="checkbox"]:checked');
                                const selectedSecondary = document.querySelector('.technician-card-2 input[type="checkbox"]:checked');
                            }
                            
                            // Show comprehensive success message
                            let confirmationParts = [];
                            if (mostUsedService) {
                                confirmationParts.push(`Service: ${mostUsedService.service_type_name}`);
                            }
                            if (mostUsedAppliance) {
                                confirmationParts.push(`Appliance: ${mostUsedAppliance.appliances_type_name}`);
                            }
                            
                            const selectedPrimary = document.querySelector('.technician-card input[type="checkbox"]:checked');
                            const selectedSecondary = document.querySelector('.technician-card-2 input[type="checkbox"]:checked');
                            
                            if (selectedPrimary || selectedSecondary) {
                                let technicianPart = 'Technicians: ';
                                if (selectedPrimary && selectedSecondary) {
                                    technicianPart += 'Primary & Secondary selected';
                                } else if (selectedPrimary) {
                                    technicianPart += 'Primary selected';
                                } else if (selectedSecondary) {
                                    technicianPart += 'Secondary selected';
                                }
                                confirmationParts.push(technicianPart);
                            }
                            
                            const confirmationMessage = confirmationParts.length > 0 
                                ? `Pre-selected: ${confirmationParts.join(', ')}` 
                                : 'Frequent selections applied!';
                            // Show success toast
                            if (typeof successToast === 'function') {
                                successToast('Technicians and preferences selected successfully!');
                            }
                            
                            // Check if justification is needed after modal selection
                            setTimeout(() => {
                                console.log('Running justification check after modal confirm...');
                                checkTechnicianSelectionAndShowJustification();
                            }, 500);
                        },
                        onCancel: function () {
                            // Skip button - just close the modal without any action
                            if (typeof infoToast === 'function') {
                                infoToast('Technician suggestions skipped. You can select manually.');
                            }
                        }
                    });
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById("appointment-date").addEventListener('change', async function () {
            console.log('Date changed to:', this.value);

            // Reset all technician selections and styles
            document.querySelectorAll('.technician-card input[type="checkbox"], .technician-card-2 input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
                checkbox.disabled = false;
                const cardBody = checkbox.closest('.technician-card, .technician-card-2').querySelector('.card-body');
                cardBody.style.backgroundColor = '';
                cardBody.style.color = '';
                cardBody.style.transform = '';
                cardBody.style.boxShadow = '';
                cardBody.style.opacity = '1';
                cardBody.style.cursor = 'pointer';
            });

            // Reset all badges
            document.querySelectorAll('.technician-card .badge, .technician-card-2 .badge').forEach(badge => {
                badge.style.display = 'none';
            });

            // Use the comprehensive availability checking that includes ongoing appointments
            await updateTechnicianAvailabilityDisplay();

            // Check for booked time slots
            getBookedTimeSlots(this.value);
        });

        const isEditMode = <?= $isEditMode ? 'true' : 'false' ?>;
        const isRebookMode = <?= $isRebookMode ? 'true' : 'false' ?>;
        const appointmentId = <?= ($isEditMode || $isRebookMode) ? $appointmentData->app_id : 'null' ?>;

        if (isEditMode || isRebookMode) {
            const selectedTechnicianId = document.querySelector('input[name="technician"]:checked').id;

            getBookedTechnicians(document.getElementById("appointment-date").value).then(technicianIds => {
                technicianIds.forEach(id => {
                    if (id === selectedTechnicianId) {
                        return;
                    }
                    const checkbox = document.querySelector("#" + id);
                    const card = document.querySelector(`.technician-card[data-technician-id="${id.replace('technician_', '')}"]`);
                    const badge = card.querySelector('.badge');

                    if (checkbox) {
                        checkbox.disabled = true;
                    }

                    if (badge) {
                        badge.style.display = 'inline-block';
                    }
                });
            });
            setTimeout(() => {
                const selectedTechnician = document.querySelector('.technician-card input[type="checkbox"]:checked');
                if (selectedTechnician) {
                    const technicianCard = selectedTechnician.closest('.technician-card');
                    const technicianContainer = document.querySelector('.scroll-container:has(.technician-card)');

                    if (!isElementFullyVisible(technicianCard, technicianContainer)) {
                        const containerRect = technicianContainer.getBoundingClientRect();
                        const cardRect = technicianCard.getBoundingClientRect();

                        let scrollPosition = technicianContainer.scrollLeft;

                        if (cardRect.left < containerRect.left) {
                            scrollPosition += cardRect.left - containerRect.left - 60;
                        } else if (cardRect.right > containerRect.right) {
                            scrollPosition += cardRect.right - containerRect.right + 60;
                        }

                        technicianContainer.scrollTo({
                            left: scrollPosition,
                            behavior: 'smooth'
                        });
                    }
                }

                // Handle secondary technician scrolling in edit/rebook mode
                const selectedSecondaryTechnician = document.querySelector('.technician-card-2 input[type="checkbox"]:checked');
                if (selectedSecondaryTechnician) {
                    const technicianCard = selectedSecondaryTechnician.closest('.technician-card-2');
                    const technicianContainer = document.querySelector('.scroll-container:has(.technician-card-2)');

                    if (technicianContainer && !isElementFullyVisible(technicianCard, technicianContainer)) {
                        const containerRect = technicianContainer.getBoundingClientRect();
                        const cardRect = technicianCard.getBoundingClientRect();

                        let scrollPosition = technicianContainer.scrollLeft;

                        if (cardRect.left < containerRect.left) {
                            scrollPosition += cardRect.left - containerRect.left - 60;
                        } else if (cardRect.right > containerRect.right) {
                            scrollPosition += cardRect.right - containerRect.right + 60;
                        }

                        technicianContainer.scrollTo({
                            left: scrollPosition,
                            behavior: 'smooth'
                        });
                    }
                }

                const selectedService = document.querySelector('.service-card input[type="checkbox"]:checked');
                if (selectedService) {
                    const serviceCard = selectedService.closest('.service-card');
                    const serviceContainer = document.querySelector('.scroll-container:has(.service-card)');

                    if (!isElementFullyVisible(serviceCard, serviceContainer)) {
                        const containerRect = serviceContainer.getBoundingClientRect();
                        const cardRect = serviceCard.getBoundingClientRect();

                        let scrollPosition = serviceContainer.scrollLeft;

                        if (cardRect.left < containerRect.left) {
                            scrollPosition += cardRect.left - containerRect.left - 60;
                        } else if (cardRect.right > containerRect.right) {
                            scrollPosition += cardRect.right - containerRect.right + 60;
                        }

                        serviceContainer.scrollTo({
                            left: scrollPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            }, 100);
        } else {
            getMostSelectedTech();
        }
        
        // Load frequent technician data for justification checking in edit/rebook modes
        if (isEditMode || isRebookMode) {
            // Load frequent technician data without showing the modal
            fetch('api/customer/get_most_selected_technician.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const topTechnicians = data.top_technicians;
                    
                    // Store frequent technician data globally for justification checking
                    frequentTechniciansData = data;
                    if (topTechnicians && topTechnicians.length > 0) {
                        frequentPrimaryTechnicianId = topTechnicians[0].user_technician;
                        if (topTechnicians.length > 1) {
                            frequentSecondaryTechnicianId = topTechnicians[1].user_technician;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading frequent technician data:', error);
                });
        }

        // Handle service type dropdown change to load appliance types
        const serviceTypeSelect = document.getElementById('service-type-select');
        if (serviceTypeSelect) {
            serviceTypeSelect.addEventListener('change', function() {
                const serviceId = this.value;
                loadApplianceTypes(serviceId);
            });
            
            // Load appliance types for initially selected service (edit/rebook mode)
            if (serviceTypeSelect.value) {
                // Get pre-selected appliance ID from PHP data
                const preSelectedApplianceId = <?= json_encode($appointmentData->appliances_type_id ?? null) ?>;
                loadApplianceTypes(serviceTypeSelect.value, preSelectedApplianceId);
            }
        }

        document.querySelectorAll('.technician-card').forEach(card => {
            card.addEventListener('click', () => {
                const checkbox = card.querySelector('input[type="checkbox"]');
                if (checkbox.disabled) {
                    return;
                }

                document.querySelectorAll('.technician-card input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.closest('.technician-card').querySelector('.card-body').style.backgroundColor = '';
                    checkbox.closest('.technician-card').querySelector('.card-body').style.color = '';
                    checkbox.closest('.technician-card').querySelector('.card-body').style.transform = '';
                    checkbox.closest('.technician-card').querySelector('.card-body').style.boxShadow = '';
                });

                checkbox.checked = true;
                card.querySelector('.card-body').style.backgroundColor = 'var(--bg-primary)';
                card.querySelector('.card-body').style.color = 'white';
                card.querySelector('.card-body').style.transform = 'translateY(-4px)';
                card.querySelector('.card-body').style.boxShadow = 'rgba(0, 0, 0, 0.15) 0px 5px 15px 0px';

                // Update secondary technician availability
                updateSecondaryTechnicianAvailability();
                
                // Check if justification is needed
                setTimeout(() => {
                    checkTechnicianSelectionAndShowJustification();
                }, 100);

                const technicianContainer = document.querySelector('.scroll-container:has(.technician-card)');
                if (!isElementFullyVisible(card, technicianContainer)) {
                    const containerRect = technicianContainer.getBoundingClientRect();
                    const cardRect = card.getBoundingClientRect();

                    let scrollPosition = technicianContainer.scrollLeft;

                    if (cardRect.left < containerRect.left) {
                        scrollPosition += cardRect.left - containerRect.left - 60;
                    } else if (cardRect.right > containerRect.right) {
                        scrollPosition += cardRect.right - containerRect.right + 60;
                    }

                    technicianContainer.scrollTo({
                        left: scrollPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Handle secondary technician selection
        document.querySelectorAll('.technician-card-2').forEach(card => {
            card.addEventListener('click', () => {
                const checkbox = card.querySelector('input[type="checkbox"]');
                if (checkbox.disabled) {
                    return;
                }

                document.querySelectorAll('.technician-card-2 input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.closest('.technician-card-2').querySelector('.card-body').style.backgroundColor = '';
                    checkbox.closest('.technician-card-2').querySelector('.card-body').style.color = '';
                    checkbox.closest('.technician-card-2').querySelector('.card-body').style.transform = '';
                    checkbox.closest('.technician-card-2').querySelector('.card-body').style.boxShadow = '';
                });

                checkbox.checked = true;
                card.querySelector('.card-body').style.backgroundColor = 'var(--bg-primary)';
                card.querySelector('.card-body').style.color = 'white';
                card.querySelector('.card-body').style.transform = 'translateY(-4px)';
                card.querySelector('.card-body').style.boxShadow = 'rgba(0, 0, 0, 0.15) 0px 5px 15px 0px';

                // Update primary technician availability
                updatePrimaryTechnicianAvailability();
                
                // Check if justification is needed
                setTimeout(() => {
                    checkTechnicianSelectionAndShowJustification();
                }, 100);

                const technicianContainer = document.querySelector('.scroll-container:has(.technician-card-2)');
                if (!isElementFullyVisible(card, technicianContainer)) {
                    const containerRect = technicianContainer.getBoundingClientRect();
                    const cardRect = card.getBoundingClientRect();

                    let scrollPosition = technicianContainer.scrollLeft;

                    if (cardRect.left < containerRect.left) {
                        scrollPosition += cardRect.left - containerRect.left - 60;
                    } else if (cardRect.right > containerRect.right) {
                        scrollPosition += cardRect.right - containerRect.right + 60;
                    }

                    technicianContainer.scrollTo({
                        left: scrollPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Function to update secondary technician availability based on primary selection
        async function updateSecondaryTechnicianAvailability() {
            const selectedPrimaryTechnician = document.querySelector('.technician-card input[type="checkbox"]:checked');
            const primaryTechnicianId = selectedPrimaryTechnician ? 
                selectedPrimaryTechnician.closest('.technician-card').dataset.technicianId : null;

            // Get current date to check for ongoing appointments
            const dateInput = document.getElementById('appointment-date');
            const selectedDate = dateInput.value;
            
            // Get unavailable technicians due to ongoing appointments
            let unavailableTechnicians = [];
            if (selectedDate) {
                try {
                    const availability = await getTechnicianAvailability(selectedDate);
                    unavailableTechnicians = availability.unavailableTechnicians;
                } catch (error) {
                    console.error('Error getting technician availability:', error);
                }
            }

            document.querySelectorAll('.technician-card-2').forEach(card => {
                const technicianId = card.dataset.technicianId;
                const checkbox = card.querySelector('input[type="checkbox"]');
                const cardBody = card.querySelector('.card-body');
                const unavailableBadge = card.querySelector('.badge');
                
                const baseFormattedId = `technician_${technicianId}`;
                const isUnavailableDueToAppointment = unavailableTechnicians.includes(baseFormattedId);
                const isSameAsPrimary = primaryTechnicianId && technicianId === primaryTechnicianId;

                if (isUnavailableDueToAppointment || isSameAsPrimary) {
                    // Disable this technician (either due to ongoing appointment or same as primary)
                    checkbox.disabled = true;
                    cardBody.style.opacity = '0.6';
                    cardBody.style.cursor = 'not-allowed';
                    cardBody.style.backgroundColor = '#f8f9fa';
                    cardBody.style.color = '#6c757d';
                    if (unavailableBadge) {
                        unavailableBadge.style.display = 'block';
                    }
                    
                    // If this technician was selected as secondary, unselect them
                    if (checkbox.checked) {
                        checkbox.checked = false;
                        cardBody.style.backgroundColor = '#f8f9fa';
                        cardBody.style.color = '#6c757d';
                        cardBody.style.transform = '';
                        cardBody.style.boxShadow = '';
                    }
                } else {
                    // Enable this technician (available and not same as primary)
                    checkbox.disabled = false;
                    cardBody.style.opacity = '1';
                    cardBody.style.cursor = 'pointer';
                    cardBody.style.backgroundColor = '';
                    cardBody.style.color = '';
                    if (unavailableBadge) {
                        unavailableBadge.style.display = 'none';
                    }
                }
            });
        }

        // Function to update primary technician availability based on secondary selection
        async function updatePrimaryTechnicianAvailability() {
            const selectedSecondaryTechnician = document.querySelector('.technician-card-2 input[type="checkbox"]:checked');
            const secondaryTechnicianId = selectedSecondaryTechnician ? 
                selectedSecondaryTechnician.closest('.technician-card-2').dataset.technicianId : null;

            // Get current date to check for ongoing appointments
            const dateInput = document.getElementById('appointment-date');
            const selectedDate = dateInput.value;
            
            // Get unavailable technicians due to ongoing appointments
            let unavailableTechnicians = [];
            if (selectedDate) {
                try {
                    const availability = await getTechnicianAvailability(selectedDate);
                    unavailableTechnicians = availability.unavailableTechnicians;
                } catch (error) {
                    console.error('Error getting technician availability:', error);
                }
            }

            document.querySelectorAll('.technician-card').forEach(card => {
                const technicianId = card.dataset.technicianId;
                const checkbox = card.querySelector('input[type="checkbox"]');
                const cardBody = card.querySelector('.card-body');
                const unavailableBadge = card.querySelector('.badge');
                
                const baseFormattedId = `technician_${technicianId}`;
                const isUnavailableDueToAppointment = unavailableTechnicians.includes(baseFormattedId);
                const isSameAsSecondary = secondaryTechnicianId && technicianId === secondaryTechnicianId;

                if (isUnavailableDueToAppointment || isSameAsSecondary) {
                    // Disable this technician (either due to ongoing appointment or same as secondary)
                    checkbox.disabled = true;
                    cardBody.style.opacity = '0.5';
                    cardBody.style.cursor = 'not-allowed';
                    unavailableBadge.style.display = 'block';
                    
                    // If this technician was selected as primary, unselect them
                    if (checkbox.checked) {
                        checkbox.checked = false;
                        cardBody.style.backgroundColor = '';
                        cardBody.style.color = '';
                        cardBody.style.transform = '';
                        cardBody.style.boxShadow = '';
                    }
                } else {
                    // Enable this technician in primary selection
                    checkbox.disabled = false;
                    cardBody.style.opacity = '1';
                    cardBody.style.cursor = 'pointer';
                    unavailableBadge.style.display = 'none';
                }
            });
        }

        const submitButton = document.getElementById('submit-appointment');
        const spinner = document.getElementById('submit-spinner');

        submitButton.addEventListener('click', function () {
            spinner.style.display = 'inline-block';
            submitButton.disabled = true;

            const formData = collectFormData();
            submitAppointment(formData);
        });

        // Function to load appliance types based on selected service type
        async function loadApplianceTypes(serviceTypeId, selectedApplianceId = null) {
            const applianceSelect = document.getElementById('appliance-type-select');
            
            if (!serviceTypeId) {
                // Reset to default option when no service type is selected
                applianceSelect.innerHTML = '<option value="">Choose Appliances Type</option>';
                return;
            }
            
            try {
                const response = await fetch(`api/customer/get_appliance_types.php?service_type_id=${serviceTypeId}`);
                const data = await response.json();
                
                if (data.success && data.appliances.length > 0) {
                    // Clear existing options and add default option
                    applianceSelect.innerHTML = '<option value="">Choose Appliances Type</option>';
                    
                    // Add appliance type options
                    data.appliances.forEach(appliance => {
                        const option = document.createElement('option');
                        option.value = appliance.appliances_type_id;
                        option.textContent = appliance.appliances_type_name;
                        
                        // Pre-select if this matches the selected appliance ID
                        if (selectedApplianceId && appliance.appliances_type_id == selectedApplianceId) {
                            option.selected = true;
                        }
                        
                        applianceSelect.appendChild(option);
                    });
                } else {
                    // If no appliances found for this service type, show default option
                    applianceSelect.innerHTML = '<option value="">Choose Appliances Type</option>';
                }
            } catch (error) {
                console.error('Error loading appliance types:', error);
                applianceSelect.innerHTML = '<option value="">Choose Appliances Type</option>';
            }
        }

        // Function to get booked time slots for a specific date and disable them
        async function getBookedTimeSlots(date) {
            const timeSelect = document.getElementById('appointment-time');
            
            if (!date) {
                // Reset all time slots to enabled if no date is selected
                Array.from(timeSelect.options).forEach(option => {
                    if (option.value) {
                        option.disabled = false;
                        option.textContent = option.textContent.replace(' (Booked)', '');
                    }
                });
                return;
            }
            
            try {
                const response = await fetch(`api/customer/get_booked_timeslots.php?date=${date}`);
                const data = await response.json();
                
                if (data.success) {
                    // Reset all options first
                    Array.from(timeSelect.options).forEach(option => {
                        if (option.value) {
                            option.disabled = false;
                            option.textContent = option.textContent.replace(' (Booked)', '');
                        }
                    });
                    
                    // Disable booked time slots
                    data.booked_slots.forEach(bookedTime => {
                        const option = timeSelect.querySelector(`option[value="${bookedTime}"]`);
                        if (option) {
                            option.disabled = true;
                            option.textContent += ' (Booked)';
                        }
                    });
                } else {
                    console.error('Error fetching booked time slots:', data.error);
                }
            } catch (error) {
                console.error('Error fetching booked time slots:', error);
            }
        }

        function collectFormData() {
            // Get service type from dropdown instead of service cards
            const serviceTypeSelect = document.getElementById('service-type-select');
            const serviceTypeId = serviceTypeSelect ? serviceTypeSelect.value || null : null;

            const applianceSelect = document.getElementById('appliance-type-select');
            const applianceTypeId = applianceSelect.value || null;

            const dateInput = document.querySelector('#appointment-date').value;
            const timeInput = document.querySelector('#appointment-time').value;

            const app_schedule = dateInput && timeInput ? `${dateInput} ${timeInput}:00` : null;

            const selectedTechnicianCard = document.querySelector('.technician-card input[type="checkbox"]:checked');
            const technicianId = selectedTechnicianCard ?
                selectedTechnicianCard.closest('.technician-card').dataset.technicianId :
                null;

            // Get second technician selection
            const selectedTechnician2Card = document.querySelector('.technician-card-2 input[type="checkbox"]:checked');
            const technician2Id = selectedTechnician2Card ?
                selectedTechnician2Card.closest('.technician-card-2').dataset.technicianId :
                null;

            const description = document.querySelector('#appointment-description').value.trim();
            
            // Get technician justification if the section is visible
            const justificationSection = document.getElementById('technician-justification-section');
            const justificationTextarea = document.getElementById('technician-justification');
            let technicianJustification = null;
            
            // In edit mode, always include the justification if the section exists
            // In create/rebook mode, only include if section is visible
            if (isEditMode) {
                // In edit mode, we always include the justification field
                if (justificationSection && justificationTextarea) {
                    technicianJustification = justificationTextarea.value.trim();
                }
            } else {
                // In create/rebook mode, only include if section is visible
                if (justificationSection && justificationSection.style.display !== 'none') {
                    technicianJustification = justificationTextarea.value.trim();
                }
            }

            const data = {
                app_schedule: app_schedule,
                app_desc: description,
                service_type_id: serviceTypeId,
                appliances_type_id: applianceTypeId,
                user_technician: technicianId,
                user_technician_2: technician2Id || null,
                is_rebook: isRebookMode
            };
            
            // Include technician_justification in data object
            // In edit mode, justification is always included (even if empty)
            // In create/rebook mode, only included when needed
            if (isEditMode) {
                data.technician_justification = technicianJustification;
            } else if (technicianJustification !== null) {
                data.technician_justification = technicianJustification;
            }

            if (isEditMode) {
                data.app_id = appointmentId;
            }

            return data;
        }

        function submitAppointment(formData) {
            if (!formData.service_type_id) {
                dangerToast('Please select a service type.');
                resetSubmitButton();
                return;
            }

            if (!formData.app_schedule) {
                dangerToast('Please select a date and time.');
                resetSubmitButton();
                return;
            }

            if (!formData.user_technician) {
                dangerToast('Please select a primary technician.');
                resetSubmitButton();
                return;
            }

            if (!formData.user_technician_2) {
                dangerToast('Please select a secondary technician.');
                resetSubmitButton();
                return;
            }

            if (formData.user_technician === formData.user_technician_2) {
                dangerToast('Primary and secondary technicians must be different.');
                resetSubmitButton();
                return;
            }
            
            // Remove any validation styling from justification textbox (justification is optional)
            const justificationTextarea = document.getElementById('technician-justification');
            if (justificationTextarea) {
                justificationTextarea.classList.remove('is-invalid');
            }

            const endpoint = isEditMode
                ? 'api/customer/update_app.php'
                : 'api/customer/set_app.php';

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        successToast(`Appointment successfully ${isEditMode ? 'updated' : 'scheduled'}!`);
                        setTimeout(() => {
                            window.location.href = 'index.php?page=appointment&type=2';
                        }, 2000);
                    } else {
                        dangerToast(data.message || `Failed to ${isEditMode ? 'update' : 'create'} appointment.`);
                        resetSubmitButton();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    dangerToast('An error occurred. Please try again later.');
                    resetSubmitButton();
                });
        }

        function resetSubmitButton() {
            spinner.style.display = 'none';
            submitButton.disabled = false;
        }

        // Initialize duplicate prevention logic for edit mode
        // This ensures that pre-selected technicians in edit mode trigger the validation
        function initializeEditModeValidation() {
            // Check if we have pre-selected technicians in edit mode
            const selectedPrimaryTechnician = document.querySelector('.technician-card input[type="checkbox"]:checked');
            const selectedSecondaryTechnician = document.querySelector('.technician-card-2 input[type="checkbox"]:checked');

            // If primary technician is pre-selected, update secondary availability
            if (selectedPrimaryTechnician) {
                updateSecondaryTechnicianAvailability();
            }

            // If secondary technician is pre-selected, update primary availability
            if (selectedSecondaryTechnician) {
                updatePrimaryTechnicianAvailability();
            }
        }

        // Function to update technician availability display
        async function updateTechnicianAvailabilityDisplay() {
            const dateInput = document.getElementById('appointment-date');
            const timeInput = document.getElementById('appointment-time');
            
            const selectedDate = dateInput.value;
            const selectedTime = timeInput.value;
            
            if (!selectedDate) {
                // Reset all technicians to available if no date selected
                resetTechnicianAvailability();
                return;
            }
            
            try {
                // Get technician availability data
                const availability = await getTechnicianAvailability(selectedDate, selectedTime);
                
                // Reorder technicians based on availability
                reorderTechniciansByAvailability(availability.unavailableTechnicians);
                
                // Update primary technicians
                updateTechnicianCards('.technician-card', availability.unavailableTechnicians, 'technician');
                
                // Update secondary technicians
                updateTechnicianCards('.technician-card-2', availability.unavailableTechnicians, 'technician2');
                
            } catch (error) {
                console.error('Error updating technician availability:', error);
            }
        }
        
        function reorderTechniciansByAvailability(unavailableTechnicianIds) {
            // Reorder primary technician cards
            reorderTechnicianContainer('.technician-card', unavailableTechnicianIds);
            
            // Reorder secondary technician cards
            reorderTechnicianContainer('.technician-card-2', unavailableTechnicianIds);
        }
        
        function reorderTechnicianContainer(cardSelector, unavailableTechnicianIds) {
            const container = document.querySelector('.scroll-container:has(' + cardSelector + ')');
            if (!container) return;
            
            const cards = Array.from(container.querySelectorAll(cardSelector));
            if (cards.length === 0) return;
            
            // Separate available and unavailable technicians
            const availableCards = [];
            const unavailableCards = [];
            
            cards.forEach(card => {
                const technicianId = card.getAttribute('data-technician-id');
                const baseFormattedId = `technician_${technicianId}`;
                
                if (unavailableTechnicianIds.includes(baseFormattedId)) {
                    unavailableCards.push(card);
                } else {
                    availableCards.push(card);
                }
            });
            
            // Function to extract star rating from technician card
            function getTechnicianRating(card) {
                const ratingElement = card.querySelector('.col-3.small small');
                if (ratingElement) {
                    const ratingText = ratingElement.textContent.trim();
                    const rating = parseFloat(ratingText.split(' ')[0]);
                    return isNaN(rating) ? 0 : rating;
                }
                return 0;
            }
            
            // Sort each group by star rating (highest first)
            availableCards.sort((a, b) => {
                const ratingA = getTechnicianRating(a);
                const ratingB = getTechnicianRating(b);
                return ratingB - ratingA; // Descending order (highest rating first)
            });
            
            unavailableCards.sort((a, b) => {
                const ratingA = getTechnicianRating(a);
                const ratingB = getTechnicianRating(b);
                return ratingB - ratingA; // Descending order (highest rating first)
            });
            
            // Remove all cards from container
            cards.forEach(card => card.remove());
            
            // Add available technicians first (sorted by rating), then unavailable ones (also sorted by rating)
            availableCards.forEach(card => container.appendChild(card));
            unavailableCards.forEach(card => container.appendChild(card));
            
            console.log(`Reordered ${cardSelector}: ${availableCards.length} available, ${unavailableCards.length} unavailable`);
        }
        
        function updateTechnicianCards(cardSelector, unavailableTechnicianIds, inputNamePrefix) {
            const technicianCards = document.querySelectorAll(cardSelector);
            
            technicianCards.forEach(card => {
                const technicianId = card.getAttribute('data-technician-id');
                const formattedId = `${inputNamePrefix}_${technicianId}`;
                const baseFormattedId = `technician_${technicianId}`; // Always check against base format
                const checkbox = card.querySelector('input[type="checkbox"]');
                const cardBody = card.querySelector('.card-body');
                const unavailableBadge = card.querySelector('.badge');
                
                // Check if technician is unavailable (works for both primary and secondary)
                if (unavailableTechnicianIds.includes(baseFormattedId)) {
                    // Mark as unavailable
                    checkbox.disabled = true;
                    checkbox.checked = false;
                    cardBody.style.opacity = '0.6';
                    cardBody.style.cursor = 'not-allowed';
                    cardBody.style.backgroundColor = '#f8f9fa';
                    cardBody.style.color = '#6c757d';
                    cardBody.style.transform = '';
                    cardBody.style.boxShadow = '';
                    if (unavailableBadge) {
                        unavailableBadge.style.display = 'block';
                    }
                } else {
                    // Mark as available
                    checkbox.disabled = false;
                    cardBody.style.opacity = '1';
                    cardBody.style.cursor = 'pointer';
                    cardBody.style.backgroundColor = '';
                    cardBody.style.color = '';
                    if (unavailableBadge) {
                        unavailableBadge.style.display = 'none';
                    }
                }
            });
        }
        
        function resetTechnicianAvailability() {
            const allCards = document.querySelectorAll('.technician-card, .technician-card-2');
            
            allCards.forEach(card => {
                const checkbox = card.querySelector('input[type="checkbox"]');
                const cardBody = card.querySelector('.card-body');
                const unavailableBadge = card.querySelector('.badge');
                
                checkbox.disabled = false;
                cardBody.style.opacity = '1';
                cardBody.style.cursor = 'pointer';
                cardBody.style.backgroundColor = '';
                cardBody.style.color = '';
                if (unavailableBadge) {
                    unavailableBadge.style.display = 'none';
                }
            });
        }
        
        // Add event listeners for date and time changes
        const dateInput = document.getElementById('appointment-date');
        const timeInput = document.getElementById('appointment-time');
        
        if (dateInput) {
            dateInput.addEventListener('change', updateTechnicianAvailabilityDisplay);
        }
        
        if (timeInput) {
            timeInput.addEventListener('change', updateTechnicianAvailabilityDisplay);
        }
        
        // Run initialization after a short delay to ensure DOM is fully loaded
        setTimeout(() => {
            initializeEditModeValidation();
            // Also check justification requirements on page load
            if (frequentPrimaryTechnicianId || frequentSecondaryTechnicianId) {
                checkTechnicianSelectionAndShowJustification();
            }
            // Update technician availability on page load if date/time are pre-selected
            updateTechnicianAvailabilityDisplay();
        }, 100);
    });
</script>