<h3>
    <a onclick="window.location.href = document.referrer;" class="btn p-2">
        <i class="bi bi-chevron-left"></i>
    </a>
    View
</h3>
<div id="appointments-container"></div>

<script>
  const serviceTypeColors = {
        'Repair': 'alert alert-danger',
        'Maintenance': 'alert alert-warning',
        'Installation': 'alert alert-success',
    };
    const defaultClass = 'alert alert-secondary';
    let customerId;
    let currentAppointmentId;
    let currentAction;

    async function fetchAppointments(customerId) {
        try {
            const response = await fetch(`api/administrator/get_app.php?customer=${customerId}`);
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
            container.innerHTML = '<div class="col-12 text-center p-5"><p>No appointments found.</p></div>';
            return;
        }

        appointments.forEach(app => {
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
            appointmentDiv.className = 'p-3 mb-2 view-item  round_lg position-relative';
            appointmentDiv.innerHTML = `
                    <div>
                        <h5 class="mb-3 p-sm-2 pb-0 m-0 fw-semibold text-truncate">${app.user_name} ${app.user_midname} ${app.user_lastname}</h5>
                       <div class="row p-2 pt-0 gap-2 align-items-center">
            <div class="col-sm-4"><i class="bi bi-telephone-fill"></i>&emsp;${app.user_contact}</div>
           <div class="col-sm-6"><i class="bi bi-calendar3"></i>&emsp;${date}</div>
            <div class="col-sm-4"><i class="bi bi-clock"></i>&emsp;${time}</div>
            <div class="col-sm-6"><i class="bi bi-wrench-adjustable"></i>&emsp;
                <div class="badge p-1 rounded-pill px-2 border-0 small fw-semibold m-0 ${serviceTypeColors[app.service_type_name] ?? defaultClass}">
                    ${app.service_type_name}
                </div>
            </div>
            <div class="col-sm-12"><i class="bi bi-geo-alt-fill"></i>&emsp;
                ${app.house_building_street ? app.house_building_street + '<br>' : ''}
                ${app.barangay ? 'Barangay: ' + app.barangay + '<br>' : ''}
                ${app.municipality_city ? 'Municipality/City: ' + app.municipality_city + '<br>' : ''}
                ${app.province ? 'Province: ' + app.province + '<br>' : ''}
                ${app.zip_code ? 'Zip Code: ' + app.zip_code : ''}
            </div>
         
            <div class="col-sm-6"><i class="bi bi-person-fill-gear"></i>&emsp;${app.tech_name} ${app.tech_midname} ${app.tech_lastname}</div>
          
            </div>
                        <div class="col-sm-12 mt-2">
                            <div class="d-flex bg-light  mb-2 round_md p-3">
                                <div class="col">${app.app_desc}</div>
                            </div>
                           <div class="d-flex justify-content-end gap-2 pt-2 ">
                                <button class="btn px-3 rounded-pill btn-light fw-semibold border-0" onclick="showConfirmationModal(${app.app_id}, 'decline')"><small>Decline</small></button>
                                <button class="btn btn-primary border-0 px-3 fw-semibold rounded-pill" onclick="showConfirmationModal(${app.app_id}, 'accept')"><small>Accept</small></button>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute top-0 end-0 mt-2 me-2 p-2 small">
                        <small>${appCreated}</small>
                    </div>
                `;
            container.appendChild(appointmentDiv);
        });
    }

    function showConfirmationModal(appointmentId, action) {
        const actionText = action.charAt(0).toUpperCase() + action.slice(1);
        showDialog({
            title: 'Confirmation',
            message: ` Are you sure you want to <span class="fw-bold">${actionText}</span> this appointment?.`,
            confirmText: actionText,
            cancelText: 'Cancel',
            onConfirm: async function() {
                await updateAppointment(appointmentId, action);
            }
        });
    }

    async function updateAppointment(appointmentId, action) {
        try {
            const response = await fetch('api/administrator/update_app.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `appointment_id=${appointmentId}&action=${action}`,
            });
            const data = await response.json();
            if (data.status === 'success') {
                // alert(data.message);
                fetchAppointments(customerId); // Refresh the appointments list
                successToast(data.message);
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
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