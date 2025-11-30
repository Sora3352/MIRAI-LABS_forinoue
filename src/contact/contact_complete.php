
<main class="contact-complete-wrapper">

    <!-- 上部ヘッダー部分（ロゴ＋戻るボタン） -->
    <div class="contact-header">
        <a href="/E-mart/src/index.php" class="logo-area">
            <img src="/E-mart/asset/img/logo.png" class="contact-logo">
        </a>

        <a href="/E-mart/src/index.php" class="store-btn">
            E-MARTストアへ
        </a>
    </div>

    <!-- 完了メッセージ -->
    <div class="complete-box">
        <h1>お問い合わせ完了</h1>
        <p>
            お問い合わせが正常に送信されました。<br>
            内容を確認後、メールにてご連絡いたします。<br>
            しばらくお待ちください。
        </p>

        <a class="back-home" href="/E-mart/src/index.php">トップページに戻る</a>
    </div>

</main>

<?php include_once("../../components/footer.php"); ?>

<!-- CSS -->
<style>
    .contact-complete-wrapper {
        max-width: 700px;
        margin: 0 auto;
        padding: 30px 20px;
        font-family: "Noto Sans JP", sans-serif;
    }

    /* ヘッダー（ロゴ＋ストアボタン） */
    .contact-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .contact-logo {
        width: 120px;
    }

    .store-btn {
        background: #ff9800;
        padding: 10px 18px;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    /* 完了メッセージ */
    .complete-box {
        text-align: center;
        margin-top: 50px;
    }

    .complete-box h1 {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .complete-box p {
        color: #444;
        line-height: 1.8;
    }

    /* トップへ戻るボタン */
    .back-home {
        display: inline-block;
        margin-top: 30px;
        background: #ff9800;
        padding: 12px 20px;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
    }
</style>