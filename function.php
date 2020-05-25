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
    debug('$_SESSIONの中身 →→→' . print_r($_SESSION, true));
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
define('MSG06', '255文字以内で入力してください');
define('MSG07', 'エラーが発生しました。しばらく経ってからやり直してください');
define('MSG08', 'そのEmailはすでに登録されています');
define('MSG09', 'メールアドレスまたはパスワードが間違っています');

// ================================
// グローバル変数
// ================================
// エラーメッセージ格納用配列
$err_msg = array();

// ================================
// バリデーション関数
// ================================
// 未入力チェック
function validRequired($str, $key)
{
    if (empty($str)) {
        global $err_msg;
        $err_msg[$key] = MSG01;
    }
}

// Email形式チェック
function validEmail($str, $key)
{
    if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}

// Email重複チェック
function validEmailDup($email)
{
    global $err_msg;
    // 例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT count(*) FROM users WHERE email = :email AND is_deleted = 0';
        $data = array(':email' => $email);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        // クエリの結果の値を取得
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // array_shift関数で１つ目だけ取り出す
        if (!empty(array_shift($result))) {
            $err_msg['email_regist'] = MSG08;
        }
    } catch (Exception $e) {
        error_log('***** エラー発生 *****' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}

// パスワード再入力チェック
function validPassRe($str1, $str2, $key)
{
    if ($str1 !== $str2) {
        global $err_msg;
        $err_msg[$key] = MSG03;
    }
}

// 半角英数字チェック
function validHalfAlphanumeric($str, $key)
{
    if (!preg_match("/^[a-zA-Z0-9]+$/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG04;
    }
}

// 最小文字数チェック(６文字未満)
function validMinLen($str, $key, $min = 6)
{
    if (mb_strlen($str) < $min) {
        global $err_msg;
        $err_msg[$key] = MSG05;
    }
}

// 最大文字数チェック(256文字以上)
function validMaxLen($str, $key, $max = 255)
{
    if (mb_strlen($str) > $max) {
        global $err_msg;
        $err_msg[$key] = MSG06;
    }
}

// ================================
// DB関係
// ================================
// DB接続関数
function dbConnect()
{
    // DBへの接続準備
    $dsn = 'mysql:dbname=webs_op;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    $options = array(
        // SQL実行失敗時にはエラーコードのみ設定(SILENT)(開発中はWARNING)
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        // デフォルトフェッチモードを連想配列型式に設定
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // バッファードクエリを使う（一度に結果セットを全て取得し、サーバー負荷を軽減）
        // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
    // PDOオブジェクト生成(DBへ接続)
    $dbh = new PDO($dsn, $user, $password, $options);
    return $dbh;
}

// SQL実行関数
function queryPost($dbh, $sql, $data)
{
    // クエリー作成
    $stmt = $dbh->prepare($sql);
    // プレースホルダに値をセット、SQL文を実行
    if (!$stmt->execute($data)) {
        debug('クエリに失敗');
        debug('失敗したSQL→→→' . print_r($stmt, true));
        global $err_msg;
        $err_msg['common'] = MSG07;
        return 0;
    }
    debug('***** queryPost クエリ成功 *****');
    return $stmt;
}

// ユーザー情報の取得
function getUser($u_id)
{
    try {
        // DB接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT * FROM users WHERE id = :id';
        $data = array(':id' => $u_id);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        // クエリ成功判定
        if ($stmt) {
            debug('***** getUser クエリ成功 *****');
        } else {
            debug('!!!!! getUser クエリ失敗 !!!!!');
        }
    } catch (Exception $e) {
        error_log('!!!!! エラー発生 !!!!!') . $e->getMessage();
    }

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//================================
// その他
//================================
// is-active呼び出し
function addIsActive($str, $page_name)
{
    if (!empty($str)) {
        if ($str === $page_name) echo ' is-active';
    }
}

// エラーメッセージ呼び出し
function errorMsgCall($str)
{
    global $err_msg;
    if (!empty($err_msg[$str])) {
        echo $err_msg[$str];
    }
}

// class="err" 呼び出し
function classErrorCall($str)
{
    global $err_msg;
    if (!empty($err_msg[$str])) {
        echo 'err-post';
    }
}

// フォーム入力保持
function getFormData($str, $flg = false)
{
    // GET送信かPOST送信か(デフォルトはPOST送信)
    if ($flg) {
        $method = $_GET;
    } else {
        $method = $_POST;
    }

    global $userInfo;
    global $err_msg;
    // ユーザー情報があるか
    if (!empty($userInfo)) {
        // フォームにエラーがあるか
        if (!empty($err_msg[$str])) {
            // $_POSTまたは$_GETにデータがあるか
            if (isset($method[$str])) {
                // ユーザー情報「有」、フォームエラー「有」、POSTにデータ「有」
                return sanitize($method[$str]);
            } else {
                // ユーザー情報「有」、フォームエラー「有」、POSTにデータ「無」
                return sanitize($userInfo[$str]);
            }
        } else {
            // $_POSTにデータがある、かつDBの情報と違う場合
            if (isset($method[$str]) && $method[$str] !== $userInfo[$str]) {
                return sanitize($method[$str]);
            } else {
                // $_POSTのデータとDBの情報が同じ
                return sanitize($userInfo[$str]);
            }
        }
    } else {
        // ユーザー情報がない場合
        return sanitize($method[$str]);
    }
}
