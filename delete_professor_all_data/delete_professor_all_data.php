<?php
session_start();
if (!isset($_SESSION['logged_in'])) { http_response_code(403); exit; }
require_once 'db_config/main.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['professor_id'] ?? '';
if (!$id) {
    echo json_encode(['status'=>'error','message'=>'缺少教授編號']);
    exit();
}

// 相關表（請依你的資料庫實際表格補齊）
$relatedTables = [
    'Course', 'DepartmentHead', 'Education', 'Expertise', 'ExternalAward',
    'ExternalExperience', 'IndustryProject', 'InternalAward', 'InternalExperience',
    'JournalPublication', 'Lecture', 'NSCProject', 'Patent'
];

try {
    foreach ($relatedTables as $table) {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE professor_id = ?");
        $stmt->execute([$id]);
    }
    // 最後刪除 Professor 本身
    $stmt = $pdo->prepare("DELETE FROM Professor WHERE professor_id = ?");
    $stmt->execute([$id]);
    echo json_encode(['status'=>'success']);
} catch (PDOException $e) {
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}