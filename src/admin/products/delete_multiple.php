<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// チェックが空
if (empty($_POST['ids']) || !is_array($_POST['ids'])) {
    header("Location: list.php");
    exit;
}

$ids = $_POST['ids'];

// ===== 件数分 ? を作成 =====
$placeholders = implode(",", array_fill(0, count($ids), "?"));

// ===== 対象商品データ取得（画像削除のため） =====
$sql = "
    SELECT p.id, p.image_url, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.id IN ($placeholders)
";

$stmt = $pdo->prepare($sql);

foreach ($ids as $i => $id) {
    $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
}

$stmt->execute();
$targets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ===== 画像削除 =====
foreach ($targets as $p) {
    if ($p['image_url']) {
        $img_path = $_SERVER['DOCUMENT_ROOT']
            . "/E-mart/asset/img/products/"
            . $p['category_name'] . "/"
            . $p['image_url'];

        if (file_exists($img_path)) {
            unlink($img_path);
        }
    }
}

// ===== DB 削除 =====
$del_sql = "
    DELETE FROM products
    WHERE id IN ($placeholders)
";

$del_stmt = $pdo->prepare($del_sql);

foreach ($ids as $i => $id) {
    $del_stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
}

$del_stmt->execute();

// 完了→一覧へ
header("Location: /E-mart/src/admin/products/list.php");
exit;
