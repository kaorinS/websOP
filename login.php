<?php
// 共通変数・関数ファイルを読み込み
require('function.php');

// デバッグ
debug('****************************************');
debug('********** ログイン & ユーザー登録ページ **********');
debug('****************************************');
debugLogStart();

// ================================
// ユーザー登録
// ================================
// POST送信されていた場合
if (!empty($_POST)) {
  debug('POSTの中身→→→' . print_r($_POST, true));
  // 変数にユーザー情報を代入
  $email_regist = $_POST['email_regist'];
  $pass_regist = $_POST['pass_regist'];
  $pass_re = $_POST['pass_re'];

  // 未入力チェック
  validRequired($email_regist, 'email_regist');
  validRequired($pass_regist, 'pass_regist');
  validRequired($pass_re, 'pass_re');

  if (empty($err_msg)) {

    // Email形式チェック
    validEmail($email_regist, 'email_regist');
    // Emailの最大文字数チェック
    validMaxLen($email_regist, 'email_regist');
    // Email重複チェック
    validEmailDup($email_regist);

    // パスワードの半角英数字チェック
    validHalfAlphanumeric($pass_regist, 'pass_regist');
    // パスワードの最大文字数チェック
    validMaxLen($pass_regist, 'pass_regist');
    // パスワードの最小文字数チェック
    validMinLen($pass_regist, 'pass_regist');

    // パスワード再入力の最大文字数チェック
    validMaxLen($pass_re, 'pass_re');
    // パスワード再入力の最小文字数チェック
    validMinLen($pass_re, 'pass_re');

    if (empty($err_msg)) {
      // パスワードとパスワード再入力が同じか
      validPassRe($pass_regist, $pass_re, 'pass_re');

      if (empty($err_msg)) {
        // 例外処理
        try {
          // DBへ接続
          $dbh = dbConnect();
          // SQL文作成
          $sql = 'INSERT INTO users (email,password,login_time,created_at) VALUES (:email,:pass,:login_time,:created_at)';
          $data = array(':email' => $email_regist, ':pass' => password_hash($pass_regist, PASSWORD_DEFAULT), ':login_time' => date('Y-m-d H:i:s'), ':created_at' => date('Y-m-d H:i:s'));
          // クエリ実行
          $stmt = queryPost($dbh, $sql, $data);

          // クエリ成功の場合
          if ($stmt) {
            // ログイン有効期限(デフォルト=1時間)
            $sesLimit = 60 * 60;
            // 最終ログイン日時を現在日時に変更
            $_SESSION['login_date'] = time();
            // ログイン有効期限を変更
            $_SESSION['login_limit'] = $sesLimit;
            // ユーザーIDを格納
            $_SESSION['user_id'] = $dbh->lastInsertId();

            debug('$_SESSIONの中身→→→' . print_r($_SESSION, true));

            header("Location:mypage.php");
            exit;
          }
        } catch (Exception $e) {
          error_log('***** エラー発生 *****' . $e->getMessage());
          $err_msg['common'] = MSG07;
        }
      }
    }
  }
}
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
            <!-- 新規登録 -->
            <div class="login-panel panel-entry">
              <form method="post" class="login-form">
                <div class="area-msg">
                  <?php
                  errorMsgCall('common');
                  ?>
                </div>
                <label class="label login-label">
                  ユーザーネーム<br>
                  <input type="text" class="input-text -entry" name="username">
                </label>
                <label class="label login-label">
                  <div class="area-msg">
                    <?php
                    errorMsgCall('email_regist');
                    ?>
                  </div>
                  メールアドレス<br>
                  <input type="text" class="input-text -entry <?php classErrorCall('email_regist'); ?>" name="email_regist">
                </label>
                <label class="label login-label">
                  <div class="area-msg">
                    <?php
                    errorMsgCall('pass_regist');
                    ?>
                  </div>
                  パスワード<span>※英数字6文字以上</span><br>
                  <input type="password" class="input-text -entry <?php classErrorCall('pass_regist'); ?>" name="pass_regist">
                </label>
                <label class="label login-label">
                  <div class="area-msg">
                    <?php
                    errorMsgCall('pass_re');
                    ?>
                    パスワード再入力<br>
                    <input type="password" class="input-text -entry <?php classErrorCall('pass_re'); ?>" name="pass_re">
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