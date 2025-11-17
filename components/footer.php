<?php
// ==========================================
// 階層を自動判定して、E-martプロジェクトのルートを計算
// ==========================================

// footer.php の絶対パス
$footer_path = __FILE__;

// プロジェクトルート名（あなたの環境に合わせて変更）
$project_root = "E-mart";

// パスをスラッシュで分解
$path_parts = explode("/", str_replace("\\", "/", $footer_path));

// "E-mart" が何階層目にあるかを探す
$root_index = array_search($project_root, $path_parts);

// ルートまでのパスを組み直す
$root_path = "/" . implode("/", array_slice($path_parts, $root_index, count($path_parts)));

// Web上のパス（例： /E-mart/asset/...）
$WEB_ROOT = "/" . $project_root . "/";
?>
<!-- ==========================================
     Footer (E-mart) - ここからHTML
========================================== -->

<link rel="stylesheet" href="<?= $WEB_ROOT ?>asset/css/footer.css">

<footer class="e-footer">
    <div class="footer-links">
        <a href="<?= $WEB_ROOT ?>src/contact/contact.php">お問い合わせフォーム</a>
        <a href="<?= $WEB_ROOT ?>src/faq/faq.php">よくある質問</a>
    </div>

    <div class="footer-sub-links">
        <a href="<?= $WEB_ROOT ?>src/pages/company.php">会社概要</a>
        <a href="<?= $WEB_ROOT ?>src/pages/terms.php">利用規約</a>
        <a href="<?= $WEB_ROOT ?>src/pages/privacy.php">プライバシーポリシー</a>
    </div>

    <p class="footer-copy">
        © 2025 E-mart All Rights Reserved.
    </p>
</footer>