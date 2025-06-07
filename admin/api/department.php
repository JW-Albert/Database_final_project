<?php
require_once '../php/config.php';

header('Content-Type: application/json');

// 檢查是否有提供系所ID
if (!isset($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing department ID'
    ]);
    exit;
}

$department_id = $_GET['id'];

try {
    // 建立資料庫連接
    $pdo = getDBConnection();
    
    // 準備 SQL 查詢
    $stmt = $pdo->prepare("
        SELECT 
            department_id,
            locat,
            office,
            phone_extension,
            email
        FROM Department
        WHERE department_id = ?
    ");

    // 執行查詢
    $stmt->execute([$department_id]);
    $department = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($department) {
        echo json_encode([
            'success' => true,
            'data' => $department
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Department not found'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?> 