<?php
// 共通変数・関数ファイルを読み込み
require('function.php');

// デバッグ
debug('**********************************************');
debug('************* パスワード再発行ページ *************');
debug('**********************************************');
debugLogStart();

// ================================
// 画面処理
// ================================
// $_SESSION['auth_key']が入っているか
if (empty($_SESSION['auth_key'])) {
    debug('!!!!! 認証キーがないor時間切れのため、認証キー発行ページへ移行します !!!!!');
    header("Location:passReissue.php");
    exit();
}

// POST送信があるか
if (!empty($_POST)) {
    debug('$_POSTの中身→→→' . print_r($_POST, true));

    // 変数に代入
    $token = $_POST['token'];

    // 未入力チェック
    validRequired($token, 'token');

    // バリデーション
    if (empty($err_msg)) {
        // 半角英数字チェック
        validHalfAlphanumeric($token, 'token');
        // 固定長チェック
        validLength($token, 'token', 8);

        if (empty($err_msg)) {
            if ($token !== $_SESSION['auth_key']) {
                $err_msg['token'] = MSG14;
            }
            if (time() > $_SESSION['auth_key_limit']) {
                $err_msg['token'] = MSG15;
            }

            if (empty($err_msg)) {
                debug('***** 認証「OK」 *****');

                // 仮パスワード生成
                $tentative_pass = makeRandKey();
                debug('仮のパスワード($tentative_pass)の中身→→→' . print_r($tentative_pass, true));

                // 例外処理
                try {
                    // DB接続
                    $dbh = dbConnect();
                    // SQL文作成
                    $sql = 'UPDATE users SET password = :pass WHERE email = :email AND is_deleted = 0';
                    $data = array(':pass' => password_hash($tentative_pass, PASSWORD_DEFAULT), ':email' => $_SESSION['auth_email']);
                    // クエリ実行
                    $stmt = queryPost($dbh, $sql, $data);

                    // クエリ成功
                    if ($stmt) {
                        debug('***** メールを送信します *****');

                        // メール送信
                        $from = 'pluvia.kk@gmail.com';
                        $to = $_SESSION['auth_email'];
                        $subject = '【仮パスワードを発行しました | イベ探';
                        $comment = <<<EOT
本メールアドレス宛にパスワードの再発行を致しました。
下記のURLにて再発行パスワードをご入力いただき、ログインしてください。

ログインページ：http://localhost:8888/webs-OP/login.php
再発行パスワード：{$tentative_pass}
※ログイン後、パスワードのご変更をお願いいたします。

********************************
イベ探カスタマーセンター
URL xxxxxx
E-mail xxxxx
********************************
EOT;
                        sendMail($from, $to, $subject, $comment);

                        // セッションを削除する
                        session_unset();
                        $_SESSION['msg_success'] = SUC03;
                        debug('$_SESSIONの中身→→→' . print_r($_SESSION, true));
                        debug('***** ログインページへ遷移します *****');
                        header("Location:login.php");
                        exit();
                    } else {
                        debug('!!!!! クエリ失敗 !!!!!');
                        $err_msg['common'] = MSG07;
                    }
                } catch (Exception $e) {
                    error_log('!!!!!  エラー発生 !!!!!' . $e->getMessage());
                    $err_msg['common'] = MSG07;
                }
            }
        }
    }
}

?>
<?php
$title = "パスワード再発行認証キー送信 | イベ探";
require('head.php');
?>

<body class="page-login page-1colum">
    <div class="wrapper">
        <!-- サクセスメッセージ -->
        <div id="js-show-msg" class="js-success-msg">
            <?php echo getSessionOnce('msg_success'); ?>
        </div>
        <!-- header -->
        <?php
        require('header.php');
        ?>
        <!-- メインコンテンツ -->
        <div class="main-container site-width">
            <main class="main login-main">
                <div class="main-form-container">
                    <div class="area-msg">
                        <?php
                        errorMsgCall('common');
                        ?>
                    </div>
                    <h2 class="title-pass_reissue">パスワード再発行</h2>
                    <div class="login-panel-group">
                        <div class="login-panel panel-login is-show">
                            <form method="post" class="login-form">
                                <p class="p-login-form">入力されたメールアドレス宛に送信された【パスワード再発行用の認証キー】を入力してください。</p>
                                <label class="label login-label">
                                    <div class="area-msg">
                                        <?php
                                        errorMsgCall('token');
                                        ?>
                                    </div>
                                    パスワード再発行用認証キー<br>
                                    <input type="text" class="input-text -login <?php classErrorCall('token'); ?>" name="token">
                                </label>
                                <p class="p-login-form">メールが届かなかった場合は、<a href="passReissue.php" class="a a-under">こちら</a>から再びメールアドレスを入力してください。</p>
                                <div class="login-submit-container -pass_reissue">
                                    <input type="submit" class="submit login-submit -login" value="送信する">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="return-top">
                    <a href="index.php">TOPページに戻る</a>
                </div>
            </main>
        </div>
        <?php
        require('footer.php');
        ?>