<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

/* ============================================================
   セール中商品（category_name を JOIN して画像を正しく取得）
============================================================ */
$sale_products = [];

try {
    $sql = "
        SELECT 
            p.id AS product_id,
            p.name,
            p.image_url,
            c.name AS category_name,
            p.price AS original_price,
            s.sale_price,
            s.discount_percent,
            s.is_time_sale,
            s.start_at,
            s.end_at
        FROM sale_products s
        INNER JOIN products p ON p.id = s.product_id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE s.is_active = 1
        ORDER BY s.discount_percent DESC, s.id ASC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $sale_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $sale_products = [];
}

/* ============================================================
   カテゴリナビ（top_category_nav）
============================================================ */
$cat_nav = [];
try {
    $nav_stmt = $pdo->prepare("
        SELECT *
        FROM top_category_nav
        WHERE is_active = 1
        ORDER BY sort_order ASC, id ASC
    ");
    $nav_stmt->execute();
    $cat_nav = $nav_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $cat_nav = [];
}

/* ============================================================
   おすすめ商品（top_picks）
============================================================ */
$pickups = [];
try {
    $pickup_stmt = $pdo->prepare("
        SELECT 
            tp.sort_order,
            p.id AS product_id,
            p.name,
            p.price,
            p.image_url,
            c.name AS category_name
        FROM top_picks tp
        JOIN products p ON tp.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY tp.sort_order ASC, tp.id ASC
    ");
    $pickup_stmt->execute();
    $pickups = $pickup_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $pickups = [];
}

// 共通ヘッダー
include_once($_SERVER['DOCUMENT_ROOT'] . "/E-mart/components/header.php");
?>

<main class="em-top">

    <!-- =====================================================
         カテゴリー（タイトル付き）
    ====================================================== -->
    <?php if (!empty($cat_nav)): ?>
        <section class="top-category-nav">
            <h2 class="category-title">商品カテゴリー</h2>

            <div class="cat-nav-list">
                <?php foreach ($cat_nav as $c): ?>
                    <a class="cat-nav-item" href="<?= htmlspecialchars($c['link_url']) ?>">
                        <?= htmlspecialchars($c['label']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>


    <!-- =====================================================
         セール中の商品（pickup と同じデザイン）
    ====================================================== -->
    <?php if (!empty($sale_products)): ?>
        <section class="sale-slider">
            <h2 class="sale-title">セール中の商品</h2>

            <div class="sale-list">
                <?php foreach ($sale_products as $s): ?>

                    <a class="sale-card" href="/E-mart/src/product/detail.php?id=<?= $s['product_id'] ?>">

                        <!-- 画像パスの統一処理 -->
                        <?php
                        $img = "/E-mart/asset/img/noimage.png";
                        if (!empty($s['image_url']) && !empty($s['category_name'])) {
                            $img = "/E-mart/asset/img/products/" .
                                $s['category_name'] . "/" .
                                $s['image_url'];
                        }
                        ?>

                        <img src="<?= htmlspecialchars($img) ?>" alt="" class="sale-img">

                        <!-- Amazon風ラベル -->
                        <div class="sale-label-wrap">
                            <span class="sale-label off-label"><?= $s['discount_percent'] ?>%OFF</span>

                            <?php if ($s['is_time_sale']): ?>
                                <span class="sale-label time-label">タイムセール</span>
                            <?php endif; ?>
                        </div>

                        <div class="sale-price">
                            ¥<?= number_format($s['sale_price']) ?>
                            <span class="sale-old">¥<?= number_format($s['original_price']) ?></span>
                        </div>

                        <div class="sale-name">
                            <?= htmlspecialchars($s['name']) ?>
                        </div>

                    </a>

                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>


    <!-- =====================================================
         おすすめ商品（既存の完成済要素）
    ====================================================== -->
    <?php if (!empty($pickups)): ?>
        <section class="top-pickups">
            <h2>おすすめ商品</h2>

            <div class="pickup-slider">
                <?php foreach ($pickups as $p): ?>

                    <a class="pickup-card" href="/E-mart/src/product/detail.php?id=<?= $p['product_id'] ?>">

                        <!-- 画像パス -->
                        <?php
                        $img = "/E-mart/asset/img/noimage.png";
                        if (!empty($p['image_url']) && !empty($p['category_name'])) {
                            $img = "/E-mart/asset/img/products/" .
                                $p['category_name'] . "/" .
                                $p['image_url'];
                        }
                        ?>
                        <img src="<?= htmlspecialchars($img) ?>" class="pickup-img">

                        <div class="pickup-title"><?= htmlspecialchars($p['name']) ?></div>
                        <div class="pickup-price">税込 <?= number_format($p['price']) ?>円</div>

                    </a>

                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php
// 共通フッター
include_once($_SERVER['DOCUMENT_ROOT'] . "/E-mart/components/footer.php");
?>

<link rel="stylesheet" href="/E-mart/asset/css/front.css">