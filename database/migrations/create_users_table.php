<?php

require __DIR__ . '/../../vendor/autoload.php';

$dotenv = parse_ini_file(__DIR__ . '/../../.env');

$host = $dotenv['DB_HOST'];
$port = $dotenv['DB_PORT'];
$dbname = $dotenv['DB_NAME'];
$username = $dotenv['DB_USERNAME'];
$password = $dotenv['DB_PASSWORD'];

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "✅ Connected to database successfully!\n\n";
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) UNIQUE NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(255) NOT NULL,
        role VARCHAR(50) DEFAULT 'cashier',
        phone_number VARCHAR(50) NULL,
        is_active TINYINT(1) DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        last_login DATETIME NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "✅ Users table created successfully!\n\n";
    
    // Check if any users exist
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch();
    
    if ($result['total'] == 0) {
        // Insert sample users
        $users = [
            [
                'full_name' => 'John Smith',
                'username' => 'john.smith',
                'email' => 'john@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'cashier',
                'phone_number' => '+1234567890',
                'is_active' => 1
            ],
            [
                'full_name' => 'Sarah Johnson',
                'username' => 'sarah.j',
                'email' => 'sarah@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'manager',
                'phone_number' => '+1987654321',
                'is_active' => 1
            ],
            [
                'full_name' => 'Mike Wilson',
                'username' => 'mike.w',
                'email' => 'mike@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'sales',
                'phone_number' => '+1122334455',
                'is_active' => 0
            ]
        ];
        
        $stmt = $pdo->prepare("INSERT INTO users (full_name, username, email, password, role, phone_number, is_active) 
                               VALUES (:full_name, :username, :email, :password, :role, :phone_number, :is_active)");
        
        foreach ($users as $user) {
            $stmt->execute($user);
        }
        
        echo "✅ Sample users created:\n";
        echo "  - John Smith (Cashier) - Active\n";
        echo "  - Sarah Johnson (Manager) - Active\n";
        echo "  - Mike Wilson (Sales) - Inactive\n\n";
    } else {
        echo "ℹ️  Users already exist in the database\n\n";
    }
    
    // Show stats
    $stmt = $pdo->query("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive
        FROM users");
    $stats = $stmt->fetch();
    
    echo "📊 Current Statistics:\n";
    echo "  Total Users: " . $stats['total'] . "\n";
    echo "  Active: " . $stats['active'] . "\n";
    echo "  Inactive: " . $stats['inactive'] . "\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
