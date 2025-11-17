<?php
// ==========================================
// DB接続設定（ロリポップ MySQL）
// ==========================================

$DB_HOST = "mysql326.phy.lolipop.lan";
$DB_NAME = "LAA1607503-mirailabs";
$DB_USER = "LAA1607503";
$DB_PASS = "MIRAI12345";

try {
    // PDOインスタンス生成
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8",
        $DB_USER,
        $DB_PASS
    );

    // 例外モードを有効化
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    exit("🚨 データベース接続エラー: " . $e->getMessage());
}
?>