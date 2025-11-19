<?php
session_start();
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
// ログアウトされたらindexに戻る絶対パスで指定
header("Location: /E-mart/src/index.php");
exit;
