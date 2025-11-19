<?php
session_start();

// 管理者ログインチェック
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/admin_breadcrumb_auto.php');

// 管理者名
$admin_name = $_SESSION['admin_name'];
?>

<link rel="stylesheet" href="/E-mart/asset/css/admin_menu.css">


<div class="admin-menu-wrapper">

    <h1 class="admin-menu-title">管理者メニュー</h1>
    <p class="admin-welcome">ようこそ、<?= htmlspecialchars($admin_name) ?> さん</p>

    <div class="admin-menu-grid">

        <!-- 管理者一覧 -->
        <a href="admin_list.php" class="admin-card">
            <div class="admin-card-icon">👤</div>
            <div class="admin-card-title">管理者一覧</div>
            <div class="admin-card-desc">管理者アカウントの確認</div>
        </a>

        <!-- 管理者追加 -->
        <a href="admin_add.php" class="admin-card">
            <div class="admin-card-icon">➕</div>
            <div class="admin-card-title">管理者の追加</div>
            <div class="admin-card-desc">新しい管理者を登録</div>
        </a>

        <!-- 商品管理（products フォルダ） -->
        <a href="products/list.php" class="admin-card">
            <div class="admin-card-icon">📦</div>
            <div class="admin-card-title">商品管理</div>
            <div class="admin-card-desc">登録済み商品の編集</div>
        </a>
        <!-- セール管理（sale フォルダ） -->
        <a href="sale/sale_list.php" class="admin-card">
            <div class="admin-card-icon">🏷️</div>
            <div class="admin-card-title">セール管理</div>
            <div class="admin-card-desc">セール商品の編集</div>
        </a>

        <!-- トップピックス管理（top_picks_manage.php） -->
        <a href="top_picks_manage.php" class="admin-card">
            <div class="admin-card-icon">⭐</div>
            <div class="admin-card-title">トップピックス管理</div>
            <div class="admin-card-desc">注目商品の編集</div>
        </a>

        <!-- カテゴリナビ管理（category_nav フォルダ） -->
        <a href="category_nav/list.php" class="admin-card">
            <div class="admin-card-icon">📚</div>
            <div class="admin-card-title">カテゴリナビ管理</div>
            <div class="admin-card-desc">カテゴリナビの編集</div>
        </a>

        <!-- ニュース管理（news フォルダ） -->
        <a href="news/news_manage.php" class="admin-card">
            <div class="admin-card-icon">📰</div>
            <div class="admin-card-title">ニュース管理</div>
            <div class="admin-card-desc">お知らせの追加・編集</div>
        </a>

        <!-- ユーザー一覧（users フォルダ） -->
        <a href="users/user_list.php" class="admin-card">
            <div class="admin-card-icon">👥</div>
            <div class="admin-card-title">ユーザー一覧</div>
            <div class="admin-card-desc">一般ユーザーの詳細</div>
        </a>

        <!-- ログアウト -->
        <a href="admin_logout.php" class="admin-card logout-card">
            <div class="admin-card-icon">🚪</div>
            <div class="admin-card-title">ログアウト</div>
            <div class="admin-card-desc">管理者セッションを終了</div>
        </a>

    </div>

</div>