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
    // 取得參數
    $tableName = isset($_REQUEST['table']) ? $_REQUEST['table'] : '';
    $columnName = isset($_REQUEST['column']) ? $_REQUEST['column'] : '';
    $rowValue = isset($_REQUEST['value']) ? $_REQUEST['value'] : '';
    
    // 驗證資料表名稱和欄位名稱（防止SQL注入）
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName) || !preg_match('/^[a-zA-Z0-9_]+$/', $columnName)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid table name or column name'
        ]);
        exit;
    }

    try {
        // 建立資料庫連接
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        
        // 設定錯誤模式為例外
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // 準備SQL查詢（使用精確匹配）
        $sql = "SELECT * FROM " . $tableName . " WHERE " . $columnName . " = :rowValue LIMIT 1";
        $stmt = $pdo->prepare($sql);
        
        // 設定搜尋值
        $stmt->bindParam(':rowValue', $rowValue);
        
        // 執行查詢
        $stmt->execute();
        
        // 取得結果
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // 回傳JSON格式的結果
            echo json_encode([
                'status' => 'success',
                'data' => $result
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No matching record found'
            ]);
        }
        
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
