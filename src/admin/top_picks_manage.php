<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

// 並び替え条件
$order = $_GET['order'] ?? 'sales';

// 売上順のSQL（order_items）
$sales_sql = "
    SELECT
        p.*,
        COALESCE(SUM(oi.quantity), 0) AS sold
    FROM products p
    LEFT JOIN order_items oi ON p.id = oi.product_id
    GROUP BY p.id
    ORDER BY sold DESC, p.id DESC
";

// 在庫順
$stock_sql = "
    SELECT p.*, p.stock AS value
    FROM products p
    ORDER BY p.stock DESC, p.id DESC
";

// 新着順
$new_sql = "
    SELECT p.*, p.created_at AS value
    FROM products p
    ORDER BY p.created_at DESC
";

// 価格安い順
$low_sql = "
    SELECT p.*, p.price AS value
    FROM products p
    ORDER BY p.price ASC, p.id DESC
";

// 価格高い順
$high_sql = "
    SELECT p.*, p.price AS value
    FROM products p
    ORDER BY p.price DESC, p.id DESC
";

// SQLを選ぶ
switch ($order) {
    case 'stock': $sql = $stock_sql; break;
    case 'new':   $sql = $new_sql; break;
    case 'low':   $sql = $low_sql; break;
    case 'high':  $sql = $high_sql; break;
    default:      $sql = $sales_sql; // 売上順
}
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// すでに選ばれているおすすめ
$pick_stmt = $pdo->query("SELECT product_id FROM top_picks ORDER BY sort_order ASC");
$selected_ids = $pick_stmt->fetchAll(PDO::FETCH_COLUMN);

// 保存処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $checked = $_POST['pick'] ?? [];

    // 一旦クリア
    $pdo->exec("TRUNCATE top_picks");

    // 再登録
    $sort = 1;
    $stmt = $pdo->prepare("INSERT INTO top_picks (product_id, sort_order) VALUES (:pid, :sort)");
    foreach ($checked as $pid) {
        $stmt->bindValue(':pid', (int)$pid, PDO::PARAM_INT);
        $stmt->bindValue(':sort', $sort, PDO::PARAM_INT);
        $stmt->execute();
        $sort++;
    }

    header("Location: top_picks_manage.php?order=$order");
    exit;
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>おすすめ商品管理 | E-mart管理</title>
    <link rel="stylesheet" href="/E-mart/asset/css/product.css">
</head>

<body>

<h1>おすすめ商品管理</h1>
<p><a href="/E-mart/src/admin/products/list.php">← 商品管理に戻る</a></p>

<!-- 並び替え選択 -->
<form method="GET" class="search-form" style="margin-bottom:15px;">
    <label>並び替え：</label>
    <select name="order" onchange="this.form.submit()">
        <option value="sales" <?= $order=='sales'?'selected':'' ?>>売上順</option>
        <option value="stock" <?= $order=='stock'?'selected':'' ?>>在庫が多い順</option>
        <option value="new"   <?= $order=='new'?'selected':'' ?>>新着順</option>
        <option value="low"   <?= $order=='low'?'selected':'' ?>>価格が安い順</option>
        <option value="high"  <?= $order=='high'?'selected':'' ?>>価格が高い順</option>
    </select>
</form>

<form method="POST">

<table class="product-table">
    <thead>
        <tr>
            <th>選択</th>
            <th>ID</th>
            <th>画像</th>
            <th>商品名</th>
            <th>カテゴリ</th>
            <th>価格</th>
            <th>在庫</th>
            <?php if ($order == 'sales'): ?>
                <th>売上数量</th>
            <?php endif; ?>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($products as $p): ?>

            <?php
            // 画像パス
            $img = "/E-mart/asset/img/noimage.png";
            if ($p["image_url"] && $p["name"]) {
                $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = :cid");
                $stmt->bindValue(":cid", $p["category_id"], PDO::PARAM_INT);
                $stmt->execute();
                $catname = $stmt->fetchColumn();

                if ($catname) {
                    $img = "/E-mart/asset/img/products/$catname/" . $p['image_url'];
                }
            }
            ?>

            <tr>
                <td>
                    <input type="checkbox" name="pick[]"
                        value="<?= $p['id'] ?>"
                        <?= in_array($p['id'], $selected_ids) ? 'checked' : '' ?>>
                </td>

                <td><?= $p['id'] ?></td>
                <td><img src="<?= $img ?>" class="thumb"></td>
                <td><?= htmlspecialchars($p['name']) ?></td>

                <td>
                    <?php
                    $c = $pdo->query("SELECT name FROM categories WHERE id=".$p["category_id"])->fetchColumn();
                    echo htmlspecialchars($c);
                    ?>
                </td>

                <td><?= number_format($p['price']) ?> 円</td>
                <td><?= $p['stock'] ?></td>

                <?php if ($order == 'sales'): ?>
                    <td><?= $p['sold'] ?></td>
                <?php endif; ?>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>

<button type="submit" class="add-btn" style="margin-top:15px;">
    選択した商品でおすすめを更新
</button>

</form>

</body>
</html>
