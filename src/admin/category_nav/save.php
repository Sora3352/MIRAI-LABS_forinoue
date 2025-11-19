<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

$mode = $_POST['mode'] ?? '';
$label = $_POST['label'] ?? '';
$link_url = $_POST['link_url'] ?? '';
$is_active = $_POST['is_active'] ?? 1;

if ($mode === 'add') {

    // sort_order は最後尾に入れる
    $max_stmt = $pdo->query("SELECT MAX(sort_order) FROM top_category_nav");
    $max = $max_stmt->fetchColumn();
    $next_sort = ($max + 1);

    $stmt = $pdo->prepare("
        INSERT INTO top_category_nav (label, link_url, is_active, sort_order)
        VALUES (:label, :link_url, :is_active, :sort)
    ");

    $stmt->execute([
        ':label' => $label,
        ':link_url' => $link_url,
        ':is_active' => $is_active,
        ':sort' => $next_sort
    ]);

} elseif ($mode === 'edit') {

    $id = $_POST['id'] ?? 0;

    $stmt = $pdo->prepare("
        UPDATE top_category_nav
        SET label = :label, link_url = :link, is_active = :active
        WHERE id = :id
    ");

    $stmt->execute([
        ':label' => $label,
        ':link' => $link_url,
        ':active' => $is_active,
        ':id' => $id,
    ]);
}

header("Location: list.php");
exit;
