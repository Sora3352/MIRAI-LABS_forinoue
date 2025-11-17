<?php
session_start();
include_once("../../components/header.php");

// エラー受け取り
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>

<link rel="stylesheet" href="/E-mart/asset/css/login.css">

<div class="login-container">

    <!-- 左：ログイン -->
    <div class="login-box">

        <h2 class="login-title">ログイン</h2>

        <?php if ($error): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="login_check.php" method="post">

            <div class="input-group">
                <label>ユーザー名</label>
                <input type="email" name="email" placeholder="例：user@example.com" required>
            </div>

            <div class="input-group">
                <label>パスワード</label>
                <input type="password" name="password" placeholder="パスワード" required>
            </div>

            <button type="submit" class="login-btn">ログイン</button>

        </form>
    </div>

    <!-- 右：新規登録 -->
    <div class="register-box">
        <h3 class="register-title">はじめてご利用の方はこちらに</h3>

        <a href="/E-mart/src/register/register_input.php" class="register-btn">
            新規会員登録
        </a>
    </div>

</div>

<?php include_once("../../components/footer.php"); ?>