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
define('MSG01', '入力してください');
define('MSG02', 'Emailの形式で入力してください');
define('MSG03', 'パスワード（再入力）が合っていません');
define('MSG04', '半角英数字のみご利用いただけます');
define('MSG05', '6文字以上で入力してください');
define('MSG06', '255文字以内で入力してください');
define('MSG07', 'エラーが発生しました。しばらく経ってからやり直してください');
define('MSG08', 'そのEmailはすでに登録されています');
define('MSG09', 'メールアドレスまたはパスワードが間違っています');
define('MSG10', 'エラーが発生しました');
define('MSG11', '現在のパスワードが間違っています');
define('MSG12', '現在のパスワードと同じです');
define('MSG13', '文字で入力してください');
define('MSG14', '認証キーが誤っています');
define('MSG15', '有効期限が切れています。認証キーを再取得してください');
define('MSG16', '選択してください');
define('MSG17', '日付が正しくありません');
define('MSG18', '不正な値が入力されました');
define('MSG19', '半角数字で入力してください');
define('MSG20', '終了日時が過去のものは登録できません');

// サクセスメッセージ
define('SUC01', 'パスワードを変更しました');
define('SUC02', 'プロフィールを編集しました');
define('SUC03', 'メールを送信しました');
define('SUC04', '登録しました');

// ================================
// グローバル変数
// ================================
// エラーメッセージ格納用配列
$err_msg = array();

// ================================
// バリデーション関数
// ================================
// 入力チェック
function validRequired($str, $key, $comment = "")
{
    if (empty($str) ||  $str === false) {
        global $err_msg;
        $err_msg[$key] = $comment . MSG01;
    }
}

// 未選択チェック
function validSelectRequired($str, $key, $comment = '')
{
    if ($str == 0 || $str = "" || empty($str)) {
        global $err_msg;
        $err_msg[$key] = $comment . MSG16;
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

// 新しいパスワードチェック
function validNewPass($str1, $str2, $key)
{
    if ($str1 === $str2) {
        global $err_msg;
        $err_msg[$key] = MSG12;
    }
}

// 半角数字チェック
function validHalfNumber($str, $key)
{
    if (!preg_match("/^[0-9]+$/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG19;
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

//半角数字とハイフンのみ 
function validHalfNumberHyphen($str, $key)
{
    if (!preg_match("/^[0-9-]+$/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG10;
    }
}

// 漢字が含まれている
function validKanji($str, $key)
{
    if (!preg_match("/^[ぁ-んァ-ヶー一-龠]+$/u", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG10;
    }
}

// 日付チェック
function validDate($str, $key)
{
    if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $str)) {
        global $err_msg;
        $err_msg[$key] =  MSG17;
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

// パスワードチェック
function validPass($str, $key)
{
    // 半角英数字チェック
    validHalfAlphanumeric($str, $key);
    // 最小文字数チェック
    validMinLen($str, $key);
    // 最大文字数チェック
    validMaxLen($str, $key);
}

// 固定長チェック
function validLength($str, $key, $length)
{
    if (mb_strlen($str) !== $length) {
        global $err_msg;
        $err_msg[$key] = $length . MSG13;
    }
}

// セレクトボックスチェック(1以上の半角数字)
function validSelect($str, $key)
{
    if ($str === 0 || !preg_match("/^[0-9]+$/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG18;
    }
}

// 都道府県チェック
function validPref($str, $key)
{
    if (!preg_match("/^([0-9]{1,2})$/", $str) || !preg_match("/^([1-9]|[1-3][0-9]|4[0-7])$/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG18;
    }
}

// 日付時刻チェック
function validDateTime($date, $format = 'Y-m-d H:i:s')
{
    // 入力値は日付けとして扱える値なのかのチェック
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

// 終了日時チェック
function validEndDate($date, $key)
{
    $today = date('Y-m-d');
    if (strtotime($today) > strtotime($date)) {
        global $err_msg;
        $err_msg[$key] = MSG20;
    }
}

// 日付順序チェック
function validDateOrder($start, $end, $key)
{
    if (strtotime($start) >= strtotime($end)) {
        global $err_msg;
        $err_msg[$key] = MSG17;
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
        debug('!!!!! クエリに失敗 !!!!!');
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

// イベント情報の取得
function getEventData($u_id, $e_id)
{
    debug('***** イベント情報を取得 *****');
    debug('ユーザーID→→→' . $u_id);
    debug('イベントID→→→' . $e_id);
    // 例外処理
    try {
        // DB接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT * FROM festival WHERE id = :e_id AND u_id = :u_id AND is_deleted = 0';
        $data = array(':e_id' => $e_id, ':u_id' => $u_id);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt) {
            // クエリ結果のデータを１レコード返却
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('!!!!! エラー発生 !!!!!' . $e->getMessage());
    }
}

// イベント情報
function getEventList($currentMinNum = 1, $cat, $area, $pref, $start, $end, $format, $span = 20)
{
    debug('***** イベント情報を取得 *****');
    // 例外処理
    try {
        // DBヘ接続
        $dbh = dbConnect();
        // SQL作成
        $sql = 'SELECT id FROM festival';
        if (!empty($cat)) $sql .= ' WHERE c_id = ' . $cat;

        if ((int) $area !== 0 && empty($cat)) {
            $sql .= ' WHERE area = ' . $area;
        } elseif ((int) $area !== 0 && !empty($cat)) {
            $sql .= ' AND area = ' . $area;
        }

        if ((int) $pref !== 0 && empty($cat) && (int) $area === 0) {
            $sql .= ' WHERE pref = ' . $pref;
        } elseif ((int) $pref !== 0 && (!empty($cat) || (int) $area !== 0)) {
            $sql .= ' AND pref = ' . $pref;
        }

        if (!empty($start) && empty($end) && empty($cat) && (int) $area === 0 && (int) $pref === 0) {
            $sql .= ' WHERE date_start >= ' . $start;
        } elseif (!empty($start) && empty($end) && (!empty($cat) || (int) $area !== 0 || (int) $pref !== 0)) {
            $sql .= ' AND date_start >= ' . $start;
        }

        if (!empty($start) && !empty($end) && empty($cat) && (int) $area === 0 && (int) $pref === 0) {
            $sql .= ' WHERE date_start >= ' . $start . ' AND date_end <= ' . $end;
        } elseif (!empty($start) && !empty($end) && (!empty($cat) || (int) $area !== 0 || (int) $pref !== 0)) {
            $sql .= ' AND date_start >= ' . $start . ' AND date_end <= ' . $end;
        }

        if (empty($start) && !empty($end) && empty($cat) && (int) $area === 0 && (int) $pref === 0) {
            $sql .= ' WHERE date_end <= ' . $end;
        } elseif (empty($start) && !empty($end) && (!empty($cat) || (int) $area !== 0 || (int) $pref !== 0)) {
            $sql .= ' AND date_end <= ' . $end;
        }

        $format_array = explode(",", $format);
        if (!empty($format_array[0]) && empty($format_array[1]) && empty($cat) && (int) $area === 0 && (int) $pref === 0 && empty($start) && empty($end)) {
            $sql  .= ' WHERE format = ' . (int) $format_array[0];
        } elseif (!empty($format_array[0]) && empty($format_array[1]) && (!empty($cat) || (int) $area !== 0 || (int) $pref !== 0 || !empty($start) || !empty($end))) {
            $sql  .= ' AND format = ' . (int) $format_array[0];
        }
        $data = array();
        debug('$sqlの中身→→→' . $sql);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        // 総レコード数をカウント
        $rst['total'] = $stmt->rowCount();
        // 総ページ数を取得
        $rst['total_page'] = ceil($rst['total'] / $span);
        debug('$rstの中身→→→' . print_r($rst, true));
        if (!$stmt) {
            return false;
        }

        // ページング用のSQL文作成
        $sql = 'SELECT * FROM festival';
        if (!empty($cat)) $sql .= ' WHERE c_id = ' . $cat;

        if ((int) $area !== 0 && empty($cat)) {
            $sql .= ' WHERE area = ' . $area;
        } elseif ((int) $area !== 0 && !empty($cat)) {
            $sql .= ' AND area = ' . $area;
        }

        if ((int) $pref !== 0 && empty($cat) && (int) $area === 0) {
            $sql .= ' WHERE pref = ' . $pref;
        } elseif ((int) $pref !== 0 && (!empty($cat) || (int) $area !== 0)) {
            $sql .= ' AND pref = ' . $pref;
        }

        if (!empty($start) && empty($end) && empty($cat) && (int) $area === 0 && (int) $pref === 0) {
            $sql .= ' WHERE date_start >= ' . $start;
        } elseif (!empty($start) && empty($end) && (!empty($cat) || (int) $area !== 0 || (int) $pref !== 0)) {
            $sql .= ' AND date_start >= ' . $start;
        }

        if (!empty($start) && !empty($end) && empty($cat) && (int) $area === 0 && (int) $pref === 0) {
            $sql .= ' WHERE date_start >= ' . $start . ' AND date_end <= ' . $end;
        } elseif (!empty($start) && !empty($end) && (!empty($cat) || (int) $area !== 0 || (int) $pref !== 0)) {
            $sql .= ' AND date_start >= ' . $start . ' AND date_end <= ' . $end;
        }

        if (empty($start) && !empty($end) && empty($cat) && (int) $area === 0 && (int) $pref === 0) {
            $sql .= ' WHERE date_end <= ' . $end;
        } elseif (empty($start) && !empty($end) && (!empty($cat) || (int) $area !== 0 || (int) $pref !== 0)) {
            $sql .= ' AND date_end <= ' . $end;
        }

        $format_array = explode(",", $format);
        if (!empty($format_array[0]) && empty($format_array[1]) && empty($cat) && (int) $area === 0 && (int) $pref === 0 && empty($start) && empty($end)) {
            $sql  .= ' WHERE format = ' . (int) $format_array[0];
        } elseif (!empty($format_array[0]) && empty($format_array[1]) && (!empty($cat) || (int) $area !== 0 || (int) $pref !== 0 || !empty($start) || !empty($end))) {
            $sql  .= ' AND format = ' . (int) $format_array[0];
        }
        $sql .= ' LIMIT ' . $span . ' OFFSET ' . $currentMinNum;
        $data = array();
        debug('ページングSQLの中身→→→' . $sql);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt) {
            // クエリ結果のデータ全レコードを格納
            $rst['data'] = $stmt->fetchAll();
            return $rst;
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('!!!!! エラー発生 !!!!!' . $e->getMessage());
    }
}

// カテゴリーデータの取得
function getCategoryData()
{
    debug('***** カテゴリーデータを取得 *****');
    // 例外処理
    try {
        // DB接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT * FROM category';
        $data = array();
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt) {
            // クエリ結果の全データを返却
            return $stmt->fetchAll();
            return false;
        }
    } catch (Exception $e) {
        error_log('!!!!! エラー発生 !!!!!' . $e->getMessage());
    }
}

// 参加対象の取得
function getTargetData()
{
    debug('***** 参加対象のデータを取得 *****');
    // 例外処理
    try {
        // DB接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT * FROM `target`';
        $data = array();
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt) {
            // クエリ結果の全データを返却
            return $stmt->fetchAll();
            return false;
        }
    } catch (Exception $e) {
        error_log('!!!!! エラー発生 !!!!!' . $e->getMessage());
    }
}

//================================
// メール送信
//================================
function sendMail($from, $to, $subject, $comment)
{
    if (!empty($to) && !empty($subject) && !empty($comment)) {
        // 文字化けしないように設定する（決まったパターン）
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        // メールを送信する
        $result = mb_send_mail($to, $subject, $comment, "From:" . $from);
        // 送信結果を判定
        if ($result) {
            debug('***** メールを送信しました *****');
        } else {
            debug('!!!!! メールを送信できませんでした !!!!!');
        }
    }
}

//================================
// その他
//================================
// サニタイズ
function sanitize($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

// サニタイズ(改行あり)
function sanitize_br($str)
{
    return nl2br(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}

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

// <input value="">の値呼び出し
function inputValueCall($str)
{
    if (!empty($_POST[$str])) echo sanitize($_POST[$str]);
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

    global $dbInfo;
    global $err_msg;
    // ユーザー情報があるか
    if (!empty($dbInfo)) {
        // フォームにエラーがあるか
        if (!empty($err_msg[$str])) {
            // $_POSTにデータがあるか
            if (isset($method[$str])) {
                // ユーザー情報「有」、フォームエラー「有」、POSTにデータ「有」
                return sanitize($method[$str]);
            } else {
                // ユーザー情報「有」、フォームエラー「有」、POSTにデータ「無」
                return sanitize($dbInfo[$str]);
            }
        } else {
            // $_POSTにデータがある、かつDBの情報と違う場合
            if (isset($method[$str]) && $method[$str] !== $dbInfo[$str]) {
                return sanitize($method[$str]);
            } else {
                // $_POSTのデータとDBの情報が同じ
                return sanitize($dbInfo[$str]);
            }
        }
    } else {
        // POST送信の有無
        if (!empty($method[$str])) {
            // ユーザー情報「無」、POSTデータ「有」
            return sanitize($method[$str]);
        }
    }
}


// optionタグselected呼び出し
function optionSelectedCall($str1, $str2)
{
    if (getFormData($str1) == $str2) echo 'selected';
}

// inputタグchecked呼び出し
function inputCheckedCall($str1, $str2)
{
    if (getFormData($str1) == $str2) echo 'checked';
}

// 1回きりのセッションを呼び出す
function getSessionOnce($key)
{
    if (!empty($_SESSION[$key])) {
        $data = $_SESSION[$key];
        // セッションの中身を空っぽにする
        $_SESSION[$key] = '';
        // 最初に代入した変数を返す
        return $data;
    }
}

// 認証キー作成
function makeRandKey($length = 8)
{
    static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0; $i < $length; ++$i) {
        $str .= $chars[mt_rand(0, 61)];
    }
    return $str;
}

// 画像処理
function uploadImg($file, $key)
{
    debug('***** 画像アップロード処理開始 *****');
    debug('FILE情報→→→' . print_r($file, true));

    if (isset($file['error']) && is_int($file['error'])) {
        // 例外処理
        try {
            // バリデーション
            switch ($file['error']) {
                    // OK
                case UPLOAD_ERR_OK:
                    break;
                    // ファイル未選択
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('ファイルが選択されていません');
                    // php.ini定義の最大サイズ超過
                case UPLOAD_ERR_INI_SIZE:
                    throw new RuntimeException('ファイルサイズが大きすぎます');
                    // フォーム定義の最大サイズ超過
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('ファルサイズが大きすぎます');
                    // その他
                default:
                    throw new RuntimeException('エラーが発生しました');
            }

            // $file['mine]の値は偽装可能のため、MINEタイプを自前でチェック
            // exif_imagetype関数は「IMAGETYPE_GIF」「IMAGETYPE_JPEG」などの定数を返す
            // 第三引数にはtrueを設定する（厳密にチェックしてくれる）
            $type = @exif_imagetype($file['tmp_name']);
            if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
                throw new RuntimeException('画像形式が未対応です');
            }

            // ファイルデータからSHA-1ハッシュを取り、ファイル名を決定し、ファイルを保存する
            // ハッシュ化しないと、同じファイル名がアップされる可能性があり、DBで判別できないと困るため
            // image_type_to_extension関数は、ファイルの拡張子を取得するもの
            $path = 'uploads/' . sha1_file($file['tmp_name']) . image_type_to_extension($type);

            if (!move_uploaded_file($file['tmp_name'], $path)) {
                // ファイルを移動する
                throw new RuntimeException('ファイル保存時にエラーが発生しました');
            }
            // 保存したファイルパスの権限を変更する
            // 所有者に読み込み、書き込みの権限を与え、その他には読み込みだけ許可する。(0644)
            chmod($path, 0644);

            debug('***** ファイルは正常にアップロードされました *****');
            debug('ファイルパス→→→' . $path);
            return $path;
        } catch (RuntimeException $e) {
            debug('!!!!! エラー発生 !!!!!' . $e->getMessage());
            global $err_msg;
            $err_msg[$key] = $e->getMessage();
        }
    }
}

// エリア判定
function areaDecided($str)
{
    if ($str === 1) {
        // 北海道
        return 1;
    } elseif ($str >= 2 && $str <= 7) {
        // 東北
        return 2;
    } elseif ($str >= 8 && $str <= 14) {
        // 関東
        return 3;
    } elseif ($str >= 15 && $str <= 23) {
        // 中部
        return 4;
    } elseif ($str >= 24 && $str <= 30) {
        // 近畿
        return 5;
    } elseif ($str >= 31 && $str <= 35) {
        // 中国
        return 6;
    } elseif ($str >= 36 && $str <= 39) {
        // 四国
        return 7;
    } elseif ($str >= 40 && $str <= 47) {
        // 九州・沖縄
        return 8;
    }
}

// エリア名呼び出し(クラス)
function areaClassCalled($str)
{
    $area_array = array(
        'hokkaido',
        'tohoku',
        'kanto',
        'chubu',
        'kinki',
        'chugoku',
        'shikoku',
        'kyusyu'
    );

    return $area_array[$str - 1];
}

// エリア名呼び出し(漢字)
function areaNameCalled($str)
{
    $area_array = array(
        '北海道',
        '東北',
        '関東',
        '中部',
        '近畿',
        '中国',
        '四国',
        '九州'
    );

    return $area_array[$str - 1];
}

// 都道府県名呼び出し
function prefNameCalled($str)
{
    // 都道府県配列
    $pref_array = array(
        '北海道',
        '青森県',
        '岩手県',
        '宮城県',
        '秋田県',
        '山形県',
        '福島県',
        '茨城県',
        '栃木県',
        '群馬県',
        '埼玉県',
        '千葉県',
        '東京都',
        '神奈川県',
        '新潟県',
        '富山県',
        '石川県',
        '福井県',
        '山梨県',
        '長野県',
        '岐阜県',
        '静岡県',
        '愛知県',
        '三重県',
        '滋賀県',
        '京都府',
        '大阪府',
        '兵庫県',
        '奈良県',
        '和歌山県',
        '鳥取県',
        '島根県',
        '岡山県',
        '広島県',
        '山口県',
        '徳島県',
        '香川県',
        '愛媛県',
        '高知県',
        '福岡県',
        '佐賀県',
        '長崎県',
        '熊本県',
        '大分県',
        '宮崎県',
        '鹿児島県',
        '沖縄県'
    );
    return $pref_array[$str - 1];
}

// ページング
function pagination($currentPageNum, $totalPageNum, $link = '', $pageColNum = 5)
{
    $pageNumHalf = floor($pageColNum / 2);
    // 現在のページが総ページ数と同じ、かつ、総ページ数が表示項目数以上なら、左にリンク４個出す
    if ($currentPageNum == $totalPageNum && $totalPageNum >= $pageColNum) {
        $minPageNum = $currentPageNum - 4;
        $maxPageNum = $currentPageNum;
        // 現在のページ数が、総ページ数の1ページ前なら、左にリンク3個、右に1個出す
    } elseif ($currentPageNum == ($totalPageNum - 1) && $totalPageNum >= $pageColNum) {
        $minPageNum = $currentPageNum - 3;
        $maxPageNum = $currentPageNum + 1;
        // 現在のページが２の場合は、左にリンク1個、右にリンク3個出す
    } elseif ($currentPageNum == 2 && $totalPageNum >= $pageColNum) {
        $minPageNum = $currentPageNum - 1;
        $maxPageNum = $currentPageNum + 3;
        // 現在のページが１の場合は、右にリンク4個出す
    } elseif ($currentPageNum == 1 && $totalPageNum >= $pageColNum) {
        $minPageNum = $currentPageNum;
        $maxPageNum = 5;
        // 総ページ数が、表示項目数より少ない場合は、総ページ数をループのMax、ループのMinを１に設定
    } elseif ($totalPageNum < $pageColNum) {
        $minPageNum = 1;
        $maxPageNum = $totalPageNum;
        // それ以外は左に2個、右に2個出す
    } else {
        $minPageNum = $currentPageNum - 2;
        $maxPageNum = $currentPageNum + 2;
    }

    echo '<div class="pagination">';
    echo '<ul class="pagination-list">';
    if ($currentPageNum != 1 && $totalPageNum > $pageColNum && $currentPageNum > $pageNumHalf + 1) {
        echo '<li class="list-item"><a href="?p=1' . $link . '" class="a-pagination">&lt;&lt;</a></li>';
    }
    for ($i = $minPageNum; $i <= $maxPageNum; $i++) {
        echo '<li class="list-item ';
        if ($currentPageNum == $i) {
            echo 'active';
        }
        echo '"><a href="?p=' . $i . $link . '" class="a-pagination">' . $i . '</a></li>';
    }
    if ($currentPageNum != $maxPageNum && $totalPageNum > $pageColNum && $currentPageNum < $totalPageNum - $pageNumHalf) {
        echo '<li class="list-item"><a href="?p=' . $totalPageNum . $link . '" class="a-pagination">&gt;&gt;</a></li>';
    }
    echo '</ul>';
    echo '</div>';
}

// GETパラメータ付与
function appendGetParam($arr_del_key = array(), $flg = false)
{
    if ($flg) {
        $str = '&';
    } else {
        $str = '?';
    }
    if (!empty($_GET)) {

        if (!empty($_GET['format']) && empty($_GET['format'][1])) {
            $_GET['format'] = $_GET['format'][0];
        } elseif (!empty($_GET['format'][1])) {
            array_push($arr_del_key, 'format');
        }

        foreach ($_GET as $key => $val) {
            if (!in_array($key, $arr_del_key, true)) {
                $str .= $key . '=' . $val . '&';
            }
        }
        $str = mb_substr($str, 0, -1, "UTF-8");
        return $str;
    }
}

// GETにキーが入っていたら、取得する
function takeGetValue($key, $val = '')
{
    $str = (!empty($_GET[$key])) ? $_GET[$key] : $val;
    return $str;
}

// イベント詳細用のイベント情報を取得
function getEventOne($e_id)
{
    debug('***** イベント情報を取得 *****');
    debug('イベントID→→→' . $e_id);
    // 例外処理
    try {
        // DB接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT f.name, f.format, f.date_start, f.date_end, f.time_start, f.time_end, f.area, f.pref, f.place, f.addr, f.target_age, f.target_other, f.fee, f.pay, f.capacity, f.people, f.comment, f.contact, f.pic1, f.pic2, f.pic3, f.c_id, c.name AS category FROM festival AS f LEFT JOIN category as c ON f.c_id = c.id WHERE f.id = :e_id AND f.is_deleted = 0';
        $data = array(':e_id' => $e_id);
        // クエリ作成
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt) {
            // 成功の場合、1レコード返却
            return  $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('!!!!! エラーが発生しました !!!!!' . $e->getMessage());
    }
}

// 曜日呼び出し
function callDayOfWeek($date)
{
    $datetime = new DateTime("$date");
    $week = array(
        '日', //0
        '月', //1
        '火', //2
        '水', //3
        '木', //4
        '金', //5
        '土', //6
    );
    $w = (int) date_format($datetime, 'w');

    return '(' . $week[$w] . ')';
}

// 時間フォーマット
function callTime($time)
{
    return date('H:i', strtotime($time));
}
