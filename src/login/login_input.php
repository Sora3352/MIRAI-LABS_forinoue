<?php
require_once '../../asset/DB/db_connect.php';

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <link rel="stylesheet" href="../../asset/css/login.css">  
</head>

<body>
  <?php include('../header/header.php'); ?>
  <div class="wrap">
    <div>
      <h1>ログイン</h1>
      <form action="login_output.php" method="post">
        <label>ユーザー名</label>
        <input type="text" name="username" />
        <label>パスワード</label>
        <input type="password" name="password" />
        <button class="#">ログイン</button>
      </form>
    </div>
    <div class="side">
      <p>はじめてご利用の方はこちらに</p>
      <button class="link">新規会員登録</button>
    </div>
  </div>
  <?php include('../footer/footer.php'); ?>
</body>

</html>