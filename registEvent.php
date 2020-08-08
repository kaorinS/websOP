<?php
// 共通変数・関数を読み込む
require('function.php');

// デバッグ
debug('**********************************************');
debug('*************** イベント登録ページ **************');
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
$dbInfo = (!empty($e_id)) ? getEventData($_SESSION['user_id'], $_GET['e_id']) : [];
// ユーザー情報を取得
$u_info = getUser($_SESSION['user_id']);
// 新規か編集か判別用フラグ
$edit_flg = (empty($dbInfo)) ? false : true;
if ($edit_flg) {
    debug('*************** イベント編集 ***************');
} else {
    debug('************* イベント新規作成 *************');
}
// DBからカテゴリーデータを取得
$dbCategoryData = getCategoryData();
// DBから参加対象のデータを取得
$dbTargetData = getTargetData();
debug('イベント情報($dbInfoの中身)→→→' . print_r($dbInfo, true));
// debug('カテゴリデータ($dbCategoryDataの中身)→→→' . print_r($dbCategoryData, true));
// debug('参加対象のデータ($dbTargetDataの中身)→→→' . print_r($dbTargetData, true));
// 参加対象の最後のキー（その他）を取得
$targetLastKey = array_key_last($dbTargetData);
$targetOtherId = $dbTargetData[$targetLastKey]['id'];
debug('参加対象の最後（その他）のkeyの値→→→' . $targetLastKey);
debug('参加対象の「その他」のID→→→' . $dbTargetData[$targetLastKey]['id']);
// 画像取得
if (!isset($pic1)) $pic1 = '';
if (!isset($pic2)) $pic2 = '';
if (!isset($pic3)) $pic3 = '';
debug('$pic1の中身→→→' . $pic1);
debug('$pic2の中身→→→' . $pic2);
debug('$pic3の中身→→→' . $pic3);
// dbInfoの参加対象を配列化
$dbTargetArray = !empty($dbInfo) ? explode(",", $dbInfo['target_age']) : '';
debug('$dbTargetArrayの中身→→→' . print_r($dbTargetArray, true));

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
    $place = $_POST['place'];
    $addr = $_POST['addr'];
    $format = (!empty($_POST['format'])) ? $_POST['format'] : '';
    $target = (isset($_POST['target']) && is_array($_POST['target'])) ? $_POST['target'] : '';
    $targetOther = $_POST['target-other'];
    $entry = (!empty($_POST['entry'])) ? (int) $_POST['entry'] : '';
    $entryFee = $_POST['entry-fee'];
    $capacity = (!empty($_POST['capacity'])) ? (int) $_POST['capacity'] : '';
    $people = (!empty($_POST['people'])) ? (int) $_POST['people'] : 0;
    $detail = $_POST['detail'];
    $organizer = $_POST['organizer'];
    // 画像をアップロードして、パスを格納
    if (empty($pic1))
        $pic1 = (!empty($_FILES['pic1']['name'])) ? uploadImg($_FILES['pic1'], 'pic1') : '';
    // 画像をPOSTしていないが、すでにDBに登録されてる場合、DBのパスを格納
    $pic1 = (empty($pic1) && !empty($dbInfo['pic1'])) ? $dbInfo['pic1'] : $pic1;
    if (empty($pic2))
        $pic2 = (!empty($_FILES['pic2']['name'])) ? uploadImg($_FILES['pic2'], 'pic2') : '';
    $pic2 = (empty($pic2) && !empty($dbInfo['pic2'])) ? $dbInfo['pic2'] : $pic2;
    if (empty($pic3))
        $pic3 = (!empty($_FILES['pic3']['name'])) ? uploadImg($_FILES['pic3'], 'pic3') : '';
    $pic3 = (empty($pic3) && !empty($dbInfo['pic3'])) ? $dbInfo['pic3'] : $pic3;
    debug('$pic1の中身→→→' . print_r($pic1, true));

    // 参加対象の配列を文字列にする変数を作っておく
    $target_separeted = '';
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
        validRequired($place, 'place');
        validRequired($addr, 'addr');
        validSelectRequired($format, 'format');
        if (!empty($target[$targetOtherId])) validRequired($targetOther, 'target', 'その他の欄を');
        validSelectRequired($entry, 'entry');
        if ($entry === 2) validRequired($entryFee, 'entry', '金額を');
        validSelectRequired($capacity, 'capacity');
        if ($capacity === 2) validRequired($people, 'capacity', '人数を');
        validRequired($detail, 'detail');
        validRequired($organizer, 'organizer');

        if (empty($err_msg)) {
            // イベント名最大文字数チェック
            validMaxLen($eventname, 'eventname');
            // 日付形式チェック
            validDate($eventStart, 'event_date');
            validDate($eventEnd, 'event_date');
            validEndDate($eventEnd, 'event_date');
            validDateOrder($eventStart, $eventEnd, 'event_date');
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
            validMaxLen($place, 'place');
            // 住所チェック
            validMaxLen($addr, 'addr');
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
            // 定員形式チェック
            validSelect($capacity, 'capacity');
            if (!empty($people)) validMaxLen($people, 'capacity');
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
        if ($dbInfo['date_start'] !== $eventStart) {
            //日付形式チェック
            validDate($eventStart, 'event_date');
        }
        // 開催日（終わり）
        if ($dbInfo['date_end'] !== $eventEnd) {
            //日付形式チェック
            validDate($eventEnd, 'event_date');
        }
        // 開催日時（始まり)
        if ($dbInfo['time_start'] !== $timeStart) {
            // 時刻形式チェック
            $checkedtime = validDateTime($timeStart, 'H:i');
            validRequired($checkedtime, 'event_time', '正しい時刻を');
        }
        // 開催日時(終わり)
        if ($dbInfo['time_end'] !== $timeEnd) {
            // 時刻形式チェック
            $checkedtime = validDateTime($timeEnd, 'H:i');
            validRequired($checkedtime, 'event_time', '正しい時刻を');
        }
        // 日時チェック
        validEndDate($eventEnd, 'event_date');
        validDateOrder($eventStart, $eventEnd, 'event_date');

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
        if ($dbInfo['place'] !== $place) {
            // 未入力チェック
            validRequired($place, 'place');
            // 最大文字数チェック
            validMaxLen($place, 'place');
        }
        // 住所
        if ($dbInfo['addr'] !== $addr) {
            // 未入力チェック
            validRequired($addr, 'addr');
            // 最大文字数チェック
            validMaxLen($addr, 'addr');
        }
        // 会場形式
        if ($dbInfo['format'] !== $format) {
            // 形式チェック
            validSelect($format, 'format');
        }
        // 参加対象
        $dbTarget = '';
        if (!empty($dbInfo['target_age'])) $dbTarget = $dbInfo['target_age'];
        if (strcmp('"' . $dbTarget . '"', '"' . $target_separeted . '"') != 0) {
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
        // 定員
        if ($dbInfo['capacity'] !== $capacity) {
            // 形式チェック
            validSelect($capacity, 'capacity');
        }
        if ($dbInfo['people'] !== $people) {
            // 最大文字数チェック
            validMaxLen($people, 'capacity');
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

    debug('$err_msgの中身→→→' . print_r($err_msg, true));
    if (empty($err_msg)) {
        $area = areaDecided($pref);
        debug('$areaの中身→→→' . $area);

        // 例外処理
        try {
            // DBヘ接続
            $dbh = dbConnect();
            // SQL文作成(新規：INSERT、更新：UPDATE)
            if ($edit_flg) {
                // 更新
                debug('***** イベント更新 *****');
                $sql = 'UPDATE festival SET name = :name, `format` = :format, c_id = :c_id, date_start = :date_start, date_end = :date_end, time_start = :time_start, time_end = :time_end, area = :area, pref = :pref, place = :place, addr = :addr, target_age = :target_age, target_other = :target_other, fee = :fee, pay = :pay, capacity = :capacity, people = :people, comment = :comment, contact = :contact, pic1 = :pic1, pic2 = :pic2, pic3 = :pic3 WHERE id = :id AND u_id = :u_id';
                $data = array(':name' => $eventname, ':format' => $format, ':c_id' => $category, ':date_start' => $eventStart, ':date_end' => $eventEnd, ':time_start' => $timeStart, ':time_end' => $timeEnd, ':area' => $area, ':pref' => $pref, ':place' => $place, ':addr' => $addr, ':target_age' => $target_separeted, ':target_other' => $targetOther, ':fee' => $entry, ':pay' => $entryFee, ':capacity' => $capacity, ':people' => $people, ':comment' => $detail, ':contact' => $organizer, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':id' => $e_id, ':u_id' => $_SESSION['user_id']);
            } else {
                // 新規
                debug('***** イベント新規作成 *****');
                $sql = 'INSERT INTO festival (name, `format`, c_id, date_start, date_end, time_start, time_end, area, pref, place, addr, target_age, target_other, fee, pay, capacity, people, comment, contact, u_id, pic1, pic2, pic3, created_at) VALUES (:name, :format, :c_id, :dateStart, :dateEnd, :timeStart, :timeEnd, :area, :pref, :place, :addr, :target, :targetOther, :entry, :pay, :capacity, :people, :detail, :contact, :u_id, :pic1, :pic2, :pic3, :created)';
                $data = array(':name' => $eventname, ':format' => $format, ':c_id' => $category, ':dateStart' => $eventStart, ':dateEnd'  => $eventEnd, ':timeStart' => $timeStart, ':timeEnd' => $timeEnd, ':area' => $area, ':pref' => $pref, ':place' => $place, ':addr' => $addr, ':target' => $target_separeted, ':targetOther' => $targetOther, ':entry' => $entry, ':pay' => $entryFee, ':capacity' => $capacity, ':people' => $people, ':detail' => $detail, ':contact' => $organizer, ':u_id' => $_SESSION['user_id'], ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':created' => date('Y-m-d H:i:s'));
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
                                <input type="text" class="input-text -mypage-edit -regist <?php classErrorCall('username'); ?>" name="eventname" value="<?php callRegistName($dbInfo, 'name', 'eventname'); ?>">
                            </label>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('event_date');
                                ?>
                            </div>
                            <label class="label block-regist -event_date">
                                開催日<span class="mypage-edit-caution">*必須</span><br>
                                <div class="wrap-input-date-mypage">
                                    <input type="date" class="input-date date-mypage-edit -regist" name="event_date-start" value="<?php callRegistName($dbInfo, 'date_start', 'event_date-start'); ?>">
                                </div>
                                <span class="event_date-span">〜</span>
                                <div class="wrap-input-date-mypage">
                                    <input type="date" class="input-date date-mypage-edit -regist" name="event_date-end" value="<?php callRegistName($dbInfo, 'date_end', 'event_date-end'); ?>">
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
                                    <input type="time" class="input-time time-mypage-edit -regist" name="event_time-start" step="300" value="<?php callRegistName($dbInfo, 'time_start', 'event_time-start'); ?>">
                                </div>
                                <span class="event_date-span">〜</span>
                                <div class="wrap-input-time-regist">
                                    <input type="time" class="input-time time-mypage-edit -regist" name="event_time-end" step="300" value="<?php callRegistName($dbInfo, 'time_end', 'event_time-end'); ?>">
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
                                        <option value="0" <?php if (empty($dbInfo) && (empty($category) || $category == '')) echo 'selected'; ?>>選択してください</option>
                                        <?php
                                        foreach ($dbCategoryData as $key => $val) :
                                        ?>
                                            <option value="<?= $val['id'] ?>" <?php if (!empty($dbInfo)) {
                                                                                    optionSelectedCall('c_id', $val['id']);
                                                                                } else {
                                                                                    optionSelectedCall('category', $val['id']);
                                                                                } ?>><?= $val['name'] ?></option>
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
                                    <select id="js-pref" class="select select-mypage-edit" name="pref" onchange="changePref(this);">
                                        <option value="0" <?php if (empty($pref) || $pref == '') echo 'selected'; ?>>選択してください</option>
                                        <optgroup label="北海道">
                                            <option value="1" <?php optionSelectedCall('pref', 1); ?>>北海道</option>
                                        </optgroup>
                                        <optgroup label="東北">
                                            <?php for ($i = 2; $i <= 7; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i) ?></option>
                                            <?php endfor; ?>
                                        </optgroup>
                                        <optgroup label="関東">
                                            <?php for ($i = 8; $i <= 14; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i) ?></option>
                                            <?php endfor; ?>
                                        </optgroup>
                                        <optgroup label="中部">
                                            <?php for ($i = 15; $i <= 23; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i) ?></option>
                                            <?php endfor; ?>
                                        </optgroup>
                                        <optgroup label="近畿">
                                            <?php for ($i = 24; $i <= 30; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i) ?></option>
                                            <?php endfor; ?>
                                        </optgroup>
                                        <optgroup label="中国">
                                            <?php for ($i = 31; $i <= 35; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i) ?></option>
                                            <?php endfor; ?>
                                        </optgroup>
                                        <optgroup label="四国">
                                            <?php for ($i = 36; $i <= 39; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i) ?></option>
                                            <?php endfor; ?>
                                        </optgroup>
                                        <optgroup label="九州・沖縄">
                                            <?php for ($i = 40; $i <= 47; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i) ?></option>
                                            <?php endfor; ?>
                                        </optgroup>
                                    </select>
                                </div>
                            </label>
                            <div class="area-msg">
                                <?php
                                errorMsgCall('place');
                                ?>
                            </div>
                            <label class="label block-regist -first">
                                開催場所<span class="mypage-edit-caution">*必須</span><br>
                                <input type="text" class="input-text -mypage-edit -regist <?php classErrorCall('place'); ?>" name="place" value="<?php echo getFormData('place'); ?>">
                            </label>
                            <div class="area-msg">
                                <?php
                                errorMsgCall('addr');
                                ?>
                            </div>
                            <label class="label block-regist -first">
                                住所<span class="mypage-edit-caution">*必須</span><br>
                                <input type="text" id="js-addr" class="input-text -mypage-edit -regist <?php classErrorCall('addr'); ?>" name="addr" value="<?php echo getFormData('addr'); ?>">
                            </label>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('format');
                                ?>
                            </div>
                            <div class="block-regist">
                                会場形式<span class="mypage-edit-caution">*必須</span><br>
                                <label><input type="radio" class="radio" name="format" value="1" <?php if ((!empty($format) && $format == 1) || (!empty($dbInfo) && (int) $dbInfo['format'] === 1)) echo 'checked'; ?>><span class="regist-checkbox-font">屋内のみ</span></label>
                                <label><input type="radio" class="radio" name="format" value="2" <?php if ((!empty($format) && $format == 2) || (!empty($dbInfo) && (int) $dbInfo['format'] === 2)) echo 'checked'; ?>><span class="regist-checkbox-font">屋外のみ</span></label>
                                <label><input type="radio" class="radio" name="format" value="3" <?php if ((!empty($format) && $format == 3) || (!empty($dbInfo) && (int) $dbInfo['format'] === 3)) echo 'checked'; ?>><span class="regist-checkbox-font">屋内・屋外</span></label>
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
                                    <label><input type="checkbox" class="checkbox" name="target[<?= $val['id'] ?>]" value="<?= $val['id'] ?>" <?php if (!empty($target[$val['id']])) {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } elseif (!empty($dbTargetArray[$val['id'] - 1])) {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } ?>><span class="regist-checkbox-font"><?= $val['name'] ?></span></label>
                                <?php endforeach; ?>
                                <input type="text" class="input-text -mypage-edit -regist_target" name="target-other" value="<?= callRegistName($dbInfo, 'target_other', 'target-other') ?>">
                            </div>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('entry');
                                ?>
                            </div>
                            <div class="label block-regist">
                                参加費・入場料など<span class="mypage-edit-caution">*必須</span><br>
                                <label><input type="radio" class="radio" name="entry" value="1" onclick="payChange();" <?php if (!empty($entry) && $entry === 1 || !empty($dbInfo) && (int) $dbInfo['fee'] === 1) echo 'checked'; ?>><span class="regist-checkbox-font">無料</span></label>
                                <label><input type="radio" id="js-radio-pay" class="radio" name="entry" value="2" onclick="payChange();" <?php if (!empty($entry) && $entry === 2 || !empty($dbInfo) && (int) $dbInfo['fee'] === 2) echo 'checked'; ?>><span class="regist-checkbox-font">有料</span></label>
                                <textarea name="entry-fee" cols="50" rows="4" id="js-text-pay" class="textarea-regist <?php if (empty($entry) || $entry !== 2) echo 'no-display' ?> <?php classErrorCall('entry-fee'); ?>" placeholder="入力例：大人 1,000円&#13;&#10;　　　　中学生 800円"><?= callRegistName($dbInfo, 'pay', 'entry-fee') ?></textarea>
                            </div>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('capacity');
                                ?>
                            </div>
                            <div class="block-regist">
                                定員<span class="mypage-edit-caution">*必須</span><br>
                                <label><input type="radio" class="radio" name="capacity[]" value="1" <?php if (!empty($capacity) && $capacity == 1 || !empty($dbInfo) && (int) $dbInfo['capacity'] === 1) echo 'checked'; ?>><span class="regist-checkbox-font">無し</span></label>
                                <label><input type="radio" class="radio" name="capacity[]" value="2" <?php if (!empty($capacity) && $capacity == 2 || !empty($dbInfo) && (int) $dbInfo['capacity'] === 2) echo 'checked'; ?>><span class="regist-checkbox-font">有り</span></label>
                                <input type="text" class="input-text -mypage-edit -regist_people" name="people" value="<?php if ((int) getFormData('people') !== 0) getFormData('people') ?>"><span class="in_regist">名</span>
                            </div>
                            <div class="area-msg -regist">
                                <?php
                                errorMsgCall('datail');
                                ?>
                            </div>
                            <label class="label block-regist">
                                詳細<span class="mypage-edit-caution">*必須</span><span id="textAttention-detail" style="display:none; color:red;">入力文字数が多すぎます</span><br>
                                <textarea name="detail" id="js-count-regist" cols="50" rows="10" class="textarea-regist <?php classErrorCall('username'); ?>" onkeyup="countLength(value,'js-counter-view-registEvent', 500, 'textAttention-detail');"><?= callRegistName($dbInfo, 'comment', 'detail') ?></textarea>
                            </label>
                            <p class="regist-counter-text"><span id="js-counter-view-registEvent">0</span>/500文字</p>
                            <div class="block-regist">
                                画像
                                <div class="img_drop-container">
                                    <div class="area-img_drop -regist">
                                        <label class="label img-drop -regist">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                            <input type="file" class="regist-input-file input-file" name="pic1">
                                            <img src="<?php if (!empty($pic1)) {
                                                            echo sanitize($pic1);
                                                        } elseif (!empty($dbInfo['pic1'])) {
                                                            echo sanitize($dbInfo['pic1']);
                                                        } ?>" class="prev-img <?php if (empty($pic1 || $dbInfo['pic1'])) echo "no-display"; ?>">
                                            ドラッグ＆ドロップ
                                        </label>
                                    </div>
                                    <div class="area-img_drop -regist">
                                        <label class="label img-drop -regist">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                            <input type="file" class="regist-input-file input-file" name="pic2">
                                            <img src="<?php if (!empty($pic2)) {
                                                            echo sanitize($pic2);
                                                        } elseif (!empty($dbInfo['pic2'])) {
                                                            echo sanitize($dbInfo['pic2']);
                                                        } ?>" class="prev-img <?php if (empty($pic2 || $dbInfo['pic2'])) echo "no-display"; ?>">
                                            ドラッグ＆ドロップ
                                        </label>
                                    </div>
                                    <div class="area-img_drop -regist">
                                        <label class="label img-drop -regist">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                            <input type="file" class="regist-input-file input-file" name="pic3">
                                            <img src="<?php if (!empty($pic3)) {
                                                            echo sanitize($pic3);
                                                        } elseif (!empty($dbInfo['pic3'])) {
                                                            echo sanitize($dbInfo['pic3']);
                                                        } ?>" class="prev-img <?php if (empty($pic3 || $dbInfo['pic3'])) echo "no-display"; ?>">
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
                                <textarea name="organizer" cols="50" rows="6" class="textarea-regist <?php classErrorCall('username'); ?>"><?= callRegistName($dbInfo, 'contact', 'organizer') ?></textarea>
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