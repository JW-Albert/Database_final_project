<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=utf-8');

// 包含資料庫連線設定
require_once '../db_config/main.php';

// 取得POST資料
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// 檢查必要參數
if (!isset($data['table']) || empty($data['table'])) {
    echo json_encode([
        'status' => 'error',
        'message' => '缺少資料表參數'
    ]);
    exit;
}

$tableName = $data['table'];
$conditions = isset($data['conditions']) ? $data['conditions'] : [];

// 驗證資料表名稱（安全性檢查）
$allowedTables = ['Professor', 'Admin', 'Staff'];
if (!in_array($tableName, $allowedTables)) {
    echo json_encode([
        'status' => 'error',
        'message' => '不允許查詢此資料表'
    ]);
    exit;
}

try {
    // 建立基本查詢
    $sql = "SELECT * FROM `$tableName`";
    $params = [];
    
    // 如果有查詢條件，建構WHERE子句
    if (!empty($conditions)) {
        $whereClause = [];
        foreach ($conditions as $condition) {
            if (isset($condition['column']) && isset($condition['operator']) && isset($condition['value'])) {
                $whereClause[] = "`{$condition['column']}` {$condition['operator']} ?";
                $params[] = $condition['value'];
            }
        }
        
        if (!empty($whereClause)) {
            $sql .= " WHERE " . implode(" AND ", $whereClause);
        }
    }
    
    // 準備並執行查詢
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'data' => $result,
        'query_info' => [
            'table' => $tableName,
            'total_rows' => count($result),
            'conditions_count' => count($conditions)
        ]
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => '資料庫查詢錯誤：' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => '查詢過程發生錯誤：' . $e->getMessage()
    ]);
}
?>