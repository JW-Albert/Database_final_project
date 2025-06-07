<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../../php/config.php';

try {
    $pdo = getDBConnection();
    // 取得所有行政人員
    $stmt = $pdo->query("SELECT staff_id, name, position, extension, email, photo, task_name FROM Staff");
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'staff' => $staff
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => '資料庫錯誤：' . $e->getMessage()
    ]);
} 