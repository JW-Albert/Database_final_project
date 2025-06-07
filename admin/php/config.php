<?php
// 資料庫連接設定
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'D1249429');
define('DB_PASSWORD', '#XKrP3CRn');
define('DB_NAME', 'D1249429');

// 建立資料庫連接
function getDBConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME,
            DB_USERNAME,
            DB_PASSWORD
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("SET NAMES utf8");
        return $conn;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?> 