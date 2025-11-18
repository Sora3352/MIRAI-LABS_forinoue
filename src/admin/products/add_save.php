<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===== 入力データ取得 =====
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = trim($_POST['price'] ?? '');
$stock = trim($_POST['stock'] ?? '');
$category_id = trim($_POST['category_id'] ?? '');
$image_url = null;

// 必須チェック
if ($name === '' || $price === '' || $stock === '' || $category_id === '') {
    die("必須項目が入力されていません");
}

// ===== カテゴリ名取得（フォルダ名用） =====
$cat_sql = "SELECT name FROM categories WHERE id = :id LIMIT 1";
$cat_stmt = $pdo->prepare($cat_sql);
$cat_stmt->bindValue(":id", $category_id, PDO::PARAM_INT);
$cat_stmt->execute();
$cat = $cat_stmt->fetch(PDO::FETCH_ASSOC);

if (!$cat) {
    die("カテゴリが存在しません");
}

$category_name = $cat['name']; // 日本語フォルダ名 OK

// 保存先フォルダ
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/E-mart/asset/img/products/" . $category_name . "/";

// フォルダが無ければ作成
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// ===== 画像アップロード処理 =====
if (!empty($_FILES['image']['name'])) {

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($ext, $allowed_ext)) {
        die("画像形式が不正です（jpg, png, gifのみ）");
    }

    // ファイル名をユニーク生成
    $new_filename = "img_" . date("Ymd_His") . "_" . bin2hex(random_bytes(4)) . "." . $ext;

    $save_path = $upload_dir . $new_filename;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $save_path)) {
        die("画像の保存に失敗しました");
    }

    // DB に保存するのはファイル名のみ
    $image_url = $new_filename;
}

// ===== DB登録 =====
$sql = "
    INSERT INTO products 
        (name, description, price, stock, category_id, image_url, created_at, updated_at)
    VALUES
        (:name, :description, :price, :stock, :category_id, :image_url, NOW(), NOW())
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':description', $description, PDO::PARAM_STR);
$stmt->bindValue(':price', $price, PDO::PARAM_INT);
$stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
$stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
$stmt->bindValue(':image_url', $image_url, PDO::PARAM_STR);

$stmt->execute();

// ===== 完了後リストへ =====
header("Location: /E-mart/src/admin/products/list.php");
exit();
