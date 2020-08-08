<?php
require('function.php');

// デバッグ
debug('**********************************************');
debug('*********** インデックス（TOPページ） **********');
debug('**********************************************');
debugLogStart();

// ================================
// 画面処理
// ================================
// GETパラメータ取得
debug('$_GETの中身→→→' . print_r($_GET, true));
// 現在ページのGETパラメータを取得(デフォルトは1)
$currentPageNum = takeGetValue('p', 1);
// カテゴリー
$category = takeGetValue('cat');
// エリア
$area = takeGetValue('area');
// 都道府県
$pref = takeGetValue('pref');
// 日時
$start = takeGetValue('start');
$end = takeGetValue('end');
// 会場形式
$format = (isset($_GET['format']) && is_array($_GET['format'])) ? implode(",", $_GET['format']) : '';
// ソート
$sort = (int) takeGetValue('sort', 1);
// 表示件数(今回は20)
$listSpan = 20;
// 現在ページの表示レコードの先頭を表示(◯件/●●件中 の◯部分)
$currentMinNum = (($currentPageNum - 1) * $listSpan);
// DBからイベントデータを取得
$dbEventData = getEventList($currentMinNum, $category, $area, $pref, $start, $end, $format, $sort, $listSpan);
// debug('$dbEventData(商品データ）の中身→→→' . print_r($dbEventData, true));
debug('イベント総数($dbEventData[total])→→→' . $dbEventData['total']);
debug('ページ総数($dbEventData[total_page])→→→' . $dbEventData['total_page']) . 'ページ';
// DBからカテゴリーデータを取得
$dbCategoryData = getCategoryData();
// ページネーション用
$link = appendGetParam(array('p'), true);
debug('$linkの中身→→→' . print_r($link, true));
// $_GET['p']が半角数字じゃない場合or数値がトータルページより大きい場合はTOPに遷移
if (!preg_match("/^[0-9]+$/", $currentPageNum)) {
  debug('!!!!! エラー発生 !!!!!');
  debug('!!!!! 指定ページに不正な値が入りました !!!!!');
  debug('!!!!! インデックスへ遷移 !!!!!');
  header("Location:index.php");
  exit;
} elseif ((int) $dbEventData['total_page'] !== 0 && $currentPageNum > (int) $dbEventData['total_page']) {
  debug('!!!!! エラー発生 !!!!!');
  debug('!!!!! 指定ページに不正な値が入りました !!!!!');
  debug('!!!!! インデックスへ遷移 !!!!!');
  header("Location:index.php");
  exit;
}
?>
<?php
$title = "イベ探！";
require('head.php');
?>

<body class="page-index page-2colum">
  <div class="wrapper">
    <!-- header -->
    <?php
    require('header.php');
    ?>
    <!----- メインコンテンツ  ----->
    <div class="main-container site-width">
      <!----- サイドバー ----->
      <aside id="index-sidebar" class="side -index">
        <form id="js-index-form" method="get">
          <div class="side-container -index">
            <h2 class="title side-h2">カテゴリー</h2>
            <div class="side-group">
              <div class="selectbox -index">
                <select name="cat" class="select side-border">
                  <option value="0" <?php if (getFormData('cat', true) == 0) echo 'selected'; ?>>未選択</option>
                  <?php foreach ($dbCategoryData as $key => $val) : ?>
                    <option value="<?= $val['id'] ?>" <?php if (getFormData('cat', true) == $val['id']) echo 'selected'; ?>><?= $val['name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="side-container -index">
            <h2 class="title side-h2">地域<span class="supp">（選択できるのは片方のみ）</span></h2>
            <div class="side-group-wrap">
              <div class="side-group">
                <h3 class="title side-title">エリア</h3>
                <div class="selectbox -index">
                  <select id="js-area-select" class="select side-border" name="area" onclick="areaChange(this.value);">
                    <option value="0" <?php if (getFormData('area', true) === 0) echo 'selected'; ?>>未選択</option>
                    <option value="1" <?php if ((int) getFormData('area', true) === 1) echo 'selected'; ?>>北海道</option>
                    <option value="2" <?php if ((int) getFormData('area', true) === 2) echo 'selected'; ?>>東北</option>
                    <option value="3" <?php if ((int) getFormData('area', true) === 3) echo 'selected'; ?>>関東</option>
                    <option value="4" <?php if ((int) getFormData('area', true) === 4) echo 'selected'; ?>>中部</option>
                    <option value="5" <?php if ((int) getFormData('area', true) === 5) echo 'selected'; ?>>近畿</option>
                    <option value="6" <?php if ((int) getFormData('area', true) === 6) echo 'selected'; ?>>中国</option>
                    <option value="7" <?php if ((int) getFormData('area', true) === 7) echo 'selected'; ?>>四国</option>
                    <option value="8" <?php if ((int) getFormData('area', true) === 8) echo 'selected'; ?>>九州・沖縄</option>
                  </select>
                </div>
              </div>
              <div class="side-group">
                <h3 class="title side-title">都道府県</h3>
                <div class="selectbox -index">
                  <select id="js-pref-select" class="select side-border" name="pref" onclick="prefChange(this.value);">
                    <option value="0" <?php if (getFormData('pref', true) === 0) echo 'selected'; ?>>未選択</option>
                    <optgroup label="北海道">
                      <option value="1" <?php if ((int) getFormData('pref', true) === 1) echo 'selected'; ?>>北海道</option>
                    </optgroup>
                    <optgroup label="東北">
                      <?php for ($i = 2; $i <= 7; $i++) : ?>
                        <option value="<?= $i ?>" <?php if ((int) getFormData('pref', true) === $i) echo 'selected'; ?>><?= prefNameCalled($i) ?></option>
                      <?php endfor; ?>
                    </optgroup>
                    <optgroup label="関東">
                      <?php for ($i = 8; $i <= 14; $i++) : ?>
                        <option value="<?= $i ?>" <?php if ((int) getFormData('pref', true) === $i) echo 'selected'; ?>><?= prefNameCalled($i) ?></option>
                      <?php endfor; ?>
                    </optgroup>
                    <optgroup label="中部">
                      <?php for ($i = 15; $i <= 23; $i++) : ?>
                        <option value="<?= $i ?>" <?php if ((int) getFormData('pref', true) === $i) echo 'selected'; ?>><?= prefNameCalled($i) ?></option>
                      <?php endfor; ?>
                    </optgroup>
                    <optgroup label="近畿">
                      <?php for ($i = 24; $i <= 30; $i++) : ?>
                        <option value="<?= $i ?>" <?php if ((int) getFormData('pref', true) === $i) echo 'selected'; ?>><?= prefNameCalled($i) ?></option>
                      <?php endfor; ?>
                    </optgroup>
                    <optgroup label="中国">
                      <?php for ($i = 31; $i <= 35; $i++) : ?>
                        <option value="<?= $i ?>" <?php if ((int) getFormData('pref', true) === $i) echo 'selected'; ?>><?= prefNameCalled($i) ?></option>
                      <?php endfor; ?>
                    </optgroup>
                    <optgroup label="四国">
                      <?php for ($i = 36; $i <= 39; $i++) : ?>
                        <option value="<?= $i ?>" <?php if ((int) getFormData('pref', true) === $i) echo 'selected'; ?>><?= prefNameCalled($i) ?></option>
                      <?php endfor; ?>
                    </optgroup>
                    <optgroup label="九州・沖縄">
                      <?php for ($i = 40; $i <= 47; $i++) : ?>
                        <option value="<?= $i ?>" <?php if ((int) getFormData('pref', true) === $i) echo 'selected'; ?>><?= prefNameCalled($i) ?></option>
                      <?php endfor; ?>
                    </optgroup>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="side-container -index">
            <h2 class="title side-h2">期間</h2>
            <div class="side-group-wrap">
              <div class="side-group">
                <div class="side-input-date">
                  <input type="date" class="input-date side-border" name="start" value="<?= getFormData('start', true) ?>">
                </div>
                <span class="span-sidebar">〜</span>
                <div class="side-input-date">
                  <input type="date" class="input-date side-border" name="end" value="<?= getFormData('end', true) ?>">
                </div>
              </div>
            </div>
          </div>
          <div class="side-container -index">
            <h2 class="title side-h2">会場形式</h2>
            <div class="side-group">
              <input type="checkbox" class="checkbox" name="format[]" value="1" <?php if (!empty($_GET['format']) && (int) $_GET['format'][0] === 1) echo 'checked'; ?>><span class="sidebar-checkbox-font">屋内</span>
              <input type="checkbox" class="checkbox" name="format[]" value="2" <?php if (!empty($_GET['format']) && (int) $_GET['format'][0] === 2) {
                                                                                  echo 'checked';
                                                                                } elseif (!empty($_GET['format'][1])) {
                                                                                  echo 'checked';
                                                                                } ?>><span class="sidebar-checkbox-font">屋外</span>
            </div>
          </div>
          <input type="submit" class="submit sidebar-submit side-border" value="検索">
        </form>
      </aside>
      <!----- メイン ----->
      <main id="index-main" class="main">
        <div class="search-title">
          <div class="search-left">
            <h2 class="main-title">イベント一覧</h2>
          </div>
          <!-- 表示件数 -->
          <div class="search-right">
            全<span class="total-num"><?= sanitize($dbEventData['total']) ?></span>件中 <span class="num"><?php echo (!empty($dbEventData['data'])) ? $currentMinNum + 1 : 0; ?></span> - <span class="num"><?php echo $currentMinNum + count($dbEventData['data']); ?></span> 件
          </div>
        </div>
        <!-- ソート -->
        <div class="sort-list">
          <ul class="sort-ul">
            <li class="sort-li" style="<?php if ($sort === 1) echo 'font-weight: bold;'; ?>"><a href="index.php<?= appendGetParam(array('sort')) ?><?php if (empty($_GET) || !empty($_GET['sort'])) {
                                                                                                                                                      echo '?';
                                                                                                                                                    } else {
                                                                                                                                                      echo '&';
                                                                                                                                                    } ?>sort=1">新着順</a></li>
            <li class="sort-li" style="<?php if ($sort === 2) echo 'font-weight: bold;'; ?>"><a href="index.php<?= appendGetParam(array('sort')) ?><?php if (empty($_GET) || !empty($_GET['sort'])) {
                                                                                                                                                      echo '?';
                                                                                                                                                    } else {
                                                                                                                                                      echo '&';
                                                                                                                                                    } ?>sort=2">登録順</a></li>
            <li class="sort-li" style="<?php if ($sort === 3) echo 'font-weight: bold;'; ?>"><a href="index.php<?= appendGetParam(array('sort')) ?><?php if (empty($_GET) || !empty($_GET['sort'])) {
                                                                                                                                                      echo '?';
                                                                                                                                                    } else {
                                                                                                                                                      echo '&';
                                                                                                                                                    } ?>sort=3">開催日順</a></li>
            <li class="sort-li" style="<?php if ($sort === 4) echo 'font-weight: bold;'; ?>"><a href="index.php<?= appendGetParam(array('sort')) ?><?php if (empty($_GET) || !empty($_GET['sort'])) {
                                                                                                                                                      echo '?';
                                                                                                                                                    } else {
                                                                                                                                                      echo '&';
                                                                                                                                                    } ?>sort=4">終了日が近い順</a></li>
          </ul>
        </div>
        <!-- イベント一覧 -->
        <div class="panel-list">
          <?php foreach ($dbEventData['data'] as $key => $val) {
            require('panel.php');
          } ?>
        </div>
        <?php pagination($currentPageNum, $dbEventData['total_page']); ?>
      </main>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
    <!-- footer -->
    <?php
    require('footer.php');
    ?>