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
    
    $updates = $data['updates'] ?? [];
    $inserts = $data['inserts'] ?? [];
    $deletes = $data['deletes'] ?? [];
    
    // 檢查 Professor 表格是否嘗試新增多筆資料
    if (isset($inserts['Professor']) && count($inserts['Professor']) > 0) {
        // 檢查 Professor 表格是否已經有資料
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Professor");
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            throw new Exception("Professor 表格只能存在一筆資料，無法新增更多資料！");
        }
        
        if (count($inserts['Professor']) > 1) {
            throw new Exception("Professor 表格只能新增一筆資料！");
        }
    }
    
    // 檢查 Professor 表格是否嘗試刪除資料
    if (isset($deletes['Professor']) && count($deletes['Professor']) > 0) {
        throw new Exception("不允許刪除 Professor 表格的資料！");
    }
    
    // 先檢查是否有嘗試修改主鍵 (僅檢查更新操作)
    foreach ($updates as $table => $rows) {
        $pk = $tablePrimaryKeys[$table] ?? null;
        if (!$pk) {
            continue;
        }
        
        foreach ($rows as $row) {
            if (!isset($row[$pk]) || empty($row[$pk])) {
                continue;
            }
            
            // 查詢原始資料來比較主鍵是否被修改
            $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE `$pk` = ?");
            $stmt->execute([$row[$pk]]);
            $originalRow = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($originalRow && isset($originalRow[$pk]) && $originalRow[$pk] != $row[$pk]) {
                throw new Exception("不允許修改主鍵！資料表：$table，主鍵欄位：$pk");
            }
        }
    }
    
    // 處理刪除 (跳過 Professor 表格)
    foreach ($deletes as $table => $ids) {
        if ($table === 'Professor') continue; // 已在上面檢查過
        
        $pk = $tablePrimaryKeys[$table] ?? null;
        if (!$pk) continue;
        
        foreach ($ids as $id) {
            if (empty($id)) continue;
            $stmt = $pdo->prepare("DELETE FROM `$table` WHERE `$pk` = ?");
            if (!$stmt->execute([$id])) {
                $errorInfo = $stmt->errorInfo();
                throw new Exception("刪除失敗 ($table): " . $errorInfo[2]);
            }
        }
    }
    
    // 處理新增
    foreach ($inserts as $table => $rows) {
        $pk = $tablePrimaryKeys[$table] ?? null;
        if (!$pk) continue;
        
        foreach ($rows as $row) {
            if (empty($row)) continue;
            
            $columns = [];
            $values = [];
            $params = [];
            
            foreach ($row as $col => $val) {
                // 跳過自動增長的主鍵和空值主鍵
                if ($col === $pk && (empty($val) || $val === '')) continue;
                
                $columns[] = "`$col`";
                $values[] = "?";
                $params[] = processValue($table, $col, $val);
            }
            
            if (!empty($columns)) {
                $sql = "INSERT INTO `$table` (" . implode(',', $columns) . ") VALUES (" . implode(',', $values) . ")";
                $stmt = $pdo->prepare($sql);
                if (!$stmt->execute($params)) {
                    $errorInfo = $stmt->errorInfo();
                    throw new Exception("新增失敗 ($table): " . $errorInfo[2]);
                }
            }
        }
    }
    
    // 處理更新
    foreach ($updates as $table => $rows) {
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
                if ($col !== $pk) { // 排除主鍵欄位不進行更新
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
                throw new Exception("更新失敗 ($table): " . $errorInfo[2]);
            }
        }
    }
    
    $pdo->commit();
    echo json_encode(['status' => 'success', 'message' => '所有資料已更新完成']);

} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    error_log("更新錯誤: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>