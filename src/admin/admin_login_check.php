<?php
session_start();
require_once("../../asset/php/db_connect.php");

$email = trim($_POST['email'] ?? "");
$password = trim($_POST['password'] ?? "");

if ($email === "" || $password === "") {
    $_SESSION['admin_login_error'] = "メールアドレスとパスワードを入力してください。";
    header("Location: admin_login.php");
    exit;
}

// DBから管理者検索
$sql = "SELECT * FROM admins WHERE email = :email LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":email", $email);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin || !password_verify($password, $admin['password'])) {
    $_SESSION['admin_login_error'] = "メールアドレス または パスワードが違います。";
    header("Location: admin_login.php");
    exit;
}

// 一般ユーザーセッションを消す（安全対策）
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_email']);

// 管理者セッション発行
$_SESSION['admin_id'] = $admin['id'];
$_SESSION['admin_name'] = $admin['name'];

header("Location: admin_menu.php");
exit;
