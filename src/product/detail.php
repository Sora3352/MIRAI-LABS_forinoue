<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

if (!isset($_GET['id'])) {
    header("Location: /E-mart/src/product/list.php");
    exit;
}

$id = (int) $_GET['id'];

// 商品取得
$sql = "
    SELECT 
        p.*,
        c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
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

$stock = (int) $product['stock'];   // 在庫数

// 発送ロジック（Amazon風）
$now = new DateTime('now');
$cutoff = new DateTime('today 18:00');
$delivery = ($now <= $cutoff)
    ? new DateTime('tomorrow')
    : new DateTime('tomorrow +1 day');
$delivery_date = $delivery->format('n月j日');

// ヘッダー読込
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

        <div class="detail-price">
            ￥<?= number_format($product['price']) ?>
        </div>

        <!-- 発送日 -->
        <div class="detail-delivery">
            <span class="company">ヤマト運輸・E-mart 配達</span><br>
            <span class="free">無料配送</span>
            <span class="date"><?= $delivery_date ?></span> にお届け
        </div>

        <!-- ====================== 在庫あり：カートに入れる ====================== -->
        <?php if ($stock > 0): ?>
            <form method="post" action="/E-mart/src/cart/cart_add.php">

                <!-- 数量選択（在庫まで） -->
                <div class="detail-qty">
                    <label for="qty">数量：</label>
                    <select id="qty" name="quantity">
                        <?php for ($i = 1; $i <= $stock; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <button class="detail-cart-btn">カートに入れる</button>

            </form>
        <?php else: ?>

            <!-- ====================== 在庫なし ====================== -->
            <div class="detail-soldout">品切れです</div>

        <?php endif; ?>

        <!-- 商品説明 -->
        <p style="margin-top:20px; color:#555; line-height:1.6;">
            <?= nl2br(htmlspecialchars($product['description'])) ?>
        </p>

    </div>

</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/footer.php'); ?>