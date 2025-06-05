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

// 檢查是否為POST請求
if ($method === 'POST') {
    // 取得POST資料
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // 檢查必要參數
    if (!isset($data['table']) || !isset($data['data']) || !is_array($data['data'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required parameters'
        ]);
        exit;
    }

    // 檢查是否有登入
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'User not logged in'
        ]);
        exit;
    }
    $loginUserId = $_SESSION['user_id'];

    $tableName = $data['table'];
    $insertData = $data['data'];

    // 強制 user_id 為登入者
    $insertData['user_id'] = $loginUserId;

    // 驗證資料表名稱（防止SQL注入）
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid table name'
        ]);
        exit;
    }

    try {
        // 先檢查資料表結構
        $initUrl = "http://localhost/get_ele/main.php?table=" . urlencode($tableName);
        $initResponse = file_get_contents($initUrl);
        $initData = json_decode($initResponse, true);

        if ($initData['status'] !== 'success') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to validate table structure: ' . $initData['message']
            ]);
            exit;
        }

        // 驗證欄位是否存在於資料表中
        $validColumns = array_column($initData['data']['columns'], 'name');
        foreach ($insertData as $column => $value) {
            if (!in_array($column, $validColumns)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => "Invalid column name: $column"
                ]);
                exit;
            }
        }

        // 準備欄位名稱和值的陣列
        $columns = array_keys($insertData);
        $values = array_values($insertData);
        $placeholders = array_fill(0, count($columns), '?');
        
        // 建立SQL查詢
        $sql = "INSERT INTO " . $tableName . " (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $pdo->prepare($sql);
        
        // 執行查詢
        $stmt->execute($values);
        
        // 取得新增的資料ID
        $lastId = $pdo->lastInsertId();
        
        // 回傳成功訊息
        echo json_encode([
            'status' => 'success',
            'message' => 'Data inserted successfully',
            'insert_id' => $lastId
        ]);
        
    } catch(PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }

    // 關閉資料庫連接
    $pdo = null;
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method. Only POST is allowed.'
    ]);
}
?>