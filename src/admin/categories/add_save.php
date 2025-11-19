<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

if (empty($_POST['name'])) {
    die("カテゴリ名がありません");
}

$name = trim($_POST['name']);

// ===== DB追加 =====
$sql = "INSERT INTO categories (name) VALUES (:name)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":name", $name, PDO::PARAM_STR);
$stmt->execute();

// ===== 画像フォルダ作成 =====
$dir = $_SERVER['DOCUMENT_ROOT'] . "/E-mart/asset/img/products/" . $name;
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

header("Location: /E-mart/src/admin/categories/list.php");
exit;
