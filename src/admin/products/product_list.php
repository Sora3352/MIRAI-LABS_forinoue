<?php
require_once("../../components/admin_login_check.php");
require_once("../../asset/php/db_connect.php");
include("../../components/admin_header.php");
include("../../components/admin_breadcrumb.php");

// 商品一覧取得（カテゴリー名もJOIN）
$sql = "
SELECT p.*, c.name AS category_name 
FROM products p
LEFT JOIN categories c ON p.category_id = c.id
ORDER BY p.created_at DESC
";
$products = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>商品一覧</h2>

<a href="product_add.php" class="admin-btn-green">＋ 商品を追加する</a>

<table class="admin-table">
    <tr>
        <th>ID</th>
        <th>画像</th>
        <th>商品名</th>
        <th>価格</th>
        <th>在庫</th>
        <th>カテゴリ</th>
        <th>操作</th>
    </tr>

    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>

            <td>
                <?php if ($p['image_url']): ?>
                    <img src="<?= $p['image_url'] ?>" style="width: 60px; height:auto;">
                <?php else: ?>
                    なし
                <?php endif; ?>
            </td>

            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= number_format($p['price']) ?> 円</td>
            <td><?= $p['stock'] ?></td>
            <td><?= $p['category_name'] ?: "未設定" ?></td>

            <td>
                <a class="admin-btn" href="product_edit.php?id=<?= $p['id'] ?>">編集</a>

                <a class="admin-btn-red" href="product_delete.php?id=<?= $p['id'] ?>"
                    onclick="return confirm('本当に削除しますか？');">
                    削除
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include("../../components/admin_footer.php"); ?>