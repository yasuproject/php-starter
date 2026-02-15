<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ionic@latest/css/ionic.bundle.css">
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 30px;
            max-width: 320px;
            width: 90%;
            text-align: center;
            transform: scale(0.9) translateY(20px);
            transition: transform 0.3s ease;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        .modal-overlay.active .modal {
            transform: scale(1) translateY(0);
        }
        
        .modal-icon {
            font-size: 60px;
            color: #ef4444;
            margin-bottom: 15px;
        }
        
        .modal-icon.success {
            color: #10b981;
        }
        
        .modal h3 {
            color: var(--text-primary);
            margin-bottom: 10px;
            font-size: 20px;
        }
        
        .modal p {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .modal-btn {
            background: var(--ion-color-primary);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .modal-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    </style>
</head>
<body>
    <button class="dark-mode-toggle" onclick="toggleDarkMode()" aria-label="Toggle dark mode">
        <ion-icon class="sun-icon" name="sunny-outline"></ion-icon>
        <ion-icon class="moon-icon" name="moon-outline"></ion-icon>
    </button>

    <!-- Error Modal -->
    <div class="modal-overlay" id="errorModal">
        <div class="modal">
            <ion-icon class="modal-icon" name="close-circle-outline"></ion-icon>
            <h3>Login Failed</h3>
            <p id="errorMessage">Invalid username or password</p>
            <button class="modal-btn" onclick="closeModal()">Try Again</button>
        </div>
    </div>

    <div class="login-container">
        <div class="login-card" id="loginCard">
            <div class="login-header">
                <div class="avatar-wrapper">
                    <div class="avatar-ring"></div>
                    <ion-icon class="avatar-icon" name="shield-checkmark-outline"></ion-icon>
                </div>
                <h1>Admin Login</h1>
                <p>Enter your credentials</p>
            </div>

            <form id="loginForm" action="/login" method="POST">
                <div class="form-group">
                    <div class="input-wrapper">
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="form-input" 
                            placeholder="Username" 
                            required
                            autocomplete="username"
                            autofocus
                        >
                        <ion-icon class="input-icon" name="person-outline"></ion-icon>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="Password" 
                            required
                            autocomplete="current-password"
                        >
                        <ion-icon class="input-icon" name="lock-closed-outline"></ion-icon>
                    </div>
                </div>

                <div class="options-row">
                    <label class="toggle-switch">
                        <input type="checkbox" name="remember">
                        <span class="toggle-track"></span>
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="login-btn" id="loginBtn">
                    <span>Sign In</span>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/ionic@latest/js/ionic.bundle.js"></script>
    <script>
        // Dark mode
        const html = document.documentElement;
        if (localStorage.getItem('theme') === 'dark' || 
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.setAttribute('data-theme', 'dark');
        }

        function toggleDarkMode() {
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        }

        // Modal functions
        function showModal(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorModal').classList.add('active');
            document.getElementById('loginCard').classList.add('shake');
            setTimeout(() => {
                document.getElementById('loginCard').classList.remove('shake');
            }, 500);
        }

        function closeModal() {
            document.getElementById('errorModal').classList.remove('active');
            document.getElementById('password').value = '';
            document.getElementById('password').focus();
        }

        // Check for PHP error messages
        <?php if (isset($error) && $error): ?>
        window.addEventListener('load', () => {
            showModal('<?= addslashes($error) ?>');
        });
        <?php endif; ?>

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if (!username || !password) {
                e.preventDefault();
                showModal('Please enter both username and password');
                return false;
            }
        });

        // Close modal on overlay click
        document.getElementById('errorModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
