<?php
require_once("../../components/admin_login_check.php");
require_once("../../asset/php/db_connect.php");
include("../../components/admin_header.php");
include("../../components/admin_breadcrumb.php");

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>商品追加</h2>

<form action="product_add_save.php" method="post" enctype="multipart/form-data" class="admin-form">

    <div class="form-group">
        <label>商品名<span class="req">必須</span></label>
        <input type="text" name="name" required>
    </div>

    <div class="form-group">
        <label>価格（円）<span class="req">必須</span></label>
        <input type="number" name="price" step="0.01" required>
    </div>

    <div class="form-group">
        <label>在庫数<span class="req">必須</span></label>
        <input type="number" name="stock" min="0" required>
    </div>

    <div class="form-group">
        <label>カテゴリー</label>
        <select name="category_id">
            <option value="">選択しない</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>商品説明</label>
        <textarea name="description" rows="5"></textarea>
    </div>

    <div class="form-group">
        <label>商品画像（任意）</label>
        <input type="file" name="image">
    </div>

    <button class="admin-btn">登録する</button>
</form>

<?php include("../../components/admin_footer.php"); ?>