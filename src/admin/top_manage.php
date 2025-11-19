<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

// 簡易エラー表示（開発中だけ）
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ===== POST処理 =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = $_POST['section'] ?? '';

    switch ($section) {
        case 'message':
            $msg = trim($_POST['main_message'] ?? '');
            if ($msg !== '') {
                // 行がなければINSERT、あればUPDATE
                $pdo->exec("INSERT IGNORE INTO top_settings (id, main_message) VALUES (1, '')");
                $stmt = $pdo->prepare("UPDATE top_settings SET main_message = :msg WHERE id = 1");
                $stmt->bindValue(':msg', $msg, PDO::PARAM_STR);
                $stmt->execute();
            }
            break;

        case 'banner':
            $mode = $_POST['mode'] ?? 'create';
            $title = trim($_POST['title'] ?? '');
            $image_path = trim($_POST['image_path'] ?? '');
            $link_url = trim($_POST['link_url'] ?? '');
            $sort_order = (int) ($_POST['sort_order'] ?? 1);
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if ($mode === 'create') {
                $sql = "INSERT INTO top_banners (title, image_path, link_url, sort_order, is_active)
                        VALUES (:title, :image, :link, :sort, :active)";
                $stmt = $pdo->prepare($sql);
            } else {
                $id = (int) $_POST['id'];
                $sql = "UPDATE top_banners
                        SET title = :title, image_path = :image, link_url = :link,
                            sort_order = :sort, is_active = :active
                        WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            }
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':image', $image_path, PDO::PARAM_STR);
            $stmt->bindValue(':link', $link_url, PDO::PARAM_STR);
            $stmt->bindValue(':sort', $sort_order, PDO::PARAM_INT);
            $stmt->bindValue(':active', $is_active, PDO::PARAM_INT);
            $stmt->execute();
            break;

        case 'banner_delete':
            $id = (int) ($_POST['id'] ?? 0);
            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM top_banners WHERE id = :id");
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
            break;

        case 'pickup':
            $mode = $_POST['mode'] ?? 'create';
            $product_id = (int) ($_POST['product_id'] ?? 0);
            $label = trim($_POST['label'] ?? '');
            $sort_order = (int) ($_POST['sort_order'] ?? 1);
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if ($mode === 'create') {
                $sql = "INSERT INTO top_pickups (product_id, label, sort_order, is_active)
                        VALUES (:pid, :label, :sort, :active)";
                $stmt = $pdo->prepare($sql);
            } else {
                $id = (int) $_POST['id'];
                $sql = "UPDATE top_pickups
                        SET product_id = :pid, label = :label, sort_order = :sort, is_active = :active
                        WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            }
            $stmt->bindValue(':pid', $product_id, PDO::PARAM_INT);
            $stmt->bindValue(':label', $label, PDO::PARAM_STR);
            $stmt->bindValue(':sort', $sort_order, PDO::PARAM_INT);
            $stmt->bindValue(':active', $is_active, PDO::PARAM_INT);
            $stmt->execute();
            break;

        case 'pickup_delete':
            $id = (int) ($_POST['id'] ?? 0);
            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM top_pickups WHERE id = :id");
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
            break;

        case 'news':
            $mode = $_POST['mode'] ?? 'create';
            $title = trim($_POST['title'] ?? '');
            $body = trim($_POST['body'] ?? '');
            $sort_order = (int) ($_POST['sort_order'] ?? 1);
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if ($mode === 'create') {
                $sql = "INSERT INTO top_news (title, body, sort_order, is_active)
                        VALUES (:title, :body, :sort, :active)";
                $stmt = $pdo->prepare($sql);
            } else {
                $id = (int) $_POST['id'];
                $sql = "UPDATE top_news
                        SET title = :title, body = :body, sort_order = :sort, is_active = :active
                        WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            }
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':body', $body, PDO::PARAM_STR);
            $stmt->bindValue(':sort', $sort_order, PDO::PARAM_INT);
            $stmt->bindValue(':active', $is_active, PDO::PARAM_INT);
            $stmt->execute();
            break;

        case 'news_delete':
            $id = (int) ($_POST['id'] ?? 0);
            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM top_news WHERE id = :id");
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
            break;

        case 'sale':
            $mode = $_POST['mode'] ?? 'create';
            $title = trim($_POST['title'] ?? '');
            $subtitle = trim($_POST['subtitle'] ?? '');
            $image_path = trim($_POST['image_path'] ?? '');
            $link_url = trim($_POST['link_url'] ?? '');
            $sort_order = (int) ($_POST['sort_order'] ?? 1);
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if ($mode === 'create') {
                $sql = "INSERT INTO top_sales
                        (title, subtitle, image_path, link_url, sort_order, is_active)
                        VALUES (:title, :subtitle, :image, :link, :sort, :active)";
                $stmt = $pdo->prepare($sql);
            } else {
                $id = (int) $_POST['id'];
                $sql = "UPDATE top_sales
                        SET title = :title, subtitle = :subtitle,
                            image_path = :image, link_url = :link,
                            sort_order = :sort, is_active = :active
                        WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            }
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':subtitle', $subtitle, PDO::PARAM_STR);
            $stmt->bindValue(':image', $image_path, PDO::PARAM_STR);
            $stmt->bindValue(':link', $link_url, PDO::PARAM_STR);
            $stmt->bindValue(':sort', $sort_order, PDO::PARAM_INT);
            $stmt->bindValue(':active', $is_active, PDO::PARAM_INT);
            $stmt->execute();
            break;

        case 'sale_delete':
            $id = (int) ($_POST['id'] ?? 0);
            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM top_sales WHERE id = :id");
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
            break;
    }

    header("Location: top_manage.php");
    exit;
}

// ===== 表示用データ取得 =====
$setting = $pdo->query("SELECT * FROM top_settings ORDER BY id ASC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$banners = $pdo->query("SELECT * FROM top_banners ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
$pickups = $pdo->query("SELECT * FROM top_pickups ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
$news_list = $pdo->query("SELECT * FROM top_news ORDER BY sort_order ASC, published_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$sales = $pdo->query("SELECT * FROM top_sales ORDER BY sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>トップページ管理 | E-mart 管理</title>
    <link rel="stylesheet" href="/E-mart/asset/css/product.css">
    <style>
        .top-manage-wrap {
            max-width: 1000px;
            margin: 20px auto;
        }

        .top-block {
            background: #fff;
            padding: 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .top-block h2 {
            margin-top: 0;
        }

        table.simple-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.simple-table th,
        table.simple-table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 13px;
        }

        .inline-form input[type="text"],
        .inline-form input[type="number"] {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="top-manage-wrap">
        <h1>トップページ管理</h1>
        <p><a href="/E-mart/src/admin/products/list.php">← 商品管理に戻る</a></p>

        <!-- メインメッセージ -->
        <div class="top-block">
            <h2>メインメッセージ（F）</h2>
            <form method="post">
                <input type="hidden" name="section" value="message">
                <input type="text" name="main_message" value="<?= htmlspecialchars($setting['main_message'] ?? '') ?>"
                    style="width:100%; padding:6px;">
                <button type="submit" class="submit-btn" style="margin-top:8px;">保存</button>
            </form>
        </div>

        <!-- バナー(A) -->
        <div class="top-block">
            <h2>バナー(A)</h2>
            <table class="simple-table">
                <tr>
                    <th>ID</th>
                    <th>タイトル</th>
                    <th>画像パス</th>
                    <th>リンク</th>
                    <th>並び順</th>
                    <th>表示</th>
                    <th>削除</th>
                </tr>
                <?php foreach ($banners as $b): ?>
                    <tr>
                        <td><?= $b['id'] ?></td>
                        <td><?= htmlspecialchars($b['title']) ?></td>
                        <td><?= htmlspecialchars($b['image_path']) ?></td>
                        <td><?= htmlspecialchars($b['link_url']) ?></td>
                        <td><?= $b['sort_order'] ?></td>
                        <td><?= $b['is_active'] ? '✓' : '' ?></td>
                        <td>
                            <form method="post" style="margin:0;" onsubmit="return confirm('削除してよいですか？');">
                                <input type="hidden" name="section" value="banner_delete">
                                <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                <button type="submit" class="delete-btn">削除</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h3>バナー追加 / 編集</h3>
            <form method="post" class="inline-form">
                <input type="hidden" name="section" value="banner">
                <input type="hidden" name="mode" value="create">
                <table class="simple-table">
                    <tr>
                        <th>タイトル</th>
                        <th>画像パス</th>
                        <th>リンクURL</th>
                        <th>並び順</th>
                        <th>表示</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="title"></td>
                        <td><input type="text" name="image_path" placeholder="/E-mart/asset/img/top/banner1.png"></td>
                        <td><input type="text" name="link_url"></td>
                        <td><input type="number" name="sort_order" value="1"></td>
                        <td><input type="checkbox" name="is_active" checked></td>
                    </tr>
                </table>
                <button type="submit" class="submit-btn">追加</button>
            </form>
        </div>

        <!-- ピックアップ商品(B) -->
        <div class="top-block">
            <h2>ピックアップ商品(B)</h2>
            <table class="simple-table">
                <tr>
                    <th>ID</th>
                    <th>商品ID</th>
                    <th>ラベル</th>
                    <th>並び順</th>
                    <th>表示</th>
                    <th>削除</th>
                </tr>
                <?php foreach ($pickups as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= $p['product_id'] ?></td>
                        <td><?= htmlspecialchars($p['label']) ?></td>
                        <td><?= $p['sort_order'] ?></td>
                        <td><?= $p['is_active'] ? '✓' : '' ?></td>
                        <td>
                            <form method="post" style="margin:0;" onsubmit="return confirm('削除してよいですか？');">
                                <input type="hidden" name="section" value="pickup_delete">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="submit" class="delete-btn">削除</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h3>ピックアップ追加</h3>
            <form method="post" class="inline-form">
                <input type="hidden" name="section" value="pickup">
                <input type="hidden" name="mode" value="create">
                <table class="simple-table">
                    <tr>
                        <th>商品ID</th>
                        <th>ラベル(任意)</th>
                        <th>並び順</th>
                        <th>表示</th>
                    </tr>
                    <tr>
                        <td><input type="number" name="product_id"></td>
                        <td><input type="text" name="label"></td>
                        <td><input type="number" name="sort_order" value="1"></td>
                        <td><input type="checkbox" name="is_active" checked></td>
                    </tr>
                </table>
                <button type="submit" class="submit-btn">追加</button>
            </form>
        </div>

        <!-- お知らせ(C) -->
        <div class="top-block">
            <h2>お知らせ(C)</h2>
            <table class="simple-table">
                <tr>
                    <th>ID</th>
                    <th>タイトル</th>
                    <th>本文</th>
                    <th>並び順</th>
                    <th>表示</th>
                    <th>削除</th>
                </tr>
                <?php foreach ($news_list as $n): ?>
                    <tr>
                        <td><?= $n['id'] ?></td>
                        <td><?= htmlspecialchars($n['title']) ?></td>
                        <td style="max-width:300px;"><?= nl2br(htmlspecialchars($n['body'])) ?></td>
                        <td><?= $n['sort_order'] ?></td>
                        <td><?= $n['is_active'] ? '✓' : '' ?></td>
                        <td>
                            <form method="post" style="margin:0;" onsubmit="return confirm('削除してよいですか？');">
                                <input type="hidden" name="section" value="news_delete">
                                <input type="hidden" name="id" value="<?= $n['id'] ?>">
                                <button type="submit" class="delete-btn">削除</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h3>お知らせ追加</h3>
            <form method="post">
                <input type="hidden" name="section" value="news">
                <input type="hidden" name="mode" value="create">
                <table class="simple-table">
                    <tr>
                        <th>タイトル</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="title"></td>
                    </tr>
                    <tr>
                        <th>本文</th>
                    </tr>
                    <tr>
                        <td><textarea name="body" rows="3" style="width:100%;"></textarea></td>
                    </tr>
                    <tr>
                        <th>並び順 / 表示</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="number" name="sort_order" value="1">
                            <label><input type="checkbox" name="is_active" checked> 表示</label>
                        </td>
                    </tr>
                </table>
                <button type="submit" class="submit-btn">追加</button>
            </form>
        </div>

        <!-- セール情報(D) -->
        <div class="top-block">
            <h2>セール情報(D)</h2>
            <table class="simple-table">
                <tr>
                    <th>ID</th>
                    <th>タイトル</th>
                    <th>サブタイトル</th>
                    <th>画像パス</th>
                    <th>リンク</th>
                    <th>順</th>
                    <th>表示</th>
                    <th>削除</th>
                </tr>
                <?php foreach ($sales as $s): ?>
                    <tr>
                        <td><?= $s['id'] ?></td>
                        <td><?= htmlspecialchars($s['title']) ?></td>
                        <td><?= htmlspecialchars($s['subtitle']) ?></td>
                        <td><?= htmlspecialchars($s['image_path']) ?></td>
                        <td><?= htmlspecialchars($s['link_url']) ?></td>
                        <td><?= $s['sort_order'] ?></td>
                        <td><?= $s['is_active'] ? '✓' : '' ?></td>
                        <td>
                            <form method="post" style="margin:0;" onsubmit="return confirm('削除してよいですか？');">
                                <input type="hidden" name="section" value="sale_delete">
                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                <button type="submit" class="delete-btn">削除</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h3>セール情報追加</h3>
            <form method="post">
                <input type="hidden" name="section" value="sale">
                <input type="hidden" name="mode" value="create">
                <table class="simple-table">
                    <tr>
                        <th>タイトル</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="title"></td>
                    </tr>
                    <tr>
                        <th>サブタイトル</th>
                    </tr>
                    <tr>
                        <td><input type="text" name="subtitle"></td>
                    </tr>
                    <tr>
                        <th>画像パス / リンクURL / 並び順 / 表示</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="image_path" placeholder="/E-mart/asset/img/top/sale1.png"><br>
                            <input type="text" name="link_url" placeholder="https://example.com"><br>
                            <input type="number" name="sort_order" value="1">
                            <label><input type="checkbox" name="is_active" checked> 表示</label>
                        </td>
                    </tr>
                </table>
                <button type="submit" class="submit-btn">追加</button>
            </form>
        </div>

    </div>
</body>

</html>