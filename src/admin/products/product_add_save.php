<?php
require_once("../../components/admin_login_check.php");
require_once("../../asset/php/db_connect.php");

$name = trim($_POST['name']);
$price = floatval($_POST['price']);
$stock = intval($_POST['stock']);
$description = trim($_POST['description']);
$category_id = $_POST['category_id'] !== "" ? intval($_POST['category_id']) : null;

if ($name === "" || $price <= 0 || $stock < 0) {
    die("入力エラーがあります。");
}

// --------------------------
// 画像アップロード処理
// --------------------------
$image_path = null;

if (!empty($_FILES['image']['name'])) {
    $filename = date("YmdHis") . "_" . basename($_FILES['image']['name']);
    $target = "../../uploads/products/" . $filename;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        die("画像アップロードに失敗しました");
    }

    $image_path = "/uploads/products/" . $filename;
}

// --------------------------
// DB登録
// --------------------------
$sql = "INSERT INTO products (name, description, price, stock, image_url, category_id)
        VALUES (:name, :description, :price, :stock, :image_url, :category_id)";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name);
$stmt->bindValue(':description', $description);
$stmt->bindValue(':price', $price);
$stmt->bindValue(':stock', $stock);
$stmt->bindValue(':image_url', $image_path);
$stmt->bindValue(':category_id', $category_id);

$stmt->execute();

header("Location: product_list.php");
exit;
