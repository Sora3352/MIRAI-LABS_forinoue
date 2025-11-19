<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM top_category_nav WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

header("Location: list.php");
exit;
