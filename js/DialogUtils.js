// Dialog utilities and instance
const DialogUtils = (function () {
    // Private variables
    let dialogModal = null;
    let modalElement = null;

    // Create the modal element structure
    function createModalElement() {
        // Create the modal container
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'reusableDialog';
        modal.setAttribute('tabindex', '-1');
        modal.setAttribute('aria-hidden', 'true');

        // Create modal dialog
        const modalDialog = document.createElement('div');
        modalDialog.className = 'modal-dialog modal-dialog-centered';

        // Create modal content
        const modalContent = document.createElement('div');
        modalContent.className = 'modal-content round_lg';

        // Create modal header
        const modalHeader = document.createElement('div');
        modalHeader.className = 'modal-header border-0';

        const modalTitle = document.createElement('h5');
        modalTitle.className = 'modal-title';
        modalTitle.id = 'reusableDialogLabel';
        modalTitle.textContent = ''; // Default title is empty

        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.className = 'btn-close';
        closeButton.setAttribute('data-bs-dismiss', 'modal');
        closeButton.setAttribute('aria-label', 'Close');

        modalHeader.appendChild(modalTitle);
        // modalHeader.appendChild(closeButton);

        // Create modal body
        const modalBody = document.createElement('div');
        modalBody.className = 'modal-body border-0 text-center';
        modalBody.id = 'reusableDialogBody';

        // Create modal footer
        const modalFooter = document.createElement('div');
        modalFooter.className = 'modal-footer border-0';

        const cancelButton = document.createElement('button');
        cancelButton.type = 'button';
        cancelButton.className = 'btn btn-light px-3 rounded-pill border-0';
        cancelButton.setAttribute('data-bs-dismiss', 'modal');
        cancelButton.textContent = 'Cancel';

        const confirmButton = document.createElement('button');
        confirmButton.type = 'button';
        confirmButton.className = 'btn btn-primary px-3 rounded-pill border-0';
        confirmButton.id = 'confirmActionButton';
        confirmButton.textContent = 'Confirm';

        modalFooter.appendChild(cancelButton);
        modalFooter.appendChild(confirmButton);

        // Assemble the modal structure
        modalContent.appendChild(modalHeader);
        modalContent.appendChild(modalBody);
        modalContent.appendChild(modalFooter);
        modalDialog.appendChild(modalContent);
        modal.appendChild(modalDialog);

        return modal;
    }

    // Initialize the dialog modal
    function initialize() {
        // Create modal element if it doesn't exist
        if (!modalElement) {
            modalElement = createModalElement();
            document.body.appendChild(modalElement);
            dialogModal = new bootstrap.Modal(modalElement);
        }
        return dialogModal;
    }

    return {
        initialize: initialize,
        getModalElement: () => modalElement,
        hide: () => dialogModal ? dialogModal.hide() : null
    };
})();
/**
 * Show a reusable dialog modal with object-based configuration
 * @param {Object} options - Configuration options for the dialog
 * @param {string} options.title - Dialog title (optional, default: '')
 * @param {string} options.message - HTML message to display in the dialog body
 * @param {string} options.confirmText - Confirm button text (optional, default: 'Confirm')
 * @param {Function} options.onConfirm - Function to call when confirm button is clicked
 * @param {string} options.cancelText - Cancel button text (optional, default: 'Cancel')
 * @param {string} options.textAlign - Text alignment in the modal body (optional, default: 'center')
 * @param {boolean} options.closeOnConfirm - Whether to close dialog after confirm (optional, default: true)
 * @param {Function} options.script - Custom script function to execute after dialog is shown (optional)
 */
function showDialog(options) {
    // Initialize the dialog modal
    const dialogModal = DialogUtils.initialize();
    const modalElement = DialogUtils.getModalElement();

    // Set default options
    const settings = {
        title: options.title || '',
        message: options.message || '',
        confirmText: options.confirmText || 'Confirm',
        cancelText: options.cancelText || 'Cancel',
        textAlign: options.textAlign || 'center',
        closeOnConfirm: options.closeOnConfirm !== undefined ? options.closeOnConfirm : true,
        script: options.script || null
    };

    // Get the confirm action function
    const onConfirm = options.onConfirm || function () { };

    // Set dialog content
    modalElement.querySelector('#reusableDialogLabel').textContent = settings.title;

    // Set p-0 class if title is empty
    const modalHeader = modalElement.querySelector('.modal-header');
    if (settings.title === '') {
        modalHeader.classList.add('p-0');
    } else {
        modalHeader.classList.remove('p-0');
    }

    modalElement.querySelector('#reusableDialogBody').innerHTML = settings.message;

    // Set text alignment class
    const modalBody = modalElement.querySelector('#reusableDialogBody');
    modalBody.className = 'modal-body border-0';
    if (settings.textAlign === 'start') {
        modalBody.classList.add('text-start');
    } else if (settings.textAlign === 'end') {
        modalBody.classList.add('text-end');
    } else {
        modalBody.classList.add('text-center');
    }

    // Set button texts
    const confirmButton = modalElement.querySelector('#confirmActionButton');
    confirmButton.innerHTML = settings.confirmText;

    const cancelButton = modalElement.querySelector('.modal-footer .btn-light');
    cancelButton.innerHTML = settings.cancelText;

    // Remove previous event listeners and create new button
    const newConfirmButton = confirmButton.cloneNode(true);
    confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);

    // Add new event listener for confirm button
    newConfirmButton.addEventListener('click', function () {
        // Create a dialog context object with hide method
        const dialogContext = {
            hide: function () {
                dialogModal.hide();
            }
        };

        // Call onConfirm with dialogContext as 'this'
        onConfirm.call(dialogContext);

        // Only close the dialog automatically if closeOnConfirm is true
        // This should happen after onConfirm has been called
        if (settings.closeOnConfirm) {
            dialogModal.hide();
        }
    });

    // Show the dialog
    dialogModal.show();

    // Execute custom script if provided
    if (settings.script && typeof settings.script === 'function') {
        setTimeout(() => {
            try {
                settings.script();
            } catch (e) {
                console.error('Error executing custom script:', e);
            }
        }, 500);
    }
}

