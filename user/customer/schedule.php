<?php
$customer_id = $_SESSION['uid'];
include_once __DIR__ . '/../../config/ini.php';
$pdo = pdo_init();

// Handle month/year filter from GET
$currentMonth = $_GET['month'] ?? date('n');
$currentYear = $_GET['year'] ?? date('Y');

// Calculate first and last day of the month
$firstDay = date('Y-m-01', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
$lastDay = date('Y-m-t', mktime(0, 0, 0, $currentMonth, 1, $currentYear));

// Fetch appointments for the logged-in customer for the entire month
$query = "SELECT 
    a.app_id,
    a.app_schedule,
    a.app_price,
    a.app_status_id,
    a.app_desc,
    a.app_justification,
    a.decline_justification,
    COALESCE(s.service_type_name, 'Unknown Service') as service_type_name,
    COALESCE(tech.user_name, 'Unknown') as technician_fname,
    COALESCE(tech.user_midname, '') as technician_mname,
    COALESCE(tech.user_lastname, 'Technician') as technician_lname,
    COALESCE(tech2.user_name, '') as technician2_fname,
    COALESCE(tech2.user_midname, '') as technician2_mname,
    COALESCE(tech2.user_lastname, '') as technician2_lname,
    COALESCE(ast.app_status_name, 'Unknown Status') as app_status_name,
    COALESCE(ata.full_address, cust.house_building_street) as customer_house_building_street,
    COALESCE(ata.barangay, cust.barangay) as customer_barangay,
    COALESCE(ata.municipality_city, cust.municipality_city) as customer_municipality_city,
    COALESCE(ata.province, cust.province) as customer_province,
    COALESCE(ata.zip_code, cust.zip_code) as customer_zip_code,
    COALESCE(s.service_type_price_min, 0) as service_type_min_price,
    COALESCE(s.service_type_price_max, 0) as service_type_max_price
FROM 
    appointment a
LEFT JOIN 
    user cust ON a.user_id = cust.user_id
LEFT JOIN 
    appointment_transaction_address ata ON a.app_id = ata.app_id
LEFT JOIN 
    service_type s ON a.service_type_id = s.service_type_id
LEFT JOIN 
    user tech ON a.user_technician = tech.user_id
LEFT JOIN 
    user tech2 ON a.user_technician_2 = tech2.user_id
LEFT JOIN 
    appointment_status ast ON a.app_status_id = ast.app_status_id
WHERE 
    a.user_id = :customer_id
    AND DATE(a.app_schedule) BETWEEN :firstDay AND :lastDay
ORDER BY a.app_schedule ASC";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':customer_id', $customer_id);
$stmt->bindParam(':firstDay', $firstDay);
$stmt->bindParam(':lastDay', $lastDay);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_OBJ);

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
        width: 100%;
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background: #dee2e6;
        border-radius: 0 0 12px 12px;
        overflow: hidden;
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
        min-width: 0;
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
        display: block;
        text-decoration: none;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .appointment-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        color: white;
        text-decoration: none;
    }
    .appointment-item.status-pending { background: #ffc107; color: #fff; }
    .appointment-item.status-approved { background: #007bff; }
    .appointment-item.status-completed { background: #28a745; }
    .appointment-item.status-declined { background: #dc3545; }
    .appointment-item.status-progress { background: #fd7e14; }
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
    /* Dashboard Card Styling for Filter Section */
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
    
    .dashboard-card-body .row {
        margin-left: -12px;
        margin-right: -12px;
    }
    
    .dashboard-card-body .row > [class*='col-'] {
        padding-left: 12px;
        padding-right: 12px;
    }

    .filter-section {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }
    .bg-orange {
        background-color: #fd7e14 !important;
        color: white !important;
    }
    
    /* Modal Styling - Match Administrator Design */
    .appointment-modal .modal-body {
        overflow-y: visible;
    }
    
    /* Prevent background scrolling when modal is open */
    body.modal-open {
        overflow: hidden !important;
    }
    
    .appointment-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        background: white;
        margin-bottom: 15px;
    }
    
    .appointment-header {
        background: #f8f9fa;
        padding: 12px 16px;
        border-bottom: 1px solid #e9ecef;
        border-radius: 8px 8px 0 0;
    }
    
    .appointment-body {
        padding: 16px;
    }
    
    .info-item {
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .info-item:last-child {
        margin-bottom: 0;
    }
    
    .additional-info {
        border-top: 1px solid #e9ecef;
        padding-top: 12px;
        margin-top: 12px;
    }
    .month-nav-btn {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .month-nav-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        text-decoration: none;
    }

    /* ===== MOBILE RESPONSIVENESS ===== */
    
    /* Mobile-first approach - Base styles for mobile */
    @media (max-width: 991.98px) {
        /* Container adjustments - prevent horizontal overflow */
        .container-fluid {
            padding-left: 5px;
            padding-right: 5px;
            overflow-x: hidden;
            max-width: 100vw;
        }
        
        /* Ensure body doesn't overflow */
        body {
            overflow-x: hidden;
        }
        
        /* Header title */
        h3 {
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 1rem !important;
        }
        
        /* Filter section mobile optimization - 2x2 grid layout */
        .dashboard-card-body {
            padding: 15px !important;
        }
        
        .dashboard-card-body .row {
            gap: 8px;
        }
        
        /* Force 2x2 grid layout on mobile */
        .dashboard-card-body .col-md-3 {
            flex: 0 0 calc(50% - 4px) !important;
            max-width: calc(50% - 4px) !important;
            margin-bottom: 8px !important;
        }
        
        /* Adjust form controls for mobile 2x2 grid layout */
        .dashboard-card-body .form-select {
            font-size: 0.85rem !important;
            padding: 6px 8px !important;
        }
        
        .dashboard-card-body .btn {
            font-size: 0.8rem !important;
            padding: 8px 6px !important;
            white-space: nowrap;
        }
        
        .dashboard-card-body .form-label {
            font-size: 0.8rem !important;
            margin-bottom: 3px !important;
        }
        
        /* Calendar header mobile optimization */
        .calendar-header {
            padding: 5px 10px;
            border-radius: 8px 8px 0 0;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow: hidden;
           
        }
        
        .calendar-header h3 {
            font-size: 1.3rem;
            margin-top: 5px;
            
            
        }
        
        .calendar-header p, .calendar-header small {
            font-size: 0.9rem;
        }
        
        /* Navigation buttons mobile */
        .month-nav-btn {
            padding: 6px 10px;
            font-size: 0.85rem;
            
        }
        #month-butl{
          margin-top: 15px;
          position: absolute;
        }
        #month-butr{
            margin-top: -68px;
        }
        
        /* Calendar grid mobile optimization */
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
        
        /* Prevent horizontal scroll on mobile - Critical fixes */
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
        
        /* Modal adjustments for mobile */
        .modal-dialog {
            margin: 10px;
            max-width: calc(100% - 20px);
        }
        
        .appointment-modal .modal-body {
            max-height: 70vh;
            padding: 15px;
        }
        
        .appointment-card {
            margin-bottom: 10px;
        }
        
        .appointment-header {
            padding: 10px 12px;
        }
    }
</style>

<div class="container-fluid mt-3 mb-3">
    <h3 class="mb-3 col-5">My Schedule</h3>
    
    <!-- Filter Section -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <i class="bi bi-funnel me-2"></i>Filter Schedule
        </div>
        <div class="dashboard-card-body">
            <form method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="page" value="schedule">
                
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Month</label>
                    <select name="month" class="form-select" onchange="this.form.submit()">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $m == $currentMonth ? 'selected' : '' ?>>
                                <?= getMonthName($m) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Year</label>
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        <?php for ($y = date('Y') - 1; $y <= date('Y') + 2; $y++): ?>
                            <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary" onclick="location.href='?page=schedule&month=<?= date('n') ?>&year=<?= date('Y') ?>'">
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
                    $prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
                    $prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
                    ?>
                    <a href="?page=schedule&month=<?= $prevMonth ?>&year=<?= $prevYear ?>" 
                       class="btn month-nav-btn" id="month-butl">
                        <i class="bi bi-chevron-left"></i> Previous
                    </a>
                </div>
                <div class="col-md-4 text-center">
                    <h3 class="mb-0"><?= getMonthName($currentMonth) ?> <?= $currentYear ?></h3>
                    <small>My Appointment Schedule</small>
                </div>
                <div class="col-md-4 text-end">
                    <?php
                    $nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;
                    $nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;
                    ?>
                    <a href="?page=schedule&month=<?= $nextMonth ?>&year=<?= $nextYear ?>" 
                       class="btn month-nav-btn"id="month-butr">
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
            $prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
            $prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
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
                    echo '<div class="appointment-count">' . count($dayAppointments) . '</div>';
                    
                    foreach ($dayAppointments as $appointment) {
                        $time = date('g:i A', strtotime($appointment->app_schedule));
                        $statusClass = '';
                        
                        switch (strtolower($appointment->app_status_name)) {
                            case 'pending':
                                $statusClass = 'status-pending';
                                break;
                            case 'approved':
                                $statusClass = 'status-approved';
                                break;
                            case 'completed':
                                $statusClass = 'status-completed';
                                break;
                            case 'declined':
                            case 'cancelled':
                                $statusClass = 'status-declined';
                                break;
                            case 'in progress':
                                $statusClass = 'status-progress';
                                break;
                        }
                        
                        echo '<div class="appointment-item ' . $statusClass . '" data-bs-toggle="modal" data-bs-target="#appointmentModal" data-date="' . $currentDate . '">';
                        echo $time . ' - ' . $appointment->service_type_name;
                        echo '</div>';
                    }
                }
                
                echo '</div>';
            }
            
            // Next month's leading days
            $totalCells = ceil(($daysInMonth + $firstDayOfWeek) / 7) * 7;
            $remainingCells = $totalCells - ($daysInMonth + $firstDayOfWeek);
            
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
<div class="modal fade appointment-modal" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0056b3; color: white">
                <h5 class="modal-title" id="appointmentModalLabel">
                    <i class="bi bi-calendar3 me-2 text-white"></i>My Appointments for <span id="modalDate"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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

// Handle appointment modal
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
        if (e.target.classList.contains('appointment-item') && e.target.hasAttribute('data-bs-toggle')) {
            e.preventDefault();
            
            const date = e.target.getAttribute('data-date');
            
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
    
    function showAppointmentsForDate(date) {
        const appointments = appointmentsData[date] || [];
        
        const formattedDate = new Date(date).toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        modalDate.textContent = formattedDate;
        
        if (appointments.length === 0) {
            modalBody.innerHTML = '<p class="text-muted text-center">No appointments scheduled for this date.</p>';
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
                statusClass = 'bg-warning text-white'; // Pending - Yellow with white text
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
                    case 1: statusClass = 'bg-warning text-white'; break; // Pending - Yellow with white text
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
                                    <i class="bi bi-tools me-2 text-primary"></i>
                                    <span>${appointment.service_type_name}</span>
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