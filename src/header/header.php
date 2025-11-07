<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-mart</title>
    <link rel="stylesheet" href="../../asset/css/header.css">
</head>

<body>
    <header class="header">
        <div class="header-top">
            <div class="logo">
                <a href="../home/home.php" class="logo-link">
                    <img src="../../img/E-mart.png" alt="E-martロゴ">

                </a>
            </div>

            <p class="info-text">
                取り扱い点数 <span class="highlight-orange">2500万点</span>
                当日出荷 <span class="highlight-orange">70000点</span>
                17時までのご注文で <span class="highlight-red">最短当日出荷！</span>
            </p>

            <div class="login-buttons">
                <form action="#" method="get">
                    <button type="submit" class="btn btn-login">ログイン</button>
                </form>
                <form action="#" method="get">
                    <button type="submit" class="btn btn-logout">ログアウト</button>
                </form>
            </div>
        </div>

        <div class="header-nav">
            <div class="nav-left">
                <button class="btn quick-order">クイックオーダー<br>(品番注文)</button>
                <select>
                    <option>絞り込み ▼</option>
                </select>
                <form action="#" method="get" class="search-form">
                    <input type="text" placeholder="商品名、キーワード、注文コード、使用用途">
                    <button type="submit">検索</button>
                </form>
            </div>

            <div class="nav-right">
                <a href="#" class="link">初めてのお客様</a> |
                <a href="#" class="link">新規登録</a> |
                <a href="#" class="link">マイページ</a> |
                <a href="#" class="link">購入履歴</a> |
                <a href="#" class="cart">🛒 カート</a>
                <div class="contact">
                    <a href="#" class="btn btn-contact">WEBお問い合わせフォーム</a>
                    <a href="#" class="btn btn-qa">Q&A</a>
                </div>
            </div>
        </div>
    </header>
</body>

</html>