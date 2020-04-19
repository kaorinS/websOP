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
            $pageName = 'favo';
            require('sidebar_mypage.php');
            ?>
            <!-- メイン -->
            <main class="main">
                <section class="sec-mypage -favo">
                    <div class="mypage-title-wrap">
                        <h2 class="mypage-title -favo">
                            お気に入り
                        </h2>
                        <div class="mypage-number-display">
                            全4件中 4件
                        </div>
                    </div>
                    <div class="selectbox -mypage">
                        <select name="sort" id="" class="select -mypage">
                            <option value="0">並び替え</option>
                            <option value="1">登録順（昇順）</option>
                            <option value="2">登録順（降順）</option>
                            <option value="3">開催日（昇順）</option>
                            <option value="4">開催日（降順）</option>
                        </select>
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