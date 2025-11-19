<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

$id = $_GET['id'];
$active = $_GET['active'];

$stmt = $pdo->prepare("UPDATE sale_products SET is_active = :active WHERE id = :id");
$stmt->bindValue(':active', $active);
$stmt->bindValue(':id', $id);
$stmt->execute();

header("Location: /E-mart/src/admin/sale/sale_list.php");
exit;
