<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ionic@latest/css/ionic.bundle.css">
    <style>
        :root {
            --ion-color-primary: #3880ff;
            --ion-color-primary-rgb: 56, 128, 255;
            --ion-color-primary-contrast: #ffffff;
            --ion-color-primary-contrast-rgb: 255, 255, 255;
            --ion-color-primary-shade: #3171e0;
            --ion-color-primary-tint: #4c8dff;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            padding: 40px 30px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header ion-icon {
            font-size: 60px;
            color: var(--ion-color-primary);
            margin-bottom: 10px;
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
            -webkit-appearance: none;
            appearance: none;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--ion-color-primary);
            box-shadow: 0 0 0 3px rgba(56, 128, 255, 0.1);
        }

        .form-input::placeholder {
            color: #999;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            color: #666;
        }

        .remember-me input {
            margin-right: 8px;
            width: 18px;
            height: 18px;
            accent-color: var(--ion-color-primary);
        }

        .forgot-password {
            color: var(--ion-color-primary);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #999;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            padding: 0 15px;
        }

        .social-login {
            display: flex;
            gap: 15px;
        }

        .social-button {
            flex: 1;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .social-button:hover {
            border-color: var(--ion-color-primary);
            background: rgba(56, 128, 255, 0.05);
        }

        .social-button ion-icon {
            font-size: 24px;
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }

            .login-card {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 22px;
            }

            .remember-forgot {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <ion-icon name="person-circle-outline"></ion-icon>
                <h1>Admin Login</h1>
                <p>Enter your credentials to access</p>
            </div>

            <form action="/admin/login" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="admin@example.com" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Enter your password" 
                        required
                    >
                </div>

                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    <a href="/admin/forgot-password" class="forgot-password">Forgot Password?</a>
                </div>

                <button type="submit" class="login-button">Sign In</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/ionic@latest/js/ionic.bundle.js"></script>
</body>
</html>
