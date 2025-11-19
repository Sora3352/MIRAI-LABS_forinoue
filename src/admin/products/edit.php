<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("不正な ID です。");
}

$id = (int) $_GET['id'];

// 商品情報
$sql = "
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.id = :id
    LIMIT 1
";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("商品が存在しません。");
}

// カテゴリ一覧
$cat_sql = "SELECT id, name FROM categories ORDER BY id ASC";
$cat_stmt = $pdo->query($cat_sql);
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品編集 | E-mart 管理</title>
    <link rel="stylesheet" href="/E-mart/asset/css/product.css">
</head>

<body>

    <h1>商品編集</h1>

    <!-- 戻るボタン -->
    <div class="back-wrap">
        <a href="/E-mart/src/admin/products/list.php" class="back-btn">← 戻る</a>
    </div>

    <form action="edit_save.php" method="POST" enctype="multipart/form-data" class="product-form">

        <input type="hidden" name="id" value="<?= $product['id'] ?>">

        <label>商品名<span class="required">*</span></label>
        <input type="text" name="name" required value="<?= htmlspecialchars($product['name']) ?>">

        <label>説明</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>

        <label>価格<span class="required">*</span></label>
        <input type="number" name="price" required value="<?= $product['price'] ?>">

        <label>在庫<span class="required">*</span></label>
        <input type="number" name="stock" required value="<?= $product['stock'] ?>">

        <label>カテゴリ<span class="required">*</span></label>
        <select name="category_id" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- 現在の画像 -->
        <label>現在の画像</label>
        <?php
        if ($product['image_url']) {
            $img_path = "/E-mart/asset/img/products/" . $product['category_name'] . "/" . $product['image_url'];
        } else {
            $img_path = "/E-mart/asset/img/noimage.png";
        }
        ?>
        <img src="<?= $img_path ?>" class="thumb" style="margin-bottom: 15px;">

        <label>画像を変更する（任意）</label>
        <input type="file" name="image">
        <input type="hidden" name="return_category" value="<?= $product['category_id'] ?>">

        <button type="submit" class="submit-btn">更新する</button>

    </form>

</body>

</html>