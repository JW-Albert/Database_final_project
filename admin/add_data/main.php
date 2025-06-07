<?php
session_start(); // 必須加這行才能取得登入資訊

// 設定允許跨域請求
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Content-Type: application/json; charset=utf-8');

// 引入共用資料庫連線設定
require_once __DIR__ . '/../db_config/main.php';

// 取得請求方法
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $json = file_get_contents('php://input');
    error_log("原始輸入資料：" . $json);
    $data = json_decode($json, true);

    // 檢查必要參數
    if (!isset($data['table']) || !isset($data['data']) || !is_array($data['data'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required parameters'
        ]);
        exit;
    }

    $tableName = $data['table'];
    $insertData = $data['data'];

    // 驗證資料表名稱格式
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid table name'
        ]);
        exit;
    }

    try {
        // 直接查詢資料庫驗證資料表結構
        $stmt = $pdo->prepare("SHOW COLUMNS FROM `$tableName`");
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($columns)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Table does not exist'
            ]);
            exit;
        }
        
        // 取得有效的欄位名稱
        $validColumns = array_column($columns, 'Field');
        
        // 驗證欄位是否存在
        foreach ($insertData as $column => $value) {
            if (!in_array($column, $validColumns)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => "Invalid column name: $column"
                ]);
                exit;
            }
        }

        // 準備 SQL
        $columns = array_keys($insertData);
        $values = array_values($insertData);
        $placeholders = array_fill(0, count($columns), '?');
        $sql = "INSERT INTO `$tableName` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        $lastId = $pdo->lastInsertId();

        echo json_encode([
            'status' => 'success',
            'message' => 'Data inserted successfully',
            'insert_id' => $lastId
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }

    $pdo = null;
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method. Only POST is allowed.'
    ]);
}
?>
