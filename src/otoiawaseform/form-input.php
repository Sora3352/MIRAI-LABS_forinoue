<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>E-mart お問い合わせフォーム</title>
  <link rel="stylesheet" href="../../asset/css/form.css">
</head>
<body>
  <div class="container">
    <header>
      <div class="logo-area">
        <img src="https://cdn-icons-png.flaticon.com/512/263/263142.png" alt="E-mart ロゴ" class="logo">
        <h1>E-mart</h1>
      </div>
      <a href="#" class="store-btn">E-MARTストアへ</a>
    </header>

    <h2>お問い合わせフォーム</h2>

    <div class="info">
      <p>お問い合わせ対応可能時間　365日24時間　対応可能</p>
      <p>お問い合わせ回答時間　月曜日～金曜日　10時～20時</p>
      <p>お問い合わせの回答はメールにて直接お送りさせていただきます。</p>
    </div>

    <!-- 送信フォーム -->
    <form action="#" method="post" enctype="multipart/form-data">
      <!-- お問い合わせ種類 -->
      <label for="type">お問い合わせ種類 <span class="required">※必須</span></label>
      <select id="type" name="type" required>
        <option value="">--</option>
        <option value="注文に関するお問い合わせ">注文に関するお問い合わせ</option>
        <option value="商品について">商品について</option>
        <option value="配送について">配送について</option>
        <option value="その他">その他</option>
      </select>

      <p class="note">※送信済みのお問い合わせの追加はお問い合わせ履歴からご確認いただけます。</p>

      <!-- お問い合わせ内容 -->
      <label for="content">お問い合わせ内容 <span class="required">※必須</span></label>
      <textarea id="content" name="content" rows="6" required></textarea>

      <!-- 添付ファイル -->
      <p class="file-note">
        画像またはファイルの添付（20MB以内）<br>
        ※ファイルを添付される場合はパスワード（暗号化）をつけずに添付してください。
      </p>

      <div class="file-area">
        <input type="file" id="file" name="file">
        <span>選択されていません</span>
      </div>

      <!-- 送信ボタン -->
      <button type="submit" class="submit-btn">送信</button>
    </form>
  </div>
</body>
</html>
