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
            <aside class="side -mypage">
                <nav class="nav-mypage">
                    <div class="side-group-mypage group-user">
                        <a href="mypage.php" class="a-group-user">
                            <div class="group-user-profile">
                                <div class="group-user-profile-media">
                                    <img src="images/hedgehog.jpg" class="img -mypage-profile-img">
                                </div>
                                <div class="group-user-profile-body">
                                    <p class="p-group-user-profile -username">名前</p>
                                    <p class="p-group-user-profile -label">マイページTOP</p>
                                </div>
                            </div>
                        </a>
                        <div class="group-user-button">
                            <button class="button -group-user" name="button" type="submit">イベントを作成する</button>
                        </div>
                        <div class="side-group-line"></div>
                    </div>
                    <div class="side-group-mypage group-fes">
                        <ul class="ul ul-mypage-side">
                            <li class="li li-mypage-side">
                                <a href="mypageCreated.php" class="a-side-group-mypage">作成したイベント</a></li>
                            <li class="li li-mypage-side">
                                <a href="mypageFavo.php" class="a-side-group-mypage">お気に入り</a></li>
                            <li class="li li-mypage-side">
                                <a href="mypageComment.php" class="a-side-group-mypage">コメントしたイベント</a></li>
                        </ul>
                        <div class="side-group-line"></div>
                    </div>
                    <div class="side-group-mypage group-edit">
                        <ul class="ul ul-mypage-side">
                            <li class="li li-mypage-side"><a href="profEdit.php" class="a-side-group-mypage">プロフィール編集</a></li>
                            <li class="li li-mypage-side"><a href="passEdit.php" class="a-side-group-mypage">パスワード変更</a></li>
                            <li class="li li-mypage-side"><a href="withdraw.php" class="a-side-group-mypage">退会</a></li>
                        </ul>
                    </div>
                </nav>
            </aside>
            <!-- メイン -->
            <main class="main">
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