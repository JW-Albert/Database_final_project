<?php
session_start();
if (!isset($_SESSION['logged_in'])) { http_response_code(403); exit; }
require_once '../db_config/main.php';

$data = json_decode(file_get_contents('php://input'), true);

// 各表主鍵名稱（請依實際資料庫調整）
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

foreach ($data as $table => $rows) {
    $pk = $tablePrimaryKeys[$table] ?? null;
    if (!$pk) continue;
    foreach ($rows as $row) {
        if (!isset($row[$pk])) continue;
        $set = [];
        $params = [];
        foreach ($row as $col => $val) {
            if ($col !== $pk) {
                $set[] = "$col = ?";
                $params[] = $val;
            }
        }
        if (empty($set)) continue;
        $params[] = $row[$pk];
        $sql = "UPDATE $table SET " . implode(',', $set) . " WHERE $pk = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }
}
echo json_encode(['status'=>'success','message'=>'所有資料已更新']);