<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ご登録情報 | E-mart</title>
  <link rel="stylesheet" href="style.css">
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

    <h2>ご登録情報</h2>

    <div class="info-sections">
      <!-- お客様情報 -->
      <section class="customer-info">
        <h3>お客様情報</h3>
        <button class="edit-btn">編集</button>
        <table>
          <tr>
            <th>登録情報</th>
            <td></td>
          </tr>
          <tr>
            <th>ユーザーID</th>
            <td></td>
          </tr>
          <tr>
            <th>パスワード</th>
            <td>
              お客様が設定したパスワード
              <button class="small-btn">パスワードの変更</button>
            </td>
          </tr>
          <tr>
            <th>住所</th>
            <td></td>
          </tr>
          <tr>
            <th>TEL</th>
            <td></td>
          </tr>
          <tr>
            <th>携帯</th>
            <td></td>
          </tr>
          <tr>
            <th>E-Mail</th>
            <td>
              <button class="small-btn">メールアドレスの設定</button>
            </td>
          </tr>
          <tr>
            <th>設定</th>
            <td></td>
          </tr>
        </table>
      </section>

      <!-- お届け先情報 -->
      <section class="delivery-info">
        <h3>お届け先情報</h3>
        <div class="delivery-header">
          <div class="select-area">
            <label for="delivery-select">お届け先選択</label>
            <select id="delivery-select" name="delivery-select">
              <option value="">名前</option>
              <option value="home">自宅</option>
              <option value="office">勤務先</option>
            </select>
          </div>
          <div class="delivery-btns">
            <button class="edit-btn">編集</button>
            <button class="delete-btn">削除</button>
          </div>
        </div>

        <table>
          <tr>
            <th>お届け先名</th>
            <td></td>
          </tr>
          <tr>
            <th>住所</th>
            <td></td>
          </tr>
          <tr>
            <th>住所</th>
            <td></td>
          </tr>
          <tr>
            <th>納品先名</th>
            <td></td>
          </tr>
        </table>
      </section>
    </div>
  </div>
</body>
</html>
