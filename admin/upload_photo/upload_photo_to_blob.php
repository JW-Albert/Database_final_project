<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => '未登入']);
    exit();
}

require_once __DIR__ . '/../db_config/main.php'; // 根據你實際的資料庫設定路徑調整

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

// 讀取圖片內容並轉為 binary
$imageData = file_get_contents($_FILES['photo']['tmp_name']);

try {
    $stmt = $pdo->prepare("UPDATE Professor SET photo = :photo WHERE professor_id = :id");
    $stmt->bindParam(':photo', $imageData, PDO::PARAM_LOB);
    $stmt->bindParam(':id', $professorId);
    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'message' => '照片已儲存在資料庫中'
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
