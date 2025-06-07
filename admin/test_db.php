<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/db_config/main.php'; // 根據實際路徑調整

try {
    // 最簡查詢，抓目前時間
    $stmt = $pdo->query("SELECT NOW() AS now_time");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ 成功連上資料庫，目前時間是：" . $row['now_time'];
} catch (PDOException $e) {
    echo "❌ 資料庫連線失敗：" . $e->getMessage();
}
?>
