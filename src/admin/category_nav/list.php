<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

// 取得
$stmt = $pdo->query("
    SELECT *
    FROM top_category_nav
    ORDER BY sort_order ASC, id ASC
");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>カテゴリナビ管理 | 管理画面</title>
    <link rel="stylesheet" href="/E-mart/asset/css/admin.css">

    <!-- SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <style>
        .nav-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .nav-card {
            background: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 10px;
            cursor: grab;
            position: relative;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
        }

        .nav-card:hover {
            background: #fafafa;
        }

        .nav-card .drag-handle {
            font-size: 18px;
            cursor: grab;
            color: #999;
            position: absolute;
            right: 12px;
            top: 12px;
        }

        .nav-title {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .nav-link {
            font-size: 13px;
            color: #555;
            word-break: break-all;
        }

        .nav-actions {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }

        .edit-btn,
        .delete-btn {
            padding: 6px 10px;
            border-radius: 5px;
            font-size: 13px;
            text-decoration: none;
        }

        .edit-btn {
            background: #4caf50;
            color: white;
        }

        .delete-btn {
            background: #e53935;
            color: white;
        }

        .add-btn {
            display: inline-block;
            padding: 10px 15px;
            background: #2196f3;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <h1>カテゴリナビ管理</h1>

    <a class="add-btn" href="add.php">＋ カテゴリを追加</a>

    <div id="navList" class="nav-container">
        <?php foreach ($categories as $c): ?>
            <div class="nav-card" data-id="<?= $c['id'] ?>">
                <div class="drag-handle">≡</div>

                <div class="nav-title">
                    <?= htmlspecialchars($c['label']) ?>
                </div>

                <div class="nav-link">
                    <?= htmlspecialchars($c['link_url']) ?>
                </div>

                <div class="nav-actions">
                    <a class="edit-btn" href="edit.php?id=<?= $c['id'] ?>">編集</a>
                    <a class="delete-btn" href="delete.php?id=<?= $c['id'] ?>" onclick="return confirm('削除しますか？');">
                        削除
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        // SortableJS
        new Sortable(document.getElementById('navList'), {
            animation: 150,
            handle: ".drag-handle",
            onEnd: function (evt) {

                // すべてのIDを取得
                let order = [];
                document.querySelectorAll('.nav-card').forEach(card => {
                    order.push(card.dataset.id);
                });

                // 並び順をAjaxで保存
                fetch("sort.php", {
                    method: "POST",
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order: order })
                });
            }
        });
    </script>

</body>

</html>