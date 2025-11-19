<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===== カテゴリ一覧取得 =====
$sql = "SELECT * FROM categories ORDER BY id ASC";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カテゴリ管理 | E-mart 管理</title>
    <link rel="stylesheet" href="/E-mart/asset/css/product.css">
</head>

<body>

    <h1>カテゴリ管理</h1>
    <div class="back-wrap">
        <a href="/E-mart/src/admin/products/list.php" class="back-btn">← 商品管理に戻る</a>
    </div>


    <!-- 追加ボタン -->
    <div class="top-actions">
        <a href="/E-mart/src/admin/categories/add.php" class="add-btn">＋ カテゴリを追加</a>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>カテゴリ名</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="4" class="no-data">カテゴリがありません</td>
                </tr>
            <?php else: ?>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= $cat['id'] ?></td>
                        <td><?= htmlspecialchars($cat['name']) ?></td>

                        <td>
                            <a href="/E-mart/src/admin/categories/edit.php?id=<?= $cat['id'] ?>" class="edit-btn">編集</a>
                        </td>

                        <td>
                            <a href="/E-mart/src/admin/categories/delete.php?id=<?= $cat['id'] ?>" class="delete-btn"
                                onclick="return confirm('削除すると、このカテゴリの商品画像フォルダも影響します。削除してよいですか？');">
                                削除
                            </a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>