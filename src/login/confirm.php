<?php
// POSTデータを取得
$data = $_POST;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>E-MART 会員登録確認</title>
    <link rel="stylesheet" href="../../asset/css/register.css">
</head>
<body>
<div class="container">

    <h2>入力内容の確認</h2>
    <p class="note">以下の内容でよろしければ「登録する」を押してください。</p>

    <form action="complete.php" method="post">
        <?php foreach ($data as $key => $val): ?>
            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($val) ?>">
        <?php endforeach; ?>

        <table class="form-table">
            <tr><th>お名前</th><td><?= htmlspecialchars($data['sei'] ?? '') ?> <?= htmlspecialchars($data['mei'] ?? '') ?></td></tr>
            <tr><th>お名前カナ</th><td><?= htmlspecialchars($data['sei_kana'] ?? '') ?> <?= htmlspecialchars($data['mei_kana'] ?? '') ?></td></tr>
            <tr><th>メールアドレス</th><td><?= htmlspecialchars($data['email'] ?? '') ?></td></tr>
            <tr><th>住所</th><td><?= htmlspecialchars(($data['zip'] ?? '') . ' ' . ($data['address'] ?? '') . ' ' . ($data['building'] ?? '')) ?></td></tr>
            <tr><th>電話番号</th><td><?= htmlspecialchars($data['tel'] ?? '') ?></td></tr>
            <tr><th>FAX番号</th><td><?= htmlspecialchars($data['fax'] ?? '') ?></td></tr>
            <tr><th>携帯電話番号</th><td><?= htmlspecialchars($data['mobile'] ?? '') ?></td></tr>
            <tr><th>ご登録のきっかけ</th><td><?= htmlspecialchars($data['reason'] ?? '') ?></td></tr>
            <tr><th>メールマガジン</th><td><?= isset($data['mailmag']) ? '希望する' : '希望しない' ?></td></tr>
        </table>

        <div class="submit-area">
            <button type="button" onclick="history.back()">戻る</button>
            <button type="submit">登録する</button>
        </div>
    </form>
</div>
</body>
</html>
