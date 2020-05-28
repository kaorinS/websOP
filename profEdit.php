<?php
// 共通変数・関数を読み込む
require('function.php');

// デバッグ
debug('**********************************************');
debug('*************** プロフィール編集 ***************');
debug('**********************************************');
debugLogStart();

// ログイン認証
require('auth.php');

// ================================
// 画面処理
// ================================
// ユーザー情報を取得
$userInfo = getUser($_SESSION['user_id']);
debug('$userInfo(DB情報)の中身→→→' . print_r($userInfo, true));

// POST送信されてるか
if (!empty($_POST)) {
    debug('$_POSTの中身→→→' . print_r($_POST, true));

    // $_POSTの中身を変数に代入
    $username = $_POST['username'];
    $pref = $_POST['pref'];
    $age = $_POST['age'];
    $email = $_POST['email'];

    // 未入力チェック
    validRequired($username, 'username');
    validRequired($email, 'email');

    // バリデーション
    if (empty($err_msg)) {
        // 登録情報と異なる場合、バリデーション
        // ユーザーネーム
        if ($username !== $userInfo['username']) {
            // 最大文字数チェック
            validMaxLen($username, 'username');
        }

        // 登録情報と異なる場合、バリデーション
        // メールアドレス
        if ($email !== $userInfo['email']) {
            // 形式チェック
            validEmail($email, 'email');
            // 最大文字数チェック
            validMaxLen($email, 'email');
            // 重複チェック
            validEmailDup($email, 'email');
        }

        // 都道府県
        // 漢字を含んでいるかチェック
        validKanji($pref, 'pref');

        // 年齢
        // 半角数字とハイフンだけかチェック
        validHalfNumberHyphen($age, 'age');

        if (empty($err_msg)) {
            debug('***** 「プロフィール編集」 バリデーションOK *****');

            // 例外処理
            try {
                // DB接続
                $dbh = dbConnect();
                // SQL文作成
                $sql = 'UPDATE users SET username = :username, pref = :pref, age = :age, email = :email WHERE id = :id';
                $data = array(':username' => $username, ':pref' => $pref, ':age' => $age, ':email' => $email, ':id' => $userInfo['id']);
                // クエリ実行
                $stmt = queryPost($dbh, $sql, $data);

                if ($stmt) {
                    // サクセスメッセージ設定
                    $_SESSION['msg_success'] = SUC02;

                    debug('***** 登録したので、マイページへ遷移します *****');
                    header("Location:mypage.php");
                    exit();
                }
            } catch (Exception $e) {
                error_log('!!!!! エラー発生 !!!!!' . $e->getMessage());
                $err_msg['common'] = MSG07;
            }
        }
    }
}
?>
<?php
$title = 'プロフィール編集　|　イベ探';
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
            $pageName = 'profEdit';
            require('sidebar_mypage.php');
            ?>
            <!-- メイン -->
            <main class="main">
                <section class="sec-mypage -prof_edit">
                    <div class="area-msg">
                        <?php
                        errorMsgCall('common');
                        ?>
                    </div>
                    <h2 class="mypage-edit-title">
                        プロフィール編集
                    </h2>
                    <div class="mypage-edit-body">
                        <form method="post" class="form-mypage-edit -prof" enctype="multipart/form-data">
                            <label class="label label-mypage-edit">
                                <div class="area-msg">
                                    <?php
                                    errorMsgCall('username');
                                    ?>
                                </div>
                                ユーザーネーム <span class="mypage-edit-caution">*必須</span><br>
                                <input type="text" class="input-text -mypage-edit <?php classErrorCall('username'); ?>" name="username" value="<?php echo getFormData('username'); ?>">
                            </label>
                            <!-- <div class="label-mypage-edit">
                                プロフィール画像
                            </div>
                            <label class=" label mypage-edit-area-drop">
                                <input type="hidden" name="MAX_FILE_SIZE" value="3145278">
                                <input type="file" class="mypage-edit-input-file" name="pic">
                                <img src="<?php echo getFormData('pic'); ?>" class="mypage-edit-prev-img <?php if (empty(getFormData('pic'))) echo 'no-display'; ?>">
                                ドラッグ&ドロップ
                            </label> -->
                            <label class="label label-mypage-edit">
                                <div class="area-msg">
                                    <?php
                                    errorMsgCall('pref');
                                    ?>
                                </div>
                                都道府県<br>
                                <div class="selectbox -mypage-edit">
                                    <select class="select select-mypage-edit" name="pref">
                                        <option value="0" <?php if ($userInfo['pref'] == '' || $userInfo['pref'] == 0) echo 'selected'; ?>>選択してください</option>
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
                            <label class="label label-mypage-edit">
                                <div class="area-msg">
                                    <?php
                                    errorMsgCall('age');
                                    ?>
                                </div>
                                年齢<br>
                                <div class="selectbox -mypage-edit">
                                    <select class="select select-mypage-edit" name="age">
                                        <option value="0" <?php if ($userInfo['age'] == '' || $userInfo['age'] == 0) echo 'selected'; ?>>選択してください</option>
                                        <option value="-15" <?php optionSelectedCall('age', '-15'); ?>>〜15歳</option>
                                        <option value="16-20" <?php optionSelectedCall('age', '16-20'); ?>>16歳〜20歳</option>
                                        <option value="21-25" <?php optionSelectedCall('age', '21-25'); ?>>21歳〜25歳</option>
                                        <option value="26-30" <?php optionSelectedCall('age', '26-30'); ?>>26歳〜30歳</option>
                                        <option value="31-35" <?php optionSelectedCall('age', '31-35'); ?>>31歳〜35歳</option>
                                        <option value="36-40" <?php optionSelectedCall('age', '36-40'); ?>>36歳〜40歳</option>
                                        <option value="41-45" <?php optionSelectedCall('age', '41-45'); ?>>41歳〜45歳</option>
                                        <option value="46-50" <?php optionSelectedCall('age', '46-50'); ?>>46歳〜50歳</option>
                                        <option value="51-55" <?php optionSelectedCall('age', '51-55'); ?>>51歳〜55歳</option>
                                        <option value="55-60" <?php optionSelectedCall('age', '55-60'); ?>>55歳〜60歳</option>
                                        <option value="61-65" <?php optionSelectedCall('age', '61-65'); ?>>61歳〜65歳</option>
                                        <option value="66-70" <?php optionSelectedCall('age', '66-70'); ?>>66歳〜70歳</option>
                                        <option value="71-" <?php optionSelectedCall('age', '71-'); ?>>71歳〜</option>
                                    </select>
                                </div>
                            </label>
                            <label class="label label-mypage-edit">
                                <div class="area-msg">
                                    <?php
                                    errorMsgCall('email');
                                    ?>
                                </div>
                                メールアドレス <span class="mypage-edit-caution">*必須</span><br>
                                <input type="text" class="input-text -mypage-edit <?php classErrorCall('username'); ?>" name="email" value="<?php echo getFormData('email'); ?>">
                            </label>
                            <div class="submit-container-mypage-edit">
                                <input type="submit" class="submit submit-mypage-edit" value="変更する">
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
        <?php
        require('footer.php');
        ?>