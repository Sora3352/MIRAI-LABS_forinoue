
<main class="contact-wrapper">

    <!-- 上部ヘッダー部分（ロゴ＋戻るボタン） -->
    <div class="contact-header">
        <a href="/E-mart/src/index.php" class="logo-area">
            <img src="/E-mart/asset/img/logo.png" class="contact-logo">
        </a>

        <a href="/E-mart/src/index.php" class="store-btn">
            E-MARTストアへ
        </a>
    </div>

    <!-- タイトル -->
    <h1 class="contact-title">お問い合わせフォーム</h1>

    <!-- 説明エリア -->
    <div class="contact-info">
        <p>お問い合わせ対応可能時間　365日24時間　対応可能</p>
        <p>お問い合わせ回答時間　月曜日〜金曜日　10時〜20時</p>
        <p>お問い合わせの回答はメールにて直接お送りさせていただきます。</p>
    </div>

    <form action="contact_save.php" method="post" enctype="multipart/form-data" class="contact-form">

        <!-- お問い合わせ種類 -->
        <label class="label-title">お問い合わせ種類 <span class="req">*</span></label>
        <select name="type" class="select-box" required>
            <option value="">--</option>
            <option value="商品について">商品について</option>
            <option value="配送について">配送について</option>
            <option value="注文について">注文について</option>
            <option value="返品・交換について">返品・交換について</option>
            <option value="会員・アカウントについて">会員・アカウントについて</option>
            <option value="その他">その他</option>
        </select>

        <p class="note">※送信済みのお問い合わせの追加はお問い合わせ履歴からご確認いただけます。</p>

        <!-- お問い合わせ内容 -->
        <label class="label-title">お問い合わせ内容 <span class="req">*</span></label>
        <textarea name="message" class="textarea-box" required></textarea>

        <!-- ファイル添付 -->
        <p class="file-note">
            画像またはファイルの添付(20MB以内)<br>
            ※ファイルを添付される場合はパスワード(暗号化)をつけずに添付してください。
        </p>

        <input type="file" name="file" class="file-input">

        <button type="submit" class="submit-btn">送信する</button>

    </form>

</main>

<?php include_once("../../components/footer.php"); ?>

<!-- 専用CSS -->
<style>
    /* 全体 */
    .contact-wrapper {
        max-width: 800px;
        margin: 0 auto;
        padding: 30px 20px;
        font-family: "Noto Sans JP", sans-serif;
    }

    /* 上部ヘッダー */
    .contact-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* ロゴ */
    .contact-logo {
        width: 120px;
    }

    /* ストアへ戻るボタン */
    .store-btn {
        background: #ff9800;
        padding: 10px 18px;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    /* タイトル */
    .contact-title {
        text-align: center;
        font-size: 26px;
        margin: 20px 0 25px;
    }

    /* 説明文 */
    .contact-info {
        text-align: center;
        color: #444;
        margin-bottom: 25px;
        line-height: 1.6;
    }

    /* ラベル */
    .label-title {
        display: block;
        font-weight: bold;
        margin: 20px 0 5px;
    }

    .req {
        color: red;
    }

    /* セレクト */
    .select-box {
        width: 100%;
        padding: 12px;
        border: 1px solid #aaa;
        border-radius: 6px;
    }

    /* テキストエリア */
    .textarea-box {
        width: 100%;
        height: 200px;
        padding: 12px;
        border: 1px solid #aaa;
        border-radius: 6px;
        resize: vertical;
    }

    /* 注意書き */
    .note {
        font-size: 13px;
        color: #444;
        margin-bottom: 10px;
    }

    /* ファイル注意書き */
    .file-note {
        margin-top: 20px;
        margin-bottom: 5px;
        font-size: 14px;
        color: #444;
    }

    /* ファイル選択 */
    .file-input {
        margin-bottom: 20px;
    }

    /* 送信ボタン */
    .submit-btn {
        width: 100%;
        padding: 14px;
        background: #ff9800;
        color: #fff;
        font-weight: bold;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 20px;
        font-size: 16px;
    }
</style>