<?php
// 仮のユーザー情報（本来はDBから取得）
$user_name = "山田太郎";
$user_email = "yamada@example.com";

// 仮の購入履歴（例: 空なら「まだ購入履歴はありません」表示）
$purchases = [];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>マイページ</title>
  <link rel="stylesheet" href="../../asset/css/mypagetop.css">
</head>
<body>

<h2>マイページ</h2>

<div class="container">
  <!-- 登録情報 -->
  <div class="box">
    <h3>ご登録情報 <a href="#" class="all-view">すべてを見る</a></h3>
    <p>登録名：<?= htmlspecialchars($user_name) ?><br>
    アドレス：<?= htmlspecialchars($user_email) ?></p>
    <div class="info">
      <a href="#">お客様情報</a>
      <a href="#">お届け先情報</a>
      <a href="#">お問い合わせ履歴</a>
    </div>
  </div>

  <!-- 購入履歴 -->
  <div class="box">
    <h3>ご購入履歴 <a href="#" class="all-view">すべてを見る</a></h3>
    <?php if (empty($purchases)): ?>
      <p>まだ購入履歴はありません</p>
    <?php else: ?>
      <ul>
        <?php foreach ($purchases as $p): ?>
          <li><?= htmlspecialchars($p["item"]) ?> - <?= htmlspecialchars($p["price"]) ?>円 (<?= htmlspecialchars($p["date"]) ?>)</li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</div>

<!-- お得情報 -->
<div class="sale-info">
  <p><span>全品１０％OFF</span> などお得な情報をお知らせ！！</p>

  <!-- フォーム送信部分 -->
  <form action="receive_mail.php" method="post">
    <input type="hidden" name="email" value="<?= htmlspecialchars($user_email) ?>">
    <button type="submit" class="btn-mail">📩 メールを受信する</button>
  </form>
</div>

</body>
</html>
