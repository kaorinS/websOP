<?php
// 共通変数・関数ファイルを読み込み
require('function.php');

// デバッグ
debug('****************************************');
debug('********** ログインページ **********');
debug('****************************************');
debugLogStart();
?>

<?php
$title = "ログイン/新規登録 | イベ探";
require('head.php');
?>

<body class="page-login page-1colum">
  <div class="wrapper">
    <?php
    require('header.php');
    ?>
    <div class="main-container site-width">
      <main class="main login-main">
        <div class="main-form-container">
          <!-- タブ -->
          <ul class="ul-login">
            <li class="login-li li-entry">新規登録</li>
            <li class="login-li li-login">ログイン</li>
          </ul>
          <!-- パネル -->
          <div class="login-panel-group">
            <!-- 新規作成 -->
            <div class="login-panel panel-entry">
              <form method="post" class="login-form">
                <label class="label login-label">
                  ユーザーネーム<br>
                  <input type="text" class="input-text -entry" name="username">
                </label>
                <label class="label login-label">
                  メールアドレス<br>
                  <input type="text" class="input-text -entry" name="mail">
                </label>
                <label class="label login-label">
                  パスワード<span>※6文字以上</span><br>
                  <input type="password" class="input-text -entry" name="pass">
                </label>
                <label class="label login-label">
                  パスワード再入力<br>
                  <input type="password" class="input-text -entry" name="re_pass">
                </label>
                <div class="login-submit-container">
                  <input type="submit" class="submit login-submit -entry" value="登録">
                </div>
              </form>
            </div>
            <!-- ログイン -->
            <div class="login-panel panel-login is-show">
              <form method="post" class="login-form">
                <label class="label login-label">
                  メールアドレス<br>
                  <input type="text" class="input-text -login" name="mail">
                </label>
                <label class="label login-label">
                  パスワード<span class="login-span-pass">※6文字以上</span><br>
                  <input type="password" class="input-text -login" name="pass">
                </label>
                <p class="login-passforget">パスワードを忘れた方は<a href="passReissue.php" class="login-a-passforget">こちら</a>から再発行の手続きへ</p>
                <label class="label login-save">
                  <input type="checkbox" name="login_save">次回ログインを省略する
                </label>
                <div class="login-submit-container"><input type="submit" class="submit login-submit -login" value="ログイン"></div>
              </form>
            </div>
          </div>
        </div>
        <div class="back-index-login">
          <a href="index.php">TOPページに戻る</a>
        </div>
      </main>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/login.js"></script>
    <?php
    require('footer.php');
    ?>