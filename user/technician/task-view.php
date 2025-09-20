<h3>
    <button onclick="window.location.href = document.referrer;" class="btn p-2">
        <i class="bi bi-chevron-left"></i>
    </button>
    <?php
    $query = $pdo->prepare("SELECT * FROM `appointment_status` WHERE app_status_id IN(" . $_GET['status'] . ")");
    $query->execute(array());
    $result = $query->fetchAll(PDO::FETCH_OBJ);

    foreach ($result as $app) { ?>


    <?php } ?>
    Assigned
</h3>
<div id="appointments-container" class="row p-0"></div>

<!-- Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content round_lg">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
            </div>
            <div class="modal-body border-0 text-center" id="confirmationModalBody">
                Mark this task as <span class="fw-bold" id="confirmationAction"></span> ?
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light  border-0 px-3  rounded-pill"
                    data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary border-0 text-light px-3 rounded-pill"
                    id="confirmActionButton">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    const serviceTypeColors = {
        'Repair': 'alert alert-danger',
        'Maintenance': 'alert alert-warning',
        'Installation': 'alert alert-success',
    };
    const defaultClass = 'alert alert-secondary';
    
    // Add custom orange color for In Progress status
    if (!document.getElementById('custom-orange-style')) {
        const style = document.createElement('style');
        style.id = 'custom-orange-style';
        style.textContent = '.bg-orange { background-color: #fd7e14 !important; }';
        document.head.appendChild(style);
    }
    let technicianId = <?php echo json_encode($_SESSION['uid']); ?>;
    let statusId = <?php echo json_encode($_GET['status']); ?>;
    let specificAppId = <?php echo json_encode($_GET['app_id'] ?? null); ?>;
    let currentAppointmentId;
    let currentAction;
    let customerId;

    async function fetchAppointments(customerId) {
        try {
            let apiUrl = `api/technician/get_app.php?customer=${customerId}&technician=${technicianId}&status=${statusId}`;
            
            // If specific app_id is provided, add it to the API call
            if (specificAppId) {
                apiUrl += `&app_id=${specificAppId}`;
            }
            
            const response = await fetch(apiUrl);
            const appointments = await response.json();
            renderAppointments(appointments);
        } catch (error) {
            console.error('Error fetching appointments:', error);
        }
    }

    function renderAppointments(appointments) {
        const container = document.getElementById('appointments-container');
        container.innerHTML = '';

        if (!appointments || appointments.length === 0) {
            container.innerHTML = '<div class="col-12 text-center p-5"><p>No task found.</p></div>';
            return;
        }

        appointments.forEach(app => {
            // Debug: Log payment status for each appointment to verify updates
            console.log(`Appointment ${app.app_id}: payment_status = '${app.payment_status}', status = '${app.app_status_name}'`);
            
            const schedule = new Date(app.app_schedule);
            const date = schedule.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            const time = schedule.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });

            const created = new Date(app.app_created);
            const now = new Date();
            const interval = now - created;
            const minutesAgo = Math.floor(interval / 60000);
            const hoursAgo = Math.floor(minutesAgo / 60);
            const daysAgo = Math.floor(hoursAgo / 24);

            let appCreated;
            if (daysAgo > 0) {
                appCreated = created.toLocaleString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true });
            } else if (hoursAgo > 0) {
                appCreated = `${hoursAgo} hour${hoursAgo > 1 ? 's' : ''} ago`;
            } else if (minutesAgo > 0) {
                appCreated = `${minutesAgo} minute${minutesAgo > 1 ? 's' : ''} ago`;
            } else {
                appCreated = 'just now';
            }

            const appointmentDiv = document.createElement('div');
            appointmentDiv.className = 'p-2  col-sm-12  position-relative';
            appointmentDiv.innerHTML = `
    <div class="view-item  round_lg p-3 m-1">
        <h5 class="mb-3 p-0 fw-semibold text-truncate">${app.user_name} ${app.user_midname} ${app.user_lastname}</h5>
        <div class="row p-2 gap-2 align-items-center">
            <div class="col-sm-4"><i class="bi bi-telephone-fill"></i>&emsp;${app.user_contact}</div>
           <div class="col-sm-6"><i class="bi bi-calendar3"></i>&emsp;${date}</div>
            <div class="col-sm-4"><i class="bi bi-clock"></i>&emsp;${time}</div>
            <div class="col-sm-6"><i class="bi bi-wrench-adjustable"></i>&emsp;
               <div class="badge p-1 rounded-pill px-2 border-0 small fw-semibold m-0 ${serviceTypeColors[app.service_type_name] ?? defaultClass}">
                    ${app.service_type_name}
                </div>
            </div>
            <div class="col-sm-6"><i class="bi bi-gear"></i>&emsp;
                <span class="badge ${(() => {
                    const statusName = app.app_status_name.toLowerCase();
                    if (statusName.includes('pending')) return 'bg-warning text-dark';
                    else if (statusName.includes('approved') || statusName.includes('assigned')) return 'bg-primary';
                    else if (statusName.includes('declined')) return 'bg-danger';
                    else if (statusName.includes('progress')) return 'bg-orange';
                    else if (statusName.includes('completed')) return 'bg-success';
                    else return 'bg-secondary';
                })()}">${app.app_status_name}</span>
            </div>
            <div class="col-sm-6"><i class="bi bi-credit-card"></i>&emsp;
                <span class="badge ${app.payment_status === 'Paid' ? 'bg-success' : 'bg-danger'} text-white">
                    ${app.payment_status || 'Unpaid'}
                </span>
            </div>
            <div class="col-sm-12"><i class="bi bi-geo-alt-fill"></i>&emsp;
                ${app.house_building_street ? app.house_building_street + '<br>' : ''}
                ${app.barangay ? 'Barangay: ' + app.barangay + '<br>' : ''}
                ${app.municipality_city ? 'Municipality/City: ' + app.municipality_city + '<br>' : ''}
                ${app.province ? 'Province: ' + app.province + '<br>' : ''}
                ${app.zip_code ? 'Zip Code: ' + app.zip_code : ''}
            </div>
         
            <div class="col-sm-6"><i class="bi bi-person-fill-gear"></i>&emsp;${app.tech_name} ${app.tech_midname} ${app.tech_lastname}</div>
          
            <div class="col-sm-6">
            <span class="px-1">₱</span>&emsp;${(() => {
                // For Assigned (1) and In Progress (5) appointments, always show service type price range
                // For Completed (3) appointments, show actual price if available
                if (app.app_status_id == 3 && app.app_price && app.app_price > 0) {
                    // Show actual price for completed appointments
                    return new Intl.NumberFormat('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(app.app_price);
                } else {
                    // Show price range from service type for assigned/in-progress appointments
                    const minPrice = parseFloat(app.service_type_price_min) || 0;
                    const maxPrice = parseFloat(app.service_type_price_max) || 0;
                    
                    if (minPrice > 0 && maxPrice > 0) {
                        if (minPrice === maxPrice) {
                            // Fixed price
                            return new Intl.NumberFormat('en-PH', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(minPrice);
                        } else {
                            // Price range
                            return new Intl.NumberFormat('en-PH', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(minPrice) + ' - ₱' + new Intl.NumberFormat('en-PH', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(maxPrice);
                        }
                    } else {
                        return '<small class="text-muted">Price not set</small>';
                    }
                }
            })()}
            </div>
          
            </div>
            
        <div class="col-sm-12 mt-2">
            <div class="d-flex bg-light  mb-2 round_md p-3">
                <div class="col">${app.app_desc}</div>
            </div>
            ${app.app_status_id == 3 && app.app_justification && app.app_justification.trim() !== '' ? `
            <div class="bg-light round_md p-3 mt-2">
                <h6 class="text-success mb-2"><i class="bi bi-journal-text me-2"></i>Cost Justification</h6>
                <div class="text-muted">
                    ${app.app_justification}
                </div>
            </div>` : ''}
            <div class="d-flex justify-content-end gap-2 pt-2 ">
                ${app.app_status_id == 1 ? `
                     <button class="btn rounded-pill btn-primary bg-opacity-50 ps-3 pe-3 text-white border-0 " onclick="showConfirmationModal('${app.service_type_name}',${app.app_id}, 'inprogress')"><small>Update</small></button>
                 ` : ''}
                 ${app.app_status_id == 5 ? `
                     <button class="btn btn-primary ps-3 pe-3 text-white border-0 rounded-pill " onclick="showConfirmationModal('${app.service_type_name}',${app.app_id}, 'complete')"><small>Update</small></button>
                 ` : ''}
                 ${app.app_status_id == 3 ? `
                     <button class="btn btn-success ps-3 pe-3 text-white border-0 rounded-pill " onclick="showConfirmationModal('${app.service_type_name}',${app.app_id}, 'paid')"><small>Mark as Paid</small></button>
                 ` : ''}
            </div>
        </div>
    </div>
    <div class="position-absolute  top-0 end-0 mt-2 me-2 p-4 pt-3 small">
        <small>${appCreated}</small>
    </div>
`;

            container.appendChild(appointmentDiv);
        });
    }


    function showConfirmationModal(appointmentType, appointmentId, action) {

        let actionText = "";
        let messageText = "";

        if (action.toLowerCase() === "inprogress") {
            actionText = "In Progress";
            messageText = ` Mark this task as <span class="fw-bold">${actionText}</span> ?`
        } else if (action.toLowerCase() === "paid") {
            actionText = "Paid";
            messageText = ` Mark this task as <span class="fw-bold">${actionText}</span> ?`
        } else {
            actionText = action.charAt(0).toUpperCase() + action.slice(1);
            messageText = `
            <div class="text-center mb-3">
                <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle mb-2" style="width: 50px; height: 50px;">
                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Complete Task</h5>
                <p class="text-muted mb-0 small">Set final price and provide cost justification</p>
            </div>
            
            <div class="mb-3">
                <label for="price" class="form-label fw-semibold text-dark mb-2">
                    <i class="bi bi-cash-coin text-warning me-2"></i>Final Service Price
                </label>
                <div class="position-relative">
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white border-0 fs-5 fw-bold px-3">₱</span>
                        <input type="text" class="form-control border-0 bg-light fs-5 fw-bold" 
                               id="price" placeholder="0.00" required 
                               style="border-top-left-radius: 0; border-bottom-left-radius: 0; padding: 12px;">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="costJustification" class="form-label fw-semibold text-dark mb-3">
                    <i class="bi bi-journal-text text-info me-2"></i>Cost Justification
                </label>
                <textarea class="form-control border-0 bg-light" id="costJustification" rows="4" 
                          placeholder="Please provide detailed explanation for the pricing:\n\n• Parts used and their costs\n• Labor time and complexity\n• Additional services performed\n• Any special circumstances" 
                          required style="resize: none; line-height: 1.6;"></textarea>
                <div class="form-text text-muted mt-2">
                    <i class="bi bi-info-circle me-1"></i>Minimum 10 characters required
                </div>
            </div>
        
        
        `

        }

        const patterns = {
            price: /^(\d+\.?\d*|\.\d+)$/
        };

        function validatePrice() {
            const price = document.getElementById('price');
            if (!price) return false;

            const value = price.value.trim();

            if (value === '') {
                price.classList.add('border-danger', 'border-3');
                price.classList.remove('border-0');
                return false;
            }

            if (patterns.price.test(value) && !isNaN(value) && parseFloat(value) >= 0) {
                price.classList.add('border-0');
                price.classList.remove('border-danger', 'border-3');
                return true;
            } else {
                price.classList.add('border-danger', 'border-3');
                price.classList.remove('border-0');
                return false;
            }
        }
        
        function validateJustification() {
            const justification = document.getElementById('costJustification');
            if (!justification) return false;

            const value = justification.value.trim();

            if (value === '' || value.length < 10) {
                justification.classList.add('border-danger', 'border-2');
                return false;
            } else {
                justification.classList.remove('border-danger', 'border-2');
                return true;
            }
        }
        function customDialogScript() {
            const price = document.getElementById('price');
            const justification = document.getElementById('costJustification');
            
            if (price) {
                price.addEventListener('input', () => {
                    validatePrice();
                });
            }
            
            if (justification) {
                justification.addEventListener('input', () => {
                    validateJustification();
                });
            }
        }

        showDialog({
            title: 'Update',
            message: messageText,
            confirmText: actionText,
            // textAlign:"start",
            cancelText: 'Cancel',
            closeOnConfirm: false,
            script: customDialogScript,
            onConfirm: async function () {
                const price = document.getElementById('price');
                const justification = document.getElementById('costJustification');
                
                // Only require price and justification validation for 'complete' action, not for 'inprogress'
                if (action.toLowerCase() === 'complete') {
                    // Validate price
                    if (!price || price.value.trim() === '') {
                        dangerToast('Price is required for completed tasks');
                        if (price) {
                            price.classList.add('border-danger', 'border-3');
                            price.classList.remove('border-0');
                        }
                        return;
                    }

                    if (!validatePrice()) {
                        dangerToast('Please enter a valid price');
                        return;
                    }
                    
                    // Validate cost justification
                    if (!justification || justification.value.trim() === '') {
                        dangerToast('Cost justification is required for completed tasks');
                        if (justification) {
                            justification.classList.add('border-danger', 'border-2');
                        }
                        return;
                    }
                    
                    if (!validateJustification()) {
                        dangerToast('Cost justification must be at least 10 characters long');
                        return;
                    }
                }
                
                this.hide();
                // Pass both price and justification values
                const priceValue = price ? price.value : '';
                const justificationValue = justification ? justification.value : '';
                await updateAppointment(appointmentId, action, priceValue, justificationValue);
            }
        });

    }



    async function updateAppointment(appointmentId, action, price, justification = '') {
        try {
            const response = await fetch('api/technician/update_app.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `appointment_id=${appointmentId}&action=${action}&price=${price}&justification=${encodeURIComponent(justification)}`,
            });
            const data = await response.json();
            if (data.status === 'success') {
                // For 'paid' action, ensure we refresh the UI to show updated payment status
                if (action === 'paid') {
                    console.log(`Marking appointment ${appointmentId} as paid - refreshing UI`);
                }
                
                // Add a small delay to ensure database update is complete
                setTimeout(() => {
                    fetchAppointments(customerId); // Refresh the appointments list
                }, 300); // Increased delay for better reliability
                successToast(data.message);
            } else {
                dangerToast(data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            dangerToast('An error occurred while updating the appointment');
        }
    }

    // Fetch appointments when the page loads
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        customerId = urlParams.get('customer');
        if (customerId) {
            fetchAppointments(customerId);
        } else {
            console.error('Customer ID is missing');
        }
    });
</script>