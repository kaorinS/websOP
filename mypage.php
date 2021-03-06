<?php
// 共通変数・関数を読み込む
require('function.php');

// デバッグ
debug('**********************************************');
debug('****************** マイページ ******************');
debug('**********************************************');
debugLogStart();

// ログイン認証
require('auth.php');

// ================================
// 画面処理
// ================================
// 画面表示用データ取得
// ================================
// ユーザーIDを取得
$u_id = $_SESSION['user_id'];
// ユーザー情報を取得
$u_info = getUser($u_id);
// debug('$u_info' . print_r($u_info, true));
// 都道府県情報があった場合、代入する
$u_pref = (!empty($u_info['pref'])) ? $u_info['pref'] : '';
// 都道府県情報があった場合、イベントを取得
$prefEventData = getMyPrefEvent($u_pref);
// debug('$prefEventDataの中身→→→' . print_r($prefEventData, true));
// 自分が作成したイベント情報を取得
$myCreatedData = getMyEventData($u_id);
$myEventData = $myCreatedData['data'];
// debug('$myCreatedDataの中身→→→' . print_r($myCreatedData, true));
// debug('$myEventData' . print_r($myEventData, true));
// お気に入りデータを取得
$myFavo = getMyLike($u_id);
$myFavoData = $myFavo['data'];

?>
<?php
$title = 'マイページ　|　イベ探';
require('head.php');
?>

<body class="page-mypage page-2colum">
    <div class="wrapper">
        <!-- サクセスメッセージ -->
        <div id="js-show-msg" class="js-success-msg">
            <?php echo getSessionOnce('msg_success'); ?>
        </div>
        <!-- header -->
        <?php
        require('header.php');
        ?>
        <!-- メインコンテンツ  -->
        <div class="main-container site-width">
            <!-- サイドバー -->
            <?php
            $pageName = '';
            require('sidebar_mypage.php');
            ?>
            <!-- メイン -->
            <main class="main">
                <!-- 新着イベント -->
                <?php if (!empty($u_info['pref'])) : ?>
                    <section class="sec-mypage -area">
                        <div class="mypage-title-wrap">
                            <h2 class="mypage-title -area">
                                <?= prefNameCalled($u_info['pref']) ?>の新着イベント
                            </h2>
                            <?php if (!empty($prefEventData[2])) : ?>
                                <div class="mypage-title-jump -area">
                                    <a href="index.php?pref=<?= $u_info['pref'] ?>" class="a-mypage-main -more">>> 一覧を見る</a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($prefEventData)) : ?>
                            <div class="panel-list">
                                <?php foreach ($prefEventData as $key => $val) {
                                    require('panel.php');
                                } ?>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>
                <!-- 作成したイベント -->
                <?php if (!empty($myEventData)) : ?>
                    <section class="sec-mypage -created">
                        <div class="mypage-title-wrap">
                            <h2 class="mypage-title -created">
                                作成したイベント
                            </h2>
                            <?php if (!empty($myEventData[2])) : ?>
                                <div class="mypage-title-jump -created">
                                    <a href="mypageCreated.php" class="a-mypage-main -more">>> 一覧を見る</a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="panel-list">
                            <?php foreach ($myEventData as $key => $val) {
                                require('panel.php');
                            } ?>
                        </div>
                    </section>
                <?php endif; ?>
                <!-- お気に入り -->
                <section class="sec-mypage -favo">
                    <div class="mypage-title-wrap">
                        <h2 class="mypage-title -favo">
                            お気に入り
                        </h2>
                        <?php if (!empty($myFavoData[2])) : ?>
                            <div class="mypage-title-jump -favo">
                                <a href="mypageFavo.php" class="a-mypage-main -more">>> 一覧を見る</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="panel-list">
                        <?php if (!empty($myFavoData)) : ?>
                            <?php foreach ($myFavoData as $key => $val) {
                                require('panel.php');
                            } ?>
                        <?php else : ?>
                            <div class="no-favo-msg">
                                お気に入りに登録されたイベントがありません
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
                <!-- <section class="sec-mypage -comment">
                    <div class="mypage-title-wrap">
                        <h2 class="mypage-title -comment">
                            コメントしたイベント
                        </h2>
                    </div>
                    <div class="panel-list">
                        <a href="eventDetail.php" class="panel">
                            <div class="panel-body">
                                <img src="images/coffee1.jpg" class="img -index" alt="">
                                <p class="panel-pref hokkaido">北海道</p>
                                <p class="panel-title">
                                    <span class="panel-date">2020年4月1日</span><br>
                                    コーヒー試飲会
                                </p>
                            </div>
                        </a>
                    </div>
                </section> -->
            </main>
        </div>
        <?php
        require('footer.php');
        ?>