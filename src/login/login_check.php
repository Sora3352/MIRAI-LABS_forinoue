<?php
session_start();
require_once("../../asset/php/db_connect.php");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// ユーザー検索
$sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = "メールアドレス または パスワードが違います。";
    header("Location: login_input.php");
    exit;
}

// ここで user情報をSESSIONに登録！
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_last_name'] = $user['last_name'];
$_SESSION['user_first_name'] = $user['first_name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_name'] = $user['last_name'] . " " . $user['first_name'];

// マイページトップへ移動
header("Location: ../mypage/mypage_top.php");
exit;
