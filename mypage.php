<?php
// 共通変数・関数を読み込む
require('function.php');
?>
<?php
$title = 'マイページ　|　イベ探';
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
            require('sidebar_mypage.php');
            ?>
            <!-- メイン -->
            <main class="main">
                <section class="sec-mypage -area">
                    <div class="mypage-title-wrap">
                        <h2 class="mypage-title -area">
                            関東エリアの新着イベント
                        </h2>
                        <div class="mypage-title-jump -area">
                            <a href="index.php" class="a-mypage-main -more">>> 一覧を見る</a>
                        </div>
                    </div>
                    <div class="panel-list">
                        <a href="eventDetail.php" class="panel">
                            <div class="panel-body">
                                <img src="images/coffee1.jpg" class="img -index" alt="">
                                <p class="panel-pref kanto">千葉県</p>
                                <p class="panel-title">
                                    <span class="panel-date">2020年4月1日</span><br>
                                    コーヒー試飲会
                                </p>
                            </div>
                        </a>
                        <a href="eventDetail.php" class="panel">
                            <div class="panel-body">
                                <img src="images/bread1.jpg" class="img -index">
                                <p class="panel-pref kanto">神奈川県</p>
                                <p class="panel-title">
                                    <span class="panel-date">2020年4月1日</span><br>
                                    親子パン教室
                                </p>
                            </div>
                        </a>
                        <a href="eventDetail.php" class="panel">
                            <div class="panel-body">
                                <img src="images/pflower1.jpg" class="img -index" alt="">
                                <p class="panel-pref kanto">東京都</p>
                                <p class="panel-title">
                                    <span class="panel-date">2020年4月1日</span><br>
                                    プリザーブドフラワーレッスン
                                </p>
                            </div>
                        </a>
                    </div>
                </section>
                <section class="sec-mypage -created">
                    <div class="mypage-title-wrap">
                        <h2 class="mypage-title -created">
                            作成したイベント
                        </h2>
                    </div>
                    <div class="panel-list">
                        <a href="eventDetail.php" class="panel">
                            <div class="panel-body">
                                <img src="images/bread1.jpg" class="img -index">
                                <p class="panel-pref kanto">神奈川県</p>
                                <p class="panel-title">
                                    <span class="panel-date">2020年4月1日</span><br>
                                    親子パン教室
                                </p>
                            </div>
                        </a>
                        <a href="eventDetail.php" class="panel">
                            <div class="panel-body">
                                <img src="images/coffee1.jpg" class="img -index" alt="">
                                <p class="panel-pref tokai">愛知県</p>
                                <p class="panel-title">
                                    <span class="panel-date">2020年4月1日</span><br>
                                    コーヒー試飲会
                                </p>
                            </div>
                        </a>
                    </div>
                </section>
                <section class="sec-mypage -favo">
                    <div class="mypage-title-wrap">
                        <h2 class="mypage-title -favo">
                            お気に入り
                        </h2>
                        <div class="mypage-title-jump -favo">
                            <a href="mypageFavo.php" class="a-mypage-main -more">>> 一覧を見る</a>
                        </div>
                    </div>
                    <div class="panel-list">
                        <a href="eventDetail.php" class="panel">
                            <div class="panel-body">
                                <img src="images/pflower1.jpg" class="img -index" alt="">
                                <p class="panel-pref kyusyu">鹿児島県</p>
                                <p class="panel-title">
                                    <span class="panel-date">2020年4月1日</span><br>
                                    プリザーブドフラワーレッスン
                                </p>
                            </div>
                        </a>
                        <a href="eventDetail.php" class="panel">
                            <div class="panel-body">
                                <img src="images/yukata1.jpg" class="img -index" alt="">
                                <p class="panel-pref kinki">滋賀県</p>
                                <p class="panel-title">
                                    <span class="panel-date">2020年4月1日</span><br>
                                    浴衣着付けレッスン
                                </p>
                            </div>
                        </a>
                        <a href="eventDetail.php" class="panel">
                            <div class="panel-body">
                                <img src="images/bread1.jpg" class="img -index" alt="">
                                <p class="panel-pref kinki">京都府</p>
                                <p class="panel-title">
                                    <span class="panel-date">2020年4月1日</span><br>
                                    親子パン教室
                                </p>
                            </div>
                        </a>
                    </div>
                </section>
                <section class="sec-mypage -comment">
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
                </section>
            </main>
        </div>
        <?php
        require('footer.php');
        ?>