import './bootstrap';
import 'flowbite';
import './dashboard-chart';

document.addEventListener("DOMContentLoaded", function() {
    let loading = document.getElementById('globalLoading');

    function showLoading() {
        loading.style.display = 'flex';
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

    // Show loading on all link clicks
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function(event) {
            if (event.target.getAttribute('href') && !event.target.getAttribute('href').startsWith('#')) {
                showLoading();
            }
        });
    });

    // Hide loading on page load complete
    window.addEventListener('load', hideLoading);
});
