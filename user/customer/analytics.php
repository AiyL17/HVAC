<?php
// Note: session_start() and includes are handled by index.php
// $userClass is already available from index.php
// Authentication is handled by the main routing system

$customer_id = $_SESSION['uid'];
$userDetails = $userClass->userDetails($customer_id);
?>

<style>
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
        padding: 12px 20px;
        border-radius: 12px 12px 0 0;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .dashboard-card-body {
        padding: 20px;
    }

    /* Hover effects consistent with dashboard */
    .hover-card {
        transition: all 0.3s ease-in-out;
    }
    
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    /* Metric cards styling */
    .metric-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease-in-out;
    }
    
    .maintenance-predictions {
        text-align: left !important;
    }
    
    .maintenance-predictions h6 {
        text-align: left !important;
        margin-left: 0 !important;
        padding-left: 0 !important;
    }
    
    .maintenance-predictions .d-flex > div:first-child {
        text-align: left !important;
    }

    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .metric-value {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: #007bff;
    }

    .metric-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }

    .chart-container {
        position: relative;
        height: 300px;
        margin: 1rem 0;
    }

    .loading-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 200px;
    }

    .insight-badge {
        display: inline-block;
        background: rgba(0, 123, 255, 0.1);
        color: #007bff;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        margin: 0.25rem;
    }

    /* Mobile responsive */
    @media (max-width: 991.98px) {
        .dashboard-card-body {
            padding: 15px;
        }
        
        .metric-card {
            margin-bottom: 1rem;
        }
    }
</style>
    <!-- Page Header -->
    <h3 class="mb-4">My Service Journey</h3>
    
    <!-- My Service Analytics Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="dashboard-card-header">
                    <!-- Desktop header layout -->
                    <div class="d-none d-md-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-graph-up me-2" style="font-size: 1.1rem;"></i>
                            <span>My Service Analytics</span>
                        </div>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="customerAnalyticsTimeframe" style="width: auto; font-size: 0.875rem;">
                                <option value="30" selected>Last 30 Days</option>
                                <option value="90">Last 3 Months</option>
                                <option value="365">Last Year</option>
                            </select>
                            <select class="form-select form-select-sm" id="customerAnalyticsType" style="width: auto; font-size: 0.875rem;">
                                <option value="appointments">My Appointments</option>
                                <option value="spending">Services & Ratings</option>
                                <option value="services">Service Types</option>
                            </select>
                            <select class="form-select form-select-sm" id="customerAnalyticsYear" style="width: auto; font-size: 0.875rem;">
                                <!-- Year options will be populated dynamically -->
                            </select>
                        </div>
                    </div>
                    
                    <!-- Mobile header layout -->
                    <div class="d-md-none">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-graph-up me-2" style="font-size: 1.1rem;"></i>
                            <span>My Service Analytics</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-12">
                                <select class="form-select form-select-sm" id="customerAnalyticsTimeframeMobile" style="font-size: 0.875rem;">
                                    <option value="30" selected>Last 30 Days</option>
                                    <option value="90">Last 3 Months</option>
                                    <option value="365">Last Year</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select form-select-sm" id="customerAnalyticsTypeMobile" style="font-size: 0.875rem;">
                                    <option value="appointments">My Appointments</option>
                                    <option value="spending">Services & Ratings</option>
                                    <option value="services">Service Types</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select form-select-sm" id="customerAnalyticsYearMobile" style="font-size: 0.875rem;">
                                    <!-- Year options will be populated dynamically -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboard-card-body">
                    <div style="height: 300px; position: relative;">
                        <canvas id="customerAnalyticsChart"></canvas>
                        <div id="customerAnalyticsLoading" class="text-center py-4" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mb-0">Loading your analytics...</p>
                        </div>
                    </div>
                    
                    <!-- Customer Key Metrics Row -->
                    <div class="row mt-3 pt-3 border-top g-3">
                        <div class="col-md-3 col-6 text-center">
                            <div class="metric-item p-2 h-100">
                                <h5 class="mb-1 text-primary" id="customerTotalServices">0</h5>
                                <small class="text-muted">Total Services</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <div class="metric-item p-2 h-100">
                                <h5 class="mb-1 text-success" id="customerTotalSpent">₱0</h5>
                                <small class="text-muted">Total Spent</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <div class="metric-item p-2 h-100">
                                <h5 class="mb-1 text-info" id="customerAvgServiceTime">0 days</h5>
                                <small class="text-muted">Avg Service Time</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <div class="metric-item p-2 h-100">
                                <h5 class="mb-1 text-warning" id="customerSatisfactionScore">5.0</h5>
                                <small class="text-muted">My Avg Rating</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Your Trusted Technicians -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card dashboard-card">
                <div class="dashboard-card-header">
                    <i class="bi bi-people-fill me-2"></i>Your Trusted Technicians
                </div>
                <div class="dashboard-card-body">
                    <div id="technicianRelationships" class="loading-spinner">
                        <div class="d-flex justify-content-center align-items-center py-5">
                            <div class="spinner-border text-primary me-3" role="status"></div>
                            <span class="text-muted">Loading your technician relationships...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Equipment Health & Maintenance Intelligence Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="dashboard-card-header">
                    <i class="bi bi-gear-fill me-2"></i>Equipment Health & Maintenance Intelligence
                </div>
                <div class="dashboard-card-body">
                    <div class="row">
                        <!-- Equipment Health Score -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="metric-card h-100">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <div id="healthScoreChart" style="height: 120px; position: relative;">
                                            <canvas id="healthScoreCanvas"></canvas>
                                        </div>
                                    </div>
                                    <h6 class="text-primary mb-1">Overall Equipment Health</h6>
                                    <small class="text-muted">Based on service frequency and ratings</small>
                                </div>
                            </div>
                        </div>

                        <!-- Equipment Performance Trends -->
                        <div class="col-lg-8 col-md-6 mb-4">
                            <div class="metric-card h-100">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-graph-up me-2"></i>Equipment Performance Trends
                                </h6>
                                <div class="chart-container" style="height: 200px;">
                                    <canvas id="equipmentTrendsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Maintenance Schedule Predictions -->
                        <div class="col-md-6 mb-4">
                            <div class="metric-card h-100">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-calendar-week me-2"></i>Maintenance Schedule Predictions
                                </h6>
                                <div id="maintenancePredictions" class="maintenance-predictions">
                                    <!-- Dynamic content will be loaded here -->
                                </div>
                            </div>
                        </div>

                        <!-- Equipment Reliability Analysis -->
                        <div class="col-md-6 mb-4">
                            <div class="metric-card h-100">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-shield-check me-2"></i>Equipment Reliability Analysis
                                </h6>
                                <div id="reliabilityAnalysis" class="reliability-analysis">
                                    <!-- Dynamic content will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Cost Analysis & Optimization -->
                        <div class="col-12">
                            <div class="metric-card">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-currency-dollar me-2"></i>Cost Analysis & Optimization
                                </h6>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="chart-container" style="height: 250px;">
                                            <canvas id="costAnalysisChart"></canvas>
                                        </div>
                                        <div id="serviceCostTotals" class="mt-3">
                                            <!-- Service type totals will be displayed here -->
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div id="costInsights" class="cost-insights">
                                            <!-- Dynamic content will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency & Risk Management Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="dashboard-card-header">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Emergency & Risk Management
                </div>
                <div class="dashboard-card-body">
                    <div class="row">
                        <!-- Risk Assessment Dashboard -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="metric-card h-100">
                                <h6 class="text-danger mb-3">
                                    <i class="bi bi-shield-exclamation me-2"></i>Risk Assessment
                                </h6>
                                <div id="riskAssessment" class="risk-assessment">
                                    <!-- Dynamic content will be loaded here -->
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Response Analytics -->
                        <div class="col-lg-8 col-md-6 mb-4">
                            <div class="metric-card h-100">
                                <h6 class="text-danger mb-3">
                                    <i class="bi bi-clock-history me-2"></i>Emergency Response Analytics
                                </h6>
                                <div class="chart-container" style="height: 200px;">
                                    <canvas id="emergencyResponseChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Equipment Failure Patterns -->
                        <div class="col-lg-6 mb-4">
                            <div class="metric-card h-100">
                                <h6 class="text-danger mb-3">
                                    <i class="bi bi-graph-down me-2"></i>Equipment Failure Patterns
                                </h6>
                                <div id="failurePatterns" class="failure-patterns">
                                    <!-- Dynamic content will be loaded here -->
                                </div>
                            </div>
                        </div>

                        <!-- Financial Risk Monitoring -->
                        <div class="col-lg-6 mb-4">
                            <div class="metric-card h-100">
                                <h6 class="text-danger mb-3">
                                    <i class="bi bi-credit-card me-2"></i>Financial Risk Monitoring
                                </h6>
                                <div id="financialRisk" class="financial-risk">
                                    <!-- Dynamic content will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        let analyticsData = null;
        let charts = {};

        // Load analytics data on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeCustomerYearFilter();
            setupCustomerAnalyticsEventListeners();
            loadAnalyticsData(); // Load main analytics data first
            loadCustomerAnalytics(); // Then load customer analytics
        });

        async function loadAnalyticsData() {
            try {
                const response = await fetch('api/customer/analytics.php');
                const result = await response.json();
                
                if (result.success) {
                    analyticsData = result.data;
                    renderAllAnalytics();
                    // Initialize advanced analytics after data is loaded
                    initializeAdvancedAnalytics();
                } else {
                    console.error('Analytics error:', result.error);
                    showError('Failed to load analytics data');
                }
            } catch (error) {
                console.error('Error loading analytics:', error);
                showError('Failed to load analytics data');
            }
        }

        function renderAllAnalytics() {
            renderTechnicianRelationships();
        }

        // Customer Analytics functionality from dashboard
        let customerAnalyticsChart = null;

        async function loadCustomerAnalytics() {
            const timeframe = document.getElementById('customerAnalyticsTimeframe')?.value || 
                             document.getElementById('customerAnalyticsTimeframeMobile')?.value || '30';
            const type = document.getElementById('customerAnalyticsType')?.value || 
                        document.getElementById('customerAnalyticsTypeMobile')?.value || 'appointments';
            const year = document.getElementById('customerAnalyticsYear')?.value || 
                        document.getElementById('customerAnalyticsYearMobile')?.value || new Date().getFullYear();
            
            // Show loading
            document.getElementById('customerAnalyticsLoading').style.display = 'block';
            
            try {
                const response = await fetch(`api/customer/get_analytics.php?timeframe=${timeframe}&type=${type}&year=${year}`);
                const data = await response.json();
                
                if (data.error) {
                    console.error('Analytics error:', data.error);
                    return;
                }
                
                // Update chart (appointments will always include spending by default)
                updateCustomerAnalyticsChart(data.chartData, type);
                
                // Update metrics
                document.getElementById('customerTotalServices').textContent = data.totalServices || '0';
                document.getElementById('customerTotalSpent').textContent = `₱${(data.totalSpent || 0).toLocaleString()}`;
                document.getElementById('customerAvgServiceTime').textContent = `${data.avgServiceTime || 0} days`;
                document.getElementById('customerSatisfactionScore').textContent = data.satisfactionScore || '0';
                
            } catch (error) {
                console.error('Error loading analytics:', error);
            } finally {
                // Hide loading
                document.getElementById('customerAnalyticsLoading').style.display = 'none';
                
                // Initialize advanced analytics after customer analytics is loaded
                setTimeout(() => {
                    initializeAdvancedAnalytics();
                }, 500);
            }
        }

        function updateCustomerAnalyticsChart(chartData, type) {
            const ctx = document.getElementById('customerAnalyticsChart').getContext('2d');
            
            // Global variables for chart instances
            let customerAnalyticsChart;
            let charts = {};
        
            // Chart destruction helper function
            function destroyChart(chartId) {
                if (Chart.getChart(chartId)) {
                    Chart.getChart(chartId).destroy();
                }
            }
            
            // Destroy existing chart
            destroyChart('customerAnalyticsChart');
            
            // Check if we have dual datasets (appointments includes spending, spending includes services & ratings)
            const isDualDataset = (type === 'appointments' || type === 'spending') && chartData.datasets;
            
            // Always show chart with data or zeros - no empty state handling needed
            const hasValidData = chartData && chartData.labels && chartData.labels.length > 0;
            
            let chartConfig = {
                type: type === 'services' ? 'doughnut' : 'line',
                data: {
                    labels: hasValidData ? chartData.labels : ['No Data'],
                    datasets: hasValidData ? 
                        (isDualDataset ? chartData.datasets : [{
                            label: chartData.label || 'Data',
                            data: chartData.data || [],
                            borderColor: '#007bff',
                            backgroundColor: type === 'services' ? [
                                '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1'
                            ] : 'rgba(0, 123, 255, 0.1)',
                            borderWidth: 2,
                            fill: type !== 'services',
                            tension: 0.4
                        }]) : (isDualDataset ? [
                            {
                                label: 'Appointments',
                                data: [0],
                                borderColor: '#007bff',
                                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                yAxisID: 'y',
                                tension: 0.4,
                            },
                            {
                                label: 'Spending (₱)',
                                data: [0],
                                borderColor: '#28a745',
                                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                yAxisID: 'y1',
                                tension: 0.4,
                            }
                        ] : [{
                            label: chartData.label || 'Data',
                            data: [0],
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.1)',
                            borderWidth: 2,
                            fill: false,
                            tension: 0.4
                        }])
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: type === 'services' || isDualDataset,
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    scales: type === 'services' ? {} : {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            title: {
                                display: isDualDataset,
                                text: type === 'appointments' ? 'Appointments' : 'Services Rendered',
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    if (type === 'appointments' && isDualDataset) {
                                        return Math.round(value); // Appointments count
                                    } else if (type === 'spending' && isDualDataset) {
                                        return Math.round(value); // Services rendered count
                                    }
                                    return value;
                                }
                            }
                        },
                        y1: isDualDataset ? {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: type === 'appointments' ? 'Spending (₱)' : 'Ratings Given',
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
                            ticks: {
                                callback: function(value) {
                                    if (type === 'appointments') {
                                        return '₱' + value.toLocaleString(); // Spending amount
                                    } else {
                                        return Math.round(value); // Ratings count
                                    }
                                }
                            }
                        } : undefined
                    },
                    elements: {
                        point: {
                            radius: 4,
                            hoverRadius: 6,
                            borderWidth: 2
                        },
                        line: {
                            borderWidth: 3,
                            tension: 0.4
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            };
            
            customerAnalyticsChart = new Chart(ctx, chartConfig);
        }



        function renderTechnicianRelationships() {
            const data = (analyticsData && analyticsData.technician_relationships) || [];
            const container = document.getElementById('technicianRelationships');
            
            if (data.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-person-plus-fill" style="font-size: 3rem; color: #dee2e6;"></i>
                        </div>
                        <h6 class="text-muted mb-2">No Technician History</h6>
                        <p class="text-muted small mb-0">You haven't worked with any technicians yet</p>
                    </div>
                `;
                return;
            }

            const html = `
                <div class="technician-scroll-container" style="height: 220px; overflow-x: auto; overflow-y: hidden; scrollbar-width: thin; scrollbar-color: #6c757d #f1f3f4;">
                    <div class="d-flex gap-3 py-2" style="min-height: 200px; align-items: flex-start;">
                        ${data.map((tech, index) => {
                            const rankColors = ['#28a745', '#FFD700', '#CD7F32']; // Green, Gold, Bronze
                            const rankLabels = ['Most Trusted', 'Highly Rated', 'Reliable'];
                            
                            const rankColor = rankColors[index] || '#007bff';
                            const rankLabel = rankLabels[index] || 'Trusted';
                            const widthPercentage = parseFloat(tech.avg_rating_given) * 20;
                            
                            return `
                                <div class="technician-card" style="min-width: 200px; height: 190px; flex-shrink: 0; background: white; border-radius: 12px; padding: 15px; text-align: center; border: 1px solid #e9ecef; transition: all 0.3s ease; display: flex; flex-direction: column; justify-content: center;">
                                    <div class="technician-avatar mb-2" style="position: relative; display: inline-block;">
                                        <img src="././img/tech.png" alt="" width="50" style="border-radius: 50%; border: 2px solid #e9ecef;">
                                        <div style="position: absolute; bottom: -3px; right: -3px; width: 18px; height: 18px; background: #28a745; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white;">
                                            <i class="bi bi-tools" style="font-size: 0.5rem; color: white;"></i>
                                        </div>
                                    </div>
                                    
                                    <h6 class="mb-1 fw-bold text-dark" style="font-size: 0.9rem;">${tech.technician_name}</h6>
                                    
                                    <div class="mb-2">
                                        <span class="badge rounded-pill" style="background: ${rankColor}; color: white; font-size: 0.7rem; padding: 3px 10px;">
                                            ${rankLabel}
                                        </span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-center align-items-center mb-2">
                                        <div style="width: 50px; height: 3px; background: #e9ecef; border-radius: 2px; margin-right: 6px;">
                                            <div style="width: ${widthPercentage}%; height: 100%; background: #007bff; border-radius: 2px;"></div>
                                        </div>
                                        <span style="font-size: 0.9rem; font-weight: 600; color: #ffc107;">
                                            ${parseFloat(tech.avg_rating_given).toFixed(1)} <i class="bi bi-star-fill" style="font-size: 0.7rem;"></i>
                                        </span>
                                    </div>
                                    
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        ${tech.times_served} service${tech.times_served !== 1 ? 's' : ''}<br>completed
                                    </small>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
                
                <style>
                    #technicianRelationships .technician-card:hover {
                        transform: translateY(-3px);
                        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
                    }
                    
                    /* Custom scrollbar for technician section */
                    #technicianRelationships .technician-scroll-container::-webkit-scrollbar {
                        height: 10px;
                    }
                    
                    #technicianRelationships .technician-scroll-container::-webkit-scrollbar-track {
                        background: #f1f3f4;
                        border-radius: 5px;
                        margin: 0 15px;
                    }
                    
                    #technicianRelationships .technician-scroll-container::-webkit-scrollbar-thumb {
                        background: #6c757d;
                        border-radius: 5px;
                    }
                    
                    #technicianRelationships .technician-scroll-container::-webkit-scrollbar-thumb:hover {
                        background: #5a6268;
                    }
                    
                    /* Ensure scrollbar is always visible when content overflows */
                    #technicianRelationships .technician-scroll-container {
                        scrollbar-width: auto;
                    }
                </style>
            `;
            
            container.innerHTML = html;
            
            // Hover effects are now handled by CSS
        }

        function renderMaintenancePatterns() {
            const data = analyticsData.maintenance_patterns;
            const ctx = document.getElementById('maintenancePatternsChart').getContext('2d');
            
            charts.maintenancePatterns = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.map(item => item.appliance_category),
                    datasets: [{
                        data: data.map(item => item.total_services),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(255, 159, 64, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        function renderSeasonalPatterns() {
            const data = analyticsData.seasonal_patterns;
            const ctx = document.getElementById('seasonalPatternsChart').getContext('2d');
            
            charts.seasonalPatterns = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.month_name),
                    datasets: [{
                        label: 'Appointments',
                        data: data.map(item => item.appointment_count),
                        backgroundColor: 'rgba(111, 66, 193, 0.8)',
                        borderColor: 'rgba(111, 66, 193, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function renderServiceRecommendations() {
            const data = analyticsData.service_recommendations;
            const container = document.getElementById('serviceRecommendations');
            
            if (data.length === 0) {
                container.innerHTML = `
                    <div class="text-center">
                        <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-success">You're all set! 🎉</h5>
                        <p class="text-muted">Your equipment is well-maintained. Keep up the great work!</p>
                        <div class="mt-3">
                            <small class="text-muted">💡 Tip: Regular maintenance helps prevent costly repairs</small>
                        </div>
                    </div>
                `;
                return;
            }

            const html = data.map(rec => `
                <div class="alert alert-info border-0 mb-3" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1), rgba(0, 242, 254, 0.1));">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-calendar-check text-info me-3 mt-1" style="font-size: 1.2rem;"></i>
                        <div class="flex-grow-1">
                            <h6 class="alert-heading mb-1">${rec.item}</h6>
                            <p class="mb-1">${rec.recommendation}</p>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>Last serviced ${rec.days_since_last_service} days ago
                            </small>
                        </div>
                    </div>
                </div>
            `).join('');
            
            container.innerHTML = html;
        }

        function renderPredictiveInsights() {
            const data = analyticsData.predictive_insights;
            const container = document.getElementById('predictiveInsights');
            
            if (data.length === 0) {
                container.innerHTML = '<p class="text-muted text-center">Not enough historical data for predictions</p>';
                return;
            }

            const html = data.map(insight => {
                const nextServiceDate = insight.predicted_next_service ? new Date(insight.predicted_next_service).toLocaleDateString() : 'TBD';
                return `
                    <div class="border-bottom pb-3 mb-3">
                        <h6 class="text-primary">${insight.service_type_name}</h6>
                        <p class="mb-1">Predicted next service: <strong>${nextServiceDate}</strong></p>
                        <small class="text-muted">Based on ${insight.historical_count} previous services</small>
                    </div>
                `;
            }).join('');
            
            container.innerHTML = html;
        }

        // Initialize year filter dropdown for customer analytics
        function initializeCustomerYearFilter() {
            const currentYear = new Date().getFullYear();
            const yearFilter = document.getElementById('customerAnalyticsYear');
            const yearFilterMobile = document.getElementById('customerAnalyticsYearMobile');
            
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

        // Setup event listeners for customer analytics filters
        function setupCustomerAnalyticsEventListeners() {
            // Desktop event listeners
            const timeframeSelect = document.getElementById('customerAnalyticsTimeframe');
            const typeSelect = document.getElementById('customerAnalyticsType');
            const yearSelect = document.getElementById('customerAnalyticsYear');
            
            // Mobile event listeners
            const timeframeSelectMobile = document.getElementById('customerAnalyticsTimeframeMobile');
            const typeSelectMobile = document.getElementById('customerAnalyticsTypeMobile');
            const yearSelectMobile = document.getElementById('customerAnalyticsYearMobile');
            
            // Desktop listeners
            if (timeframeSelect) {
                timeframeSelect.addEventListener('change', function() {
                    // Sync with mobile
                    if (timeframeSelectMobile) timeframeSelectMobile.value = this.value;
                    loadCustomerAnalytics();
                });
            }
            
            if (typeSelect) {
                typeSelect.addEventListener('change', function() {
                    // Sync with mobile
                    if (typeSelectMobile) typeSelectMobile.value = this.value;
                    loadCustomerAnalytics();
                });
            }
            
            if (yearSelect) {
                yearSelect.addEventListener('change', function() {
                    // Sync with mobile
                    if (yearSelectMobile) yearSelectMobile.value = this.value;
                    loadCustomerAnalytics();
                });
            }
            
            // Mobile listeners
            if (timeframeSelectMobile) {
                timeframeSelectMobile.addEventListener('change', function() {
                    // Sync with desktop
                    if (timeframeSelect) timeframeSelect.value = this.value;
                    loadCustomerAnalytics();
                });
            }
            
            if (typeSelectMobile) {
                typeSelectMobile.addEventListener('change', function() {
                    // Sync with desktop
                    if (typeSelect) typeSelect.value = this.value;
                    loadCustomerAnalytics();
                });
            }
            
            if (yearSelectMobile) {
                yearSelectMobile.addEventListener('change', function() {
                    // Sync with desktop
                    if (yearSelect) yearSelect.value = this.value;
                    loadCustomerAnalytics();
                });
            }
        }

        function showError(message) {
            // Show error message to user
            console.error(message);
        }

        // Equipment Health & Maintenance Intelligence Functions
        function renderHealthScore() {
            const ctx = document.getElementById('healthScoreCanvas');
            if (!ctx) return;

            // Destroy existing chart if it exists
            if (Chart.getChart('healthScoreCanvas')) {
                Chart.getChart('healthScoreCanvas').destroy();
            }

            const data = (analyticsData && analyticsData.equipment_health) || null;
            
            // Check if we have real data from the database
            if (!data || !data.score) {
                // Show empty state instead of dummy data - replace the entire metric card content
                const metricCard = ctx.closest('.metric-card');
                metricCard.innerHTML = `
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-gear-fill me-2"></i>Overall Equipment Health
                    </h6>
                    <div class="empty-state text-center py-4">
                        <i class="bi bi-gear text-muted" style="font-size: 2.5rem;"></i>
                        <h6 class="text-muted mt-3">No Equipment Data</h6>
                        <p class="text-muted mb-0 small">Complete some services to see your equipment health score</p>
                    </div>
                `;
                return;
            }
            
            const score = data.score;
            const color = score >= 80 ? '#28a745' : score >= 60 ? '#ffc107' : '#dc3545';

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [score, 100 - score],
                        backgroundColor: [color, '#e9ecef'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    }
                },
                plugins: [{
                    beforeDraw: function(chart) {
                        const width = chart.width,
                              height = chart.height,
                              ctx = chart.ctx;
                        ctx.restore();
                        const fontSize = (height / 114).toFixed(2);
                        ctx.font = fontSize + "em sans-serif";
                        ctx.textBaseline = "middle";
                        ctx.fillStyle = color;
                        const text = score + "%",
                              textX = Math.round((width - ctx.measureText(text).width) / 2),
                              textY = height / 2;
                        ctx.fillText(text, textX, textY);
                        ctx.save();
                    }
                }]
            });
        }

        function renderEquipmentTrends() {
            const data = (analyticsData && analyticsData.equipment_trends) || [];
            const equipmentReliability = (analyticsData && analyticsData.equipment_reliability) || [];
            const ctx = document.getElementById('equipmentTrendsChart');
            if (!ctx) return;

            // Check if we have real data from the database
            if ((!data || data.length === 0) && (!equipmentReliability || equipmentReliability.length === 0)) {
                // Show empty state instead of dummy data
                const container = ctx.parentElement;
                container.innerHTML = `
                    <div class="empty-state text-center py-4">
                        <i class="bi bi-graph-up text-muted" style="font-size: 2.5rem;"></i>
                        <h6 class="text-muted mt-3">No Performance Data</h6>
                        <p class="text-muted mb-0 small">Complete services to see equipment performance trends</p>
                    </div>
                `;
                return;
            }

            // Destroy existing chart if it exists
            if (Chart.getChart('equipmentTrendsChart')) {
                Chart.getChart('equipmentTrendsChart').destroy();
            }

            let chartLabels, performanceData, reliabilityData;

            if (data.length > 0) {
                // Use real monthly performance trends
                chartLabels = data.map(trend => {
                    const date = new Date(trend.month + '-01');
                    return date.toLocaleDateString('en-US', { month: 'short', year: '2-digit' });
                });
                
                performanceData = data.map(trend => parseFloat(trend.avg_rating) || 0);
                reliabilityData = data.map(trend => {
                    const reliabilityScore = trend.service_count > 0 ? 
                        Math.round((trend.reliable_services / trend.service_count) * 100) : 0;
                    return reliabilityScore;
                });
            } else {
                // Fallback if no monthly data available
                chartLabels = ['Current'];
                
                if (equipmentReliability.length > 0) {
                    const avgRating = equipmentReliability.reduce((sum, eq) => sum + eq.avg_rating, 0) / equipmentReliability.length;
                    const avgReliability = equipmentReliability.reduce((sum, eq) => sum + eq.reliability_score, 0) / equipmentReliability.length;
                    
                    performanceData = [avgRating];
                    reliabilityData = [avgReliability];
                } else {
                    performanceData = [0];
                    reliabilityData = [0];
                }
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Performance Rating',
                        data: performanceData,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y',
                        fill: true
                    }, {
                        label: 'Reliability Score',
                        data: reliabilityData,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y1',
                        fill: true
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
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (context.datasetIndex === 0) {
                                        return `Performance: ${context.parsed.y.toFixed(1)}/5.0`;
                                    } else {
                                        return `Reliability: ${context.parsed.y}%`;
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            min: 0,
                            max: 5,
                            title: { display: true, text: 'Rating (1-5)' },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            min: 0,
                            max: 100,
                            title: { display: true, text: 'Reliability %' },
                            grid: { drawOnChartArea: false },
                            ticks: {
                                stepSize: 20
                            }
                        }
                    }
                }
            });
        }

        function renderMaintenancePredictions() {
            const container = document.getElementById('maintenancePredictions');
            if (!container) return;

            const predictions = (analyticsData && analyticsData.maintenance_predictions) || [];
            
            // Check if we have real data from the database
            if (!predictions || predictions.length === 0) {
                container.innerHTML = `
                    <div class="empty-state text-center py-4">
                        <i class="bi bi-calendar-week text-muted" style="font-size: 2.5rem;"></i>
                        <h6 class="text-muted mt-3">No Maintenance Data</h6>
                        <p class="text-muted mb-0 small">Schedule services to see maintenance predictions</p>
                    </div>
                `;
                return;
            }

            const html = predictions.map(pred => {
                const priorityColor = pred.priority === 'High' ? 'danger' : pred.priority === 'Medium' ? 'warning' : 'success';
                return `<div class="border-bottom pb-2 mb-2"><div class="d-flex justify-content-between align-items-center"><div><h6 class="mb-1" style="text-align: left; margin-left: 0; padding-left: 0;">${pred.equipment}</h6><small class="text-muted" style="text-align: left;">Next service: ${pred.nextService}</small></div><div class="text-end"><span class="badge bg-${priorityColor}">${pred.priority}</span><div><small class="text-muted">${pred.daysUntil} days</small></div></div></div></div>`;
            }).join('');

            // Add scrollbar if more than 3 items
            const scrollStyle = predictions.length > 3 ? 'style="max-height: 200px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #6c757d #f1f3f4;"' : '';
            container.innerHTML = `<div ${scrollStyle}>${html}</div>`;
        }

        function renderReliabilityAnalysis() {
            const container = document.getElementById('reliabilityAnalysis');
            if (!container) return;

            const reliability = (analyticsData && analyticsData.reliability_analysis) || [];
            
            // Check if we have real data from the database
            if (!reliability || reliability.length === 0) {
                container.innerHTML = `
                    <div class="empty-state text-center py-4">
                        <i class="bi bi-shield-check text-muted" style="font-size: 2.5rem;"></i>
                        <h6 class="text-muted mt-3">No Reliability Data</h6>
                        <p class="text-muted mb-0 small">Complete services to see equipment reliability analysis</p>
                    </div>
                `;
                return;
            }

            const html = reliability.map(item => {
                const trendIcon = item.trend === 'up' ? 'arrow-up text-success' : 
                                 item.trend === 'down' ? 'arrow-down text-danger' : 'dash text-warning';
                const scoreColor = item.score >= 90 ? 'success' : item.score >= 80 ? 'warning' : 'danger';
                
                return `
                    <div class="border-bottom pb-2 mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${item.equipment}</h6>
                                <small class="text-muted">${item.issues} issues this quarter</small>
                            </div>
                            <div class="text-end">
                                <div class="text-${scoreColor} fw-bold">${item.score}%</div>
                                <i class="bi bi-${trendIcon}"></i>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // Add scrollbar if more than 3 items
            const scrollStyle = reliability.length > 3 ? 'style="max-height: 200px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #6c757d #f1f3f4;"' : '';
            container.innerHTML = `<div ${scrollStyle}>${html}</div>`;
        }

        function renderCostAnalysis() {
            const ctx = document.getElementById('costAnalysisChart');
            if (!ctx) return;

            // Destroy existing chart if it exists
            if (Chart.getChart('costAnalysisChart')) {
                Chart.getChart('costAnalysisChart').destroy();
            }

            // Check if analyticsData exists and has the required properties
            if (!analyticsData) {
                const container = ctx.parentElement;
                container.innerHTML = `
                    <div class="empty-state text-center py-4">
                        <i class="bi bi-graph-up-arrow text-muted" style="font-size: 2.5rem;"></i>
                        <h6 class="text-muted mt-3">No Cost Data</h6>
                        <p class="text-muted mb-0 small">Complete paid services to see cost analysis</p>
                    </div>
                `;
                return;
            }
            
            const costData = analyticsData.cost_analysis || [];
            const costOptimization = analyticsData.cost_optimization || {};
            const monthlyTrends = analyticsData.monthly_cost_trends || [];
            
            // Check if we have actual cost data
            if (costData.length === 0) {
                const container = ctx.parentElement;
                container.innerHTML = `
                    <div class="empty-state text-center py-4">
                        <i class="bi bi-graph-up-arrow text-muted" style="font-size: 2.5rem;"></i>
                        <h6 class="text-muted mt-3">No Cost Data</h6>
                        <p class="text-muted mb-0 small">Complete paid services to see cost analysis</p>
                    </div>
                `;
                return;
            }

            // Prepare chart data using actual service types from database
            let chartLabels = [];
            let datasets = [];

            if (costData.length > 0) {
                // Use actual service type data from database
                chartLabels = costData.map(service => service.service_type_name);
                const costs = costData.map(service => parseFloat(service.total_cost));
                
                // Generate colors for each service type
                const colors = [
                    'rgba(220, 53, 69, 0.8)',   // Red
                    'rgba(40, 167, 69, 0.8)',   // Green
                    'rgba(255, 193, 7, 0.8)',   // Yellow
                    'rgba(0, 123, 255, 0.8)',   // Blue
                    'rgba(108, 117, 125, 0.8)', // Gray
                    'rgba(255, 87, 34, 0.8)',   // Orange
                    'rgba(156, 39, 176, 0.8)',  // Purple
                    'rgba(76, 175, 80, 0.8)'    // Light Green
                ];
                
                datasets.push({
                    label: 'Service Costs',
                    data: costs,
                    backgroundColor: colors.slice(0, costData.length),
                    borderColor: colors.slice(0, costData.length).map(color => color.replace('0.8', '1')),
                    borderWidth: 1
                });
            } else {
                // Fallback if no data available
                chartLabels = ['No Data'];
                datasets.push({
                    label: 'Service Costs',
                    data: [0],
                    backgroundColor: ['rgba(108, 117, 125, 0.8)'],
                    borderColor: ['rgba(108, 117, 125, 1)'],
                    borderWidth: 1
                });
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Cost (₱)' }
                        }
                    }
                }
            });

            // Display service cost totals below the chart in 3 columns
            const serviceTotalsContainer = document.getElementById('serviceCostTotals');
            if (serviceTotalsContainer && costData.length > 0) {
                let totalsHtml = '<div class="row">';
                
                costData.forEach((service, index) => {
                    const colors = [
                        '#dc3545', '#28a745', '#ffc107', '#007bff', 
                        '#6c757d', '#ff5722', '#9c27b0', '#4caf50'
                    ];
                    const color = colors[index % colors.length];
                    
                    totalsHtml += `
                        <div class="col-6 col-md-4 mb-2 text-center">
                            <div style="color: ${color}; font-weight: 600; font-size: 1rem;">₱${parseFloat(service.total_cost).toLocaleString()}</div>
                            <hr style="margin: 0.25rem 0; border-color: ${color};">
                            <div style="font-size: 0.85rem;">${service.service_type_name}</div>
                        </div>
                    `;
                });
                
                totalsHtml += '</div>';
                
                serviceTotalsContainer.innerHTML = `
                    <div>
                        <small class="text-muted d-block mb-2">Service Type Totals:</small>
                        ${totalsHtml}
                    </div>
                `;
            }

            // Render cost insights using real data
            const insightsContainer = document.getElementById('costInsights');
            if (insightsContainer) {
                const repairVsMaintenance = costOptimization.repair_vs_maintenance || {};
                const insights = costOptimization.insights || [];
                const recommendations = costOptimization.recommendations || [];

                let insightsHtml = '';

                // Calculate dynamic totals from actual service data
                let totalMaintenance = 0;
                let totalRepair = 0;
                let totalCost = 0;
                
                costData.forEach(service => {
                    const cost = parseFloat(service.total_cost);
                    totalCost += cost;
                    
                    // Categorize services based on name
                    const serviceName = service.service_type_name.toLowerCase();
                    if (serviceName.includes('maintenance') || serviceName.includes('preventive') || serviceName.includes('inspection')) {
                        totalMaintenance += cost;
                    } else if (serviceName.includes('repair') || serviceName.includes('fix') || serviceName.includes('emergency')) {
                        totalRepair += cost;
                    }
                });
                
                // Bottom metrics section has been removed

                // Generate dynamic insights based on actual data
                const maintenanceRatio = totalCost > 0 ? ((totalMaintenance / totalCost) * 100).toFixed(1) : 0;
                const repairRatio = totalCost > 0 ? ((totalRepair / totalCost) * 100).toFixed(1) : 0;
                const avgMonthlySpend = totalCost > 0 ? (totalCost / 12).toFixed(2) : 0;
                
                // Dynamic Cost Insights
                insightsHtml += `<div class="mb-3"><h6 class="text-primary mb-2">Cost Insights:</h6>`;
                
                if (totalCost > 0) {
                    if (maintenanceRatio >= 60) {
                        insightsHtml += `
                            <div class="alert alert-info py-2 mb-2">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>Good maintenance investment - ${maintenanceRatio}% of spending on prevention</small>
                            </div>
                        `;
                    } else if (repairRatio >= 60) {
                        insightsHtml += `
                            <div class="alert alert-warning py-2 mb-2">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <small>High repair costs - ${repairRatio}% spent on reactive repairs</small>
                            </div>
                        `;
                    }
                    
                    if (avgMonthlySpend > 10000) {
                        insightsHtml += `
                            <div class="alert alert-info py-2 mb-2">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>High monthly average spend: ₱${parseFloat(avgMonthlySpend).toLocaleString()}</small>
                            </div>
                        `;
                    }
                } else {
                    insightsHtml += `
                        <div class="alert alert-info py-2 mb-2">
                            <i class="bi bi-info-circle me-2"></i>
                            <small>No service costs recorded yet</small>
                        </div>
                    `;
                }
                insightsHtml += `</div>`;

                // Dynamic Recommendations
                insightsHtml += `<div class="mb-3"><h6 class="text-success mb-2">Recommendations:</h6>`;
                
                if (totalCost > 0) {
                    if (repairRatio > maintenanceRatio) {
                        insightsHtml += `
                            <div class="alert alert-success py-2 mb-2">
                                <i class="bi bi-lightbulb me-2"></i>
                                <small>Increase preventive maintenance to reduce costly repairs</small>
                            </div>
                        `;
                    }
                    
                    if (maintenanceRatio >= 60) {
                        insightsHtml += `
                            <div class="alert alert-success py-2 mb-2">
                                <i class="bi bi-lightbulb me-2"></i>
                                <small>Continue current maintenance schedule to prevent costly repairs</small>
                            </div>
                        `;
                    }
                    
                    if (costData.length >= 3) {
                        insightsHtml += `
                            <div class="alert alert-success py-2 mb-2">
                                <i class="bi bi-lightbulb me-2"></i>
                                <small>Review service frequency and consider bulk service packages</small>
                            </div>
                        `;
                    }
                } else {
                    insightsHtml += `
                        <div class="alert alert-success py-2 mb-2">
                            <i class="bi bi-lightbulb me-2"></i>
                            <small>Schedule regular maintenance to optimize equipment performance</small>
                        </div>
                    `;
                }
                insightsHtml += `</div>`;

                insightsContainer.innerHTML = insightsHtml;
            }
        }

        // Emergency & Risk Management Functions
        function renderRiskAssessment() {
            const container = document.getElementById('riskAssessment');
            if (!container) return;

            // Use real risk assessment data from analytics API
            const risks = (analyticsData && analyticsData.risk_assessment) || [];

            if (risks.length === 0) {
                container.innerHTML = `
                    <div class="empty-state text-center py-4">
                        <i class="bi bi-shield-exclamation text-muted" style="font-size: 2.5rem;"></i>
                        <h6 class="text-muted mt-3">No Risk Data</h6>
                        <p class="text-muted mb-0 small">Complete services to see risk assessment</p>
                    </div>
                `;
                return;
            }

            const html = risks.map(risk => {
                const levelColor = risk.level === 'High' ? 'danger' : risk.level === 'Medium' ? 'warning' : 'success';
                const factorsHtml = risk.factors ? risk.factors.map(factor => `<small class="text-muted d-block">• ${factor}</small>`).join('') : '';
                
                return `
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-bold">${risk.type}</span>
                            <span class="badge bg-${levelColor}">${risk.level}</span>
                        </div>
                        <div class="progress mb-1" style="height: 8px;">
                            <div class="progress-bar bg-${levelColor}" style="width: ${risk.probability}%"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">${risk.probability}% probability</small>
                        </div>
                        ${factorsHtml}
                    </div>
                `;
            }).join('');

            container.innerHTML = html;
        }

        function renderEmergencyResponse() {
            const ctx = document.getElementById('emergencyResponseChart');
            if (!ctx) return;

            // Destroy existing chart if it exists
            if (Chart.getChart('emergencyResponseChart')) {
                Chart.getChart('emergencyResponseChart').destroy();
            }

            // Use real emergency response data from analytics API
            const emergencyData = (analyticsData && analyticsData.emergency_response) || [];

            if (emergencyData.length === 0) {
                const container = ctx.parentElement;
                container.innerHTML = `
                    <div class="empty-state text-center py-4">
                        <i class="bi bi-clock-history text-muted" style="font-size: 2.5rem;"></i>
                        <h6 class="text-muted mt-3">No Emergency Data</h6>
                        <p class="text-muted mb-0 small">Emergency response analytics will appear with service history</p>
                    </div>
                `;
                return;
            }

            const months = emergencyData.map(item => {
                const date = new Date(item.month + '-01');
                return date.toLocaleDateString('en-US', { month: 'short' });
            });
            const responseHours = emergencyData.map(item => parseFloat(item.avg_response_time) || 0);
            const emergencyCount = emergencyData.map(item => parseInt(item.emergency_count) || 0);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Avg Response Time (hours)',
                        data: responseHours,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y'
                    }, {
                        label: 'Emergency Calls',
                        data: emergencyCount,
                        borderColor: '#fd7e14',
                        backgroundColor: 'rgba(253, 126, 20, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            min: 0,
                            title: { display: true, text: 'Hours' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            min: 0,
                            title: { display: true, text: 'Count' },
                            grid: { drawOnChartArea: false }
                        }
                    }
                }
            });
        }

        function renderFailurePatterns() {
            const container = document.getElementById('failurePatterns');
            if (!container) return;

            // Use real equipment failure patterns data from analytics API
            const patterns = (analyticsData && analyticsData.equipment_failure_patterns) || [];

            if (patterns.length === 0) {
                container.innerHTML = `
                    <div class="empty-state text-center py-4">
                        <i class="bi bi-graph-down text-muted" style="font-size: 2.5rem;"></i>
                        <h6 class="text-muted mt-3">No Failure Data</h6>
                        <p class="text-muted mb-0 small">Equipment failure patterns will appear with service history</p>
                    </div>
                `;
                return;
            }

            const html = patterns.map(pattern => {
                const trendIcon = pattern.trend === 'increasing' ? 'arrow-up text-danger' : 
                                 pattern.trend === 'decreasing' ? 'arrow-down text-success' : 'dash text-warning';
                const repairFrequency = pattern.repair_frequency ? ` (${pattern.repair_frequency} repairs/year)` : '';
                
                return `
                    <div class="border-bottom pb-2 mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${pattern.appliance_type}</h6>
                                <small class="text-muted">Failures: ${pattern.failure_count}${repairFrequency}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">${pattern.failure_count}</div>
                                <i class="bi bi-${trendIcon}"></i>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // Add scrollbar if more than 3 items
            const scrollStyle = patterns.length > 3 ? 'style="max-height: 240px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #6c757d #f1f3f4;"' : '';
            container.innerHTML = `<div ${scrollStyle}>${html}</div>`;
        }

        function renderFinancialRisk() {
            const container = document.getElementById('financialRisk');
            if (!container) return;

            // Use real financial risk data from analytics API
            const riskData = (analyticsData && analyticsData.financial_risk) || {};

            if (!riskData || Object.keys(riskData).length === 0) {
                container.innerHTML = `
                    <div class="empty-state text-center py-4">
                        <i class="bi bi-credit-card text-muted" style="font-size: 2.5rem;"></i>
                        <h6 class="text-muted mt-3">No Financial Risk Data</h6>
                        <p class="text-muted mb-0 small">Financial risk monitoring will appear with payment history</p>
                    </div>
                `;
                return;
            }

            const riskColor = riskData.risk_level === 'High' ? 'danger' : 
                             riskData.risk_level === 'Medium' ? 'warning' : 'success';

            container.innerHTML = `
                <div class="text-center mb-3">
                    <h4 class="text-${riskColor}">₱${parseFloat(riskData.total_overdue || 0).toLocaleString()}</h4>
                    <small class="text-muted">Total Overdue Amount</small>
                </div>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="fw-bold">${riskData.unpaid_services || 0}</div>
                        <small class="text-muted">Unpaid Services</small>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold">${riskData.avg_payment_delay || 0} days</div>
                        <small class="text-muted">Avg Delay</small>
                    </div>
                </div>
                <div class="row text-center mt-2">
                    <div class="col-12">
                        <div class="fw-bold">${riskData.overdue_appointments || 0}</div>
                        <small class="text-muted">Overdue Appointments</small>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-${riskColor} w-100">${riskData.risk_level || 'Low'} Risk</span>
                </div>
            `;
        }


        // Initialize new analytics sections
        function initializeAdvancedAnalytics() {
            // Equipment Health & Maintenance Intelligence
            renderHealthScore();
            renderEquipmentTrends();
            renderMaintenancePredictions();
            renderReliabilityAnalysis();
            renderCostAnalysis();

            // Emergency & Risk Management
            renderRiskAssessment();
            renderEmergencyResponse();
            renderFailurePatterns();
            renderFinancialRisk();
        }
</script>
