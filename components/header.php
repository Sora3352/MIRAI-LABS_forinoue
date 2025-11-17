<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="emart-header">
    <div class="header-left">

        <!-- ロゴ（後で画像差し替え） -->
        <a href="/MIRAI-LABS_forinoue/src/index.php" class="logo-link">
            <img src="/MIRAI-LABS_forinoue/asset/img/logo.png" alt="E-mart" class="emart-logo">
        </a>

        <!-- 取扱点数の画像（後で差し替え） -->
        <img src="/MIRAI-LABS_forinoue/asset/img/header_item_count.png" alt="取扱点数" class="item-count-img">
    </div>

    <div class="header-center">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="/MIRAI-LABS_forinoue/src/login/login_input.php" class="btn login-btn">ログイン</a>
        <?php else: ?>
            <a href="/MIRAI-LABS_forinoue/src/login/logout.php" class="btn logout-btn">ログアウト</a>
        <?php endif; ?>

        <a href="/MIRAI-LABS_forinoue/src/register/register_input.php" class="btn signup-btn">新規登録</a>

        <div class="mypage-area">
            <a href="/MIRAI-LABS_forinoue/src/mypage/mypage_top.php" class="mypage-link">マイページ ▼</a>
            <span>|</span>
            <a href="/MIRAI-LABS_forinoue/src/order/order_history.php" class="mypage-link">購入履歴</a>
        </div>
    </div>

    <div class="header-right">
        <a href="/MIRAI-LABS_forinoue/src/cart/cart_view.php" class="cart-link">
            <img src="/MIRAI-LABS_forinoue/asset/img/cart_icon.png" class="cart-icon">
            カート
        </a>

        <a href="/MIRAI-LABS_forinoue/src/contact/contact_form.php" class="info-btn">WEB お問い合わせフォーム</a>
        <a href="/MIRAI-LABS_forinoue/src/contact/contact_form.php" class="qa-btn">よくある質問<br>Q&A</a>
    </div>
</header>

<link rel="stylesheet" href="/MIRAI-LABS_forinoue/asset/css/header.css">