<?php
$title = "パスワード再発行 | イベ探";
require('head.php');
?>

<body class="page-login page-1colum">
    <div class="wrapper">
        <?php
        require('header.php');
        ?>
        <div class="main-container site-width">
            <main class="main login-main">
                <div class="main-form-container">
                    <h2 class="title-pass_reissue">パスワード再発行</h2>
                    <div class="login-panel-group">
                        <div class="login-panel panel-login is-show">
                            <form method="post" class="login-form">
                                <p class="p-login-form">ご登録のメールアドレス宛にパスワード再発行用の認証キーをお送りします。</p>
                                <label class="label login-label">
                                    登録したメールアドレス<br>
                                    <input type="text" class="input-text -login" name="mail">
                                </label>
                                <div class="login-submit-container -pass_reissue">
                                    <input type="submit" class="submit login-submit -login" value="送信する">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="return-top">
                    <a href="index.php">TOPページに戻る</a>
                </div>
            </main>
        </div>
        <?php
        require('footer.php');
        ?>