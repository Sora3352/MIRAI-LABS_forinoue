<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お問い合わせフォーム | E-mart</title>
    <link rel="stylesheet" href="../../asset/css/customer.css">
</head>
<body>

<div class="container">
    <header>
        <div class="logo">
            <!-- <img src="https://upload.wikimedia.org/wikipedia/commons/1/1e/Shopping_cart_icon.svg" alt="E-martロゴ"> -->
            <span>E-mart</span>
        </div>
        <h1>お問い合わせフォーム</h1>
        <a href="#" class="store-btn">E-MARTストアへ</a>
    </header>

    <section class="info">
        <p>お問い合わせ対応可能時間　365日24時間　対応可能</p>
        <p>お問い合わせ回答時間　月曜日〜金曜日　10時〜20時</p>
        <p>お問い合わせの回答はメールにて直接お送りさせていただきます。</p>
    </section>

    <form action="#" method="post" enctype="multipart/form-data">
        <label for="type">お問い合わせ種類 <span class="required">*必須</span></label>
        <select id="type" name="type" required>
            <option value="">--</option>
            <option value="商品について">商品について</option>
            <option value="配送について">配送について</option>
            <option value="その他">その他</option>
        </select>

        <p class="note">※送信済みのお問い合わせの追加はお問い合わせ履歴からご確認いただけます。</p>

        <label for="content">お問い合わせ内容 <span class="required">*必須</span></label>
        <textarea id="content" name="content" rows="8" required></textarea>

        <div class="file-upload">
            <p>画像またはファイルの添付(20MB以内)</p>
            <p class="note">※ファイルを添付される場合はパスワード(暗号化)をつけずに添付してください。</p>
            <input type="file" name="file">
        </div>

        <button type="submit" class="submit-btn">送信する</button>
    </form>
</div>

</body>
</html>
