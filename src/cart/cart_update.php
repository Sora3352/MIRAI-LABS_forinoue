<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: /E-mart/src/login/login_input.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$cart_item_id = (int) ($_POST['cart_item_id'] ?? 0);

if ($cart_item_id <= 0) {
    header("Location: /E-mart/src/cart/cart.php");
    exit;
}

$sql = "DELETE FROM cart_items WHERE id = :id AND user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $cart_item_id,
    ':user_id' => $user_id
]);

header("Location: /E-mart/src/cart/cart.php");
exit;
