<?php
// 共通変数・関数ファイルを読み込み
require('function.php');

// デバッグ
debug('**********************************************');
debug('********** ログイン & ユーザー登録ページ **********');
debug('**********************************************');
debugLogStart();

// ログイン認証
require('auth.php');

// ================================
// 画面処理
// ================================
// POST送信されていた場合
if (!empty($_POST)) {
  debug('$_POSTの中身→→→' . print_r($_POST, true));

  // $_POSTのキーを調べる
  $key = key($_POST);
  debug('$keyの中身→→→' . print_r($key, true));

  // タブ切り替え用変数設置
  $tab = 'login';

  // ================================
  // 新規登録
  // ================================
  if ($key === 'username') {
    debug('********** 新規登録の処理開始 **********');

    // 変数にユーザー情報を代入
    $username = $_POST['username'];
    $email_regist = $_POST['email_regist'];
    $pass_regist = $_POST['pass_regist'];
    $pass_re = $_POST['pass_re'];

    // タブ切り替え用変数変更
    $tab = 'regist';

    // 未入力チェック
    validRequired($username, 'username');
    validRequired($email_regist, 'email_regist');
    validRequired($pass_regist, 'pass_regist');
    validRequired($pass_re, 'pass_re');

    if (empty($err_msg)) {
      // ユーザーネーム
      // 最大文字数チェック
      validMaxLen($username, 'username');

      // Email
      // 形式チェック
      validEmail($email_regist, 'email_regist');
      // 最大文字数チェック
      validMaxLen($email_regist, 'email_regist');
      // 重複チェック
      validEmailDup($email_regist);

      // パスワード
      // 半角英数字チェック
      validHalfAlphanumeric($pass_regist, 'pass_regist');
      // 最大文字数チェック
      validMaxLen($pass_regist, 'pass_regist');
      // 最小文字数チェック
      validMinLen($pass_regist, 'pass_regist');

      // パスワード再入力
      // 最大文字数チェック
      validMaxLen($pass_re, 'pass_re');
      // 最小文字数チェック
      validMinLen($pass_re, 'pass_re');

      if (empty($err_msg)) {
        // パスワードとパスワード再入力が同じか
        validPassRe($pass_regist, $pass_re, 'pass_re');

        if (empty($err_msg)) {
          debug('***** 「新規登録」バリデーションOK *****');

          // 例外処理
          try {
            // DBへ接続
            $dbh = dbConnect();
            // SQL文作成
            $sql = 'INSERT INTO users (username,email,password,login_time,created_at) VALUES (:username,:email,:pass,:login_time,:created_at)';
            $data = array(':username' => $username, ':email' => $email_regist, ':pass' => password_hash($pass_regist, PASSWORD_DEFAULT), ':login_time' => date('Y-m-d H:i:s'), ':created_at' => date('Y-m-d H:i:s'));
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);

            // クエリ成功の場合
            if ($stmt) {
              // ログイン有効期限設定(デフォルト=1時間)
              $sesLimit = 60 * 60;
              // 最終ログイン日時を現在日時に設定
              $_SESSION['login_date'] = time();
              // ログイン有効期限を変更
              $_SESSION['login_limit'] = $sesLimit;
              // ユーザーIDを格納
              $_SESSION['user_id'] = $dbh->lastInsertId();

              debug('$_SESSIONの中身→→→' . print_r($_SESSION, true));
              debug('***** マイページへ遷移 *****');
              header("Location:mypage.php");
              exit;
            }
          } catch (Exception $e) {
            error_log('***** エラー発生 *****' . $e->getMessage());
            $err_msg['common_regist'] = MSG07;
          }
        }
      }
    }
  } elseif ($key === 'email_login') {
    // ================================
    // ログイン
    // ================================
    debug('********** ログイン処理開始 **********');

    // 変数にユーザー情報を代入
    $email_login = $_POST['email_login'];
    $pass_login = $_POST['pass_login'];
    $login_save = (!empty($_POST['login_save'])) ? true : false;

    // 未入力チェック
    validRequired($email_login, 'email_login');
    validRequired($pass_login, 'pass_login');

    // Email
    // 形式チェック
    validEmail($email_login, 'email_login');
    // 最大文字数チェック
    validMaxLen($email_login, 'email_login');

    // パスワード
    // 半角英数字チェック
    validHalfAlphanumeric($pass_login, 'pass_login');
    // 最小文字数チェック
    validMinLen($pass_login, 'pass_login');
    // 最大文字数チェック
    validMaxLen($pass_login, 'pass_login');

    if (empty($err_msg)) {
      debug('***** 「ログイン」バリデーションOK *****');

      // 例外処理
      try {
        // DB接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT id,password FROM users WHERE email = :email AND is_deleted = 0';
        $data = array(':email' => $email_login);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        // クエリ結果の値を取得
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        debug('クエリ実行結果($result)の値→→→' . print_r($result, true));

        // パスワード照合
        if (!empty($result) && password_verify($pass_login, $result['password'])) {
          debug('***** パスワード照合OK *****');

          // ログイン有効期限設定(1時間)
          $sesLimit = 60 * 60;
          // 最終ログイン日時を現在日時に設定
          $_SESSION['login_date'] = time();
          // ログイン保持にチェックがあるか
          if ($login_save) {
            debug('***** ログイン保持 「有効」 *****');
            // ログイン有効時間を30日に設定
            $_SESSION['login_limit'] = $sesLimit * 24 * 30;
          } else {
            debug('***** ログイン保持 「無効」 *****');
            // ログイン有効時間をデフォルトの1時間に設定
            $_SESSION['login_limit'] = $sesLimit;
          }
          // ユーザーIDを格納
          $_SESSION['user_id'] = $result['id'];

          debug('$_SESSIONの中身→→→' . print_r($_SESSION, true));
          debug('***** マイページへ遷移 *****');
          header("Location:mypage.php");
          exit;
        } else {
          debug('!!!!! パスワード照合NG !!!!!');
          $err_msg['common_login'] = MSG09;
        }
      } catch (Exception $e) {
        error_log('!!!!!!!!!! エラー発生 !!!!!!!!!!' . $e->getMessage());
        $err_msg['common_login'] = MSG07;
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
  <!-- サクセスメッセージ -->
  <div id="js-show-msg" class="js-success-msg">
    <?php echo getSessionOnce('msg_success'); ?>
  </div>
  <!-- ヘッダー -->
  <div class="wrapper">
    <?php
    require('header.php');
    ?>
    <!-- メインコンテンツ -->
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
            <div class="login-panel panel-entry <?php if (!empty($_POST) && $tab === 'regist') echo 'is-show'; ?>">
              <form method="post" class="login-form">
                <div class="area-msg">
                  <?php
                  errorMsgCall('common_regist');
                  ?>
                </div>
                <label class="label login-label">
                  <div class="area-msg">
                    <?php
                    errorMsgCall('username');
                    ?>
                  </div>
                  ユーザーネーム<br>
                  <input type="text" class="input-text -entry <?php classErrorCall('username'); ?>" name="username" value="<?php echo getFormData('username'); ?>">
                </label>
                <label class="label login-label">
                  <div class="area-msg">
                    <?php
                    errorMsgCall('email_regist');
                    ?>
                  </div>
                  メールアドレス<br>
                  <input type="text" class="input-text -entry <?php classErrorCall('email_regist'); ?>" name="email_regist" value="<?php echo getFormData('email_regist'); ?>">
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
                  </div>
                  パスワード再入力<br>
                  <input type="password" class="input-text -entry <?php classErrorCall('pass_re'); ?>" name="pass_re">
                </label>
                <div class="login-submit-container">
                  <input type="submit" class="submit login-submit -entry" value="登録">
                </div>
              </form>
            </div>
            <!-- ログイン -->
            <div class="login-panel panel-login <?php if (empty($_POST) || $tab !== 'regist') echo 'is-show'; ?>">
              <form method="post" class="login-form">
                <div class="area-msg">
                  <?php
                  errorMsgCall('common_login');
                  ?>
                </div>
                <label class="label login-label">
                  <div class="area-msg">
                    <?php
                    errorMsgCall('email_login');
                    ?>
                  </div>
                  メールアドレス<br>
                  <input type="text" class="input-text -login <?php classErrorCall('email_login'); ?>" name="email_login" value="<?php echo getFormData('email_login'); ?>">
                </label>
                <label class="label login-label">
                  <div class="area-msg">
                    <?php
                    errorMsgCall('pass_login');
                    ?>
                  </div>
                  パスワード<span class="login-span-pass">※6文字以上</span><br>
                  <input type="password" class="input-text -login <?php classErrorCall('pass_login'); ?>" name="pass_login">
                </label>
                <p class="login-passforget">パスワードを忘れた方は<a href="passReissue.php" class="login-a-passforget">こちらから再発行の手続きへ</a></p>
                <label class="label login-save">
                  <input type="checkbox" name="login_save">次回ログインを省略する
                </label>
                <div class="login-submit-container"><input type="submit" class="submit login-submit -login" value="ログイン"></div>
              </form>
            </div>
          </div>
        </div>
        <a href="index.php">
          <div class="back-index-login">TOPページに戻る</div>
        </a>
      </main>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/login.js"></script>
    <?php
    require('footer.php');
    ?>