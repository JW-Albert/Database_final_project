<?php
$host = 'localhost';
$dbname = 'D1249429';
$user = 'D1249429';
$pass = '#XKrP3CRn'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => '資料庫連線失敗：' . $e->getMessage()
    ]);
    exit;
}
?>
