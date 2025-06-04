<?php
// db_config/main.php

$host = '140.134.53.57';
$dbname = 'D1249429';
$user = 'D1249429';
$pass = '#XKrP3CRn';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 用 JSON 格式回傳錯誤
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => '資料庫連線失敗：' . $e->getMessage()]);
    exit;
}
?>
