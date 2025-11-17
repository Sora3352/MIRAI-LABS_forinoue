<?php
require_once("../../components/admin_login_check.php");
require_once("../../asset/php/db_connect.php");
include("../../components/admin_header.php");
include("../../components/admin_breadcrumb.php");

// 商品取得
$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch();

if (!$product) {
    die("商品が見つかりません。");
}

// カテゴリ一覧
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>商品編集</h2>

<form action="product_edit_save.php" method="post" enctype="multipart/form-data" class="admin-form">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">

    <div class="form-group">
        <label>商品名</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
    </div>

    <div class="form-group">
        <label>価格（円）</label>
        <input type="number" name="price" value="<?= $product['price'] ?>" step="0.01" required>
    </div>

    <div class="form-group">
        <label>在庫数</label>
        <input type="number" name="stock" value="<?= $product['stock'] ?>" required>
    </div>

    <div class="form-group">
        <label>カテゴリー</label>
        <select name="category_id">
            <option value="">選択しない</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? "selected" : "" ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>商品説明</label>
        <textarea name="description" rows="5"><?= htmlspecialchars($product['description']) ?></textarea>
    </div>

    <div class="form-group">
        <label>現在の画像</label><br>
        <?php if ($product['image_url']): ?>
            <img src="<?= $product['image_url'] ?>" style="width:120px; margin-bottom:10px;">
        <?php else: ?>
            なし
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label>新しい画像（変更しない場合は未選択）</label>
        <input type="file" name="image">
    </div>

    <button class="admin-btn">保存する</button>
</form>

<?php include("../../components/admin_footer.php"); ?>