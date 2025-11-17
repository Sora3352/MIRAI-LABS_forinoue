<?php
require_once("../../components/admin_login_check.php");
require_once("../../asset/php/db_connect.php");

$id = intval($_POST['id']);
$name = trim($_POST['name']);
$price = floatval($_POST['price']);
$stock = intval($_POST['stock']);
$description = trim($_POST['description']);
$category_id = $_POST['category_id'] !== "" ? intval($_POST['category_id']) : null;

// --- 既存商品取得
$stmt = $pdo->prepare("SELECT image_url FROM products WHERE id = :id");
$stmt->bindValue(":id", $id);
$stmt->execute();
$old = $stmt->fetch();

$image_url = $old['image_url'];

// --- 新規画像アップロード
if (!empty($_FILES['image']['name'])) {
    $filename = date("YmdHis") . "_" . basename($_FILES['image']['name']);
    $target = "../../uploads/products/" . $filename;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $image_url = "/uploads/products/" . $filename;
    }
}

// --- 更新SQL
$sql = "
UPDATE products SET
    name = :name,
    description = :description,
    price = :price,
    stock = :stock,
    image_url = :image_url,
    category_id = :category_id,
    updated_at = NOW()
WHERE id = :id
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(":name", $name);
$stmt->bindValue(":description", $description);
$stmt->bindValue(":price", $price);
$stmt->bindValue(":stock", $stock);
$stmt->bindValue(":image_url", $image_url);
$stmt->bindValue(":category_id", $category_id);
$stmt->bindValue(":id", $id);
$stmt->execute();

header("Location: product_list.php");
exit;
