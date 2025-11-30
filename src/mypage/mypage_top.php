<?php
session_start();

// ▼ 未ログインならログインページへ強制リダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login_input.php");
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/E-mart/components/header.php");

// ログインユーザー名取得
$user_name = $_SESSION['user_name'] ?? "ゲスト";
?>

<link rel="stylesheet" href="/E-mart/asset/css/mypage.css">

<div class="mypage-container">

    <h1 class="mypage-title">マイページ</h1>

    <div class="mypage-grid">

        <!-- 左：ご登録情報 -->
        <div class="mypage-box">
            <div class="mypage-box-header">
                <div class="title-left">
                    <i class="fa-solid fa-user"></i> ご登録情報
                </div>
                <a href="mypage_info.php" class="btn-small">すべて見る</a>
            </div>

            <div class="mypage-box-body">
                <p><strong>登録名：</strong> <?= htmlspecialchars($user_name) ?></p>
                <p><strong>アドレス：</strong><?= htmlspecialchars($_SESSION['user_email']) ?></p>

                <div class="mypage-links">
                    <a href="mypage_info.php"><i class="fa-solid fa-id-card"></i> お客様情報</a>
                    <a href="mypage_address.php"><i class="fa-solid fa-truck"></i> お届け先情報</a>
                    <a href="mypage_contact.php"><i class="fa-solid fa-headphones"></i> お問い合わせ履歴</a>
                </div>
            </div>
        </div>

        <!-- 右：購入履歴 -->
        <div class="mypage-box">
            <div class="mypage-box-header">
                <div class="title-left">
                    <i class="fa-solid fa-cart-shopping"></i> ご購入履歴
                </div>
                <a href="../order/order_history.php" class="btn-small">すべて見る</a>
            </div>

            <div class="mypage-box-body">
                <p>まだ購入履歴はありません</p>
            </div>
        </div>
        <!-- ▼ お問い合わせ履歴 -->
        <div class="mypage-box">
            <div class="mypage-box-header">
                <div class="title-left">
                    <i class="fa-solid fa-headset"></i> お問い合わせ履歴
                </div>
                <a href="/E-mart/src/contact/contact_history.php" class="btn-small">一覧を見る</a>
            </div>

            <div class="mypage-box-body">
                <p>過去のお問い合わせ内容を確認できます。</p>

                <div class="mypage-links">
                    <a href="/E-mart/src/contact/contact_history.php">
                        <i class="fa-solid fa-comments"></i> お問い合わせ履歴を開く
                    </a>

                    <a href="/E-mart/src/contact/contact_form.php">
                        <i class="fa-solid fa-envelope-circle-check"></i> 新しいお問い合わせ
                    </a>
                </div>
            </div>
        </div>


    </div>

    <!-- 下：お得情報 -->
    <div class="mypage-campaign">
        <p class="campaign-title">全品１０％OFF などお得な情報をお知らせ！！</p>

        <a href="#" class="campaign-btn">
            <i class="fa-solid fa-envelope"></i> メールを受け取る
        </a>
    </div>

</div>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/E-mart/components/footer.php"); ?>