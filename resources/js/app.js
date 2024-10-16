import './bootstrap';
import 'flowbite';
import './dashboard-chart';

document.addEventListener("DOMContentLoaded", function() {
    let loading = document.getElementById('globalLoading');
    const currentPath = window.location.pathname;

    function showLoading() {
        // Only show loading if not on excluded routes
        if (!['/loading-pmgi', '/loading-perakuan'].includes(currentPath)) {
            loading.style.display = 'flex';
        }
    }

    function hideLoading() {
        loading.style.display = 'none';
    }

    // Show loading on all AJAX requests
    $(document).ajaxStart(function() {
        showLoading();
    }).ajaxStop(function() {
        hideLoading();
    });

    // Show loading on all fetch requests
    (function(fetch) {
        window.fetch = function(...args) {
            showLoading();
            return fetch(...args).finally(() => hideLoading());
        };
    })(window.fetch);

    // Show loading on form submission
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', showLoading);
    });

    // Show loading on all link clicks, but hide it shortly after for download links
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function(event) {
            const href = event.target.getAttribute('href');

            if (href && !href.startsWith('#')) {
                showLoading();

                // Check if the link is for a file download based on file extension
                const downloadExtensions = ['pdf', 'docx', 'xlsx', 'csv'];
                const isDownloadLink = downloadExtensions.some(ext => href.endsWith(`.${ext}`));

                // If it's a download link, hide the loading indicator after a short delay
                if (isDownloadLink) {
                    setTimeout(() => {
                        hideLoading();
                    }, 1000); // Adjust the delay as needed (3 seconds here)
                }
            }
        });
    });

    // Hide loading on page load complete
    window.addEventListener('load', hideLoading);
});
