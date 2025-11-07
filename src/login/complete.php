<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>E-MART 登録完了</title>
    <link rel="stylesheet" href="../../asset/css/register.css">
    <style>
        .complete-box {
            text-align: center;
            margin-top: 80px;
        }
        .complete-box h2 {
            border: none;
            font-size: 22px;
            color: #a00;
        }
        .complete-box p {
            margin-top: 20px;
            font-size: 16px;
            color: #333;
        }
        .complete-box button {
            margin-top: 40px;
            background: #a00;
            color: #fff;
            border: none;
            padding: 12px 40px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .complete-box button:hover {
            background: #c00;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="complete-box">
        <h2>会員登録が完了しました</h2>
        <p>ご登録いただきありがとうございます。<br>
        ご入力いただいたメールアドレス宛に確認メールをお送りしました。</p>

        <button onclick="location.href='register.php'">トップページへ戻る</button>
    </div>
</div>
</body>
</html>
