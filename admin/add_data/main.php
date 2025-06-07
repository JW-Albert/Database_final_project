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
        // 檢查資料表結構
        $initUrl = "get_ele/main.php?table=" . urlencode($tableName);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $initUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, false);

        $response = curl_exec($ch);
        file_put_contents('curl_log.txt', $response);
        $decoded = json_decode($response, true);
        curl_close($ch);

        if ($response === false) {
            echo json_encode([
                'status' => 'error',
                'message' => 'CURL failed: ' . curl_error($ch)
            ]);
            exit;
        }

        if ($decoded === null) {
            echo json_encode([
                'status' => 'error',
                'message' => '回傳不是 JSON：' . $response
            ]);
            exit;
        }

        if (!isset($decoded['status']) || $decoded['status'] !== 'success') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to validate table structure: ' . ($decoded['message'] ?? 'unknown')
            ]);
            exit;
        }

        // 驗證欄位是否存在
        $validColumns = array_column($decoded['data']['columns'], 'name');
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
