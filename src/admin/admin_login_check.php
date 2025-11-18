<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($email === '' || $password === '') {
    $_SESSION['admin_login_error'] = "メールアドレスとパスワードを入力してください。";
    header("Location: admin_login.php");
    exit;
}

try {
    // 管理者検索
    $sql = "SELECT * FROM admins WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        $_SESSION['admin_login_error'] = "メールアドレスまたはパスワードが違います。";
        header("Location: admin_login.php");
        exit;
    }

    // パスワードチェック
    if (!password_verify($password, $admin['password'])) {
        $_SESSION['admin_login_error'] = "メールアドレスまたはパスワードが違います。";
        header("Location: admin_login.php");
        exit;
    }

    // ===== ログイン成功 =====
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];

    header("Location: admin_menu.php");  // ダッシュボードへ
    exit;

} catch (Exception $e) {
    $_SESSION['admin_login_error'] = "エラーが発生しました。";
    header("Location: admin_login.php");
    exit;
}
