<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');

// 資料庫連接設定
$host = 'localhost';
$dbname = 'D1249429';
$user = 'D1249429';
$pass = '#XKrP3CRn'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $input = json_decode(file_get_contents('php://input'), true);
    $table = $input['table'];
    $primaryKey = $input['primaryKey'];
    $relatedTables = $input['relatedTables'];

    $references = [];
    $hasReferences = false;

    // 檢查每個相關表格是否有外鍵參考
    foreach ($relatedTables as $relatedTable) {
        $sql = "SELECT COUNT(*) as count FROM `$relatedTable` WHERE professor_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$primaryKey]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            $hasReferences = true;
            $references[] = [
                'table' => $relatedTable,
                'count' => $result['count']
            ];
        }
    }

    echo json_encode([
        'hasReferences' => $hasReferences,
        'references' => $references
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>