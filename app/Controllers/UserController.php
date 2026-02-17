<?php

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Config/Session.php';

class UserController {

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

            // Get all users
            $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
            $users = $stmt->fetchAll();

            // Get statistics
            $total_users = count($users);
            $active_users = count(array_filter($users, function($u) { return $u['is_active']; }));
            $inactive_users = $total_users - $active_users;

        } catch (Exception $e) {
            error_log('Users list error: ' . $e->getMessage());
            $users = [];
            $total_users = 0;
            $active_users = 0;
            $inactive_users = 0;
        }

        require __DIR__ . '/../Views/users/index.php';
    }

    public function create() {
        Session::requireAuth();

        // Add security headers
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;");

        $username = Session::get('admin_username');

        require __DIR__ . '/../Views/users/create.php';
    }

    public function store() {
        Session::requireAuth();
        Session::init();

        // Validate input
        $fullname = trim($_POST['fullname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'cashier';
        $status = $_POST['status'] ?? 'active';

        // Validation
        $errors = [];

        if (empty($fullname)) {
            $errors[] = 'Full name is required';
        }

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

        if (empty($role)) {
            $errors[] = 'Role is required';
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = implode(', ', $errors);
            header('Location: /admin/users/create');
            exit;
        }

        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();

            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            if ($stmt->fetch()) {
                $_SESSION['error_message'] = 'Username already exists';
                header('Location: /admin/users/create');
                exit;
            }

            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $_SESSION['error_message'] = 'Email already exists';
                header('Location: /admin/users/create');
                exit;
            }

            // Hash password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $pdo->prepare("
                INSERT INTO users (full_name, username, email, phone_number, password, role, is_active, created_at)
                VALUES (:full_name, :username, :email, :phone_number, :password, :role, :is_active, NOW())
            ");

            $isActive = ($status === 'active') ? 1 : 0;

            $stmt->execute([
                ':full_name' => $fullname,
                ':username' => $username,
                ':email' => $email,
                ':phone_number' => $phone ?: null,
                ':password' => $passwordHash,
                ':role' => $role,
                ':is_active' => $isActive
            ]);

            $_SESSION['success_message'] = 'User created successfully!';
            header('Location: /admin/users');
            exit;

        } catch (Exception $e) {
            error_log('Create user error: ' . $e->getMessage());
            $_SESSION['error_message'] = 'Failed to create user. Please try again.';
            header('Location: /admin/users/create');
            exit;
        }
    }

    public function edit() {
        Session::requireAuth();
        Session::init();

        // Add security headers
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;");

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error_message'] = 'User ID is required';
            header('Location: /admin/users');
            exit;
        }

        $admin_username = Session::get('admin_username');

        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();

            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch();

            if (!$user) {
                $_SESSION['error_message'] = 'User not found';
                header('Location: /admin/users');
                exit;
            }

        } catch (Exception $e) {
            error_log('Edit user error: ' . $e->getMessage());
            $_SESSION['error_message'] = 'Failed to load user. Please try again.';
            header('Location: /admin/users');
            exit;
        }

        require __DIR__ . '/../Views/users/edit.php';
    }

    public function update() {
        Session::requireAuth();
        Session::init();

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error_message'] = 'User ID is required';
            header('Location: /admin/users');
            exit;
        }

        // Validate input
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? 'cashier';
        $status = $_POST['status'] ?? 'active';
        $password = $_POST['password'] ?? '';

        // Validation
        $errors = [];

        if (empty($fullname)) {
            $errors[] = 'Full name is required';
        }

        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address';
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = implode(', ', $errors);
            header('Location: /admin/users/edit?id=' . $id);
            exit;
        }

        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();

            // Check if email already exists for other users
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1");
            $stmt->execute([':email' => $email, ':id' => $id]);
            if ($stmt->fetch()) {
                $_SESSION['error_message'] = 'Email already exists';
                header('Location: /admin/users/edit?id=' . $id);
                exit;
            }

            $isActive = ($status === 'active') ? 1 : 0;

            // Update user
            if (!empty($password)) {
                if (strlen($password) < 8) {
                    $_SESSION['error_message'] = 'Password must be at least 8 characters';
                    header('Location: /admin/users/edit?id=' . $id);
                    exit;
                }

                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    UPDATE users
                    SET full_name = :full_name, email = :email, phone_number = :phone_number,
                        role = :role, is_active = :is_active, password = :password
                    WHERE id = :id
                ");
                $stmt->execute([
                    ':id' => $id,
                    ':full_name' => $fullname,
                    ':email' => $email,
                    ':phone_number' => $phone ?: null,
                    ':role' => $role,
                    ':is_active' => $isActive,
                    ':password' => $passwordHash
                ]);
            } else {
                $stmt = $pdo->prepare("
                    UPDATE users
                    SET full_name = :full_name, email = :email, phone_number = :phone_number,
                        role = :role, is_active = :is_active
                    WHERE id = :id
                ");
                $stmt->execute([
                    ':id' => $id,
                    ':full_name' => $fullname,
                    ':email' => $email,
                    ':phone_number' => $phone ?: null,
                    ':role' => $role,
                    ':is_active' => $isActive
                ]);
            }

            $_SESSION['success_message'] = 'User updated successfully!';
            header('Location: /admin/users');
            exit;

        } catch (Exception $e) {
            error_log('Update user error: ' . $e->getMessage());
            $_SESSION['error_message'] = 'Failed to update user. Please try again.';
            header('Location: /admin/users/edit?id=' . $id);
            exit;
        }
    }

    public function delete() {
        Session::requireAuth();
        Session::init();

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error_message'] = 'User ID is required';
            header('Location: /admin/users');
            exit;
        }

        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();

            // Check if user exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            if (!$stmt->fetch()) {
                $_SESSION['error_message'] = 'User not found';
                header('Location: /admin/users');
                exit;
            }

            // Delete user
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);

            $_SESSION['success_message'] = 'User deleted successfully!';
            header('Location: /admin/users');
            exit;

        } catch (Exception $e) {
            error_log('Delete user error: ' . $e->getMessage());
            $_SESSION['error_message'] = 'Failed to delete user. Please try again.';
            header('Location: /admin/users');
            exit;
        }
    }
}
