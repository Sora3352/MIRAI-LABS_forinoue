<?php
session_start();
require_once("../../asset/php/db_connect.php");

// 管理者ログインチェック
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$password_confirm = trim($_POST['password_confirm']);

if ($name === "" || $email === "" || $password === "") {
    $_SESSION['admin_add_error'] = "すべての項目を入力してください。";
    header("Location: admin_add.php");
    exit;
}

if ($password !== $password_confirm) {
    $_SESSION['admin_add_error'] = "パスワードが一致しません。";
    header("Location: admin_add.php");
    exit;
}

// 重複確認
$stmt = $pdo->prepare("SELECT id FROM admins WHERE email = :email");
$stmt->bindValue(':email', $email);
$stmt->execute();

if ($stmt->fetch()) {
    $_SESSION['admin_add_error'] = "同じメールアドレスの管理者が存在します。";
    header("Location: admin_add.php");
    exit;
}

// 登録
$hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (name, email, password) VALUES (:name, :email, :password)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name);
$stmt->bindValue(':email', $email);
$stmt->bindValue(':password', $hash);
$stmt->execute();

header("Location: admin_list.php");
exit;
