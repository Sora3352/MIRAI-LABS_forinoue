<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

// カテゴリ一覧
$sql = "SELECT id, name FROM categories ORDER BY id ASC";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品追加 | E-mart 管理</title>
    <link rel="stylesheet" href="/E-mart/asset/css/product.css?v=2"><!-- CSSバージョン２ -->
</head>

<body>

    <h1>商品追加</h1>

    <form action="add_save.php" method="POST" enctype="multipart/form-data" class="product-form">

        <label>商品名<span class="required">*</span></label>
        <input type="text" name="name" required>

        <label>説明</label>
        <textarea name="description" rows="4"></textarea>

        <label>価格<span class="required">*</span></label>
        <input type="number" name="price" required>

        <label>在庫<span class="required">*</span></label>
        <input type="number" name="stock" required>

        <label>カテゴリ<span class="required">*</span></label>
        <select name="category_id" required>
            <option value="">選択してください</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>商品画像</label>
        <input type="file" name="image">

        <button type="submit" class="submit-btn">商品を登録</button>

    </form>

</body>

</html>