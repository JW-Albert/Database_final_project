<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../db_config/main.php';


session_start(); // 啟用 session


$input_username = $_POST['username'] ?? '';
$input_password = $_POST['password'] ?? '';

try {
    $stmt = $pdo->prepare("SELECT * FROM User WHERE username = ? AND password = ?");
    $stmt->execute([$input_username, $input_password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // 登入成功，儲存登入資訊到 session
        $_SESSION['user_id'] = $user['id'];   
        $_SESSION['username'] = $user['username'];
        $_SESSION['logged_in'] = true; 

        header("Location: ../home_page.php");


        exit();
    } else {
        $error_msg = urlencode("帳號或密碼錯誤，請重新輸入！");
        header("Location: login.html?error=$error_msg");
        exit();
    }
} catch (PDOException $e) {
    $error_msg = urlencode("資料庫錯誤：" . $e->getMessage());
    header("Location: login.html?error=$error_msg");
    exit();
}
?>
