<?php
session_start();

// 管理者ログインチェック
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/admin_breadcrumb_auto.php'); ?>

<link rel="stylesheet" href="/E-mart/asset/css/form.css">

<div class="admin-wrapper">

    <h2 class="form-title">管理者の追加</h2>

    <?php if (!empty($_SESSION['admin_add_error'])): ?>
        <p class="error-message"><?= htmlspecialchars($_SESSION['admin_add_error']) ?></p>
        <?php unset($_SESSION['admin_add_error']); ?>
    <?php endif; ?>

    <form action="admin_add_save.php" method="post">

        <div class="form-section">

            <div class="form-row">
                <label class="required">名前</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-row">
                <label class="required">メールアドレス</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-row">
                <label class="required">パスワード</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-row">
                <label class="required">パスワード（再入力）</label>
                <input type="password" name="password_confirm" required>
            </div>

        </div>

        <div class="form-submit">
            <button type="submit" class="submit-btn">登録する</button>
        </div>

    </form>

    <div class="back-link">
        <a href="admin_list.php">← 管理者一覧へ戻る</a>
    </div>
</div>

