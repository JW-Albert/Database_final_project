<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../php/config.php';

try {
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("SELECT * FROM Department WHERE department_id = 'D001'");
    $stmt->execute();
    $department = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($department) {
        echo json_encode([
            'success' => true,
            'data' => $department
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '找不到系所資料'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => '資料庫錯誤：' . $e->getMessage()
    ]);
}
?> 