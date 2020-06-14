<?php
// 共通変数・関数を読み込む
require('function.php');

// デバッグ
debug('**********************************************');
debug('********** ログイン & ユーザー登録ページ **********');
debug('**********************************************');
debugLogStart();

// ログイン認証
require('auth.php');

// ================================
// 画面処理
// ================================
// ========= 画面表示用データ取得 =========
// イベントIDがある場合、イベントID(GETデータ）を格納
$e_id = (!empty($_GET['e_id'])) ? $_GET['e_id'] : '';
// DBからイベント情報を取得
$dbInfo = (!empty($e_id)) ? getEventData($_SESSION['user_id'], $_GET['e_id']) : '';
// 新規か編集か判別用フラグ
$edit_flg = (empty($dbInfo)) ? false : true;
if ($edit_flg) {
    debug('***** イベント編集 *****');
} else {
    debug('***** イベント新規作成 *****');
}
// DBからカテゴリーデータを取得
$dbCategoryData = getCategoryData();
// DBから参加対象のデータを取得
$dbTargetData = getTargetData();
debug('イベントID→→→' . $e_id);
debug('イベント情報($dbInfoの中身)→→→' . print_r($dbInfo, true));
debug('カテゴリデータ($dbCategoryDataの中身)→→→' . print_r($dbCategoryData, true));
debug('参加対象のデータ($dbTargetDataの中身)→→→' . print_r($dbTargetData, true));
// 参加対象の最後のキー（その他）を取得
$targetLastKey = array_key_last($dbTargetData);
$targetOtherId = $dbTargetData[$targetLastKey]['id'];
debug('参加対象の最後（その他）のkeyの値→→→' . $targetLastKey);
debug('参加対象の「その他」のID→→→' . $dbTargetData[$targetLastKey]['id']);

// ========== パラメータ改竄チェック ==========
// GETパラメータが改竄されている場合、マイページへ遷移させる
if (!empty($p_id) && empty($dbInfo)) {
    debug('!!!!! GETパラメータの商品IDが違うため、マイページへ遷移 !!!!!');
    header("Location:mypage.php");
    exit;
}

// ========== POST送信時の処理 ==========
// POSTされているか
if (!empty($_POST)) {
    debug('$_POSTの中身→→→' . print_r($_POST, true));
    debug('$_FILESの中身→→→' . print_r($_FILES, true));

    // 変数にユーザー情報を代入
    $eventname = $_POST['eventname'];
    $eventStart = $_POST['event_date-start'];
    $eventEnd = (!empty($_POST['event_date-end'])) ? $_POST['event_date-end'] : $eventStart;
    $timeStart = $_POST['event_time-start'];
    $timeEnd = $_POST['event_time-end'];
    $category = $_POST['category'];
    $pref = $_POST['pref'];
    $area = '';
    $address = $_POST['address'];
    $format = (!empty($_POST['format'])) ? $_POST['format'] : '';
    $target = (isset($_POST['target']) && is_array($_POST['target'])) ? $_POST['target'] : '';
    $targetOther = $_POST['target-other'];
    $entry = (!empty($_POST['entry'])) ? $_POST['entry'] : '';
    $entryFee = $_POST['entry-fee'];
    $detail = $_POST['detail'];
    $organizer = $_POST['organizer'];
    // 画像をアップロードして、パスを格納
    $pic1 = (!empty($_FILES['pic1']['name'])) ? uploadImg($_FILES['pic1'], 'pic1') : '';
    // 画像をPOSTしていないが、すでにDBに登録されてる場合、DBのパスを格納
    $pic1 = (empty($pic1) && !empty($dbInfo['pic1'])) ? $dbInfo['pic1'] : $pic1;
    $pic2 = (!empty($_FILES['pic2']['name'])) ? uploadImg($_FILES['pic2'], 'pic2') : '';
    $pic2 = (empty($pic2) && !empty($dbInfo['pic2'])) ? $dbInfo['pic2'] : $pic2;
    $pic3 = (!empty($_FILES['pic3']['name'])) ? uploadImg($_FILES['pic3'], 'pic3') : '';
    $pic3 = (empty($pic3) && !empty($dbInfo['pic3'])) ? $dbInfo['pic3'] : $pic3;

    // 参加対象の配列を文字列にする変数を作っておく
    if (!empty($target)) $target_separeted = implode(",", $target);
    debug('$target_separetedの中身→→→' . $target_separeted);

    // バリデーション
    if (empty($dbInfo)) {
        // 未入力チェック
        validRequired($eventname, 'eventname');
        validSelectRequired($eventStart, 'event_date', '開催日を');
        validSelectRequired($eventEnd, 'event_date', '開催日を');
        validSelectRequired($timeStart, 'event_time', '時刻を');
        validSelectRequired($timeEnd, 'event_time', '時刻を');
        validSelectRequired($category, 'category');
        validSelectRequired($pref, 'pref');
        validRequired($address, 'address');
        validSelectRequired($format, 'format');
        if (!empty($target[$targetOtherId])) validRequired($targetOther, 'target', 'その他の欄を');
        validSelectRequired($entry, 'entry');
        if ($entry == 2) validRequired($entryFee, 'entry', '金額を');
        validRequired($detail, 'detail');
        validRequired($organizer, 'organizer');

        if (empty($err_msg)) {
            // イベント名最大文字数チェック
            validMaxLen($eventname, 'eventname');
            // 日付形式チェック
            validDate($eventStart, 'event_date');
            validDate($eventEnd, 'event_date');
            // 時刻形式チェック
            $checkedTimeStart = validDateTime($timeStart, 'H:i');
            validRequired($checkedTimeStart, 'event_time', '正しい時刻を');
            $checkedTimeEnd = validDateTime($timeEnd, 'H:i');
            validRequired($checkedTimeEnd, 'event_time', '正しい時刻を');
            // カテゴリーチェック
            validSelect($category, 'category');
            // 都道府県チェック
            validPref($pref, 'pref');
            // 開催場所チェック
            validMaxLen($address, 'address');
            // 会場形式チェック
            validSelect($format, 'format');
            // 参加対象チェック
            if (!empty($target)) {
                // 半角数字チェック
                foreach ($target as $val) {
                    validHalfNumber($val, 'target');
                }
            }
            // 参加対象（その他）最大文字数チェック
            if (!empty($targetOther)) validMaxLen($targetOther, 'target');
            // 参加費形式チェック
            validSelect($entry, 'entry');
            // 参加費（有料)文字数チェック
            if (!empty($entryFee)) validMaxLen($entryFee, 'entry');
            // 詳細最大文字数チェック
            validMaxLen($detail, 'detail', 500);
            // 主催~最大文字数チェック
            validMaxLen($organizer, 'organizer');
        }
    } else {
        // イベント名
        if ($dbInfo['name'] !== $eventname) {
            // 未入力チェック
            validRequired($eventname, 'eventname');
            // 最大文字数チェック
            validMaxLen($eventname, 'eventname');
        }
        // 開催日(始まり)
        if ($dbInfo['date-start'] !== $eventStart) {
            //日付形式チェック
            validDate($eventStart, 'event_date');
        }
        // 開催日（終わり）
        if ($dbInfo['date-end'] !== $eventEnd) {
            //日付形式チェック
            validDate($eventEnd, 'event_date');
        }
        // 開催日時（始まり)
        if ($dbInfo['time-start'] !== $timeStart) {
            // 時刻形式チェック
            $checkedtime = validDateTime($timeStart, 'H:i');
            validRequired($checkedtime, 'event_time', '正しい時刻を');
        }
        // 開催日時(終わり)
        if ($dbInfo['time-end'] !== $timeEnd) {
            // 時刻形式チェック
            $checkedtime = validDateTime($timeEnd, 'H:i');
            validRequired($checkedtime, 'event_time', '正しい時刻を');
        }
        // カテゴリー
        if ($dbInfo['c_id'] !==  $category) {
            // セレクトボックスチェック
            validSelect($category, 'category');
        }
        // 都道府県
        if ($dbInfo['pref'] !== $pref) {
            // 都道府県チェック
            validPref($pref, 'pref');
        }
        // 開催場所
        if ($dbInfo['address'] !== $address) {
            // 未入力チェック
            validRequired($address, 'address');
            // 最大文字数チェック
            validMaxLen($address, 'address');
        }
        // 会場形式
        if ($dbInfo['format'] !== $format) {
            // 形式チェック
            validSelect($format, 'format');
        }
        // 参加対象
        if ($dbInfo['target_age'] !== $target_separeted) {
            // 半角数字チェック
            foreach ($target as $val) {
                validHalfNumber($val, 'target');
            }
        }
        // 参加対象（その他）
        if ($dbInfo['target_other'] !== $targetOther) {
            // 最大文字数チェック
            validMaxLen($targetOther, 'target');
        }
        // 料金
        if ($dbInfo['fee'] !== $entry) {
            validSelect($entry, 'entry');
        }
        // 有料の場合
        if ($dbInfo['pay'] !== $entryFee) {
            // 最大文字数チェック
            validMaxLen($entryFee, 'entry');
        }
        // 詳細
        if ($dbInfo['comment'] !== $detail) {
            // 最大文字数チェック
            validMaxLen($detail, 'detail');
        }
        // 主催・問い合わせ
        if ($dbInfo['contact'] !== $organizer) {
            // 最大文字数チェック
            validMaxLen($organizer, 'organizer');
        }
    }

    if (empty($err_msg)) {
        $area = areaDecided($category);

        // 例外処理
        try {
            // DBヘ接続
            $dbh = dbConnect();
            // SQL文作成(新規：INSERT、更新：UPDATE)
            if ($edit_flg) {
                // 更新
                debug('***** イベント更新 *****');
                $sql = 'UPDATE festival SET name = :name, `format` = :format, c_id = :c_id, date_start = :dateStart, date_end = :dateEnd, time_start = :timeStart, time_end = :timeEnd, area = :area, pref = :pref, place = :address, target_age = :target, target_other = :targetOther, fee = :entry, pay = :pay, comment = :detail, contact = :contact, pic1 = :pic1, pic2 = :pic2, pic3 = :pic3 WHERE id = :id AND u_id = :u_id';
                $data = array(':name' => $eventname, ':format' => $format, ':c_id' => $category, ':dateStart' => $eventStart, ':dateEnd'  => $eventEnd, ':timeStart' => $timeStart, ':timeEnd' => $timeEnd, ':area' => $area, ':pref' => $pref, ':address' => $address, ':target' => $target_separeted, ':targetOther' => $targetOther, ':entry' => $entry, ':pay' => $entryFee, ':detail' => $detail, ':contact' => $organizer, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':id' => $e_id, ':u_id' => $_SESSION['user_id']);
            } else {
                // 新規
                debug('***** イベント新規作成 *****');
                $sql = 'INSERT INTO festival (name, `format`, c_id, date_start, date_end, time_start, time_end, area, pref, place, target_age, target_other, fee, pay, comment, contact, u_id, pic1, pic2, pic3, created_at) VALUES (:name, :format, :c_id, :dateStart, :dateEnd, :timeStart, :timeEnd, :area, :pref, :address, :target, :targetOther, :entry, :pay, :detail, :contact, :u_id, :pic1, :pic2, :pic3, :created)';
                $data = array(':name' => $eventname, ':format' => $format, ':c_id' => $category, ':dateStart' => $eventStart, ':dateEnd'  => $eventEnd, ':timeStart' => $timeStart, ':timeEnd' => $timeEnd, ':area' => $area, ':pref' => $pref, ':address' => $address, ':target' => $target_separeted, ':targetOther' => $targetOther, ':entry' => $entry, ':pay' => $entryFee, ':detail' => $detail, ':contact' => $organizer, ':u_id' => $_SESSION['user_id'], ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':created' => date('Y-m-d H:i:s'));
            }
            debug('SQL→→→' . $sql);
            debug('流し込みデータ→→→' . print_r($data, true));
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);

            // クエリ成功
            if ($stmt) {
                $_SESSION['msg_success'] = SUC04;
                debug('***** マイページへ遷移 *****');
                header("Location:mypage.php");
                exit;
            }
        } catch (Exception $e) {
            error_log('!!!!! エラー発生 !!!!!' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}

?>
<?php
$title = 'イベント作成　|　イベ探';
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
            $pageName = '';
            require('sidebar_mypage.php');
            ?>
            <!-- メイン -->
            <main class="main">
                <section class="sec-mypage -prof_edit">
                    <h2 class="mypage-edit-title">
                        イベント作成
                    </h2>
                    <div class="area-msg">
                        <?php
                        errorMsgCall('common');
                        ?>
                    </div>
                    <div class="mypage-edit-body">
                        <form method="post" class="form-regist_event" enctype="multipart/form-data">
                            <div class="area-msg">
                                <?php
                                errorMsgCall('eventname');
                                ?>
                            </div>
                            <label class="label block-regist -first">
                                イベント名<span class="mypage-edit-caution">*必須</span><br>
                                <input type="text" class="input-text -mypage-edit -regist <?php classErrorCall('username'); ?>" name="eventname" value="<?php echo getFormData('eventname'); ?>">
                            </label>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('event_date');
                                ?>
                            </div>
                            <label class="label block-regist -event_date">
                                開催日<span class="mypage-edit-caution">*必須</span><br>
                                <div class="wrap-input-date-mypage">
                                    <input type="date" class="input-date date-mypage-edit -regist" name="event_date-start">
                                </div>
                                <span class="event_date-span">〜</span>
                                <div class="wrap-input-date-mypage">
                                    <input type="date" class="input-date date-mypage-edit -regist" name="event_date-end">
                                </div>
                            </label>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('event_time');
                                ?>
                            </div>
                            <label class="label block-regist -event_date">
                                開催時間<span class="mypage-edit-caution">*必須</span><br>
                                <div class="wrap-input-time-regist">
                                    <input type="time" class="input-time time-mypage-edit -regist" name="event_time-start" step="300">
                                </div>
                                <span class="event_date-span">〜</span>
                                <div class="wrap-input-time-regist">
                                    <input type="time" class="input-time time-mypage-edit -regist" name="event_time-end" step="300">
                                </div>
                            </label>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('category');
                                ?>
                            </div>
                            <label class="label block-regist">
                                カテゴリー<span class="mypage-edit-caution">*必須</span><br>
                                <div class="selectbox -mypage-edit -regist">
                                    <select name="category" class="select select-mypage-edit">
                                        <option value="0" <?php if (empty($category) || $category == '') echo 'selected'; ?>>選択してください</option>
                                        <?php
                                        foreach ($dbCategoryData as $key => $val) :
                                        ?>
                                            <option value="<?= $val['id'] ?>" <?php optionSelectedCall('category', $val['id']); ?>><?= $val['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </label>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('pref');
                                ?>
                            </div>
                            <label class="label block-regist">
                                開催地<span class="mypage-edit-caution">*必須</span><br>
                                <div class="selectbox -mypage-edit -regist">
                                    <select class="select select-mypage-edit" name="pref">
                                        <option value="0" <?php if (empty($pref) || $pref == '') echo 'selected'; ?>>選択してください</option>
                                        <optgroup label="北海道・東北">
                                            <option value="1" <?php optionSelectedCall('pref', 1); ?>>北海道</option>
                                            <option value="2" <?php optionSelectedCall('pref', 2); ?>>青森県</option>
                                            <option value="3" <?php optionSelectedCall('pref', 3); ?>>秋田県</option>
                                            <option value="4" <?php optionSelectedCall('pref', 4); ?>>岩手県</option>
                                            <option value="5" <?php optionSelectedCall('pref', 5); ?>>山形県</option>
                                            <option value="6" <?php optionSelectedCall('pref', 6); ?>>宮城県</option>
                                            <option value="7" <?php optionSelectedCall('pref', 7); ?>>福島県</option>
                                        </optgroup>
                                        <optgroup label="甲信越・北陸">
                                            <option value="8" <?php optionSelectedCall('pref', 8); ?>>山梨県</option>
                                            <option value="9" <?php optionSelectedCall('pref', 9); ?>>長野県</option>
                                            <option value="10" <?php optionSelectedCall('pref', 10); ?>>新潟県</option>
                                            <option value="11" <?php optionSelectedCall('pref', 11); ?>>富山県</option>
                                            <option value="12" <?php optionSelectedCall('pref', 12); ?>>石川県</option>
                                            <option value="13" <?php optionSelectedCall('pref', 13); ?>>福井県</option>
                                        </optgroup>
                                        <optgroup label="関東">
                                            <option value="14" <?php optionSelectedCall('pref', 14); ?>>茨城県</option>
                                            <option value="15" <?php optionSelectedCall('pref', 15); ?>>栃木県</option>
                                            <option value="16" <?php optionSelectedCall('pref', 16); ?>>群馬県</option>
                                            <option value="17" <?php optionSelectedCall('pref', 17); ?>>埼玉県</option>
                                            <option value="18" <?php optionSelectedCall('pref', 18); ?>>千葉県</option>
                                            <option value="19" <?php optionSelectedCall('pref', 19); ?>>東京都</option>
                                            <option value="20" <?php optionSelectedCall('pref', 20); ?>>神奈川県</option>
                                        </optgroup>
                                        <optgroup label="東海">
                                            <option value="21" <?php optionSelectedCall('pref', 21); ?>>愛知県</option>
                                            <option value="22" <?php optionSelectedCall('pref', 22); ?>>静岡県</option>
                                            <option value="23" <?php optionSelectedCall('pref', 23); ?>>岐阜県</option>
                                            <option value="24" <?php optionSelectedCall('pref', 24); ?>>三重県</option>
                                        </optgroup>
                                        <optgroup label="関西">
                                            <option value="25" <?php optionSelectedCall('pref', 25); ?>>大阪府</option>
                                            <option value="26" <?php optionSelectedCall('pref', 26); ?>>兵庫県</option>
                                            <option value="27" <?php optionSelectedCall('pref', 27); ?>>京都府</option>
                                            <option value="28" <?php optionSelectedCall('pref', 28); ?>>滋賀県</option>
                                            <option value="29" <?php optionSelectedCall('pref', 29); ?>>奈良県</option>
                                            <option value="30" <?php optionSelectedCall('pref', 30); ?>>和歌山県</option>
                                        </optgroup>
                                        <optgroup label="中国">
                                            <option value="31" <?php optionSelectedCall('pref', 31); ?>>岡山県</option>
                                            <option value="32" <?php optionSelectedCall('pref', 32); ?>>広島県</option>
                                            <option value="33" <?php optionSelectedCall('pref', 33); ?>>鳥取県</option>
                                            <option value="34" <?php optionSelectedCall('pref', 34); ?>>島根県</option>
                                            <option value="35" <?php optionSelectedCall('pref', 35); ?>>山口県</option>
                                        </optgroup>
                                        <optgroup label="四国">
                                            <option value="36" <?php optionSelectedCall('pref', 36); ?>>徳島県</option>
                                            <option value="37" <?php optionSelectedCall('pref', 37); ?>>香川県</option>
                                            <option value="38" <?php optionSelectedCall('pref', 38); ?>>愛媛県</option>
                                            <option value="39" <?php optionSelectedCall('pref', 39); ?>>高知県</option>
                                        </optgroup>
                                        <optgroup label="九州・沖縄">
                                            <option value="40" <?php optionSelectedCall('pref', 40); ?>>福岡県</option>
                                            <option value="41" <?php optionSelectedCall('pref', 41); ?>>佐賀県</option>
                                            <option value="42" <?php optionSelectedCall('pref', 42); ?>>長崎県</option>
                                            <option value="43" <?php optionSelectedCall('pref', 43); ?>>熊本県</option>
                                            <option value="44" <?php optionSelectedCall('pref', 44); ?>>大分県</option>
                                            <option value="45" <?php optionSelectedCall('pref', 45); ?>>宮崎県</option>
                                            <option value="46" <?php optionSelectedCall('pref', 46); ?>>鹿児島県</option>
                                            <option value="47" <?php optionSelectedCall('pref', 47); ?>>沖縄県</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </label>
                            <div class="area-msg">
                                <?php
                                errorMsgCall('address');
                                ?>
                            </div>
                            <label class="label block-regist -first">
                                開催場所<span class="mypage-edit-caution">*必須</span><br>
                                <input type="text" class="input-text -mypage-edit -regist <?php classErrorCall('address'); ?>" name="address" value="<?php echo getFormData('address'); ?>">
                            </label>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('format');
                                ?>
                            </div>
                            <div class="block-regist">
                                会場形式<span class="mypage-edit-caution">*必須</span><br>
                                <label><input type="radio" class="radio" name="format" value="1" <?php if (!empty($format) && $format == 1) echo 'checked'; ?>><span class="regist-checkbox-font">屋内のみ</span></label>
                                <label><input type="radio" class="radio" name="format" value="2" <?php if (!empty($format) && $format == 2) echo 'checked'; ?>><span class="regist-checkbox-font">屋外のみ</span></label>
                                <label><input type="radio" class="radio" name="format" value="3" <?php if (!empty($format) && $format == 3) echo 'checked'; ?>><span class="regist-checkbox-font">屋内・屋外</span></label>
                            </div>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('target');
                                ?>
                            </div>
                            <div class="block-regist">
                                参加対象<br>
                                <?php
                                foreach ($dbTargetData as $key => $val) :
                                ?>
                                    <label><input type="checkbox" class="checkbox" name="target[<?= $val['id'] ?>]" value="<?= $val['id'] ?>"><span class="regist-checkbox-font"><?= $val['name'] ?></span></label>
                                <?php endforeach; ?>
                                <input type="text" class="input-text -mypage-edit -regist_target" name="target-other" value="<?php echo getFormData('target-other'); ?>">
                            </div>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('entry');
                                ?>
                            </div>
                            <div class="label block-regist">
                                参加費<span class="mypage-edit-caution">*必須</span><br>
                                <label><input type="radio" class="radio" name="entry" value="1" onclick="payChange();" <?php if (!empty($entry) && $entry == 1) echo 'checked'; ?>><span class="regist-checkbox-font">無料</span></label>
                                <label><input type="radio" id="js-radio-pay" class="radio" name="entry" value="2" onclick="payChange();" <?php if (!empty($entry) && $entry == 2) echo 'checked'; ?>><span class="regist-checkbox-font">有料</span></label>
                                <textarea name="entry-fee" cols="50" rows="4" id="js-text-pay" class="textarea-regist no-display <?php classErrorCall('entry-fee'); ?>" placeholder="入力例：大人 1,000円&#13;&#10;　　　　中学生 800円"><?php echo getFormData('entry-fee'); ?></textarea>
                            </div>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('datail');
                                ?>
                            </div>
                            <label class="label block-regist">
                                詳細<span class="mypage-edit-caution">*必須</span><span id="textAttention-detail" style="display:none; color:red;">入力文字数が多すぎます</span><br>
                                <textarea name="detail" id="js-count-regist" cols="50" rows="10" class="textarea-regist <?php classErrorCall('username'); ?>" onkeyup="countLength(value,'js-counter-view-registEvent', 500, 'textAttention-detail');"><?php echo getFormData('detail'); ?></textarea>
                            </label>
                            <p class="regist-counter-text"><span id="js-counter-view-registEvent">0</span>/500文字</p>
                            <div class="block-regist">
                                画像
                                <div class="img_drop-container">
                                    <div class="area-img_drop -regist">
                                        <label class="label img-drop -regist">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                            <input type="file" class="regist-input-file input-file" name="pic1">
                                            <img src="<?php echo getFormData('pic1'); ?>" class="prev-img <?php if (empty(getFormData('pic1'))) echo "no-display"; ?>">
                                            ドラッグ＆ドロップ
                                        </label>
                                    </div>
                                    <div class="area-img_drop -regist">
                                        <label class="label img-drop -regist">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                            <input type="file" class="regist-input-file input-file" name="pic2">
                                            <img src="<?php echo getFormData('pic2'); ?>" class="prev-img <?php if (empty(getFormData('pic2'))) echo "no-display"; ?>">
                                            ドラッグ＆ドロップ
                                        </label>
                                    </div>
                                    <div class="area-img_drop -regist">
                                        <label class="label img-drop -regist">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                            <input type="file" class="regist-input-file input-file" name="pic3">
                                            <img src="<?php echo getFormData('pic3'); ?>" class="prev-img <?php if (empty(getFormData('pic3'))) echo "no-display"; ?>">
                                            ドラッグ＆ドロップ
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('organizer');
                                ?>
                            </div>
                            <label class="label block-regist">
                                主催・問い合わせ・申込先<span class="mypage-edit-caution">*必須</span><br>
                                <textarea name="organizer" cols="50" rows="6" class="textarea-regist <?php classErrorCall('username'); ?>"><?php echo getFormData('organizer'); ?></textarea>
                            </label>
                            <div class="submit-container-mypage-edit">
                                <input type="submit" class="submit submit-mypage-edit -regist" value="作成する">
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript" src="js/registEvent.js"></script>
        <?php
        require('footer.php');
        ?>