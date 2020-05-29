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
// POST送信されているか
if (!empty($_POST)) {
    debug('$_POSTの中身→→→' . print_r($_POST, true));

    // 変数に代入
    $email = $_POST['mail'];

    // 未入力チェック
    validRequired($email, 'mail');

    if (empty($err_msg)) {
        // 形式チェック
        validEmail($email, 'mail');
        // 最大文字数チェック
        validMaxLen($email, 'mail');

        if (empty($err_msg)) {
            // 例外処理
            try {
                // DB接続
                $dbh = dbConnect();
                // SQL文作成
                $sql = 'SELECT count(*) FROM users WHERE email = :email AND is_deleted = 0';
                $data = array(':email' => $email);
                // クエリ実行
                $stmt = queryPost($dbh, $sql, $data);
                // クエリ結果の値を取得
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                debug('$resultの中身→→→' . print_r($result, true));

                // EmailがDBに登録されているか
                if ($stmt && array_shift($result)) {
                    debug('***** クエリ成功 DBにメールアドレスの存在を確認 *****');
                    $_SESSION['msg_success'] = SUC03;

                    // 認証キーを生成する
                    $auth_key = makeRandKey();

                    debug('***** メールを送信します *****');

                    // メールを送信する
                    $from = 'pluvia.kk@gmail.com';
                    $to = $email;
                    $subject = '【パスワード再発行用認証キー】｜イベ探';
                    $comment = <<<EOT
本メールアドレス宛にパスワード再発行のご依頼がありました。
下記のURLにて認証キーをご入力頂くとパスワードが再発行されます。

パスワード再発行認証キー入力ページ：http://localhost:8888/webs-OP/passReissueAuth.php
認証キー：{$auth_key}
※認証キーの有効期限は30分となります

認証キーを再発行されたい場合は、下記ページより再発行をお願い致します。
http://localhost:8888/webS-P/passRemindSend.php

********************************
イベ探カスタマーセンター
URL xxxxxx
E-mail xxxxx
********************************
EOT;
                    sendMail($from, $to, $subject, $comment);

                    // 認証に必要な情報をセッションに入れる
                    $_SESSION['auth_key'] = $auth_key;
                    $_SESSION['auth_email'] = $email;
                    // 認証可能時間は30分（60s ＊ 30m)
                    $_SESSION['auth_key_limit'] = time() + (60 * 30);
                    debug('$_SESSIONの中身→→→' . print_r($_SESSION, true));
                    debug('***** 認証キー送信ページえへ遷移 *****');
                    header("Location:passReissueAuth.php");
                    exit;
                }
            } catch (Exception $e) {
                error_log('!!!!! エラー発生 !!!!!' . $e->getMessage());
                $err_msg['common'] = MSG07;
            }
        }
    }
}
?>
<?php
$title = "パスワード再発行 | イベ探";
require('head.php');
?>

<body class="page-login page-1colum">
    <div class="wrapper">
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
                                <p class="p-login-form">ご登録のメールアドレス宛にパスワード再発行用の認証キーをお送りします。</p>
                                <label class="label login-label">
                                    <div class="area-msg">
                                        <?php
                                        errorMsgCall('mail');
                                        ?>
                                    </div>
                                    登録したメールアドレス<br>
                                    <input type="text" class="input-text -login <?php classErrorCall('mail'); ?>" name="mail">
                                </label>
                                <div class="login-submit-container -pass_reissue">
                                    <input type="submit" class="submit login-submit -login" value="送信する">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <a href="index.php">
                    <div class="back-index-login">TOPページに戻る</div>
                </a>
            </main>
        </div>
        <?php
        require('footer.php');
        ?>