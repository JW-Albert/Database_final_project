<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// 資料庫連接設定
$host = 'localhost';
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';

// 檢查必要參數
if (!isset($_GET['table'])) {
    echo json_encode([
        'status' => 'error',
        'message' => '缺少必要參數'
    ]);
    exit;
}

// 獲取參數
$table = $_GET['table'];

// 驗證表名（防止SQL注入）
if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
    echo json_encode([
        'status' => 'error',
        'message' => '無效的表名'
    ]);
    exit;
}

try {
    // 建立資料庫連接
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 準備SQL語句
    $sql = "SELECT * FROM $table";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // 獲取結果
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        echo json_encode([
            'status' => 'success',
            'data' => $results
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '未找到資料'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => '資料庫錯誤：' . $e->getMessage()
    ]);
}
?> 