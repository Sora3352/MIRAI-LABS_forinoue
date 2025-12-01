<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = "/E-mart/src/order/checkout.php";
    header("Location: /E-mart/src/login/login_input.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

try {
    $pdo->beginTransaction();

    /* ===============================
       1. デフォルト住所の取得
    ================================ */
    $sql_addr = "
        SELECT *
        FROM addresses
        WHERE user_id = :user_id
          AND is_default = 1
        LIMIT 1
    ";
    $stmt_addr = $pdo->prepare($sql_addr);
    $stmt_addr->execute([':user_id' => $user_id]);
    $address = $stmt_addr->fetch(PDO::FETCH_ASSOC);

    if (!$address) {
        throw new Exception("配送先住所が登録されていません。");
    }

    /* ===============================
       2. カート内容を取得（★price_snapshot必須）
    ================================ */
    $sql_cart = "
        SELECT 
            ci.id AS cart_item_id,
            ci.quantity,
            ci.price_snapshot,    -- ★セール価格含む確定価格
            p.id AS product_id,
            p.name,
            p.stock
        FROM cart_items ci
        INNER JOIN products p ON ci.product_id = p.id
        WHERE ci.user_id = :user_id
        FOR UPDATE
    ";
    $stmt_cart = $pdo->prepare($sql_cart);
    $stmt_cart->execute([':user_id' => $user_id]);
    $items = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

    if (!$items) {
        throw new Exception("カートが空です。");
    }

    /* ===============================
       3. 小計の計算（★snapshotを使用）
    ================================ */
    $total_price = 0;
    foreach ($items as $it) {
        $total_price += ((int) $it['price_snapshot']) * ((int) $it['quantity']);
    }

    /* ===============================
       4. orders に登録
    ================================ */
    $sql_order = "
        INSERT INTO orders
            (user_id, address_id, payment_method_id, total_price, status, created_at, updated_at)
        VALUES
            (:user_id, :address_id, 1, :total_price, 'pending', NOW(), NOW())
    ";

    $stmt_order = $pdo->prepare($sql_order);
    $stmt_order->execute([
        ':user_id' => $user_id,
        ':address_id' => $address['address_id'],
        ':total_price' => $total_price
    ]);

    $order_id = (int) $pdo->lastInsertId();

    /* ===============================
       5. order_items に登録 + 在庫更新
    ================================ */
    $sql_item = "
        INSERT INTO order_items
            (order_id, product_id, quantity, price)
        VALUES
            (:order_id, :product_id, :quantity, :price)
    ";
    $stmt_item = $pdo->prepare($sql_item);

    $sql_stock = "
        UPDATE products
        SET stock = stock - :qty
        WHERE id = :pid
    ";
    $stmt_stock = $pdo->prepare($sql_stock);

    foreach ($items as $it) {

        // 在庫チェック
        if ($it['stock'] < $it['quantity']) {
            throw new Exception("在庫不足の商品があります：" . $it['name']);
        }

        // ★order_items もセール価格で登録
        $stmt_item->execute([
            ':order_id' => $order_id,
            ':product_id' => $it['product_id'],
            ':quantity' => $it['quantity'],
            ':price' => $it['price_snapshot']   // ★修正点
        ]);

        // 在庫を減らす
        $stmt_stock->execute([
            ':qty' => $it['quantity'],
            ':pid' => $it['product_id']
        ]);
    }

    /* ===============================
       6. カート削除
    ================================ */
    $sql_delete = "DELETE FROM cart_items WHERE user_id = :user_id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([':user_id' => $user_id]);

    $pdo->commit();

    /* ===============================
       7. 完了画面へ
    ================================ */
    header("Location: /E-mart/src/order/order_complete.php?order_id={$order_id}");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("注文処理エラー: " . $e->getMessage());
}
