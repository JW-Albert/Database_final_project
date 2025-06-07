<?php
session_start();
if (!isset($_SESSION['logged_in'])) { 
    http_response_code(403); 
    echo json_encode(['status' => 'error', 'message' => '未授權存取']);
    exit; 
}

try {
    // 檢查資料庫連線檔案是否存在
    $db_config_path = '../db_config/main.php';
    if (!file_exists($db_config_path)) {
        throw new Exception("資料庫配置檔案不存在: $db_config_path");
    }
    
    require_once $db_config_path;
    
    // 檢查 PDO 連線是否存在
    if (!isset($pdo)) {
        throw new Exception("資料庫連線失敗");
    }

    // 解析 JSON 資料
    $input = file_get_contents('php://input');
    if (empty($input)) {
        throw new Exception("沒有接收到資料");
    }
    
    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("JSON 解析錯誤: " . json_last_error_msg());
    }

    // 各表主鍵名稱
    $tablePrimaryKeys = [
        'Professor' => 'professor_id',
        'Course' => 'id',
        'DepartmentHead' => 'id',
        'Education' => 'id',
        'Expertise' => 'expertise_id',
        'ExternalAward' => 'id',
        'ExternalExperience' => 'id',
        'IndustryProject' => 'id',
        'InternalAward' => 'id',
        'InternalExperience' => 'id',
        'JournalPublication' => 'id',
        'Lecture' => 'id',
        'NSCProject' => 'id',
        'Patent' => 'id'
    ];

    // 資料類型處理函數
    function processValue($table, $column, $value) {
        // 如果是空字串，處理不同資料類型
        if ($value === '' || $value === null) {
            // 常見的整數欄位
            if (in_array($column, ['extension', 'phone', 'year', 'duration', 'amount', 'volume', 'issue', 'pages'])) {
                return null;
            }
            // 日期時間欄位
            if (in_array($column, ['start_date', 'end_date', 'publication_date', 'award_date', 'created_at', 'updated_at'])) {
                return null;
            }
            // 其他欄位保持空字串
            return '';
        }
        
        // 日期格式驗證和轉換
        if (in_array($column, ['start_date', 'end_date', 'publication_date', 'award_date'])) {
            if (!empty($value)) {
                // 嘗試解析日期
                $timestamp = strtotime($value);
                if ($timestamp === false) {
                    return null; // 無效日期設為 null
                }
                return date('Y-m-d', $timestamp);
            }
        }
        
        // 整數欄位驗證
        if (in_array($column, ['extension', 'phone', 'year', 'duration', 'amount', 'volume', 'issue', 'pages'])) {
            if (!is_numeric($value)) {
                return null;
            }
            return (int)$value;
        }
        
        return $value;
    }

    $pdo->beginTransaction();
    
    foreach ($data as $table => $rows) {
        $pk = $tablePrimaryKeys[$table] ?? null;
        if (!$pk) {
            error_log("未知資料表: $table");
            continue;
        }
        
        foreach ($rows as $row) {
            if (!isset($row[$pk]) || empty($row[$pk])) {
                error_log("缺少主鍵 $pk 在資料表 $table");
                continue;
            }
            
            $set = [];
            $params = [];
            foreach ($row as $col => $val) {
                if ($col !== $pk) {
                    $processedValue = processValue($table, $col, $val);
                    $set[] = "`$col` = ?";
                    $params[] = $processedValue;
                }
            }
            
            if (empty($set)) continue;
            
            $params[] = $row[$pk];
            $sql = "UPDATE `$table` SET " . implode(',', $set) . " WHERE `$pk` = ?";
            
            $stmt = $pdo->prepare($sql);
            if (!$stmt->execute($params)) {
                $errorInfo = $stmt->errorInfo();
                throw new Exception("SQL 執行失敗 ($table): " . $errorInfo[2]);
            }
        }
    }
    
    $pdo->commit();
    echo json_encode(['status' => 'success', 'message' => '所有資料已更新']);

} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    error_log("更新錯誤: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>