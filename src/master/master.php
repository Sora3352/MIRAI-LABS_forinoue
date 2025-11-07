<?php
require_once('../../asset/DB/session_check.php'); // ログイン＆管理者チェック
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理者メニュー</title>
    <link rel="stylesheet" href="../../asset/css/style.css">
    <link rel="stylesheet" href="../../asset/css/header.css">
</head>
<body>

<?php include('../header/header.php'); ?>

<main class="admin-main">
    <h1>管理者メニュー</h1>
    <div class="admin-menu">
        <a href="product_manage.php" class="admin-btn">🧾 商品管理</a>
        <a href="order_list.php" class="admin-btn">📦 注文一覧</a>
        <a href="user_manage.php" class="admin-btn">👤 ユーザー管理</a>
        <a href="logout.php" class="admin-btn logout">🚪 ログアウト</a>
    </div>
</main>

</body>
</html>
