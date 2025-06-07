<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../../php/config.php';

try {
    $pdo = getDBConnection();

    // 取得所有教授
    $stmt = $pdo->query("SELECT professor_id, name, type, email, photo FROM Professor WHERE type = '榮譽特聘教授'");
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

    echo json_encode([
        'success' => true,
        'professors' => $professors,
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => '資料庫錯誤：' . $e->getMessage()
    ]);
} 