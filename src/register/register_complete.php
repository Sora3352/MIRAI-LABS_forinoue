<?php
session_start();
include_once("../../components/header.php");
?>

<link rel="stylesheet" href="/E-mart/asset/css/form.css">

<style>
    .complete-box {
        max-width: 700px;
        margin: 60px auto;
        background: #fff;
        padding: 40px 50px;
        border-radius: 8px;
        border: 1px solid #ddd;
        text-align: center;
    }

    .complete-box h2 {
        font-size: 1.6rem;
        margin-bottom: 20px;
        font-weight: bold;
        color: #333;
    }

    .complete-box p {
        font-size: 1rem;
        color: #444;
        margin-bottom: 25px;
        line-height: 1.7;
    }

    .success-icon {
        width: 70px;
        height: 70px;
        margin-bottom: 25px;
    }

    .complete-btn {
        display: inline-block;
        padding: 12px 40px;
        background: #333;
        color: #fff;
        border-radius: 6px;
        text-decoration: none;
        font-size: 1rem;
        transition: 0.2s;
    }

    .complete-btn:hover {
        background: #555;
    }
</style>


<div class="complete-box">

    <!-- 成功アイコン -->
    <svg class="success-icon" viewBox="0 0 24 24" fill="#4CAF50">
        <path d="M20.285 6.709l-11.03 11.03-5.54-5.54L5.116 10l4.14 4.14 9.03-9.03z" />
    </svg>

    <h2>会員登録が完了しました</h2>

    <p>
        E-martをご利用いただきありがとうございます。<br>
        ご登録のメールアドレスとパスワードでログインできるようになりました。
    </p>

    <a href="/E-mart/src/login/login_input.php" class="complete-btn">ログイン画面へ</a>
</div>


<?php include_once("../../components/footer.php"); ?>