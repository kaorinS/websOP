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
            <a href="registEvent.php" class="mypage-regist_event">
                <div class="group-user-regist_event">
                    イベントを作成する
                </div>
            </a>
            <div class="side-group-line"></div>
        </div>
        <div class="side-group-mypage group-fes">
            <ul class="ul ul-mypage-side">
                <li class="li li-mypage-side">
                    <a href="mypageCreated.php" class="a-side-group-mypage <?php addIsActive($pageName, 'created'); ?>">作成したイベント</a></li>
                <li class="li li-mypage-side">
                    <a href="mypageFavo.php" class="a-side-group-mypage <?php addIsActive($pageName, 'favo'); ?>">お気に入り</a></li>
                <li class="li li-mypage-side">
                    <a href="mypageComment.php" class="a-side-group-mypage <?php addIsActive($pageName, 'comment'); ?>">コメントしたイベント</a></li>
            </ul>
            <div class="side-group-line"></div>
        </div>
        <div class="side-group-mypage group-edit">
            <ul class="ul ul-mypage-side">
                <li class="li li-mypage-side"><a href="profEdit.php" class="a-side-group-mypage <?php addIsActive($pageName, 'profEdit'); ?>">プロフィール編集</a></li>
                <li class="li li-mypage-side"><a href="passEdit.php" class="a-side-group-mypage <?php addIsActive($pageName, 'passEdit'); ?>">パスワード変更</a></li>
                <li class="li li-mypage-side"><a href="withdraw.php" class="a-side-group-mypage <?php addIsActive($pageName, 'withdraw'); ?>">退会</a></li>
            </ul>
        </div>
    </nav>
</aside>