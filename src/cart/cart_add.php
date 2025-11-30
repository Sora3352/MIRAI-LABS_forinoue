<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* ---------------------------------------------------
   🔐 ログインチェック
   ログインしてなければ今のURLを保存してログインへ
--------------------------------------------------- */
if (!isset($_SESSION['user_id'])) {

    // ★カート追加しようとした元のURLを保存
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];

    header("Location: /E-mart/src/login/login_input.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;

if ($product_id <= 0) {
    header("Location: /E-mart/src/product/list.php");
    exit;
}

try {

    /* ============================
       1) cart 取得 or 作成
    ============================ */
    $stmt = $pdo->prepare("SELECT id FROM cart WHERE user_id = :user_id LIMIT 1");
    $stmt->execute([':user_id' => $user_id]);
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart) {
        $cart_id = (int) $cart['id'];
    } else {
        $ins = $pdo->prepare("
            INSERT INTO cart (user_id, created_at, updated_at)
            VALUES (:user_id, NOW(), NOW())
        ");
        $ins->execute([':user_id' => $user_id]);
        $cart_id = (int) $pdo->lastInsertId();
    }

    /* ============================
       2) cart_items に user_id があるか確認
    ============================ */
    $colStmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'cart_items'
          AND COLUMN_NAME = 'user_id'
    ");
    $colStmt->execute();
    $has_user_id = (int) $colStmt->fetchColumn() > 0;

    /* ============================
       3) 既に同じ商品が入ってるか
    ============================ */
    if ($has_user_id) {
        $checkSql = "
            SELECT id, quantity
            FROM cart_items
            WHERE cart_id = :cart_id
              AND user_id = :user_id
              AND product_id = :product_id
            LIMIT 1
        ";
        $checkParams = [
            ':cart_id' => $cart_id,
            ':user_id' => $user_id,
            ':product_id' => $product_id
        ];
    } else {
        $checkSql = "
            SELECT id, quantity
            FROM cart_items
            WHERE cart_id = :cart_id
              AND product_id = :product_id
            LIMIT 1
        ";
        $checkParams = [
            ':cart_id' => $cart_id,
            ':product_id' => $product_id
        ];
    }

    $stmt = $pdo->prepare($checkSql);
    $stmt->execute($checkParams);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    /* ============================
       4) UPDATE or INSERT
    ============================ */
    if ($existing) {

        $up = $pdo->prepare("
            UPDATE cart_items
            SET quantity = quantity + 1,
                updated_at = NOW()
            WHERE id = :id
        ");
        $up->execute([':id' => $existing['id']]);

    } else {

        if ($has_user_id) {
            $insSql = "
                INSERT INTO cart_items
                    (cart_id, user_id, product_id, quantity, price_snapshot, created_at, updated_at)
                VALUES
                    (:cart_id, :user_id, :product_id, 1, NULL, NOW(), NOW())
            ";
            $insParams = [
                ':cart_id' => $cart_id,
                ':user_id' => $user_id,
                ':product_id' => $product_id
            ];
        } else {
            $insSql = "
                INSERT INTO cart_items
                    (cart_id, product_id, quantity, price_snapshot, created_at, updated_at)
                VALUES
                    (:cart_id, :product_id, 1, NULL, NOW(), NOW())
            ";
            $insParams = [
                ':cart_id' => $cart_id,
                ':product_id' => $product_id
            ];
        }

        $ins = $pdo->prepare($insSql);
        $ins->execute($insParams);
    }

    // 正常 → カートへ移動
    header("Location: /E-mart/src/cart/cart.php");
    exit;

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
