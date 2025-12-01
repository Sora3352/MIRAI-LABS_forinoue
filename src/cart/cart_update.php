<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

// ログイン確認
if (!isset($_SESSION['user_id'])) {
    header("Location: /E-mart/src/login/login_input.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$cart_item_id = $_POST['cart_item_id'] ?? 0;
$quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

if ($cart_item_id <= 0) {
    header("Location: /E-mart/src/cart/cart.php");
    exit;
}

// カート商品の情報を取得（在庫確認のため）
$sql = "
    SELECT 
        ci.id,
        ci.product_id,
        p.stock
    FROM cart_items ci
    INNER JOIN products p ON ci.product_id = p.id
    WHERE ci.id = :id AND ci.user_id = :user_id
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $cart_item_id,
    ':user_id' => $user_id
]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// 無い or 他人のカート → 追い返す
if (!$item) {
    header("Location: /E-mart/src/cart/cart.php");
    exit;
}

$stock = (int) $item['stock'];

// 在庫チェック
if ($quantity < 1) {
    $quantity = 1;
}
if ($quantity > $stock) {
    // 在庫以上にしようとした → 在庫最大に強制
    $quantity = $stock;

    // セッションへ警告メッセージ格納（cart.php で使える）
    $_SESSION['cart_error'] = "在庫数より多くは選択できません（在庫：$stock 個）";
}

// 更新
$update = $pdo->prepare("
    UPDATE cart_items
    SET quantity = :quantity, updated_at = NOW()
    WHERE id = :id AND user_id = :user_id
");
$update->execute([
    ':quantity' => $quantity,
    ':id' => $cart_item_id,
    ':user_id' => $user_id
]);

header("Location: /E-mart/src/cart/cart.php");
exit;
