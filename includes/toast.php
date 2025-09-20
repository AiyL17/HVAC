<div class="toast-container position-fixed top-0 end-0 pt-5 p-3">
        <div id="liveToast" class="toast p-0 round_lg border-0 bg-transparent" role="alert" aria-live="assertive" aria-atomic="true">
            <div id="toastAlert" class="d-flex alert  alert-success p-0 m-0">
                <div id="toastBody" class="toast-body">
                
                </div>
                <button type="button" class="btn me-2 m-auto small border-0" data-bs-dismiss="toast" aria-label="Close"><i class="bi bi-x-lg"></i></button>
            </div>
        </div>
    </div>

   

<script>
     function showToast(message, alertType) {
            // Update the toast message
            document.getElementById('toastBody').innerText = message;

            // Update the alert type class
            const toastAlert = document.getElementById('toastAlert');
            toastAlert.className = `d-flex alert ps-2 round_lg alert-${alertType} p-0 m-0`;

            // Show the toast
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function(toastEl) {
                return new bootstrap.Toast(toastEl)
            })
            toastList.forEach(toast => toast.show())
        }

        function successToast(message) {
            showToast(message, 'success');
        }

        function warningToast(message) {
            showToast(message, 'warning');
        }

        function dangerToast(message) {
            showToast(message, 'danger');
        }
</script>