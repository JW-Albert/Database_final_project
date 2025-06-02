<?php
$env = parse_ini_file(__DIR__ . '/passwd.env');
$valid_username = $env['USERNAME'];
$valid_password = $env['PASSWORD'];

$input_username = $_POST['username'] ?? '';
$input_password = $_POST['password'] ?? '';

if ($input_username === $valid_username && $input_password === $valid_password) {
    // 登入成功跳轉首頁
    header("Location: ../index.html");
    exit();
} else {
    // 登入失敗
    $error_msg = urlencode("帳號或密碼錯誤，請重新輸入！");
    header("Location: login.html?error=$error_msg");
    exit();
}
?>
