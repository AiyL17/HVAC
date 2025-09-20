<h3><?= ucfirst($_GET['page']) ?> </h3>

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

/* Dashboard Card Styling - matching administrator dashboard */
.dashboard-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.dashboard-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.dashboard-card-header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 15px 20px;
    font-weight: 600;
    border-bottom: none;
    font-size: 1rem;
}

.dashboard-card-body {
    padding: 20px;
}

/* Performance Card Styling */
.performance-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
}

.performance-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.clickable-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.clickable-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border-radius: 50%;
}

.hover-card:hover .stat-icon {
    transform: scale(1.1);
}

/* Workload Metrics */
.workload-metric {
    text-align: center;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.workload-metric-value {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 4px;
}

.workload-metric-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

/* Color classes matching administrator dashboard */
.text-primary { color: #0d6efd !important; }
.text-success { color: #198754 !important; }
.text-info { color: #0dcaf0 !important; }
.text-warning { color: #ffc107 !important; }

.bg-primary.bg-opacity-10 { background-color: rgba(13, 110, 253, 0.1) !important; }
.bg-success.bg-opacity-10 { background-color: rgba(25, 135, 84, 0.1) !important; }
.bg-info.bg-opacity-10 { background-color: rgba(13, 202, 240, 0.1) !important; }
.bg-warning.bg-opacity-10 { background-color: rgba(255, 193, 7, 0.1) !important; }

/* Appointment Cards */
.appointment-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.2s ease;
    overflow: hidden;
    margin-bottom: 16px;
}

.appointment-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #d1d5db;
}

.appointment-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-bottom: 1px solid #f3f4f6;
    background: #fafafa;
}

.appointment-service-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    font-size: 18px;
    flex-shrink: 0;
}

.appointment-header-content {
    flex: 1;
    min-width: 0;
}

.appointment-service-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 2px 0;
    line-height: 1.3;
}

.appointment-datetime-header {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 4px;
}

.appointment-card-body {
    padding: 16px 20px;
}

.appointment-detail-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
    font-size: 0.9rem;
}

.appointment-detail-row:last-child {
    margin-bottom: 0;
}

.appointment-detail-icon {
    width: 16px;
    height: 16px;
    color: #6b7280;
    flex-shrink: 0;
}

.appointment-customer-name {
    color: #374151;
    font-weight: 500;
}

.appointment-status-badge {
    position: absolute;
    top: 16px;
    right: 20px;
    font-size: 0.75rem;
    padding: 4px 8px;
    border-radius: 4px;
}

/* Timeline styling for activities */
.timeline-marker {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-top: 6px;
    position: relative;
}

.timeline-item:not(:last-child) .timeline-marker::after {
    content: '';
    position: absolute;
    left: 3px;
    top: 14px;
    width: 2px;
    height: 20px;
    background-color: #dee2e6;
}
</style>

<!-- Enhanced Statistics Cards -->
<div class="row mt-4 px-2">
    <div class="col-lg-3 col-md-6 col-6 p-1">
        <a href="index.php?page=task" class="text-decoration-none" title="View all tasks">
            <div class="dashboard-card h-100 hover-card clickable-card">
                <div class="dashboard-card-body text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar-check fs-4 text-primary"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 text-primary fw-bold" id="totalTasks">0</h2>
                            <div class="spinner-border spinner-border-sm text-primary d-none" role="status" id="totalLoader">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 text-muted fw-semibold">Total Tasks</h6>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> Active
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-lg-3 col-md-6 col-6 p-1">
        <a href="javascript:void(0)" onclick="filterTasksByStatus('pending')" class="text-decoration-none" title="View assigned tasks">
            <div class="dashboard-card h-100 hover-card clickable-card">
                <div class="dashboard-card-body text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 text-warning fw-bold" id="pendingTasks">0</h2>
                            <div class="spinner-border spinner-border-sm text-warning d-none" role="status" id="pendingLoader">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 text-muted fw-semibold">Assigned</h6>
                        <small class="text-info">
                            <i class="bi bi-clock"></i> Ready
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-lg-3 col-md-6 col-6 p-1">
        <a href="javascript:void(0)" onclick="filterTasksByStatus('inprogress')" class="text-decoration-none" title="View in-progress tasks">
            <div class="dashboard-card h-100 hover-card clickable-card">
                <div class="dashboard-card-body text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-gear-fill fs-4 text-info"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 text-info fw-bold" id="inProgressTasks">0</h2>
                            <div class="spinner-border spinner-border-sm text-info d-none" role="status" id="progressLoader">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 text-muted fw-semibold">In Progress</h6>
                        <small class="text-primary">
                            <i class="bi bi-play-fill"></i> Working
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-lg-3 col-md-6 col-6 p-1">
        <a href="javascript:void(0)" onclick="filterTasksByStatus('completed')" class="text-decoration-none" title="View completed tasks">
            <div class="dashboard-card h-100 hover-card clickable-card">
                <div class="dashboard-card-body text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-check-circle fs-4 text-success"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 text-success fw-bold" id="completedTasks">0</h2>
                            <div class="spinner-border spinner-border-sm text-success d-none" role="status" id="completedLoader">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 text-muted fw-semibold">Completed</h6>
                        <small class="text-success">
                            <i class="bi bi-check2"></i> Done
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>


<!-- Upcoming Appointments and Current Workload Row -->
<div class="row mt-4 px-2">
    <!-- Upcoming Appointments -->
    <div class="col-lg-8 p-1" style="margin-bottom: 15px;">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar-check me-2"></i>Upcoming Appointments
                </div>
            </div>
            <!-- Filter Row -->
            <div class="px-3 py-2 border-bottom">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Time Period</label>
                        <select class="form-select form-select-sm" id="timePeriodFilter" onchange="fetchUpcomingAppointments()">
                            <option value="">All Appointments</option>
                            <option value="today">Today Only</option>
                            <option value="week">This Week</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Month Filter</label>
                        <select class="form-select form-select-sm" id="monthFilter" onchange="fetchUpcomingAppointments()">
                            <option value="">All Months</option>
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
            <div class="dashboard-card-body">
                <div class="appointments-cards-container" style="max-height: 400px; overflow-y: auto; overflow-x: hidden;">
                    <div id="appointmentsContainer">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading appointments...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Workload Summary -->
    <div class="col-lg-4 p-1">
        <div class="dashboard-card mb-3">
            <div class="dashboard-card-header">
                <i class="bi bi-activity me-2"></i>Current Workload
            </div>
            <div class="dashboard-card-body">
                <div id="workloadContainer">
                    <div class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <i class="bi bi-lightning me-2"></i>Quick Actions
            </div>
            <div class="dashboard-card-body">
                <div class="d-grid gap-2">
                    <a href="index.php?page=task" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-list-task me-2"></i>View All Tasks
                    </a>
                    <a href="index.php?page=schedule" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-calendar-week me-2"></i>My Schedule
                    </a>
                    <button class="btn btn-outline-success btn-sm" onclick="refreshDashboard()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Refresh Data
                    </button>
                </div>
                <hr class="my-3">
                <div class="text-center">
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        Last updated: <span id="lastUpdated">Loading...</span>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // Function to fetch basic task counts first
    function fetchPerformanceStats() {
        // Fetch basic task counts using the working app_count.php API
        fetch('api/technician/app_count.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('API Response:', data); // Debug log
                
                if (data.success === false) {
                    console.error('API Error:', data.message);
                    return;
                }

                // Update basic stats with the working API data
                updateBasicStatsFromAppCount(data);
                
                // Basic stats loaded successfully
            })
            .catch(error => {
                console.error('Error fetching basic stats:', error);
                showErrorState();
            });
    }

    // Update basic statistics from app_count.php API
    function updateBasicStatsFromAppCount(data) {
        // app_count.php returns: task_count, pending_count, in_progress_count, completed_count
        document.getElementById('totalTasks').textContent = data.task_count || 0;
        document.getElementById('pendingTasks').textContent = data.pending_count || 0;
        document.getElementById('inProgressTasks').textContent = data.in_progress_count || 0;
        document.getElementById('completedTasks').textContent = data.completed_count || 0;
        
        console.log('Updated basic stats:', {
            total: data.task_count,
            pending: data.pending_count,
            inProgress: data.in_progress_count,
            completed: data.completed_count
        });
    }


    // Show error state when data fails to load
    function showErrorState() {
        document.getElementById('totalTasks').textContent = '—';
        document.getElementById('pendingTasks').textContent = '—';
        document.getElementById('inProgressTasks').textContent = '—';
        document.getElementById('completedTasks').textContent = '—';
    }

    // Call the function when the page loads
    document.addEventListener('DOMContentLoaded', fetchPerformanceStats);

    // Set up periodic refresh every 5 minutes
    setInterval(fetchPerformanceStats, 300000);

    // Function to fetch upcoming appointments (only approved appointments)
    function fetchUpcomingAppointments() {
        const timePeriodFilter = document.getElementById('timePeriodFilter').value;
        const monthFilter = document.getElementById('monthFilter').value;
        
        const params = new URLSearchParams();
        // Filter for both approved and in progress appointments (status = 1 and 5)
        params.append('status', '1,5');
        
        if (timePeriodFilter) {
            params.append('period', timePeriodFilter);
        }
        if (monthFilter) {
            params.append('month', monthFilter);
        }
        
        fetch(`api/technician/get_upcoming_appointments.php?${params}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayAppointments(data.appointments);
                } else {
                    showAppointmentsError(data.message || 'Failed to load appointments');
                }
            })
            .catch(error => {
                console.error('Error fetching appointments:', error);
                showAppointmentsError('Network error occurred');
            });
    }

    // Function to display appointments
    function displayAppointments(appointments) {
        const container = document.getElementById('appointmentsContainer');
        
        if (!appointments || appointments.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="bi bi-calendar-x fs-4 text-muted mb-3"></i>
                    <p class="text-muted">No upcoming appointments found</p>
                </div>
            `;
            return;
        }

        let html = '<div class="row g-3">';
        appointments.forEach(appointment => {
            const statusClass = getStatusClass(appointment.app_status_id);
            const statusText = getStatusText(appointment.app_status_id);
            const scheduleDate = new Date(appointment.app_schedule);
            const formattedDate = scheduleDate.toLocaleDateString('en-US', {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            const formattedTime = scheduleDate.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });

            html += `
                <div class="col-12">
                    <div class="appointment-card position-relative">
                        <div class="appointment-card-header">
                            <div class="appointment-service-icon">
                                <i class="bi bi-tools"></i>
                            </div>
                            <div class="appointment-header-content">
                                <h6 class="appointment-service-title">${appointment.service_type_name}</h6>
                                <p class="appointment-datetime-header">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    ${formattedDate} at ${formattedTime}
                                </p>
                            </div>
                        </div>
                        <div class="appointment-card-body">
                            <div class="appointment-detail-row">
                                <i class="bi bi-person appointment-detail-icon"></i>
                                <span class="appointment-customer-name">${appointment.customer_name}</span>
                            </div>
                            ${appointment.appliances_type_name ? `
                                <div class="appointment-detail-row">
                                    <i class="bi bi-gear appointment-detail-icon"></i>
                                    <span>${appointment.appliances_type_name}</span>
                                </div>
                            ` : ''}
                            ${appointment.app_desc ? `
                                <div class="appointment-detail-row">
                                    <i class="bi bi-info-circle appointment-detail-icon"></i>
                                    <span>${appointment.app_desc.length > 80 ? 
                                        appointment.app_desc.substring(0, 80) + '...' : 
                                        appointment.app_desc}</span>
                                </div>
                            ` : ''}
                            <div class="appointment-detail-row">
                                <i class="bi bi-people appointment-detail-icon"></i>
                                <span>${appointment.primary_technician_name}${appointment.secondary_technician_name ? ` & ${appointment.secondary_technician_name}` : ''}</span>
                            </div>
                            <div class="appointment-detail-row">
                                <i class="bi bi-info-circle appointment-detail-icon"></i>
                                <span>${getStatusText(appointment.app_status_id)}</span>
                            </div>
                            <div class="appointment-detail-row mt-3">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewAppointmentModal" 
                                    data-customer-name="${appointment.customer_name}"
                                    data-customer-address="${appointment.customer_address || ''}"
                                    data-date="${formattedDate}"
                                    data-time="${formattedTime}"
                                    data-service-type="${appointment.service_type_name}"
                                    data-appliances-type="${appointment.appliances_type_name || 'Not Specified'}"
                                    data-description="${appointment.app_desc || ''}"
                                    data-primary-technician="${appointment.primary_technician_name || ''}"
                                    data-secondary-technician="${appointment.secondary_technician_name || ''}"
                                    data-payment-status="${appointment.payment_status || 'Unpaid'}"
                                    data-price-min="${appointment.service_type_price_min || ''}"
                                    data-price-max="${appointment.service_type_price_max || ''}"
                                    data-status="${statusText}"
                                    data-app-id="${appointment.app_id}">
                                    <i class="bi bi-eye me-1"></i>View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    }

    // Initialize modal event handler after DOM is loaded
    function initializeModalHandler() {
        const modal = document.getElementById('viewAppointmentModal');
        if (modal) {
            // Hide background scrollbar when modal opens
            modal.addEventListener('show.bs.modal', function (event) {
                document.body.style.overflow = 'hidden';
                document.documentElement.style.overflow = 'hidden';
                const button = event.relatedTarget;
                
                // Extract data from button attributes
                const customerName = button.getAttribute('data-customer-name');
                const customerAddress = button.getAttribute('data-customer-address');
                const date = button.getAttribute('data-date');
                const time = button.getAttribute('data-time');
                const serviceType = button.getAttribute('data-service-type');
                const appliancesType = button.getAttribute('data-appliances-type');
                const description = button.getAttribute('data-description');
                const primaryTechnician = button.getAttribute('data-primary-technician');
                const secondaryTechnician = button.getAttribute('data-secondary-technician');
                const paymentStatus = button.getAttribute('data-payment-status');
                const priceMin = button.getAttribute('data-price-min');
                const priceMax = button.getAttribute('data-price-max');
                const status = button.getAttribute('data-status');
                const appId = button.getAttribute('data-app-id');
                
                // Populate modal with appointment data
                modal.querySelector('#modalCustomerName').textContent = customerName;
                modal.querySelector('#modalCustomerAddress').textContent = customerAddress || 'Not provided';
                modal.querySelector('#modalDate').textContent = date;
                modal.querySelector('#modalTime').textContent = time;
                modal.querySelector('#modalServiceType').textContent = serviceType;
                modal.querySelector('#modalAppliancesType').textContent = appliancesType;
                modal.querySelector('#modalDescription').textContent = description || 'No description provided';
                modal.querySelector('#modalStatus').textContent = status;
                modal.querySelector('#modalPaymentStatus').textContent = paymentStatus || 'Unpaid';
                
                // Handle primary technician (always show)
                modal.querySelector('#modalPrimaryTechnician').textContent = primaryTechnician || 'Not Assigned';
                
                // Show/hide secondary technician section
                const secondTechnicianSection = modal.querySelector('#secondTechnicianSection');
                if (secondaryTechnician && secondaryTechnician.trim() !== '') {
                    secondTechnicianSection.style.display = 'block';
                    modal.querySelector('#modalSecondaryTechnician').textContent = secondaryTechnician;
                } else {
                    secondTechnicianSection.style.display = 'none';
                }
                
                // Handle price range display
                const priceRangeSection = modal.querySelector('#priceRangeSection');
                const priceRangeElement = modal.querySelector('#modalPriceRange');
                if (priceMin && priceMax && parseFloat(priceMin) > 0 && parseFloat(priceMax) > 0) {
                    const minPrice = parseFloat(priceMin).toLocaleString('en-US', {minimumFractionDigits: 2});
                    const maxPrice = parseFloat(priceMax).toLocaleString('en-US', {minimumFractionDigits: 2});
                    priceRangeElement.textContent = `₱${minPrice} - ₱${maxPrice}`;
                    priceRangeSection.style.display = 'block';
                } else {
                    priceRangeSection.style.display = 'none';
                }
            });
            
            // Restore background scrollbar when modal closes
            modal.addEventListener('hidden.bs.modal', function (event) {
                document.body.style.overflow = '';
                document.documentElement.style.overflow = '';
            });
        }
    }

    // Function to show appointments error
    function showAppointmentsError(message) {
        const container = document.getElementById('appointmentsContainer');
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p class="text-muted">${message}</p>
                <button class="btn btn-sm btn-outline-primary" onclick="fetchUpcomingAppointments()">
                    <i class="fas fa-retry me-1"></i>Try Again
                </button>
            </div>
        `;
    }

    // Helper function to get status class
    function getStatusClass(statusId) {
        switch(parseInt(statusId)) {
            case 1: return 'border-success text-success'; // Approved
            case 2: return 'border-warning text-warning'; // Pending
            case 5: return 'border-info text-info'; // In Progress
            default: return 'border-secondary text-secondary';
        }
    }

    // Helper function to get status text
    function getStatusText(statusId) {
        switch(parseInt(statusId)) {
            case 1: return 'Approved';
            case 2: return 'Pending';
            case 5: return 'In Progress';
            default: return 'Unknown';
        }
    }

    // Function to refresh appointments
    function refreshAppointments() {
        fetchUpcomingAppointments();
    }


    // Function to fetch workload summary
    function fetchWorkloadSummary() {
        fetch('api/technician/get_workload_summary.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayWorkloadSummary(data.workload);
                } else {
                    showWorkloadError(data.message || 'Failed to load workload data');
                }
            })
            .catch(error => {
                console.error('Error fetching workload:', error);
                showWorkloadError('Network error occurred');
            });
    }

    // Function to display workload summary
    function displayWorkloadSummary(workload) {
        const container = document.getElementById('workloadContainer');
        
        container.innerHTML = `
            <div class="row text-center">
                <div class="col-4">
                    <div style="min-height: 60px; display: flex; flex-direction: column; justify-content: center;">
                        <h4 class="text-primary mb-1">${workload.today_tasks || 0}</h4>
                        <small class="text-muted" style="white-space: nowrap;">Today</small>
                    </div>
                </div>
                <div class="col-4">
                    <div style="min-height: 60px; display: flex; flex-direction: column; justify-content: center;">
                        <h4 class="text-warning mb-1">${workload.this_week || 0}</h4>
                        <small class="text-muted" style="white-space: nowrap;">This Week</small>
                    </div>
                </div>
                <div class="col-4">
                    <div style="min-height: 60px; display: flex; flex-direction: column; justify-content: center;">
                        <h4 class="text-info mb-1">${workload.next_week || 0}</h4>
                        <small class="text-muted" style="white-space: nowrap;">Next Week</small>
                    </div>
                </div>
            </div>
            <hr class="my-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">Current Status:</span>
                <span class="badge bg-success">
                    Active
                </span>
            </div>
        `;
    }

    // Function to show workload error
    function showWorkloadError(message) {
        document.getElementById('workloadContainer').innerHTML = `
            <div class="text-center py-2">
                <i class="fas fa-exclamation-circle text-warning"></i>
                <p class="text-muted small mb-0">${message}</p>
            </div>
        `;
    }

    // Function to fetch recent activity
    function fetchRecentActivity() {
        fetch('api/technician/get_recent_activity.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayRecentActivity(data.activities);
                } else {
                    showActivityError(data.message || 'Failed to load recent activity');
                }
            })
            .catch(error => {
                console.error('Error fetching recent activity:', error);
                showActivityError('Network error occurred');
            });
    }

    // Function to display recent activity
    function displayRecentActivity(activities) {
        // Recent activity section has been removed
        return;
        
        if (!activities || activities.length === 0) {
            container.innerHTML = `
                <div class="text-center py-3">
                    <i class="fas fa-history text-muted"></i>
                    <p class="text-muted small mb-0">No recent activity</p>
                </div>
            `;
            return;
        }

        let html = '<div class="timeline">';
        activities.forEach((activity, index) => {
            const timeAgo = getTimeAgo(activity.created_at);
            html += `
                <div class="timeline-item ${index < activities.length - 1 ? 'mb-3' : ''}">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="timeline-marker bg-primary"></div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-1 small">${activity.description}</p>
                            <small class="text-muted">${timeAgo}</small>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    }

    // Function to show activity error
    function showActivityError(message) {
        // Recent activity section has been removed
        return;
    }


    // Helper function to get time ago
    function getTimeAgo(dateString) {
        const now = new Date();
        const date = new Date(dateString);
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMins / 60);
        const diffDays = Math.floor(diffHours / 24);

        if (diffMins < 1) return 'Just now';
        if (diffMins < 60) return `${diffMins}m ago`;
        if (diffHours < 24) return `${diffHours}h ago`;
        if (diffDays < 7) return `${diffDays}d ago`;
        return date.toLocaleDateString();
    }


    // Function to refresh dashboard data
    function refreshDashboard() {
        // Show refresh feedback
        const refreshBtn = document.querySelector('button[onclick="refreshDashboard()"]');
        const originalText = refreshBtn.innerHTML;
        refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Refreshing...';
        refreshBtn.disabled = true;
        
        // Refresh all data
        fetchPerformanceStats();
        fetchUpcomingAppointments();
        fetchWorkloadSummary();
        
        // Update last updated time
        document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
        
        // Reset button after 2 seconds
        setTimeout(() => {
            refreshBtn.innerHTML = originalText;
            refreshBtn.disabled = false;
        }, 2000);
    }

    // Function to clear all filters
    function clearFilters() {
        document.getElementById('statusFilter').value = '';
        document.getElementById('timePeriodFilter').value = '';
        document.getElementById('monthFilter').value = '';
        fetchUpcomingAppointments();
    }

    // Function to filter tasks by status
    function filterTasksByStatus(status) {
        // Build URL with status filter
        let url = 'index.php?page=task';
        
        // Map dashboard status to task list filter values (matching task-list.php expectations)
        const statusMap = {
            'pending': '1',        // Approved/Pending technician action
            'inprogress': '5',     // In Progress
            'completed': '3,9'     // Completed (includes both status 3 and 9)
        };
        
        if (statusMap[status]) {
            url += '&status=' + statusMap[status];
        }
        
        // Navigate to filtered task list
        window.location.href = url;
    }

    // Load all dashboard data when page loads
    document.addEventListener('DOMContentLoaded', function() {
        fetchPerformanceStats();
        fetchUpcomingAppointments();
        fetchWorkloadSummary();
        // fetchRecentActivity(); // Removed - section no longer exists
        
        // Initialize modal handler
        initializeModalHandler();
        
        // Set initial last updated time
        document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
    });
</script>

<!-- View Appointment Modal -->
<div class="modal fade" id="viewAppointmentModal" tabindex="-1" aria-labelledby="viewAppointmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content round_lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="viewAppointmentModalLabel"><i class="bi bi-card-list me-2"></i>Appointment Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="text-muted small"><i class="bi bi-person me-2"></i>Customer Name</label>
                    <div id="modalCustomerName" class="fs-6"></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small"><i class="bi bi-geo-alt me-2"></i>Customer Address</label>
                    <div id="modalCustomerAddress" class="fs-6"></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small"><i class="bi bi-calendar-event me-2"></i>Date</label>
                    <div id="modalDate" class="fs-6"></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small"><i class="bi bi-tools me-2"></i>Service Type</label>
                    <div id="modalServiceType" class="fs-6"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="text-muted small"><i class="bi bi-credit-card me-2"></i>Payment Status</label>
                    <div id="modalPaymentStatus" class="fs-6"></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small"><i class="bi bi-clock me-2"></i>Time</label>
                    <div id="modalTime" class="fs-6"></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small"><i class="bi bi-box-seam me-2"></i>Appliance Type</label>
                    <div id="modalAppliancesType" class="fs-6"></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small"><i class="bi bi-check2-circle me-2"></i>Status</label>
                    <div id="modalStatus" class="fs-6"></div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="text-muted small"><i class="bi bi-person-badge me-2"></i>Primary Technician</label>
                    <div id="modalPrimaryTechnician" class="fs-6"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3" id="secondTechnicianSection" style="display: none;">
                    <label class="text-muted small"><i class="bi bi-person-plus me-2"></i>Secondary Technician</label>
                    <div id="modalSecondaryTechnician" class="fs-6"></div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mt-3">
            <h6 class="text-primary"><i class="bi bi-card-text me-2"></i>Description</h6>
            <p id="modalDescription" class="p-3 bg-light rounded border"></p>
        </div>
        <div class="mt-3" id="priceRangeSection" style="display: none;">
            <h6 class="text-primary"><i class="bi bi-currency-dollar me-2"></i>Expected Price Range</h6>
            <p id="modalPriceRange" class="p-3 bg-light rounded border fs-5"></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<style>
.timeline-marker {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-top: 6px;
}
.timeline-item:not(:last-child) .timeline-marker::after {
    content: '';
    position: absolute;
    left: 3px;
    top: 14px;
    width: 2px;
    height: 20px;
    background-color: #dee2e6;
}
.timeline-marker {
    position: relative;
}

/* Hide body scrollbar when modal is open */
body.modal-open {
    overflow: hidden !important;
}

/* Additional CSS to ensure scrollbar is completely hidden */
.modal-open {
    overflow: hidden !important;
}

.modal-open .main-content,
.modal-open body {
    overflow: hidden !important;
    position: fixed !important;
    width: 100% !important;
}

/* Custom modal scrollbar hiding */
.modal.show {
    overflow-y: auto !important;
}

.modal.show ~ * {
    overflow: hidden !important;
}
</style>