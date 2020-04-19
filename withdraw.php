<?php
// 共通変数・関数を読み込む
require('function.php');
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