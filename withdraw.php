<?php
// 共通変数・関数を読み込む
require('function.php');

// デバッグ
debug('**********************************************');
debug('****************** 退会ページ ******************');
debug('**********************************************');
debugLogStart();

// ログイン認証
require('auth.php');

// ================================
// 画面処理
// ================================
// POST送信されているか
if (!empty($_POST)) {
    debug('$_POSTの中身→→→' . print_r($_POST, true));

    // 例外処理
    try {
        // DB接続
        $dbh = dbConnect();
        // SQL文作成
        $sql1 = 'UPDATE users SET is_deleted = 1 WHERE id = :id';
        $sql2 = 'UPDATE festival SET is_deleted = 1 WHERE u_id = :id';
        $sql3 = 'UPDATE favo SET is_deleted = 1 WHERE u_id = :id';
        $data = array(':id' => $_SESSION['user_id']);
        // クエリ実行
        $stmt1 = queryPost($dbh, $sql1, $data);
        $stmt2 = queryPost($dbh, $sql2, $data);
        $stmt3 = queryPost($dbh, $sql3, $data);
        // クエリ成功
        if ($stmt1 && $stmt2 && $stmt3) {
            debug('***** 退会処理 クエリ成功 *****');
            debug('***** セッション削除 *****');

            // セッション削除
            session_destroy();

            debug('$_SESSIONの中身→→→' . print_r($_SESSION, true));
            debug('TOPページへ遷移');
            header("Location:index.php");
            exit();
        } else {
            debug('!!!!! 退会処理 クエリ失敗 !!!!!');
            $err_msg['common'] = MSG07;
        }
    } catch (Exception $e) {
        error_log('!!!!! エラー発生 !!!!!' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
?>
<?php
$title = '退会　|　イベ探';
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
            $pageName = 'withdraw';
            require('sidebar_mypage.php');
            ?>
            <!-- メイン -->
            <main class="main">
                <section class="sec-mypage -prof_edit">
                    <h2 class="mypage-edit-title">
                        退会
                    </h2>
                    <div class="mypage-edit-body">
                        <form action="" method="post" class="form-mypage-edit -withdraw">
                            <h3 class="title-withdraw">本当に退会しますか？</h3>
                            <div class="area-msg">
                                <?php
                                errorMsgCall('common');
                                ?>
                            </div>
                            <div class="submit-container-mypage-edit -withdraw">
                                <input type="submit" class="submit submit-mypage-edit -withdraw" value="退会する" name="submit">
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
        <?php
        require('footer.php');
        ?>