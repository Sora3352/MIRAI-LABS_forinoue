<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: /E-mart/src/login/login_input.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? 0;

// データ取得（本人のデータのみ）
$sql = "
    SELECT *
    FROM contact_inquiries
    WHERE id = :id AND user_id = :user_id
    LIMIT 1
";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id);
$stmt->bindValue(":user_id", $user_id);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "データが見つかりません。";
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/header.php');
?>

<main class="contact-detail">

    <a href="/E-mart/src/contact/contact_history.php" class="back-link">← 履歴に戻る</a>

    <h1 class="detail-title">お問い合わせ詳細</h1>

    <div class="detail-box">

        <div class="detail-row">
            <span class="label">お問い合わせ種類</span>
            <span class="value"><?= htmlspecialchars($data['type']) ?></span>
        </div>

        <div class="detail-row">
            <span class="label">日時</span>
            <span class="value"><?= htmlspecialchars($data['created_at']) ?></span>
        </div>

        <div class="detail-row">
            <span class="label">内容</span>
            <div class="message-box">
                <?= nl2br(htmlspecialchars($data['message'])) ?>
            </div>
        </div>

        <?php if (!empty($data['file_path'])): ?>
            <div class="detail-row">
                <span class="label">添付ファイル</span>
                <a href="<?= htmlspecialchars($data['file_path']) ?>" class="file-btn" download>
                    ダウンロード
                </a>
            </div>
        <?php endif; ?>

    </div>

</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/footer.php'); ?>

<style>
    .contact-detail {
        max-width: 800px;
        margin: 30px auto;
        padding: 0 20px;
        font-family: "Noto Sans JP";
    }

    .back-link {
        text-decoration: none;
        font-size: 14px;
        color: #555;
    }

    .detail-title {
        text-align: center;
        margin: 20px 0 30px;
        font-size: 26px;
    }

    .detail-box {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        border: 1px solid #ddd;
    }

    .detail-row {
        margin-bottom: 25px;
    }

    .label {
        display: inline-block;
        width: 140px;
        font-weight: bold;
    }

    .value {
        color: #333;
    }

    .message-box {
        background: #fafafa;
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 6px;
        margin-top: 10px;
        line-height: 1.6;
    }

    .file-btn {
        padding: 10px 16px;
        background: #ff9800;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
    }
</style>