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
    
    echo "Connected to database successfully!\n";
    
    // Create admin table for admin panel access
    $sql = "CREATE TABLE IF NOT EXISTS admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(100) NOT NULL,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('superadmin', 'admin', 'manager') DEFAULT 'admin',
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "Admin table created successfully!\n";
    
    // Check if default admin exists
    $stmt = $pdo->prepare("SELECT id FROM admin WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => 'admin']);
    $admin = $stmt->fetch();
    
    if (!$admin) {
        // Insert default admin
        $passwordHash = password_hash('admin', PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO admin (fullname, username, email, password_hash, role, status) 
                               VALUES (:fullname, :username, :email, :password_hash, :role, :status)");
        
        $stmt->execute([
            ':fullname' => 'System Administrator',
            ':username' => 'admin',
            ':email' => 'admin@admin.com',
            ':password_hash' => $passwordHash,
            ':role' => 'superadmin',
            ':status' => 'active'
        ]);
        
        echo "\n✓ Default admin user created!\n";
        echo "  Username: admin\n";
        echo "  Password: admin\n";
        echo "  Role: superadmin\n";
    } else {
        echo "Default admin user already exists.\n";
    }
    
    echo "\n✓ Admin panel is ready!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
