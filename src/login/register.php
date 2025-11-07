<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>E-MART 会員登録</title>
    <link rel="stylesheet" href="../../asset/css/register.css">
</head>
<body>
<div class="container">
    <p class="note">記号や特殊漢字は入力できない場合がございます。</p>

    <form action="confirm.php" method="post">
        <h2>お客様情報</h2>
        <table class="form-table">
            <tr>
                <th>お名前<span class="required">必須</span></th>
                <td>
                    <input type="text" name="sei" placeholder="例：威魔" required>
                    <input type="text" name="mei" placeholder="例：太郎" required>
                </td>
            </tr>
            <tr>
                <th>お名前カナ<span class="required">必須</span></th>
                <td>
                    <input type="text" name="sei_kana" placeholder="例：イマ" required>
                    <input type="text" name="mei_kana" placeholder="例：タロウ" required>
                </td>
            </tr>
            <tr>
                <th>メールアドレス<span class="required">必須</span></th>
                <td><input type="email" name="email" placeholder="例：emart@emart.com" required></td>
            </tr>
            <tr>
                <th>メールアドレス(再入力)<span class="required">必須</span></th>
                <td><input type="email" name="email_confirm" placeholder="例：emart@emart.com" required></td>
            </tr>
        </table>

        <h2>住所</h2>
        <table class="form-table">
            <tr>
                <th>郵便番号</th>
                <td><input type="text" name="zip" placeholder="例：1010001"></td>
            </tr>
            <tr>
                <th>住所</th>
                <td><input type="text" name="address" placeholder="例：東京都千代田区千代田1-1"></td>
            </tr>
            <tr>
                <th>建物名</th>
                <td><input type="text" name="building" placeholder="例：E-MARTビル2F"></td>
            </tr>
            <tr>
                <th>電話番号<span class="required">必須</span></th>
                <td><input type="text" name="tel" placeholder="例：0600000000" required></td>
            </tr>
            <tr>
                <th>FAX番号</th>
                <td>
                    <input type="text" name="fax" placeholder="例：0600001111">
                    <label><input type="checkbox"> 電話番号と同じ</label>
                    <label><input type="checkbox"> FAXを持っていない</label>
                </td>
            </tr>
            <tr>
                <th>携帯電話番号</th>
                <td><input type="text" name="mobile" placeholder="例：09000000000"></td>
            </tr>
        </table>

        <h2>アンケート</h2>
        <table class="form-table">
            <tr>
                <th>ご登録のきっかけ<span class="required">必須</span></th>
                <td class="radio-group">
                    <label><input type="radio" name="reason" value="郵送チラシ" required> 郵送チラシ</label>
                    <label><input type="radio" name="reason" value="インターネットで検索した"> インターネットで検索した</label>
                    <label><input type="radio" name="reason" value="知人に聞いた"> 知人に聞いた</label>
                    <label><input type="radio" name="reason" value="新聞広告・雑誌"> 新聞広告・雑誌</label>
                    <label><input type="radio" name="reason" value="テレビ"> テレビ</label>
                    <label><input type="radio" name="reason" value="SNS"> Twitter・Facebook・SNS</label>
                    <label><input type="radio" name="reason" value="その他"> その他</label>
                </td>
            </tr>
        </table>

        <h2>パスワードを設定する</h2>
        <table class="form-table">
            <tr>
                <th>パスワード<span class="required">必須</span></th>
                <td><input type="password" name="pass" placeholder="半角英数8文字以上" required></td>
            </tr>
            <tr>
                <th>パスワード(再入力)<span class="required">必須</span></th>
                <td><input type="password" name="pass_confirm" placeholder="確認のため再入力" required></td>
            </tr>
        </table>

        <h2>メールマガジン</h2>
        <table class="form-table">
            <tr>
                <th>メールマガジンの配信</th>
                <td><label><input type="checkbox" name="magazine" value="希望する"> クーポン・キャンペーンなどのお得な情報を希望する</label></td>
            </tr>
        </table>

        <div class="submit-area">
            <button type="submit">確認画面へ進む</button>
        </div>
    </form>
</div>
</body>
</html>
