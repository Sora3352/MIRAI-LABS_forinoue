<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

// セール一覧取得
$sql = "
    SELECT 
        s.id AS sale_id,
        p.id AS product_id,
        p.name,
        p.price AS original_price,
        s.sale_price,
        s.discount_percent,
        s.is_time_sale,
        s.start_at,
        s.end_at,
        s.is_active
    FROM sale_products s
    INNER JOIN products p ON p.id = s.product_id
    ORDER BY s.updated_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="/E-mart/asset/css/sale.css">

<div class="sale-wrapper">

    <h1 class="sale-title">セール管理</h1>

    <a href="/E-mart/src/admin/sale/sale_edit.php" class="sale-add-btn">＋ セールを追加</a>

    <table class="sale-table">
        <tr>
            <th>ID</th>
            <th>商品名</th>
            <th>定価</th>
            <th>セール価格</th>
            <th>割引率</th>
            <th>タイムセール</th>
            <th>期間</th>
            <th>表示</th>
            <th>編集</th>
            <th>解除</th>
        </tr>

        <?php foreach ($sales as $s): ?>
            <tr class="sale-row">
                <td><?= $s['sale_id'] ?></td>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td>¥<?= number_format($s['original_price']) ?></td>
                <td>¥<?= number_format($s['sale_price']) ?></td>

                <td class="sale-discount">
                    <?= $s['discount_percent'] ?>%
                </td>

                <td>
                    <?php if ($s['is_time_sale']): ?>
                        <span class="sale-timer">タイムセール</span>
                    <?php else: ?>
                        ―
                    <?php endif; ?>
                </td>

                <td>
                    <?php if ($s['start_at']): ?>
                        <?= $s['start_at'] ?><br>〜 <?= $s['end_at'] ?>
                    <?php else: ?>
                        ―
                    <?php endif; ?>
                </td>

                <td>
                    <?php if ($s['is_active']): ?>
                        <a href="/E-mart/src/admin/sale/sale_toggle.php?id=<?= $s['sale_id'] ?>&active=0"
                            class="sale-btn-sm">ON</a>
                    <?php else: ?>
                        <a href="/E-mart/src/admin/sale/sale_toggle.php?id=<?= $s['sale_id'] ?>&active=1"
                            class="sale-btn-sm gray">OFF</a>
                    <?php endif; ?>
                </td>

                <td>
                    <a href="/E-mart/src/admin/sale/sale_edit.php?id=<?= $s['sale_id'] ?>" class="sale-btn-sm">編集</a>
                </td>

                <td>
                    <a href="/E-mart/src/admin/sale/sale_delete.php?id=<?= $s['sale_id'] ?>" class="sale-btn-sm delete"
                        onclick="return confirm('セールを解除しますか？');">解除</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>