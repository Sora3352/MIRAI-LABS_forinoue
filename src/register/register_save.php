<?php
session_start();
require_once('../../asset/php/db_connect.php');

// ===== 入力データ受け取り =====
$last_name = trim($_POST['last_name'] ?? '');
$first_name = trim($_POST['first_name'] ?? '');
$last_name_kana = trim($_POST['last_name_kana'] ?? '');
$first_name_kana = trim($_POST['first_name_kana'] ?? '');

$email = trim($_POST['email'] ?? '');
$email_confirm = trim($_POST['email_confirm'] ?? '');

$postal_code = trim($_POST['postal_code'] ?? '');
$prefecture = trim($_POST['prefecture'] ?? '');
$city = trim($_POST['city'] ?? '');
$street = trim($_POST['street'] ?? '');
$building = trim($_POST['building'] ?? '');

$tel = trim($_POST['tel'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$fax = trim($_POST['fax'] ?? '');

$password = trim($_POST['password'] ?? '');
$password_confirm = trim($_POST['password_confirm'] ?? '');

$mail_magazine = isset($_POST['mail_magazine']) ? 1 : 0;

$errors = [];

// =========================================
// ◆ バリデーション
// =========================================

if ($last_name === '')
    $errors[] = '姓を入力してください';
if ($first_name === '')
    $errors[] = '名を入力してください';
if ($last_name_kana === '')
    $errors[] = 'セイを入力してください';
if ($first_name_kana === '')
    $errors[] = 'メイを入力してください';

if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors[] = 'メールアドレスの形式が正しくありません';

if ($email !== $email_confirm)
    $errors[] = 'メールアドレスが一致しません';

if (strlen($password) < 8)
    $errors[] = 'パスワードは8文字以上で入力してください';

if ($password !== $password_confirm)
    $errors[] = 'パスワードが一致しません';

if ($postal_code === '')
    $errors[] = '郵便番号を入力してください';
if ($prefecture === '')
    $errors[] = '都道府県を選択してください';
if ($city === '')
    $errors[] = '市区町村を入力してください';
if ($street === '')
    $errors[] = '番地を入力してください';

if ($tel === '')
    $errors[] = '電話番号を入力してください';


// =========================================
// ◆ メールアドレス重複チェック
// =========================================
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->fetch()) {
    $errors[] = 'このメールアドレスはすでに登録されています';
}


// =========================================
// ◆ エラーがあれば戻す
// =========================================
if (!empty($errors)) {
    $_SESSION['register_errors'] = $errors;
    $_SESSION['register_input'] = $_POST;
    header('Location: register_input.php');
    exit;
}


// =========================================
// ◆ 登録処理（トランザクション）
// =========================================
$pdo->beginTransaction();

try {

    // ◆ users 登録（名前・カナ・メルマガ対応版）
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (
                name,
                email,
                password,
                phone,
                mail_magazine,
                last_name,
                first_name,
                last_name_kana,
                first_name_kana,
                created_at,
                updated_at
            ) VALUES (
                :name,
                :email,
                :password,
                :phone,
                :mail_magazine,
                :last_name,
                :first_name,
                :last_name_kana,
                :first_name_kana,
                NOW(),
                NOW()
            )";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $last_name . ' ' . $first_name);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':password', $hash);
    $stmt->bindValue(':phone', $mobile);
    $stmt->bindValue(':mail_magazine', $mail_magazine, PDO::PARAM_INT);

    $stmt->bindValue(':last_name', $last_name);
    $stmt->bindValue(':first_name', $first_name);
    $stmt->bindValue(':last_name_kana', $last_name_kana);
    $stmt->bindValue(':first_name_kana', $first_name_kana);

    $stmt->execute();

    // 新規 user_id
    $user_id = $pdo->lastInsertId();


    // ◆ addresses 登録
    $sql = "INSERT INTO addresses 
        (user_id, address_type, recipient_name, postal_code, prefecture, city, street, building, phone_number, is_default)
        VALUES 
        (:user_id, 'shipping', :recipient_name, :postal_code, :prefecture, :city, :street, :building, :phone_number, 1)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':recipient_name', $last_name . ' ' . $first_name);
    $stmt->bindValue(':postal_code', $postal_code);
    $stmt->bindValue(':prefecture', $prefecture);
    $stmt->bindValue(':city', $city);
    $stmt->bindValue(':street', $street);
    $stmt->bindValue(':building', $building);
    $stmt->bindValue(':phone_number', $tel);

    $stmt->execute();


    // ◆ コミット
    $pdo->commit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("登録エラー: " . $e->getMessage());
}


// =========================================
// ◆ 完了ページへ
// =========================================
header('Location: register_complete.php');
exit;
