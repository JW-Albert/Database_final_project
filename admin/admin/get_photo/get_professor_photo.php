<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(403);
    exit("未登入");
}
require_once __DIR__ . '/../db_config/main.php';

$professorId = $_GET['id'] ?? null;
if (!$professorId) {
    http_response_code(400);
    exit("缺少 ID");
}

$stmt = $pdo->prepare("SELECT photo FROM Professor WHERE professor_id = ?");
$stmt->execute([$professorId]);
$row = $stmt->fetch();

if ($row && $row['photo']) {
    // 嘗試判斷圖片格式
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->buffer($row['photo']);
    header("Content-Type: " . ($mime ?: "image/jpeg"));
    echo $row['photo'];
} else {
    http_response_code(404);
    exit("找不到照片");
}
