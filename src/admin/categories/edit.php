<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("不正なID");
}
$id = (int) $_GET['id'];

// カテゴリ取得
$sql = "SELECT * FROM categories WHERE id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("カテゴリが存在しません");
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カテゴリ編集 | E-mart 管理</title>
    <link rel="stylesheet" href="/E-mart/asset/css/product.css">
</head>

<body>

    <h1>カテゴリ編集</h1>

    <div class="back-wrap">
        <a href="/E-mart/src/admin/categories/list.php" class="back-btn">← 戻る</a>
    </div>

    <form action="edit_save.php" method="POST" class="product-form">

        <input type="hidden" name="id" value="<?= $category['id'] ?>">

        <label>カテゴリ名<span class="required">*</span></label>
        <input type="text" name="name" required value="<?= htmlspecialchars($category['name']) ?>">

        <button type="submit" class="submit-btn">更新する</button>

    </form>

</body>

</html>