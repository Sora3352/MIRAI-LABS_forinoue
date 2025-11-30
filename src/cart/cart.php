<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

// ログイン必須
if (!isset($_SESSION['user_id'])) {
    header("Location: /E-mart/src/login/login_input.php");
    exit;
}
$user_id = (int) $_SESSION['user_id'];

// カート取得（商品情報＋カテゴリ名）
$sql = "
    SELECT 
        ci.id AS cart_item_id,
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
    ORDER BY ci.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 合計計算
$subtotal = 0;
foreach ($items as $it) {
    $subtotal += ((int) $it['price']) * ((int) $it['quantity']);
}

// header
include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/header.php');
?>
<link rel="stylesheet" href="/E-mart/asset/css/cart.css">

<main class="cart-page">

    <h1 class="cart-title">ショッピングカート</h1>

    <?php if (empty($items)): ?>
        <p class="cart-empty">カートに商品は入っていません。</p>
    <?php else: ?>

        <div class="cart-layout">

            <!-- 左：商品リスト -->
            <section class="cart-items">
                <?php foreach ($items as $it):
                    $price = (int) $it['price'];
                    $qty = (int) $it['quantity'];
                    $stock = (int) $it['stock'];
                    $line_total = $price * $qty;
                    ?>
                    <div class="cart-item">

                        <div class="cart-item-image">
                            <img src="/E-mart/asset/img/products/<?= htmlspecialchars($it['category_name']) ?>/<?= htmlspecialchars($it['image_url']) ?>"
                                alt="<?= htmlspecialchars($it['name']) ?>">
                        </div>

                        <div class="cart-item-info">
                            <div class="cart-item-name">
                                <?= htmlspecialchars($it['name']) ?>
                            </div>

                            <div class="cart-item-price">
                                ￥<?= number_format($price) ?>
                            </div>

                            <div class="cart-item-stock">
                                <?php if ($stock > 0): ?>
                                    <span class="in-stock">在庫あり</span>
                                <?php else: ?>
                                    <span class="out-stock">品切れ</span>
                                <?php endif; ?>
                            </div>

                            <!-- 数量変更（在庫数まで） -->
                            <form method="post" action="/E-mart/src/cart/cart_update.php" class="cart-qty-form">
                                <input type="hidden" name="cart_item_id" value="<?= $it['cart_item_id'] ?>">

                                <select name="quantity" onchange="this.form.submit()">
                                    <?php
                                    // 在庫数までしか選べない
                                    $max_qty = max(1, $stock);

                                    for ($n = 1; $n <= $max_qty; $n++):
                                        ?>
                                        <option value="<?= $n ?>" <?= ($qty === $n) ? 'selected' : '' ?>>
                                            <?= $n ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </form>

                            <!-- 削除 -->
                            <form method="post" action="/E-mart/src/cart/cart_remove.php" class="cart-remove-form"
                                onsubmit="return confirm('この商品を削除しますか？');">
                                <input type="hidden" name="cart_item_id" value="<?= $it['cart_item_id'] ?>">
                                <button type="submit" class="cart-remove-btn">削除</button>
                            </form>
                        </div>

                        <div class="cart-item-total">
                            ￥<?= number_format($line_total) ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </section>

            <!-- 右：集計 -->
            <aside class="cart-summary">
                <div class="summary-box">
                    <div class="summary-row">
                        小計（<?= count($items) ?>点）:
                        <span class="summary-price">￥<?= number_format($subtotal) ?></span>
                    </div>

                    <a href="/E-mart/src/order/checkout.php" class="summary-checkout-btn">
                        レジに進む
                    </a>

                    <div class="summary-note">
                        ※注文確定はまだしない仕様でもOK。後で実装で大丈夫。
                    </div>
                </div>
            </aside>

        </div>

    <?php endif; ?>

</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/footer.php'); ?>