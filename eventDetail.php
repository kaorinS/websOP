<?php
// 共通変数・関数を読み込む
require('function.php');

// デバッグ
debug('**********************************************');
debug('*************** イベント詳細ページ **************');
debug('**********************************************');
debugLogStart();

// ================================
// 画面処理
// ================================
// *****データ取得*****
// GETの中身を取得
debug('$_GETの中身→→→' . print_r($_GET, true));
// イベントIDを取得
$e_id = takeGetValue('e_id');
// DBからイベントデータを取得
$viewData = getEventOne($e_id);
// ユーザーIDを取得
$u_id = $_SESSION['user_id'];
debug('$u_idの中身→→→' . $u_id);
debug('$viewData[u_id]の中身→→→' . $viewData['u_id']);

// ***** 処理 *****
// イベントデータの有無を確認
if (empty($viewData)) {
    error_log('!!!!! エラー発生 !!!!!');
    error_log('!!!!! イベントIDが不正な値 !!!!!');
    error_log('!!!!! TOPページへ遷移 !!!!!');
    header("Location:index.php");
    exit;
}
// debug('$viewData(DBデータ)の中身→→→' . print_r($viewData, true));

// ================================
// イベント消去の処理
// ================================
// POST送信されているか
if (!empty($_POST)) {
    debug('***** POST送信されました *****');
    if ((int) $u_id === (int) $viewData['u_id']) {
        // 例外処理
        try {
            // DB接続
            $dbh = dbConnect();
            // SQL文作成
            $sql = 'UPDATE festival SET is_deleted = 1 WHERE u_id = :u_id AND id = :f_id';
            $data = array(':u_id' => $u_id, ':f_id' => $viewData['id']);
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
            // クエリ成功
            if ($stmt) {
                debug('***** イベント削除処理 クエリ成功 *****');
                $_SESSION['msg_success'] = SUC05;
                // マイページへ遷移
                debug('***** マイページへ遷移 *****');
                header("Location:mypage.php");
                exit();
            } else {
                debug('!!!!! イベント削除処理 クエリ失敗 !!!!!');
                $err_msg['common'] = MSG07;
            }
        } catch (Exception $e) {
            error_log('!!!!! エラー発生 !!!!!' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    } else {
        debug('!!!!! 作成者と接続者が異なります !!!!!');
    }
}
?>
<?php
$title = $viewData['name'] . ' | イベ探';
require('head.php');
?>

<body class="page-eventDetail page-1colum">
    <div class="wrapper">
        <!-- header -->
        <?php
        require('header.php');
        ?>
        <!-- メインコンテンツ  -->
        <div class="main-container site-width">
            <div class="area-msg">
                <?php
                errorMsgCall('common');
                ?>
            </div>
            <div class="display-header">
                <div class="display-category">
                    <a href="index.php" class="a a-under">イベ探 TOP</a> > <a href="index.php?category=<?= sanitize($viewData['c_id']) ?>" class="a a-under"><?= sanitize($viewData['category']) ?></a><br>
                    <a href="index.php?area=<?= sanitize($viewData['area']) ?>" class="a a-under"><?= sanitize(areaNameCalled($viewData['area'])) ?></a> > <a href="index.php?pref=<?= sanitize($viewData['pref']) ?>"><span class="span-pref <?= sanitize(areaClassCalled($viewData['area'])) ?>"><?= sanitize(prefNameCalled($viewData['pref'])) ?></span></a>
                </div>
                <?php if ((int) $u_id === (int) $viewData['u_id']) : ?>
                    <div class="display-edit">
                        <a href="registEvent.php?e_id=<?= $viewData['id'] ?>" class="display-event event-edit display-event-border">
                            <i class="fas fa-edit icn-edit"></i>編集する
                        </a>
                        <div class="display-event">
                            <form method="post" onSubmit="return check()">
                                <input type="submit" value="&#xf2ed;  削除する" class="submit fas icn-edit -delete display-event-border event-delete" name="submit">
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <main class="main eventDetail-main">
                <button type="button" id="js-like" class="add-like js-click-like <?php if (isLike($_SESSION['user_id'], $viewData['id'])) echo 'active'; ?>" data-eventid="<?= sanitize($viewData['id']) ?>"><i class="fas fa-heart icn-like"></i><span class="btn-text">お気に入りに追加</span></button>
                <div class="detail-header">
                    <div class="detail-header-heading">
                        <h1 class="detail-header-title"><?= sanitize($viewData['name']) ?></h1>
                    </div>
                    <div class="detail-header-box">
                        <div class="detail-header-box-period">
                            <div class="detail-header-box-icon">
                                <span class="span-detail-header-open">開催期間</span>
                            </div>
                            <span class="span-detail-header-text"><?= sanitize(date("Y年n月j日", strtotime($viewData['date_start']))) . callDayOfWeek($viewData['date_start']) ?><?php if ($viewData['date_start'] !== $viewData['date_end']) echo ' 〜 ' . sanitize(date("Y年n月j日", strtotime($viewData['date_end']))) . callDayOfWeek(($viewData['date_end']));  ?></span>
                        </div>
                        <div class="detail-header-box-period">
                            <div class="detail-header-box-icon">
                                <span class="span-detail-header-open">開催時間</span>
                            </div>
                            <span class="span-detail-header-text"><?= sanitize(callTime($viewData['time_start'])) . ' 〜 ' . sanitize(callTime($viewData['time_end'])) ?></span>
                        </div>
                        <div class="detail-header-box-period">
                            <div class="detail-header-box-icon">
                                <span class="span-detail-header-open">場所</span>
                            </div>
                            <span class="span-detail-header-text"><?= sanitize($viewData['place']) ?></span>
                        </div>
                    </div>
                </div>
                <div class="detail-main">
                    <div class="detail-main-header">
                        <div class="detail-main-header-mainimg">
                            <img src="<?= sanitize($viewData['pic1']) ?>" class="img-detail-main" id="js-switch-img-main">
                        </div>
                        <div class="detail-main-header-subimg">
                            <ul class="ul-detail-main-subimg">
                                <?php if (!empty($viewData['pic1'])) {
                                    echo '<li class="li-detail-main-subimg">';
                                    echo '<img src="' . sanitize($viewData['pic1']) . '" class="img-detail-sub js-switch-img-sub">';
                                    echo '</li>';
                                } ?>
                                <?php if (!empty($viewData['pic2'])) {
                                    echo '<li class="li-detail-main-subimg">';
                                    echo '<img src="' . sanitize($viewData['pic2']) . '" class="img-detail-sub js-switch-img-sub">';
                                    echo '</li>';
                                } ?>
                                <?php if (!empty($viewData['pic3'])) {
                                    echo '<li class="li-detail-main-subimg">';
                                    echo '<img src="' . sanitize($viewData['pic3']) . '" class="img-detail-sub js-switch-img-sub">';
                                    echo '</li>';
                                } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="detail-main-body">
                        <div class="detail-main-info">
                            <div class="detail-main-info-body">
                                <p class="detail-main-article">
                                    <?= sanitize_br($viewData['comment']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="detail-main-footer">
                        <div class="detail-main-date">
                            <p class="detail-date-info">イベント情報（詳細）</p>
                            <table class="table detail-main-table">
                                <tbody>
                                    <tr class="detail-date-row">
                                        <th class="detail-date-th">開催期間</th>
                                        <td class="detail-date-td"><?= sanitize(date("Y年n月j日", strtotime($viewData['date_start']))) ?><?= callDayOfWeek($viewData['date_start']) ?><?php if ($viewData['date_start'] !== $viewData['date_end']) echo ' 〜 ' . sanitize(date("Y年n月j日", strtotime($viewData['date_end']))) . callDayOfWeek($viewData['date_end']) ?></td>
                                    </tr>
                                    <tr class="detail-date-row">
                                        <th class="detail-date-th">開催時間</th>
                                        <td class="detail-date-td"><?= sanitize(callTime($viewData['time_start'])) . ' 〜 ' . sanitize(callTime($viewData['time_end'])) ?></td>
                                    </tr>
                                    <tr class="detail-date-row">
                                        <th class="detail-date-th">料金</th>
                                        <td class="detail-date-td"><?php if ((int) $viewData['fee'] === 1) {
                                                                        echo '無料';
                                                                    } else {
                                                                        echo sanitize_br($viewData['pay']);
                                                                    } ?></td>
                                    </tr>
                                    <tr class="detail-date-row">
                                        <th class="detail-date-th">定員</th>
                                        <td class="detail-date-td"><?php if ((int) $viewData['capacity'] === 1) {
                                                                        echo '無し';
                                                                    } else {
                                                                        echo sanitize($viewData['people']) . '名';
                                                                    } ?></td>
                                    </tr>
                                    <tr class="detail-date-row">
                                        <th class="detail-date-th">会場</th>
                                        <td class="detail-date-td"><?= sanitize($viewData['place']) ?></td>
                                    </tr>
                                    <tr class="detail-date-row">
                                        <th class="detail-date-th">住所</th>
                                        <td class="detail-date-td"><?= sanitize($viewData['addr']) ?></td>
                                    </tr>
                                    <tr class="detail-date-row">
                                        <th class="detail-date-th">主催者情報</th>
                                        <td class="detail-date-td"><?= sanitize_br($viewData['contact']) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <div class="back-index-eventDetail">
                <a href="index.php<?= appendGetParam(array('e_id')); ?>">
                    << TOPに戻る</a> </div> <div class="return-top-wrap">
                        <button id="js-return-top" class="return-top js-fadein"></button>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript" src="js/eventDetail.js"></script>
        <?php
        require('footer.php');
        ?>