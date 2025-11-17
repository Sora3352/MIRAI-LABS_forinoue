<?php
// ===========================================
// 階層を自動判定してプロジェクトルートを計算
// ===========================================

// header.php の絶対パス
$header_path = __FILE__;

// プロジェクトフォルダ名（← 井上くんの環境に合わせて固定）
$project_root = "E-mart";

// パスを分解
$path_parts = explode("/", str_replace("\\", "/", $header_path));

// E-mart がどこにあるか探す
$root_index = array_search($project_root, $path_parts);

// Webルートを作成（/E-mart/）
$WEB_ROOT = "/" . $project_root . "/";

// ===========================================
// ログイン状態チェック
// ===========================================
session_start();
$logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? "";
?>

<link rel="stylesheet" href="/E-mart/asset/css/header.css">
<script src="/E-mart/asset/js/main.js" defer></script>

<header class="emart-header">

    <!-- ====== 上段 ====== -->
    <div class="header-top">

        <div class="header-left">
            <a href="/E-mart/src/index.php" class="logo-link">
                <img src="/E-mart/asset/img/logo.png" class="emart-logo" alt="E-mart">
            </a>

            <img src="/E-mart/asset/img/header_item_count.png" class="item-count-img" alt="取扱点数">
        </div>

        <div class="header-center">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="/E-mart/src/login/login_input.php" class="btn login-btn">ログイン</a>
            <?php else: ?>
                <a href="/E-mart/src/login/logout.php" class="btn logout-btn">ログアウト</a>
            <?php endif; ?>

            <a href="/E-mart/src/register/register_input.php" class="btn signup-btn">新規登録</a>

            <a href="/E-mart/src/mypage/mypage_top.php" class="mypage-link">マイページ ▼</a>
            <span>|</span>
            <a href="/E-mart/src/order/order_history.php" class="mypage-link">購入履歴</a>

            <a href="/E-mart/src/cart/cart_view.php" class="cart-link">
                <img src="/E-mart/asset/img/cart.jpg" class="cart-icon" alt="">
                カート
            </a>

            <a href="/E-mart/src/contact/contact_form.php" class="info-btn">WEB お問い合わせ<br>フォーム</a>
            <a href="/E-mart/src/contact/contact_form.php" class="qa-btn">よくある質問<br>Q&A</a>
        </div>

    </div>


    <!-- ====== 下段 ====== -->
    <div class="header-bottom">

        <a class="quick-order-btn">クイックオーダー<br>(品番注文)</a>

        <select class="filter-select">
            <option>絞り込み ▽</option>
        </select>

        <input type="text" class="search-box" placeholder="商品名、キーワード、注文コード、使用用途">

        <button class="search-btn">検索</button>

    </div>

</header>