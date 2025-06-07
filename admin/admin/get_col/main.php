<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../db_config/main.php';


// 檢查必要參數
if (!isset($_GET['table']) || !isset($_GET['column'])) {
    echo json_encode([
        'status' => 'error',
        'message' => '缺少必要參數'
    ]);
    exit;
}

// 獲取參數
$table = $_GET['table'];
$column = $_GET['column'];
$value = isset($_GET['value']) ? $_GET['value'] : null;

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
    if ($value !== null) {
        $sql = "SELECT * FROM $table WHERE $column LIKE :value";
        $stmt = $pdo->prepare($sql);
        $searchValue = "%$value%";
        $stmt->bindParam(':value', $searchValue);
    } else {
        $sql = "SELECT * FROM $table";
        $stmt = $pdo->prepare($sql);
    }

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