<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

$is_edit = isset($_GET['id']);
$sale = [];

if ($is_edit) {
    $stmt = $pdo->prepare("SELECT * FROM sale_products WHERE id = :id");
    $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $sale = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 商品一覧取得
$products = $pdo->query("SELECT id, name, price FROM products ORDER BY id DESC")
    ->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="/E-mart/asset/css/sale.css">

<div class="sale-wrapper">
    <h1 class="sale-title"><?= $is_edit ? "セール編集" : "セール追加" ?></h1>

    <form action="/E-mart/src/admin/sale/sale_save.php" method="post" class="sale-form">

        <?php if ($is_edit): ?>
            <input type="hidden" name="id" value="<?= $sale['id'] ?>">
        <?php endif; ?>

        <div class="form-row">
            <label>商品を選択</label>
            <select name="product_id" required>
                <option value="">選択してください</option>
                <?php foreach ($products as $p): ?>
                    <option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>" <?= ($is_edit && $p['id'] == $sale['product_id']) ? "selected" : "" ?>>
                        <?= $p['name'] ?>（定価 ¥<?= number_format($p['price']) ?>）
                    </option>

                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <label>セール価格</label>
            <input type="number" name="sale_price" required value="<?= $is_edit ? $sale['sale_price'] : "" ?>">
        </div>

        <div class="form-row">
            <label>割引率（％）</label>
            <input type="number" name="discount_percent" value="<?= $is_edit ? $sale['discount_percent'] : "" ?>">
        </div>

        <div class="form-row">
            <label>タイムセール</label>
            <select name="is_time_sale">
                <option value="0" <?= (!$is_edit || $sale['is_time_sale'] == 0) ? "selected" : "" ?>>しない</option>
                <option value="1" <?= ($is_edit && $sale['is_time_sale'] == 1) ? "selected" : "" ?>>する</option>
            </select>
        </div>

        <div class="form-row">
            <label>開始日時</label>
            <input type="datetime-local" name="start_at"
                value="<?= $is_edit && $sale['start_at'] ? str_replace(' ', 'T', $sale['start_at']) : "" ?>">
        </div>

        <div class="form-row">
            <label>終了日時</label>
            <input type="datetime-local" name="end_at"
                value="<?= $is_edit && $sale['end_at'] ? str_replace(' ', 'T', $sale['end_at']) : "" ?>">
        </div>

        <div class="form-row">
            <label>表示</label>
            <select name="is_active">
                <option value="1" <?= (!$is_edit || $sale['is_active'] == 1) ? "selected" : "" ?>>ON</option>
                <option value="0" <?= ($is_edit && $sale['is_active'] == 0) ? "selected" : "" ?>>OFF</option>
            </select>
        </div>

        <button class="sale-save-btn" type="submit">保存</button>
    </form>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const productSelect = document.querySelector("select[name='product_id']");
        const salePriceInput = document.querySelector("input[name='sale_price']");
        const discountInput = document.querySelector("input[name='discount_percent']");

        function getRegularPrice() {
            return parseFloat(productSelect.selectedOptions[0]?.dataset.price || 0);
        }

        // ▼ ① セール価格 → 割引率
        function calcDiscount() {
            const price = getRegularPrice();
            const sale = parseFloat(salePriceInput.value || 0);

            if (price > 0 && sale > 0) {
                const discount = Math.round((1 - sale / price) * 100);
                discountInput.value = discount;
            }
        }

        // ▼ ② 割引率 → セール価格
        function calcSalePrice() {
            const price = getRegularPrice();
            const discount = parseFloat(discountInput.value || 0);

            if (price > 0 && discount >= 0 && discount < 100) {
                const sale = Math.round(price * (1 - discount / 100));
                salePriceInput.value = sale;
            }
        }

        // 商品変更 → どっちも再計算
        productSelect.addEventListener("change", function () {
            calcDiscount();
            calcSalePrice();
        });

        // セール価格を変更したら割引率を更新
        salePriceInput.addEventListener("input", calcDiscount);

        // 割引率を変更したらセール価格を更新
        discountInput.addEventListener("input", calcSalePrice);

    });
</script>