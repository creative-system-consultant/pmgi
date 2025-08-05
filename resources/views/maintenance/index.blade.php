<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Under Maintenance</title>
    @wireUiScripts
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .maintenance-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .maintenance-icon {
            font-size: 4rem;
            color: #f39c12;
            margin-bottom: 1rem;
        }
        .maintenance-title {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .maintenance-message {
            color: #7f8c8d;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #e74c3c;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .auto-refresh {
            color: #95a5a6;
            font-size: 0.9rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">🔧</div>
        <h1 class="maintenance-title">System Under Maintenance</h1>
        <p class="maintenance-message">
            <span class="status-indicator"></span>
            We're currently performing scheduled maintenance to improve your experience. 
            Please check back in a few minutes.
        </p>
        
        <div id="countdown" class="auto-refresh"></div>
        
        <x-button 
            primary 
            class="bg-primary-700 hover:bg-primary-800 focus:ring-primary-300"
            label="Check Status" 
            id="checkStatusBtn"
            wire:click="checkSystemStatus"
        />
        
        <p class="auto-refresh">
            This page will automatically refresh every 30 seconds
        </p>
    </div>

    <script>
        let countdownInterval;
        let refreshInterval;
        
        function startCountdown() {
            let seconds = 30;
            const countdownElement = document.getElementById('countdown');
            
            countdownInterval = setInterval(() => {
                seconds--;
                countdownElement.textContent = `Auto-refresh in ${seconds} seconds`;
                
                if (seconds <= 0) {
                    checkSystemStatus();
                    seconds = 30;
                }
            }, 1000);
        }
        
        function checkSystemStatus() {
            fetch('/maintenance/status', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    // Clear intervals
                    clearInterval(countdownInterval);
                    clearInterval(refreshInterval);
                    
                    // Show success message and redirect
                    WireUI.notify({
                        title: 'System Available!',
                        description: 'Redirecting to login page...',
                        icon: 'success'
                    });
                    
                    setTimeout(() => {
                        window.location.href = data.redirect_url || '/login';
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error checking system status:', error);
            });
        }
        
        // Prevent back navigation
        function preventBack() {
            window.history.forward();
        }
        
        setTimeout(preventBack, 0);
        window.onunload = function() { null };
        
        // Override browser back button
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
        
        // Push a dummy state to prevent back navigation
        history.pushState(null, null, location.href);
        window.addEventListener('popstate', function() {
            history.pushState(null, null, location.href);
        });
        
        // Start the countdown and periodic checks
        document.addEventListener('DOMContentLoaded', function() {
            startCountdown();
            
            // Set up automatic status checking every 30 seconds
            refreshInterval = setInterval(checkSystemStatus, 30000);
            
            // Manual check button
            document.getElementById('checkStatusBtn').addEventListener('click', checkSystemStatus);
        });
    </script>
</body>
</html>