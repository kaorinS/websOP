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
                            <li class="li li-mypage-side"><a href="withdraw.php" class="a-side-group-mypage -active">退会</a></li>
                        </ul>
                    </div>
                </nav>
            </aside>
            <!-- メイン -->
            <main class="main">
                <section class="sec-mypage -prof_edit">
                    <h2 class="mypage-edit-title">
                        退会
                    </h2>
                    <div class="mypage-edit-body">
                        <form method="post" class="form-mypage-edit -withdraw">
                            <h3 class="title-withdraw">本当に退会しますか？</h3>
                            <div class="submit-container-mypage-edit -withdraw">
                                <input type="submit" class="submit submit-mypage-edit -withdraw" value="退会する">
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
        <?php
        require('footer.php');
        ?>