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

    /* Dashboard consistent styling with admin interface */
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
        color: #28a745;
        margin-bottom: 5px;
    }

    .workload-metric-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }

    .hover-card {
        transition: all 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    /* Professional Table Styling - Consistent with task-list.php */
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

    /* Table styling enhancements */
    .table-container {
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }
</style>

<h3>Performance Statistics</h3>


<!-- Revenue and Performance Analytics -->
<div class="row mt-4 px-2">
    <!-- Revenue Trends -->
    <div class="col-lg-8 p-2">
        <div class="dashboard-card hover-card">
            <div class="dashboard-card-header">
                <!-- Desktop header layout -->
                <div class="d-none d-md-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-graph-up me-2"></i>
                        <span>Revenue Analytics</span>
                    </div>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" id="revenueTimeframe" style="width: auto; font-size: 0.875rem;">
                            <option value="7">Last 7 Days</option>
                            <option value="30" selected>Last 30 Days</option>
                            <option value="90">Last 90 Days</option>
                            <option value="365">This Year</option>
                        </select>
                        <select class="form-select form-select-sm" id="revenueChartType" style="width: auto; font-size: 0.875rem;">
                            <option value="revenue">Revenue Trends</option>
                            <option value="jobs">Job Completion</option>
                            <option value="ratings">Rating Trends</option>
                        </select>
                        <select class="form-select form-select-sm" id="revenueYearFilter" style="width: auto; font-size: 0.875rem;">
                            <!-- Year options will be populated dynamically by JavaScript -->
                        </select>
                    </div>
                </div>
                
                <!-- Mobile header layout -->
                <div class="d-md-none">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-graph-up me-2"></i>
                        <span>Revenue Analytics</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-12">
                            <select class="form-select form-select-sm" id="revenueTimeframeMobile" style="font-size: 0.875rem;">
                                <option value="7">Last 7 Days</option>
                                <option value="30" selected>Last 30 Days</option>
                                <option value="90">Last 90 Days</option>
                                <option value="365">This Year</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <select class="form-select form-select-sm" id="revenueChartTypeMobile" style="font-size: 0.875rem;">
                                <option value="revenue">Revenue Trends</option>
                                <option value="jobs">Job Completion</option>
                                <option value="ratings">Rating Trends</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <select class="form-select form-select-sm" id="revenueYearFilterMobile" style="font-size: 0.875rem;">
                                <!-- Year options will be populated dynamically by JavaScript -->
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard-card-body">
                <div style="height: 300px; position: relative;">
                    <canvas id="revenueChart"></canvas>
                    <div id="revenueLoading" class="text-center py-4" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <div class="spinner-border text-success mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mb-0">Loading revenue analytics...</p>
                    </div>
                </div>
                
                <!-- Key Metrics Row -->
                <div class="row mt-3 pt-3 border-top g-3">
                    <div class="col-6 col-md-3 text-center">
                        <div class="metric-item">
                            <h5 class="mb-1 text-success" id="totalRevenue">₱0</h5>
                            <small class="text-muted">Total Revenue</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div class="metric-item">
                            <h5 class="mb-1 text-primary" id="totalJobs">0</h5>
                            <small class="text-muted">Total Jobs</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div class="metric-item">
                            <h5 class="mb-1 text-warning" id="avgRevenue">₱0</h5>
                            <small class="text-muted">Avg per Job</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <div class="metric-item">
                            <h5 class="mb-1 text-info" id="avgRatingMetric" style="text-shadow: none;">0.0</h5>
                            <small class="text-muted">Avg Rating</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="col-lg-4 p-2">
        <div class="dashboard-card hover-card h-100">
            <div class="dashboard-card-header">
                <i class="bi bi-trophy me-2"></i>Performance Summary
            </div>
            <div class="dashboard-card-body">
            
            <!-- Frequent Customer -->
            <div class="mb-3 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted">Most Frequent Customer</span>
                    <i class="bi bi-person-heart text-primary"></i>
                </div>
                <div class="fw-bold text-primary" id="frequentCustomer">Loading...</div>
            </div>
            
            <!-- This Month Stats -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small">This Month</span>
                    <span class="badge bg-primary" id="thisMonthJobs">0 jobs</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small">Revenue</span>
                    <span class="fw-bold text-success" id="thisMonthRevenue">₱0</span>
                </div>
            </div>


            <!-- Top Three Frequent Customers -->
            <div class="mb-3">
                <span class="small text-muted">Top Frequent Customers</span>
                <div id="topCustomers">
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="small">1st</span>
                        <span class="fw-bold text-primary" id="customer1">Loading...</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <span class="small">2nd</span>
                        <span class="fw-bold text-secondary" id="customer2">Loading...</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <span class="small">3rd</span>
                        <span class="fw-bold text-muted" id="customer3">Loading...</span>
                    </div>
                </div>
            </div>

            <!-- Top Service Type -->
            <div class="mb-3">
                <span class="small text-muted">Top Service Type</span>
                <div class="fw-bold" id="topServiceType">-</div>
            </div>

            <!-- Performance Badge -->
            <div class="text-center mt-3">
                <span class="badge bg-success p-2" id="performanceBadge">Loading...</span>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Analytics -->
<div class="row mt-3 px-2">
    <!-- Service Expertise -->
    <div class="col-lg-6 p-2">
        <div class="dashboard-card hover-card h-100">
            <div class="dashboard-card-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                <i class="bi bi-tools me-2"></i>Service Expertise
            </div>
            <div class="dashboard-card-body">
                <div style="position: relative; height: 300px;">
                    <canvas id="serviceExpertiseChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Satisfaction -->
    <div class="col-lg-6 p-2">
        <div class="dashboard-card hover-card h-100">
            <div class="dashboard-card-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                <i class="bi bi-heart-fill me-2"></i>Customer Satisfaction
            </div>
            <div class="dashboard-card-body">
                <div style="position: relative; height: 300px;">
                    <canvas id="satisfactionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Team Collaboration Summary -->
<div class="row mt-3 px-2">
    <div class="col-12 p-2">
        <div class="dashboard-card hover-card">
            <div class="dashboard-card-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                <i class="bi bi-people-fill me-2"></i>Team Collaboration Analytics
            </div>
            <div class="dashboard-card-body">
                <!-- Collaboration Summary Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 col-6 mb-3">
                        <div class="workload-metric">
                            <div class="workload-metric-value text-info" id="teamJobsPercentage">0%</div>
                            <div class="workload-metric-label">Team Jobs</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-6 mb-3">
                        <div class="workload-metric">
                            <div class="workload-metric-value text-success" id="uniquePartners">0</div>
                            <div class="workload-metric-label">Unique Partners</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-6 mb-3">
                        <div class="workload-metric">
                            <div class="workload-metric-value text-primary" id="primaryRoleJobs">0</div>
                            <div class="workload-metric-label">Primary Role Jobs</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-6 mb-3">
                        <div class="workload-metric">
                            <div class="workload-metric-value text-secondary" id="secondaryRoleJobs">0</div>
                            <div class="workload-metric-label">Secondary Role Jobs</div>
                        </div>
                    </div>
                </div>

                <!-- Role Distribution Chart and Partnership Performance -->
                <div class="row mb-4">
                    <div class="col-lg-4 mb-3">
                        <h6 class="text-primary mb-3"><i class="bi bi-person-badge me-2 text-primary"></i>Role Distribution</h6>
                        <div style="position: relative; height: 200px;">
                            <canvas id="roleDistributionChart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-8 mb-3">
                        <h6 class="text-primary mb-3"><i class="bi bi-handshake me-2 text-primary"></i>Top Partnership Performance</h6>
                        
                        <!-- Desktop table view -->
                        <div class="d-none d-md-block">
                            <div class="table-container">
                                <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Partner Name</th>
                                                <th scope="col">Total Jobs</th>
                                                <th scope="col">Avg Rating</th>
                                                <th scope="col">Completion Rate</th>
                                                <th scope="col">Avg Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody id="partnershipTable">
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Loading...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mobile card view -->
                        <div class="d-md-none" style="max-height: 200px; overflow-y: auto;">
                            <div id="partnershipMobile">
                                <div class="text-center text-muted py-3">Loading...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Partnership Performance Table (Mobile Full Width) -->
<div class="row mt-3 px-2 d-md-none">
    <div class="col-12 p-2">
        <div class="dashboard-card hover-card">
            <div class="dashboard-card-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                <i class="bi bi-handshake me-2"></i>Partnership Performance Details
            </div>
            <div class="dashboard-card-body">
                <div style="max-height: 300px; overflow-y: auto;">
                    <div id="partnershipMobileDetailed">
                        <div class="text-center text-muted py-3">Loading...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Performance -->
<div class="row mt-3 px-2">
    <div class="col-12 p-2">
        <div class="dashboard-card hover-card">
            <div class="dashboard-card-header" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                <i class="bi bi-activity me-2"></i>Recent Performance
            </div>
            <div class="dashboard-card-body">
                <!-- Desktop table view -->
                <div class="d-none d-md-block">
                    <div class="table-container">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Job ID</th>
                                        <th scope="col">Service</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Rating</th>
                                        <th scope="col">Revenue</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="recentPerformanceTable">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile card view -->
                <div class="d-md-none" style="max-height: 400px; overflow-y: auto;">
                    <div id="recentPerformanceMobile">
                        <div class="text-center text-muted py-3">Loading...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let revenueChart, serviceExpertiseChart, satisfactionChart;

// Load statistics when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadTechnicianStatistics();
    
    // Populate year filters for both desktop and mobile
    const currentYear = new Date().getFullYear();
    const yearFilters = ['revenueYearFilter', 'revenueYearFilterMobile'];
    
    yearFilters.forEach(filterId => {
        const yearFilter = document.getElementById(filterId);
        if (yearFilter) {
            for (let year = currentYear; year >= currentYear - 5; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                if (year === currentYear) option.selected = true;
                yearFilter.appendChild(option);
            }
        }
    });
    
    // Add event listeners for desktop filters
    document.getElementById('revenueTimeframe')?.addEventListener('change', updateRevenueAnalytics);
    document.getElementById('revenueChartType')?.addEventListener('change', updateRevenueAnalytics);
    document.getElementById('revenueYearFilter')?.addEventListener('change', updateRevenueAnalytics);
    
    // Add event listeners for mobile filters
    document.getElementById('revenueTimeframeMobile')?.addEventListener('change', function() {
        // Sync with desktop filter
        const desktopFilter = document.getElementById('revenueTimeframe');
        if (desktopFilter) desktopFilter.value = this.value;
        updateRevenueAnalytics();
    });
    
    document.getElementById('revenueChartTypeMobile')?.addEventListener('change', function() {
        // Sync with desktop filter
        const desktopFilter = document.getElementById('revenueChartType');
        if (desktopFilter) desktopFilter.value = this.value;
        updateRevenueAnalytics();
    });
    
    document.getElementById('revenueYearFilterMobile')?.addEventListener('change', function() {
        // Sync with desktop filter
        const desktopFilter = document.getElementById('revenueYearFilter');
        if (desktopFilter) desktopFilter.value = this.value;
        updateRevenueAnalytics();
    });
});

// Main function to load all statistics
function loadTechnicianStatistics() {
    fetch('api/technician/statistics.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateKPICards(data);
                updatePerformanceSummary(data);
                updateRecentPerformance(data);
                updateTeamCollaborationAnalytics(data);
                createCharts(data);
                // Initialize advanced revenue analytics
                updateRevenueAnalytics();
            } else {
                console.error('Error loading statistics:', data.message);
                showErrorState();
            }
        })
        .catch(error => {
            console.error('Error fetching statistics:', error);
            showErrorState();
        });
}

// Update KPI cards (removed since sections were deleted)
function updateKPICards(data) {
    // KPI cards removed - no elements to update
}

// Update performance summary
function updatePerformanceSummary(data) {
    document.getElementById('frequentCustomer').textContent = data.frequent_customer || 'No data available';
    document.getElementById('thisMonthJobs').textContent = (data.this_month_jobs || 0) + ' jobs';
    document.getElementById('thisMonthRevenue').textContent = '₱' + (data.this_month_revenue || 0).toLocaleString();
    
    // Update top three customers
    const topCustomers = data.top_customers || [];
    document.getElementById('customer1').textContent = topCustomers[0] || 'No data';
    document.getElementById('customer2').textContent = topCustomers[1] || 'No data';
    document.getElementById('customer3').textContent = topCustomers[2] || 'No data';
    
    document.getElementById('topServiceType').textContent = data.top_service_type || '-';
    
    // Performance badge
    const rating = data.average_rating || 0;
    let badge = 'Excellent Performer';
    let badgeClass = 'bg-success';
    
    if (rating < 3) {
        badge = 'Needs Improvement';
        badgeClass = 'bg-danger';
    } else if (rating < 4) {
        badge = 'Good Performer';
        badgeClass = 'bg-warning';
    }
    
    const badgeElement = document.getElementById('performanceBadge');
    badgeElement.textContent = badge;
    badgeElement.className = `badge p-2 ${badgeClass}`;
}


// Update recent performance table
function updateRecentPerformance(data) {
    const tbody = document.getElementById('recentPerformanceTable');
    const mobileContainer = document.getElementById('recentPerformanceMobile');
    
    if (data.recent_jobs && data.recent_jobs.length > 0) {
        // Desktop table view
        let tableHtml = '';
        data.recent_jobs.forEach(job => {
            const rating = job.rating || 0;
            const stars = '★'.repeat(rating) + '☆'.repeat(5 - rating);
            tableHtml += `
                <tr>
                    <td>#${job.job_id}</td>
                    <td>${job.service_type}</td>
                    <td>${new Date(job.date).toLocaleDateString()}</td>
                    <td><span class="text-warning">${stars}</span> <small class="text-muted">(${rating}/5)</small></td>
                    <td>₱${(job.revenue || 0).toLocaleString()}</td>
                    <td><span class="badge bg-success">Completed</span></td>
                </tr>
            `;
        });
        tbody.innerHTML = tableHtml;
        
        // Mobile card view
        let mobileHtml = '';
        data.recent_jobs.forEach(job => {
            const rating = job.rating || 0;
            const stars = '★'.repeat(rating) + '☆'.repeat(5 - rating);
            mobileHtml += `
                <div class="card mb-2 border-0 bg-light">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="card-title mb-1 text-primary">#${job.job_id}</h6>
                                <p class="card-text mb-1 fw-bold">${job.service_type}</p>
                            </div>
                            <span class="badge bg-success">Completed</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Date</small>
                            <span class="fw-medium">${new Date(job.date).toLocaleDateString()}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Rating</small>
                            <div>
                                <span class="text-warning fs-6">${stars}</span>
                                <small class="text-muted ms-2">(${rating}/5)</small>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block">Revenue</small>
                            <span class="fw-medium text-success">₱${(job.revenue || 0).toLocaleString()}</span>
                        </div>
                    </div>
                </div>
            `;
        });
        mobileContainer.innerHTML = mobileHtml;
    } else {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No recent jobs found</td></tr>';
        mobileContainer.innerHTML = '<div class="text-center text-muted py-3">No recent jobs found</div>';
    }
}

// Update Team Collaboration Analytics
function updateTeamCollaborationAnalytics(data) {
    // Update collaboration summary cards
    const collaborationSummary = data.collaboration_summary || {};
    document.getElementById('teamJobsPercentage').textContent = (collaborationSummary.team_job_percentage || 0) + '%';
    document.getElementById('uniquePartners').textContent = collaborationSummary.unique_partners || 0;
    document.getElementById('primaryRoleJobs').textContent = collaborationSummary.primary_role_count || 0;
    document.getElementById('secondaryRoleJobs').textContent = collaborationSummary.secondary_role_count || 0;
    
    // Update partnership performance table
    updatePartnershipTable(data.partnership_performance || []);
    
    // Create team collaboration charts
    createRoleDistributionChart(data.role_distribution || []);
}

// Update partnership performance table
function updatePartnershipTable(partnerships) {
    const tbody = document.getElementById('partnershipTable');
    const mobileContainer = document.getElementById('partnershipMobile');
    const mobileDetailedContainer = document.getElementById('partnershipMobileDetailed');
    
    if (partnerships && partnerships.length > 0) {
        // Desktop table view
        let tableHtml = '';
        partnerships.forEach(partner => {
            const ratingStars = '★'.repeat(Math.floor(partner.avg_rating)) + '☆'.repeat(5 - Math.floor(partner.avg_rating));
            tableHtml += `
                <tr>
                    <td class="fw-medium">${partner.partner_name}</td>
                    <td><span class="badge bg-primary">${partner.total_jobs}</span></td>
                    <td>
                        <span class="text-warning">${ratingStars}</span>
                        <small class="text-muted ms-1">(${partner.avg_rating}/5)</small>
                    </td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" style="width: ${partner.completion_rate}%">${partner.completion_rate}%</div>
                        </div>
                    </td>
                    <td class="text-success fw-medium">₱${partner.avg_revenue.toLocaleString()}</td>
                </tr>
            `;
        });
        tbody.innerHTML = tableHtml;
        
        // Mobile compact view (shows in collaboration section)
        let mobileCompactHtml = '';
        partnerships.slice(0, 2).forEach(partner => {
            const ratingStars = '★'.repeat(Math.floor(partner.avg_rating)) + '☆'.repeat(5 - Math.floor(partner.avg_rating));
            mobileCompactHtml += `
                <div class="card mb-2 border-0 bg-light">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0 text-primary small">${partner.partner_name}</h6>
                                <small class="text-muted">${partner.total_jobs} jobs • ${ratingStars}</small>
                            </div>
                            <span class="badge bg-success small">₱${partner.avg_revenue.toLocaleString()}</span>
                        </div>
                    </div>
                </div>
            `;
        });
        if (partnerships.length > 2) {
            mobileCompactHtml += `<div class="text-center"><small class="text-muted">+${partnerships.length - 2} more partners</small></div>`;
        }
        mobileContainer.innerHTML = mobileCompactHtml;
        
        // Mobile detailed view (separate section)
        let mobileDetailedHtml = '';
        partnerships.forEach(partner => {
            const ratingStars = '★'.repeat(Math.floor(partner.avg_rating)) + '☆'.repeat(5 - Math.floor(partner.avg_rating));
            mobileDetailedHtml += `
                <div class="card mb-3 border-0 bg-light">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-1 text-primary">${partner.partner_name}</h6>
                            <span class="badge bg-primary">${partner.total_jobs} jobs</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted d-block">Rating</small>
                                <div>
                                    <span class="text-warning">${ratingStars}</span>
                                    <small class="text-muted ms-1">(${partner.avg_rating}/5)</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Completion Rate</small>
                                <div class="progress mt-1" style="height: 15px;">
                                    <div class="progress-bar bg-success" style="width: ${partner.completion_rate}%"></div>
                                </div>
                                <small class="text-muted">${partner.completion_rate}%</small>
                            </div>
                            <div class="col-12 mt-2">
                                <small class="text-muted d-block">Avg Revenue</small>
                                <span class="fw-medium text-success">₱${partner.avg_revenue.toLocaleString()}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        if (mobileDetailedContainer) {
            mobileDetailedContainer.innerHTML = mobileDetailedHtml;
        }
    } else {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No partnership data available</td></tr>';
        mobileContainer.innerHTML = '<div class="text-center text-muted py-3">No partnership data available</div>';
        if (mobileDetailedContainer) {
            mobileDetailedContainer.innerHTML = '<div class="text-center text-muted py-3">No partnership data available</div>';
        }
    }
}


// Create Role Distribution Chart
function createRoleDistributionChart(roleData) {
    const ctx = document.getElementById('roleDistributionChart');
    if (!ctx) return;
    
    // Destroy existing chart if it exists
    if (window.roleDistributionChart instanceof Chart) {
        window.roleDistributionChart.destroy();
    }
    
    // Handle no data scenario
    if (!roleData || roleData.length === 0) {
        // Create professional empty state chart
        window.roleDistributionChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [''],
                datasets: [{
                    data: [0],
                    backgroundColor: ['transparent'],
                    borderColor: ['transparent'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                }
            },
            plugins: [{
                id: 'roleDistributionEmptyState',
                beforeDraw: function(chart) {
                    const ctx = chart.ctx;
                    const width = chart.width;
                    const height = chart.height;
                    
                    // Clear the entire canvas
                    ctx.clearRect(0, 0, width, height);
                    
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    
                    // Draw simple icon
                    ctx.font = '3rem sans-serif';
                    ctx.fillStyle = '#6c757d';
                    ctx.fillText('◐', width / 2, height / 2 - 25);
                    
                    // Draw main text
                    ctx.font = '1.1rem sans-serif';
                    ctx.fillStyle = '#495057';
                    ctx.fillText('No Role Data Available', width / 2, height / 2 + 5);
                    
                    // Draw subtitle
                    ctx.font = '0.9rem sans-serif';
                    ctx.fillStyle = '#6c757d';
                    ctx.fillText('Work on team assignments to see role distribution', width / 2, height / 2 + 25);
                    
                    ctx.restore();
                }
            }]
        });
        return;
    }
    
    const labels = roleData.map(item => item.role_type);
    const jobCounts = roleData.map(item => item.job_count);
    const completionRates = roleData.map(item => item.completion_rate);
    
    window.roleDistributionChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: jobCounts,
                backgroundColor: [
                    '#007bff',
                    '#6c757d'
                ],
                borderColor: [
                    '#007bff',
                    '#6c757d'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} jobs (${percentage}%)`;
                        },
                        afterLabel: function(context) {
                            const index = context.dataIndex;
                            return `Completion Rate: ${completionRates[index]}%`;
                        }
                    }
                }
            }
        }
    });
}

// Create all charts
function createCharts(data) {
    createRevenueChart(data);
    createServiceExpertiseChart(data);
    createSatisfactionChart(data);
}

// Enhanced Revenue Analytics function
async function updateRevenueAnalytics() {
    const loadingElement = document.getElementById('revenueLoading');
    
    try {
        // Show loading indicator
        if (loadingElement) {
            loadingElement.style.display = 'block';
            loadingElement.innerHTML = `
                <div class="spinner-border text-success mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mb-0">Loading revenue analytics...</p>
            `;
        }
        
        // Get values from both desktop and mobile selectors
        const timeframe = document.getElementById('revenueTimeframe')?.value || 
                         document.getElementById('revenueTimeframeMobile')?.value || '30';
        const chartType = document.getElementById('revenueChartType')?.value || 
                         document.getElementById('revenueChartTypeMobile')?.value || 'revenue';
        const year = document.getElementById('revenueYearFilter')?.value || 
                    document.getElementById('revenueYearFilterMobile')?.value || new Date().getFullYear();
        
        console.log('Fetching revenue analytics with params:', { timeframe, chartType, year });
        
        const apiUrl = `api/technician/statistics.php?timeframe=${timeframe}&chart_type=${chartType}&year=${year}`;
        const response = await fetch(apiUrl);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Revenue analytics response:', data);
        
        if (data.success) {
            // Hide loading indicator
            if (loadingElement) {
                loadingElement.style.display = 'none';
            }
            
            // Update key metrics
            updateRevenueMetrics(data);
            
            // Update chart
            createAdvancedRevenueChart(data, chartType);
            
            console.log('Revenue analytics updated successfully');
        } else {
            console.error('Error loading revenue analytics:', data.message || 'Unknown error');
            showRevenueError();
        }
    } catch (error) {
        console.error('Error fetching revenue analytics:', error);
        showRevenueError();
    }
}

// Update revenue metrics
function updateRevenueMetrics(data) {
    document.getElementById('totalRevenue').textContent = '₱' + (data.total_revenue || 0).toLocaleString();
    document.getElementById('totalJobs').textContent = data.total_jobs || 0;
    document.getElementById('avgRevenue').textContent = '₱' + (data.avg_revenue || 0).toLocaleString();
    const avgRatingElement = document.getElementById('avgRatingMetric');
    avgRatingElement.textContent = (data.average_rating || 0).toFixed(1);
    avgRatingElement.style.textShadow = 'none';
}

// Show error state for revenue analytics
function showRevenueError() {
    const loadingElement = document.getElementById('revenueLoading');
    if (loadingElement) {
        loadingElement.style.display = 'block';
        loadingElement.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="bi bi-exclamation-triangle fs-4 text-warning"></i><br>
                <p class="mb-2">Error loading analytics</p>
                <button class="btn btn-sm btn-outline-primary" onclick="updateRevenueAnalytics()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Retry
                </button>
            </div>
        `;
    }
    
    // Reset metrics to show error state
    document.getElementById('totalRevenue').textContent = 'Error';
    document.getElementById('totalJobs').textContent = 'Error';
    document.getElementById('avgRevenue').textContent = 'Error';
    document.getElementById('avgRatingMetric').textContent = 'Error';
}

// Advanced Revenue Chart with multiple chart types
function createAdvancedRevenueChart(data, chartType) {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) {
        console.error('Revenue chart canvas not found');
        return;
    }
    
    const context = ctx.getContext('2d');
    if (revenueChart) revenueChart.destroy();
    
    // Validate chart data
    const chartData = data.chart_data || {};
    const labels = chartData.labels || [];
    const datasets = chartData.datasets || [];
    
    if (labels.length === 0 || datasets.length === 0) {
        console.warn('No chart data available, creating empty chart');
        // Create empty chart with message
        revenueChart = new Chart(context, {
            type: 'line',
            data: {
                labels: ['No Data'],
                datasets: [{
                    label: 'No Data Available',
                    data: [0],
                    borderColor: '#6c757d',
                    backgroundColor: 'rgba(108, 117, 125, 0.1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'No Data Available'
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
        return;
    }
    
    let chartConfig = {
        data: {
            labels: labels,
            datasets: datasets
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
                },
                title: {
                    display: true,
                    text: chartType === 'revenue' ? 'Revenue Analytics' : 
                          chartType === 'jobs' ? 'Job Completion Analytics' : 
                          'Rating Trends Analytics'
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            if (chartType === 'revenue') {
                                return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString();
                            }
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            }
        }
    };
    
    if (chartType === 'ratings') {
        // Line chart for rating trends
        chartConfig.type = 'line';
        chartConfig.options.scales = {
            y: {
                beginAtZero: true,
                max: 5,
                title: {
                    display: true,
                    text: 'Rating (1-5 Stars)'
                },
                ticks: {
                    stepSize: 0.5
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Date'
                }
            }
        };
    } else if (chartType === 'jobs') {
        // Bar chart for job completion
        chartConfig.type = 'bar';
        chartConfig.options.scales = {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Jobs'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Date'
                }
            }
        };
    } else {
        // Line chart for revenue with dual axis
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
            x: {
                title: {
                    display: true,
                    text: 'Date'
                }
            }
        };
    }
    
    try {
        revenueChart = new Chart(context, chartConfig);
        console.log('Revenue chart created successfully');
    } catch (error) {
        console.error('Error creating revenue chart:', error);
        // Create a simple fallback chart
        revenueChart = new Chart(context, {
            type: 'line',
            data: {
                labels: ['Error'],
                datasets: [{
                    label: 'Chart Error',
                    data: [0],
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Chart Error - Please Refresh'
                    }
                }
            }
        });
    }
}

// Fallback simple revenue chart (for compatibility)
function createRevenueChart(data) {
    createAdvancedRevenueChart(data, 'revenue');
}

// Toggle service expertise chart visibility
function toggleServiceChart() {
    const chartContainer = document.getElementById('serviceChartContainer');
    const toggleBtn = document.getElementById('toggleExpertiseChart');
    
    if (chartContainer.style.display === 'none') {
        chartContainer.style.display = 'block';
        toggleBtn.innerHTML = '<i class="bi bi-list me-1"></i>Show List';
        // Create chart if not already created
        if (!serviceExpertiseChart) {
            const data = window.lastStatsData || {};
            createServiceExpertiseChart(data);
        }
    } else {
        chartContainer.style.display = 'none';
        toggleBtn.innerHTML = '<i class="bi bi-bar-chart me-1"></i>Show Chart';
    }
}

// Create service expertise list view
function createServiceExpertiseList(data) {
    const services = data.service_expertise || [];
    const listContainer = document.getElementById('serviceExpertiseList');
    
    if (services.length === 0) {
        listContainer.innerHTML = `
            <div class="text-center text-muted py-3">
                <i class="bi bi-info-circle me-2"></i>
                No service data available yet
            </div>
        `;
        return;
    }
    
    let listHTML = '';
    services.forEach((service, index) => {
        const rating = parseFloat(service.avg_rating);
        const jobCount = service.job_count;
        
        // Determine rating color and performance level
        let ratingClass = 'text-success';
        let performanceText = 'Excellent';
        if (rating < 3) {
            ratingClass = 'text-danger';
            performanceText = 'Needs Improvement';
        } else if (rating < 4) {
            ratingClass = 'text-warning';
            performanceText = 'Good';
        }
        
        // Create star display
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        let starsHTML = '';
        
        for (let i = 0; i < 5; i++) {
            if (i < fullStars) {
                starsHTML += '<i class="bi bi-star-fill text-warning"></i>';
            } else if (i === fullStars && hasHalfStar) {
                starsHTML += '<i class="bi bi-star-half text-warning"></i>';
            } else {
                starsHTML += '<i class="bi bi-star text-muted"></i>';
            }
        }
        
        listHTML += `
            <div class="border rounded p-3 mb-2 bg-light">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="mb-1 text-primary">${service.service_type}</h6>
                        <small class="text-muted">${jobCount} job${jobCount !== 1 ? 's' : ''} completed</small>
                    </div>
                    <span class="badge ${ratingClass === 'text-success' ? 'bg-success' : ratingClass === 'text-warning' ? 'bg-warning' : 'bg-danger'}">${performanceText}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        ${starsHTML}
                        <span class="ms-2 fw-bold ${ratingClass}">${rating.toFixed(1)}</span>
                    </div>
                    <small class="text-muted">Avg Rating</small>
                </div>
            </div>
        `;
    });
    
    listContainer.innerHTML = listHTML;
}

// Create service expertise chart
function createServiceExpertiseChart(data) {
    const ctx = document.getElementById('serviceExpertiseChart').getContext('2d');
    
    if (serviceExpertiseChart) serviceExpertiseChart.destroy();
    
    const services = data.service_expertise || [];
    const hasData = services.length > 0;
    
    if (!hasData) {
        // Create professional empty state chart
        serviceExpertiseChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [''],
                datasets: [{
                    data: [0],
                    backgroundColor: ['transparent'],
                    borderColor: ['transparent'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                },
                scales: {
                    y: { display: false },
                    x: { display: false }
                },
                elements: {
                    bar: {
                        backgroundColor: 'transparent',
                        borderColor: 'transparent'
                    }
                }
            },
            plugins: [{
                id: 'serviceExpertiseEmptyState',
                beforeDraw: function(chart) {
                    const ctx = chart.ctx;
                    const width = chart.width;
                    const height = chart.height;
                    
                    // Clear the entire canvas
                    ctx.clearRect(0, 0, width, height);
                    
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    
                    // Draw simple icon
                    ctx.font = '3rem sans-serif';
                    ctx.fillStyle = '#6c757d';
                    ctx.fillText('⚙', width / 2, height / 2 - 25);
                    
                    // Draw main text
                    ctx.font = '1.1rem sans-serif';
                    ctx.fillStyle = '#495057';
                    ctx.fillText('No Service Expertise Data', width / 2, height / 2 + 5);
                    
                    // Draw subtitle
                    ctx.font = '0.9rem sans-serif';
                    ctx.fillStyle = '#6c757d';
                    ctx.fillText('Complete some jobs to see your service expertise', width / 2, height / 2 + 25);
                    
                    ctx.restore();
                }
            }]
        });
        return;
    }
    
    const labels = services.map(s => s.service_type);
    const ratings = services.map(s => parseFloat(s.avg_rating));
    const jobCounts = services.map(s => s.job_count);
    
    serviceExpertiseChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Average Rating',
                data: ratings,
                backgroundColor: labels.map((label, index) => {
                    const colors = [
                        '#007bff',    // Blue
                        '#28a745',    // Green
                        '#ffc107',    // Yellow
                        '#dc3545',    // Red
                        '#6c757d',    // Gray
                        '#6610f2',    // Purple
                        '#e83e8c',    // Pink
                        '#17a2b8',    // Light Blue
                        '#fd7e14',    // Orange
                        '#20c997'     // Teal
                    ];
                    return colors[index % colors.length];
                }),
                borderColor: labels.map((label, index) => {
                    const colors = [
                        '#007bff',    // Blue
                        '#28a745',    // Green
                        '#ffc107',    // Yellow
                        '#dc3545',    // Red
                        '#6c757d',    // Gray
                        '#6610f2',    // Purple
                        '#e83e8c',    // Pink
                        '#36a2eb',    // Light Blue
                        '#ff9f40',    // Orange
                        '#4bc0c0'     // Teal
                    ];
                    return colors[index % colors.length];
                }),
                borderWidth: 2,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    callbacks: {
                        afterLabel: function(context) {
                            const index = context.dataIndex;
                            const jobCount = jobCounts[index] || 0;
                            return `Jobs completed: ${jobCount}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return value + ' ★';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Rating'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Service Types'
                    },
                    ticks: {
                        maxRotation: 45,
                        minRotation: 0
                    }
                }
            }
        }
    });
}

// Create satisfaction chart
function createSatisfactionChart(data) {
    const ctx = document.getElementById('satisfactionChart').getContext('2d');
    
    if (satisfactionChart) satisfactionChart.destroy();
    
    const ratingDist = data.rating_distribution || [0, 0, 0, 0, 0];
    
    // Check if all ratings are zero (no data)
    const hasData = ratingDist.some(rating => rating > 0);
    
    if (!hasData) {
        // Create professional empty state chart
        satisfactionChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [''],
                datasets: [{
                    data: [0],
                    backgroundColor: ['transparent'],
                    borderColor: ['transparent'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                }
            },
            plugins: [{
                id: 'satisfactionEmptyState',
                beforeDraw: function(chart) {
                    const ctx = chart.ctx;
                    const width = chart.width;
                    const height = chart.height;
                    
                    // Clear the entire canvas
                    ctx.clearRect(0, 0, width, height);
                    
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    
                    // Draw simple icon
                    ctx.font = '3rem sans-serif';
                    ctx.fillStyle = '#6c757d';
                    ctx.fillText('★', width / 2, height / 2 - 25);
                    
                    // Draw main text
                    ctx.font = '1.1rem sans-serif';
                    ctx.fillStyle = '#495057';
                    ctx.fillText('No Customer Ratings Yet', width / 2, height / 2 + 5);
                    
                    // Draw subtitle
                    ctx.font = '0.9rem sans-serif';
                    ctx.fillStyle = '#6c757d';
                    ctx.fillText('Customer ratings will appear here after job completion', width / 2, height / 2 + 25);
                    
                    ctx.restore();
                }
            }]
        });
        return;
    }
    
    satisfactionChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                data: ratingDist,
                backgroundColor: [
                    '#dc3545',    // Red - 1 Star
                    '#fd7e14',    // Orange - 2 Stars  
                    '#ffc107',    // Yellow - 3 Stars
                    '#28a745',    // Green - 4 Stars
                    '#007bff'     // Blue - 5 Stars
                ],
                borderColor: [
                    '#dc3545',    // Red
                    '#fd7e14',    // Orange
                    '#ffc107',    // Yellow
                    '#28a745',    // Green
                    '#007bff'     // Blue
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15 },
                    display: true
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });
}

// Show error state
function showErrorState() {
    // Update all data elements with error messages
    const elements = [
        { id: 'frequentCustomer', text: 'Failed to load data' },
        { id: 'thisMonthJobs', text: 'Error' },
        { id: 'thisMonthRevenue', text: 'Error' },
        { id: 'customer1', text: 'Error' },
        { id: 'customer2', text: 'Error' },
        { id: 'customer3', text: 'Error' },
        { id: 'topServiceType', text: 'Error' },
        { id: 'performanceBadge', text: 'Error Loading Data' },
        { id: 'teamJobsPercentage', text: 'Error' },
        { id: 'uniquePartners', text: 'Error' },
        { id: 'primaryRoleJobs', text: 'Error' },
        { id: 'secondaryRoleJobs', text: 'Error' }
    ];
    
    elements.forEach(element => {
        const el = document.getElementById(element.id);
        if (el) {
            el.textContent = element.text;
            el.className = el.className.replace(/bg-\w+/, 'bg-danger');
        }
    });
    
    // Update tables
    const tbody = document.getElementById('recentPerformanceTable');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Failed to load data</td></tr>';
    }
    
    const partnershipTable = document.getElementById('partnershipTable');
    if (partnershipTable) {
        partnershipTable.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Failed to load data</td></tr>';
    }
    
    // Update mobile containers
    const mobileContainers = [
        'recentPerformanceMobile',
        'partnershipMobile', 
        'partnershipMobileDetailed'
    ];
    
    mobileContainers.forEach(containerId => {
        const container = document.getElementById(containerId);
        if (container) {
            container.innerHTML = '<div class="text-center text-danger py-3"><i class="bi bi-exclamation-triangle me-2"></i>Failed to load data</div>';
        }
    });
    
    // Create error charts
    createErrorChart('serviceExpertiseChart', 'Service data unavailable');
    createErrorChart('satisfactionChart', 'Rating data unavailable');
    createErrorChart('roleDistributionChart', 'Role data unavailable');
}

// Create error chart for no data scenarios
function createErrorChart(canvasId, message) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['No Data'],
            datasets: [{
                data: [1],
                backgroundColor: ['#f8d7da'],
                borderColor: ['#dc3545'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        },
        plugins: [{
            id: 'errorText',
            beforeDraw: function(chart) {
                const ctx = chart.ctx;
                const width = chart.width;
                const height = chart.height;
                
                ctx.restore();
                const fontSize = (height / 150).toFixed(2);
                ctx.font = fontSize + "em sans-serif";
                ctx.textBaseline = "middle";
                ctx.fillStyle = "#dc3545";
                
                const textX = Math.round((width - ctx.measureText(message).width) / 2);
                const textY = height / 2;
                
                ctx.fillText(message, textX, textY);
                ctx.save();
            }
        }]
    });
}

// Refresh data every 5 minutes
setInterval(loadTechnicianStatistics, 300000);
</script>
