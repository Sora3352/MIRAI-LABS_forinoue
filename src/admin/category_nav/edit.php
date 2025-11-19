<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM top_category_nav WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "データが見つかりません。";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>カテゴリ編集 | 管理画面</title>
    <link rel="stylesheet" href="/E-mart/asset/css/admin.css">
</head>

<body>

    <h1>カテゴリ編集</h1>

    <div class="form-box">

        <form action="save.php" method="post">

            <input type="hidden" name="mode" value="edit">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">

            <div class="input-group">
                <label>表示名（label）</label>
                <input type="text" name="label" value="<?= htmlspecialchars($data['label']) ?>" required>
            </div>

            <div class="input-group">
                <label>リンク先URL（link_url）</label>
                <input type="text" name="link_url" value="<?= htmlspecialchars($data['link_url']) ?>">
            </div>

            <div class="input-group">
                <label>表示ステータス</label>
                <select name="is_active">
                    <option value="1" <?= $data['is_active'] ? 'selected' : '' ?>>表示</option>
                    <option value="0" <?= !$data['is_active'] ? 'selected' : '' ?>>非表示</option>
                </select>
            </div>

            <button type="submit" class="btn">保存する</button>

        </form>

        <br>
        <a href="list.php">← 戻る</a>

    </div>

</body>

</html>