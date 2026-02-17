<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Config/Database.php';

header('Content-Type: application/json');

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $search = $_GET['search'] ?? 'john';
    $searchParam = '%' . $search . '%';
    
    // Test 1: Direct query
    $sql1 = "SELECT COUNT(*) as total FROM users WHERE full_name LIKE ?";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute([$searchParam]);
    $result1 = $stmt1->fetch();
    
    // Test 2: Named parameter
    $sql2 = "SELECT COUNT(*) as total FROM users WHERE full_name LIKE :search";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([':search' => $searchParam]);
    $result2 = $stmt2->fetch();
    
    // Test 3: Multiple conditions
    $sql3 = "SELECT COUNT(*) as total FROM users WHERE (full_name LIKE :search OR username LIKE :search OR email LIKE :search)";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->bindValue(':search', $searchParam);
    $stmt3->execute();
    $result3 = $stmt3->fetch();
    
    // Test 4: Full query with all params
    $where = ["(full_name LIKE :search OR username LIKE :search OR email LIKE :search)"];
    $whereClause = 'WHERE ' . implode(' AND ', $where);
    $params = [':search' => $searchParam];
    
    $sql4 = "SELECT COUNT(*) as total FROM users $whereClause";
    $stmt4 = $pdo->prepare($sql4);
    foreach ($params as $key => $value) {
        $stmt4->bindValue($key, $value);
    }
    $stmt4->execute();
    $result4 = $stmt4->fetch();
    
    echo json_encode([
        'success' => true,
        'search_term' => $search,
        'tests' => [
            'test1_direct_query' => $result1,
            'test2_named_param' => $result2,
            'test3_multiple_conditions' => $result3,
            'test4_full_query' => $result4
        ]
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], JSON_PRETTY_PRINT);
}
