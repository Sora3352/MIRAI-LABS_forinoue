<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

if (!isset($_GET['id'])) {
    header("Location: /E-mart/src/product/list.php");
    exit;
}

$id = (int) $_GET['id'];

/* ================================
   商品 + セール情報取得
================================ */
$sql = "
    SELECT 
        p.*,
        c.name AS category_name,
        s.sale_price,
        s.start_at,
        s.end_at,
        CASE
            WHEN s.sale_price IS NOT NULL
                 AND s.start_at <= NOW()
                 AND s.end_at >= NOW()
            THEN s.sale_price
            ELSE p.price
        END AS final_price
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN sale_products s ON s.product_id = p.id
    WHERE p.id = :id
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "商品が見つかりません。";
    exit;
}

$stock = (int) $product['stock'];

/* セール中かどうかの判定 */
$is_sale = (
    !empty($product['sale_price']) &&
    $product['start_at'] <= date('Y-m-d H:i:s') &&
    $product['end_at']   >= date('Y-m-d H:i:s')
);

$final_price = (int) $product['final_price'];

/* ============================
   配送日ロジック
============================ */
$now = new DateTime('now');
$cutoff = new DateTime('today 18:00');
$delivery = ($now <= $cutoff)
    ? new DateTime('tomorrow')
    : new DateTime('tomorrow +1 day');
$delivery_date = $delivery->format('n月j日');

/* ============================
   ヘッダー読込
============================ */
include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/header.php');
?>

<link rel="stylesheet" href="/E-mart/asset/css/product_detail.css?v=1.0.3">

<main class="detail-wrapper">

    <!-- ====================== 左：商品画像 ====================== -->
    <div class="detail-image">
        <img src="/E-mart/asset/img/products/<?= htmlspecialchars($product['category_name']) ?>/<?= htmlspecialchars($product['image_url']) ?>"
            alt="<?= htmlspecialchars($product['name']) ?>">
    </div>

    <!-- ====================== 右：商品情報 ====================== -->
    <div class="detail-info">

        <h1 class="detail-title"><?= htmlspecialchars($product['name']) ?></h1>

        <!-- ====================== 価格表示 ====================== -->
        <div class="detail-price">

            <?php if ($is_sale): ?>
                <span style="color:#d00; font-size:26px; font-weight:bold;">
                    ￥<?= number_format($final_price) ?>
                </span>
                <span style="text-decoration:line-through; color:#777; margin-left:8px;">
                    ￥<?= number_format($product['price']) ?>
                </span>
                <div style="color:#d00; font-weight:bold; margin-top:4px;">
                    ✨ タイムセール価格！ ✨
                </div>
            <?php else: ?>
                <span style="font-size:26px; font-weight:bold;">
                    ￥<?= number_format($final_price) ?>
                </span>
            <?php endif; ?>

        </div>

        <!-- 発送日 -->
        <div class="detail-delivery">
            <span class="company">ヤマト運輸・E-mart 配達</span><br>
            <span class="free">無料配送</span>
            <span class="date"><?= $delivery_date ?></span> にお届け
        </div>

        <!-- ====================== カートに入れる ====================== -->
        <?php if ($stock > 0): ?>
            <form method="post" action="/E-mart/src/cart/cart_add.php">

                <!-- 数量選択 -->
                <div class="detail-qty">
                    <label for="qty">数量：</label>
                    <select id="qty" name="quantity">
                        <?php for ($i = 1; $i <= $stock; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="hidden" name="price_snapshot" value="<?= $final_price ?>">

                <button class="detail-cart-btn">カートに入れる</button>

            </form>
        <?php else: ?>
            <div class="detail-soldout">品切れです</div>
        <?php endif; ?>

        <!-- 商品説明 -->
        <p style="margin-top:20px; color:#555; line-height:1.6;">
            <?= nl2br(htmlspecialchars($product['description'])) ?>
        </p>

    </div>

</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/footer.php'); ?>
