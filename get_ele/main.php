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

// 檢查是否為GET或POST請求
if ($method === 'GET' || $method === 'POST') {
    // 取得資料表名稱
    $tableName = isset($_REQUEST['table']) ? $_REQUEST['table'] : '';
    
    // 驗證資料表名稱（防止SQL注入）
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid table name'
        ]);
        exit;
    }

    try {
        // 建立資料庫連接
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        
        // 設定錯誤模式為例外
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // 查詢資料表結構
        $sql = "SHOW COLUMNS FROM " . $tableName;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        // 取得所有欄位資訊
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 整理欄位資訊
        $columnInfo = [];
        foreach ($columns as $column) {
            $columnInfo[] = [
                'name' => $column['Field'],
                'type' => $column['Type'],
                'null' => $column['Null'],
                'key' => $column['Key'],
                'default' => $column['Default'],
                'extra' => $column['Extra']
            ];
        }

        // 檢查資料表是否存在
        if (empty($columnInfo)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Table does not exist'
            ]);
            exit;
        }
        
        // 回傳JSON格式的結果
        echo json_encode([
            'status' => 'success',
            'data' => [
                'table_name' => $tableName,
                'columns' => $columnInfo
            ]
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
        'message' => 'Invalid request method'
    ]);
}
?> 