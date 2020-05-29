<?php
// 共通変数・関数を読み込む
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
// DBからユーザー情報を取得
$userInfo = getUser($_SESSION['user_id']);
debug('***** ユーザー情報を取得 *****');
debug('$userInfoの中身→→→' . print_r($userInfo, true));

// POST送信されているか
if (!empty($_POST)) {
    debug('$_POSTの中身→→→' . print_r($_POST, true));

    // POSTの中身を変数に代入
    $pass_old = $_POST['pass_old'];
    $pass_new = $_POST['pass_new'];
    $pass_new_re = $_POST['pass_new_re'];

    // バリデーション
    // 未入力チェック
    validRequired($pass_old, 'pass_old');
    validRequired($pass_new, 'pass_new');
    validRequired($pass_new_re, 'pass_new_re');

    if (empty($err_msg)) {

        // 現在のパスワードチェック
        validPass($pass_old, 'pass_old');
        // 新しいパスワードのチェック
        validPass($pass_new, 'pass_new');

        // 現在のパスワードとDBに登録されたパスワードを照合する
        if (!password_verify($pass_old, $userInfo['password'])) {
            global $err_msg;
            $err_msg['pass_old'] = MSG11;
        }

        // 現在のパスワードと新しいパスワードが異なっているか
        if ($pass_old === $pass_new) {
            global $err_msg;
            $err_msg['pass_new'] = MSG12;
        }

        // 新しいパスワードと新しいパスワード(再入力)が同じか
        validPassRe($pass_new, $pass_new_re, 'pass_new_re');

        if (empty($err_msg)) {
            debug('***** バリデーション 「OK」 *****');

            // 例外処理
            try {
                // DB接続
                $dbh = dbConnect();
                // sql文作成
                $sql = 'UPDATE users SET `password` = :password WHERE id = :id';
                $data = array(':password' => password_hash($pass_new, PASSWORD_DEFAULT), ':id' => $userInfo['id']);
                // クエリ実行
                $stmt = queryPost($dbh, $sql, $data);

                if ($stmt) {
                    // サクセスメッセージ設定
                    $_SESSION['msg_success'] = SUC01;

                    debug('***** メールを送信します *****');

                    // メールを送信
                    $username = ($userInfo['username']);
                    $from = 'pluvia.kk@gmail.com';
                    $to = $userInfo['email'];
                    $subject = 'パスワード変更通知 | イベ探';
                    // EOTはEndOfTextの略。任意の文字可能（ABCとかTEXTとか)。ただし、先頭の<<<は必須。前後に空白を入れるのもダメ
                    // 注意!EOT内は半角空白も全てそのまま扱われるため、インデントしてはいけない!!!!
                    $comment = <<<EOT
{$username}さん
パスワードが変更されました。

********************************
イベ探カスタマーセンター
URL xxxxxxxxxx
E-mail xxxxxxxxxx
********************************
EOT;
                    sendMail($from, $to, $subject, $comment);

                    debug('***** マイページへ遷移します *****');
                    header("Location:mypage.php");
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
$title = 'パスワード編集　|　イベ探';
require('head.php');
?>

<body class="page-mypage page-2colum">
    <div class="wrapper">
        <!-- header -->
        <?php
        require('header.php');
        ?>
        <!-- メインコンテンツ  -->
        <div class="main-container site-width">
            <!-- サイドバー -->
            <?php
            $pageName = 'passEdit';
            require('sidebar_mypage.php');
            ?>
            <!-- メイン -->
            <main class="main">
                <section class="sec-mypage -prof_edit">
                    <div class="area-msg">
                        <?php
                        errorMsgCall('common');
                        ?>
                    </div>
                    <h2 class="mypage-edit-title">
                        パスワード変更
                    </h2>
                    <div class="mypage-edit-body">
                        <form method="post" class="form-mypage-edit -pass">
                            <label class="label label-mypage-edit">
                                <div class="area-msg">
                                    <?php
                                    errorMsgCall('pass_old');
                                    ?>
                                </div>
                                現在のパスワード<br>
                                <input type="password" class="input-text -mypage-edit <?php classErrorCall('pass_old'); ?>" name="pass_old">
                            </label>
                            <label class="label label-mypage-edit">
                                <div class="area-msg">
                                    <?php
                                    errorMsgCall('pass_new');
                                    ?>
                                </div>
                                新しいパスワード<br>
                                <input type="password" class="input-text -mypage-edit <?php classErrorCall('pass_new'); ?>" name="pass_new">
                            </label>
                            <label class="label label-mypage-edit">
                                <div class="area-msg">
                                    <?php
                                    errorMsgCall('pass_new_re');
                                    ?>
                                </div>
                                新しいパスワード（再入力）<br>
                                <input type="password" class="input-text -mypage-edit <?php classErrorCall('pass_new_re'); ?>" name="pass_new_re">
                            </label>
                            <div class="submit-container-mypage-edit">
                                <input type="submit" class="submit submit-mypage-edit" value="変更する">
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
        <?php
        require('footer.php');
        ?>