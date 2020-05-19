<?php
// ================================
// ini_set（ログ・タイムゾーン)
// ================================
// ログを取るか
ini_set('log_errors', 'on');
// ログの出力ファイルを指定
ini_set('error_log', 'php.log');
// 全てのエラーを表示する
ini_set('error_reporting', E_ALL);
// タイムゾーン設定
ini_set('date.timezone', 'Asia/Tokyo');

// ================================
// デバッグ
// ================================
// デバッグフラグ（開発中のみtrue)
$debug_flg = true;
// デバッグログ関数
function debug($str)
{
    global $debug_flg;
    if (!empty($debug_flg)) {
        error_log('*** デバッグ ***：' . $str);
    }
}

// ================================
// セッション準備・セッション有効期限延長
// ================================
// セッションファイルの置き場を変更(/var/tmp/以下に置くと30にちは削除されない)
session_save_path("/var/tmp/");
// ガーベージコレクションが削除するセッションの有効期限設定(30日以上経過で1/100の確率で削除)(デフォは1440s=60*24m)(今回は30日に設定)
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
// クッキーの有効期限延長（ブラウザを閉じても削除されないよう）(デフォは0)(今回は30日に設定)
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
// セッションを使う
session_start();
// 現在のセッションIDを新しく生成したものと置き換える(なりすましセキュリティ対策)
session_regenerate_id();

// ================================
// 画面表示処理開始ログ吐き出し関数
// ================================
function debugLogStart()
{
    debug('>>>>>>>>>>>>>>> 画面表示処理開始 >>>>>>>>>>>>>>>');
    debug('セッションID →→→' . session_id());
    debug('セッション変数の中身 →→→' . print_r($_SESSION, true));
    debug('現在日時タイムスタンプ →→→' . time());
    if (!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])) {
        debug('ログイン期限日時タイムスタンプ →→→' . ($_SESSION['login_date'] + $_SESSION['login_limit']));
    }
}

// ================================
// 定数
// ================================
// エラーメッセージ
define('MSG01', '必須項目です');
define('MSG02', 'Emailの形式で入力してください');
define('MSG03', 'パスワード（再入力）が合っていません');
define('MSG04', '半角英数字のみご利用いただけます');
define('MSG05', '6文字以上で入力してください');
define('MSG06', '256文字以内で入力してください');
define('MSG07', 'エラーが発生しました。しばらく経ってからやり直してください');
define('MSG08', 'そのEmailはすでに登録されています');
//================================
// 全体
//================================
// is-active呼び出し
function addIsActive($str, $page_name)
{
    if (!empty($str)) {
        if ($str === $page_name) echo ' is-active';
    }
}
