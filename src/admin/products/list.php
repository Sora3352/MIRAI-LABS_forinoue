<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===== 検索条件 =====
$keyword = $_GET['keyword'] ?? '';
$category_id = $_GET['category'] ?? '';

// ===== 1ページあたり =====
$per_page = 30;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
    $page = 1;
$start = ($page - 1) * $per_page;

// ===== カテゴリ一覧 =====
$cat_sql = "SELECT id, name FROM categories ORDER BY id ASC";
$cat_stmt = $pdo->query($cat_sql);
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

// ===== 件数カウント =====
$count_sql = "
    SELECT COUNT(*)
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE 1
";
if ($keyword !== '')
    $count_sql .= " AND p.name LIKE :keyword";
if ($category_id !== '')
    $count_sql .= " AND p.category_id = :category_id";

$count_stmt = $pdo->prepare($count_sql);
if ($keyword !== '')
    $count_stmt->bindValue(":keyword", '%' . $keyword . '%', PDO::PARAM_STR);
if ($category_id !== '')
    $count_stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);
$count_stmt->execute();

$total_count = $count_stmt->fetchColumn();
$total_pages = ceil($total_count / $per_page);

// ===== 商品取得 =====
$sql = "
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE 1
";
if ($keyword !== '')
    $sql .= " AND p.name LIKE :keyword";
if ($category_id !== '')
    $sql .= " AND p.category_id = :category_id";

$sql .= " ORDER BY p.id DESC LIMIT :start, :per_page";

$stmt = $pdo->prepare($sql);
if ($keyword !== '')
    $stmt->bindValue(":keyword", '%' . $keyword . '%', PDO::PARAM_STR);
if ($category_id !== '')
    $stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);
$stmt->bindValue(":start", $start, PDO::PARAM_INT);
$stmt->bindValue(":per_page", $per_page, PDO::PARAM_INT);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品管理 | E-mart 管理</title>
    <link rel="stylesheet" href="/E-mart/asset/css/product.css">
</head>

<body>

    <h1>商品管理</h1>

    <!-- 商品追加ボタン -->
    <div class="top-actions">
        <!-- トップに戻るボタンはグレーにする -->
        <a href="/E-mart/src/admin/admin_menu.php" class="add-btn" style="background:#888;">← 管理トップ</a>


        <!-- 商品追加 -->
        <a href="/E-mart/src/admin/products/add.php" class="add-btn">＋ 商品を追加</a>

        <!-- カテゴリ管理 -->
        <a href="/E-mart/src/admin/categories/list.php" class="add-btn" style="background:#6a1b9a; margin-left:10px;">
            📂 カテゴリ管理
        </a>

    </div>

    <!-- 検索フォーム -->
    <form method="GET" class="search-form">
        <input type="text" name="keyword" placeholder="商品名で検索" value="<?= htmlspecialchars($keyword) ?>">

        <select name="category">
            <option value="">すべてのカテゴリ</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($category_id == $cat['id'] ? 'selected' : '') ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">検索</button>
    </form>

    <!-- ============================= -->
    <!-- 商品一覧（複数削除対応） -->
    <!-- ============================= -->

    <form method="POST" action="/E-mart/src/admin/products/delete_multiple.php"
        onsubmit="return confirm('選択した商品を削除しますか？');">

        <table class="product-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check_all"></th>
                    <th>ID</th>
                    <th>画像</th>
                    <th>商品名</th>
                    <th>カテゴリ</th>
                    <th>価格</th>
                    <th>在庫</th>
                    <th>編集</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="8" class="no-data">商品がありません</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="<?= $p['id'] ?>">
                            </td>

                            <td><?= $p['id'] ?></td>

                            <td>
                                <?php
                                if ($p['image_url'] && $p['category_name']) {
                                    $img_path = "/E-mart/asset/img/products/"
                                        . $p['category_name'] . "/"
                                        . $p['image_url'];
                                } else {
                                    $img_path = "/E-mart/asset/img/noimage.png";
                                }
                                ?>
                                <img src="<?= $img_path ?>" class="thumb">
                            </td>

                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= htmlspecialchars($p['category_name']) ?></td>
                            <td><?= number_format($p['price']) ?> 円</td>
                            <td><?= $p['stock'] ?></td>

                            <td>
                                <a href="/E-mart/src/admin/products/edit.php?id=<?= $p['id'] ?>" class="edit-btn" target="_self">編集</a><!-- 新しいタブで開かないようにしたい -->
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- 一括削除ボタン -->
        <?php if (!empty($products)): ?>
            <div style="margin-top: 15px;">
                <button type="submit" class="delete-btn">選択した商品を削除</button>
            </div>
        <?php endif; ?>

    </form>

    <!-- ページネーション -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>&keyword=<?= urlencode($keyword) ?>&category=<?= $category_id ?>">« 前へ</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>&category=<?= $category_id ?>"
                class="<?= ($i == $page ? 'active' : '') ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?= $page + 1 ?>&keyword=<?= urlencode($keyword) ?>&category=<?= $category_id ?>">次へ »</a>
        <?php endif; ?>
    </div>

    <script>
        // 全選択・全解除
        document.getElementById('check_all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name="ids[]"]');
            checkboxes.forEach(ch => ch.checked = this.checked);
        });
    </script>

</body>

</html>