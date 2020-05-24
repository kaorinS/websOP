<header class="header">
    <div class="site-width header-wrap">
        <h1 class="logo"><a href="index.php"><i class="fab fa-fly"></i>イベ探！</a></h1>
        <nav class="header-nav">
            <ul class="header-ul">
                <?php if (empty($_SESSION['user_id'])) : ?>
                    <li class="header-li"><a href="login.php">新規登録 / ログイン</a></li>
                <?php else : ?>
                    <li class="header-li -login"><a href="mypage.php">マイページ</a></li>
                    <li class="header-li -login"><a href="logout.php">ログアウト</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>