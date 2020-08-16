<?php
// 共通変数・関数を読み込む
require('function.php');

// デバッグ
debug('**********************************************');
debug('********** マイページ(作成したイベント) **********');
debug('**********************************************');
debugLogStart();

// ログイン認証
require('auth.php');

// ================================
// 画面処理
// ================================
// ユーザーIDを代入
$u_id = $_SESSION['user_id'];
// ユーザー情報を取得(サイドバー用)
$u_info = getUser($u_id);
// 現在何ページ目か取得(デフォは１)
$currentPangeNum = takeGetValue('p', 1);
// 1ページに表示するイベント数
$span = 12;
// 表示されるイベントの先頭(OFFSET後の数値)
$startNumber = (($currentPangeNum - 1) * $span);
// 作成したイベント情報を取得
$myCreatedData = getMyEventData($u_id, $span, $startNumber);
// debug('$myCreatedDataの中身→→→' . print_r($myCreatedData, true));
?>
<?php
$title = '作成したイベント　|　イベ探';
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
            $pageName = 'created';
            require('sidebar_mypage.php');
            ?>
            <!-- メイン -->
            <main class="main">
                <section class="sec-mypage -created">
                    <div class="mypage-title-wrap">
                        <h2 class="mypage-title -created">
                            作成したイベント
                        </h2>
                        <div class="mypage-number-display">
                            全 <?= $myCreatedData['total'] ?> 件中 <?php echo (!empty($myCreatedData['data'])) ? $startNumber + 1 : 0; ?> <?php if (!empty($myCreatedData['data']) && !empty($myCreatedData['data'][1])) echo ' - ' . ($startNumber + count($myCreatedData['data'])); ?> 件
                        </div>
                    </div>
                    <!-- <div class="selectbox -mypage">
                        <select name="sort" id="" class="select -mypage">
                            <option value="0">並び替え</option>
                            <option value="1">登録順（昇順）</option>
                            <option value="2">登録順（降順）</option>
                            <option value="3">開催日（昇順）</option>
                            <option value="4">開催日（降順）</option>
                        </select>
                    </div> -->
                    <div class="panel-list">
                        <?php foreach ($myCreatedData['data'] as $key => $val) {
                            require('panel.php');
                        } ?>
                    </div>
                </section>
                <?php if ((int) $myCreatedData['total_page'] !== 1 || !empty($myCreatedData['data'])) pagination($currentPangeNum, $myCreatedData['total_page']); ?>
            </main>
        </div>
        <?php
        require('footer.php');
        ?>