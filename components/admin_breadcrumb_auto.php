<?php
$path = $_SERVER["REQUEST_URI"];
$path = str_replace("/E-mart/src/admin/", "", $path);
$path = explode("?", $path)[0];
$segments = explode("/", trim($path, "/"));

// ページ名辞書
$names = [
    "admin_menu.php" => "管理者メニュー",
    "admin_list.php" => "管理者一覧",
    "admin_add.php" => "管理者追加",
    "product_manage.php" => "商品管理",
    "product_add.php" => "商品追加",
    "product_edit.php" => "商品編集",
    "news_manage.php" => "ニュース管理",
];

$breadcrumbs = [];
$breadcrumbs["admin_menu.php"] = "管理者メニュー";

$current = end($segments);
if (isset($names[$current])) {
    $breadcrumbs[$current] = $names[$current];
}

$WEB_ADMIN = "/E-mart/src/admin/";
?>

<link rel="stylesheet" href="/E-mart/asset/css/admin_breadcrumb.css">

<nav class="admin-breadcrumb">
    <?php
    $i = 0;
    $total = count($breadcrumbs);
    foreach ($breadcrumbs as $file => $label):
        $i++;
        $is_last = ($i === $total);

        // アイコン設定
        $icon = "📄";
        if ($label === "管理者メニュー")
            $icon = "🏠";
        if (strpos($label, "商品") !== false)
            $icon = "📦";
        if (strpos($label, "管理者一覧") !== false)
            $icon = "👤";
        if (strpos($label, "編集") !== false)
            $icon = "✏";
        ?>
        <?php if ($is_last): ?>
            <span class="bc-last"><?= $icon ?>         <?= $label ?></span>
        <?php else: ?>
            <a href="<?= $WEB_ADMIN . $file ?>" class="bc-link">
                <?= $icon ?>         <?= $label ?>
            </a>
            <span class="bc-arrow">›</span>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>