<?php
session_start();
if (!isset($_SESSION['logged_in'])) { 
    http_response_code(403); 
    echo json_encode(['error' => '未授權存取']);
    exit; 
}

try {
    require_once '../db_config/main.php';
    
    $table = $_GET['table'] ?? '';
    if (empty($table)) {
        throw new Exception("未指定資料表");
    }
    
    // 驗證表格名稱是否在允許的列表中
    $allowedTables = [
        'Professor', 'Course', 'DepartmentHead', 'Education', 'Expertise',
        'ExternalAward', 'ExternalExperience', 'IndustryProject', 
        'InternalAward', 'InternalExperience', 'JournalPublication',
        'Lecture', 'NSCProject', 'Patent'
    ];
    
    if (!in_array($table, $allowedTables)) {
        throw new Exception("無效的資料表名稱");
    }
    
    $stmt = $pdo->prepare("SHOW FULL COLUMNS FROM `$table`");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($columns);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>