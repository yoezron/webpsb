<!-- Global Loading Overlay -->
<div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.7); z-index: 99999; align-items: center; justify-content: center;">
    <div class="loading-spinner-container">
        <div class="loading-spinner"></div>
        <p class="loading-text" id="loading-text">Memuat...</p>
    </div>
</div>

<style>
    #loading-overlay {
        backdrop-filter: blur(5px);
    }

    .loading-spinner-container {
        text-align: center;
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.3s ease;
    }

    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #1AB34A;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    .loading-text {
        color: #333;
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        animation: pulse 1.5s ease-in-out infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    /* Inline loader for buttons */
    .btn-loading {
        position: relative;
        pointer-events: none;
        opacity: 0.7;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid #ffffff;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }

    /* Skeleton loader for content */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 4px;
    }

    .skeleton-text {
        height: 16px;
        margin-bottom: 10px;
    }

    .skeleton-title {
        height: 24px;
        width: 60%;
        margin-bottom: 15px;
    }

    .skeleton-card {
        height: 200px;
        margin-bottom: 20px;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }
</style>

<script>
    /**
     * Global Loading Overlay
     * Usage: showLoading('Message') / hideLoading()
     */
    function showLoading(message = 'Memuat...') {
        const overlay = document.getElementById('loading-overlay');
        const text = document.getElementById('loading-text');
        if (overlay) {
            text.textContent = message;
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function hideLoading() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    /**
     * Button Loading State
     * Usage: setButtonLoading(button, true/false, 'Loading text')
     */
    function setButtonLoading(button, isLoading, loadingText = '') {
        if (!button) return;

        if (isLoading) {
            button.setAttribute('data-original-text', button.innerHTML);
            button.classList.add('btn-loading');
            button.disabled = true;
            if (loadingText) {
                button.innerHTML = `<i class="icofont-spinner icofont-spin"></i> ${loadingText}`;
            }
        } else {
            const originalText = button.getAttribute('data-original-text');
            if (originalText) {
                button.innerHTML = originalText;
            }
            button.classList.remove('btn-loading');
            button.disabled = false;
        }
    }

    /**
     * AJAX Loading Wrapper
     * Usage: await fetchWithLoading(url, options, 'Loading message')
     */
    async function fetchWithLoading(url, options = {}, loadingMessage = 'Memuat...') {
        showLoading(loadingMessage);
        try {
            const response = await fetch(url, options);
            return response;
        } finally {
            hideLoading();
        }
    }

    /**
     * Auto-hide loading on page show (handles back/forward button navigation)
     * This fixes the issue where loading spinner stays visible after clicking back
     */
    window.addEventListener('pageshow', function(event) {
        // Hide loading overlay when page is shown (including from bfcache)
        hideLoading();

        // Reset any button loading states
        document.querySelectorAll('.btn-loading').forEach(function(btn) {
            btn.classList.remove('btn-loading');
            btn.disabled = false;
            const originalText = btn.getAttribute('data-original-text');
            if (originalText) {
                btn.innerHTML = originalText;
            }
        });
    });

    // Also hide on DOMContentLoaded as fallback
    document.addEventListener('DOMContentLoaded', function() {
        hideLoading();
    });
</script>
