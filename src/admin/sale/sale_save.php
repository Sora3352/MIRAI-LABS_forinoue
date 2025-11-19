<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

// POSTデータ取得
$id = $_POST['id'] ?? null;
$product_id = $_POST['product_id'];
$sale_price = $_POST['sale_price'];
$discount_percent = $_POST['discount_percent'];
$is_time_sale = $_POST['is_time_sale'];
$start_at = $_POST['start_at'] ?: null;
$end_at = $_POST['end_at'] ?: null;
$is_active = $_POST['is_active'];

// 定価取得
$stmt = $pdo->prepare("SELECT price FROM products WHERE id = :id");
$stmt->bindValue(':id', $product_id);
$stmt->execute();
$original_price = $stmt->fetchColumn();

// SQL分岐
if ($id) {
    // 更新
    $sql = "
        UPDATE sale_products SET
            product_id = :product_id,
            sale_price = :sale_price,
            old_price = :old_price,
            discount_percent = :discount,
            is_time_sale = :is_time_sale,
            start_at = :start_at,
            end_at = :end_at,
            is_active = :is_active
        WHERE id = :id
    ";
} else {
    // 新規追加
    $sql = "
        INSERT INTO sale_products (
            product_id, sale_price, old_price, discount_percent,
            is_time_sale, start_at, end_at, is_active
        ) VALUES (
            :product_id, :sale_price, :old_price, :discount,
            :is_time_sale, :start_at, :end_at, :is_active
        )
    ";
}

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':product_id', $product_id);
$stmt->bindValue(':sale_price', $sale_price);
$stmt->bindValue(':old_price', $original_price);
$stmt->bindValue(':discount', $discount_percent);
$stmt->bindValue(':is_time_sale', $is_time_sale);
$stmt->bindValue(':start_at', $start_at);
$stmt->bindValue(':end_at', $end_at);
$stmt->bindValue(':is_active', $is_active);

if ($id) {
    $stmt->bindValue(':id', $id);
}

$stmt->execute();

header("Location: /E-mart/src/admin/sale/sale_list.php");
exit;
