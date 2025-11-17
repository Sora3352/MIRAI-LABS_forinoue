<?php
session_start();

// 前回のエラー
$errors = $_SESSION['register_errors'] ?? [];
$old = $_SESSION['register_input'] ?? [];

// 表示後クリア
unset($_SESSION['register_errors']);
unset($_SESSION['register_input']);

// 共通ヘッダー
include_once("../../components/header.php");
?>

<link rel="stylesheet" href="/E-mart/asset/css/form.css">

<div class="register-wrapper">

    <h2 class="form-title">新規会員登録</h2>

    <!-- ===== エラー表示（全体） ===== -->
    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>


    <form action="register_save.php" method="post">

        <!-- ==============================
             お客様情報
        ================================= -->
        <section class="form-section">
            <h3>お客様情報</h3>

            <div class="form-row">
                <div class="form-label">お名前（姓） <span class="required">必須</span></div>
                <input type="text" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>"
                    placeholder="例：今戸">
            </div>

            <div class="form-row">
                <div class="form-label">お名前（名） <span class="required">必須</span></div>
                <input type="text" name="first_name" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>"
                    placeholder="例：太郎">
            </div>

            <div class="form-row">
                <div class="form-label">セイ <span class="required">必須</span></div>
                <input type="text" name="last_name_kana" value="<?= htmlspecialchars($old['last_name_kana'] ?? '') ?>"
                    placeholder="例：イマド">
            </div>

            <div class="form-row">
                <div class="form-label">メイ <span class="required">必須</span></div>
                <input type="text" name="first_name_kana" value="<?= htmlspecialchars($old['first_name_kana'] ?? '') ?>"
                    placeholder="例：タロウ">
            </div>

            <div class="form-row">
                <div class="form-label">メールアドレス <span class="required">必須</span></div>
                <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                    placeholder="Emart@emart.com">
            </div>

            <div class="form-row">
                <div class="form-label">メールアドレス（再入力） <span class="required">必須</span></div>
                <input type="email" name="email_confirm" value="<?= htmlspecialchars($old['email_confirm'] ?? '') ?>"
                    placeholder="Emart@emart.com">
            </div>
        </section>


        <!-- ==============================
             住所
        ================================= -->
        <section class="form-section">
            <h3>住所</h3>

            <!-- 郵便番号 -->
            <div class="form-row">
                <div class="form-label">郵便番号 <span class="required">必須</span></div>
                <input type="text" name="postal_code" value="<?= htmlspecialchars($old['postal_code'] ?? '') ?>"
                    placeholder="例：8120012">
            </div>

            <!-- 都道府県 -->
            <div class="form-row">
                <div class="form-label">都道府県 <span class="required">必須</span></div>

                <select name="prefecture">
                    <option value="">選択してください</option>
                    <?php
                    $prefs = [
                        "北海道",
                        "青森県",
                        "岩手県",
                        "宮城県",
                        "秋田県",
                        "山形県",
                        "福島県",
                        "茨城県",
                        "栃木県",
                        "群馬県",
                        "埼玉県",
                        "千葉県",
                        "東京都",
                        "神奈川県",
                        "新潟県",
                        "富山県",
                        "石川県",
                        "福井県",
                        "山梨県",
                        "長野県",
                        "岐阜県",
                        "静岡県",
                        "愛知県",
                        "三重県",
                        "滋賀県",
                        "京都府",
                        "大阪府",
                        "兵庫県",
                        "奈良県",
                        "和歌山県",
                        "鳥取県",
                        "島根県",
                        "岡山県",
                        "広島県",
                        "山口県",
                        "徳島県",
                        "香川県",
                        "愛媛県",
                        "高知県",
                        "福岡県",
                        "佐賀県",
                        "長崎県",
                        "熊本県",
                        "大分県",
                        "宮崎県",
                        "鹿児島県",
                        "沖縄県"
                    ];

                    foreach ($prefs as $p):
                        $selected = ($old['prefecture'] ?? '') === $p ? "selected" : "";
                        ?>
                        <option value="<?= $p ?>" <?= $selected ?>><?= $p ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- 市区町村 -->
            <div class="form-row">
                <div class="form-label">市区町村 <span class="required">必須</span></div>
                <input type="text" name="city" value="<?= htmlspecialchars($old['city'] ?? '') ?>"
                    placeholder="例：福岡市博多区">
            </div>

            <!-- 番地 -->
            <div class="form-row">
                <div class="form-label">番地 <span class="required">必須</span></div>
                <input type="text" name="street" value="<?= htmlspecialchars($old['street'] ?? '') ?>"
                    placeholder="例：博多駅前1-2-3">
            </div>

            <!-- 建物名 -->
            <div class="form-row">
                <div class="form-label">建物名</div>
                <input type="text" name="building" value="<?= htmlspecialchars($old['building'] ?? '') ?>"
                    placeholder="例：博多ビル301">
            </div>

            <!-- 電話番号 -->
            <div class="form-row">
                <div class="form-label">電話番号 <span class="required">必須</span></div>
                <input type="text" name="tel" value="<?= htmlspecialchars($old['tel'] ?? '') ?>"
                    placeholder="例：0921234567">
            </div>

            <!-- 携帯 -->
            <div class="form-row">
                <div class="form-label">携帯電話</div>
                <input type="text" name="mobile" value="<?= htmlspecialchars($old['mobile'] ?? '') ?>"
                    placeholder="例：09012345678">
            </div>

            <!-- FAX -->
            <div class="form-row">
                <div class="form-label">FAX番号</div>
                <input type="text" name="fax" value="<?= htmlspecialchars($old['fax'] ?? '') ?>"
                    placeholder="例：0921234567">
            </div>

        </section>


        <!-- ==============================
             アンケート
        ================================= -->
        <section class="form-section">
            <h3>アンケート</h3>

            <div class="checkbox-row">
                <label><input type="checkbox" name="survey[]" value="post_mail"> 郵便チラシ</label>
                <label><input type="checkbox" name="survey[]" value="internet"> インターネットで検索した</label>
                <label><input type="checkbox" name="survey[]" value="friend"> 知人に聞いた</label>
                <label><input type="checkbox" name="survey[]" value="newspaper"> 新聞広告・雑誌</label>
                <label><input type="checkbox" name="survey[]" value="tv"> テレビ</label>
                <label><input type="checkbox" name="survey[]" value="sns"> Twitter・Facebook・SNS</label>
                <label><input type="checkbox" name="survey[]" value="other"> その他</label>
            </div>
        </section>


        <!-- ==============================
             パスワード
        ================================= -->
        <section class="form-section">
            <h3>パスワードを設定する</h3>

            <div class="form-row">
                <div class="form-label">パスワード <span class="required">必須</span></div>
                <input type="password" name="password" placeholder="半角英数8文字以上">
            </div>

            <div class="form-row">
                <div class="form-label">パスワード（再入力） <span class="required">必須</span></div>
                <input type="password" name="password_confirm" placeholder="確認のため、再度入力してください">
            </div>
        </section>


        <!-- ==============================
             メールマガジン
        ================================= -->
        <section class="form-section">
            <h3>メールマガジン</h3>

            <label class="checkbox-row">
                <input type="checkbox" name="mail_magazine" value="1" <?= isset($old['mail_magazine']) ? 'checked' : '' ?>>
                クーポン・キャンペーンなどのお得な情報を希望する
            </label>
        </section>


        <!-- ==============================
             利用規約
        ================================= -->
        <section class="form-section">
            <h3>以下の内容をご確認の上お申し込みください</h3>

            <label class="checkbox-row">
                <input type="checkbox" name="agree" required>
                ご利用規約（一般無機物販売）とプライバシーポリシーに同意します。
            </label>
        </section>


        <!-- ==============================
             送信ボタン
        ================================= -->
        <div class="form-submit">
            <button type="submit" class="submit-btn">送信</button>
        </div>

    </form>
</div>

<?php include_once("../../components/footer.php"); ?>