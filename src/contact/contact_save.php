<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/E-mart/asset/php/db_connect.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$type = $_POST['type'] ?? '';
$message = $_POST['message'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;

$errors = [];
if ($type === '')
    $errors[] = "お問い合わせ種類が未選択です。";
if (trim($message) === '')
    $errors[] = "お問い合わせ内容を入力してください。";

if (!empty($errors)) {
    $_SESSION['contact_error'] = implode("<br>", $errors);
    header("Location: contact_form.php");
    exit;
}

// --- ファイル保存 ---
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/E-mart/asset/contact_files/";
if (!file_exists($upload_dir))
    mkdir($upload_dir, 0777, true);

$file_path = null;

if (!empty($_FILES['file']['name'])) {
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $new_name = "contact_" . date("Ymd_His") . "_" . uniqid() . "." . $ext;

    $full_path = $upload_dir . $new_name;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $full_path)) {
        $file_path = "/E-mart/asset/contact_files/" . $new_name;
    }
}

// --- DB保存 ---
$sql = "
    INSERT INTO contact_inquiries (user_id, type, message, file_path, created_at)
    VALUES (:user_id, :type, :message, :file_path, NOW())
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
$stmt->bindValue(":type", $type, PDO::PARAM_STR);
$stmt->bindValue(":message", $message, PDO::PARAM_STR);
$stmt->bindValue(":file_path", $file_path, PDO::PARAM_STR);
$stmt->execute();

header("Location: contact_complete.php");
exit;
?>