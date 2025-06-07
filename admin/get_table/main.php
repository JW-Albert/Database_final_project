<?php
header('Content-Type: application/json');

// 載入你的資料庫設定
require_once __DIR__ . '/../db_config/main.php'; // 請確認這裡路徑正確

try {
    // 執行 SQL 取得所有資料表
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_NUM);

    // 把每一列的第一個欄位取出（表名）
    $tableNames = array_map(fn($row) => $row[0], $tables);

    echo json_encode([
        'status' => 'success',
        'tables' => $tableNames
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => '資料庫錯誤：' . $e->getMessage()
    ]);
}
