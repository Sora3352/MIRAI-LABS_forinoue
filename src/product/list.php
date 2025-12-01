<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

// ============================
// パラメータ取得
// ============================
$keyword = $_GET['keyword'] ?? '';
$category_id = $_GET['category'] ?? '';
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$in_stock = $_GET['in_stock'] ?? '';
$sort = $_GET['sort'] ?? ''; // 並び替え


// ============================
// カテゴリ一覧
// ============================
$cat_stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);


// ============================
// 商品一覧 SQL（★セール対応）
// ============================
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
                 AND s.end_at   >= NOW()
            THEN s.sale_price
            ELSE p.price
        END AS final_price
    FROM products p
    LEFT JOIN categories c
        ON p.category_id = c.id
    LEFT JOIN sale_products s
        ON s.product_id = p.id
    WHERE 1
";

$params = [];

// キーワード
if ($keyword !== '') {
    $sql .= " AND (p.name LIKE :keyword OR p.description LIKE :keyword)";
    $params[':keyword'] = '%' . $keyword . '%';
}

// カテゴリ
if ($category_id !== '') {
    $sql .= " AND p.category_id = :category_id";
    $params[':category_id'] = (int) $category_id;
}

// 価格帯（★final_price ではなく元の price 基準にしておく）
if ($price_min !== '') {
    $sql .= " AND p.price >= :price_min";
    $params[':price_min'] = (float) $price_min;
}
if ($price_max !== '') {
    $sql .= " AND p.price <= :price_max";
    $params[':price_max'] = (float) $price_max;
}

// 在庫ありのみ
if ($in_stock === '1') {
    $sql .= " AND p.stock > 0";
}

// ============================
// 並び替え（★final_price 使用）
// ============================
$sql .= " ORDER BY 
    CASE WHEN p.stock > 0 THEN 0 ELSE 1 END,
";

if ($sort === 'price_asc') {
    $sql .= " final_price ASC, p.id DESC";
} elseif ($sort === 'price_desc') {
    $sql .= " final_price DESC, p.id DESC";
} else {
    $sql .= " p.id DESC";  // デフォルト：新着順
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ============================
// ページネーション
// ============================
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$per_page = 32;

$total_products = count($products);
$total_pages = max(1, ceil($total_products / $per_page));

$start_index = ($page - 1) * $per_page;
$products = array_slice($products, $start_index, $per_page);


// ============================
// ヘッダー
// ============================
include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/header.php');
?>

<link rel="stylesheet" href="/E-mart/asset/css/product_list.css?v=1.2.0">

<main class="product-page">

    <div class="product-page-header">
        <div>
            <h1 class="product-title">商品一覧</h1>

            <?php if ($keyword !== ''): ?>
                <p class="search-word">検索ワード: 「<?= htmlspecialchars($keyword) ?>」</p>
            <?php endif; ?>
        </div>

        <!-- 並び替え -->
        <form method="get" class="sort-form">
            <?php foreach ($_GET as $key => $value): ?>
                <?php if ($key !== 'sort' && $key !== 'page'): ?>
                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                <?php endif; ?>
            <?php endforeach; ?>

            <select name="sort" onchange="this.form.submit()" class="sort-select">
                <option value="">並び替え</option>
                <option value="price_asc" <?= ($sort === 'price_asc') ? 'selected' : '' ?>>価格の安い順</option>
                <option value="price_desc" <?= ($sort === 'price_desc') ? 'selected' : '' ?>>価格の高い順</option>
            </select>
        </form>

        <!-- 小さい画面用の絞り込みボタン -->
        <label for="filter-toggle-checkbox" class="filter-toggle">
            絞り込み
        </label>
    </div>

    <!-- ハンバーガー制御用のチェックボックス -->
    <input type="checkbox" id="filter-toggle-checkbox" style="display:none;">

    <div class="product-page-inner">

        <!-- ===== サイドバー（絞り込み） ===== -->
        <aside class="sidebar">
            <div class="sidebar-title">絞り込み</div>

            <form method="get" class="filter-form">

                <div class="filter-group">
                    <label for="f_keyword">キーワード</label>
                    <input type="text" id="f_keyword" name="keyword" value="<?= htmlspecialchars($keyword) ?>"
                        placeholder="テレビ, 掃除機 など">
                </div>

                <div class="filter-group">
                    <label for="f_category">カテゴリ</label>
                    <select id="f_category" name="category">
                        <option value="">すべて</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($category_id == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>価格帯</label>
                    <div class="filter-inline">
                        <input type="number" name="price_min" value="<?= htmlspecialchars($price_min) ?>"
                            placeholder="最小 (円)">
                        <input type="number" name="price_max" value="<?= htmlspecialchars($price_max) ?>"
                            placeholder="最大 (円)">
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-checkbox-label">
                        <input type="checkbox" name="in_stock" value="1" <?= ($in_stock === '1') ? 'checked' : '' ?>>
                        在庫ありのみ
                    </label>
                </div>

                <button type="submit" class="filter-submit">この条件で絞り込む</button>
            </form>
        </aside>

        <!-- ===== 商品グリッド ===== -->
        <div class="product-grid">

            <?php if (!empty($products)): ?>

                <?php foreach ($products as $p): ?>
                    <?php
                    $final_price = (int) $p['final_price'];
                    $base_price = (int) $p['price'];
                    $is_sale = ($final_price < $base_price);
                    ?>
                    <div class="product-card">

                        <a href="/E-mart/src/product/detail.php?id=<?= $p['id'] ?>" class="product-link"
                            style="text-decoration: none;">
                            <div class="product-image">
                                <img src="/E-mart/asset/img/products/<?= htmlspecialchars($p['category_name']) ?>/<?= htmlspecialchars($p['image_url']) ?>"
                                    alt="">
                            </div>

                            <div class="product-name">
                                <?= htmlspecialchars(mb_strimwidth($p['name'], 0, 40, "…")) ?>
                            </div>

                            <div class="product-price">
                                <?php if ($is_sale): ?>
                                    <span style="color:#d00; font-weight:bold;">
                                        ￥<?= number_format($final_price) ?>
                                    </span>
                                    <span style="text-decoration:line-through; color:#777; margin-left:6px; font-size:0.9em;">
                                        ￥<?= number_format($base_price) ?>
                                    </span>
                                <?php else: ?>
                                    <span>
                                        ￥<?= number_format($final_price) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </a>

                        <!-- 発送ロジック -->
                        <?php
                        $now = new DateTime('now');
                        $cutoff = new DateTime('today 18:00');

                        if ($now <= $cutoff) {
                            $delivery = new DateTime('tomorrow');
                        } else {
                            $delivery = new DateTime('tomorrow +1 day');
                        }
                        $delivery_date = $delivery->format('n月j日');
                        ?>

                        <div class="product-extra">
                            <div class="delivery-info">
                                <span class="company">ヤマト運輸・E-mart 配達</span><br>
                                <span class="free">無料配送</span>
                                <span class="date"><?= $delivery_date ?></span> にお届け
                            </div>

                            <?php if ((int) $p['stock'] > 0): ?>
                                <form method="post" action="/E-mart/src/cart/cart_add.php" class="add-cart-form">
                                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                    <button type="submit" class="add-cart-btn">
                                        カートに入れる
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="soldout">品切れです</div>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; ?>

            <?php else: ?>

                <p class="no-products">該当する商品はありません。</p>

            <?php endif; ?>

        </div><!-- /product-grid -->
    </div><!-- /product-page-inner -->

    <!-- ======= ページネーション ======= -->
    <div class="pagination">

        <?php if ($page > 1): ?>
            <a class="page-btn" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                ← 前へ
            </a>
        <?php endif; ?>

        <?php
        $start = max(1, $page - 3);
        $end = min($total_pages, $page + 3);

        for ($i = $start; $i <= $end; $i++):
            ?>
            <a class="page-number <?= ($page == $i) ? 'active' : '' ?>"
                href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a class="page-btn" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                次へ →
            </a>
        <?php endif; ?>

    </div><!-- /pagination -->

</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/footer.php'); ?>