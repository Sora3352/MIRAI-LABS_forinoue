<?php
// 共通ヘッダーの読み込み
include_once("../components/header.php");
?>

<main class="container">
    <div id="app">
        <h2>{{ message }}</h2>
        <p>ようこそ <strong>MIRAI-LABS E-mart</strong> へ！</p>
        <p>このページは Vue.js が動作しているテストページです。</p>
    </div>
</main>

<?php
// 共通フッターの読み込み
include_once("../components/footer.php");
?>

<!-- ===== Vue.js & JS 読み込み ===== -->
<script src="https://unpkg.com/vue@3"></script>
<script src="../asset/js/vue/app.js"></script>
<link rel="stylesheet" href="../asset/css/style.css">