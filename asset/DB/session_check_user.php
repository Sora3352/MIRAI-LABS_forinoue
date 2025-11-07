<?php
session_start();

// ===== 未ログイン時はログインページへ =====
if (!isset($_SESSION['username'])) {
    header('Location: ../../src/login/login.php');
    exit();
}

// ===== ロールを保持しているかチェック =====
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'user'; // 未設定時は一般ユーザーとして扱う
}
?>
