<?php
session_start();
require_once("../../asset/php/db_connect.php");

// 管理者ログインチェック
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/admin_breadcrumb_auto.php');
// 管理者一覧取得
$stmt = $pdo->query("SELECT id, name, email FROM admins ORDER BY id ASC");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="/E-mart/asset/css/admin.css">

<div class="admin-list-wrapper">

    <h2 class="form-title">管理者一覧</h2>

    <a href="admin_add.php" class="add-btn">＋ 管理者を追加</a>

    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>名前</th>
            <th>メールアドレス</th>
        </tr>

        <?php foreach ($admins as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= htmlspecialchars($a['name']) ?></td>
                <td><?= htmlspecialchars($a['email']) ?></td>
            </tr>
        <?php endforeach; ?>

    </table>

</div>

