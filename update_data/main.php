<?php
// 設定允許跨域請求
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Content-Type: application/json; charset=utf-8');

// 資料庫連接設定
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// 取得請求方法
$method = $_SERVER['REQUEST_METHOD'];

// 檢查是否為POST請求
if ($method === 'POST') {
    // 取得POST資料
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // 檢查必要參數
    if (!isset($data['table']) || !isset($data['conditions']) || !is_array($data['conditions']) || 
        !isset($data['values']) || !is_array($data['values'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required parameters'
        ]);
        exit;
    }

    $tableName = $data['table'];
    $conditions = $data['conditions'];
    $values = $data['values'];

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
        
        // 驗證條件欄位
        foreach ($conditions as $condition) {
            if (!in_array($condition['column'], $validColumns)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => "Invalid condition column name: {$condition['column']}"
                ]);
                exit;
            }
        }
        
        // 驗證更新欄位
        foreach ($values as $column => $value) {
            if (!in_array($column, $validColumns)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => "Invalid update column name: $column"
                ]);
                exit;
            }
        }

        // 建立資料庫連接
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        
        // 設定錯誤模式為例外
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // 建立SET子句
        $setClauses = [];
        $setParams = [];
        
        foreach ($values as $column => $value) {
            $setClauses[] = "$column = ?";
            $setParams[] = $value;
        }
        
        // 建立WHERE子句
        $whereClauses = [];
        $whereParams = [];
        
        foreach ($conditions as $condition) {
            $column = $condition['column'];
            $operator = $condition['operator'];
            $value = $condition['value'];
            
            // 驗證運算符
            $validOperators = ['=', '!=', '>', '<', '>=', '<=', 'LIKE'];
            if (!in_array($operator, $validOperators)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => "Invalid operator: $operator"
                ]);
                exit;
            }
            
            $whereClauses[] = "$column $operator ?";
            $whereParams[] = $value;
        }
        
        // 建立SQL查詢
        $sql = "UPDATE " . $tableName . " SET " . implode(', ', $setClauses);
        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }
        
        $stmt = $pdo->prepare($sql);
        
        // 執行查詢
        $stmt->execute(array_merge($setParams, $whereParams));
        
        // 取得受影響的列數
        $affectedRows = $stmt->rowCount();
        
        // 回傳成功訊息
        echo json_encode([
            'status' => 'success',
            'message' => 'Data updated successfully',
            'affected_rows' => $affectedRows
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