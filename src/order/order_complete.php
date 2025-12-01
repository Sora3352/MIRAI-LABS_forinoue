<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

// 注文IDがない場合は一覧に戻す
if (!isset($_GET['order_id'])) {
    header("Location: /E-mart/src/product/list.php");
    exit;
}

$order_id = (int) $_GET['order_id'];

// ログイン必須
if (!isset($_SESSION['user_id'])) {
    header("Location: /E-mart/src/login/login_input.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

/* =============================
   注文情報を取得
============================= */
$sql = "
    SELECT 
        o.id,
        o.total_price,
        o.created_at,
        a.recipient_name,
        a.postal_code,
        a.prefecture,
        a.city,
        a.street,
        a.building
    FROM orders o
    LEFT JOIN addresses a ON o.address_id = a.address_id
    WHERE o.id = :order_id AND o.user_id = :user_id
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':order_id' => $order_id,
    ':user_id' => $user_id
]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("注文情報が見つかりません。");
}

// ヘッダー
include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/header.php');
?>

<link rel="stylesheet" href="/E-mart/asset/css/order_complete.css">

<main class="complete-wrapper">
    <div class="complete-box">

        <div class="complete-icon">✔</div>
        <h1 class="complete-title">ご注文が確定しました</h1>

        <p class="complete-message">
            ご注文いただきありがとうございます。<br>
            ご注文番号：<strong>#<?= htmlspecialchars($order['id']) ?></strong>
        </p>

        <div class="complete-section">
            <h2>お届け先</h2>
            <p>
                <?= htmlspecialchars($order['recipient_name']) ?> 様<br>
                〒<?= htmlspecialchars($order['postal_code']) ?><br>
                <?= htmlspecialchars($order['prefecture']) ?>
                <?= htmlspecialchars($order['city']) ?>
                <?= htmlspecialchars($order['street']) ?>
                <?= htmlspecialchars($order['building']) ?>
            </p>
        </div>

        <div class="complete-section">
            <h2>お支払い方法</h2>
            <p>代引き（商品お届け時にお支払い）</p>
        </div>

        <div class="complete-section">
            <h2>ご請求金額</h2>
            <p class="complete-price">￥<?= number_format($order['total_price']) ?></p>
        </div>

        <a class="complete-btn" href="/E-mart/src/order/order_history.php">注文履歴を見る</a>
        <a class="complete-btn secondary" href="/E-mart/src/product/list.php">買い物を続ける</a>

    </div>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/footer.php'); ?>