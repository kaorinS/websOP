<?php
// 共通変数・関数を読み込む
require('function.php');
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
                    <h2 class="mypage-edit-title">
                        パスワード変更
                    </h2>
                    <div class="mypage-edit-body">
                        <form method="post" class="form-mypage-edit -pass">
                            <label class="label label-mypage-edit">
                                現在のパスワード<br>
                                <input type="password" class="input-text -mypage-edit" name="pass_old">
                            </label>
                            <label class="label label-mypage-edit">
                                新しいパスワード<br>
                                <input type="password" class="input-text -mypage-edit" name="pass_new">
                            </label>
                            <label class="label label-mypage-edit">
                                新しいパスワード（再入力）<br>
                                <input type="password" class="input-text -mypage-edit" name="pass_new_re">
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