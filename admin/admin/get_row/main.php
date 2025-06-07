<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// 資料庫連接設定
require_once __DIR__ . '/../db_config/main.php';


// 檢查必要參數
if (!isset($_GET['table']) || !isset($_GET['column']) || !isset($_GET['value'])) {
    echo json_encode([
        'status' => 'error',
        'message' => '缺少必要參數'
    ]);
    exit;
}

// 獲取參數
$table = $_GET['table'];
$column = $_GET['column'];
$value = $_GET['value'];

// 驗證表名（防止SQL注入）
if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
    echo json_encode([
        'status' => 'error',
        'message' => '無效的表名'
    ]);
    exit;
}

try {

    // 準備SQL語句
    $sql = "SELECT * FROM $table WHERE $column = :value LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':value', $value);
    $stmt->execute();

    // 獲取結果
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'data' => $result
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '未找到符合條件的資料'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => '資料庫錯誤：' . $e->getMessage()
    ]);
}
?> 