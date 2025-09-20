<style>
    /* Dashboard consistent styling with admin interface */
    .chart-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        height: 400px;
        border: 1px solid #dee2e6;
    }

    .filter-container {
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
        align-items: center;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-label {
        font-weight: 500;
        margin-bottom: 0;
    }

    /* Dashboard section cards matching admin interface */
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
        padding: 15px 20px;
        border-radius: 12px 12px 0 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .dashboard-card-body {
        padding: 20px;
    }

    /* Workload metrics styling */
    .workload-metric {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }

    .workload-metric:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .workload-metric-value {
        font-size: 2rem;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 5px;
    }

    .workload-metric-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }

    /* Performance metrics */
    .performance-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        border: 1px solid #e9ecef;
        margin-bottom: 15px;
    }

    .performance-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #28a745;
    }

    /* Financial overview styling */
    .financial-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 10px;
        border: 1px solid #e9ecef;
    }

    /* Activity feed styling */
    .activity-item {
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-badge {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 10px;
    }

    .activity-badge.new { background: #007bff; }
    .activity-badge.approved { background: #28a745; }
    .activity-badge.completed { background: #17a2b8; }
    .activity-badge.cancelled { background: #dc3545; }

    /* Table styling consistent with admin tables */
    .dashboard-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .dashboard-table thead {
        background: #007bff;
        color: white;
    }

    .dashboard-table th {
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        padding: 12px 15px;
        border: none;
    }

    .dashboard-table td {
        padding: 12px 15px;
        border-color: #e9ecef;
    }

    /* Filter styling */
    .dashboard-filters {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }

    .dashboard-filters .form-select {
        border-color: #ced4da;
        border-radius: 6px;
    }

    .dashboard-filters .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Enhanced Statistics Cards */
    .hover-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    /* Professional Appointment Cards Styling */
    .appointment-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 0;
        margin-bottom: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
        height: 100%;
        overflow: none;
    }
    
    .appointment-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(-1px);
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
    
    .appointment-technician-name {
        color: #374151;
    }
    
    .appointment-technician-secondary {
        color: #6b7280;
        font-size: 0.85rem;
        margin-left: 4px;
        display: inline;
    }
    
    .appointment-service-type {
        background: #eff6ff;
        color: #1d4ed8;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .appointment-status-badge {
        position: absolute;
        top: 16px;
        right: 20px;
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 500;
    }
    
    .appointment-card-header {
        position: relative;
    }
    
    .appointment-contact-btn {
        background: #f97316;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .appointment-contact-btn:hover {
        background: #ea580c;
        color: white;
        text-decoration: none;
    }
    
    /* Specific styling for appointments cards container to prevent horizontal scroll */
    .appointments-cards-container .row {
        margin-left: 0;
        margin-right: 0;
    }
    
    .appointments-cards-container .row > * {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    /* Mobile Upcoming Appointments Styling */
    .mobile-appointment-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .mobile-appointment-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        transform: translateY(-1px);
    }
    
    .mobile-appointment-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    
    .mobile-appointment-customer {
        font-weight: 600;
        font-size: 1.1rem;
        color: #2c3e50;
        margin: 0;
        line-height: 1.3;
    }
    
    .mobile-appointment-status {
        flex-shrink: 0;
        margin-left: 12px;
    }
    
    .mobile-appointment-details {
        margin-bottom: 12px;
    }
    
    .mobile-appointment-detail {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
    }
    
    .mobile-appointment-detail i {
        width: 16px;
        margin-right: 8px;
        color: #007bff;
    }
    
    .mobile-appointment-service {
        color: #495057;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    /* Mobile Filter Improvements for Dashboard */
    @media (max-width: 768px) {
        .dashboard-filters {
            padding: 12px;
        }
        
        .dashboard-filters .row {
            gap: 8px;
        }
        
        .dashboard-filters .form-label {
            font-size: 0.9rem;
            margin-bottom: 4px !important;
        }
        
        .dashboard-filters .form-select {
            font-size: 0.9rem;
        }
        
        /* Hide desktop table on mobile */
        .desktop-appointments-table {
            display: none;
        }
        
        /* Show mobile cards only on mobile */
        .mobile-appointments-container {
            display: block;
        }
        
        .dashboard-card-body {
            padding: 16px;
        }
        
        .dashboard-card-header {
            padding: 12px 16px;
            font-size: 1rem;
        }
    }
    
    /* Desktop - hide mobile cards */
    @media (min-width: 769px) {
        .mobile-appointments-container {
            display: none;
        }
        
        .desktop-appointments-table {
            display: block;
        }
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .hover-card:hover .stat-icon {
        transform: scale(1.1);
    }

    .dashboard-card .text-primary { color: #0d6efd !important; }
    .dashboard-card .text-success { color: #198754 !important; }
    .dashboard-card .text-info { color: #0dcaf0 !important; }
    .dashboard-card .text-warning { color: #ffc107 !important; }

    .bg-primary.bg-opacity-10 { background-color: rgba(13, 110, 253, 0.1) !important; }
    .bg-success.bg-opacity-10 { background-color: rgba(25, 135, 84, 0.1) !important; }
    .bg-info.bg-opacity-10 { background-color: rgba(13, 202, 240, 0.1) !important; }
    .bg-warning.bg-opacity-10 { background-color: rgba(255, 193, 7, 0.1) !important; }
</style>
<h3><?= ucfirst($_GET['page']) ?></h3>


<!-- Enhanced Statistics Cards -->
<div class="row mt-4 px-2">
    <div class="col-lg-3 col-md-6 col-6 p-1">
        <a class="text-decoration-none" href="index.php?page=appointment">
            <div class="dashboard-card h-100 hover-card app-count-container">
                <div class="dashboard-card-body text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar-check fs-4 text-primary"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 text-primary fw-bold app-count">0</h2>
                            <div class="spinner-border spinner-border-sm text-primary d-none" role="status" id="appointmentLoader">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 text-muted fw-semibold">Total Appointments</h6>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> Active
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-lg-3 col-md-6 col-6 p-1">
        <a class="text-decoration-none" href="index.php?page=user&type=4">
            <div class="dashboard-card h-100 hover-card user-count-container" data-user-type="customer">
                <div class="dashboard-card-body text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-people fs-4 text-success"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 text-success fw-bold user-count">0</h2>
                            <div class="spinner-border spinner-border-sm text-success d-none" role="status" id="customerLoader">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 text-muted fw-semibold">Total Customers</h6>
                        <small class="text-info">
                            <i class="bi bi-person-plus"></i> Registered
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-lg-3 col-md-6 col-6 p-1">
        <a class="text-decoration-none" href="index.php?page=user&type=3">
            <div class="dashboard-card h-100 hover-card user-count-container" data-user-type="staff">
                <div class="dashboard-card-body text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-person-badge fs-4 text-info"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 text-info fw-bold user-count">0</h2>
                            <div class="spinner-border spinner-border-sm text-info d-none" role="status" id="staffLoader">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 text-muted fw-semibold">Staff Members</h6>
                        <small class="text-primary">
                            <i class="bi bi-shield-check"></i> Active
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-lg-3 col-md-6 col-6 p-1">
        <a class="text-decoration-none" href="index.php?page=user&type=2">
            <div class="dashboard-card h-100 hover-card user-count-container" data-user-type="technician">
                <div class="dashboard-card-body text-center">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-tools fs-4 text-warning"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 text-warning fw-bold user-count">0</h2>
                            <div class="spinner-border spinner-border-sm text-warning d-none" role="status" id="technicianLoader">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 text-muted fw-semibold">Technicians</h6>
                        <small class="text-success">
                            <i class="bi bi-wrench"></i> Available
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>



</div>

<!-- Enhanced Admin Dashboard Sections -->
<div class="row mt-4 px-2">
    <!-- Upcoming Appointments Table -->
    <div class="col-lg-8 p-1">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-calendar-check me-2"></i>Upcoming Appointments</span>
                    <span class="badge bg-white text-primary px-3 py-1" id="upcomingCount" style="border-radius: 15px;">0</span>
                </div>
            </div>
            
            <div class="dashboard-card-body">
                <!-- Enhanced Filter Section -->
                <div class="dashboard-filters">
                    <div class="row g-3">
                        <div class="col-md-6">
                        <label class="form-label mb-2" style="font-weight: normal; color: #212529;">Time Period</label>
                        <select class="form-select" id="periodFilter" onchange="updateUpcomingAppointments()">
                                <option value="all">All Appointments</option>
                                <option value="today">Today Only</option>
                                <option value="this_week">This Week</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                        <label class="form-label mb-2" style="font-weight: normal; color: #212529;">Month Filter</label>
                        <select class="form-select" id="monthFilter" onchange="updateUpcomingAppointments()">
                                <option value="all_month">All Months</option>
                                <option value="january">January</option>
                                <option value="february">February</option>
                                <option value="march">March</option>
                                <option value="april">April</option>
                                <option value="may">May</option>
                                <option value="june">June</option>
                                <option value="july">July</option>
                                <option value="august">August</option>
                                <option value="september">September</option>
                                <option value="october">October</option>
                                <option value="november">November</option>
                                <option value="december">December</option>
                            </select>
                        </div>
                    </div>
                </div>
            
                <!-- Card Layout View -->
                <div class="appointments-cards-container" style="max-height: 400px; overflow-y: auto; overflow-x: hidden;">
                    <div id="upcomingAppointmentsCards" class="row g-3">
                        <div class="col-12">
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x fs-4"></i><br>
                                <small>Loading upcoming appointments...</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Workload + Technician Performance -->
    <div class="col-lg-4 p-1">
        <!-- Current Workload Summary -->
        <div class="dashboard-card mb-3">
            <div class="dashboard-card-header">
                <i class="bi bi-activity me-2"></i>Current Workload
            </div>
            <div class="dashboard-card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="workload-metric">
                            <div class="workload-metric-value text-warning" id="ongoingJobs">0</div>
                            <div class="workload-metric-label">
                                <i class="bi bi-gear-fill me-1"></i>In Progress
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="workload-metric">
                            <div class="workload-metric-value text-info" id="pendingJobs">0</div>
                            <div class="workload-metric-label">
                                <i class="bi bi-hourglass-split me-1"></i>Pending
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technician Performance Section -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <i class="bi bi-trophy me-2"></i>Performance Metrics
            </div>
            <div class="dashboard-card-body">
                <!-- Performance Stats -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="performance-card text-center">
                            <div class="performance-value text-success" id="jobsCompletedToday">0</div>
                            <small class="text-muted">
                                <i class="bi bi-calendar-day me-1"></i>Today
                            </small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="performance-card text-center">
                            <div class="performance-value text-primary" id="jobsCompletedWeek">0</div>
                            <small class="text-muted">
                                <i class="bi bi-calendar-week me-1"></i>This Week
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Top Performer Card -->
                <div id="topPerformerToday" class="performance-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <small class="text-muted fw-semibold">🏆 Top Performer Today</small>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <div class="text-center">
                        <i class="bi bi-person-circle fs-2 text-primary mb-2"></i>
                        <p class="mb-0 fw-semibold">Loading...</p>
                        <small class="text-muted">Champion of the day</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Feed + Financial Overview -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>




<script>
    let myChart;
    let chartData = {
        labels: [],
        data: []
    };

    // Function to update visible filters based on time period selection
    const updateFilters = () => {
        const timePeriod = document.getElementById('timeFilter').value;
        const weeklyFilters = document.getElementById('weeklyFilters');
        const monthlyFilters = document.getElementById('monthlyFilters');

        if (timePeriod === 'weekly') {
            weeklyFilters.style.display = 'flex';
            monthlyFilters.style.display = 'none';
        } else {
            weeklyFilters.style.display = 'none';
            monthlyFilters.style.display = 'flex';
        }

        fetchChartData();
    };

    // Initialize year and month filters
    const initFilters = () => {
        const currentYear = moment().year();
        const yearFilterWeekly = document.getElementById('yearFilterWeekly');
        const yearFilterMonthly = document.getElementById('yearFilterMonthly');

        // Clear existing options
        yearFilterWeekly.innerHTML = '';
        yearFilterMonthly.innerHTML = '';

        // Add options for the last 3 years
        for (let year = currentYear; year >= currentYear - 2; year--) {
            const optionWeekly = document.createElement('option');
            optionWeekly.value = year;
            optionWeekly.textContent = year;
            yearFilterWeekly.appendChild(optionWeekly);

            const optionMonthly = document.createElement('option');
            optionMonthly.value = year;
            optionMonthly.textContent = year;
            yearFilterMonthly.appendChild(optionMonthly);
        }

        updateMonthOptions();
    };

    // Update month options based on selected year
    const updateMonthOptions = () => {
        const monthFilter = document.getElementById('monthFilter');
        monthFilter.innerHTML = '';

        const months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        months.forEach((month, index) => {
            const option = document.createElement('option');
            option.value = index;
            option.textContent = month;
            monthFilter.appendChild(option);
        });

        // Set current month as default
        monthFilter.value = moment().month();
    };

    // Fetch data from API based on selected filters
    const fetchChartData = async () => {
        const timePeriod = document.getElementById('timeFilter').value;
        let url = 'api/administrator/app_data.php?period=' + timePeriod;

        if (timePeriod === 'weekly') {
            const selectedYear = document.getElementById('yearFilterWeekly').value;
            const selectedMonth = document.getElementById('monthFilter').value;
            url += '&year=' + selectedYear + '&month=' + selectedMonth;
        } else { // monthly
            const selectedYear = document.getElementById('yearFilterMonthly').value;
            url += '&year=' + selectedYear;
        }

        try {
            const response = await fetch(url);
            const data = await response.json();

            if (data.success) {
                chartData.labels = data.labels;
                chartData.data = data.data;
                updateChart();
            } else {
                console.error('Error fetching chart data:', data.message);
            }
        } catch (error) {
            console.error('Error fetching chart data:', error);
        }
    };

    // Initialize and update chart
    const initChart = () => {
        const ctx = document.getElementById('myChart').getContext('2d');

        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Appointment Data',
                    data: chartData.data,
                    backgroundColor: '#2c6bd0',
                    borderColor: 'rgba(54, 162, 235, 0)',
                    borderWidth: 1,
                    borderRadius: 10, // Rounded corners for bars
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,  // Animation duration in ms for initial load
                    easing: 'easeOutQuart'  // Smoother animation easing
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `Appointments: ${context.raw}`;
                            }
                        }
                    },
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Appointment Data'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Appointments'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            // text: 'Date'
                        }
                    }
                }
            }
        });
    };

    // Update chart when filter changes
    const updateChart = () => {
        if (!myChart) {
            initChart();
            return;
        }
        // Update chart data
        myChart.data.labels = chartData.labels;
        myChart.data.datasets[0].data = chartData.data;

        // Update title based on selected filters
        const timePeriod = document.getElementById('timeFilter').value;
        if (timePeriod === 'weekly') {
            const monthName = document.getElementById('monthFilter').options[document.getElementById('monthFilter').selectedIndex].text;
            const year = document.getElementById('yearFilterWeekly').value;
            myChart.options.plugins.title = {
                display: true,
                text: `Weekly Appointment - ${monthName} ${year}`
            };
        } else {
            const year = document.getElementById('yearFilterMonthly').value;
            myChart.options.plugins.title = {
                display: true,
                text: `Monthly Appointment - ${year}`
            };
        }

        myChart.update();
    };


    document.addEventListener('DOMContentLoaded', function () {
        // Function to fetch and update user counts
        window.updateUserCounts = async function() {
            const containers = document.querySelectorAll('.user-count-container');
            for (const container of containers) {
                const userType = container.getAttribute('data-user-type');
                try {
                    const response = await fetch(`api/user_count.php?user_type=${userType}`);
                    const data = await response.json();
                    if (data.success) {
                        container.querySelector('.user-count').textContent = data.user_count;
                    } else {
                        console.error(`Error fetching count for ${userType}:`, data.message);
                    }
                } catch (error) {
                    console.error(`Error fetching count for ${userType}:`, error);
                }
            }
        }

        // Function to fetch and update app counts
        async function updateAppCounts() {
            const containers = document.querySelectorAll('.app-count-container');
            for (const container of containers) {
                try {
                    const response = await fetch(`api/administrator/app_count.php`);
                    const data = await response.json();
                    if (data.success) {
                        container.querySelector('.app-count').textContent = data.app_count;
                    } else {
                        console.error(`Error fetching app count`, data.message);
                    }
                } catch (error) {
                    console.error(`Error fetching app count`, error);
                }
            }
        }

        // Function to fetch and update upcoming appointments
        window.updateUpcomingAppointments = async function() {
            try {
                // Get filter values
                const periodFilter = document.getElementById('periodFilter')?.value || 'all';
                const monthFilter = document.getElementById('monthFilter')?.value || 'all_month';
                
                // Debug logging
                console.log('Filter values:', { period: periodFilter, month: monthFilter });
                
                // Build query parameters
                const params = new URLSearchParams({
                    period: periodFilter,
                    month: monthFilter
                });
                
                console.log('API URL:', `api/administrator/upcoming_appointments.php?${params}`);
                
                const response = await fetch(`api/administrator/upcoming_appointments.php?${params}`);
                const data = await response.json();
                if (data.success) {
                    // Store appointments data globally for modal access
                    window.currentAppointments = data.appointments;
                    
                    document.getElementById('upcomingCount').textContent = data.appointments.length;
                    const cardsContainer = document.getElementById('upcomingAppointmentsCards');
                    
                    if (data.appointments && data.appointments.length > 0) {
                        // Populate appointment cards
                        cardsContainer.innerHTML = data.appointments.map(app => {
                            let technicianDisplay = app.technician_name || 'Not Assigned';
                            let technicianSecondary = '';
                            if (app.technician_2_name) {
                                technicianSecondary = ` & ${app.technician_2_name}`;
                            }
                            
                            return `
                            <div class="col-12">
                                <div class="appointment-card">
                                    <div class="appointment-card-header">
                                        <div class="appointment-service-icon">
                                            <i class="bi bi-tools"></i>
                                        </div>
                                        <div class="appointment-header-content">
                                            <h6 class="appointment-service-title">${app.service_type}</h6>
                                            <p class="appointment-datetime-header">
                                                <i class="bi bi-calendar3"></i>
                                                ${app.date} at ${app.time}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="appointment-card-body">
                                        <div class="appointment-detail-row">
                                            <i class="bi bi-person appointment-detail-icon"></i>
                                            <span class="appointment-customer-name">${app.customer_name}</span>
                                        </div>
                                        
                
                                        
                                        <div class="appointment-detail-row">
                                            <i class="bi bi-person-gear appointment-detail-icon"></i>
                                            <span class="appointment-technician-name">${technicianDisplay}<span class="appointment-technician-secondary">${technicianSecondary}</span></span>
                                        </div>
                                        
                                        <div class="appointment-detail-row mt-3">
                                             <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewAppointmentModal" 
                                                 data-customer-name="${app.customer_name}"
                                                 data-date="${app.full_date}"
                                                 data-time="${app.full_time}"
                                                 data-address="${app.customer_address}"
                                                 data-contact="${app.customer_contact}"
                                                 data-service-type="${app.service_type}"
                                                 data-appliances-type="${app.appliances_type || 'Not Specified'}"
                                                 data-description="${app.description || ''}"
                                                 data-technician="${app.technician_name}"
                                                 data-technician2="${app.technician_2_name || ''}"
                                                 data-status="${app.status || ''}"
                                                 data-payment-status="${app.payment_status}"
                                                 data-price-min="${app.service_type_price_min || ''}"
                                                 data-price-max="${app.service_type_price_max || ''}">
                                                 <i class="bi bi-eye me-1"></i>View Details
                                             </button>
                                         </div>
                                    </div>
                                </div>
                            </div>
                            `;
                        }).join('');
                    } else {
                        // No appointments - show empty state
                        cardsContainer.innerHTML = `
                            <div class="col-12">
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-calendar-check fs-4"></i><br>
                                    <small>No upcoming appointments</small>
                                </div>
                            </div>
                        `;
                    }
                }
            } catch (error) {
                console.error('Error fetching upcoming appointments:', error);
            }
        }

        // Function to fetch workload summary
        window.updateWorkloadSummary = async function() {
            try {
                const response = await fetch(`api/administrator/workload_summary.php`);
                const data = await response.json();
                if (data.success) {
                    document.getElementById('ongoingJobs').textContent = data.ongoing || 0;
                    document.getElementById('pendingJobs').textContent = data.pending || 0;
                }
            } catch (error) {
                console.error('Error fetching workload summary:', error);
            }
        }

        // Function to fetch technician performance
        window.updateTechnicianPerformance = async function() {
            try {
                const response = await fetch(`api/administrator/technician_performance.php`);
                const data = await response.json();
                if (data.success) {
                    document.getElementById('jobsCompletedToday').textContent = data.jobs_today || 0;
                    document.getElementById('jobsCompletedWeek').textContent = data.jobs_week || 0;
                    
                    const topPerformerDiv = document.getElementById('topPerformerToday');
                    if (data.top_performer) {
                        topPerformerDiv.innerHTML = `
                            <small class="text-muted">Top Performer Today</small>
                            <div class="text-center py-2">
                                <i class="bi bi-person-check-fill fs-4 text-success"></i>
                                <p class="mb-0 small"><strong>${data.top_performer.name}</strong></p>
                                <small class="text-muted">${data.top_performer.jobs_completed} jobs completed</small>
                            </div>
                        `;
                    }
                }
            } catch (error) {
                console.error('Error fetching technician performance:', error);
            }
        }

        // Initialize existing functions
        updateUserCounts();
        updateAppCounts();
        updateUpcomingAppointments();
        updateWorkloadSummary();
        updateTechnicianPerformance();
        
        // Function to view appointment details
        window.viewAppointmentDetails = function(appointmentId) {
            // Redirect to appointment list page with the appointment ID as a parameter
            // This will allow the appointment-list.php page to automatically open the modal
            window.location.href = `index.php?page=appointment&view=${appointmentId}`;
        }

        // Bootstrap modal event handler for appointment details
        document.getElementById('viewAppointmentModal').addEventListener('show.bs.modal', function (event) {
            // Hide all background scrollbars
            document.documentElement.style.overflow = 'hidden';
            document.body.style.overflow = 'hidden';
            
            var button = event.relatedTarget;
            var modal = this;
            
            // Populate modal with data from button attributes
            modal.querySelector('#modalCustomerName').textContent = button.dataset.customerName || '';
            modal.querySelector('#modalDate').textContent = button.dataset.date || '';
            modal.querySelector('#modalTime').textContent = button.dataset.time || '';
            modal.querySelector('#modalCustomerAddress').textContent = button.dataset.address || '';
            modal.querySelector('#modalServiceType').textContent = button.dataset.serviceType || '';
            modal.querySelector('#modalAppliancesType').textContent = button.dataset.appliancesType || 'Not Specified';
            modal.querySelector('#modalDescription').textContent = button.dataset.description || '';
            modal.querySelector('#modalTechnician').textContent = button.dataset.technician || '';
            modal.querySelector('#modalStatus').textContent = button.dataset.status || '';
            modal.querySelector('#modalPaymentStatus').textContent = button.dataset.paymentStatus || '';
            
            // Handle secondary technician
            var technician2Element = modal.querySelector('#modalTechnician2');
            var technician2Container = modal.querySelector('#secondTechnicianSection');
            if (button.dataset.technician2 && button.dataset.technician2.trim() !== '') {
                technician2Element.textContent = button.dataset.technician2;
                technician2Container.style.display = 'block';
            } else {
                technician2Container.style.display = 'none';
            }
            
            // Hide sections not relevant for approved appointments
            modal.querySelector('#completedPriceSection').style.display = 'none';
            modal.querySelector('#costJustificationSection').style.display = 'none';
            modal.querySelector('#declineJustificationSection').style.display = 'none';
            modal.querySelector('#feedbackSection').style.display = 'none';
            
            // Show price range for approved appointments
            var priceRangeElement = modal.querySelector('#modalPriceRange');
            var priceRangeContainer = modal.querySelector('#priceRangeSection');
            if (button.dataset.priceMin && button.dataset.priceMax) {
                var minPrice = parseFloat(button.dataset.priceMin).toLocaleString('en-US', {minimumFractionDigits: 2});
                var maxPrice = parseFloat(button.dataset.priceMax).toLocaleString('en-US', {minimumFractionDigits: 2});
                priceRangeElement.textContent = '₱' + minPrice + ' - ₱' + maxPrice;
                priceRangeContainer.style.display = 'block';
            } else {
                priceRangeContainer.style.display = 'none';
            }
        });

        // Restore background page scrollbar when modal closes
        document.getElementById('viewAppointmentModal').addEventListener('hidden.bs.modal', function (event) {
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
        });

        // Poll every 30 seconds to update all data
        setInterval(() => {
            updateUserCounts();
            updateAppCounts();
            updateUpcomingAppointments();
            updateWorkloadSummary();
            updateTechnicianPerformance();
            updateAnalyticsChart();
            updateFinancialOverview();
        }, 30000);
    });

    // Global function to view appointment details
    window.viewAppointmentDetails = function(appointmentId) {
        console.log('viewAppointmentDetails called with ID:', appointmentId);
        console.log('Current appointments available:', window.currentAppointments);
        
        // Find the appointment data from the current appointments
        const appointmentData = window.currentAppointments?.find(app => app.app_id == appointmentId);
        
        if (!appointmentData) {
            console.error('Appointment data not found for ID:', appointmentId);
            console.error('Available appointment IDs:', window.currentAppointments?.map(app => app.app_id));
            alert('Appointment data not found. Please refresh the page and try again.');
            return;
        }
        
        console.log('Found appointment data:', appointmentData);

        // Populate modal with appointment data
        const modal = document.getElementById('viewAppointmentModal');
        
        // Basic information
        modal.querySelector('#modalCustomerName').textContent = appointmentData.customer_name;
        modal.querySelector('#modalCustomerAddress').textContent = appointmentData.customer_address;
        modal.querySelector('#modalDate').textContent = appointmentData.full_date;
        modal.querySelector('#modalTime').textContent = appointmentData.full_time;
        modal.querySelector('#modalServiceType').textContent = appointmentData.service_type;
        modal.querySelector('#modalTechnician').textContent = appointmentData.technician_name;
        modal.querySelector('#modalPaymentStatus').textContent = appointmentData.payment_status;
        modal.querySelector('#modalStatus').textContent = appointmentData.status;
        modal.querySelector('#modalDescription').textContent = appointmentData.description;
        modal.querySelector('#modalAppliancesType').textContent = appointmentData.appliances_type;

        // Handle second technician display
        const secondTechnicianSection = modal.querySelector('#secondTechnicianSection');
        const modalTechnician2 = modal.querySelector('#modalTechnician2');
        
        if (appointmentData.technician_2_name && appointmentData.technician_2_name.trim() !== '') {
            modalTechnician2.textContent = appointmentData.technician_2_name;
            secondTechnicianSection.style.display = 'block';
        } else {
            secondTechnicianSection.style.display = 'none';
        }

        // Handle decline justification display
        const declineJustificationSection = document.getElementById('declineJustificationSection');
        const modalDeclineJustification = document.getElementById('modalDeclineJustification');
        
        if (appointmentData.status === 'Declined') {
            if (appointmentData.decline_justification && appointmentData.decline_justification.trim() !== '') {
                modalDeclineJustification.textContent = appointmentData.decline_justification;
                declineJustificationSection.style.display = 'block';
            } else {
                modalDeclineJustification.textContent = 'No justification provided.';
                declineJustificationSection.style.display = 'block';
            }
        } else {
            declineJustificationSection.style.display = 'none';
        }

        // Handle finalized price and cost justification display for completed appointments
        const completedPriceSection = document.getElementById('completedPriceSection');
        const costJustificationSection = document.getElementById('costJustificationSection');
        const modalFinalizedPrice = document.getElementById('modalFinalizedPrice');
        const modalCostJustification = document.getElementById('modalCostJustification');
        
        if (appointmentData.app_status_id == '3' && appointmentData.status === 'Completed') {
            // Display finalized price if available
            if (appointmentData.app_price && appointmentData.app_price.trim() !== '' && appointmentData.app_price !== '0') {
                const formattedPrice = '₱' + new Intl.NumberFormat('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(parseFloat(appointmentData.app_price));
                modalFinalizedPrice.textContent = formattedPrice;
                completedPriceSection.style.display = 'block';
            } else {
                completedPriceSection.style.display = 'none';
            }
            
            // Display cost justification if available
            if (appointmentData.app_justification && appointmentData.app_justification.trim() !== '') {
                modalCostJustification.textContent = appointmentData.app_justification;
                costJustificationSection.style.display = 'block';
            } else {
                costJustificationSection.style.display = 'none';
            }
            
            // Handle feedback display for completed appointments
            const feedbackSection = document.getElementById('feedbackSection');
            
            if (appointmentData.app_rating && appointmentData.app_rating !== '0' && appointmentData.app_comment && appointmentData.app_comment.trim() !== '' && appointmentData.app_comment !== 'No Comment') {
                // Display star rating
                const ratingStars = document.getElementById('ratingStars');
                const ratingValue = document.getElementById('ratingValue');
                const rating = parseInt(appointmentData.app_rating);
                
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        starsHtml += '★';
                    } else {
                        starsHtml += '☆';
                    }
                }
                ratingStars.innerHTML = starsHtml;
                ratingValue.textContent = '(' + rating + '/5)';
                
                // Display comment
                document.getElementById('modalComment').textContent = appointmentData.app_comment;
                
                feedbackSection.style.display = 'block';
            } else {
                feedbackSection.style.display = 'none';
            }
        } else {
            // Hide completed appointment sections for non-completed appointments
            completedPriceSection.style.display = 'none';
            costJustificationSection.style.display = 'none';
            
            // Show price range for non-completed appointments
            const priceRangeSection = document.getElementById('priceRangeSection');
            const modalPriceRange = document.getElementById('modalPriceRange');
            
            if (appointmentData.service_type_price_min && appointmentData.service_type_price_max && 
                appointmentData.service_type_price_min.trim() !== '' && appointmentData.service_type_price_max.trim() !== '') {
                const minPrice = parseFloat(appointmentData.service_type_price_min);
                const maxPrice = parseFloat(appointmentData.service_type_price_max);
                
                if (minPrice > 0 && maxPrice > 0) {
                    let priceRangeText;
                    if (minPrice === maxPrice) {
                        // Fixed price
                        priceRangeText = '₱' + new Intl.NumberFormat('en-PH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(minPrice);
                    } else {
                        // Price range
                        priceRangeText = '₱' + new Intl.NumberFormat('en-PH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(minPrice) + ' - ₱' + new Intl.NumberFormat('en-PH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(maxPrice);
                    }
                    modalPriceRange.textContent = priceRangeText;
                    priceRangeSection.style.display = 'block';
                } else {
                    priceRangeSection.style.display = 'none';
                }
            } else {
                priceRangeSection.style.display = 'none';
            }
        }

        // Show the modal
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    };
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
                    <div id="modalTechnician" class="fs-6"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3" id="secondTechnicianSection" style="display: none;">
                    <label class="text-muted small"><i class="bi bi-person-plus me-2"></i>Secondary Technician</label>
                    <div id="modalTechnician2" class="fs-6"></div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mt-3">
            <h6 class="text-primary"><i class="bi bi-card-text me-2"></i>Description</h6>
            <p id="modalDescription" class="p-3 bg-light rounded border"></p>
        </div>
        <div class="mt-3" id="declineJustificationSection" style="display: none;">
            <h6 class="text-primary"><i class="bi bi-exclamation-circle me-2"></i>Decline Justification</h6>
            <p id="modalDeclineJustification" class="p-3 bg-light rounded border"></p>
        </div>
        <div class="mt-3" id="completedPriceSection" style="display: none;">
            <h6 class="text-primary"><i class="bi bi-currency-exchange me-2"></i>Finalized Price</h6>
            <p id="modalFinalizedPrice" class="p-3 bg-light rounded border fs-5"></p>
        </div>
        <div class="mt-3" id="costJustificationSection" style="display: none;">
            <h6 class="text-primary"><i class="bi bi-journal-text me-2"></i>Cost Justification</h6>
            <p id="modalCostJustification" class="p-3 bg-light rounded border"></p>
        </div>
        <div class="mt-3" id="priceRangeSection" style="display: none;">
            <h6 class="text-primary"><i class="bi bi-tag me-2"></i>Expected Price Range</h6>
            <p id="modalPriceRange" class="p-3 bg-light rounded border fs-5"></p>
        </div>
        <div class="mt-3" id="feedbackSection" style="display: none;">
            <h6 class="text-primary"><i class="bi bi-star-fill me-2"></i>Customer Feedback</h6>
            <div class="p-3 bg-light rounded border">
                <div class="mb-2">
                    <label class="text-muted small"><i class="bi bi-star me-2"></i>Rating</label>
                    <div id="modalRating" class="fs-6">
                        <span id="ratingStars" class="text-warning"></span>
                        <span id="ratingValue" class="ms-2 text-muted"></span>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="text-muted small"><i class="bi bi-chat-text me-2"></i>Comment</label>
                    <div id="modalComment" class="fs-6 mt-1"></div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>