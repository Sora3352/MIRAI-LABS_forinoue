<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>E-MART ホーム</title>
    <link rel="stylesheet" href="../../asset/css/style.css"> <!-- CSSのパス調整 -->
    <link rel="stylesheet" href="../../asset/css/header.css"> <!-- header専用CSS -->
</head>
<body>

    <!-- ▼ 共通ヘッダー読込 -->
    <?php include('../header/header.php'); ?>
    <!-- ▲ 共通ヘッダー読込 -->

    <main>
        <h2>商品カテゴリ</h2>
        <div class="category">
            <a href="#">すべての商品</a>
            <a href="#">ベビー・おもちゃ・ホビー</a>
            <a href="#">食品＆飲料</a>
            <a href="#">パソコン</a>
            <a href="#">家電</a>
            <a href="#">ヘルス・ビューティー</a>
            <a href="#">楽器</a>
            <a href="#">ホーム・キッチン</a>
            <a href="#">アウトドア・スポーツ</a>
            <a href="#">カメラ</a>
            <a href="#">ゲーム</a>
            <a href="#">AV機器</a>
            <a href="#">時計・ジュエリー</a>
            <a href="#">カー・バイク用品</a>
            <a href="#">パーソナルモビリティ</a>
            <a href="#">理美容・健康</a>
            <a href="#">DVD・ブルーレイ</a>
            <a href="#">オーディオ</a>
            <a href="#">DIY・工具</a>
            <a href="#">インパクト</a>
        </div>

        <div class="menu-box">
            <div class="menu-item">曜日別特売</div>
            <div class="menu-item">カテゴリ特化カレンダー</div>
            <div class="menu-item">デジタルカタログ</div>
        </div>

        <h2>お得なE-MARTオリジナル商品</h2>
        <div class="product-area">
            <?php
            $products = [
                "商品画像1", "商品画像2", "商品画像3", "商品画像4", "商品画像5", "商品画像6"
            ];
            foreach ($products as $p) {
                echo '<div class="product">
                        <a href="#"><img src="https://via.placeholder.com/80x80" alt="'.$p.'"></a>
                        <p>税込み0円</p>
                      </div>';
            }
            ?>
            <button class="next-btn">▶</button>
        </div>
    <h2>お得なE-MARTオリジナル商品</h2>
    <div class="product-area">
        <?php
        $products = [
            "商品画像1", "商品画像2", "商品画像3", 
            "商品画像4", "商品画像5", "商品画像6"
        ];
        $i = 1;
        foreach ($products as $p) {
            echo '<div class="product">
                    <a href="#"><img src="images/item'.$i.'.jpg" alt="'.$p.'"></a>
                    <p>税込み0円</p>
                  </div>';
            $i++;
        }
        ?>
        <button class="next-btn">▶</button>
    </div>

        <div class="feature-area">
            <div class="feature">ブランド名から探す</div>
            <div class="feature">自動車部品検索から探す</div>
            <div class="feature">バイク部品検索から探す</div>
            <div class="feature">オムロン製品検索から探す</div>
            <div class="feature">SMC製品検索から探す</div>
        </div>
    </main>

    <!-- ▼ 共通フッター（作成済みなら） -->
    <?php include('../footer/footer.php'); ?>
    <!-- ▲ 共通フッター -->

</body>
</html>
