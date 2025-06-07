<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => '未登入']);
    exit();
}

$uploadDir = __DIR__ . '/../../pics/'; // pics 資料夾路徑

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => '只允許 POST 請求']);
    exit();
}

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => '上傳失敗']);
    exit();
}

$professorId = $_POST['professor_id'] ?? null;
if (!$professorId) {
    echo json_encode(['status' => 'error', 'message' => '缺少教授編號']);
    exit();
}

// 取得副檔名
$ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
$allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
if (!in_array($ext, $allowedExts)) {
    echo json_encode(['status' => 'error', 'message' => '只允許 JPG, PNG, GIF 檔案']);
    exit();
}

// 檔案儲存路徑
$savePath = $uploadDir . $professorId . '.' . $ext;

// 搬移檔案
if (move_uploaded_file($_FILES['photo']['tmp_name'], $savePath)) {
    echo json_encode([
        'status' => 'success',
        'message' => '照片已儲存到 pics 資料夾'
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => '檔案儲存失敗']);
}
?>
