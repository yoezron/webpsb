<!-- Toast Notification Container -->
<div id="toast-container" aria-live="polite" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;"></div>

<style>
    .toast-custom {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        margin-bottom: 15px;
        animation: slideInRight 0.3s ease;
        border-left: 5px solid;
    }

    .toast-custom.toast-success {
        border-left-color: #1AB34A;
    }

    .toast-custom.toast-error {
        border-left-color: #dc3545;
    }

    .toast-custom.toast-warning {
        border-left-color: #F3C623;
    }

    .toast-custom.toast-info {
        border-left-color: #3498db;
    }

    .toast-header-custom {
        background: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 12px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .toast-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-size: 1rem;
    }

    .toast-success .toast-icon {
        background: #d4edda;
        color: #1AB34A;
    }

    .toast-error .toast-icon {
        background: #f8d7da;
        color: #dc3545;
    }

    .toast-warning .toast-icon {
        background: #fff3cd;
        color: #F3C623;
    }

    .toast-info .toast-icon {
        background: #d1ecf1;
        color: #3498db;
    }

    .toast-title {
        font-weight: 700;
        font-size: 0.95rem;
        margin: 0;
        flex: 1;
    }

    .toast-body-custom {
        padding: 12px 15px;
        color: #333;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .toast-close {
        background: transparent;
        border: none;
        color: #999;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .toast-close:hover {
        background: rgba(0, 0, 0, 0.05);
        color: #333;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .toast-custom.removing {
        animation: slideOutRight 0.3s ease forwards;
    }

    @media (max-width: 576px) {
        #toast-container {
            top: 10px;
            right: 10px;
            left: 10px;
            min-width: auto;
            max-width: none;
        }

        .toast-custom {
            width: 100%;
        }
    }
</style>

<script>
    /**
     * Toast Notification System
     * Usage: showToast('Message', 'type', duration)
     * Types: success, error, warning, info
     */
    function showToast(message, type = 'info', duration = 5000) {
        const container = document.getElementById('toast-container');
        if (!container) {
            console.error('Toast container not found');
            return;
        }

        // Icon mapping
        const icons = {
            success: 'icofont-check-circled',
            error: 'icofont-warning',
            warning: 'icofont-exclamation-circle',
            info: 'icofont-info-circle'
        };

        // Title mapping
        const titles = {
            success: 'Berhasil',
            error: 'Error',
            warning: 'Peringatan',
            info: 'Informasi'
        };

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-custom toast-${type}`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="toast-header-custom">
                <div class="d-flex align-items-center flex-grow-1">
                    <div class="toast-icon">
                        <i class="${icons[type]}"></i>
                    </div>
                    <strong class="toast-title">${titles[type]}</strong>
                </div>
                <button type="button" class="toast-close" aria-label="Close">
                    <i class="icofont-close"></i>
                </button>
            </div>
            <div class="toast-body-custom">
                ${message}
            </div>
        `;

        // Add to container
        container.appendChild(toast);

        // Close button functionality
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => {
            removeToast(toast);
        });

        // Auto remove after duration
        if (duration > 0) {
            setTimeout(() => {
                removeToast(toast);
            }, duration);
        }
    }

    function removeToast(toast) {
        toast.classList.add('removing');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    // Auto-show toasts from session flash messages
    <?php if (session()->getFlashdata('success')): ?>
        showToast('<?= addslashes(session()->getFlashdata('success')) ?>', 'success');
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        showToast('<?= addslashes(session()->getFlashdata('error')) ?>', 'error');
    <?php endif; ?>

    <?php if (session()->getFlashdata('warning')): ?>
        showToast('<?= addslashes(session()->getFlashdata('warning')) ?>', 'warning');
    <?php endif; ?>

    <?php if (session()->getFlashdata('info')): ?>
        showToast('<?= addslashes(session()->getFlashdata('info')) ?>', 'info');
    <?php endif; ?>
</script>
