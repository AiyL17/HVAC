/**
 * Bootstrap 5.3 Reusable Toast Notifications
 * A standalone script to create and manage Bootstrap toasts dynamically
 */

// Toast utilities and instance
const ToastUtils = (function() {
    // Private variables
    let toastContainer = null;

    // Create the toast container element structure
    function createToastContainer() {
        // Create the toast container
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed top-0 end-0 pt-5 p-3';

        // Create the toast element
        const toast = document.createElement('div');
        toast.id = 'liveToast';
        toast.className = 'toast p-0 round_lg border-0 bg-transparent';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        // Create toast alert
        const toastAlert = document.createElement('div');
        toastAlert.id = 'toastAlert';
        toastAlert.className = 'd-flex alert p-0 m-0';

        // Create toast body
        const toastBody = document.createElement('div');
        toastBody.id = 'toastBody';
        toastBody.className = 'toast-body';

        // Create close button
        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.className = 'btn me-2 m-auto small border-0';
        closeButton.setAttribute('data-bs-dismiss', 'toast');
        closeButton.setAttribute('aria-label', 'Close');
        closeButton.innerHTML = '<i class="bi bi-x-lg"></i>';

        // Assemble the toast structure
        toastAlert.appendChild(toastBody);
        toastAlert.appendChild(closeButton);
        toast.appendChild(toastAlert);
        container.appendChild(toast);

        return container;
    }

    // Initialize the toast container
    function initialize() {
        // Create toast container if it doesn't exist
        if (!toastContainer) {
            toastContainer = createToastContainer();
            document.body.appendChild(toastContainer);
        }
        return toastContainer;
    }

    return {
        initialize: initialize,
        getToastContainer: () => toastContainer
    };
})();

/**
 * Show a toast notification
 * @param {string} message - Message to display in the toast
 * @param {string} alertType - Type of alert (success, warning, danger, etc.)
 */
function showToast(message, alertType) {
    // Initialize the toast container
    const toastContainer = ToastUtils.initialize();
    const toastElement = toastContainer.querySelector('.toast');

    // Update the toast message
    const toastBody = toastContainer.querySelector('#toastBody');
    toastBody.textContent = message;

    // Update the alert type class
    const toastAlert = toastContainer.querySelector('#toastAlert');
    toastAlert.className = `d-flex alert ps-2 round_lg alert-${alertType} p-0 m-0`;

    // Show the toast
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
}

/**
 * Show a success toast notification
 * @param {string} message - Message to display in the toast
 */
function successToast(message) {
    showToast(message, 'success');
}

/**
 * Show a warning toast notification
 * @param {string} message - Message to display in the toast
 */
function warningToast(message) {
    showToast(message, 'warning');
}

/**
 * Show a danger toast notification
 * @param {string} message - Message to display in the toast
 */
function dangerToast(message) {
    showToast(message, 'danger');
}
