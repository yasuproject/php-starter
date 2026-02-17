<?php

require_once __DIR__ . '/../Config/Database.php';

class UsersApiController {

    private function getApiKey() {
        // Try headers first
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
            if (strpos($authHeader, 'Bearer ') === 0) {
                return substr($authHeader, 7);
            }
        }
        
        // Try apache_request_headers
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
            if (strpos($authHeader, 'Bearer ') === 0) {
                return substr($authHeader, 7);
            }
        }

        // Fallback to query parameter
        return $_GET['api_key'] ?? '';
    }

    private function authenticate() {
        $apiKey = $this->getApiKey();

        if (empty($apiKey)) {
            $this->jsonResponse(['error' => 'API key required'], 401);
            exit;
        }

        // Get API key from environment
        $validKey = getenv('API_KEY') ?: ($_ENV['API_KEY'] ?? null);

        if (!$validKey && file_exists(__DIR__ . '/../../.env')) {
            $dotenv = parse_ini_file(__DIR__ . '/../../.env');
            $validKey = $dotenv['API_KEY'] ?? null;
        }

        if ($apiKey !== $validKey) {
            $this->jsonResponse(['error' => 'Invalid API key'], 401);
            exit;
        }
    }

    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function getDb() {
        try {
            return Database::getInstance()->getConnection();
        } catch (Exception $e) {
            $this->jsonResponse(['error' => 'Database connection failed'], 500);
            exit;
        }
    }

    // GET /api/users - List all users
    public function index() {
        $this->authenticate();

        try {
            $pdo = $this->getDb();

            // Get query parameters
            $role = $_GET['role'] ?? null;
            $status = $_GET['status'] ?? null;
            $search = $_GET['search'] ?? null;
            $page = (int)($_GET['page'] ?? 1);
            $limit = (int)($_GET['limit'] ?? 20);
            $offset = ($page - 1) * $limit;

            // Build query
            $where = [];
            $params = [];

            if ($role) {
                $where[] = "role = :role";
                $params[':role'] = $role;
            }

            if ($status === 'active') {
                $where[] = "is_active = 1";
            } elseif ($status === 'inactive') {
                $where[] = "is_active = 0";
            }

            if ($search) {
                $where[] = "(full_name LIKE :search OR username LIKE :search OR email LIKE :search)";
                $params[':search'] = '%' . $search . '%';
            }

            $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            // Get total count
            $countSql = "SELECT COUNT(*) as total FROM users $whereClause";
            $countStmt = $pdo->prepare($countSql);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $total = $countStmt->fetch()['total'];

            // Get users
            $sql = "SELECT id, full_name, username, email, phone_number, role, is_active, created_at, last_login 
                    FROM users $whereClause 
                    ORDER BY created_at DESC 
                    LIMIT :limit OFFSET :offset";

            $stmt = $pdo->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $users = $stmt->fetchAll();

            $this->jsonResponse([
                'success' => true,
                'data' => $users,
                'meta' => [
                    'total' => (int)$total,
                    'page' => $page,
                    'limit' => $limit,
                    'pages' => ceil($total / $limit)
                ]
            ]);

        } catch (Exception $e) {
            error_log('API List users error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Failed to fetch users'], 500);
        }
    }

    // GET /api/users/{id} - Get single user
    public function show() {
        $this->authenticate();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->jsonResponse(['error' => 'User ID is required'], 400);
        }

        try {
            $pdo = $this->getDb();
            $stmt = $pdo->prepare("SELECT id, full_name, username, email, phone_number, role, is_active, created_at, last_login 
                                   FROM users WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch();

            if (!$user) {
                $this->jsonResponse(['error' => 'User not found'], 404);
            }

            $this->jsonResponse([
                'success' => true,
                'data' => $user
            ]);

        } catch (Exception $e) {
            error_log('API Show user error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Failed to fetch user'], 500);
        }
    }

    // POST /api/users - Create user
    public function store() {
        $this->authenticate();

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        // Validation
        $errors = [];

        $fullname = trim($input['full_name'] ?? '');
        $username = trim($input['username'] ?? '');
        $email = trim($input['email'] ?? '');
        $phone = trim($input['phone_number'] ?? '');
        $password = $input['password'] ?? '';
        $role = $input['role'] ?? 'cashier';
        $isActive = $input['is_active'] ?? true;

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
            $errors[] = 'Invalid email format';
        }

        if (empty($password)) {
            $errors[] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }

        if (!in_array($role, ['cashier', 'manager', 'sales', 'inventory', 'admin'])) {
            $errors[] = 'Invalid role';
        }

        if (!empty($errors)) {
            $this->jsonResponse(['error' => 'Validation failed', 'errors' => $errors], 422);
        }

        try {
            $pdo = $this->getDb();

            // Check username exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            if ($stmt->fetch()) {
                $this->jsonResponse(['error' => 'Username already exists'], 409);
            }

            // Check email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $this->jsonResponse(['error' => 'Email already exists'], 409);
            }

            // Create user
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $isActiveValue = filter_var($isActive, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

            $stmt = $pdo->prepare("
                INSERT INTO users (full_name, username, email, phone_number, password, role, is_active, created_at)
                VALUES (:full_name, :username, :email, :phone_number, :password, :role, :is_active, NOW())
            ");

            $stmt->execute([
                ':full_name' => $fullname,
                ':username' => $username,
                ':email' => $email,
                ':phone_number' => $phone ?: null,
                ':password' => $passwordHash,
                ':role' => $role,
                ':is_active' => $isActiveValue
            ]);

            $userId = $pdo->lastInsertId();

            // Fetch created user
            $stmt = $pdo->prepare("SELECT id, full_name, username, email, phone_number, role, is_active, created_at 
                                   FROM users WHERE id = :id");
            $stmt->execute([':id' => $userId]);
            $user = $stmt->fetch();

            $this->jsonResponse([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ], 201);

        } catch (Exception $e) {
            error_log('API Create user error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Failed to create user'], 500);
        }
    }

    // PUT/PATCH /api/users/{id} - Update user
    public function update() {
        $this->authenticate();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->jsonResponse(['error' => 'User ID is required'], 400);
        }

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        try {
            $pdo = $this->getDb();

            // Check user exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            if (!$stmt->fetch()) {
                $this->jsonResponse(['error' => 'User not found'], 404);
            }

            $updates = [];
            $params = [':id' => $id];

            if (isset($input['full_name'])) {
                $updates[] = "full_name = :full_name";
                $params[':full_name'] = trim($input['full_name']);
            }

            if (isset($input['email'])) {
                $email = trim($input['email']);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->jsonResponse(['error' => 'Invalid email format'], 422);
                }

                // Check email not used by other user
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1");
                $stmt->execute([':email' => $email, ':id' => $id]);
                if ($stmt->fetch()) {
                    $this->jsonResponse(['error' => 'Email already in use'], 409);
                }

                $updates[] = "email = :email";
                $params[':email'] = $email;
            }

            if (isset($input['phone_number'])) {
                $updates[] = "phone_number = :phone_number";
                $params[':phone_number'] = trim($input['phone_number']) ?: null;
            }

            if (isset($input['role'])) {
                if (!in_array($input['role'], ['cashier', 'manager', 'sales', 'inventory', 'admin'])) {
                    $this->jsonResponse(['error' => 'Invalid role'], 422);
                }
                $updates[] = "role = :role";
                $params[':role'] = $input['role'];
            }

            if (isset($input['is_active'])) {
                $updates[] = "is_active = :is_active";
                $params[':is_active'] = filter_var($input['is_active'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
            }

            if (isset($input['password']) && !empty($input['password'])) {
                if (strlen($input['password']) < 8) {
                    $this->jsonResponse(['error' => 'Password must be at least 8 characters'], 422);
                }
                $updates[] = "password = :password";
                $params[':password'] = password_hash($input['password'], PASSWORD_DEFAULT);
            }

            if (empty($updates)) {
                $this->jsonResponse(['error' => 'No fields to update'], 422);
            }

            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            // Fetch updated user
            $stmt = $pdo->prepare("SELECT id, full_name, username, email, phone_number, role, is_active, created_at, last_login 
                                   FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch();

            $this->jsonResponse([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ]);

        } catch (Exception $e) {
            error_log('API Update user error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Failed to update user'], 500);
        }
    }

    // DELETE /api/users/{id} - Delete user
    public function delete() {
        $this->authenticate();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->jsonResponse(['error' => 'User ID is required'], 400);
        }

        try {
            $pdo = $this->getDb();

            // Check user exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            if (!$stmt->fetch()) {
                $this->jsonResponse(['error' => 'User not found'], 404);
            }

            // Delete user
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);

            $this->jsonResponse([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);

        } catch (Exception $e) {
            error_log('API Delete user error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Failed to delete user'], 500);
        }
    }

    // POST /api/users/login - Login user
    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';

        if (empty($username) || empty($password)) {
            $this->jsonResponse(['error' => 'Username and password are required'], 400);
        }

        try {
            $pdo = $this->getDb();

            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($password, $user['password'])) {
                $this->jsonResponse(['error' => 'Invalid credentials'], 401);
            }

            if (!$user['is_active']) {
                $this->jsonResponse(['error' => 'Account is inactive'], 403);
            }

            // Update last login
            $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = :id");
            $stmt->execute([':id' => $user['id']]);

            // Remove password from response
            unset($user['password']);

            $this->jsonResponse([
                'success' => true,
                'message' => 'Login successful',
                'data' => $user
            ]);

        } catch (Exception $e) {
            error_log('API Login error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Login failed'], 500);
        }
    }

    // GET /api/users/me - Get current user (requires auth)
    public function me() {
        $this->authenticate();

        $userId = $_GET['user_id'] ?? null;
        if (!$userId) {
            $this->jsonResponse(['error' => 'User ID is required'], 400);
        }

        try {
            $pdo = $this->getDb();
            $stmt = $pdo->prepare("SELECT id, full_name, username, email, phone_number, role, is_active, created_at, last_login 
                                   FROM users WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $userId]);
            $user = $stmt->fetch();

            if (!$user) {
                $this->jsonResponse(['error' => 'User not found'], 404);
            }

            $this->jsonResponse([
                'success' => true,
                'data' => $user
            ]);

        } catch (Exception $e) {
            error_log('API Me error: ' . $e->getMessage());
            $this->jsonResponse(['error' => 'Failed to fetch user'], 500);
        }
    }
}
