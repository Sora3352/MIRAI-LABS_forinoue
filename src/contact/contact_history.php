<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: /E-mart/src/login/login_input.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ユーザーの履歴取得
$sql = "
    SELECT *
    FROM contact_inquiries
    WHERE user_id = :user_id
    ORDER BY created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":user_id", $user_id);
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/header.php');
?>
<a href="/E-mart/src/mypage/mypage_top.php" class="back-mypage-btn">
    ← マイページトップへ戻る
</a>

<main class="contact-history">

    <h1 class="history-title">お問い合わせ履歴</h1>

    <?php if (empty($contacts)): ?>
        <p class="no-history">お問い合わせ履歴はありません。</p>
    <?php else: ?>
        <div class="history-list">

            <?php foreach ($contacts as $c): ?>
                <div class="history-card">

                    <div class="history-info">
                        <div class="history-type"><?= htmlspecialchars($c['type']) ?></div>
                        <div class="history-date"><?= htmlspecialchars($c['created_at']) ?></div>
                        <div class="history-message">
                            <?= nl2br(htmlspecialchars(mb_strimwidth($c['message'], 0, 70, "…"))) ?>
                        </div>
                    </div>

                    <div class="history-actions">

                        <?php if (!empty($c['file_path'])): ?>
                            <a href="<?= htmlspecialchars($c['file_path']) ?>" class="download-btn" download>
                                添付
                            </a>
                        <?php endif; ?>

                        <a href="/E-mart/src/contact/contact_detail.php?id=<?= $c['id'] ?>" class="detail-btn">
                            詳細
                        </a>

                    </div>

                </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>

</main>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/components/footer.php'); ?>

<style>
    .contact-history {
        max-width: 900px;
        margin: 30px auto;
        padding: 0 15px;
        font-family: "Noto Sans JP";
    }

    .history-title {
        text-align: center;
        font-size: 26px;
        margin-bottom: 25px;
    }

    .no-history {
        text-align: center;
        margin-top: 40px;
        color: #666;
    }

    .history-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .history-card {
        border: 1px solid #ddd;
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }

    .history-type {
        font-size: 18px;
        font-weight: bold;
    }

    .history-date {
        font-size: 14px;
        color: #777;
        margin-bottom: 10px;
    }

    .history-message {
        font-size: 15px;
        color: #444;
    }

    .history-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .detail-btn,
    .download-btn {
        padding: 10px 16px;
        text-align: center;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        font-weight: bold;
    }

    .detail-btn {
        background: #ff9800;
        color: #fff;
    }

    .download-btn {
        background: #eee;
        color: #333;
    }

    .back-mypage-btn {
        display: inline-block;
        margin: 10px 0 15px 0;
        padding: 8px 14px;
        background: #f0f0f0;
        border-radius: 6px;
        color: #333;
        text-decoration: none;
        font-size: 14px;
        font-weight: bold;
    }

    .back-mypage-btn:hover {
        background: #e0e0e0;
    }
</style>