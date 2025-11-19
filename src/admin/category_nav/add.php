<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>カテゴリ追加 | 管理画面</title>
    <link rel="stylesheet" href="/E-mart/asset/css/admin.css">

    <style>
        .form-box {
            max-width: 500px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
        }

        input[type="text"],
        input[type="url"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .btn {
            padding: 10px 15px;
            background: #2196f3;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            border: none;
        }
    </style>
</head>

<body>

    <h1>カテゴリ追加</h1>

    <div class="form-box">

        <form action="save.php" method="post">

            <input type="hidden" name="mode" value="add">

            <div class="input-group">
                <label>表示名（label）</label>
                <input type="text" name="label" required>
            </div>

            <div class="input-group">
                <label>リンク先URL（link_url）</label>
                <input type="text" name="link_url" placeholder="/E-mart/...">
            </div>

            <div class="input-group">
                <label>表示ステータス</label>
                <select name="is_active">
                    <option value="1">表示</option>
                    <option value="0">非表示</option>
                </select>
            </div>

            <button type="submit" class="btn">追加する</button>

        </form>

        <br>
        <a href="list.php">← 戻る</a>

    </div>

</body>

</html>