<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* ---------------------------------------------------
   🔐 ログインチェック
--------------------------------------------------- */
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: /E-mart/src/login/login_input.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
$qty = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

if ($product_id <= 0) {
    header("Location: /E-mart/src/product/list.php");
    exit;
}

try {

    /* ============================
       0) 現在の正しい価格（final_price）を取得
    ============================ */

    $sql = "
        SELECT 
            p.price,
            s.sale_price,
            s.start_at,
            s.end_at,
            CASE
                WHEN s.sale_price IS NOT NULL
                     AND s.start_at <= NOW()
                     AND s.end_at >= NOW()
                THEN s.sale_price
                ELSE p.price
            END AS final_price
        FROM products p
        LEFT JOIN sale_products s ON s.product_id = p.id
        WHERE p.id = :pid
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':pid' => $product_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        die("商品が存在しません");
    }

    $price_snapshot = (int) $row['final_price'];



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
       2) cart_items に user_id カラムがあるか確認
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
       3) 同じ商品がカートに入っているか確認
    ============================ */
    if ($has_user_id) {
        $checkSql = "
            SELECT id, quantity, price_snapshot
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
            SELECT id, quantity, price_snapshot
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
       4) UPDATE か INSERT
    ============================ */
    if ($existing) {

        // 既存アイテム → 数量 + (投稿数量)
        $up = $pdo->prepare("
            UPDATE cart_items
            SET quantity = quantity + :qty,
                updated_at = NOW()
            WHERE id = :id
        ");
        $up->execute([
            ':qty' => $qty,
            ':id' => $existing['id']
        ]);

    } else {

        // 新規追加 → price_snapshot を保存
        if ($has_user_id) {
            $insSql = "
                INSERT INTO cart_items
                    (cart_id, user_id, product_id, quantity, price_snapshot, created_at, updated_at)
                VALUES
                    (:cart_id, :user_id, :product_id, :qty, :price_snapshot, NOW(), NOW())
            ";
            $insParams = [
                ':cart_id' => $cart_id,
                ':user_id' => $user_id,
                ':product_id' => $product_id,
                ':qty' => $qty,
                ':price_snapshot' => $price_snapshot
            ];
        } else {
            $insSql = "
                INSERT INTO cart_items
                    (cart_id, product_id, quantity, price_snapshot, created_at, updated_at)
                VALUES
                    (:cart_id, :product_id, :qty, :price_snapshot, NOW(), NOW())
            ";
            $insParams = [
                ':cart_id' => $cart_id,
                ':product_id' => $product_id,
                ':qty' => $qty,
                ':price_snapshot' => $price_snapshot
            ];
        }

        $ins = $pdo->prepare($insSql);
        $ins->execute($insParams);
    }


    /* ============================
       カートへ遷移
    ============================ */
    header("Location: /E-mart/src/cart/cart.php");
    exit;

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
