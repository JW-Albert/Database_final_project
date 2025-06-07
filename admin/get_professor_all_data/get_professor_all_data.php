<?php
session_start();
if (!isset($_SESSION['logged_in'])) { http_response_code(403); exit; }
require_once '../db_config/main.php';

$id = $_GET['id'] ?? '';
if (!$id) {
    http_response_code(400);
    exit('缺少教授編號');
}

$result = [];

// 主表（排除 photo 欄位）
$stmt = $pdo->prepare("SELECT professor_id, name, bio, extension, email, password, type, website_url, retire FROM Professor WHERE professor_id = ?");
$stmt->execute([$id]);
$result['Professor'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 相關表（請依你的資料庫實際表格補齊）
$relatedTables = [
    'Course', 'DepartmentHead', 'Education', 'Expertise', 'ExternalAward',
    'ExternalExperience', 'IndustryProject', 'InternalAward', 'InternalExperience',
    'JournalPublication', 'Lecture', 'NSCProject', 'Patent'
];

foreach ($relatedTables as $table) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE professor_id = ?");
        $stmt->execute([$id]);
        $result[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $result[$table] = ['error' => $e->getMessage()];
    }
}
header('Content-Type: application/json');
echo json_encode($result);