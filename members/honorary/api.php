<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../../php/config.php';

try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT name, photo, type, email, extension, bio FROM Professor WHERE type = '榮譽特聘講座'");
    $stmt->execute();
    $honorary = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'success' => true,
        'honorary' => $honorary
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => '資料庫錯誤：' . $e->getMessage()
    ]);
} 