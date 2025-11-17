<?php
session_start();

// すでに管理者ログイン中ならDashboardへ
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_menu.php");
    exit;
}

include_once("../../components/header.php");

// エラー受け取り
$error = $_SESSION['admin_login_error'] ?? "";
unset($_SESSION['admin_login_error']);
?>

<link rel="stylesheet" href="/E-mart/asset/css/admin_login.css">

<div class="admin-login-wrapper">

    <div class="admin-login-box">

        <h2 class="admin-login-title">管理者ログイン</h2>

        <?php if ($error): ?>
            <p class="admin-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="admin_login_check.php" method="post">

            <div class="admin-input-group">
                <label>メールアドレス</label>
                <input type="email" name="email" required>
            </div>

            <div class="admin-input-group">
                <label>パスワード</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="admin-login-btn">ログイン</button>

        </form>

    </div>
</div>

<?php include_once("../../components/footer.php"); ?>