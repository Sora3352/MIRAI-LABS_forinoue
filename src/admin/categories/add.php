<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カテゴリ追加 | E-mart 管理</title>
    <link rel="stylesheet" href="/E-mart/asset/css/product.css">
</head>

<body>

    <h1>カテゴリ追加</h1>

    <div class="back-wrap">
        <a href="/E-mart/src/admin/categories/list.php" class="back-btn">← 戻る</a>
    </div>

    <form action="add_save.php" method="POST" class="product-form">

        <label>カテゴリ名<span class="required">*</span></label>
        <input type="text" name="name" required>

        <button type="submit" class="submit-btn">追加する</button>

    </form>

</body>

</html>