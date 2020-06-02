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
// DBからカテゴリーデータを取得
$dbCategoryData = getCategoryData();
// DBから参加対象のデータを取得
$dbTargetData = getTargetData();
debug('イベントID→→→' . $e_id);
debug('イベント情報($dbInfoの中身)→→→' . print_r($dbInfo, true));
debug('カテゴリデータ($dbCategoryDataの中身)→→→' . print_r($dbCategoryData, true));
debug('参加対象のデータ($dbTargetDataの中身)→→→' . print_r($dbTargetData, true));

// ========== パラメータ改竄チェック ==========
// GETパラメータが改竄されている場合、マイページへ遷移さsる
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
    $eventEnd = $_POST['event_date-end'];
    $category = $_POST['category'];
    $pref = $_POST['pref'];
    $format = $_POST['format'];
    $target = $_POST['target'];
    $targetOther = $_POST['target-other'];
    $entry = $_POST['entry'];
    $entryFee = $_POST['entry-fee'];
    $detail = $_POST['detail'];
    $organizer = $_POST['organizer'];
    $pic1 = (!empty($_FILES['pic1']['name'])) ? uploadImg($_FILES['pic1'], 'pic1') : '';
    $pic1 = (empty($pic1) && !empty($dbInfo['pic1'])) ? $dbInfo['pic1'] : $pic1;
    $pic2 = (!empty($_FILES['pic2']['name'])) ? uploadImg($_FILES['pic2'], 'pic2') : '';
    $pic2 = (empty($pic2) && !empty($dbInfo['pic2'])) ? $dbInfo['pic2'] : $pic2;
    $pic3 = (!empty($_FILES['pic3']['name'])) ? uploadImg($_FILES['pic3'], 'pic3') : '';
    $pic3 = (empty($pic3) && !empty($dbInfo['pic3'])) ? $dbInfo['pic3'] : $pic3;

    // バリデーション
    if (empty($dbInfo)) {
        // 未入力チェック
        validRequired($eventname, 'eventname');
        validSelectboxRequired($category, 'category');
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
                    <div class="mypage-edit-body">
                        <form method="post" class="form-regist_event">
                            <label class="label block-regist -first">
                                イベント名<span class="mypage-edit-caution">*必須</span><br>
                                <input type="text" class="input-text -mypage-edit -regist" name="eventname" value="<?php echo getFormData('eventname'); ?>">
                            </label>
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
                            <label class="label block-regist">
                                カテゴリー<span class="mypage-edit-caution">*必須</span><br>
                                <div class="selectbox -mypage-edit -regist">
                                    <select name="category" class="select select-mypage-edit">
                                        <option value="0" <?php if (getFormData('category') == 0) echo 'selected'; ?>>選択してください</option>
                                        <?php
                                        foreach ($dbCategoryData as $key => $val) :
                                        ?>
                                            <option value="<?= $val['id'] ?>" <?php if (getFormData('category') == $val['id']) echo 'selected'; ?>><?= $val['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </label>
                            <label class="label block-regist">
                                開催地<span class="mypage-edit-caution">*必須</span><br>
                                <div class="selectbox -mypage-edit -regist">
                                    <select class="select select-mypage-edit" name="pref">
                                        <option value="0" <?php if (empty($pref) || $pref == '') echo 'selected'; ?>>選択してください</option>
                                        <optgroup label="北海道・東北">
                                            <option value="北海道" <?php optionSelectedCall('pref', '北海道'); ?>>北海道</option>
                                            <option value="青森県" <?php optionSelectedCall('pref', '青森県'); ?>>青森県</option>
                                            <option value="秋田県" <?php optionSelectedCall('pref', '秋田県'); ?>>秋田県</option>
                                            <option value="岩手県" <?php optionSelectedCall('pref', '岩手県'); ?>>岩手県</option>
                                            <option value="山形県" <?php optionSelectedCall('pref', ' 山形県'); ?>>山形県</option>
                                            <option value="宮城県" <?php optionSelectedCall('pref', '宮城県'); ?>>宮城県</option>
                                            <option value="福島県" <?php optionSelectedCall('pref', '福島県'); ?>>福島県</option>
                                        </optgroup>
                                        <optgroup label="甲信越・北陸">
                                            <option value="山梨県" <?php optionSelectedCall('pref', '山梨県'); ?>>山梨県</option>
                                            <option value="長野県" <?php optionSelectedCall('pref', '長野県'); ?>>長野県</option>
                                            <option value="新潟県" <?php optionSelectedCall('pref', '新潟県'); ?>>新潟県</option>
                                            <option value="富山県" <?php optionSelectedCall('pref', '富山県'); ?>>富山県</option>
                                            <option value="石川県" <?php optionSelectedCall('pref', '石川県'); ?>>石川県</option>
                                            <option value="福井県" <?php optionSelectedCall('pref', '福井県'); ?>>福井県</option>
                                        </optgroup>
                                        <optgroup label="関東">
                                            <option value="茨城県" <?php optionSelectedCall('pref', '茨城県'); ?>>茨城県</option>
                                            <option value="栃木県" <?php optionSelectedCall('pref', '栃木県'); ?>>栃木県</option>
                                            <option value="群馬県" <?php optionSelectedCall('pref', '群馬県'); ?>>群馬県</option>
                                            <option value="埼玉県" <?php optionSelectedCall('pref', '埼玉県'); ?>>埼玉県</option>
                                            <option value="千葉県" <?php optionSelectedCall('pref', '千葉県'); ?>>千葉県</option>
                                            <option value="東京都" <?php optionSelectedCall('pref', '東京都'); ?>>東京都</option>
                                            <option value="神奈川県" <?php optionSelectedCall('pref', '神奈川県'); ?>>神奈川県</option>
                                        </optgroup>
                                        <optgroup label="東海">
                                            <option value="愛知県" <?php optionSelectedCall('pref', '愛知県'); ?>>愛知県</option>
                                            <option value="静岡県" <?php optionSelectedCall('pref', '静岡県'); ?>>静岡県</option>
                                            <option value="岐阜県" <?php optionSelectedCall('pref', '岐阜県'); ?>>岐阜県</option>
                                            <option value="三重県" <?php optionSelectedCall('pref', '三重県'); ?>>三重県</option>
                                        </optgroup>
                                        <optgroup label="関西">
                                            <option value="大阪府" <?php optionSelectedCall('pref', '大阪府'); ?>>大阪府</option>
                                            <option value="兵庫県" <?php optionSelectedCall('pref', '兵庫県'); ?>>兵庫県</option>
                                            <option value="京都府" <?php optionSelectedCall('pref', '京都府'); ?>>京都府</option>
                                            <option value="滋賀県" <?php optionSelectedCall('pref', '滋賀県'); ?>>滋賀県</option>
                                            <option value="奈良県" <?php optionSelectedCall('pref', '奈良県'); ?>>奈良県</option>
                                            <option value="和歌山県" <?php optionSelectedCall('pref', '和歌山県'); ?>>和歌山県</option>
                                        </optgroup>
                                        <optgroup label="中国">
                                            <option value="岡山県" <?php optionSelectedCall('pref', '岡山県'); ?>>岡山県</option>
                                            <option value="広島県" <?php optionSelectedCall('pref', '広島県'); ?>>広島県</option>
                                            <option value="鳥取県" <?php optionSelectedCall('pref', '鳥取県'); ?>>鳥取県</option>
                                            <option value="島根県" <?php optionSelectedCall('pref', '島根県'); ?>>島根県</option>
                                            <option value="山口県" <?php optionSelectedCall('pref', '山口県'); ?>>山口県</option>
                                        </optgroup>
                                        <optgroup label="四国">
                                            <option value="徳島県" <?php optionSelectedCall('pref', '徳島県'); ?>>徳島県</option>
                                            <option value="香川県" <?php optionSelectedCall('pref', '香川県'); ?>>香川県</option>
                                            <option value="愛媛県" <?php optionSelectedCall('pref', '愛媛県'); ?>>愛媛県</option>
                                            <option value="高知県" <?php optionSelectedCall('pref', '高知県'); ?>>高知県</option>
                                        </optgroup>
                                        <optgroup label="九州・沖縄">
                                            <option value="福岡県" <?php optionSelectedCall('pref', '福岡県'); ?>>福岡県</option>
                                            <option value="佐賀県" <?php optionSelectedCall('pref', '佐賀県'); ?>>佐賀県</option>
                                            <option value="長崎県" <?php optionSelectedCall('pref', '長崎県'); ?>>長崎県</option>
                                            <option value="熊本県" <?php optionSelectedCall('pref', '熊本県'); ?>>熊本県</option>
                                            <option value="大分県" <?php optionSelectedCall('pref', '大分県'); ?>>大分県</option>
                                            <option value="宮崎県" <?php optionSelectedCall('pref', '宮崎県'); ?>>宮崎県</option>
                                            <option value="鹿児島県" <?php optionSelectedCall('pref', '鹿児島県'); ?>>鹿児島県</option>
                                            <option value="沖縄県" <?php optionSelectedCall('pref', '沖縄県'); ?>>沖縄県</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </label>
                            <div class="block-regist">
                                会場形式<span class="mypage-edit-caution">*必須</span><br>
                                <label><input type="radio" class="radio" name="format" value="1" <?php if (!empty($format) && $format == 1) echo 'checked'; ?>><span class="regist-checkbox-font">屋内のみ</span></label>
                                <label><input type="radio" class="radio" name="format" value="2" <?php if (!empty($form) && $format == 2) echo 'checked'; ?>><span class="regist-checkbox-font">屋外のみ</span></label>
                                <label><input type="radio" class="radio" name="format" value="3" <?php if (!empty($format) && $format == 3) echo 'checked'; ?>><span class="regist-checkbox-font">屋内・屋外</span></label>
                            </div>
                            <div class="block-regist">
                                参加対象<br>
                                <?php
                                foreach ($dbTargetData as $key => $val) :
                                ?>
                                    <label><input type="checkbox" class="checkbox" name="target[<?= $val['id'] ?>]" value="<?= $val['id'] ?>" <?php if (getFormData('target') == $val['id']) echo 'checked'; ?>><span class="regist-checkbox-font"><?= $val['name'] ?></span></label>
                                <?php endforeach; ?>
                                <input type="text" class="input-text -mypage-edit -regist_target" name="target-other" value="<?php echo getFormData('target-other'); ?>">
                            </div>
                            <div class="label block-regist">
                                参加費<span class="mypage-edit-caution">*必須</span><br>
                                <label><input type="radio" class="radio" name="entry" value="1" <?php if (!empty($entry) && $entry == 1) echo 'checked'; ?>><span class="regist-checkbox-font">無料</span></label>
                                <label><input type="radio" class="radio" name="entry" value="2" <?php if (!empty($entry) && $entry == 2) echo 'checked'; ?>><span class="regist-checkbox-font">有料</span></label>
                                <label>
                                    <input type="text" class="input-text -mypage-edit -regist_target" name="entry-fee"><span class="regist-checkbox-font" value="<?php echo getFormData('entry-fee'); ?>">円</span>
                                </label>
                            </div>
                            <label class="label block-regist">
                                詳細<span class="mypage-edit-caution">*必須</span><span id="textAttention-detail" style="display:none; color:red;">入力文字数が多すぎます</span><br>
                                <textarea name="detail" id="js-count-regist" cols="50" rows="10" class="textarea-regist" onkeyup="countLength(value,'js-counter-view-registEvent', 500, 'textAttention-detail');"><?php echo getFormData('detail'); ?></textarea>
                            </label>
                            <p class="regist-counter-text"><span id="js-counter-view-registEvent">0</span>/500文字</p>
                            <div class="block-regist">
                                画像
                                <div class="img_drop-container">
                                    <div class="area-img_drop -regist">
                                        <label class="label img-drop -regist">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                            <input type="file" class="regist-input-file" name="pic1">
                                            <img src="<?php echo getFormData('pic1'); ?>" class="prev-img <?php if (empty(getFormData('pic1'))) echo "no-display"; ?>">
                                            ドラッグ＆ドロップ
                                        </label>
                                    </div>
                                    <div class="area-img_drop -regist">
                                        <label class="label img-drop -regist">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                            <input type="file" class="regist-input-file" name="pic1">
                                            <img src="<?php echo getFormData('pic1'); ?>" class="prev-img <?php if (empty(getFormData('pic1'))) echo "no-display"; ?>">
                                            ドラッグ＆ドロップ
                                        </label>
                                    </div>
                                    <div class="area-img_drop -regist">
                                        <label class="label img-drop -regist">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                                            <input type="file" class="regist-input-file" name="pic1">
                                            <img src="<?php echo getFormData('pic1'); ?>" class="prev-img <?php if (empty(getFormData('pic1'))) echo "no-display"; ?>">
                                            ドラッグ＆ドロップ
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <label class="label block-regist">
                                主催・問い合わせ・申込先<span class="mypage-edit-caution">*必須</span><br>
                                <textarea name="organizer" cols="50" rows="6" class="textarea-regist"><?php echo getFormData('organizer'); ?></textarea>
                            </label>
                            <div class="submit-container-mypage-edit">
                                <input type="submit" class="submit submit-mypage-edit -regist" value="作成する">
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
        <script type="text/javascript" src="js/registEvent.js"></script>
        <?php
        require('footer.php');
        ?>