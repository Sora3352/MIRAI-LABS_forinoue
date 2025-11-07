<?php
// =========================================
// DB接続ファイル（Lolipopサーバー用）
// =========================================

// DB接続情報
$DB_HOST = "mysql326.phy.lolipop.lan";   // サーバー名
$DB_NAME = "LAA1607503-mirailabs";       // データベース名
$DB_USER = "LAA1607503";                 // ユーザー名
$DB_PASS = "MIRAI12345";                 // パスワード

try {
    // DSN（Data Source Name）
    $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";

    // PDOインスタンス作成
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // エラーを例外としてスロー
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // 連想配列として取得
        PDO::ATTR_EMULATE_PREPARES => false, // 静的プレースホルダーを使用
    ]);

} catch (PDOException $e) {
    // 接続エラー時の処理
    exit('データベース接続エラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
?>