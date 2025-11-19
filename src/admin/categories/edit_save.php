<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

if (!isset($_POST['id']) || !isset($_POST['name'])) {
    die("不正な入力");
}

$id = (int) $_POST['id'];
$new_name = trim($_POST['name']);

// 旧カテゴリ名取得
$sql = "SELECT name FROM categories WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$old = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$old) {
    die("カテゴリが存在しません");
}

$old_name = $old['name'];

// ===== DB更新 =====
$update = $pdo->prepare("UPDATE categories SET name = :name WHERE id = :id");
$update->bindValue(":name", $new_name, PDO::PARAM_STR);
$update->bindValue(":id", $id, PDO::PARAM_INT);
$update->execute();

// ===== フォルダ名変更 =====
$old_dir = $_SERVER['DOCUMENT_ROOT'] . "/E-mart/asset/img/products/" . $old_name;
$new_dir = $_SERVER['DOCUMENT_ROOT'] . "/E-mart/asset/img/products/" . $new_name;

if (file_exists($old_dir)) {
    rename($old_dir, $new_dir);
}

header("Location: /E-mart/src/admin/categories/list.php");
exit;
