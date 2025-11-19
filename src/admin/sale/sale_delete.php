<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("UPDATE sale_products SET is_active = 0 WHERE id = :id");
$stmt->bindValue(':id', $id);
$stmt->execute();

header("Location: /E-mart/src/admin/sale/sale_list.php");
exit;
