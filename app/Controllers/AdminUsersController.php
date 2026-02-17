<?php

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Config/Session.php';

class AdminUsersController {

    public function index() {
        Session::requireAuth();
        Session::init();

        // Add security headers
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;");

        $admin_username = Session::get('admin_username');

        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();

            // Get all admin users
            $stmt = $pdo->query("SELECT * FROM admin ORDER BY created_at DESC");
            $admins = $stmt->fetchAll();

            // Get statistics
            $total_admins = count($admins);
            $active_admins = count(array_filter($admins, function($a) { return $a['status'] === 'active'; }));
            $inactive_admins = $total_admins - $active_admins;

        } catch (Exception $e) {
            error_log('Admin users list error: ' . $e->getMessage());
            $admins = [];
            $total_admins = 0;
            $active_admins = 0;
            $inactive_admins = 0;
        }

        require __DIR__ . '/../Views/admin_users/index.php';
    }

    public function create() {
        Session::requireAuth();

        // Add security headers
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;");

        $admin_username = Session::get('admin_username');

        require __DIR__ . '/../Views/admin_users/create.php';
    }

    public function store() {
        Session::requireAuth();
        Session::init();

        // Validate input
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $status = $_POST['status'] ?? 'active';

        // Validation
        $errors = [];

        if (empty($username)) {
            $errors[] = 'Username is required';
        } elseif (strlen($username) < 3) {
            $errors[] = 'Username must be at least 3 characters';
        }

        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address';
        }

        if (empty($password)) {
            $errors[] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = implode(', ', $errors);
            header('Location: /admin/admins/create');
            exit;
        }

        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();

            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM admin WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            if ($stmt->fetch()) {
                $_SESSION['error_message'] = 'Username already exists';
                header('Location: /admin/admins/create');
                exit;
            }

            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM admin WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $_SESSION['error_message'] = 'Email already exists';
                header('Location: /admin/admins/create');
                exit;
            }

            // Hash password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin
            $stmt = $pdo->prepare("
                INSERT INTO admin (username, email, password_hash, status, created_at)
                VALUES (:username, :email, :password_hash, :status, NOW())
            ");

            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password_hash' => $passwordHash,
                ':status' => $status
            ]);

            $_SESSION['success_message'] = 'Admin user created successfully!';
            header('Location: /admin/admins');
            exit;

        } catch (Exception $e) {
            error_log('Create admin error: ' . $e->getMessage());
            $_SESSION['error_message'] = 'Failed to create admin user. Please try again.';
            header('Location: /admin/admins/create');
            exit;
        }
    }
}
