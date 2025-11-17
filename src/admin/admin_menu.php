<?php
session_start();

// 管理者ログインチェック
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include_once("../../components/header.php");

// 管理者名
$admin_name = $_SESSION['admin_name'];
?>

<link rel="stylesheet" href="/E-mart/asset/css/admin_menu.css">
<?php include("../../components/admin_breadcrumb_auto.php"); ?>


<div class="admin-menu-wrapper">

    <h1 class="admin-menu-title">管理者メニュー</h1>
    <p class="admin-welcome">ようこそ、<?= htmlspecialchars($admin_name) ?> さん</p>

    <div class="admin-menu-grid">

        <a href="admin_list.php" class="admin-card">
            <div class="admin-card-icon">👤</div>
            <div class="admin-card-title">管理者一覧</div>
            <div class="admin-card-desc">管理者アカウントの確認</div>
        </a>

        <a href="admin_add.php" class="admin-card">
            <div class="admin-card-icon">➕</div>
            <div class="admin-card-title">管理者の追加</div>
            <div class="admin-card-desc">新しい管理者を登録</div>
        </a>

        <a href="products/product_list.php" class="admin-card">
            <div class="admin-card-icon">📦</div>
            <div class="admin-card-title">商品管理</div>
            <div class="admin-card-desc">登録済み商品の編集</div>
        </a>

        <a href="../news/news_manage.php" class="admin-card">
            <div class="admin-card-icon">📰</div>
            <div class="admin-card-title">ニュース管理</div>
            <div class="admin-card-desc">お知らせの追加・編集</div>
        </a>

        <a href="../user/user_list.php" class="admin-card">
            <div class="admin-card-icon">👥</div>
            <div class="admin-card-title">ユーザー一覧</div>
            <div class="admin-card-desc">一般ユーザーの詳細</div>
        </a>

        <a href="admin_logout.php" class="admin-card logout-card">
            <div class="admin-card-icon">🚪</div>
            <div class="admin-card-title">ログアウト</div>
            <div class="admin-card-desc">管理者セッションを終了</div>
        </a>

    </div>

</div>

<?php include_once("../../components/footer.php"); ?>