<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("不正なIDです");
}

$id = (int) $_GET['id'];

// カテゴリ名取得
$sql = "SELECT name FROM categories WHERE id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$cat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cat) {
    die("カテゴリが存在しません");
}
$name = $cat['name'];

// ===== 商品チェック =====
$check_sql = "SELECT COUNT(*) FROM products WHERE category_id = :id";
$check = $pdo->prepare($check_sql);
$check->bindValue(":id", $id, PDO::PARAM_INT);
$check->execute();

if ($check->fetchColumn() > 0) {
    die("このカテゴリに商品が残っているため削除できません。");
}

// ===== DB削除 =====
$del = $pdo->prepare("DELETE FROM categories WHERE id = :id");
$del->bindValue(":id", $id, PDO::PARAM_INT);
$del->execute();

// ===== フォルダ削除 =====
$dir = $_SERVER['DOCUMENT_ROOT'] . "/E-mart/asset/img/products/" . $name;

if (file_exists($dir)) {
    rmdir($dir); // 中身が空なら削除できる
}

header("Location: /E-mart/src/admin/categories/list.php");
exit;
