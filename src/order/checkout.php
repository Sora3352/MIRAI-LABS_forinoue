<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

// ログイン必須
if (!isset($_SESSION['user_id'])) {
    // 元の場所に戻るためのリダイレクトURL
    $_SESSION['redirect_after_login'] = "/E-mart/src/order/checkout.php";
    header("Location: /E-mart/src/login/login_input.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

/* ===============================
   1. ユーザー情報を取得
================================= */
$sql_user = "SELECT * FROM users WHERE id = :id LIMIT 1";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->execute([':id' => $user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("ユーザー情報が取得できません");
}


/* ===============================
   1.5 デフォルト住所を取得
================================= */
$sql_addr = "
    SELECT *
    FROM addresses
    WHERE user_id = :user_id
      AND is_default = 1
    LIMIT 1
";

$stmt_addr = $pdo->prepare($sql_addr);
$stmt_addr->execute([':user_id' => $user_id]);
$address = $stmt_addr->fetch(PDO::FETCH_ASSOC);

if (!$address) {
    die("配送先住所が登録されていません。マイページから住所を登録してください。");
}


/* ===============================
   2. カート内容を取得
================================= */
$sql_cart = "
    SELECT 
        ci.quantity,
        p.id AS product_id,
        p.name,
        p.price,
        p.stock,
        p.image_url,
        c.name AS category_name
    FROM cart_items ci
    INNER JOIN products p ON ci.product_id = p.id
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE ci.user_id = :user_id
";
$stmt_cart = $pdo->prepare($sql_cart);
$stmt_cart->execute([':user_id' => $user_id]);
$items = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

if (!$items) {
    header("Location: /E-mart/src/cart/cart.php");
    exit;
}

/* ===============================
   3. 合計金額
================================= */
$subtotal = 0;
foreach ($items as $it) {
    $subtotal += $it['price'] * $it['quantity'];
}

/* ===============================
   4. Amazon風の配送日（簡易版）
================================= */
$now = new DateTime('now');
$cutoff = new DateTime('today 18:00');
$delivery = ($now <= $cutoff)
    ? new DateTime('tomorrow')
    : new DateTime('tomorrow +1 day');
$delivery_date = $delivery->format('n月j日');

// ヘッダー
include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/header.php');
?>

<link rel="stylesheet" href="/E-mart/asset/css/order_checkout.css">

<main class="checkout-wrapper">

    <h1 class="checkout-title">注文内容を確認</h1>

    <div class="checkout-layout">

        <!-- =====================================
             左カラム：配送先・支払い方法・商品一覧
        ====================================== -->
        <section class="checkout-left">

            <!-- 配送先（Amazon風） -->
            <div class="checkout-box">
                <h2 class="checkout-box-title">お届け先</h2>
                <p class="checkout-user-name">
                    <?= htmlspecialchars($user['last_name'] . " " . $user['first_name']) ?> 様
                </p>
                <p class="checkout-address">
                    〒<?= htmlspecialchars($address['postal_code']) ?><br>
                    <?= htmlspecialchars($address['prefecture']) ?>
                    <?= htmlspecialchars($address['city']) ?>
                    <?= htmlspecialchars($address['street']) ?><br>
                    <?= htmlspecialchars($address['building']) ?>
                </p>

                <p class="checkout-email">
                    メール: <?= htmlspecialchars($user['email']) ?>
                </p>
            </div>

            <!-- 支払い方法 -->
            <div class="checkout-box">
                <h2 class="checkout-box-title">お支払い方法</h2>
                <p class="checkout-pay">代引き（商品お届け時にお支払い）</p>
            </div>

            <!-- 配送予定 -->
            <div class="checkout-box">
                <h2 class="checkout-box-title">配送</h2>
                <p class="checkout-delivery">ヤマト運輸・E-mart 配送<br>
                    <strong><?= $delivery_date ?></strong> にお届け予定
                </p>
            </div>

            <!-- 商品一覧（Amazon風） -->
            <div class="checkout-box">
                <h2 class="checkout-box-title">商品</h2>

                <?php foreach ($items as $it): ?>
                    <div class="checkout-item">
                        <div class="checkout-item-img">
                            <img
                                src="/E-mart/asset/img/products/<?= htmlspecialchars($it['category_name']) ?>/<?= htmlspecialchars($it['image_url']) ?>">
                        </div>
                        <div class="checkout-item-info">
                            <div class="item-name"><?= htmlspecialchars($it['name']) ?></div>
                            <div class="item-price">￥<?= number_format($it['price']) ?></div>
                            <div class="item-qty">数量：<?= $it['quantity'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

        </section>

        <!-- =====================================
             右カラム：小計 + 注文確定ボタン
        ====================================== -->
        <aside class="checkout-right">
            <div class="summary-box">
                <div class="summary-row">
                    小計（<?= count($items) ?>点）：
                    <span class="summary-price">￥<?= number_format($subtotal) ?></span>
                </div>

                <form action="/E-mart/src/order/order_create.php" method="post">
                    <button class="checkout-btn">注文を確定する</button>
                </form>

                <p class="summary-note">
                    ※注文確定後の変更・キャンセルはできません。
                </p>
            </div>
        </aside>

    </div>
</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/footer.php'); ?>