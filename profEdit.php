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
        // 1~47かどうかチェック
        validPref($pref, 'pref');

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
                                        <optgroup label="北海道">
                                            <option value="1" <?php optionSelectedCall('pref', 1); ?>>北海道</option>
                                        </optgroup>
                                        <optgroup label="東北">
                                            <?php for ($i = 2; $i <= 7; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i - 1) ?></option>
                                            <?php endfor; ?>
                                        </optgroup>
                                        <optgroup label="関東">
                                            <?php for ($i = 8; $i <= 14; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i - 1) ?></option>
                                            <?php endfor; ?>
                                        </optgroup>
                                        <optgroup label="中部">
                                            <?php for ($i = 15; $i <= 23; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i - 1) ?></option>
                                            <?php endfor; ?>
                                        </optgroup>
                                        <optgroup label="近畿">
                                            <?php for ($i = 24; $i <= 30; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i - 1) ?></option>
                                            <?php endfor; ?>
                                        </optgroup>
                                        <optgroup label="中国">
                                            <?php for ($i = 31; $i <= 35; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i - 1) ?></option>
                                            <?php endfor; ?>
                                        </optgroup>
                                        <optgroup label="四国">
                                            <?php for ($i = 36; $i <= 39; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i - 1) ?></option>
                                            <?php endfor; ?>
                                        </optgroup>
                                        <optgroup label="九州・沖縄">
                                            <?php for ($i = 40; $i <= 47; $i++) : ?>
                                                <option value="<?= $i ?>" <?php optionSelectedCall('pref', $i); ?>><?= prefNameCalled($i - 1) ?></option>
                                            <?php endfor; ?>
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
                                <input type="text" class="input-text -mypage-edit <?php classErrorCall('email'); ?>" name="email" value="<?php echo getFormData('email'); ?>">
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