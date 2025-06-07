<?php
// 設定允許跨域請求
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../db_config/main.php';


// 取得請求方法
$method = $_SERVER['REQUEST_METHOD'];

// 檢查是否為POST請求
if ($method === 'POST') {
    // 取得POST資料
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // 檢查必要參數
    if (!isset($data['table']) || !isset($data['conditions']) || !is_array($data['conditions'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required parameters'
        ]);
        exit;
    }

    $tableName = $data['table'];
    $conditions = $data['conditions'];

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
        $initUrl = "get_ele/main.php?table=" . urlencode($tableName);
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
        foreach ($conditions as $condition) {
            if (!in_array($condition['column'], $validColumns)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => "Invalid column name: {$condition['column']}"
                ]);
                exit;
            }
        }


        
        // 建立WHERE子句
        $whereClauses = [];
        $params = [];
        
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
            $params[] = $value;
        }
        
        // 建立SQL查詢
        $sql = "DELETE FROM " . $tableName;
        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }
        
        $stmt = $pdo->prepare($sql);
        
        // 執行查詢
        $stmt->execute($params);
        
        // 取得受影響的列數
        $affectedRows = $stmt->rowCount();
        
        // 回傳成功訊息
        echo json_encode([
            'status' => 'success',
            'message' => 'Data deleted successfully',
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