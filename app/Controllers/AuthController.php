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
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        if (empty($email) || empty($password)) {
            Session::flash('error', 'Please enter both email and password');
            header('Location: /login');
            exit;
        }

        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();

            // Use OR with different parameter names
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = :email OR username = :username LIMIT 1");
            $stmt->execute([
                ':email' => $email,
                ':username' => $email
            ]);
            $admin = $stmt->fetch();

            if (!$admin) {
                Session::flash('error', 'Invalid credentials');
                header('Location: /login');
                exit;
            }

            if (!password_verify($password, $admin['password_hash'])) {
                Session::flash('error', 'Invalid credentials');
                header('Location: /login');
                exit;
            }

            if ($admin['status'] !== 'active') {
                Session::flash('error', 'Account is disabled');
                header('Location: /login');
                exit;
            }

            // Login successful
            Session::set('admin_id', $admin['id']);
            Session::set('admin_username', $admin['username']);
            Session::set('admin_email', $admin['email']);
            Session::set('login_time', time());

            // Update last login
            $updateStmt = $pdo->prepare("UPDATE admin SET last_login = NOW() WHERE id = :id");
            $updateStmt->execute([':id' => $admin['id']]);

            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + 30 * 24 * 60 * 60, '/', '', false, true);
            }

            Session::flash('success', 'Welcome back, ' . $admin['username'] . '!');
            header('Location: /admin/dashboard');
            exit;

        } catch (Exception $e) {
            Session::flash('error', 'Login failed: ' . $e->getMessage());
            header('Location: /login');
            exit;
        }
    }

    public function logout() {
        Session::init();
        Session::destroy();
        
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        header('Location: /login');
        exit;
    }

    public function dashboard() {
        Session::requireAuth();
        
        $username = Session::get('admin_username');
        echo "<h1>Welcome to Dashboard</h1>";
        echo "<p>Hello, " . htmlspecialchars($username) . "!</p>";
        echo '<a href="/logout">Logout</a>';
    }
}
