<?php

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Config/Session.php';

class AuthController {
    
    public function login() {
        Session::init();
        
        if (Session::isLoggedIn()) {
            header('Location: /admin/dashboard');
            exit;
        }
        
        $error = Session::flash('error');
        $success = Session::flash('success');
        
        require __DIR__ . '/../Views/login.php';
    }

    public function authenticate() {
        Session::init();
        
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        // Validation
        if (empty($username) || empty($password)) {
            Session::flash('error', 'Please enter both username and password');
            header('Location: /login');
            exit;
        }

        // Rate limiting check
        $attempts = Session::get('login_attempts', 0);
        $lastAttempt = Session::get('last_attempt_time', 0);
        
        if ($attempts >= 5 && time() - $lastAttempt < 300) {
            Session::flash('error', 'Too many failed attempts. Please try again in 5 minutes.');
            header('Location: /login');
            exit;
        }

        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();

            // Find admin by username only
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            $admin = $stmt->fetch();

            // Generic error message for security
            if (!$admin || !password_verify($password, $admin['password_hash'])) {
                // Log failed attempt
                Session::set('login_attempts', $attempts + 1);
                Session::set('last_attempt_time', time());
                
                Session::flash('error', 'Invalid username or password');
                header('Location: /login');
                exit;
            }

            if ($admin['status'] !== 'active') {
                Session::flash('error', 'Account is disabled');
                header('Location: /login');
                exit;
            }

            // Clear failed attempts
            Session::remove('login_attempts');
            Session::remove('last_attempt_time');

            // Login successful - create session
            Session::set('admin_id', $admin['id']);
            Session::set('admin_username', $admin['username']);
            Session::set('login_time', time());

            // Update last login
            $updateStmt = $pdo->prepare("UPDATE admin SET last_login = NOW() WHERE id = :id");
            $updateStmt->execute([':id' => $admin['id']]);

            // Set remember me cookie if requested
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, [
                    'expires' => time() + 30 * 24 * 60 * 60,
                    'path' => '/',
                    'httponly' => true,
                    'secure' => true,
                    'samesite' => 'Strict'
                ]);
            }

            Session::flash('success', 'Welcome back, ' . $admin['username'] . '!');
            header('Location: /admin/dashboard');
            exit;

        } catch (Exception $e) {
            error_log('Login error: ' . $e->getMessage());
            Session::flash('error', 'Login failed. Please try again later.');
            header('Location: /login');
            exit;
        }
    }

    public function logout() {
        Session::init();
        Session::destroy();
        
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', [
                'expires' => time() - 3600,
                'path' => '/',
                'httponly' => true,
                'secure' => true,
                'samesite' => 'Strict'
            ]);
        }
        
        header('Location: /login');
        exit;
    }

    public function dashboard() {
        Session::requireAuth();
        
        $username = Session::get('admin_username');
        
        // Add security headers
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;");
        
        echo "<!DOCTYPE html>\n";
        echo "<html>\n";
        echo "<head>\n";
        echo "<title>Admin Dashboard</title>\n";
        echo "<style>body{font-family:system-ui;margin:40px;}</style>\n";
        echo "</head>\n";
        echo "<body>\n";
        echo "<h1>Welcome to Dashboard</h1>\n";
        echo "<p>Hello, " . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . "!</p>\n";
        echo '<a href="/logout">Logout</a>\n';
        echo "</body>\n";
        echo "</html>\n";
    }
}
