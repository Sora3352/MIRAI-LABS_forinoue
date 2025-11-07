<?php
session_start();

// ===== ログインチェック =====
if (!isset($_SESSION['username'])) {
    header('Location: ../../src/login/login.php');
    exit();
}

// ===== 管理者権限チェック =====
if ($_SESSION['role'] !== 'admin') {
    echo '<!DOCTYPE html><html lang="ja"><head><meta charset="UTF-8"><title>アクセス拒否</title>';
    echo '<style>body{font-family:Arial;text-align:center;margin-top:80px;color:#333;}';
    echo 'a{color:#007BFF;text-decoration:none;}</style></head><body>';
    echo '<h2>⚠️ アクセス権限がありません。</h2>';
    echo '<p>このページは管理者のみがアクセス可能です。</p>';
    echo '<p><a href="../../src/home/index.php">ホームに戻る</a></p>';
    echo '</body></html>';
    exit();
}
?>