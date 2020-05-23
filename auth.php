<?php

// ================================
// ログイン認証・自動ログアウト
// ================================
// ログインしてる場合
if (!empty($_SESSION['login_date'])) {
    debug('*** ログイン認証 *** 「ログイン済みユーザー」');

    // 現在日時が有効期限の時間を超えていた場合
    if (($_SESSION['login_date'] + $_SESSION['login_limit']) < time()) {
        debug('!!!!! ログイン有効期限オーバー !!!!!');

        // セッションを削除する
        session_destroy();
        // ログインページへ遷移
        header("Location:login.php");
        exit();
    } else {
        debug('***** ログイン有効期限内 *****');

        // 最終ログイン日時を現在日時に更新
        $_SESSION['login_date'] = time();

        // 現在login.phpだった場合
        if (basename($_SERVER['PHP_SELF']) === 'login.php') {
            debug('***** マイページへ遷移 *****');
            // マイページへ遷移
            header("Location:mypage.php");
            exit();
        }
    }
} else {
    // ログインしてない場合
    debug('*** ログイン認証 *** 「未ログインユーザー」');

    // 現在login.php以外だった場合
    if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
        debug('***** ログインページへ遷移 *****');
        // ログインページへ
        header("Location:login.php");
        exit();
    }
}
