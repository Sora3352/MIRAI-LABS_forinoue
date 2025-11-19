<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===== 入力値 =====
$id = (int) ($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = trim($_POST['price'] ?? '');
$stock = trim($_POST['stock'] ?? '');
$category_id = trim($_POST['category_id'] ?? '');

// 必須チェック
if (!$id || $name === '' || $price === '' || $stock === '' || $category_id === '') {
    die("必須項目が不足しています。");
}

// ===== 元の商品情報取得 =====
$sql = "
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.id = :id
    LIMIT 1
";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$old = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$old) {
    die("商品が存在しません。");
}

$old_image = $old['image_url'];
$old_category_id = $old['category_id'];
$old_catname = $old['category_name'];


// ===== 新カテゴリ名の取得 =====
$cat_sql = "SELECT name FROM categories WHERE id = :id LIMIT 1";
$cat_stmt = $pdo->prepare($cat_sql);
$cat_stmt->bindValue(":id", $category_id, PDO::PARAM_INT);
$cat_stmt->execute();
$cat = $cat_stmt->fetch(PDO::FETCH_ASSOC);

if (!$cat) {
    die("カテゴリが見つかりません。");
}

$new_catname = $cat['name'];


// ===== パス設定 =====
$old_folder = $_SERVER['DOCUMENT_ROOT'] . "/E-mart/asset/img/products/" . $old_catname . "/";
$new_folder = $_SERVER['DOCUMENT_ROOT'] . "/E-mart/asset/img/products/" . $new_catname . "/";

// 新カテゴリフォルダが無ければ作成
if (!is_dir($new_folder)) {
    mkdir($new_folder, 0777, true);
}

$new_image_url = $old_image; // 基本は元画像を保持


// ================================
// 画像アップロードあり？
// ================================
if (!empty($_FILES['image']['name'])) {

    // 拡張子チェック
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($ext, $allowed_ext)) {
        die("アップロードできる画像は jpg / png / gif のみです。");
    }

    // 新しいファイル名
    $new_filename = "img_" . date("Ymd_His") . "_" . bin2hex(random_bytes(4)) . "." . $ext;

    $new_path = $new_folder . $new_filename;

    // アップロード
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $new_path)) {
        die("画像アップロードに失敗しました。");
    }

    // 元画像があれば削除（古カテゴリ・新カテゴリ問わず）
    if ($old_image) {
        $old_path = $old_folder . $old_image;
        if (file_exists($old_path))
            unlink($old_path);
    }

    $new_image_url = $new_filename;
}

// ================================
// カテゴリ変更されたが画像アップロードがない場合
// → 元画像を新フォルダに移動
// ================================
if ($old_category_id != $category_id && $old_image && empty($_FILES['image']['name'])) {
    $old_path = $old_folder . $old_image;
    $new_path = $new_folder . $old_image;

    if (file_exists($old_path)) {
        rename($old_path, $new_path); // フォルダ移動
    }
}


// ===== DB更新 =====
$update_sql = "
    UPDATE products
    SET name = :name,
        description = :description,
        price = :price,
        stock = :stock,
        category_id = :category_id,
        image_url = :image_url,
        updated_at = NOW()
    WHERE id = :id
";

$update = $pdo->prepare($update_sql);
$update->bindValue(':name', $name, PDO::PARAM_STR);
$update->bindValue(':description', $description, PDO::PARAM_STR);
$update->bindValue(':price', $price, PDO::PARAM_INT);
$update->bindValue(':stock', $stock, PDO::PARAM_INT);
$update->bindValue(':category_id', $category_id, PDO::PARAM_INT);
$update->bindValue(':image_url', $new_image_url, PDO::PARAM_STR);
$update->bindValue(':id', $id, PDO::PARAM_INT);

$update->execute();


// ===== 完了後リダイレクト =====
$return_category = $_POST['return_category'] ?? '';
$return_keyword = $_GET['keyword'] ?? ''; // 必要ならキーワードも保持

$query = "?category=" . urlencode($return_category);

header("Location: /E-mart/src/admin/products/list.php" . $query);
exit();
