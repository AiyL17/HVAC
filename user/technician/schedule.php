<style>
    /* Empty State Styling */
    .empty-state {
        padding: 2rem 1rem;
        text-align: center;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin: 1rem 0;
    }

    .empty-state-mobile {
        padding: 2rem 1rem;
        text-align: center;
        background-color: #f8f9fa;
        border-radius: 12px;
        margin: 1rem 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
    }
</style>

<?php
$technician_id = $_SESSION['uid'];
include_once __DIR__ . '/../../config/ini.php';
$pdo = pdo_init();

// Handle month/year filter from GET
$currentMonth = $_GET['month'] ?? date('n');
$currentYear = $_GET['year'] ?? date('Y');

// Ensure month and year are integers
$currentMonth = (int)$currentMonth;
$currentYear = (int)$currentYear;

// Calculate first and last day of the month
$firstDay = date('Y-m-01', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
$lastDay = date('Y-m-t', mktime(0, 0, 0, $currentMonth, 1, $currentYear));

// Debug: Log the date range being used
// Uncomment for debugging
/*
error_log("Technician Schedule - Month: $currentMonth, Year: $currentYear");
error_log("Date range: $firstDay to $lastDay");
error_log("Technician ID: $technician_id");
*/

// Fetch appointments for the technician for the entire month
$query = "SELECT 
    a.app_id,
    a.app_schedule,
    a.app_price,
    a.app_status_id,
    a.app_desc,
    a.app_justification,
    a.decline_justification,
    a.payment_status,
    COALESCE(s.service_type_name, 'Unknown Service') as service_type_name,
    COALESCE(u.user_name, 'Unknown') as customer_fname,
    COALESCE(u.user_midname, '') as customer_mname,
    COALESCE(u.user_lastname, 'Customer') as customer_lname,
    COALESCE(u.user_contact, '') as customer_contact,
    COALESCE(u.house_building_street, '') as customer_house_building_street,
    COALESCE(ata.barangay, u.barangay, '') as customer_barangay,
    COALESCE(ata.municipality_city, u.municipality_city, '') as customer_municipality_city,
    COALESCE(ata.province, u.province, '') as customer_province,
    COALESCE(ata.zip_code, u.zip_code, '') as customer_zip_code,
    COALESCE(tech.user_name, 'Unknown') as technician_fname,
    COALESCE(tech.user_midname, '') as technician_mname,
    COALESCE(tech.user_lastname, 'Technician') as technician_lname,
    COALESCE(tech2.user_name, '') as technician2_fname,
    COALESCE(tech2.user_midname, '') as technician2_mname,
    COALESCE(tech2.user_lastname, '') as technician2_lname,
    COALESCE(ast.app_status_name, 'Unknown Status') as app_status_name,
    COALESCE(s.service_type_price_min, 0) as service_type_min_price,
    COALESCE(s.service_type_price_max, 0) as service_type_max_price
FROM 
    appointment a
LEFT JOIN 
    service_type s ON a.service_type_id = s.service_type_id
LEFT JOIN 
    user u ON a.user_id = u.user_id
LEFT JOIN 
    appointment_transaction_address ata ON a.app_id = ata.app_id
LEFT JOIN 
    user tech ON a.user_technician = tech.user_id
LEFT JOIN 
    user tech2 ON a.user_technician_2 = tech2.user_id
LEFT JOIN 
    appointment_status ast ON a.app_status_id = ast.app_status_id
WHERE 
    (a.user_technician = :technician_id OR a.user_technician_2 = :technician_id2)
    AND DATE(a.app_schedule) >= :firstDay 
    AND DATE(a.app_schedule) <= :lastDay
ORDER BY a.app_schedule ASC";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':technician_id', $technician_id);
$stmt->bindParam(':technician_id2', $technician_id);
$stmt->bindParam(':firstDay', $firstDay);
$stmt->bindParam(':lastDay', $lastDay);

// Debug: Log the SQL query and parameters
// Uncomment for debugging
/*
error_log("SQL Query: " . $query);
error_log("Parameters - Technician ID: $technician_id, First Day: $firstDay, Last Day: $lastDay");
*/

$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_OBJ);

// Debug: Log appointment count and statuses for troubleshooting
// Uncomment the following lines for debugging if needed
/*
error_log("Technician Schedule Debug - Total appointments found: " . count($appointments));
foreach ($appointments as $app) {
    $appDate = date('Y-m-d', strtotime($app->app_schedule));
    error_log("Appointment ID: {$app->app_id}, Date: {$appDate}, Status: {$app->app_status_id} ({$app->app_status_name}), Customer: {$app->customer_fname}");
}
*/

// Verify appointments are within the expected date range
if (count($appointments) > 0) {
    $appointmentDates = array_map(function($app) {
        return date('Y-m-d', strtotime($app->app_schedule));
    }, $appointments);
    
    // Uncomment for debugging date range verification
    /*
    error_log("Expected date range: $firstDay to $lastDay");
    error_log("Actual appointment dates: " . implode(', ', array_unique($appointmentDates)));
    */
}

// Organize appointments by date
$appointmentsByDate = [];
foreach ($appointments as $appointment) {
    $date = date('Y-m-d', strtotime($appointment->app_schedule));
    $appointmentsByDate[$date][] = $appointment;
}

// Calendar helper functions
function getMonthName($month) {
    return date('F', mktime(0, 0, 0, $month, 1));
}

function getDaysInMonth($month, $year) {
    return date('t', mktime(0, 0, 0, $month, 1, $year));
}

function getFirstDayOfWeek($month, $year) {
    return date('w', mktime(0, 0, 0, $month, 1, $year));
}
?>

<style>
    /* Dashboard Consistent Styling */
    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: 1px solid #dee2e6;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .dashboard-card-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        padding: 12px 20px;
        border-radius: 12px 12px 0 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .dashboard-card-body {
        padding: 24px;
    }

    .calendar-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .calendar-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 20px;
    }
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background: #dee2e6;
        border-radius: 0 0 12px 12px;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
    .calendar-day-header {
        background: #495057;
        color: white;
        padding: 12px 8px;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .calendar-day {
        background: white;
        min-height: 120px;
        padding: 8px;
        position: relative;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }
    .calendar-day:hover {
        background: #f8f9fa;
    }
    .calendar-day.other-month {
        background: #f8f9fa;
        color: #6c757d;
    }
    .calendar-day.today {
        background: #e3f2fd;
        border: 2px solid #2196f3;
    }
    .day-number {
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }
    .appointment-item {
        background: #007bff;
        color: white;
        padding: 2px 6px;
        margin: 1px 0;
        border-radius: 3px;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.2s ease;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .appointment-item:hover {
        background: #0056b3;
        transform: scale(1.02);
    }
    /* Status-based color coding */
    .appointment-item.status-1 { background: #ffc107; color: #000; } /* Pending - Yellow */
    .appointment-item.status-2 { background: #007bff; } /* Approved - Blue */
    .appointment-item.status-3 { background: #28a745; } /* Completed - Green */
    .appointment-item.status-4 { background: #dc3545; } /* Declined/Cancelled - Red */
    .appointment-item.status-5 { background: #fd7e14; } /* In Progress - Orange */
    
    /* Semantic status color coding (name-based) */
    .appointment-item.status-pending { background: #ffc107; color: #000; } /* Pending - Yellow */
    .appointment-item.status-approved { background: #007bff; } /* Approved - Blue */
    .appointment-item.status-completed { background: #28a745; } /* Completed - Green */
    .appointment-item.status-declined { background: #dc3545; } /* Declined/Cancelled - Red */
    .appointment-item.status-progress { background: #fd7e14; } /* In Progress - Orange */
    .appointment-count {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #007bff;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .month-nav-btn {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        transition: all 0.2s ease;
        font-weight: 500;
        text-decoration: none;
    }
    .month-nav-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        text-decoration: none;
    }
    .month-nav-btn:focus,
    .month-nav-btn:active {
        background: rgba(255,255,255,0.3);
        color: white;
        text-decoration: none;
        box-shadow: none;
    }
    .appointment-modal .modal-body {
        max-height: 600px;
        overflow-y: auto;
    }
    .bg-orange {
        background-color: #fd7e14 !important;
        color: white !important;
    }
    
    /* Appointment Card Styles */
    .appointment-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }
    
    .appointment-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        transform: translateY(-1px);
    }
    
    .appointment-header {
        background: #f8f9fa;
        padding: 12px 15px;
        border-bottom: 1px solid #e0e0e0;
        border-radius: 8px 8px 0 0;
    }
    
    .appointment-body {
        padding: 15px;
    }
    
    .info-item {
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        font-size: 0.9rem;
    }
    
    .info-item i {
        width: 16px;
        flex-shrink: 0;
    }
    
    .additional-info {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }

    /* ===== MOBILE RESPONSIVENESS ===== */
    
    @media (max-width: 991.98px) {
        .container-fluid {
            padding-left: 5px;
            padding-right: 5px;
            overflow-x: hidden;
            max-width: 100vw;
        }
        
        body {
            overflow-x: hidden;
        }
        
        .calendar-header {
            padding: 15px 10px;
            border-radius: 8px 8px 0 0;
        }
        
        .calendar-header h3 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }
        
        .calendar-header p {
            font-size: 0.9rem;
        }
        
        /* Navigation buttons mobile */
        .month-nav-btn {
            padding: 6px 10px;
            font-size: 0.85rem;
        }
        
        /* Fix button alignment on mobile - ensure buttons stay on same line */
        .calendar-header .row {
            display: flex !important;
            justify-content: space-between;
            align-items: center;
            flex-wrap: nowrap !important;
            margin: 0;
            padding: 0 8px;
        }
        
        .calendar-header .col-md-4 {
            padding: 0 !important;
            margin: 0 !important;
            flex-basis: auto !important;
            max-width: none !important;
            width: auto !important;
        }
        
        .calendar-header .col-md-4:first-child {
            flex: 0 0 auto;
            text-align: left;
        }
        
        .calendar-header .col-md-4:nth-child(2) {
            flex: 1;
            text-align: center;
            padding: 0 8px !important;
            min-width: 0;
        }
        
        .calendar-header .col-md-4:last-child {
            flex: 0 0 auto;
            text-align: right;
        }
        
        .calendar-grid {
            border-radius: 0 0 8px 8px;
            overflow-x: hidden;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }
        
        .calendar-day-header {
            padding: 4px 1px;
            font-size: 0.65rem;
            min-width: 0;
            overflow: hidden;
        }
        
        .calendar-day {
            min-height: 60px;
            padding: 1px;
            font-size: 0.7rem;
            min-width: 0;
            overflow: hidden;
        }
        
        .calendar-container {
            overflow-x: hidden;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;
            margin: 0;
        }
        
        .day-number {
            font-size: 0.8rem;
            margin-bottom: 3px;
        }
        
        .appointment-item {
            font-size: 0.6rem;
            padding: 1px 4px;
            margin: 0.5px 0;
        }
        
        .appointment-count {
            width: 16px;
            height: 16px;
            font-size: 0.6rem;
            top: 2px;
            right: 2px;
        }
        
        .modal-dialog {
            margin: 10px;
            max-width: calc(100% - 20px);
        }
        
        .appointment-modal .modal-body {
            max-height: 70vh;
            padding: 15px;
        }
    }
    
    @media (max-width: 575.98px) {
        .calendar-header h4 {
            font-size: 1.1rem;
        }
        
        .calendar-header small {
            font-size: 0.8rem;
        }
        
        .month-nav-btn {
            padding: 4px 8px;
            font-size: 0.8rem;
        }
        
        .calendar-day {
            min-height: 50px;
            padding: 1px;
        }
        
        .calendar-day-header {
            padding: 4px 1px;
            font-size: 0.65rem;
        }
        
        .day-number {
            font-size: 0.7rem;
        }
        
        .appointment-item {
            font-size: 0.5rem;
            padding: 0px 2px;
        }
        
        .appointment-count {
            width: 14px;
            height: 14px;
            font-size: 0.55rem;
        }
    }
    
    /* Hide body scrollbar when modal is open */
    body.modal-open {
        overflow: hidden !important;
    }
    
    /* Touch-friendly improvements */
    @media (max-width: 991.98px) {
        .appointment-item {
            min-height: 20px;
            display: flex;
            align-items: center;
        }
        
        .month-nav-btn,
        .btn {
            min-height: 44px;
            touch-action: manipulation;
        }
    }
</style>

<div class="container-fluid mt-3 mb-3">
    <h3 style="margin-bottom: 24px; margin-top: 10px;">Schedules</h3>
    <!-- Filters and Search -->
    <div class="dashboard-card mb-4">
        <div class="dashboard-card-header">
            <i class="bi bi-funnel me-2"></i>Filter Schedule
        </div>
        <div class="dashboard-card-body py-3">
            <form method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="page" value="schedule">
                
                <div class="col-lg-4 col-md-6 col-6">
                    <label class="form-label small text-muted mb-1">Month</label>
                    <select name="month" class="form-select" onchange="this.form.submit()">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $currentMonth == $m ? 'selected' : '' ?>>
                                <?= getMonthName($m) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="col-lg-4 col-md-6 col-6">
                    <label class="form-label small text-muted mb-1">Year</label>
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        <?php for ($y = date('Y') - 1; $y <= date('Y') + 2; $y++): ?>
                            <option value="<?= $y ?>" <?= $currentYear == $y ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="col-lg-4 col-md-12 col-12">
                    <label class="form-label small text-muted mb-1">&nbsp;</label>
                    <button type="button" class="btn btn-primary w-100" onclick="location.href='index.php?page=schedule&month=<?= date('n') ?>&year=<?= date('Y') ?>'">
                        <i class="bi bi-arrow-clockwise me-1"></i>This Month
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="calendar-container">
        <!-- Calendar Header -->
        <div class="calendar-header">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <?php
                    $prevMonth = $currentMonth - 1;
                    $prevYear = $currentYear;
                    if ($prevMonth < 1) {
                        $prevMonth = 12;
                        $prevYear--;
                    }
                    ?>
                    <a href="?page=schedule&month=<?= $prevMonth ?>&year=<?= $prevYear ?>" 
                       class="btn month-nav-btn">
                        <i class="bi bi-chevron-left"></i> Previous
                    </a>
                </div>
                <div class="col-md-4 text-center">
                    <h3 class="mb-1"><?= getMonthName($currentMonth) ?> <?= $currentYear ?></h3>
                    <p class="mb-0">Technician Schedule</p>
                </div>
                <div class="col-md-4 text-end">
                    <?php
                    $nextMonth = $currentMonth + 1;
                    $nextYear = $currentYear;
                    if ($nextMonth > 12) {
                        $nextMonth = 1;
                        $nextYear++;
                    }
                    ?>
                    <a href="?page=schedule&month=<?= $nextMonth ?>&year=<?= $nextYear ?>" 
                       class="btn month-nav-btn">
                        Next <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="calendar-grid">
            <!-- Day Headers -->
            <div class="calendar-day-header">Sun</div>
            <div class="calendar-day-header">Mon</div>
            <div class="calendar-day-header">Tue</div>
            <div class="calendar-day-header">Wed</div>
            <div class="calendar-day-header">Thu</div>
            <div class="calendar-day-header">Fri</div>
            <div class="calendar-day-header">Sat</div>
            
            <?php
            $daysInMonth = getDaysInMonth($currentMonth, $currentYear);
            $firstDayOfWeek = getFirstDayOfWeek($currentMonth, $currentYear);
            $today = date('Y-m-d');
            
            // Previous month's trailing days
            $prevMonth = $currentMonth - 1;
            $prevYear = $currentYear;
            if ($prevMonth < 1) {
                $prevMonth = 12;
                $prevYear--;
            }
            $daysInPrevMonth = getDaysInMonth($prevMonth, $prevYear);
            
            for ($i = $firstDayOfWeek - 1; $i >= 0; $i--) {
                $day = $daysInPrevMonth - $i;
                echo '<div class="calendar-day other-month">';
                echo '<div class="day-number">' . $day . '</div>';
                echo '</div>';
            }
            
            // Current month days
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                $isToday = $currentDate === $today;
                $dayAppointments = $appointmentsByDate[$currentDate] ?? [];
                
                echo '<div class="calendar-day' . ($isToday ? ' today' : '') . '" data-date="' . $currentDate . '">';
                echo '<div class="day-number">' . $day . '</div>';
                
                if (count($dayAppointments) > 0) {
                    // Determine the primary status for the day (highest priority status)
                    $statusPriority = ['3' => 5, '4' => 4, '5' => 3, '1' => 2, '2' => 1]; // Completed > Declined > In Progress > Approved > Pending
                    $primaryStatus = '2'; // Default to Pending
                    $maxPriority = 0;
                    
                    foreach ($dayAppointments as $appointment) {
                        $currentPriority = $statusPriority[$appointment->app_status_id] ?? 0;
                        if ($currentPriority > $maxPriority) {
                            $maxPriority = $currentPriority;
                            $primaryStatus = $appointment->app_status_id;
                        } elseif ($maxPriority == 0) {
                            // If no priority found, use the first appointment's status as fallback
                            $primaryStatus = $appointment->app_status_id;
                        }
                    }
                    
                    $countClass = '';
                    switch ($primaryStatus) {
                        case '2': // Pending
                            $countClass = 'count-pending';
                            break;
                        case '1': // Approved
                            $countClass = 'count-approved';
                            break;
                        case '4': // Declined
                            $countClass = 'count-declined';
                            break;
                        case '5': // In Progress
                            $countClass = 'count-progress';
                            break;
                        case '3': // Completed
                            $countClass = 'count-completed';
                            break;
                        default:
                            // Fallback for any other status IDs
                            $countClass = 'count-default';
                            break;
                    }
                    
                    echo '<div class="appointment-count ' . $countClass . '">' . count($dayAppointments) . '</div>';
                    
                    foreach ($dayAppointments as $appointment) {
                        $time = date('g:i A', strtotime($appointment->app_schedule));
                        $statusClass = '';
                        
                        switch ($appointment->app_status_id) {
                            case 2: // Pending
                                $statusClass = 'status-pending';
                                break;
                            case 1: // Approved
                                $statusClass = 'status-approved';
                                break;
                            case 4: // Declined
                                $statusClass = 'status-declined';
                                break;
                            case 5: // In Progress
                                $statusClass = 'status-progress';
                                break;
                            case 3: // Completed
                                $statusClass = 'status-completed';
                                break;
                            default:
                                // Fallback for any other status IDs
                                $statusClass = 'status-default';
                                break;
                        }
                        
                        echo '<div class="appointment-item ' . $statusClass . '" data-appointment-id="' . $appointment->app_id . '" data-date="' . $currentDate . '" data-bs-toggle="modal" data-bs-target="#appointmentModal">';
                        echo $time . ' - ' . $appointment->customer_fname;
                        echo '</div>';
                    }
                }
                
                echo '</div>';
            }
            
            // Next month's leading days
            $totalCells = 42; // 6 rows × 7 days
            $cellsUsed = $firstDayOfWeek + $daysInMonth;
            $remainingCells = $totalCells - $cellsUsed;
            
            for ($day = 1; $day <= $remainingCells; $day++) {
                echo '<div class="calendar-day other-month">';
                echo '<div class="day-number">' . $day . '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Appointment Details Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0056b3; color: white">
                <h5 class="modal-title" id="appointmentModalLabel">
                    <i class="bi bi-calendar3 me-2 text-white"></i>Appointments for <span id="modalDate"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Appointment details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Store appointments data for JavaScript access
const appointmentsData = <?= json_encode($appointmentsByDate) ?>;
document.addEventListener('DOMContentLoaded', function() {
    const appointmentModal = document.getElementById('appointmentModal');
    const modalDate = document.getElementById('modalDate');
    const modalBody = document.getElementById('modalBody');
    let modalInstance = null;
    
    // Initialize modal instance
    modalInstance = new bootstrap.Modal(appointmentModal, {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    
    // Handle modal events to prevent backdrop issues
    appointmentModal.addEventListener('hidden.bs.modal', function() {
        // Remove any remaining backdrops
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        
        // Restore body scroll
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    });
    
    // Handle appointment item clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('appointment-item') || e.target.closest('.appointment-item')) {
            e.preventDefault();
            e.stopPropagation();
            
            const appointmentItem = e.target.classList.contains('appointment-item') ? e.target : e.target.closest('.appointment-item');
            const date = appointmentItem.getAttribute('data-date');
            
            if (date && appointmentsData[date]) {
                showAppointmentsForDate(date);
                modalInstance.show();
            }
        }
    });
    
    // Handle calendar day clicks (for days with appointments)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('calendar-day') || e.target.closest('.calendar-day')) {
            // Only show modal if clicking on the day itself, not on appointment items
            if (!e.target.classList.contains('appointment-item') && !e.target.closest('.appointment-item')) {
                const calendarDay = e.target.classList.contains('calendar-day') ? e.target : e.target.closest('.calendar-day');
                const date = calendarDay.getAttribute('data-date');
                
                if (date && appointmentsData[date] && appointmentsData[date].length > 0) {
                    showAppointmentsForDate(date);
                    modalInstance.show();
                }
            }
        }
    });
    
    // Handle automatic form submission when month or year dropdowns change
    const monthSelect = document.getElementById('month-select');
    const yearSelect = document.getElementById('year-select');
    const filterForm = document.getElementById('schedule-filter-form');
    
    if (monthSelect && yearSelect && filterForm) {
        monthSelect.addEventListener('change', function() {
            filterForm.submit();
        });
        
        yearSelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }
    
    function showAppointmentsForDate(date) {
        const appointments = appointmentsData[date] || [];
        const formattedDate = new Date(date + 'T00:00:00').toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        modalDate.textContent = formattedDate;
        
        if (appointments.length === 0) {
            modalBody.innerHTML = '<div class="empty-state text-center py-4"><i class="bi bi-calendar-x text-muted" style="font-size: 2.5rem;"></i><h6 class="text-muted mt-3">No Appointments</h6><p class="text-muted mb-0">No appointments scheduled for this date.</p></div>';
            return;
        }
        
        // Sort appointments by status priority: Pending, In Progress, Approved, Completed, Cancelled
        const statusOrder = {
            1: 1, // Pending
            5: 2, // In Progress
            2: 3, // Approved
            3: 4, // Completed
            4: 5  // Cancelled/Declined
        };
        
        const sortedAppointments = appointments.sort((a, b) => {
            const orderA = statusOrder[parseInt(a.app_status_id)] || 999;
            const orderB = statusOrder[parseInt(b.app_status_id)] || 999;
            
            // If same status, sort by time
            if (orderA === orderB) {
                return a.app_schedule.localeCompare(b.app_schedule);
            }
            
            return orderA - orderB;
        });
        
        let html = '';
        sortedAppointments.forEach(function(appointment, index) {
            const time = new Date('2000-01-01T' + appointment.app_schedule.split(' ')[1]).toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
            
            let statusClass = 'bg-secondary';
            const statusName = appointment.app_status_name ? appointment.app_status_name.toLowerCase() : '';
            
            // Map by status name to ensure consistency
            if (statusName.includes('pending')) {
                statusClass = 'bg-warning text-dark'; // Pending - Yellow
            } else if (statusName.includes('approved') || statusName.includes('approval')) {
                statusClass = 'bg-primary'; // Approved - Blue
            } else if (statusName.includes('completed') || statusName.includes('complete')) {
                statusClass = 'bg-success'; // Completed - Green
            } else if (statusName.includes('declined') || statusName.includes('cancelled') || statusName.includes('cancel')) {
                statusClass = 'bg-danger'; // Declined/Cancelled - Red
            } else if (statusName.includes('progress') || statusName.includes('ongoing')) {
                statusClass = 'bg-orange'; // In Progress - Orange
            } else {
                // Fallback to ID-based mapping if name matching fails
                switch (parseInt(appointment.app_status_id)) {
                    case 1: statusClass = 'bg-warning text-dark'; break; // Pending - Yellow
                    case 2: statusClass = 'bg-primary'; break; // Approved - Blue
                    case 3: statusClass = 'bg-success'; break; // Completed - Green
                    case 4: statusClass = 'bg-danger'; break; // Declined/Cancelled - Red
                    case 5: statusClass = 'bg-orange'; break; // In Progress - Orange
                }
            }
            
            html += `
                <div class="appointment-card">
                    <div class="appointment-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock me-2 text-primary"></i>
                                <strong>${time}</strong>
                                <span class="badge ${statusClass} ms-2">${appointment.app_status_name}</span>
                            </div>
                            <small class="text-muted">ID: ${appointment.app_id}</small>
                        </div>
                    </div>
                    <div class="appointment-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="bi bi-person me-2 text-primary"></i>
                                    <strong>${appointment.customer_fname} ${appointment.customer_mname || ''} ${appointment.customer_lname}</strong>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                                    <span>${(() => {
                                        const addressParts = [
                                            appointment.customer_house_building_street,
                                            appointment.customer_barangay,
                                            appointment.customer_municipality_city,
                                            appointment.customer_province,
                                            appointment.customer_zip_code
                                        ].filter(part => part && part.trim() !== '');
                                        return addressParts.length > 0 ? addressParts.join(', ') : 'No address provided';
                                    })()}</span>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-tools me-2 text-primary"></i>
                                    <span>${appointment.service_type_name}</span>
                                </div>
                                ${appointment.customer_contact ? 
                                    `<div class="info-item">
                                        <i class="bi bi-telephone me-2 text-primary"></i>
                                        <span>${appointment.customer_contact}</span>
                                    </div>` : ''
                                }
                            </div>
                            <div class="col-md-6">
                                ${appointment.technician_fname ? 
                                    `<div class="info-item">
                                        <i class="bi bi-person-gear me-2 text-primary"></i>
                                        <span>Primary: ${appointment.technician_fname} ${appointment.technician_lname || ''}</span>
                                    </div>` : 
                                    `<div class="info-item text-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <span>No primary technician assigned</span>
                                    </div>`
                                }
                                ${appointment.technician2_fname && appointment.technician2_fname.trim() !== '' ? 
                                    `<div class="info-item">
                                        <i class="bi bi-person-plus me-2 text-primary"></i>
                                        <span>Secondary: ${appointment.technician2_fname} ${appointment.technician2_lname || ''}</span>
                                    </div>` : ''
                                }
                                ${(() => {
                                    // Show actual price for completed appointments, expected range for others
                                    if (appointment.app_status_id == 3 && appointment.app_price && appointment.app_price > 0) {
                                        return `<div class="info-item">
                                            <i class="bi bi-currency-exchange me-2 text-primary"></i>
                                            <span>₱${parseFloat(appointment.app_price).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                                        </div>`;
                                    } else if (appointment.service_type_min_price && appointment.service_type_max_price) {
                                        const minPrice = parseFloat(appointment.service_type_min_price);
                                        const maxPrice = parseFloat(appointment.service_type_max_price);
                                        if (minPrice > 0 && maxPrice > 0) {
                                            return `<div class="info-item">
                                                <i class="bi bi-currency-exchange me-2 text-primary"></i>
                                                <span>₱${minPrice.toLocaleString('en-US', {minimumFractionDigits: 2})} - ₱${maxPrice.toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                                            </div>`;
                                        }
                                    }
                                    return '';
                                })()}
                            </div>
                        </div>
                        ${appointment.app_desc || appointment.decline_justification || appointment.app_justification ? 
                            `<div class="additional-info">
                                ${appointment.app_desc ? 
                                    `<div class="info-item">
                                        <i class="bi bi-chat-text me-2 text-primary"></i>
                                        <span><strong>Description:</strong> ${appointment.app_desc}</span>
                                    </div>` : ''
                                }
                                ${appointment.decline_justification && (appointment.app_status_name.toLowerCase().includes('declined') || appointment.app_status_name.toLowerCase().includes('cancelled')) ? 
                                    `<div class="info-item">
                                        <i class="bi bi-x-circle me-2 text-danger"></i>
                                        <span><strong>Decline Reason:</strong> ${appointment.decline_justification}</span>
                                    </div>` : ''
                                }
                                ${appointment.app_justification && appointment.app_status_id == 3 ? 
                                    `<div class="info-item">
                                        <i class="bi bi-journal-text me-2 text-primary"></i>
                                        <span><strong>Cost Justification:</strong> ${appointment.app_justification}</span>
                                    </div>` : ''
                                }
                            </div>` : ''
                        }
                    </div>
                </div>
            `;
        });
        
        modalBody.innerHTML = html;
    }
});
</script>

<style>
    @media print {
        .filter-section, .modal { 
            display: none !important; 
        }
        .calendar-container {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
        }
    }
</style>
