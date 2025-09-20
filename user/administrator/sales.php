<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
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
    
    .dashboard-card-body .row {
        margin-left: -12px;
        margin-right: -12px;
    }
    
    .dashboard-card-body .row > [class*='col-'] {
        padding-left: 12px;
        padding-right: 12px;
    }

    /* Container and Layout Styling */
    .container {
        margin-top: -20px;
    }  
    .container.mt-4 {
        padding: 1px;
        height: 820px;
        margin-bottom: 15px;
    }  
    
    .row.mb-3.g-2 {
        padding: 15px;
    }
    
    .card.p-2.p-md-3.round_md {
        margin-left: 10px;
        margin-right: 10px;
    }

    .custom-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;    
        gap: 10px;  
    }
    /* Hover effect for cards */
    .hover-card {
        transition: all 0.3s ease;
    }
    
    .hover-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    /* Workload metrics styling */
    .workload-metric {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
        height: 100%;
    }

    .workload-metric:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .workload-metric-value {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .workload-metric-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }
    .dashboard-chart-card.full-width canvas {
        max-height: 280px !important;
        min-height: 240px;
        width: 100% !important;
        max-width: none !important;
    }
    @media (max-width: 991px) {
        .dashboard-chart-card {
            min-height: 320px;
            padding: 16px 8px 8px 8px;
        }
        .dashboard-chart-title {
            font-size: 1rem;
        }
    }
    @media (max-width: 767px) {
        .dashboard-chart-card {
            min-height: 260px;
            padding: 10px 2px 2px 2px;
        }
        .dashboard-chart-title {
            font-size: 0.98rem;
        }
    }

      /* Table Styling */
    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #4a4a4a;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e9ecef;
        padding: 12px 16px;
    }
    
    .table tbody td {
        padding: 12px 16px;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.03);
    }

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

    /* Remove table borders */
    #salesReport,
    #salesReport th,
    #salesReport td {
        border: none !important;
    }
    /* Hide default DataTables controls */
    .dataTables_filter,
    .dataTables_length {
        display: none !important;
    }
     /* Optional: Remove DataTables default styling */
    #salesReport.dataTable {
        border-collapse: separate !important;
    }

    /* Custom Pagination Styling */
    .dataTables_paginate {
        margin-top: 20px !important;
        text-align: center !important;
    }

    .dataTables_paginate .paginate_button {
        display: inline-block !important;
        padding: 8px 12px !important;
        margin: 0 2px !important;
        background: #f8f9fa !important;
        border: 0px solid #dee2e6 !important;
        border-radius: 20px !important;
        color: #495057 !important;
        text-decoration: none !important;
        transition: all 0.3s ease !important;
        font-size: 14px !important;
        min-width: 40px !important;
        text-align: center !important;
    }

    .dataTables_paginate .paginate_button:hover {
        background: #e9ecef !important;
        border-color: #adb5bd !important;
        color: #495057 !important;
    }

    .dataTables_paginate .paginate_button.current,
    .dataTables_paginate .paginate_button.current a {
        background: #007bff !important;
        border-color: #007bff !important;
        color: white !important;
        font-weight: bold !important;
    }

    .dataTables_paginate .paginate_button.current:hover,
    .dataTables_paginate .paginate_button.current:hover a {
        background: #0056b3 !important;
        border-color: #0056b3 !important;
        color: white !important;
    }

    /* Additional specificity for active pagination button text color */
    #salesReport_paginate .paginate_button.current,
    #salesReport_paginate .paginate_button.current:hover,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        color: white !important;
    }

    /* Ensure non-active pagination buttons stay dark on hover */
    #salesReport_paginate .paginate_button:not(.current):hover,
    .dataTables_wrapper .dataTables_paginate .paginate_button:not(.current):hover {
        color: #495057 !important;
    }

    .dataTables_paginate .paginate_button.disabled {
        background: #f8f9fa !important;
        border-color: #dee2e6 !important;
        color: #6c757d !important;
        cursor: not-allowed !important;
        opacity: 0.5 !important;
    }

    .dataTables_paginate .paginate_button.disabled:hover {
        background: #f8f9fa !important;
        transform: none !important;
        box-shadow: none !important;
    }

    /* Custom styling for Previous/Next buttons */
    .dataTables_paginate .paginate_button.previous,
    .dataTables_paginate .paginate_button.next {
        font-weight: bold !important;
        padding: 8px 16px !important;
    }

    /* Add icons to Previous/Next (optional) */
    .dataTables_paginate .paginate_button.previous:before {
        content: "‹ " !important;
    }

    .dataTables_paginate .paginate_button.next:after {
        content: " ›" !important;
    }

    /* Mobile-specific card styling */
    @media (max-width: 991.98px) {
        #mobileCardsContainer .card {
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            border: none !important;
        }
        
        #mobileCardsContainer .card-title {
            font-size: 1rem;
        }
        
        #mobileCardsContainer .badge {
            font-weight: 600;
        }
    }
    
    /* Mobile pagination styling to match desktop */
    .mobile-dataTables-paginate {
        margin-top: 20px !important;
        text-align: center !important;
    }
    
    .mobile-paginate-button {
        display: inline-block !important;
        padding: 8px 12px !important;
        margin: 0 2px !important;
        background: #f8f9fa !important;
        border: 0px solid #dee2e6 !important;
        border-radius: 20px !important;
        color: #495057 !important;
        text-decoration: none !important;
        transition: all 0.3s ease !important;
        font-size: 14px !important;
        min-width: 40px !important;
        text-align: center !important;
    }
    
    .mobile-paginate-button:hover {
        background: #e9ecef !important;
        border-color: #adb5bd !important;
        color: #495057 !important;
        transform: translateY(-1px) !important;
        text-decoration: none !important;
    }
    
    .mobile-paginate-button.current {
        background: #007bff !important;
        border-color: #007bff !important;
        color: white !important;
        font-weight: bold !important;
    }
    
    .mobile-paginate-button.current:hover {
        background: #0056b3 !important;
        border-color: #0056b3 !important;
        transform: translateY(-1px) !important;
        text-decoration: none !important;
    }
    
    .mobile-paginate-button.disabled {
        background: #f8f9fa !important;
        border-color: #dee2e6 !important;
        color: #6c757d !important;
        cursor: not-allowed !important;
        opacity: 0.5 !important;
    }
    
    .mobile-paginate-button.disabled:hover {
        background: #f8f9fa !important;
        transform: none !important;
        box-shadow: none !important;
        text-decoration: none !important;
    }
    
    .mobile-paginate-button.previous,
    .mobile-paginate-button.next {
        font-weight: bold !important;
        padding: 8px 16px !important;
    }

    /* Technician Performance Dropdown Styling */
    #technicianYearFilter option,
    #technicianMonthFilter option {
        background: white !important;
        color: black !important;
    }

    #technicianYearFilter:focus,
    #technicianMonthFilter:focus {
        background: white !important;
        color: black !important;
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }

    /* Customer Engagement Dropdown Styling */
    #engagementYearFilter option,
    #engagementMonthFilter option {
        background: white !important;
        color: black !important;
    }

    #engagementYearFilter:focus,
    #engagementMonthFilter:focus {
        background: white !important;
        color: black !important;
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }

    /* Desktop Header - Ensure inline layout */
    @media (min-width: 769px) {
        .page-header-mobile .section-selector-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 8px;
            padding-top: 8px;
        }
        
        .page-header-mobile .section-selector-container label {
            margin-bottom: 0 !important;
            white-space: nowrap;
        }
    }

    /* Mobile Header Responsiveness */
    @media (max-width: 768px) {
        .page-header-mobile {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 15px;
            margin-top: 10px !important;
            padding-top: 20px !important;
        }
        
        .page-header-mobile h3 {
            font-size: 1.5rem !important;
            margin-bottom: 0 !important;
            line-height: 1.3;
        }
        
        .page-header-mobile .section-selector-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-start;
        }
        
        .page-header-mobile .section-selector-container label {
            margin-bottom: 0 !important;
            font-size: 0.875rem;
            text-align: left;
            align-self: flex-start;
        }
        
        .page-header-mobile .section-selector-container select {
            min-width: 100% !important;
            width: 100% !important;
        }
    }
    
    @media (max-width: 576px) {
        .page-header-mobile {
            padding: 20px 15px 0 15px !important;
            margin-top: 15px !important;
        }
        
        .page-header-mobile h3 {
            font-size: 1.25rem !important;
        }
    }

    /* Technician Performance Mobile Header Styling */
    @media (max-width: 768px) {
        .technician-performance-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px;
        }
        
        .technician-performance-title {
            width: 100%;
            margin-bottom: 0;
        }
        
        .technician-performance-filters {
            width: 100%;
            display: flex;
            gap: 10px;
            justify-content: flex-start;
        }
        
        .technician-performance-filters select {
            flex: 1;
            min-width: 0;
            font-size: 0.875rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .technician-performance-filters {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        
        .technician-performance-filters select {
            width: 100% !important;
        }
    }

    /* Customer Engagement Mobile Header Styling */
    @media (max-width: 768px) {
        .customer-engagement-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px;
        }
        
        .customer-engagement-title {
            width: 100%;
            margin-bottom: 0;
        }
        
        .customer-engagement-filters {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            justify-content: flex-start;
        }
        
        .customer-engagement-filters select {
            width: 100% !important;
            min-width: 0;
            font-size: 0.875rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .customer-engagement-filters {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
    }

    /* CLV Mobile Header Styling */
    @media (max-width: 768px) {
        .clv-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px;
        }
        
        .clv-title {
            width: 100%;
            margin-bottom: 0;
        }
        
        .clv-filters {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            justify-content: flex-start;
        }
        
        .clv-filters select {
            width: 100% !important;
            min-width: 0;
            font-size: 0.875rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .clv-filters {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
    }

    /* Geographic Mobile Header Styling */
    @media (max-width: 768px) {
        .geographic-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px;
        }
        
        .geographic-title {
            width: 100%;
            margin-bottom: 0;
        }
        
        .geographic-filters {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            justify-content: flex-start;
        }
        
        .geographic-filters select {
            width: 100% !important;
            min-width: 0;
            font-size: 0.875rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .geographic-filters {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
    }
</style>



    <!-- Page Header with Section Navigation -->
    <div class="d-flex justify-content-between align-items-center mb-4 page-header-mobile" style="padding: 15px 20px 0 20px; margin-top: -20px;">
        <h3 style="margin: 0; color: #333; font-weight: 600; font-size: 27.22px;">Sales Report & Analytics</h3>
        <div class="d-flex align-items-center section-selector-container">
            <label class="form-label small text-muted mb-0 me-2">View Section:</label>
            <select id="sectionSelector" class="form-select form-select-sm" style="min-width: 180px;">
                <option value="sales-report">Sales Report</option>
                <option value="dashboard-analytics">Dashboard Analytics</option>
            </select>
        </div>
    </div>
    
    <!-- Sales Report Section -->
    <div id="sales-report-section">
    <!-- Filters and Controls -->
    <div class="dashboard-card mb-4">
        <div class="dashboard-card-header">
            <i class="bi bi-funnel me-2"></i>Filter Sales Data
        </div>
        <div class="dashboard-card-body py-3">
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <label class="form-label small text-muted mb-1">Time Period</label>
                    <select id="timePeriod" class="form-select">
                        <option value="monthly">Monthly</option>
                        <option value="weekly">Weekly</option>
                    </select>
                </div>
                <div class="col-md-3 col-6" id="yearFilterMonthlyContainer">
                    <label class="form-label small text-muted mb-1">Year</label>
                    <select id="yearFilterMonthly" class="form-select">
                        <option value="">All Years</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                    </select>
                </div>
                <div class="col-md-6" id="weeklyFiltersContainer" style="display: none;">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Month</label>
                            <select id="monthFilter" class="form-select">
                                <option value="">All Months</option>
                                <option value="0">January</option>
                                <option value="1">February</option>
                                <option value="2">March</option>
                                <option value="3">April</option>
                                <option value="4">May</option>
                                <option value="5">June</option>
                                <option value="6">July</option>
                                <option value="7">August</option>
                                <option value="8">September</option>
                                <option value="9">October</option>
                                <option value="10">November</option>
                                <option value="11">December</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">Year</label>
                            <select id="yearFilterWeekly" class="form-select">
                                <option value="">All Years</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Data Table -->
    <div class="dashboard-card mb-4">
        <div class="dashboard-card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-table me-2"></i>Sales Records
                </div>
                <div>
                    <button id="printButton" class="btn btn-light btn-sm">
                        <i class="bi bi-printer-fill"></i> Print
                    </button>
                </div>
            </div>
        </div>
        <div class="dashboard-card-body">
            <!-- Mobile-friendly table controls -->
            <div class="row mb-3 g-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Show Records</label>
                    <select id="customPageLength" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="-1">All</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label small text-muted mb-1">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="customSearch" class="form-control" placeholder="Search sales records...">
                    </div>
                </div>
            </div>
            
            <!-- Desktop Table View -->
            <div class="d-none d-lg-block">
                <div class="table-responsive">
                    <table id="salesReport" class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Service Type</th>
                                <th scope="col">Appliances Type</th>
                                <th scope="col" class="text-end">Amount</th>
                                <th scope="col">Service Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Real data from database will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Mobile Card View -->
            <div class="d-lg-none" id="mobileCardsContainer">
                <!-- Mobile cards will be dynamically generated here -->
            </div>
            
            <!-- Mobile Pagination -->
            <div class="d-lg-none mt-3" id="mobilePaginationContainer">
                <!-- Mobile pagination will be dynamically generated here -->
            </div>
        </div>
    </div>
    </div> <!-- End Sales Report Section -->

    <!-- Dashboard Analytics Section -->
    <div id="dashboard-analytics-section" style="display: none;">

            
            <!-- Business Analytics and Financial Overview -->
            <div class="row mb-4">
                <!-- Business Analytics Chart -->
                <div class="col-lg-8">
                    <div class="dashboard-card hover-card">
                        <div class="dashboard-card-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                            <!-- Desktop header layout -->
                            <div class="d-none d-md-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-graph-up me-2"></i>
                                    <span>Business Analytics</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <select class="form-select form-select-sm" id="analyticsTimeframe" style="width: auto; font-size: 0.875rem;">
                                        <option value="7">Last 7 Days</option>
                                        <option value="30" selected>Last 30 Days</option>
                                        <option value="90">Last 90 Days</option>
                                        <option value="365">This Year</option>
                                    </select>
                                    <select class="form-select form-select-sm" id="analyticsChartType" style="width: auto; font-size: 0.875rem;">
                                        <option value="appointments">Appointments</option>
                                        <option value="revenue">Revenue</option>
                                        <option value="service_types">Service Types</option>
                                        <option value="appliance_types">Appliance Types</option>
                                    </select>
                                    <select class="form-select form-select-sm" id="analyticsYearFilter" style="width: auto; font-size: 0.875rem;">
                                        <!-- Year options will be populated dynamically -->
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Mobile header layout -->
                            <div class="d-md-none">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-graph-up me-2"></i>
                                    <span>Business Analytics</span>
                                </div>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <select class="form-select form-select-sm" id="analyticsTimeframeMobile" style="font-size: 0.875rem;">
                                            <option value="7">Last 7 Days</option>
                                            <option value="30" selected>Last 30 Days</option>
                                            <option value="90">Last 90 Days</option>
                                            <option value="365">This Year</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-select form-select-sm" id="analyticsChartTypeMobile" style="font-size: 0.875rem;">
                                            <option value="appointments">Appointments</option>
                                            <option value="revenue">Revenue</option>
                                            <option value="service_types">Service Types</option>
                                            <option value="appliance_types">Appliance Types</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-select form-select-sm" id="analyticsYearFilterMobile" style="font-size: 0.875rem;">
                                            <!-- Year options will be populated dynamically -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard-card-body">
                            <div style="height: 300px; position: relative;">
                                <canvas id="businessAnalyticsChart"></canvas>
                                <div id="analyticsLoading" class="text-center py-4" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                    <div class="spinner-border text-primary mb-3" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="text-muted mb-0">Loading analytics...</p>
                                </div>
                            </div>
                            
                            <!-- Key Metrics Row -->
                            <div class="row business-analytics-metrics">
                                <div class="col-lg-3 col-md-6 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-primary" id="totalAppointments" style="font-size: 1.1rem;">0</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Total Appointments</div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-success" id="completionRate" style="font-size: 1.1rem;">0%</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Completion Rate</div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-info" id="avgResponseTime" style="font-size: 1.1rem;">0h</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Avg Response Time</div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-warning" id="customerSatisfaction" style="font-size: 1.1rem;">0.0</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Avg Rating</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Overview -->
                <div class="col-lg-4">
                    <div class="dashboard-card hover-card">
                        <div class="dashboard-card-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                            <i class="bi bi-cash-stack me-2"></i>Financial Overview
                        </div>
                        <div class="dashboard-card-body">
                            <!-- Today's Revenue -->
                            <div class="financial-item" style="background: #f8f9fa; border-radius: 8px; padding: 12px 15px; border: 1px solid #e9ecef; margin-bottom: 15px;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-day text-success me-2"></i>
                                        <small class="fw-semibold">Today's Revenue</small>
                                    </div>
                                    <span class="badge bg-success px-2 py-1" style="border-radius: 12px;">₱<span id="todayRevenue">0.00</span></span>
                                </div>
                                <div class="progress" style="height: 4px; border-radius: 2px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 0%; border-radius: 2px;" id="todayRevenueProgress"></div>
                                </div>
                            </div>

                            <!-- This Week's Revenue -->
                            <div class="financial-item" style="background: #f8f9fa; border-radius: 8px; padding: 12px 15px; border: 1px solid #e9ecef; margin-bottom: 15px;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-week text-primary me-2"></i>
                                        <small class="fw-semibold">This Week's Revenue</small>
                                    </div>
                                    <span class="badge bg-primary px-2 py-1" style="border-radius: 12px;">₱<span id="weekRevenue">0.00</span></span>
                                </div>
                                <div class="progress" style="height: 4px; border-radius: 2px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 0%; border-radius: 2px;" id="weekRevenueProgress"></div>
                                </div>
                            </div>

                            <!-- Pending Payments -->
                            <div class="financial-item" style="background: #f8f9fa; border-radius: 8px; padding: 12px 15px; border: 1px solid #e9ecef; margin-bottom: 15px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                        <small class="fw-semibold">Pending Payments</small>
                                    </div>
                                    <span class="badge bg-warning px-2 py-1" style="border-radius: 12px;">₱<span id="pendingPayments">0.00</span></span>
                                </div>
                            </div>

                            <!-- Collection Rate -->
                            <div class="financial-item" style="background: #f8f9fa; border-radius: 8px; padding: 12px 15px; border: 1px solid #e9ecef;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-graph-up text-info me-2"></i>
                                        <small class="fw-semibold">Collection Rate</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold text-info me-2" id="collectionRate">0%</span>
                                        <i class="bi bi-arrow-up text-success"></i>
                                    </div>
                                </div>
                                <div class="progress" style="height: 4px; border-radius: 2px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 0%; border-radius: 2px;" id="collectionRateProgress"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Technician Performance -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dashboard-card hover-card">
                        <div class="dashboard-card-header technician-performance-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                            <!-- Desktop layout -->
                            <div class="d-none d-md-flex justify-content-between align-items-center w-100">
                                <div class="technician-performance-title">
                                    <i class="bi bi-people me-2"></i>Technician Performance
                                </div>
                                <div class="technician-performance-filters">
                                    <select id="technicianYearFilter" class="form-select form-select-sm d-inline-block" style="width: auto; background: white; border: 1px solid #dee2e6; color: black;">
                                        <option value="">All Years</option>
                                    </select>
                                    <select id="technicianMonthFilter" class="form-select form-select-sm d-inline-block ms-2" style="width: auto; background: white; border: 1px solid #dee2e6; color: black;">
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
                            
                            <!-- Mobile layout -->
                            <div class="d-md-none w-100">
                                <div class="technician-performance-title mb-3">
                                    <i class="bi bi-people me-2"></i>Technician Performance
                                </div>
                                <div class="technician-performance-filters">
                                    <select id="technicianYearFilterMobile" class="form-select form-select-sm" style="background: white; border: 1px solid #dee2e6; color: black;">
                                        <option value="">All Years</option>
                                    </select>
                                    <select id="technicianMonthFilterMobile" class="form-select form-select-sm" style="background: white; border: 1px solid #dee2e6; color: black;">
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
                            <div style="position: relative; height: 300px;">
                                <canvas id="technicianPerformanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Engagement Levels -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dashboard-card hover-card">
                        <div class="dashboard-card-header customer-engagement-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                            <!-- Desktop layout -->
                            <div class="d-none d-md-flex justify-content-between align-items-center w-100">
                                <div class="customer-engagement-title">
                                    <i class="bi bi-heart me-2"></i>Customer Engagement Levels
                                </div>
                                <div class="customer-engagement-filters">
                                    <select id="engagementYearFilter" class="form-select form-select-sm d-inline-block" style="width: auto; background: white; border: 1px solid #dee2e6; color: black;">
                                        <option value="">All Years</option>
                                    </select>
                                    <select id="engagementMonthFilter" class="form-select form-select-sm d-inline-block ms-2" style="width: auto; background: white; border: 1px solid #dee2e6; color: black;">
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
                            
                            <!-- Mobile layout -->
                            <div class="d-md-none w-100">
                                <div class="customer-engagement-title mb-3">
                                    <i class="bi bi-heart me-2"></i>Customer Engagement Levels
                                </div>
                                <div class="customer-engagement-filters">
                                    <select id="engagementYearFilterMobile" class="form-select form-select-sm" style="background: white; border: 1px solid #dee2e6; color: black;">
                                        <option value="">All Years</option>
                                    </select>
                                    <select id="engagementMonthFilterMobile" class="form-select form-select-sm" style="background: white; border: 1px solid #dee2e6; color: black;">
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
                            <div style="position: relative; height: 300px;">
                                <canvas id="engagementLevelsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <!-- Customer Lifetime Value Analysis -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dashboard-card hover-card">
                        <div class="dashboard-card-header clv-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                            <!-- Desktop layout -->
                            <div class="d-none d-md-flex justify-content-between align-items-center w-100">
                                <div class="clv-title">
                                    <i class="bi bi-person-heart me-2"></i>Customer Lifetime Value Analysis
                                </div>
                                <div class="clv-filters">
                                    <select id="clvYearFilter" class="form-select form-select-sm d-inline-block" style="width: auto; background: white; border: 1px solid #dee2e6; color: black;">
                                        <option value="">All Years</option>
                                    </select>
                                    <select id="clvMonthFilter" class="form-select form-select-sm d-inline-block ms-2" style="width: auto; background: white; border: 1px solid #dee2e6; color: black;">
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
                            
                            <!-- Mobile layout -->
                            <div class="d-md-none w-100">
                                <div class="clv-title mb-3">
                                    <i class="bi bi-person-heart me-2"></i>Customer Lifetime Value Analysis
                                </div>
                                <div class="clv-filters">
                                    <select id="clvYearFilterMobile" class="form-select form-select-sm" style="background: white; border: 1px solid #dee2e6; color: black;">
                                        <option value="">All Years</option>
                                    </select>
                                    <select id="clvMonthFilterMobile" class="form-select form-select-sm" style="background: white; border: 1px solid #dee2e6; color: black;">
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
                            <!-- CLV Metrics Row -->
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-4 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-info" id="totalCustomersCount" style="font-size: 1.1rem;">0</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Total Customers</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-success" id="totalCustomersRevenue" style="font-size: 1.1rem;">₱0.00</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Total Revenue</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-primary" id="avgCLVValue" style="font-size: 1.1rem;">₱0.00</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Average CLV</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-success" id="highValueCustomersCount" style="font-size: 1.1rem;">0</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">High-Value</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-warning" id="loyalCustomersCount" style="font-size: 1.1rem;">0</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Loyal (3+ jobs)</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-success" id="topCustomerValue" style="font-size: 1.1rem;">₱0.00</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Top Customer</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Chart Container -->
                            <div style="position: relative; height: 350px;">
                                <canvas id="clvAnalysisChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <!-- Geographic/Regional Sales Insights -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dashboard-card hover-card">
                        <div class="dashboard-card-header geographic-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                            <!-- Desktop layout -->
                            <div class="d-none d-md-flex justify-content-between align-items-center w-100">
                                <div class="geographic-title">
                                    <i class="bi bi-geo-alt me-2"></i>Geographic/Regional Sales Insights
                                </div>
                                <div class="geographic-filters">
                                    <select id="geographicYearFilter" class="form-select form-select-sm d-inline-block" style="width: auto; background: white; border: 1px solid #dee2e6; color: black;">
                                        <option value="">All Years</option>
                                    </select>
                                    <select id="geographicMonthFilter" class="form-select form-select-sm d-inline-block ms-2" style="width: auto; background: white; border: 1px solid #dee2e6; color: black;">
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
                            
                            <!-- Mobile layout -->
                            <div class="d-md-none w-100">
                                <div class="geographic-title mb-3">
                                    <i class="bi bi-geo-alt me-2"></i>Geographic/Regional Sales Insights
                                </div>
                                <div class="geographic-filters">
                                    <select id="geographicYearFilterMobile" class="form-select form-select-sm" style="background: white; border: 1px solid #dee2e6; color: black;">
                                        <option value="">All Years</option>
                                    </select>
                                    <select id="geographicMonthFilterMobile" class="form-select form-select-sm" style="background: white; border: 1px solid #dee2e6; color: black;">
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
                            <!-- Regional Metrics Row -->
                            <div class="row mb-3">
                                <div class="col-lg-2 col-md-4 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-success" id="topRevenueRegion" style="font-size: 1.1rem;">-</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Top Revenue</div>
                                        <div class="workload-metric-value text-success small" id="topRegionRevenue" style="font-size: 0.7rem;">₱0.00</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-primary" id="mostActiveRegion" style="font-size: 1.1rem;">-</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Most Active</div>
                                        <div class="workload-metric-value text-primary small" id="mostActiveJobs" style="font-size: 0.7rem;">0</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-info" id="totalRegions" style="font-size: 1.1rem;">0</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Total Regions</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-warning" id="totalRegionalCustomers" style="font-size: 1.1rem;">0</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Total Customers</div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-8 col-12 mb-2">
                                    <div class="workload-metric" style="padding: 8px 12px;">
                                        <div class="workload-metric-value text-success" id="avgRevenuePerRegion" style="font-size: 1.1rem;">₱0.00</div>
                                        <div class="workload-metric-label" style="font-size: 0.75rem;">Avg Revenue per Region</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Chart Container -->
                            <div style="position: relative; height: 350px;">
                                <canvas id="geographicAnalysisChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    </div> <!-- End Dashboard Analytics Section -->
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function () {
        // Real sales data from database (completed and paid appointments)
        console.log('Sales Data loaded:', salesData.length, 'records');

        // Add resetFilters function
        function resetFilters() {
            // Reset all filter controls
            $('#customSearch').val('');
            $('#customPageLength').val('10');
            $('#startDate').val('');
            $('#endDate').val('');
            
            // Clear DataTable and reload all data
            table.clear().rows.add(salesData).draw();
            table.page.len(10).draw();
            
            // Update mobile cards
            renderMobileCards(salesData.slice(0, 10));
            
            // Update metrics
            updateSalesMetrics(salesData);
        }

        // Initialize DataTable with custom settings
        var table = $('#salesReport').DataTable({
            data: salesData,
            columns: [
                { 
                    data: 'id',
                    title: 'ID',
                    width: '6%',
                    className: 'text-center'
                },
                { 
                    data: 'customer_name',
                    title: 'Customer',
                    width: '22%',
                    render: function(data) {
                        return data ? '<span class="text-truncate d-inline-block" style="max-width: 180px;" title="' + data.trim() + '">' + data.trim() + '</span>' : 'N/A';
                    }
                },
                { 
                    data: 'service',
                    title: 'Service Type',
                    width: '16%'
                },
                { 
                    data: 'appliance_type',
                    title: 'Appliances Type',
                    width: '20%',
                    render: function(data) {
                        return data ? '<span class="text-truncate d-inline-block" style="max-width: 160px;" title="' + data.trim() + '">' + data.trim() + '</span>' : 'N/A';
                    }
                },
                { 
                    data: 'price', 
                    title: 'Amount',
                    width: '14%',
                    render: function (data) { 
                        return '₱' + new Intl.NumberFormat('en-PH', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(data || 0);
                    },
                    className: 'text-end'
                },
                { 
                    data: 'date',
                    title: 'Service Date',
                    width: '22%',
                    render: function(data) {
                        const date = new Date(data);
                        return '<span>' + date.toLocaleDateString('en-PH', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        }) + '</span>';
                    },
                    className: 'text-center'
                }
            ],
            // Enhanced DataTable settings
            searching: true,
            lengthChange: true,
            ordering: true,
            order: [[0, 'desc']], // Sort by ID descending (most recent first)
            dom: 'rtip',
            pageLength: 10,
            responsive: true,
            language: {
                emptyTable: `
                    <div class="empty-state">
                        <i class="bi bi-graph-up text-muted" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mt-3">No Sales Records Found</h5>
                        <p class="text-muted mb-0">There are no sales records matching your current filters.</p>
                    </div>
                `,
                zeroRecords: `
                    <div class="empty-state">
                        <i class="bi bi-graph-up text-muted" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mt-3">No Sales Records Found</h5>
                        <p class="text-muted mb-0">There are no sales records matching your current filters.</p>
                    </div>
                `,
                info: "Showing _START_ to _END_ of _TOTAL_ sales records",
                infoEmpty: "No sales records available",
                infoFiltered: "(filtered from _MAX_ total records)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });

        // Custom search functionality
        $('#customSearch').on('keyup change', function () {
            table.search($(this).val()).draw();
        });

        // Custom page length functionality
        $('#customPageLength').on('change', function () {
            var length = $(this).val();
            table.page.len(length).draw();
        });
        
        // Set default page length to 10 and trigger the change
        $('#customPageLength').val('10').trigger('change');
        
        // Ensure mobile cards show only 10 items initially
        const initialData = salesData.slice(0, 10);
        renderMobileCards(initialData);

        // Initialize Sales Performance Metrics
        updateSalesMetrics(salesData);

        // Section Navigation Functionality
        $('#sectionSelector').on('change', function() {
            const selectedSection = $(this).val();
            
            if (selectedSection === 'sales-report') {
                $('#sales-report-section').show();
                $('#dashboard-analytics-section').hide();
            } else if (selectedSection === 'dashboard-analytics') {
                $('#sales-report-section').hide();
                $('#dashboard-analytics-section').show();
                
                // Initialize charts when switching to analytics section
                setTimeout(function() {
                    // Charts are already initialized, no need for additional update
                }, 100);
            }
        });

        // Your existing filter functionality
        $('#timePeriod').on('change', function () {
            var timePeriod = $(this).val();
            if (timePeriod === 'weekly') {
                $('#weeklyFiltersContainer').show();
                $('#yearFilterMonthlyContainer').hide();
            } else {
                $('#weeklyFiltersContainer').hide();
                $('#yearFilterMonthlyContainer').show();
            }
            filterData();
        });

        $('#yearFilterMonthly').on('change', function () {
            filterData();
        });

        $('#monthFilter').on('change', function () {
            filterData();
        });

        $('#yearFilterWeekly').on('change', function () {
            filterData();
        });

        function filterData() {
            var timePeriod = $('#timePeriod').val();
            var filteredData = [...salesData]; // Create a copy of the original data

            console.log('Filtering data. Total records:', salesData.length);
            console.log('Time period:', timePeriod);

            if (timePeriod === 'weekly') {
                const selectedYear = $('#yearFilterWeekly').val();
                const selectedMonth = $('#monthFilter').val();

                console.log('Weekly filter - Year:', selectedYear, 'Month:', selectedMonth);

                if (selectedYear) {
                    filteredData = filteredData.filter(function (item) {
                        const itemYear = new Date(item.date).getFullYear().toString();
                        return itemYear === selectedYear;
                    });
                }
                if (selectedMonth !== '' && selectedMonth !== null) {
                    filteredData = filteredData.filter(function (item) {
                        const itemMonth = new Date(item.date).getMonth();
                        return itemMonth === parseInt(selectedMonth);
                    });
                }
            } else {
                const selectedYear = $('#yearFilterMonthly').val();
                console.log('Monthly filter - Year:', selectedYear);

                if (selectedYear) {
                    filteredData = filteredData.filter(function (item) {
                        const itemYear = new Date(item.date).getFullYear().toString();
                        return itemYear === selectedYear;
                    });
                }
            }

            console.log('Filtered data count:', filteredData.length);

            // Update DataTable
            table.clear().rows.add(filteredData).draw();

            // Update mobile cards
            renderMobileCards(filteredData.slice(0, parseInt($('#customPageLength').val() || 10)));

            // Charts are updated via centralized year filter, not sales data filter

            // Update Sales Performance Metrics with filtered data
            updateSalesMetrics(filteredData);
        }

        // Sales Performance Metrics calculation function
        function updateSalesMetrics(data) {
            // Calculate Total Sales Revenue
            const totalRevenue = data.reduce((sum, item) => {
                return sum + (parseFloat(item.price) || 0);
            }, 0);

            // Calculate Average Order Value
            const averageOrderValue = data.length > 0 ? totalRevenue / data.length : 0;

            // Calculate Sales Growth Rate (comparing current period with previous period)
            const currentYear = new Date().getFullYear();
            const currentYearData = data.filter(item => new Date(item.date).getFullYear() === currentYear);
            const previousYearData = data.filter(item => new Date(item.date).getFullYear() === currentYear - 1);
            
            const currentYearRevenue = currentYearData.reduce((sum, item) => sum + (parseFloat(item.price) || 0), 0);
            const previousYearRevenue = previousYearData.reduce((sum, item) => sum + (parseFloat(item.price) || 0), 0);
            
            const salesGrowthRate = previousYearRevenue > 0 
                ? ((currentYearRevenue - previousYearRevenue) / previousYearRevenue) * 100 
                : 0;

            // Calculate Conversion Rate (assuming all data in salesData are already converted sales)
            // For now, we'll use 100% since salesData only contains completed and paid appointments
            const conversionRate = 100;

            // Update the UI
            $('#totalSalesRevenue').text('₱' + new Intl.NumberFormat('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(totalRevenue));

            $('#averageOrderValue').text('₱' + new Intl.NumberFormat('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(averageOrderValue));

            $('#salesGrowthRate').text(salesGrowthRate.toFixed(1) + '%');
            
            // Update color based on growth rate
            const growthElement = $('#salesGrowthRate');
            growthElement.removeClass('text-success text-danger text-info');
            if (salesGrowthRate > 0) {
                growthElement.addClass('text-success');
            } else if (salesGrowthRate < 0) {
                growthElement.addClass('text-danger');
            } else {
                growthElement.addClass('text-info');
            }

            $('#conversionRate').text(conversionRate.toFixed(1) + '%');
        }

        // Mobile card rendering function
        function renderMobileCards(data) {
            const container = $('#mobileCardsContainer');
            container.empty();
            
            if (data.length === 0) {
                container.html(`
                    <div class="empty-state-mobile text-center py-5">
                        <i class="bi bi-graph-up text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">No Sales Records Found</h5>
                        <p class="text-muted mb-0 px-3">There are no sales records matching your current filters.</p>
                    </div>
                `);
                return;
            }
            
            // Create a row container for two-column layout
            const rowContainer = $('<div class="row g-3"></div>');
            
            data.forEach(function(record) {
                const date = new Date(record.date);
                const formattedDate = date.toLocaleDateString('en-PH', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
                
                const amount = '₱' + new Intl.NumberFormat('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(record.price || 0);
                
                const cardColumn = `
                    <div class="col-12 col-sm-6">
                        <div class="card h-100 border-start border-4 border-success">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0 fw-bold text-primary" style="font-size: 0.9rem;">ID: ${record.id}</h6>
                                    <span class="badge bg-success rounded-pill" style="font-size: 0.7rem;">${amount}</span>
                                </div>
                                
                                <div class="row g-1">
                                    <!-- Left Side: Customer Name and Date -->
                                    <div class="col-6">
                                        <div class="mb-2">
                                            <small class="text-muted d-block" style="font-size: 0.7rem;"><i class="bi bi-person me-1"></i>Customer:</small>
                                            <small class="fw-medium" style="font-size: 0.75rem;" title="${record.customer_name || 'N/A'}">
                                                ${record.customer_name ? (record.customer_name.length > 12 ? record.customer_name.substring(0, 12) + '...' : record.customer_name) : 'N/A'}
                                            </small>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted d-block" style="font-size: 0.7rem;"><i class="bi bi-calendar3 me-1"></i>Date:</small>
                                            <small class="fw-medium" style="font-size: 0.75rem;">${formattedDate}</small>
                                        </div>
                                    </div>
                                    
                                    <!-- Right Side: Service Type and Appliance Type -->
                                    <div class="col-6">
                                        <div class="mb-2">
                                            <small class="text-muted d-block" style="font-size: 0.7rem;"><i class="bi bi-gear me-1"></i>Service:</small>
                                            <small class="fw-medium" style="font-size: 0.75rem;" title="${record.service || 'N/A'}">
                                                ${record.service ? (record.service.length > 12 ? record.service.substring(0, 12) + '...' : record.service) : 'N/A'}
                                            </small>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted d-block" style="font-size: 0.7rem;"><i class="bi bi-tools me-1"></i>Appliance:</small>
                                            <small class="fw-medium" style="font-size: 0.75rem;" title="${record.appliance_type || 'N/A'}">
                                                ${record.appliance_type ? (record.appliance_type.length > 12 ? record.appliance_type.substring(0, 12) + '...' : record.appliance_type) : 'N/A'}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                rowContainer.append(cardColumn);
            });
            
            container.append(rowContainer);
        }
        
        // Initial mobile cards render with pagination limit
        const initialPageData = salesData.slice(0, 10);
        renderMobileCards(initialPageData);
        
        // Update mobile cards when table is redrawn
        table.on('draw', function() {
            const visibleData = table.rows({ page: 'current' }).data().toArray();
            renderMobileCards(visibleData);
            renderMobilePagination();
        });
        
        // Update mobile cards when page length changes
        table.on('length.dt', function(e, settings, len) {
            const visibleData = table.rows({ page: 'current' }).data().toArray();
            renderMobileCards(visibleData);
            renderMobilePagination();
        });
        
        // Mobile pagination rendering function
        function renderMobilePagination() {
            const paginationContainer = $('#mobilePaginationContainer');
            const info = table.page.info();
            
            if (info.pages <= 1) {
                paginationContainer.empty();
                return;
            }
            
            let paginationHtml = `
                <div class="mobile-dataTables-wrapper" style="margin-top: 20px; text-align: center;">
                    <div class="mobile-dataTables-paginate">
            `;
            
            // Previous button
            paginationHtml += `
                <a class="mobile-paginate-button previous ${info.page === 0 ? 'disabled' : ''}" href="#" data-page="${info.page - 1}">
                    ‹ Previous
                </a>
            `;
            
            // Page numbers (show current page and adjacent pages)
            const startPage = Math.max(0, info.page - 1);
            const endPage = Math.min(info.pages - 1, info.page + 1);
            
            if (startPage > 0) {
                paginationHtml += `
                    <a class="mobile-paginate-button" href="#" data-page="0">1</a>
                `;
                if (startPage > 1) {
                    paginationHtml += `<span class="mobile-paginate-button disabled">...</span>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += `
                    <a class="mobile-paginate-button ${i === info.page ? 'current' : ''}" href="#" data-page="${i}">${i + 1}</a>
                `;
            }
            
            if (endPage < info.pages - 1) {
                if (endPage < info.pages - 2) {
                    paginationHtml += `<span class="mobile-paginate-button disabled">...</span>`;
                }
                paginationHtml += `
                    <a class="mobile-paginate-button" href="#" data-page="${info.pages - 1}">${info.pages}</a>
                `;
            }
            
            // Next button
            paginationHtml += `
                <a class="mobile-paginate-button next ${info.page === info.pages - 1 ? 'disabled' : ''}" href="#" data-page="${info.page + 1}">
                    Next ›
                </a>
            `;
            
            paginationHtml += `
                    </div>
                </div>
            `;
            
            paginationContainer.html(paginationHtml);
            
            // Add click handlers for pagination links
            paginationContainer.find('.mobile-paginate-button').on('click', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                if (page !== undefined && !$(this).hasClass('disabled') && !$(this).hasClass('current')) {
                    table.page(page).draw('page');
                }
            });
        }
        
        // Initial mobile pagination render
        renderMobilePagination();

        // Function to update summary statistics
        function updateSummaryStats(data) {
            var totalSales = 0;
            var totalTransactions = data.length;
            var serviceSummary = {};
            
            data.forEach(function(item) {
                totalSales += parseFloat(item.price) || 0;
                
                // Count services
                if (serviceSummary[item.service]) {
                    serviceSummary[item.service].count++;
                    serviceSummary[item.service].total += parseFloat(item.price) || 0;
                } else {
                    serviceSummary[item.service] = {
                        count: 1,
                        total: parseFloat(item.price) || 0
                    };
                }
            });
            
            // Update summary display (if summary elements exist)
            if ($('#totalSales').length) {
                $('#totalSales').text('₱' + new Intl.NumberFormat('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(totalSales));
            }
            
            if ($('#totalTransactions').length) {
                $('#totalTransactions').text(totalTransactions);
            }
            
            console.log('Summary Stats Updated:', {
                totalSales: totalSales,
                totalTransactions: totalTransactions,
                serviceSummary: serviceSummary
            });
        }

        // Initialize summary stats with all data
        updateSummaryStats(salesData);

       // Enhanced Print functionality with totals and summary
$('#printButton').on('click', function () {
    // Create a new window with proper settings
    var printWindow = window.open('', '_blank', 'width=900,height=700,scrollbars=yes,resizable=yes');
    
    // Check if popup was blocked
    if (!printWindow) {
        alert('Please allow popups for this site to print the sales report.');
        return;
    }

    // Get filtered data for calculations
    var filteredData = [];
    table.rows({ filter: 'applied' }).every(function () {
        filteredData.push(this.data());
    });

    // Calculate totals and summary
    var totalSales = 0;
    var serviceSummary = {};
    var totalTransactions = filteredData.length;

    filteredData.forEach(function(item) {
        totalSales += item.price;

        // Count services
        if (serviceSummary[item.service]) {
            serviceSummary[item.service].count++;
            serviceSummary[item.service].total += item.price;
        } else {
            serviceSummary[item.service] = {
                count: 1,
                total: item.price
            };
        }
    });

    // Format currency function
    function formatCurrency(amount) {
        return '₱' + new Intl.NumberFormat('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount || 0);
    }

    // Start building the print document
    printWindow.document.write('<html><head><title>Sales Report</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
    printWindow.document.write('table { border-collapse: collapse; width: 100%; margin-top: 20px; }');
    printWindow.document.write('th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }');
    printWindow.document.write('th { background-color: #f2f2f2; font-weight: bold; }');
    printWindow.document.write('h1 { text-align: center; margin-bottom: 20px; color: #333; }');
    printWindow.document.write('h2 { color: #555; margin-top: 30px; margin-bottom: 15px; }');
    printWindow.document.write('.summary-container { background-color: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 5px; }');
    printWindow.document.write('.summary-item { margin: 10px 0; font-size: 16px; }');
    printWindow.document.write('.total-sales { font-size: 20px; color: #007bff; }');
    printWindow.document.write('.service-summary { margin-top: 20px; }');
    printWindow.document.write('.service-item { padding: 8px 0; border-bottom: 1px solid #eee; }');
    printWindow.document.write('.print-date { text-align: right; margin-bottom: 20px; font-style: italic; color: #666; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');

    // Header
    printWindow.document.write('<h2 style="text-align:center; font-size:18px; margin:0 0 6px 0; font-weight:600;">Sales Report</h2>');
    printWindow.document.write('<h1 style="font-size:22px; margin:0; text-align:center; font-weight:700; letter-spacing:0.5px;">HVAC AIR CONDITIONING AND REFRIGERATION SERVICES</h1>');
    printWindow.document.write('<div style="text-align:center; font-size:13px; margin-top:2px;">Purok 16-A, Ising, Carmen, Davao Del Norte</div>');
    printWindow.document.write('<hr style="border-top:2px solid #000; margin:12px 0;">');
    printWindow.document.write('<div class="print-date">Generated on: ' + new Date().toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }) + '</div>');

    // Sales Summary Section
    printWindow.document.write('<div class="summary-container">');
    printWindow.document.write('<h2>Sales Summary</h2>');
    printWindow.document.write('<div class="summary-item total-sales">Total Sales: ' + formatCurrency(totalSales) + '</div>');
    printWindow.document.write('<div class="summary-item">Total Transactions: ' + totalTransactions + '</div>');
    printWindow.document.write('<div class="summary-item">Average Transaction: ' + formatCurrency(totalTransactions > 0 ? totalSales / totalTransactions : 0) + '</div>');

    // Service breakdown
    printWindow.document.write('<div class="service-summary">');
    printWindow.document.write('<h3>Service Breakdown:</h3>');
    Object.keys(serviceSummary).forEach(function(service) {
        var serviceData = serviceSummary[service];
        var percentage = totalSales > 0 ? ((serviceData.total / totalSales) * 100).toFixed(1) : 0;
        printWindow.document.write('<div class="service-item">');
        printWindow.document.write('<strong>' + service + ':</strong> ' + serviceData.count + ' transactions, ');
        printWindow.document.write(formatCurrency(serviceData.total) + ' (' + percentage + '% of total sales)');
        printWindow.document.write('</div>');
    });
    printWindow.document.write('</div>');
    printWindow.document.write('</div>');

    // Detailed Transaction Table
    printWindow.document.write('<h2>Detailed Transactions</h2>');
    printWindow.document.write('<table>');

    // Get table header
    printWindow.document.write('<thead>');
    $('#salesReport thead tr').each(function () {
        printWindow.document.write('<tr>');
        $(this).find('th').each(function () {
            printWindow.document.write('<th>' + $(this).text() + '</th>');
        });
        printWindow.document.write('</tr>');
    });
    printWindow.document.write('</thead>');

    // Get visible table data (respects current filtering and pagination)
    printWindow.document.write('<tbody>');
    table.rows({ filter: 'applied' }).every(function () {
        var data = this.data();
        printWindow.document.write('<tr>');
        printWindow.document.write('<td>' + data.id + '</td>');
        printWindow.document.write('<td>' + (data.customer_name || 'N/A') + '</td>');
        printWindow.document.write('<td>' + (data.service || 'N/A') + '</td>');
        printWindow.document.write('<td>' + (data.appliance_type || 'N/A') + '</td>');
        printWindow.document.write('<td>' + formatCurrency(data.price) + '</td>');
        printWindow.document.write('<td>' + (data.date ? new Date(data.date).toLocaleDateString('en-PH', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        }) : 'N/A') + '</td>');
        printWindow.document.write('</tr>');
    });
    // Add totals row to table
    printWindow.document.write('<tr style="background-color: #e9ecef; font-weight: bold;">');
    printWindow.document.write('<td colspan="4" style="text-align: right;">TOTAL:</td>');
    printWindow.document.write('<td>' + formatCurrency(totalSales) + '</td>');
    printWindow.document.write('<td>' + totalTransactions + ' transactions</td>');
    printWindow.document.write('</tr>');
    printWindow.document.write('</tbody>');



    printWindow.document.write('</table>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    
    // Use a more reliable approach to handle printing
    var printContent = printWindow.document.documentElement.outerHTML;
    
    // Focus the window and wait for it to be ready
    printWindow.focus();
    
    // Use setTimeout to ensure the document is fully loaded
    setTimeout(function() {
        try {
            printWindow.print();
            
            // Close the window after printing (or when user cancels)
            printWindow.onafterprint = function() {
                setTimeout(function() {
                    if (!printWindow.closed) {
                        printWindow.close();
                    }
                }, 100);
            };
            
            // Fallback: close window after a reasonable time if onafterprint doesn't fire
            setTimeout(function() {
                if (!printWindow.closed) {
                    printWindow.close();
                }
            }, 5000);
            
        } catch (e) {
            console.error('Print error:', e);
            alert('Print failed. Please try again or check your browser settings.');
            if (!printWindow.closed) {
                printWindow.close();
            }
        }
    }, 500);

});
    });
</script>
<?php
// --- PHP DATA AGGREGATION FOR CHARTS ---
include_once __DIR__ . '/../../config/ini.php';
$pdo = pdo_init();


// 2. Service Type Distribution by Year
$serviceTypeDistByYear = [];
$stmt = $pdo->query("SELECT YEAR(a.app_schedule) as year, s.service_type_name, COUNT(*) as cnt FROM appointment a JOIN service_type s ON a.service_type_id = s.service_type_id WHERE a.app_price IS NOT NULL AND a.app_price != '' AND a.app_status_id = 3 GROUP BY YEAR(a.app_schedule), s.service_type_name ORDER BY year, s.service_type_name");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $serviceTypeDistByYear[$row['year']][$row['service_type_name']] = (int)$row['cnt'];
}

// 3. Appliance Type Distribution by Year
$applianceTypeDistByYear = [];
$stmt = $pdo->query("SELECT YEAR(a.app_schedule) as year, at.appliances_type_name, COUNT(*) as cnt FROM appointment a JOIN service_type_appliances sta ON a.service_type_id = sta.service_type_id JOIN appliances_type at ON sta.appliances_type_id = at.appliances_type_id WHERE a.app_price IS NOT NULL AND a.app_price != '' GROUP BY YEAR(a.app_schedule), at.appliances_type_name ORDER BY year, at.appliances_type_name");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $applianceTypeDistByYear[$row['year']][$row['appliances_type_name']] = (int)$row['cnt'];
}

// 4. Technician Performance by Year and Month
$techPerfByYear = [];
$techPerfByMonth = [];
$allTechnicians = [];
$availableYears = [];

// First, get all technicians (users who have been assigned as technicians in appointments)
$stmt = $pdo->query("SELECT DISTINCT u.user_name FROM user u JOIN appointment a ON u.user_id = a.user_technician ORDER BY u.user_name");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $allTechnicians[] = $row['user_name'];
}

// Get performance data by year
$stmt = $pdo->query("SELECT YEAR(a.app_schedule) as year, u.user_name, COUNT(*) as cnt FROM appointment a JOIN user u ON a.user_technician = u.user_id WHERE a.app_status_id = 3 AND a.app_price IS NOT NULL AND a.app_price != '' GROUP BY YEAR(a.app_schedule), u.user_name ORDER BY year, u.user_name");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $techPerfByYear[$row['year']][$row['user_name']] = (int)$row['cnt'];
    if (!in_array($row['year'], $availableYears)) {
        $availableYears[] = $row['year'];
    }
}

// Get performance data by year and month
$stmt = $pdo->query("SELECT YEAR(a.app_schedule) as year, MONTH(a.app_schedule) as month, u.user_name, COUNT(*) as cnt FROM appointment a JOIN user u ON a.user_technician = u.user_id WHERE a.app_status_id = 3 AND a.app_price IS NOT NULL AND a.app_price != '' GROUP BY YEAR(a.app_schedule), MONTH(a.app_schedule), u.user_name ORDER BY year, month, u.user_name");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $techPerfByMonth[$row['year']][$row['month']][$row['user_name']] = (int)$row['cnt'];
}

sort($availableYears);

// 5. Customer Engagement Levels by Year and Month
$engagementByYear = [];
$engagementByMonth = [];

// Get engagement metrics by year: rating distribution, feedback rate, and satisfaction levels
$stmt = $pdo->query("
    SELECT 
        YEAR(app_schedule) as year,
        COUNT(*) as total_appointments,
        COUNT(CASE WHEN app_rating > 0 THEN 1 END) as rated_appointments,
        COUNT(CASE WHEN app_comment != '' AND app_comment != 'No Comment' THEN 1 END) as feedback_appointments,
        AVG(CASE WHEN app_rating > 0 THEN app_rating ELSE NULL END) as avg_rating,
        COUNT(CASE WHEN app_rating >= 4 THEN 1 END) as high_satisfaction,
        COUNT(CASE WHEN app_rating >= 3 AND app_rating < 4 THEN 1 END) as medium_satisfaction,
        COUNT(CASE WHEN app_rating > 0 AND app_rating < 3 THEN 1 END) as low_satisfaction,
        COUNT(CASE WHEN app_rating = 0 THEN 1 END) as no_rating
    FROM appointment 
    WHERE app_status_id = 3 
    GROUP BY YEAR(app_schedule) 
    ORDER BY year
");

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $year = $row['year'];
    $engagementByYear[$year] = [
        'total_appointments' => (int)$row['total_appointments'],
        'rated_appointments' => (int)$row['rated_appointments'],
        'feedback_appointments' => (int)$row['feedback_appointments'],
        'avg_rating' => round((float)$row['avg_rating'], 2),
        'high_satisfaction' => (int)$row['high_satisfaction'],
        'medium_satisfaction' => (int)$row['medium_satisfaction'],
        'low_satisfaction' => (int)$row['low_satisfaction'],
        'no_rating' => (int)$row['no_rating'],
        'engagement_rate' => $row['total_appointments'] > 0 ? round(($row['rated_appointments'] / $row['total_appointments']) * 100, 1) : 0,
        'feedback_rate' => $row['total_appointments'] > 0 ? round(($row['feedback_appointments'] / $row['total_appointments']) * 100, 1) : 0
    ];
}

// Get engagement metrics by year and month for detailed filtering
$stmt = $pdo->query("
    SELECT 
        YEAR(app_schedule) as year,
        MONTH(app_schedule) as month,
        COUNT(*) as total_appointments,
        COUNT(CASE WHEN app_rating > 0 THEN 1 END) as rated_appointments,
        COUNT(CASE WHEN app_comment != '' AND app_comment != 'No Comment' THEN 1 END) as feedback_appointments,
        AVG(CASE WHEN app_rating > 0 THEN app_rating ELSE NULL END) as avg_rating,
        COUNT(CASE WHEN app_rating >= 4 THEN 1 END) as high_satisfaction,
        COUNT(CASE WHEN app_rating >= 3 AND app_rating < 4 THEN 1 END) as medium_satisfaction,
        COUNT(CASE WHEN app_rating > 0 AND app_rating < 3 THEN 1 END) as low_satisfaction,
        COUNT(CASE WHEN app_rating = 0 THEN 1 END) as no_rating
    FROM appointment 
    WHERE app_status_id = 3 
    GROUP BY YEAR(app_schedule), MONTH(app_schedule) 
    ORDER BY year, month
");

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $year = $row['year'];
    $month = $row['month'];
    $engagementByMonth[$year][$month] = [
        'total_appointments' => (int)$row['total_appointments'],
        'rated_appointments' => (int)$row['rated_appointments'],
        'feedback_appointments' => (int)$row['feedback_appointments'],
        'avg_rating' => round((float)$row['avg_rating'], 2),
        'high_satisfaction' => (int)$row['high_satisfaction'],
        'medium_satisfaction' => (int)$row['medium_satisfaction'],
        'low_satisfaction' => (int)$row['low_satisfaction'],
        'no_rating' => (int)$row['no_rating'],
        'engagement_rate' => $row['total_appointments'] > 0 ? round(($row['rated_appointments'] / $row['total_appointments']) * 100, 1) : 0,
        'feedback_rate' => $row['total_appointments'] > 0 ? round(($row['feedback_appointments'] / $row['total_appointments']) * 100, 1) : 0
    ];
}


// 8. Customer Lifetime Value Analysis Data
$customerLifetimeValue = [];
$stmt = $pdo->query("
    SELECT 
        u.user_id,
        CONCAT(u.user_name, ' ', COALESCE(u.user_midname, ''), ' ', u.user_lastname) as customer_name,
        COUNT(a.app_id) as total_appointments,
        SUM(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' AND a.app_price REGEXP '^[0-9]+([.][0-9]+)?$' 
            THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as total_spent,
        AVG(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' AND a.app_price REGEXP '^[0-9]+([.][0-9]+)?$' 
            THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as avg_order_value,
        MIN(a.app_created) as first_appointment,
        MAX(a.app_created) as last_appointment,
        AVG(a.app_rating) as avg_rating,
        COUNT(CASE WHEN a.payment_status = 'Paid' THEN 1 END) as paid_appointments,
        YEAR(MIN(a.app_created)) as customer_since_year,
        DATEDIFF(CURDATE(), MIN(a.app_created)) as customer_age_days
    FROM user u
    JOIN appointment a ON u.user_id = a.user_id
    JOIN user_type ut ON u.user_type_id = ut.user_type_id
    WHERE ut.user_type_name = 'Customer' 
        AND a.app_status_id = 3
        AND a.payment_status = 'Paid'
        AND a.app_price IS NOT NULL 
        AND a.app_price != ''
        AND a.app_price REGEXP '^[0-9]+([.][0-9]+)?$'
    GROUP BY u.user_id
    HAVING total_appointments > 0
    ORDER BY total_spent DESC
");

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $customerLifetimeValue[] = [
        'user_id' => (int)$row['user_id'],
        'customer_name' => trim($row['customer_name']),
        'total_appointments' => (int)$row['total_appointments'],
        'total_spent' => (float)$row['total_spent'],
        'avg_order_value' => (float)$row['avg_order_value'],
        'first_appointment' => $row['first_appointment'],
        'last_appointment' => $row['last_appointment'],
        'avg_rating' => round((float)$row['avg_rating'], 2),
        'paid_appointments' => (int)$row['paid_appointments'],
        'customer_since_year' => (int)$row['customer_since_year'],
        'customer_age_days' => (int)$row['customer_age_days'],
        'payment_rate' => $row['total_appointments'] > 0 ? round(($row['paid_appointments'] / $row['total_appointments']) * 100, 1) : 0
    ];
}

// CLV Summary Statistics
$clvSummary = [];
if (!empty($customerLifetimeValue)) {
    $totalCustomers = count($customerLifetimeValue);
    $totalRevenue = array_sum(array_column($customerLifetimeValue, 'total_spent'));
    $avgCLV = $totalCustomers > 0 ? $totalRevenue / $totalCustomers : 0;
    $topCustomers = array_slice($customerLifetimeValue, 0, 10); // Top 10 customers by spending
    
    // Customer segmentation
    $highValueCustomers = array_filter($customerLifetimeValue, function($customer) use ($avgCLV) {
        return $customer['total_spent'] > ($avgCLV * 1.5);
    });
    
    $loyalCustomers = array_filter($customerLifetimeValue, function($customer) {
        return $customer['total_appointments'] >= 3;
    });
    
    $clvSummary = [
        'total_customers' => $totalCustomers,
        'total_revenue' => $totalRevenue,
        'avg_clv' => $avgCLV,
        'high_value_customers' => count($highValueCustomers),
        'loyal_customers' => count($loyalCustomers),
        'top_customers' => $topCustomers
    ];
}

// 9. Geographic/Regional Sales Insights Data - Using Historical Transaction Addresses
$geographicSales = [];
$stmt = $pdo->query("
    SELECT 
        COALESCE(ata.municipality_city, u.municipality_city) as region,
        COALESCE(ata.province, u.province) as province,
        COALESCE(ata.barangay, u.barangay) as barangay,
        COUNT(a.app_id) as total_appointments,
        COUNT(CASE WHEN a.app_status_id = 3 THEN 1 END) as completed_appointments,
        COUNT(CASE WHEN a.app_status_id = 3 AND a.payment_status = 'Paid' THEN 1 END) as paid_appointments,
        COUNT(DISTINCT a.user_id) as total_customers,
        SUM(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' AND a.app_price REGEXP '^[0-9]+([.][0-9]+)?$' 
            THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as total_revenue,
        AVG(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' AND a.app_price REGEXP '^[0-9]+([.][0-9]+)?$' 
            THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as avg_revenue_per_job,
        AVG(a.app_rating) as avg_rating,
        YEAR(a.app_created) as year,
        MONTH(a.app_created) as month
    FROM appointment a
    JOIN user u ON a.user_id = u.user_id
    LEFT JOIN appointment_transaction_address ata ON a.app_id = ata.app_id
    WHERE a.app_status_id = 3 
        AND a.payment_status = 'Paid'
        AND a.app_price IS NOT NULL 
        AND a.app_price != ''
        AND a.app_price REGEXP '^[0-9]+([.][0-9]+)?$'
        AND COALESCE(ata.municipality_city, u.municipality_city) IS NOT NULL 
        AND COALESCE(ata.municipality_city, u.municipality_city) != ''
    GROUP BY COALESCE(ata.municipality_city, u.municipality_city), 
             COALESCE(ata.province, u.province), 
             COALESCE(ata.barangay, u.barangay), 
             YEAR(a.app_created), 
             MONTH(a.app_created)
    ORDER BY year DESC, month DESC, total_revenue DESC
");

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $geographicSales[] = [
        'region' => $row['region'],
        'province' => $row['province'],
        'barangay' => $row['barangay'],
        'total_appointments' => (int)$row['total_appointments'],
        'completed_appointments' => (int)$row['completed_appointments'],
        'paid_appointments' => (int)$row['paid_appointments'],
        'total_customers' => (int)$row['total_customers'],
        'total_revenue' => (float)$row['total_revenue'],
        'avg_revenue_per_job' => (float)$row['avg_revenue_per_job'],
        'avg_rating' => round((float)$row['avg_rating'], 2),
        'completion_rate' => $row['total_appointments'] > 0 ? 
            round(($row['completed_appointments'] / $row['total_appointments']) * 100, 1) : 0,
        'payment_rate' => $row['completed_appointments'] > 0 ? 
            round(($row['paid_appointments'] / $row['completed_appointments']) * 100, 1) : 0,
        'year' => (int)$row['year'],
        'month' => (int)$row['month']
    ];
}

// Geographic Sales Summary - Using Historical Transaction Addresses
$geographicSummary = [];
$stmt = $pdo->query("
    SELECT 
        COALESCE(ata.municipality_city, u.municipality_city) as region,
        COUNT(DISTINCT a.user_id) as total_customers,
        COUNT(a.app_id) as total_jobs,
        SUM(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' AND a.app_price REGEXP '^[0-9]+([.][0-9]+)?$' 
            THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as total_revenue,
        AVG(CASE WHEN a.app_price IS NOT NULL AND a.app_price != '' AND a.app_price REGEXP '^[0-9]+([.][0-9]+)?$' 
            THEN CAST(a.app_price AS DECIMAL(10,2)) ELSE 0 END) as avg_job_value
    FROM appointment a
    JOIN user u ON a.user_id = u.user_id
    LEFT JOIN appointment_transaction_address ata ON a.app_id = ata.app_id
    WHERE a.app_status_id = 3 
        AND a.payment_status = 'Paid'
        AND COALESCE(ata.municipality_city, u.municipality_city) IS NOT NULL 
        AND COALESCE(ata.municipality_city, u.municipality_city) != ''
    GROUP BY COALESCE(ata.municipality_city, u.municipality_city)
    ORDER BY total_revenue DESC
");

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $geographicSummary[$row['region']] = [
        'total_customers' => (int)$row['total_customers'],
        'total_jobs' => (int)$row['total_jobs'],
        'total_revenue' => (float)$row['total_revenue'],
        'avg_job_value' => (float)$row['avg_job_value']
    ];
}

// 11. Sales Report Data - Get completed and paid appointments
$salesReportData = [];
$stmt = $pdo->query("
    SELECT 
        a.app_id,
        s.service_type_name as service,
        CAST(a.app_price AS DECIMAL(10,2)) as price,
        DATE_FORMAT(a.app_schedule, '%Y-%m-%d') as date,
        CONCAT(u.user_name, ' ', COALESCE(u.user_midname, ''), ' ', u.user_lastname) as customer_name,
        COALESCE(ap.appliances_type_name, 'N/A') as appliance_type,
        a.payment_status,
        a.app_created as transaction_date
    FROM appointment a
    JOIN service_type s ON a.service_type_id = s.service_type_id
    JOIN user u ON a.user_id = u.user_id
    LEFT JOIN appliances_type ap ON a.appliances_type_id = ap.appliances_type_id
    WHERE a.app_status_id = 3 
        AND a.payment_status = 'Paid'
        AND a.app_price IS NOT NULL 
        AND a.app_price != '' 
        AND a.app_price REGEXP '^[0-9]+([.][0-9]+)?$'
    ORDER BY a.app_schedule DESC
");

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $salesReportData[] = [
        'id' => (int)$row['app_id'],
        'service' => $row['service'],
        'price' => (float)$row['price'],
        'date' => $row['date'],
        'customer_name' => trim($row['customer_name']),
        'appliance_type' => trim($row['appliance_type']),
        'payment_status' => $row['payment_status'],
        'transaction_date' => $row['transaction_date']
    ];
}
?>
<!-- Add Chart Containers Below the Table -->
<script>
// --- PHP DATA TO JS ---
const serviceTypeDistByYear = <?php echo json_encode($serviceTypeDistByYear); ?>;
const applianceTypeDistByYear = <?php echo json_encode($applianceTypeDistByYear); ?>;
const techPerfByYear = <?php echo json_encode($techPerfByYear); ?>;
const techPerfByMonth = <?php echo json_encode($techPerfByMonth); ?>;
const allTechnicians = <?php echo json_encode($allTechnicians); ?>;
const availableYears = <?php echo json_encode($availableYears); ?>;
const engagementByYear = <?php echo json_encode($engagementByYear); ?>;
const engagementByMonth = <?php echo json_encode($engagementByMonth); ?>;
const customerLifetimeValue = <?php echo json_encode($customerLifetimeValue); ?>;
const clvSummary = <?php echo json_encode($clvSummary); ?>;
const geographicSales = <?php echo json_encode($geographicSales); ?>;
const geographicSummary = <?php echo json_encode($geographicSummary); ?>;
const salesData = <?php echo json_encode($salesReportData); ?>;

// Chart variables
let technicianChart, engagementChart, clvChart, geographicChart;

// Chart contexts
const technicianPerformanceCtx = document.getElementById('technicianPerformanceChart').getContext('2d');
const engagementLevelsCtx = document.getElementById('engagementLevelsChart').getContext('2d');
const clvAnalysisCtx = document.getElementById('clvAnalysisChart').getContext('2d');
const geographicAnalysisCtx = document.getElementById('geographicAnalysisChart').getContext('2d');




// Function to update Technician Performance chart
function updateTechnicianPerformanceChart(selectedYear = '', selectedMonth = '') {
    let technicianData = {};
    let chartTitle = 'Technician Performance';
    
    // Initialize all technicians with zero values
    allTechnicians.forEach(technician => {
        technicianData[technician] = 0;
    });
    
    console.log('Filtering technician data:', { selectedYear, selectedMonth });
    
    if (selectedYear && selectedMonth) {
        // Filter by specific year and month
        const monthData = techPerfByMonth[selectedYear] && techPerfByMonth[selectedYear][selectedMonth] || {};
        Object.entries(monthData).forEach(([technician, count]) => {
            if (technicianData.hasOwnProperty(technician)) {
                technicianData[technician] = count;
            }
        });
        
        // Update chart title
        const monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 
                           'July', 'August', 'September', 'October', 'November', 'December'];
        chartTitle = `Technician Performance - ${monthNames[parseInt(selectedMonth)]} ${selectedYear}`;
        
    } else if (selectedYear) {
        // Filter by year only
        const yearData = techPerfByYear[selectedYear] || {};
        Object.entries(yearData).forEach(([technician, count]) => {
            if (technicianData.hasOwnProperty(technician)) {
                technicianData[technician] = count;
            }
        });
        
        chartTitle = `Technician Performance - ${selectedYear}`;
        
    } else if (selectedMonth) {
        // Filter by month across all years
        Object.keys(techPerfByMonth).forEach(year => {
            const monthData = techPerfByMonth[year][selectedMonth] || {};
            Object.entries(monthData).forEach(([technician, count]) => {
                if (technicianData.hasOwnProperty(technician)) {
                    technicianData[technician] += count;
                }
            });
        });
        
        const monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 
                           'July', 'August', 'September', 'October', 'November', 'December'];
        chartTitle = `Technician Performance - ${monthNames[parseInt(selectedMonth)]} (All Years)`;
        
    } else {
        // Aggregate all years and months
        Object.values(techPerfByYear).forEach(yearData => {
            Object.entries(yearData).forEach(([technician, count]) => {
                if (technicianData.hasOwnProperty(technician)) {
                    technicianData[technician] += count;
                }
            });
        });
        
        chartTitle = 'Technician Performance - All Time';
    }
    
    console.log('Filtered technician data:', technicianData);
    
    // Sort technicians by performance (descending) - show all technicians including those with 0 appointments
    const sortedTechnicians = Object.entries(technicianData)
        .sort((a, b) => b[1] - a[1]); // Show all technicians
    
    const labels = sortedTechnicians.map(([name, count]) => name);
    const data = sortedTechnicians.map(([name, count]) => count);
    
    // Generate unique colors for each technician
    const uniqueColors = [
        '#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8',
        '#6f42c1', '#fd7e14', '#20c997', '#e83e8c', '#6c757d',
        '#343a40', '#495057', '#0056b3', '#1e7e34', '#721c24',
        '#856404', '#0c5460', '#59359a', '#b35205', '#1a6e5c',
        '#a71e69', '#495057', '#004085', '#155724', '#58151c'
    ];
    
    const colors = labels.map((label, index) => uniqueColors[index % uniqueColors.length]);
    
    // Destroy existing chart if it exists
    if (technicianChart) {
        technicianChart.destroy();
    }
    
    // Create new chart
    technicianChart = new Chart(technicianPerformanceCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Completed Appointments',
                data: data,
                backgroundColor: colors,
                borderColor: colors.map(color => color),
                borderWidth: 2,
                borderRadius: {
                    topLeft: 8,
                    topRight: 8,
                    bottomLeft: 0,
                    bottomRight: 0
                },
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        title: function(context) {
                            return 'Technician: ' + context[0].label;
                        },
                        label: function(context) {
                            const maxValue = Math.max(...data);
                            const percentage = maxValue > 0 ? ((context.parsed.y / maxValue) * 100).toFixed(1) : '0';
                            return [
                                'Completed Jobs: ' + context.parsed.y,
                                'Performance: ' + percentage + '% of top performer'
                            ];
                        },
                        afterLabel: function(context) {
                            const value = context.parsed.y;
                            const maxValue = Math.max(...data);
                            if (value === 0) return '📋 No Appointments';
                            if (value >= maxValue * 0.8) return '🏆 Top Performer';
                            if (value >= maxValue * 0.6) return '⭐ Good Performance';
                            if (value >= maxValue * 0.4) return '📈 Average Performance';
                            if (value >= maxValue * 0.2) return '📊 Below Average';
                            return '🔄 Needs Improvement';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.08)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        padding: 8,
                        callback: function(value) {
                            return value + ' jobs';
                        }
                    },
                    title: {
                        display: true,
                        text: chartTitle,
                        font: {
                            size: 13,
                            weight: 'bold'
                        },
                        padding: 15
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        },
                        maxRotation: 45,
                        minRotation: 0
                    },
                    title: {
                        display: true,
                        text: 'Top Performing Technicians',
                        font: {
                            size: 13,
                            weight: 'bold'
                        },
                        padding: 15
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });
}

// Function to update Customer Engagement Levels chart with enhanced visualization
function updateEngagementLevelsChart(selectedYear = '', selectedMonth = '') {
    let engagementData = {};
    let chartTitle = 'Customer Engagement Levels';
    let labels = [];
    
    console.log('Filtering engagement data:', { selectedYear, selectedMonth });
    
    if (selectedYear && selectedMonth) {
        // Filter by specific year and month
        const monthData = engagementByMonth[selectedYear] && engagementByMonth[selectedYear][selectedMonth];
        if (monthData) {
            const monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 
                               'July', 'August', 'September', 'October', 'November', 'December'];
            const label = `${monthNames[parseInt(selectedMonth)]} ${selectedYear}`;
            engagementData[label] = monthData;
            chartTitle = `Customer Engagement - ${monthNames[parseInt(selectedMonth)]} ${selectedYear}`;
        }
    } else if (selectedYear) {
        // Filter by year only
        if (engagementByYear[selectedYear]) {
            engagementData[selectedYear] = engagementByYear[selectedYear];
            chartTitle = `Customer Engagement - ${selectedYear}`;
        }
    } else if (selectedMonth) {
        // Filter by month across all years
        const monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 
                           'July', 'August', 'September', 'October', 'November', 'December'];
        Object.keys(engagementByMonth).forEach(year => {
            const monthData = engagementByMonth[year][selectedMonth];
            if (monthData) {
                const label = `${monthNames[parseInt(selectedMonth)]} ${year}`;
                engagementData[label] = monthData;
            }
        });
        chartTitle = `Customer Engagement - ${monthNames[parseInt(selectedMonth)]} (All Years)`;
    } else {
        // Show all years
        engagementData = engagementByYear;
        chartTitle = 'Customer Engagement - All Time';
    }
    
    labels = Object.keys(engagementData).sort();
    
    // Extract data for visualization
    const engagementRates = labels.map(label => engagementData[label]?.engagement_rate || 0);
    const feedbackRates = labels.map(label => engagementData[label]?.feedback_rate || 0);
    const avgRatings = labels.map(label => engagementData[label]?.avg_rating || 0);
    const totalAppointments = labels.map(label => engagementData[label]?.total_appointments || 0);
    
    console.log('Engagement chart data:', { labels, engagementRates, feedbackRates, avgRatings });
    
    // Destroy existing chart if it exists
    if (engagementChart) {
        engagementChart.destroy();
    }
    
    engagementChart = new Chart(engagementLevelsCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Rating Engagement (%)',
                    data: engagementRates,
                    backgroundColor: '#007bff',
                    borderColor: '#007bff',
                    borderWidth: 2,
                    borderRadius: {
                        topLeft: 8,
                        topRight: 8,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    borderSkipped: false,
                    yAxisID: 'y'
                },
                {
                    label: 'Feedback Rate (%)',
                    data: feedbackRates,
                    backgroundColor: '#28a745',
                    borderColor: '#28a745',
                    borderWidth: 2,
                    borderRadius: {
                        topLeft: 8,
                        topRight: 8,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    borderSkipped: false,
                    yAxisID: 'y'
                },
                {
                    label: 'Average Rating (×20)',
                    data: avgRatings.map(rating => rating * 20),
                    backgroundColor: '#ffc107',
                    borderColor: '#ffc107',
                    borderWidth: 2,
                    borderRadius: {
                        topLeft: 8,
                        topRight: 8,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    borderSkipped: false,
                    yAxisID: 'y'
                },
                {
                    label: 'Total Appointments',
                    data: totalAppointments,
                    backgroundColor: '#fd7e14',
                    borderColor: '#fd7e14',
                    borderWidth: 2,
                    borderRadius: {
                        topLeft: 8,
                        topRight: 8,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    borderSkipped: false,
                    yAxisID: 'y2'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                title: {
                    display: true,
                    text: chartTitle,
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: 20
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        title: function(context) {
                            return 'Period: ' + context[0].label;
                        },
                        label: function(context) {
                            let value = context.parsed.y;
                            let suffix = '';
                            
                            if (context.dataset.label.includes('Rate (%)') || context.dataset.label.includes('Engagement (%)')) {
                                suffix = '%';
                            } else if (context.dataset.label.includes('Rating (×20)')) {
                                value = (value / 20).toFixed(1);
                                suffix = '/5 stars';
                            } else if (context.dataset.label.includes('Appointments')) {
                                suffix = ' appointments';
                            }
                            
                            return context.dataset.label.replace(' (×20)', '') + ': ' + value + suffix;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Time Period',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    max: 100,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    title: {
                        display: true,
                        text: 'Engagement Metrics (%)',
                        font: {
                            size: 11,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                y2: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false
                    },
                    title: {
                        display: true,
                        text: 'Appointment Count',
                        font: {
                            size: 11,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            return Math.round(value);
                        }
                    }
                }
            }
        }
    });
}


// Function to update Customer Lifetime Value Analysis chart
function updateCLVAnalysisChart(selectedYear = '', selectedMonth = '') {
    let filteredCustomers = customerLifetimeValue;
    let chartTitle = 'Customer Lifetime Value Analysis';
    
    if (selectedYear && selectedMonth) {
        // Filter customers who had appointments in the specific year and month
        const targetYear = parseInt(selectedYear);
        const targetMonth = parseInt(selectedMonth);
        
        filteredCustomers = customerLifetimeValue.filter(customer => {
            const firstAppDate = new Date(customer.first_appointment);
            const lastAppDate = new Date(customer.last_appointment);
            
            // Check if customer had any activity during the target year and month
            const firstYear = firstAppDate.getFullYear();
            const firstMonth = firstAppDate.getMonth() + 1;
            const lastYear = lastAppDate.getFullYear();
            const lastMonth = lastAppDate.getMonth() + 1;
            
            // Customer must have been active during the target year
            if (firstYear > targetYear || lastYear < targetYear) {
                return false;
            }
            
            // If customer started and ended in target year, check month range
            if (firstYear === targetYear && lastYear === targetYear) {
                return firstMonth <= targetMonth && targetMonth <= lastMonth;
            }
            
            // If customer started before target year and ended in/after target year
            if (firstYear < targetYear && lastYear >= targetYear) {
                return lastYear > targetYear || lastMonth >= targetMonth;
            }
            
            // If customer started in target year and ended after
            if (firstYear === targetYear && lastYear > targetYear) {
                return firstMonth <= targetMonth;
            }
            
            return false;
        });
        
        const monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 
                           'July', 'August', 'September', 'October', 'November', 'December'];
        chartTitle = `Customer Lifetime Value - ${monthNames[targetMonth]} ${selectedYear}`;
    } else if (selectedYear) {
        // Filter customers who had appointments in the selected year
        const targetYear = parseInt(selectedYear);
        filteredCustomers = customerLifetimeValue.filter(customer => {
            const firstYear = new Date(customer.first_appointment).getFullYear();
            const lastYear = new Date(customer.last_appointment).getFullYear();
            return firstYear <= targetYear && lastYear >= targetYear;
        });
        chartTitle = `Customer Lifetime Value - ${selectedYear}`;
    } else if (selectedMonth) {
        // Filter customers who had appointments in the selected month across all years
        const targetMonth = parseInt(selectedMonth);
        filteredCustomers = customerLifetimeValue.filter(customer => {
            const firstAppDate = new Date(customer.first_appointment);
            const lastAppDate = new Date(customer.last_appointment);
            const firstMonth = firstAppDate.getMonth() + 1;
            const lastMonth = lastAppDate.getMonth() + 1;
            const firstYear = firstAppDate.getFullYear();
            const lastYear = lastAppDate.getFullYear();
            
            // If same year, check if month range includes target
            if (firstYear === lastYear) {
                return firstMonth <= targetMonth && targetMonth <= lastMonth;
            }
            
            // If multiple years, customer likely active in target month
            // Check if first appointment month <= target OR last appointment month >= target
            return firstMonth <= targetMonth || lastMonth >= targetMonth;
        });
        
        const monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 
                           'July', 'August', 'September', 'October', 'November', 'December'];
        chartTitle = `Customer Lifetime Value - ${monthNames[targetMonth]} (All Years)`;
    } else {
        chartTitle = 'Customer Lifetime Value - All Time';
    }
    
    console.log('CLV Filter applied:', { 
        selectedYear, 
        selectedMonth, 
        totalCustomers: customerLifetimeValue.length,
        filteredCount: filteredCustomers.length,
        sampleFilteredCustomers: filteredCustomers.slice(0, 3).map(c => ({
            name: c.customer_name,
            firstApp: c.first_appointment,
            lastApp: c.last_appointment,
            totalSpent: c.total_spent
        }))
    });
    
    // Sort by total spent and show all customers
    const topCustomers = filteredCustomers
        .sort((a, b) => b.total_spent - a.total_spent);
    
    console.log('CLV Chart Data Debug:', {
        filteredCustomersCount: filteredCustomers.length,
        topCustomersCount: topCustomers.length,
        sampleTopCustomers: topCustomers.slice(0, 3).map(c => ({
            name: c.customer_name,
            spent: c.total_spent,
            appointments: c.total_appointments
        }))
    });
    
    // Handle case where no customers match the filter
    if (topCustomers.length === 0) {
        console.log('No customers found for selected filters');
        
        // Update CLV summary metrics with empty data
        updateCLVSummaryMetrics([]);
        
        // Destroy existing chart if it exists
        if (clvChart) {
            clvChart.destroy();
        }
        
        // Create empty chart with message
        clvChart = new Chart(clvAnalysisCtx, {
            type: 'bar',
            data: {
                labels: ['No Data'],
                datasets: [{
                    label: 'No customers found for selected period',
                    data: [0],
                    backgroundColor: 'rgba(200, 200, 200, 0.5)',
                    borderColor: 'rgba(200, 200, 200, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: chartTitle + ' - No Data Available',
                        font: { size: 16, weight: 'bold' },
                        padding: { top: 10, bottom: 20 }
                    },
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, max: 1 }
                }
            }
        });
        return;
    }
    
    // Prepare chart data with better customer name handling
    const customerNames = topCustomers.map(customer => {
        const name = customer.customer_name;
        // Split name and show first name + last initial for better readability
        const nameParts = name.trim().split(' ');
        if (nameParts.length >= 2) {
            return nameParts[0] + ' ' + nameParts[nameParts.length - 1].charAt(0) + '.';
        }
        return name.length > 12 ? name.substring(0, 12) + '...' : name;
    });
    
    const customerSpending = topCustomers.map(customer => customer.total_spent);
    const customerAppointments = topCustomers.map(customer => customer.total_appointments);
    
    // Calculate value per appointment for better insights
    const valuePerAppointment = topCustomers.map(customer => 
        customer.total_appointments > 0 ? (customer.total_spent / customer.total_appointments) : 0
    );
    
    // Update CLV summary metrics
    updateCLVSummaryMetrics(filteredCustomers);
    
    // Destroy existing chart if it exists
    if (clvChart) {
        clvChart.destroy();
    }
    
    // Create new chart with cleaner design
    clvChart = new Chart(clvAnalysisCtx, {
        type: 'bar',
        data: {
            labels: customerNames,
            datasets: [
                {
                    label: 'Total Revenue',
                    data: customerSpending,
                    backgroundColor: '#007bff',
                    borderColor: '#007bff',
                    borderWidth: 2,
                    borderRadius: {
                        topLeft: 8,
                        topRight: 8,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    borderSkipped: false
                },
                {
                    label: 'Appointments',
                    data: customerAppointments,
                    backgroundColor: '#28a745',
                    borderColor: '#28a745',
                    borderWidth: 2,
                    yAxisID: 'y2',
                    borderRadius: {
                        topLeft: 8,
                        topRight: 8,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    borderSkipped: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                title: {
                    display: true,
                    text: chartTitle,
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: {
                        top: 10,
                        bottom: 20
                    }
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#6f42c1',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        title: function(context) {
                            const index = context[0].dataIndex;
                            return topCustomers[index].customer_name;
                        },
                        label: function(context) {
                            const index = context.dataIndex;
                            const customer = topCustomers[index];
                            
                            if (context.dataset.label === 'Total Revenue') {
                                return [
                                    `Total Revenue: ₱${context.parsed.y.toLocaleString('en-PH', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    })}`,
                                    `Value per Visit: ₱${valuePerAppointment[index].toLocaleString('en-PH', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    })}`
                                ];
                            } else if (context.dataset.label === 'Appointments') {
                                return [
                                    `Total Appointments: ${context.parsed.y}`,
                                    `Avg Rating: ${(customer.avg_rating || 0).toFixed(1)}/5 stars`,
                                    `Customer Since: ${customer.customer_since_year}`
                                ];
                            }
                        },
                        afterBody: function(context) {
                            const index = context[0].dataIndex;
                            const customer = topCustomers[index];
                            return `Last Visit: ${new Date(customer.last_appointment).toLocaleDateString()}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'All Customers by Revenue',
                        font: {
                            size: 13,
                            weight: 'bold'
                        },
                        color: '#333'
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#666'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.08)',
                        lineWidth: 1
                    },
                    title: {
                        display: true,
                        text: 'Revenue (₱)',
                        font: {
                            size: 12,
                            weight: 'bold'
                        },
                        color: '#6f42c1'
                    },
                    ticks: {
                        font: {
                            size: 10
                        },
                        color: '#666',
                        callback: function(value) {
                            if (value >= 1000000) {
                                return '₱' + (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return '₱' + (value / 1000).toFixed(0) + 'K';
                            }
                            return '₱' + value.toLocaleString();
                        }
                    }
                },
                y2: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    max: function(context) {
                        const maxAppointments = Math.max(...customerAppointments);
                        return Math.ceil(maxAppointments * 1.1);
                    },
                    grid: {
                        drawOnChartArea: false
                    },
                    title: {
                        display: true,
                        text: 'Appointments',
                        font: {
                            size: 12,
                            weight: 'bold'
                        },
                        color: '#34a853'
                    },
                    ticks: {
                        font: {
                            size: 10
                        },
                        color: '#666',
                        stepSize: 1,
                        callback: function(value) {
                            return Math.round(value);
                        }
                    }
                }
            }
        }
    });
}

// Function to update CLV summary metrics
function updateCLVSummaryMetrics(customers) {
    if (!customers || customers.length === 0) {
        document.getElementById('totalCustomersCount').textContent = '0';
        document.getElementById('totalCustomersRevenue').textContent = '₱0.00';
        document.getElementById('avgCLVValue').textContent = '₱0.00';
        document.getElementById('highValueCustomersCount').textContent = '0';
        document.getElementById('loyalCustomersCount').textContent = '0';
        document.getElementById('topCustomerValue').textContent = '₱0.00';
        return;
    }
    
    const totalCustomers = customers.length;
    const totalRevenue = customers.reduce((sum, customer) => sum + customer.total_spent, 0);
    const avgCLV = totalCustomers > 0 ? totalRevenue / totalCustomers : 0;
    
    // High-value customers (above 1.5x average CLV)
    const highValueCustomers = customers.filter(customer => customer.total_spent > (avgCLV * 1.5));
    
    // Loyal customers (3+ appointments)
    const loyalCustomers = customers.filter(customer => customer.total_appointments >= 3);
    
    // Top customer value
    const topCustomerValue = customers.length > 0 ? Math.max(...customers.map(c => c.total_spent)) : 0;
    
    // Update UI elements
    document.getElementById('totalCustomersCount').textContent = totalCustomers;
    document.getElementById('totalCustomersRevenue').textContent = '₱' + totalRevenue.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    document.getElementById('avgCLVValue').textContent = '₱' + avgCLV.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    document.getElementById('highValueCustomersCount').textContent = highValueCustomers.length;
    document.getElementById('loyalCustomersCount').textContent = loyalCustomers.length;
    document.getElementById('topCustomerValue').textContent = '₱' + topCustomerValue.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Business Analytics Chart
let businessAnalyticsChart = null;

// Function to fetch and update the analytics chart
window.updateAnalyticsChart = async function() {
    try {
        const timeframe = document.getElementById('analyticsTimeframe')?.value || 
                         document.getElementById('analyticsTimeframeMobile')?.value || '30';
        const chartType = document.getElementById('analyticsChartType')?.value || 
                         document.getElementById('analyticsChartTypeMobile')?.value || 'appointments';
        const year = document.getElementById('analyticsYearFilter')?.value || 
                    document.getElementById('analyticsYearFilterMobile')?.value || new Date().getFullYear();
        
        console.log('Updating analytics with:', { timeframe, chartType });
        
        const apiUrl = `api/administrator/business_analytics.php?timeframe=${timeframe}&chart_type=${chartType}&year=${year}`;
        console.log('API URL:', apiUrl);
        
        const response = await fetch(apiUrl);
        const data = await response.json();
        
        console.log('Analytics API response:', data);
        
        if (data.success) {
            // Hide loading indicator
            const loadingElement = document.getElementById('analyticsLoading');
            if (loadingElement) {
                loadingElement.style.display = 'none';
            }
            
            // Update key metrics
            if (data.metrics) {
                const totalAppointments = document.getElementById('totalAppointments');
                const completionRate = document.getElementById('completionRate');
                const avgResponseTime = document.getElementById('avgResponseTime');
                const customerSatisfaction = document.getElementById('customerSatisfaction');
                
                if (totalAppointments) totalAppointments.textContent = data.metrics.total_appointments || '0';
                if (completionRate) completionRate.textContent = (data.metrics.completion_rate || '0') + '%';
                if (avgResponseTime) avgResponseTime.textContent = (data.metrics.avg_response_time || '0') + 'h';
                if (customerSatisfaction) customerSatisfaction.textContent = data.metrics.customer_satisfaction || '0.0';
            }
            
            // Update chart
            if (data.chart_data) {
                updateBusinessAnalyticsChart(data.chart_data, chartType);
            }
        } else {
            console.error('Error fetching analytics:', data.message);
            const loadingElement = document.getElementById('analyticsLoading');
            if (loadingElement) {
                loadingElement.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-exclamation-triangle fs-4"></i><br>
                        Error loading analytics
                    </div>
                `;
            }
        }
    } catch (error) {
        console.error('Error fetching business analytics:', error);
        const loadingElement = document.getElementById('analyticsLoading');
        if (loadingElement) {
            loadingElement.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="bi bi-wifi-off fs-4"></i><br>
                    Connection error
                </div>
            `;
        }
    }
}

// Function to update the analytics chart with data
function updateBusinessAnalyticsChart(chartData, chartType) {
    const ctx = document.getElementById('businessAnalyticsChart');
    if (!ctx) {
        console.error('Chart canvas not found');
        return;
    }
    
    // Destroy existing chart if it exists
    if (businessAnalyticsChart) {
        businessAnalyticsChart.destroy();
    }
    
    // Handle empty data
    const hasData = chartData.labels && chartData.labels.length > 0 && 
                   chartData.datasets && chartData.datasets.length > 0 &&
                   chartData.datasets.some(dataset => dataset.data && dataset.data.length > 0);
    
    let chartConfig = {
        data: {
            labels: hasData ? chartData.labels : ['No Data Available'],
            datasets: hasData ? chartData.datasets : [{
                label: 'No Data',
                data: [1],
                backgroundColor: ['#e9ecef'],
                borderColor: '#dee2e6',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                title: {
                    display: true,
                    text: chartType === 'revenue' ? 'Revenue & Payment Analytics' : 
                          chartType === 'appointments' ? 'Appointment Status Analytics' : 
                          chartType === 'service_types' ? 'Service Types Distribution' :
                          'Appliance Types Distribution'
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1
                }
            }
        }
    };
    
    if (chartType === 'service_types' || chartType === 'appliance_types') {
        // Doughnut chart for service types and appliance types distribution
        chartConfig.type = 'doughnut';
        chartConfig.options.plugins.legend.position = 'bottom';
        chartConfig.options.plugins.legend.labels = {
            usePointStyle: true,
            padding: 20,
            font: {
                size: 12
            }
        };
        chartConfig.options.scales = {};
    } else if (chartType === 'revenue') {
        // Dual-axis line chart for revenue analytics
        chartConfig.type = 'line';
        chartConfig.options.scales = {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Revenue (₱)'
                },
                ticks: {
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Payments'
                },
                grid: {
                    drawOnChartArea: false,
                },
            },
            x: {
                title: {
                    display: true,
                    text: 'Date'
                }
            }
        };
        
        // Assign different y-axes to different datasets
        if (chartData.datasets && chartData.datasets.length > 1) {
            chartData.datasets.forEach((dataset, index) => {
                if (dataset.label && (dataset.label.includes('Revenue') || dataset.label.includes('revenue'))) {
                    dataset.yAxisID = 'y';
                } else {
                    dataset.yAxisID = 'y1';
                }
            });
        }
    } else {
        // Multi-line chart for appointments with single y-axis
        chartConfig.type = 'line';
        chartConfig.options.scales = {
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
                    text: 'Date'
                }
            }
        };
    }
    
    businessAnalyticsChart = new Chart(ctx, chartConfig);
}

// Function to fetch financial overview
window.updateFinancialOverview = async function() {
    try {
        const response = await fetch(`api/administrator/financial_overview.php`);
        const data = await response.json();
        if (data.success) {
            // Update revenue displays
            document.getElementById('todayRevenue').textContent = parseFloat(data.today_revenue || 0).toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('weekRevenue').textContent = parseFloat(data.week_revenue || 0).toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('pendingPayments').textContent = parseFloat(data.pending_payments || 0).toLocaleString('en-US', {minimumFractionDigits: 2});
            
            // Update collection rate
            const collectionRate = data.collection_rate || 0;
            document.getElementById('collectionRate').textContent = collectionRate + '%';
            
            // Update progress bars
            const todayProgress = Math.min((data.today_revenue / data.week_revenue) * 100, 100);
            const weekProgress = Math.min((data.week_revenue / data.monthly_target) * 100, 100);
            
            document.getElementById('todayRevenueProgress').style.width = todayProgress + '%';
            document.getElementById('weekRevenueProgress').style.width = weekProgress + '%';
            document.getElementById('collectionRateProgress').style.width = collectionRate + '%';
        }
    } catch (error) {
        console.error('Error fetching financial overview:', error);
    }
}

// Setup event listeners for analytics controls
function setupAnalyticsEventListeners() {
    // Desktop event listeners
    const timeframeSelect = document.getElementById('analyticsTimeframe');
    const chartTypeSelect = document.getElementById('analyticsChartType');
    const yearFilterSelect = document.getElementById('analyticsYearFilter');
    
    if (timeframeSelect) {
        timeframeSelect.addEventListener('change', function() {
            const mobileTimeframeSelect = document.getElementById('analyticsTimeframeMobile');
            if (mobileTimeframeSelect) mobileTimeframeSelect.value = this.value;
            console.log('Timeframe changed to:', this.value);
            updateAnalyticsChart();
        });
    }
    
    if (chartTypeSelect) {
        chartTypeSelect.addEventListener('change', function() {
            const mobileChartTypeSelect = document.getElementById('analyticsChartTypeMobile');
            if (mobileChartTypeSelect) mobileChartTypeSelect.value = this.value;
            console.log('Chart type changed to:', this.value);
            updateAnalyticsChart();
        });
    }
    
    if (yearFilterSelect) {
        yearFilterSelect.addEventListener('change', function() {
            const mobileYearFilterSelect = document.getElementById('analyticsYearFilterMobile');
            if (mobileYearFilterSelect) mobileYearFilterSelect.value = this.value;
            console.log('Year filter changed to:', this.value);
            updateAnalyticsChart();
        });
    }
    
    // Mobile event listeners
    const timeframeSelectMobile = document.getElementById('analyticsTimeframeMobile');
    const chartTypeSelectMobile = document.getElementById('analyticsChartTypeMobile');
    const yearFilterSelectMobile = document.getElementById('analyticsYearFilterMobile');
    
    if (timeframeSelectMobile) {
        timeframeSelectMobile.addEventListener('change', function() {
            if (timeframeSelect) timeframeSelect.value = this.value;
            console.log('Timeframe (mobile) changed to:', this.value);
            updateAnalyticsChart();
        });
    }
    
    if (chartTypeSelectMobile) {
        chartTypeSelectMobile.addEventListener('change', function() {
            if (chartTypeSelect) chartTypeSelect.value = this.value;
            console.log('Chart type (mobile) changed to:', this.value);
            updateAnalyticsChart();
        });
    }
    
    if (yearFilterSelectMobile) {
        yearFilterSelectMobile.addEventListener('change', function() {
            if (yearFilterSelect) yearFilterSelect.value = this.value;
            console.log('Year filter (mobile) changed to:', this.value);
            updateAnalyticsChart();
        });
    }
}

// Initialize year filter dropdown
function initializeYearFilter() {
    const currentYear = new Date().getFullYear();
    const yearFilter = document.getElementById('analyticsYearFilter');
    const yearFilterMobile = document.getElementById('analyticsYearFilterMobile');
    
    // Function to populate a year filter dropdown
    function populateYearFilter(filterElement) {
        if (filterElement) {
            // Clear existing options
            filterElement.innerHTML = '';
            
            // Add year options (current year and 5 previous years)
            for (let year = currentYear; year >= currentYear - 5; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                if (year === currentYear) {
                    option.selected = true;
                }
                filterElement.appendChild(option);
            }
        }
    }
    
    // Populate both desktop and mobile year filters
    populateYearFilter(yearFilter);
    populateYearFilter(yearFilterMobile);
}


// Function to update Geographic/Regional Sales Insights chart
function updateGeographicAnalysisChart(selectedYear = '', selectedMonth = '') {
    // Check if geographicSales data exists and is an array
    if (!geographicSales || !Array.isArray(geographicSales)) {
        console.warn('Geographic sales data not available or not an array');
        updateGeographicSummaryMetrics({});
        return;
    }
    
    let filteredData = geographicSales;
    let chartTitle = 'Geographic/Regional Sales Insights';
    
    if (selectedYear && selectedMonth) {
        // Filter by specific year and month
        filteredData = geographicSales.filter(region => {
            return region.year && region.month && 
                   region.year.toString() === selectedYear && 
                   region.month.toString() === selectedMonth;
        });
        
        const monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 
                           'July', 'August', 'September', 'October', 'November', 'December'];
        chartTitle = `Geographic Sales - ${monthNames[parseInt(selectedMonth)]} ${selectedYear}`;
    } else if (selectedYear) {
        // Filter by year only
        filteredData = geographicSales.filter(region => 
            region.year && region.year.toString() === selectedYear
        );
        chartTitle = `Geographic Sales - ${selectedYear}`;
    } else if (selectedMonth) {
        // Filter by month across all years
        filteredData = geographicSales.filter(region => {
            if (!region.month) return false;
            
            // Handle different month formats: number, string, or date-based
            const regionMonth = region.month.toString();
            const selectedMonthStr = selectedMonth.toString();
            
            // Direct match
            if (regionMonth === selectedMonthStr) return true;
            
            // Zero-padded match (e.g., "01" vs "1")
            if (regionMonth.padStart(2, '0') === selectedMonthStr.padStart(2, '0')) return true;
            
            // Parse as integer and compare
            const regionMonthInt = parseInt(regionMonth);
            const selectedMonthInt = parseInt(selectedMonthStr);
            if (!isNaN(regionMonthInt) && !isNaN(selectedMonthInt) && regionMonthInt === selectedMonthInt) return true;
            
            return false;
        });
        
        const monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 
                           'July', 'August', 'September', 'October', 'November', 'December'];
        chartTitle = `Geographic Sales - ${monthNames[parseInt(selectedMonth)]} (All Years)`;
    } else {
        chartTitle = 'Geographic Sales - All Time';
    }
    
    console.log('Geographic Filter applied:', { 
        selectedYear, 
        selectedMonth, 
        totalRegions: geographicSales.length,
        filteredCount: filteredData.length
    });
    
    // Debug: Log sample data to understand the structure
    if (geographicSales.length > 0) {
        console.log('Sample geographic data:', geographicSales.slice(0, 3));
        console.log('Available months in data:', [...new Set(geographicSales.map(r => r.month))]);
        console.log('Available years in data:', [...new Set(geographicSales.map(r => r.year))]);
    }
    
    // Group by region and sum the data
    const regionGroups = {};
    filteredData.forEach(region => {
        if (!regionGroups[region.region]) {
            regionGroups[region.region] = {
                total_appointments: 0,
                completed_appointments: 0,
                paid_appointments: 0,
                total_revenue: 0,
                total_customers: 0,
                total_rating: 0,
                rating_count: 0
            };
        }
        regionGroups[region.region].total_appointments += region.total_appointments;
        regionGroups[region.region].completed_appointments += region.completed_appointments;
        regionGroups[region.region].paid_appointments += region.paid_appointments;
        regionGroups[region.region].total_revenue += region.total_revenue;
        if (region.avg_rating > 0) {
            regionGroups[region.region].total_rating += region.avg_rating;
            regionGroups[region.region].rating_count++;
        }
    });
    
    // Sort regions by total revenue (descending) and take top 10
    const sortedRegions = Object.entries(regionGroups)
        .sort(([,a], [,b]) => b.total_revenue - a.total_revenue)
        .slice(0, 10);
    
    // Convert to arrays for chart
    const regionNames = sortedRegions.map(([name]) => name);
    const totalRevenue = sortedRegions.map(([,data]) => data.total_revenue);
    const totalJobs = sortedRegions.map(([,data]) => data.completed_appointments);
    const avgRevPerJob = sortedRegions.map(([,data]) => 
        data.completed_appointments > 0 
            ? data.total_revenue / data.completed_appointments 
            : 0
    );
    
    // Calculate performance scores (combination of revenue and job count)
    const maxRevenue = Math.max(...totalRevenue);
    const maxJobs = Math.max(...totalJobs);
    const performanceScores = sortedRegions.map(([,data]) => {
        const revenueScore = maxRevenue > 0 ? (data.total_revenue / maxRevenue) * 50 : 0;
        const jobScore = maxJobs > 0 ? (data.completed_appointments / maxJobs) * 50 : 0;
        return revenueScore + jobScore;
    });
    
    if (geographicChart) {
        geographicChart.destroy();
    }
    
    geographicChart = new Chart(geographicAnalysisCtx, {
        type: 'bar',
        data: {
            labels: regionNames,
            datasets: [{
                label: 'Regional Performance Score',
                data: performanceScores,
                backgroundColor: function(context) {
                    // Color gradient based on performance score
                    if (!context || !context.parsed || context.parsed.x === undefined) {
                        return 'rgba(200, 200, 200, 0.8)'; // Default gray color
                    }
                    
                    const value = context.parsed.x;
                    const maxValue = Math.max(...performanceScores);
                    const intensity = maxValue > 0 ? value / maxValue : 0;
                    
                    if (intensity > 0.8) return 'rgba(40, 167, 69, 0.8)';      // Green - Excellent
                    else if (intensity > 0.6) return 'rgba(255, 193, 7, 0.8)'; // Yellow - Good
                    else if (intensity > 0.4) return 'rgba(255, 152, 0, 0.8)'; // Orange - Average
                    else return 'rgba(220, 53, 69, 0.8)';                      // Red - Needs Improvement
                },
                borderColor: function(context) {
                    if (!context || !context.parsed || context.parsed.x === undefined) {
                        return '#dee2e6'; // Default gray border
                    }
                    
                    const value = context.parsed.x;
                    const maxValue = Math.max(...performanceScores);
                    const intensity = maxValue > 0 ? value / maxValue : 0;
                    
                    if (intensity > 0.8) return '#28a745';      // Green
                    else if (intensity > 0.6) return '#ffc107'; // Yellow
                    else if (intensity > 0.4) return '#ff9800'; // Orange
                    else return '#dc3545';                      // Red
                },
                borderWidth: 2,
                borderRadius: {
                    topLeft: 0,
                    topRight: 6,
                    bottomLeft: 0,
                    bottomRight: 6
                },
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                title: {
                    display: true,
                    text: chartTitle,
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: '#333',
                    padding: 20
                },
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#6f42c1',
                    borderWidth: 2,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            const index = context[0].dataIndex;
                            return regionNames[index] + ' Region';
                        },
                        label: function(context) {
                            const index = context.dataIndex;
                            const regionData = sortedRegions[index][1];
                            
                            return [
                                `Performance Score: ${context.parsed.x.toFixed(1)}/100`,
                                '',
                                `Total Revenue: ₱${regionData.total_revenue.toLocaleString('en-PH', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })}`,
                                `Completed Jobs: ${regionData.completed_appointments}`,
                                `Avg per Job: ₱${avgRevPerJob[index].toLocaleString('en-PH', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })}`,
                                `Total Appointments: ${regionData.total_appointments}`,
                                `Success Rate: ${regionData.total_appointments > 0 ? 
                                    ((regionData.completed_appointments / regionData.total_appointments) * 100).toFixed(1) : 0}%`
                            ];
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Performance Score (0-100)',
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        color: '#333'
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.1)',
                        lineWidth: 1
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#666',
                        callback: function(value) {
                            return value.toFixed(0);
                        }
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Regions/Cities (Top 10 by Revenue)',
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        color: '#333'
                    },
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        color: '#333',
                        padding: 10
                    }
                }
            },
            layout: {
                padding: {
                    left: 10,
                    right: 20,
                    top: 10,
                    bottom: 10
                }
            }
        }
    });
    
    // Update geographic summary metrics
    updateGeographicSummaryMetrics(regionGroups);
}

// Function to update Geographic summary metrics
function updateGeographicSummaryMetrics(regionGroups) {
    if (!regionGroups || Object.keys(regionGroups).length === 0) {
        document.getElementById('topRevenueRegion').textContent = '-';
        document.getElementById('topRegionRevenue').textContent = '₱0.00';
        document.getElementById('mostActiveRegion').textContent = '-';
        document.getElementById('mostActiveJobs').textContent = '0';
        document.getElementById('totalRegions').textContent = '0';
        document.getElementById('totalRegionalCustomers').textContent = '0';
        document.getElementById('avgRevenuePerRegion').textContent = '₱0.00';
        return;
    }
    
    // Find top revenue region and most active region
    let topRevenueRegion = '';
    let topRegionRevenue = 0;
    let mostActiveRegion = '';
    let mostActiveJobs = 0;
    let totalRevenue = 0;
    let totalCustomers = 0;
    
    Object.keys(regionGroups).forEach(regionName => {
        const region = regionGroups[regionName];
        
        if (region.total_revenue > topRegionRevenue) {
            topRegionRevenue = region.total_revenue;
            topRevenueRegion = regionName;
        }
        
        if (region.completed_appointments > mostActiveJobs) {
            mostActiveJobs = region.completed_appointments;
            mostActiveRegion = regionName;
        }
        
        totalRevenue += region.total_revenue;
        totalCustomers += region.total_customers || 0;
    });
    
    const totalRegions = Object.keys(regionGroups).length;
    const avgRevenuePerRegion = totalRegions > 0 ? totalRevenue / totalRegions : 0;
    
    // Update the summary metrics in the UI
    document.getElementById('topRevenueRegion').textContent = topRevenueRegion || '-';
    document.getElementById('topRegionRevenue').textContent = '₱' + topRegionRevenue.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    document.getElementById('mostActiveRegion').textContent = mostActiveRegion || '-';
    document.getElementById('mostActiveJobs').textContent = mostActiveJobs;
    document.getElementById('totalRegions').textContent = totalRegions;
    document.getElementById('totalRegionalCustomers').textContent = totalCustomers;
    document.getElementById('avgRevenuePerRegion').textContent = '₱' + avgRevenuePerRegion.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Populate year dropdown for technician chart with dynamic years
function populateTechnicianYearFilter() {
    const yearSelect = document.getElementById('technicianYearFilter');
    const yearSelectMobile = document.getElementById('technicianYearFilterMobile');
    
    // Clear existing options except "All Years" for both desktop and mobile
    while (yearSelect.children.length > 1) {
        yearSelect.removeChild(yearSelect.lastChild);
    }
    while (yearSelectMobile.children.length > 1) {
        yearSelectMobile.removeChild(yearSelectMobile.lastChild);
    }
    
    // Get unique years from technician performance data
    const dynamicYears = new Set();
    
    // Add years from techPerfByYear
    Object.keys(techPerfByYear).forEach(year => {
        if (year && year !== '') {
            dynamicYears.add(year);
        }
    });
    
    // Add years from techPerfByMonth
    Object.keys(techPerfByMonth).forEach(yearMonth => {
        const year = yearMonth.split('-')[0];
        if (year && year !== '') {
            dynamicYears.add(year);
        }
    });
    
    // Convert to sorted array and populate both dropdowns
    const sortedYears = Array.from(dynamicYears).sort((a, b) => b - a);
    sortedYears.forEach(year => {
        // Desktop dropdown
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
        
        // Mobile dropdown
        const optionMobile = document.createElement('option');
        optionMobile.value = year;
        optionMobile.textContent = year;
        yearSelectMobile.appendChild(optionMobile);
    });
    
    console.log('Technician Year Filter populated with years:', dynamicYears);
}

// Add event listeners for technician chart filters with enhanced functionality
function addTechnicianFilterListeners() {
    // Desktop filters
    document.getElementById('technicianYearFilter').addEventListener('change', function() {
        const selectedYear = this.value;
        const selectedMonth = document.getElementById('technicianMonthFilter').value;
        
        // Sync with mobile filters
        document.getElementById('technicianYearFilterMobile').value = selectedYear;
        
        console.log('Year filter changed:', selectedYear);
        updateTechnicianPerformanceChart(selectedYear, selectedMonth);
        
        if (selectedYear) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });

    document.getElementById('technicianMonthFilter').addEventListener('change', function() {
        const selectedYear = document.getElementById('technicianYearFilter').value;
        const selectedMonth = this.value;
        
        // Sync with mobile filters
        document.getElementById('technicianMonthFilterMobile').value = selectedMonth;
        
        console.log('Month filter changed:', selectedMonth);
        updateTechnicianPerformanceChart(selectedYear, selectedMonth);
        
        if (selectedMonth) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });

    // Mobile filters
    document.getElementById('technicianYearFilterMobile').addEventListener('change', function() {
        const selectedYear = this.value;
        const selectedMonth = document.getElementById('technicianMonthFilterMobile').value;
        
        // Sync with desktop filters
        document.getElementById('technicianYearFilter').value = selectedYear;
        
        console.log('Mobile year filter changed:', selectedYear);
        updateTechnicianPerformanceChart(selectedYear, selectedMonth);
        
        if (selectedYear) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });

    document.getElementById('technicianMonthFilterMobile').addEventListener('change', function() {
        const selectedYear = document.getElementById('technicianYearFilterMobile').value;
        const selectedMonth = this.value;
        
        // Sync with desktop filters
        document.getElementById('technicianMonthFilter').value = selectedMonth;
        
        console.log('Mobile month filter changed:', selectedMonth);
        updateTechnicianPerformanceChart(selectedYear, selectedMonth);
        
        if (selectedMonth) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });
}

// Call the function to add listeners
addTechnicianFilterListeners();

// Function to refresh year dropdown when new data is available
function refreshTechnicianYearFilter() {
    const currentSelection = document.getElementById('technicianYearFilter').value;
    populateTechnicianYearFilter();
    
    // Restore selection if it still exists
    const yearSelect = document.getElementById('technicianYearFilter');
    const options = Array.from(yearSelect.options);
    const optionExists = options.some(option => option.value === currentSelection);
    
    if (optionExists) {
        yearSelect.value = currentSelection;
    } else {
        yearSelect.value = '';
    }
    
    console.log('Technician year filter refreshed');
}

// Populate year dropdown for engagement chart with dynamic years
function populateEngagementYearFilter() {
    const yearSelect = document.getElementById('engagementYearFilter');
    const yearSelectMobile = document.getElementById('engagementYearFilterMobile');
    
    // Clear existing options except "All Years" for both desktop and mobile
    while (yearSelect.children.length > 1) {
        yearSelect.removeChild(yearSelect.lastChild);
    }
    while (yearSelectMobile.children.length > 1) {
        yearSelectMobile.removeChild(yearSelectMobile.lastChild);
    }
    
    // Get all available years from engagement data and sort them in descending order (newest first)
    const dynamicYears = Object.keys(engagementByYear).map(year => parseInt(year)).sort((a, b) => b - a);
    
    // Add current year if not present in data
    const currentYear = new Date().getFullYear();
    if (!dynamicYears.includes(currentYear)) {
        dynamicYears.unshift(currentYear);
        dynamicYears.sort((a, b) => b - a);
    }
    
    // Populate both dropdowns with years
    dynamicYears.forEach(year => {
        // Desktop dropdown
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
        
        // Mobile dropdown
        const optionMobile = document.createElement('option');
        optionMobile.value = year;
        optionMobile.textContent = year;
        yearSelectMobile.appendChild(optionMobile);
    });
    
    console.log('Engagement Year Filter populated with years:', dynamicYears);
}

// Add event listeners for engagement chart filters with enhanced functionality
function addEngagementFilterListeners() {
    // Desktop filters
    document.getElementById('engagementYearFilter').addEventListener('change', function() {
        const selectedYear = this.value;
        const selectedMonth = document.getElementById('engagementMonthFilter').value;
        
        // Sync with mobile filters
        document.getElementById('engagementYearFilterMobile').value = selectedYear;
        
        console.log('Engagement year filter changed:', selectedYear);
        updateEngagementLevelsChart(selectedYear, selectedMonth);
        
        if (selectedYear) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });

    document.getElementById('engagementMonthFilter').addEventListener('change', function() {
        const selectedYear = document.getElementById('engagementYearFilter').value;
        const selectedMonth = this.value;
        
        // Sync with mobile filters
        document.getElementById('engagementMonthFilterMobile').value = selectedMonth;
        
        console.log('Engagement month filter changed:', selectedMonth);
        updateEngagementLevelsChart(selectedYear, selectedMonth);
        
        if (selectedMonth) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });

    // Mobile filters
    document.getElementById('engagementYearFilterMobile').addEventListener('change', function() {
        const selectedYear = this.value;
        const selectedMonth = document.getElementById('engagementMonthFilterMobile').value;
        
        // Sync with desktop filters
        document.getElementById('engagementYearFilter').value = selectedYear;
        
        console.log('Mobile engagement year filter changed:', selectedYear);
        updateEngagementLevelsChart(selectedYear, selectedMonth);
        
        if (selectedYear) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });

    document.getElementById('engagementMonthFilterMobile').addEventListener('change', function() {
        const selectedYear = document.getElementById('engagementYearFilterMobile').value;
        const selectedMonth = this.value;
        
        // Sync with desktop filters
        document.getElementById('engagementMonthFilter').value = selectedMonth;
        
        console.log('Mobile engagement month filter changed:', selectedMonth);
        updateEngagementLevelsChart(selectedYear, selectedMonth);
        
        if (selectedMonth) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });
}

// Call the function to add listeners
addEngagementFilterListeners();

// Populate year dropdown for Geographic chart with dynamic years
function populateGeographicYearFilter() {
    const yearSelect = document.getElementById('geographicYearFilter');
    const yearSelectMobile = document.getElementById('geographicYearFilterMobile');
    
    // Clear existing options except "All Years" for both desktop and mobile
    while (yearSelect.children.length > 1) {
        yearSelect.removeChild(yearSelect.lastChild);
    }
    while (yearSelectMobile.children.length > 1) {
        yearSelectMobile.removeChild(yearSelectMobile.lastChild);
    }
    
    // Get all available years from geographic data and sort them in descending order (newest first)
    const allYears = new Set();
    geographicSales.forEach(region => {
        allYears.add(region.year);
    });
    
    // Add current year to ensure it's always available for new data
    const currentYear = new Date().getFullYear();
    allYears.add(currentYear);
    
    // Convert to sorted array (newest first)
    const dynamicYears = Array.from(allYears).sort((a, b) => b - a);
    
    // Populate both dropdowns with years
    dynamicYears.forEach(year => {
        // Desktop dropdown
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
        
        // Mobile dropdown
        const optionMobile = document.createElement('option');
        optionMobile.value = year;
        optionMobile.textContent = year;
        yearSelectMobile.appendChild(optionMobile);
    });
    
    console.log('Geographic Year Filter populated with years:', dynamicYears);
}

// Add event listeners for Geographic chart filters with enhanced functionality
function addGeographicFilterListeners() {
    // Desktop filters
    document.getElementById('geographicYearFilter').addEventListener('change', function() {
        const selectedYear = this.value;
        const selectedMonth = document.getElementById('geographicMonthFilter').value;
        
        // Sync with mobile filters
        document.getElementById('geographicYearFilterMobile').value = selectedYear;
        
        console.log('Geographic year filter changed:', selectedYear);
        updateGeographicAnalysisChart(selectedYear, selectedMonth);
        
        if (selectedYear) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });

    document.getElementById('geographicMonthFilter').addEventListener('change', function() {
        const selectedYear = document.getElementById('geographicYearFilter').value;
        const selectedMonth = this.value;
        
        // Sync with mobile filters
        document.getElementById('geographicMonthFilterMobile').value = selectedMonth;
        
        console.log('Geographic month filter changed:', selectedMonth);
        updateGeographicAnalysisChart(selectedYear, selectedMonth);
        
        if (selectedMonth) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });

    // Mobile filters
    document.getElementById('geographicYearFilterMobile').addEventListener('change', function() {
        const selectedYear = this.value;
        const selectedMonth = document.getElementById('geographicMonthFilterMobile').value;
        
        // Sync with desktop filters
        document.getElementById('geographicYearFilter').value = selectedYear;
        
        console.log('Mobile geographic year filter changed:', selectedYear);
        updateGeographicAnalysisChart(selectedYear, selectedMonth);
        
        if (selectedYear) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });

    document.getElementById('geographicMonthFilterMobile').addEventListener('change', function() {
        const selectedYear = document.getElementById('geographicYearFilterMobile').value;
        const selectedMonth = this.value;
        
        // Sync with desktop filters
        document.getElementById('geographicMonthFilter').value = selectedMonth;
        
        console.log('Mobile geographic month filter changed:', selectedMonth);
        updateGeographicAnalysisChart(selectedYear, selectedMonth);
        
        if (selectedMonth) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });
}

// Call the function to add listeners
addGeographicFilterListeners();

// Populate year dropdown for CLV chart with dynamic years
function populateCLVYearFilter() {
    const yearSelect = document.getElementById('clvYearFilter');
    const yearSelectMobile = document.getElementById('clvYearFilterMobile');
    
    // Clear existing options except "All Years" for both desktop and mobile
    while (yearSelect.children.length > 1) {
        yearSelect.removeChild(yearSelect.lastChild);
    }
    while (yearSelectMobile.children.length > 1) {
        yearSelectMobile.removeChild(yearSelectMobile.lastChild);
    }
    
    // Get all available years from customer data (both first and last appointment years)
    const allYears = new Set();
    customerLifetimeValue.forEach(customer => {
        const firstYear = new Date(customer.first_appointment).getFullYear();
        const lastYear = new Date(customer.last_appointment).getFullYear();
        
        // Add all years between first and last appointment
        for (let year = firstYear; year <= lastYear; year++) {
            allYears.add(year);
        }
    });
    
    // Add current year to ensure it's always available for new data
    const currentYear = new Date().getFullYear();
    allYears.add(currentYear);
    
    // Convert to sorted array (newest first)
    const dynamicYears = Array.from(allYears).sort((a, b) => b - a);
    
    // Populate both dropdowns with years
    dynamicYears.forEach(year => {
        // Desktop dropdown
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
        
        // Mobile dropdown
        const optionMobile = document.createElement('option');
        optionMobile.value = year;
        optionMobile.textContent = year;
        yearSelectMobile.appendChild(optionMobile);
    });
    
    console.log('CLV Year Filter populated with years:', dynamicYears);
}

// Add event listeners for CLV chart filters with enhanced functionality
function addCLVFilterListeners() {
    // Desktop filters
    document.getElementById('clvYearFilter').addEventListener('change', function() {
        const selectedYear = this.value;
        const selectedMonth = document.getElementById('clvMonthFilter').value;
        
        // Sync with mobile filters
        document.getElementById('clvYearFilterMobile').value = selectedYear;
        
        console.log('CLV year filter changed:', selectedYear);
        updateCLVAnalysisChart(selectedYear, selectedMonth);
        
        if (selectedYear) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });

    document.getElementById('clvMonthFilter').addEventListener('change', function() {
        const selectedYear = document.getElementById('clvYearFilter').value;
        const selectedMonth = this.value;
        
        // Sync with mobile filters
        document.getElementById('clvMonthFilterMobile').value = selectedMonth;
        
        console.log('CLV month filter changed:', selectedMonth);
        updateCLVAnalysisChart(selectedYear, selectedMonth);
        
        if (selectedMonth) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });

    // Mobile filters
    document.getElementById('clvYearFilterMobile').addEventListener('change', function() {
        const selectedYear = this.value;
        const selectedMonth = document.getElementById('clvMonthFilterMobile').value;
        
        // Sync with desktop filters
        document.getElementById('clvYearFilter').value = selectedYear;
        
        console.log('Mobile CLV year filter changed:', selectedYear);
        updateCLVAnalysisChart(selectedYear, selectedMonth);
        
        if (selectedYear) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });

    document.getElementById('clvMonthFilterMobile').addEventListener('change', function() {
        const selectedYear = document.getElementById('clvYearFilterMobile').value;
        const selectedMonth = this.value;
        
        // Sync with desktop filters
        document.getElementById('clvMonthFilter').value = selectedMonth;
        
        console.log('Mobile CLV month filter changed:', selectedMonth);
        updateCLVAnalysisChart(selectedYear, selectedMonth);
        
        if (selectedMonth) {
            this.style.backgroundColor = '#e3f2fd';
        } else {
            this.style.backgroundColor = 'white';
        }
    });
}

// Call the function to add listeners
addCLVFilterListeners();

// Initialize all charts
populateTechnicianYearFilter();
populateEngagementYearFilter();
populateCLVYearFilter();
populateGeographicYearFilter();
updateTechnicianPerformanceChart();
updateEngagementLevelsChart();
updateCLVAnalysisChart();
updateGeographicAnalysisChart();

// Initialize Business Analytics and Financial Overview sections
initializeYearFilter();
setupAnalyticsEventListeners();
updateAnalyticsChart();
updateFinancialOverview();

// Add event listener for centralized year filter (if it exists)
const centralYearFilter = document.getElementById('centralYearFilter');
if (centralYearFilter) {
    centralYearFilter.addEventListener('change', function() {
        const selectedYear = this.value;
        updateCLVAnalysisChart(selectedYear);
        updateGeographicAnalysisChart(selectedYear);
    });
}

</script>