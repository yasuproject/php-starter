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
</head>
<body>
    <button class="dark-mode-toggle" onclick="toggleDarkMode()" aria-label="Toggle dark mode">
        <ion-icon class="sun-icon" name="sunny-outline"></ion-icon>
        <ion-icon class="moon-icon" name="moon-outline"></ion-icon>
    </button>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="avatar-wrapper">
                    <div class="avatar-ring"></div>
                    <ion-icon class="avatar-icon" name="person-circle-outline"></ion-icon>
                </div>
                <h1>Welcome Back</h1>
                <p>Sign in to continue</p>
            </div>

            <form action="/admin/login" method="POST">
                <div class="form-group">
                    <div class="input-wrapper">
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            placeholder="Email address" 
                            required
                            autocomplete="email"
                        >
                        <ion-icon class="input-icon" name="mail-outline"></ion-icon>
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
                    <a href="/admin/forgot-password" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="login-btn">Sign In</button>

                <button type="button" class="biometric-btn" onclick="useBiometric()">
                    <ion-icon name="finger-print-outline"></ion-icon>
                    Use Biometric
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/ionic@latest/js/ionic.bundle.js"></script>
    <script>
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

        function useBiometric() {
            if (window.FaceID || window.Fingerprint) {
                navigator.biometric.authenticate({ 
                    reason: 'Authenticate to access admin panel' 
                }).then(() => {
                    document.querySelector('form').submit();
                });
            } else {
                alert('Biometric authentication not available');
            }
        }
    </script>
</body>
</html>
