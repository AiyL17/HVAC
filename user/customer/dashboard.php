<?php
$customer_id = $_SESSION['uid'];
include_once __DIR__ . '/../../config/ini.php';
$pdo = pdo_init();

// Get appointment counts by status for the logged-in customer
$stmt = $pdo->prepare("SELECT app_status_id, COUNT(*) as count FROM appointment WHERE user_id = ? GROUP BY app_status_id");
$stmt->execute([$customer_id]);
$statusCounts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Initialize counts (default to 0 if no appointments)
$pendingCount = $statusCounts[2] ?? 0;    // Pending
$approvedCount = $statusCounts[1] ?? 0;   // Approved
$inProgressCount = $statusCounts[5] ?? 0; // In Progress
$completedCount = $statusCounts[3] ?? 0;  // Completed

// Last month appointments query removed - modal no longer needed
?>

<style>
    .service-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .service-card:hover {
        transform: translateY(-4px);
    }

    .service-icon {
        font-size: 2.5rem;
        margin-right: 0.5rem;
        color: #0d6efd;
    }


    .scroll-container::-webkit-scrollbar {
        display: none;
    }

    .scroll-container {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .appointment-item:hover {
        background-color: #f8f9fa;
    }

    .appointment-item:last-child {
        border-bottom: none !important;
    }

    .appointments-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .appointments-list::-webkit-scrollbar {
        width: 6px;
    }

    .appointments-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .appointments-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .appointments-list::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Simplified modal scroll prevention - let Bootstrap handle it */
    body.modal-open {
        overflow: hidden !important;
    }
    
    /* Smooth modal transitions */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }
    
    /* Card hover animation */
    .hover-card {
        transition: all 0.3s ease-in-out;
    }
    
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Card header hover effect */
    .card-header {
        transition: all 0.3s ease-in-out;
    }
    
    .card:hover .card-header {
        background: linear-gradient(135deg, #1a8cff, #0066cc) !important;
    }
    
    /* Dashboard consistent styling with admin interface */
    /* Mobile-specific spacing */
    @media (max-width: 991.98px) {
        /* Reduce spacing for Overview section on mobile */
        .mb-4.mb-md-3 {
            margin-bottom: 0.75rem !important;
        }
        
        /* Add more specific spacing between sections on mobile */
        #upcomingAppointmentsSection {
            margin-bottom: 3rem;
            padding-bottom: 1.5rem;
            margin-top: 1rem !important;
        }

        #recentActivitySection {
            margin-bottom: -4rem;
        }

        #pending, #approved{
            margin-bottom: -2rem;

        }

        #progress, #completed {
            margin-bottom: -1rem;
        }

        /* Ensure cards have proper spacing on mobile */
        .card {
            margin-bottom: 1.5rem;
        }
        
        /* Reduce gap between Overview cards on mobile */
        .g-md-4.g-3 {
            --bs-gutter-y: 0.75rem;
        }
    }
</style>

<!-- Dashboard Stats Cards -->
<h3 class="mb-4 mb-md-3">Overview</h3>
<div class="row g-4 g-md-3 mb-2">
    <!-- Pending Card -->
    <div class="col-xl-3 col-md-6 col-6" id="pending">
        <a href="index.php?page=appointment&type=2" class="text-decoration-none text-dark">
            <div class="card h-110 border-0 shadow-sm hover-card" style="height: 160px;">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="bg-secondary bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-hourglass-split text-secondary fs-2"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 fw-bold text-secondary"><?= $pendingCount ?></h2>
                            <span class="text-muted">Pending</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Approved Card -->
    <div class="col-xl-3 col-md-6 col-6" id="approved">
        <a href="index.php?page=appointment&type=1" class="text-decoration-none text-dark">
            <div class="card h-110 border-0 shadow-sm hover-card" style="height: 160px;">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="bg-info bg-opacity-25 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-check-circle text-primary fs-2"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 fw-bold text-primary fs-2""><?= $approvedCount ?></h2>
                            <span class="text-muted">Approved</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- In Progress Card -->
    <div class="col-xl-3 col-md-6 col-6" id="progress">
        <a href="index.php?page=appointment&type=5" class="text-decoration-none text-dark">
            <div class="card h-110 border-0 shadow-sm hover-card" style="height: 160px;">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-gear text-warning fs-2"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 fw-bold text-warning fs-2"><?= $inProgressCount ?></h2>
                            <span class="text-muted">In Progress</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Completed Card -->
    <div class="col-xl-3 col-md-6 col-6" id="completed">
        <a href="index.php?page=appointment&type=3" class="text-decoration-none text-dark">
            <div class="card h-110 border-0 shadow-sm hover-card" style="height: 160px;">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-check2-all text-success fs-2"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 fw-bold text-success fs-2"><?= $completedCount ?></h2>
                            <span class="text-muted">Completed</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<!-- Upcoming Appointments Section -->
<div class="row mt-4 mt-md-3" id="upcomingAppointmentsSection">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.08) !important;">
            <div class="card-header text-white" style="border-radius: 12px 12px 0 0; background: linear-gradient(135deg, #007bff, #0056b3);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-check me-2" style="font-size: 1.2rem;"></i>
                        <h5 class="mb-0">Upcoming Appointments</h5>
                    </div>
                    <span id="appointmentCountBadge" class="badge bg-white text-primary px-3 py-1" style="border-radius: 15px;">0</span>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Filter Controls in Card Body -->
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e9ecef;">
                    <div class="row g-3">
                        <div class="col-6 col-md-6">
                            <label class="form-label mb-2" style="font-weight: normal; color: #212529;">Time Period</label>
                            <select id="timePeriodFilter" class="form-select">
                                <option value="all" selected>All Appointments</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-6">
                            <label class="form-label mb-2" style="font-weight: normal; color: #212529;">Month Filter</label>
                            <select id="monthFilter" class="form-select">
                                <option value="all" selected>All Months</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Appointments Container -->
                <div id="upcomingAppointmentsContainer" style="height: 400px; overflow-y: auto;">
                    <!-- Appointments will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Dashboard Right Side -->
    <div class="col-lg-4" id="recentActivitySection">
        <!-- Recent Activity -->
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.08) !important;">
            <div class="card-header text-white" style="border-radius: 12px 12px 0 0; background: linear-gradient(135deg, #007bff, #0056b3);">
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock-history me-2" style="font-size: 1.1rem;"></i>
                    <h6 class="mb-0">Recent Activity</h6>
                </div>
            </div>
            <div class="card-body" style="overflow-y: auto; position: relative;">
                <div id="recentActivityContainer" style="height: 475px; overflow-y: auto; padding-bottom: 10px;">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted small">Loading recent activities...</p>
                    </div>
                </div>
                <div id="recentActivityButton" class="text-center" style="position: sticky; bottom: 0; background: white; padding: 10px 0; border-top: 1px solid #e9ecef; margin-top: 5px;">
                    <!-- Button will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Analytics Section -->
<div class="row mt-4">
</div>

<br>

<!-- Chart.js Library for Analytics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', async function () {

        // Modal for completed and paid appointments has been removed
        
        // Helper function to generate status badges
        function getStatusBadge(status) {
            const badges = {
                'Pending': '<span class="badge bg-warning text-dark">Pending</span>',
                'Approved': '<span class="badge bg-success">Approved</span>',
                'In Progress': '<span class="badge bg-info">In Progress</span>',
                'Completed': '<span class="badge bg-primary">Completed</span>',
                'Declined': '<span class="badge bg-danger">Declined</span>'
            };
            return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
        }
        
        // Function to handle appointment rebooking with pre-selection
        window.rebookAppointment = function(appointmentDataStr) {
            try {
                // Parse the appointment data
                const appointmentData = JSON.parse(appointmentDataStr.replace(/&quot;/g, '"'));
                
                // Construct URL with pre-selection parameters
                const params = new URLSearchParams({
                    page: 'appointment',
                    action: 'rebook',
                    id: appointmentData.app_id
                });
                
                // Add parameters only if they have valid values
                if (appointmentData.service_type_id && appointmentData.service_type_id !== 'null') {
                    params.set('service_type_id', appointmentData.service_type_id);
                }
                if (appointmentData.appliances_type_id && appointmentData.appliances_type_id !== 'null') {
                    params.set('appliances_type_id', appointmentData.appliances_type_id);
                }
                if (appointmentData.user_technician && appointmentData.user_technician !== 'null') {
                    params.set('user_technician', appointmentData.user_technician);
                }
                if (appointmentData.user_technician_2 && appointmentData.user_technician_2 !== 'null') {
                    params.set('user_technician_2', appointmentData.user_technician_2);
                }
                
                window.location.href = `?${params.toString()}`;
            } catch (error) {
                console.error('Error parsing appointment data for rebooking:', error);
                // Fallback to basic rebook without pre-selection
                window.location.href = '?page=appointment&action=rebook';
            }
        };

        const app_containers = document.querySelectorAll('.app-count-container');

        // Loop through each container
        for (const container of app_containers) {
            try {
                // Fetch the user count from the API
                const type = container.closest('a').getAttribute('href').split('type=')[1];
                const response = await fetch(`api/customer/app_count.php?type=${type}`);
                const data = await response.json();

                if (data.success) {
                    // Update the user count in the HTML
                    container.querySelector('.app-count').textContent = data.app_count;
                } else {
                    console.error(`Error fetching count`, data.message);
                }
            } catch (error) {
                console.error(`Error fetching count`, error);
            }
        }

        // Load upcoming appointments
        loadUpcomingAppointments();
        
        // Load recent activities
        loadRecentActivities();
        
        // Add event listeners for filter changes
        document.getElementById('timePeriodFilter').addEventListener('change', function() {
            // When time period changes, reset month to 'all' if not already
            if (document.getElementById('monthFilter').value !== 'all') {
                document.getElementById('monthFilter').value = 'all';
            }
            loadUpcomingAppointments();
        });
        
        document.getElementById('monthFilter').addEventListener('change', function() {
            // When month is selected, set time period to 'all' to show all appointments in that month
            if (this.value !== 'all' && document.getElementById('timePeriodFilter').value !== 'all') {
                document.getElementById('timePeriodFilter').value = 'all';
            }
            loadUpcomingAppointments();
        });
    });

    // Helper function to generate status badges
    function getStatusBadge(status) {
        // Return empty string for Approved status to hide it
        if (status === 'Approved') {
            return '';
        }
        
        const badges = {
            'Pending': '<span class="badge bg-warning text-dark">Pending</span>',
            'In Progress': '<span class="badge bg-info">In Progress</span>',
            'Completed': '<span class="badge bg-primary">Completed</span>',
            'Declined': '<span class="badge bg-danger">Declined</span>'
        };
        return badges[status] || '';
    }

    // Function to load upcoming appointments
    async function loadUpcomingAppointments() {
        try {
            // Show loading spinner
            document.getElementById('upcomingAppointmentsContainer').innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading appointments...</p>
                </div>
            `;

            // Get the selected time period and month
            const timePeriod = document.getElementById('timePeriodFilter').value;
            const selectedMonth = document.getElementById('monthFilter').value;
            
            // Check if we're showing all appointments (no filters)
            const isShowingAll = timePeriod === 'all' && selectedMonth === 'all';
            
            // Build query parameters
            const params = new URLSearchParams();
            if (timePeriod) params.append('timePeriod', timePeriod);
            if (selectedMonth) params.append('month', selectedMonth);
            
            // Add a flag to the API request to indicate we want all appointments when filters are set to 'all'
            const showAllFlag = isShowingAll ? '&show_all=1' : '';
            const response = await fetch(`api/customer/get_upcoming_appointments.php?period=${timePeriod}&month=${selectedMonth}${showAllFlag}`);
            
            // Check if response is ok
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Parse JSON response
            const data = await response.json();
            
            const container = document.getElementById('upcomingAppointmentsContainer');
            
            // Update the appointment count badge with the actual count from the response
            const countBadge = document.getElementById('appointmentCountBadge');
            if (countBadge) {
                // If we have a count in the response, use it, otherwise default to 0
                countBadge.textContent = data.count !== undefined ? data.count : '0';
            }
            
            if (data.success && data.appointments && data.appointments.length > 0) {
                // If we have appointments, render them and update the count
                renderUpcomingAppointments(data.appointments);
            } else {
                // Show no appointments message
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <h6 class="text-muted mt-3">No Upcoming Appointments</h6>
                        <p class="text-muted small mb-3">You don't have any upcoming appointments scheduled.</p>
                        <a href="?page=appointment&action=create" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Book New Appointment
                        </a>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading upcoming appointments:', error);
            
            // Set count to 0 on error
            const countBadge = document.getElementById('appointmentCountBadge');
            if (countBadge) {
                countBadge.textContent = '0';
            }
            
            document.getElementById('upcomingAppointmentsContainer').innerHTML = `
                <div class="text-center py-4">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                    <p class="text-muted mt-2 mb-0">Failed to load appointments. Please try again later.</p>
                    <small class="text-muted d-block mt-2">Error: ${error.message}</small>
                </div>
            `;
        }
    }

    // Function to render upcoming appointments
    function renderUpcomingAppointments(appointments) {
        // Update the appointment count badge
        const countBadge = document.getElementById('appointmentCountBadge');
        if (countBadge) {
            countBadge.textContent = appointments.length;
        }
        
        const container = document.getElementById('upcomingAppointmentsContainer');
        
        const appointmentsHtml = appointments.map(appointment => {
            const appointmentDate = new Date(appointment.app_schedule);
            const formattedDate = appointmentDate.toLocaleDateString('en-US', {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
            const formattedTime = appointmentDate.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            // Status badge
            const statusBadge = getStatusBadge(appointment.status_name);
            
            // Technician info
            let technicianInfo = '';
            if (appointment.primary_technician_name) {
                technicianInfo = appointment.primary_technician_name;
                if (appointment.secondary_technician_name) {
                    technicianInfo += ` & ${appointment.secondary_technician_name}`;
                }
            } else {
                technicianInfo = 'Not assigned yet';
            }

            // Price and payment status info
            let priceInfo = '';
            if (appointment.app_price && appointment.app_price.trim() !== '') {
                const paymentBadge = appointment.payment_status === 'Paid' 
                    ? '<span class="badge bg-success ms-2">Paid</span>' 
                    : '<span class="badge bg-warning text-dark ms-2">Unpaid</span>';
                priceInfo = `
                    <p class="text-muted small mb-1">
                        <i class="bi bi-currency-dollar me-1"></i>₱${appointment.app_price}${paymentBadge}
                    </p>
                `;
            }

            // Description preview (truncated)
            let descriptionPreview = '';
            if (appointment.app_desc && appointment.app_desc.trim() !== '') {
                const truncatedDesc = appointment.app_desc.length > 50 
                    ? appointment.app_desc.substring(0, 50) + '...' 
                    : appointment.app_desc;
                descriptionPreview = `
                    <p class="text-muted small mb-1" title="${appointment.app_desc}">
                        <i class="bi bi-file-text me-1"></i>${truncatedDesc}
                    </p>
                `;
            }

            return `
                <div class="border-bottom p-3 appointment-item position-relative" style="transition: background-color 0.2s;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="bi bi-tools text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="flex-grow-1 pe-5">
                                    <h6 class="mb-1 fw-bold">${appointment.service_type_name}</h6>
                                    <p class="text-muted small mb-1">
                                        <i class="bi bi-calendar3 me-1"></i>${formattedDate} at ${formattedTime}
                                    </p>
                                    <p class="text-muted small mb-1">
                                        <i class="bi bi-person-gear me-1"></i>${technicianInfo}
                                    </p>
                                    ${appointment.appliances_type_name ? `
                                        <p class="text-muted small mb-1">
                                            <i class="bi bi-gear me-1"></i>${appointment.appliances_type_name}
                                        </p>
                                    ` : ''}
                                    ${descriptionPreview}
                                    <p class="text-muted small mb-1">
                                        <i class="bi bi-info-circle me-1"></i>${appointment.status_name}
                                    </p>
                                    ${priceInfo}

                                    ${appointment.app_status_id == 1 || appointment.app_status_id == 5 ? `
                                        <div class="d-md-none mt-2">
                                            <button class="btn btn-sm text-white" style="background-color: #fd7e14; border-color: #fd7e14;" onclick="contactTechnician('${appointment.app_id}', '${appointment.primary_technician_contact || ''}', '${appointment.secondary_technician_contact || ''}', '${(appointment.primary_technician_name || '').replace(/'/g, "\\'")}', '${(appointment.secondary_technician_name || '').replace(/'/g, "\\'")}')">
                                                <i class="bi bi-telephone me-1"></i>Contact Technician
                                            </button>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mt-2 mt-md-0 text-start text-md-end">
                            <div class="d-none d-md-flex justify-content-md-end">
                                ${appointment.app_status_id == 1 || appointment.app_status_id == 5 ? `
                                    <button class="btn btn-sm text-white" style="background-color: #fd7e14; border-color: #fd7e14;" onclick="contactTechnician('${appointment.app_id}', '${appointment.primary_technician_contact || ''}', '${appointment.secondary_technician_contact || ''}', '${(appointment.primary_technician_name || '').replace(/'/g, "\\'")}', '${(appointment.secondary_technician_name || '').replace(/'/g, "\\'")}')">
                                        <i class="bi bi-telephone me-1"></i>Contact Technician
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = `
            <div class="appointments-list">
                ${appointmentsHtml}
            </div>
        `;
    }

    // Let Bootstrap handle modal scroll prevention automatically

    // Function to handle contact technician
    function contactTechnician(appointmentId, primaryContact = '', secondaryContact = '', primaryName = '', secondaryName = '') {
        let contactInfo = '';
        let hasPrimary = primaryContact && primaryContact.trim() !== '';
        let hasSecondary = secondaryContact && secondaryContact.trim() !== '';
        
        if (hasPrimary || hasSecondary) {
            contactInfo = `
                <div class="contact-list mb-3">
                    <h6 class="mb-3">Your Assigned Technicians:</h6>
                    <div class="d-flex flex-column gap-3">
                        ${hasPrimary ? `
                        <div class="d-flex">
                            <i class="bi bi-person-fill text-primary me-3" style="font-size: 1.5rem; margin-top: 3px;"></i>
                            <div>
                                <div class="fw-bold" style="text-align: left !important; margin-bottom: 0.3rem;">Primary Technician</div>
                                <div class="d-flex mt-1">
                                    <span class="me-2">${primaryName || 'Not assigned'}</span>
                                    <span class="text-muted mx-2">:</span>
                                    <a href="tel:${primaryContact}" class="text-decoration-none">
                                        ${primaryContact}
                                    </a>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                        
                        ${hasSecondary ? `
                        <div class="d-flex">
                            <i class="bi bi-person-fill text-secondary me-3" style="font-size: 1.5rem; margin-top: 3px;"></i>
                            <div>
                                <div class="fw-bold" style="text-align: left !important; margin-bottom: 0.3rem;">Secondary Technician</div>
                                <div class="d-flex mt-1">
                                    <span class="me-2">${secondaryName || 'Not assigned'}</span>
                                    <span class="text-muted mx-2">:</span>
                                    <a href="tel:${secondaryContact}" class="text-decoration-none">
                                        ${secondaryContact}
                                    </a>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                </div>
                <div class="alert alert-info">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                        <div>
                            <small class="d-block">You can call the technicians directly using the numbers above.</small>
                            <small class="d-block mt-2">For other inquiries, please contact us through 
                                <?php
                                // Get admin contact number
                                $admin_contact = '';
                                
                                // Query to get admin contact (user_type_id = 1)
                                $admin_query = $pdo->prepare("SELECT user_contact FROM user WHERE user_type_id = 1 AND user_contact != '' ORDER BY user_id ASC LIMIT 1");
                                $admin_query->execute();
                                $admin_row = $admin_query->fetch(PDO::FETCH_ASSOC);
                                if($admin_row) {
                                    $admin_contact = $admin_row['user_contact'];
                                    echo '<strong>' . htmlspecialchars($admin_contact) . '</strong>';
                                } else {
                                    echo 'our admin';
                                }
                                ?> 
                                during business hours (Mon-Fri, 8AM-5PM), or message us on our <a href="https://www.facebook.com/profile.php?id=100071430079338" target="_blank" class="text-primary">Facebook page</a> for assistance.</small>
                        </div>
                    </div>
                </div>
            `;
        } else {
            contactInfo = `
                <div class="alert alert-info">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                        <div>
                            <p class="mb-1"><strong>No direct technician contacts available</strong></p>
                            <p class="mb-1">Please contact our main office for assistance:</p>
                            <p class="mb-1"><strong>Phone:</strong> (123) 456-7890</p>
                            <p class="mb-0"><strong>Hours:</strong> Monday - Friday, 8:00 AM - 5:00 PM</p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        showDialog({
            message: `
                <div class="">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-telephone text-primary" style="font-size: 2rem;"></i>
                        <h5 class="mb-0 ms-2">Contact Technician</h5>
                    </div>
                    <p class="text-muted mb-3">Get in touch with your assigned technician for appointment #${appointmentId}</p>
                    ${contactInfo}
                </div>
            `,
            confirmText: 'Got it',
            showCancel: false
        });
    }
    
    // Function to load recent activities
    async function loadRecentActivities() {
        try {
            const response = await fetch('/HVAC/api/customer/get_recent_activities.php');
            const data = await response.json();
            
            const container = document.getElementById('recentActivityContainer');
            
            console.log('API Response:', data); // Debug log
            
            if (data.success && data.activities && data.activities.length > 0) {
                let html = '';
                
                // Show only first 6 activities initially
                const activitiesToShow = data.activities.slice(0, 6);
                
                activitiesToShow.forEach(activity => {
                    const activityDate = new Date(activity.activity_timestamp);
                    const formattedDate = activityDate.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                    const formattedTime = activityDate.toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                    
                    html += `
                        <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                            <div class="me-3">
                                <i class="bi ${activity.activity_icon} ${activity.activity_color}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small">${activity.activity_description}</div>
                                <small class="text-muted">
                                    ${formattedDate} at ${formattedTime}
                                </small>
                            </div>
                        </div>
                    `;
                });
                
                // Don't add button here anymore - it will be in the sticky footer
                
                container.innerHTML = html;
                
                // Show initial button if there are more than 6 activities
                if (data.activities.length > 6) {
                    document.getElementById('recentActivityButton').innerHTML = `
                        <button class="btn btn-sm btn-outline-primary" onclick="loadMoreRecentActivities()" id="viewAllBtn">
                            <i class="bi bi-eye me-1"></i>View All Activities
                        </button>
                    `;
                }
            } else {
                container.innerHTML = `
                    <div class="text-center py-3">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted small mb-0 mt-2">No recent activity</p>
                    </div>
                `;
                // Hide button when no activities
                document.getElementById('recentActivityButton').innerHTML = '';
            }
        } catch (error) {
            console.error('Error loading recent activities:', error);
            document.getElementById('recentActivityContainer').innerHTML = `
                <div class="text-center py-3">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                    <p class="text-muted small mb-0 mt-2">Failed to load activities</p>
                </div>
            `;
        }
    }

    // Function to load more recent activities inline
    let currentActivityCount = 6;
    let allActivitiesData = [];
    
    function loadMoreRecentActivities() {
        const container = document.getElementById('recentActivityContainer');
        
        if (allActivitiesData.length === 0) {
            // First time - fetch all data
            fetch('/HVAC/api/customer/get_recent_activities.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.activities && data.activities.length > 0) {
                        allActivitiesData = data.activities;
                        currentActivityCount = Math.min(currentActivityCount + 5, allActivitiesData.length);
                        renderActivities();
                    }
                })
                .catch(error => {
                    console.error('Error loading all activities:', error);
                });
        } else {
            // Expand to show 5 more activities
            currentActivityCount = Math.min(currentActivityCount + 5, allActivitiesData.length);
            renderActivities();
        }
    }
    
    function renderActivities() {
        const container = document.getElementById('recentActivityContainer');
        let html = '';
        
        const activitiesToShow = allActivitiesData.slice(0, currentActivityCount);
        
        activitiesToShow.forEach(activity => {
            const activityDate = new Date(activity.activity_timestamp);
            const formattedDate = activityDate.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
            const formattedTime = activityDate.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
            
            html += `
                <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                    <div class="me-3">
                        <i class="bi ${activity.activity_icon} ${activity.activity_color}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold small">${activity.activity_description}</div>
                        <small class="text-muted">
                            ${formattedDate} at ${formattedTime}
                        </small>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
        updateActivityButton();
    }
    
    function showLessActivities() {
        currentActivityCount = 6;
        renderActivities();
    }
    
    function updateActivityButton() {
        const buttonContainer = document.getElementById('recentActivityButton');
        
        if (!buttonContainer) {
            console.error('Button container not found');
            return;
        }
        
        // Show appropriate button based on current state
        if (allActivitiesData.length > 6 && currentActivityCount < allActivitiesData.length) {
            // Show "View More" if there are more activities
            buttonContainer.innerHTML = `
                <button class="btn btn-sm btn-outline-primary" onclick="loadMoreRecentActivities()" id="viewAllBtn">
                    <i class="bi bi-eye me-1"></i>View More Activities
                </button>
            `;
        } else if (currentActivityCount > 6) {
            // Show "Show Less" if showing more than 6 activities
            buttonContainer.innerHTML = `
                <button class="btn btn-sm btn-outline-secondary" onclick="showLessActivities()" id="viewAllBtn">
                    <i class="bi bi-chevron-up me-1"></i>Show Less
                </button>
            `;
        } else {
            // Hide button if showing exactly 6 activities and no more available
            buttonContainer.innerHTML = '';
        }
    }
    </script>
