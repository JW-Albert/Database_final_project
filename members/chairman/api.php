<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../../php/config.php';

try {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT d.professor_id, d.term, p.name, p.extension, p.email, p.type, p.photo FROM DepartmentHead d JOIN Professor p ON d.professor_id = p.professor_id ORDER BY d.term DESC");
    $heads = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'success' => true,
        'heads' => $heads
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => '資料庫錯誤：' . $e->getMessage()
    ]);
} 