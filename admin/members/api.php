<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../php/config.php';

try {
    $pdo = getDBConnection();

    // 取得所有教授
    $stmt = $pdo->query("SELECT professor_id, name, type, email, photo FROM Professor");
    $professors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 取得所有專長
    $expertise_stmt = $pdo->query("SELECT professor_id, name_cn FROM Expertise");
    $expertise_map = [];
    foreach ($expertise_stmt as $row) {
        $pid = $row['professor_id'];
        if (!isset($expertise_map[$pid])) $expertise_map[$pid] = [];
        $expertise_map[$pid][] = $row['name_cn'];
    }
    // 合併專長到教授
    foreach ($professors as &$prof) {
        $pid = $prof['professor_id'];
        $prof['expertise'] = $expertise_map[$pid] ?? [];
    }

    // 取得所有行政人員
    $stmt = $pdo->query("SELECT staff_id, name, position, extension, email, photo, task_name FROM Staff");
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'professors' => $professors,
        'staff' => $staff
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => '資料庫錯誤：' . $e->getMessage()
    ]);
} 