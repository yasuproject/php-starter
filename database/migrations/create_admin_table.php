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
    
    // Create admin table
    $sql = "CREATE TABLE IF NOT EXISTS admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL,
        status ENUM('active', 'inactive') DEFAULT 'active'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "Table 'admin' created successfully!\n";
    
    // Insert default admin
    $passwordHash = password_hash('admin', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO admin (username, email, password_hash) 
                           VALUES (:username, :email, :password_hash)
                           ON DUPLICATE KEY UPDATE 
                           password_hash = :password_hash2");
    
    $stmt->execute([
        ':username' => 'admin',
        ':email' => 'admin@admin.com',
        ':password_hash' => $passwordHash,
        ':password_hash2' => $passwordHash
    ]);
    
    echo "Default admin user created!\n";
    echo "Username: admin\n";
    echo "Password: admin\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
