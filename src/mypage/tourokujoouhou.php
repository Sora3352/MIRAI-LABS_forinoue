<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
// ===========================================
// 1. データベース接続設定と接続処理
// 以下の値を、ご自身の環境に合わせて変更してください。
// ===========================================
$host    = 'localhost';              // データベースのホスト名
$db      = 'your_database_name';     // データベース名
$user    = 'your_db_user';           // データベースユーザー名
$pass    = 'your_db_password';       // データベースパスワード
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = null;
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // 接続エラー発生時
    exit('データベース接続に失敗しました: ' . $e->getMessage());
}

// 認証済みユーザーのIDを仮定 (実際はセッションから取得: $_SESSION['user_id'])
// 例として、DBに存在するIDを指定してください
$current_user_id = 'example_user123'; 
$userData = [];
$shippingAddress = [];

// ===========================================
// 2. お客様情報の取得
// ===========================================
try {
    // users テーブルから情報を取得する (パスワードはセキュリティ上表示しない)
    $sql_user = "SELECT user_id, address, tel, mobile, email, setting FROM users WHERE user_id = ?";
    $stmt = $pdo->prepare($sql_user);
    $stmt->execute([$current_user_id]);
    $userData = $stmt->fetch(); 

    if (!$userData) {
        exit('ユーザー情報が見つかりませんでした。');
    }
} catch (\PDOException $e) {
    error_log('ユーザー情報取得エラー: ' . $e->getMessage());
    $userData = ['user_id' => 'ERROR', 'address' => 'データ取得エラー', 'tel' => '', 'mobile' => '', 'email' => '', 'setting' => ''];
}


// ===========================================
// 3. お届け先情報の取得 (最初の1件のみを取得すると仮定)
// ===========================================
try {
    // shipping_addresses テーブルから情報を取得する
    $sql_shipping = "SELECT shipping_name, address1, address2, delivery_name FROM shipping_addresses WHERE user_id = ? LIMIT 1";
    $stmt = $pdo->prepare($sql_shipping);
    $stmt->execute([$current_user_id]);
    $shippingAddress = $stmt->fetch();

    if (!$shippingAddress) {
         // データがない場合のデフォルト値
        $shippingAddress = ['shipping_name' => '未登録', 'address1' => '登録がありません', 'address2' => '', 'delivery_name' => ''];
    }
} catch (\PDOException $e) {
    error_log('お届け先情報取得エラー: ' . $e->getMessage());
    $shippingAddress = ['shipping_name' => 'ERROR', 'address1' => 'データ取得エラー', 'address2' => '', 'delivery_name' => ''];
}

// ===========================================
// 4. HTMLに出力 (ここから下の空欄にDB情報が表示されます)
// ===========================================
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ご登録情報</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>ご登録情報</h1>

    <div class="container">
        <section class="customer-info">
            <h2>お客様情報</h2>
            <div class="edit-btn-area">
                <button type="button">編集</button>
            </div>
            
            <dl class="info-list">
                <dt class="header-row">登録情報</dt>
                <dd class="header-row"></dd>

                <dt>ユーザーID</dt>
                <dd><?php echo htmlspecialchars($userData['user_id'] ?? ''); ?></dd>
                
                <dt>パスワード</dt>
                <dd>
                    <span class="password-display">お客様が設定したパスワード</span>
                    <button type="button" class="change-password-btn">パスワードの変更</button>
                </dd>
                
                <dt>住所</dt>
                <dd><?php echo nl2br(htmlspecialchars($userData['address'] ?? '')); ?></dd>
                
                <dt>TEL</dt>
                <dd><?php echo htmlspecialchars($userData['tel'] ?? ''); ?></dd>

                <dt>携帯</dt>
                <dd><?php echo htmlspecialchars($userData['mobile'] ?? ''); ?></dd>

                <dt>E-Mail</dt>
                <dd>
                    <?php echo htmlspecialchars($userData['email'] ?? ''); ?>
                    <button type="button" class="email-setting-btn">メールアドレスの設定</button>
                </dd>

                <dt>設定</dt>
                <dd><?php echo htmlspecialchars($userData['setting'] ?? ''); ?></dd>
            </dl>
        </section>

        <section class="shipping-info">
            <h2>お届け先情報</h2>
            
            <div class="shipping-header">
                <label for="shipping-select">お届け先選択</label>
                <select id="shipping-select">
                    <option><?php echo htmlspecialchars($shippingAddress['shipping_name'] ?? ''); ?></option>
                </select>
                <button type="button" class="edit-btn">編集</button>
                <button type="button" class="delete-btn">削除</button>
            </div>

            <dl class="shipping-detail-list">
                <dt>お届け先名</dt>
                <dd><?php echo htmlspecialchars($shippingAddress['shipping_name'] ?? ''); ?></dd>

                <dt>住所</dt>
                <dd><?php echo htmlspecialchars($shippingAddress['address1'] ?? ''); ?></dd>

                <dt>住所</dt>
                <dd><?php echo htmlspecialchars($shippingAddress['address2'] ?? ''); ?></dd>

                <dt>納品先名</dt>
                <dd><?php echo htmlspecialchars($shippingAddress['delivery_name'] ?? ''); ?></dd>
            </dl>
        </section>
    </div>

</body>
</html>
</body>
</html>