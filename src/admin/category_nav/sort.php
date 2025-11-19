<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['order'])) {
    exit;
}

$order = $data['order'];

$sort = 1;
$stmt = $pdo->prepare("UPDATE top_category_nav SET sort_order = :sort WHERE id = :id");

foreach ($order as $id) {
    $stmt->execute([
        ':sort' => $sort,
        ':id' => $id
    ]);
    $sort++;
}

echo "OK";
