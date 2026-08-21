<!-- Global Toast Notification Container -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3"
    style="z-index: 1090; width: 100%; max-width: 450px;">

    <!-- 1. LARAVEL SESSION SUCCESS ALERT -->
    @if (session('success'))
        <div class="toast show align-items-center text-bg-success border-0 shadow" role="alert" aria-live="assertive"
            aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex p-2">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close btn-close-white m-auto me-2" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- 2. LARAVEL SESSION ERROR / DANGER ALERT -->
    @if (session('error') || session('danger'))
        <div class="toast show align-items-center text-bg-danger border-0 shadow" role="alert" aria-live="assertive"
            aria-atomic="true" data-bs-autohide="false">
            <div class="d-flex p-2">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-octagon-fill fs-5"></i>
                    <div>{{ session('error') ?? session('danger') }}</div>
                </div>
                <button type="button" class="btn-close btn-close-white m-auto me-2" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- LARAVEL SESSION WARNING ALERT -->
    @if (session('warning'))
        <div class="toast show align-items-center text-bg-warning border-0 shadow" role="alert" aria-live="assertive"
            aria-atomic="true" data-bs-delay="6000">
            <div class="d-flex p-2">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>{{ session('warning') }}</div>
                </div>
                <button type="button" class="btn-close m-auto me-2" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- 3. LARAVEL FORM VALIDATION ERRORS STACK -->
    @if ($errors->any())
        <div class="toast show align-items-center text-bg-warning border-0 shadow" role="alert" aria-live="assertive"
            aria-atomic="true" data-bs-autohide="false">
            <div class="d-flex p-2">
                <div class="toast-body">
                    <div class="d-flex align-items-center gap-2 fw-bold mb-1">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <span>Please fix the following:</span>
                    </div>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close m-auto me-2" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- 4. JAVASCRIPT DYNAMIC TOAST TEMPLATE CONTAINER (Populated via JS function below) -->
    <div id="dynamicToastContainer"></div>
</div>

<style>
    /* Styling adjustments to blend into your current system layout */
    .toast {
        border-radius: 12px !important;
        backdrop-filter: blur(8px);
        margin-bottom: 0.5rem;
    }

    .text-bg-success {
        background-color: rgba(25, 135, 84, 0.95) !important;
    }

    .text-bg-danger {
        background-color: rgba(220, 53, 69, 0.95) !important;
    }

    .text-bg-warning {
        background-color: rgba(253, 126, 20, 0.95) !important;
        color: #ffffff !important;
    }

    .text-bg-info {
        background-color: rgba(118, 159, 205, 0.95) !important;
        color: #ffffff !important;
    }

    /* Soften the close icon for validation alert */
    .text-bg-warning .btn-close {
        filter: invert(1) grayscale(1) brightness(2);
    }
</style>

<script>
    /**
     * Call this anywhere in your custom javascript assets/inline scripts
     * Types supported: 'success', 'danger', 'warning', 'info'
     * 
     * Example Usage: 
     * showNotification('success', 'Driver settings saved cleanly!');
     */
    function showNotification(type, message, duration = 4000) {
        const container = document.getElementById('dynamicToastContainer');
        if (!container) return;

        // Map icons based on types
        const icons = {
            success: 'bi-check-circle-fill',
            danger: 'bi-exclamation-octagon-fill',
            warning: 'bi-exclamation-triangle-fill',
            info: 'bi-info-circle-fill'
        };

        const toastId = 'toast_' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex p-2">
                    <div class="toast-body d-flex align-items-center gap-2">
                        <i class="bi ${icons[type] || 'bi-bell-fill'} fs-5"></i>
                        <div>${message}</div>
                    </div>
                    <button type="button" class="btn-close ${type !== 'warning' ? 'btn-close-white' : ''} m-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', toastHtml);

        const toastElement = document.getElementById(toastId);
        const bsToast = new bootstrap.Toast(toastElement, {
            delay: duration,
            autohide: true
        });

        bsToast.show();

        // Remove element from DOM entirely after hiding to prevent bloat
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    // Auto-initialize standard framework autohide alerts
    document.addEventListener('DOMContentLoaded', () => {
        const existingToasts = document.querySelectorAll('.toast-container .toast');
        existingToasts.forEach(toastEl => {
            if (!toastEl.closest('#dynamicToastContainer')) {
                const autoHideAttr = toastEl.getAttribute('data-bs-autohide');
                const delayAttr = toastEl.getAttribute('data-bs-delay') || 4000;

                const config = {
                    autohide: autoHideAttr !== 'false',
                    delay: parseInt(delayAttr)
                };

                const bsToast = new bootstrap.Toast(toastEl, config);
                bsToast.show();
            }
        });
    });
</script>
