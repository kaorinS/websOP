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
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;
// 表示件数(今回は20)
$listSpan = 20;
// 現在ページの表示レコードの先頭を表示(◯件/●●件中 の◯部分)
$currentMinNum = (($currentPageNum - 1) * $listSpan);
// DBから商品データを取得
$dbEventData = getEventList($currentMinNum);
debug('$dbEventDataの中身→→→' . print_r($dbEventData, true));
// DBからカテゴリーデータを取得
$dbCategoryData = getCategoryData();
// ページネーション用
$link = appendGetParam(array('p'), true);
debug('$linkの中身→→→' . print_r($link, true));
// $_GET['p']が半角数字じゃない場合or数値がトータルページより大きい場合はTOPに遷移
if (!preg_match("/^[0-9]+$/", $currentPageNum) || $currentPageNum > $dbEventData['total_page']) {
  debug('!!!!! エラー発生 !!!!!');
  debug('!!!!! 指定ページに不正な値が入りました !!!!!');
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
    <!-- メインコンテンツ  -->
    <div class="main-container site-width">
      <!-- サイドバー -->
      <aside id="index-sidebar" class="side -index">
        <form method="get">
          <h2 class="title side-h2">地域<span class="supp">（選択できるのは片方のみ）</span></h2>
          <div class="side-group-wrap">
            <div class="side-group">
              <h3 class="title side-title">エリア</h3>
              <div class="selectbox -index">
                <select id="js-area-select" class="select side-border" name="area" onclick="AreaChange(this.value);">
                  <option value="0">未選択</option>
                  <option value="1">北海道・東北</option>
                  <option value="2">甲信越・北陸</option>
                  <option value="3">関東</option>
                  <option value="4">東海</option>
                  <option value="5">近畿</option>
                  <option value="6">中国</option>
                  <option value="7">四国</option>
                  <option value="8">九州・沖縄</option>
                </select>
              </div>
            </div>
            <div class="side-group">
              <h3 class="title side-title">都道府県</h3>
              <div class="selectbox -index">
                <select id="js-pref-select" class="select side-border" name="pref" onclick="PrefChange(this.value);">
                  <option value="0">未選択</option>
                  <optgroup label="北海道">
                    <option value="1">北海道</option>
                  </optgroup>
                  <optgroup label="東北">
                    <?php for ($i = 2; $i <= 7; $i++) : ?>
                      <option value="<?= $i ?>"><?= prefNameCalled($i - 1) ?></option>
                    <?php endfor; ?>
                  </optgroup>
                  <optgroup label="関東">
                    <?php for ($i = 8; $i <= 14; $i++) : ?>
                      <option value="<?= $i ?>"><?= prefNameCalled($i - 1) ?></option>
                    <?php endfor; ?>
                  </optgroup>
                  <optgroup label="中部">
                    <?php for ($i = 15; $i <= 23; $i++) : ?>
                      <option value="<?= $i ?>"><?= prefNameCalled($i - 1) ?></option>
                    <?php endfor; ?>
                  </optgroup>
                  <optgroup label="近畿">
                    <?php for ($i = 24; $i <= 30; $i++) : ?>
                      <option value="<?= $i ?>"><?= prefNameCalled($i - 1) ?></option>
                    <?php endfor; ?>
                  </optgroup>
                  <optgroup label="中国">
                    <?php for ($i = 31; $i <= 35; $i++) : ?>
                      <option value="<?= $i ?>"><?= prefNameCalled($i - 1) ?></option>
                    <?php endfor; ?>
                  </optgroup>
                  <optgroup label="四国">
                    <?php for ($i = 36; $i <= 39; $i++) : ?>
                      <option value="<?= $i ?>"><?= prefNameCalled($i - 1) ?></option>
                    <?php endfor; ?>
                  </optgroup>
                  <optgroup label="九州・沖縄">
                    <?php for ($i = 40; $i <= 47; $i++) : ?>
                      <option value="<?= $i ?>"><?= prefNameCalled($i - 1) ?></option>
                    <?php endfor; ?>
                  </optgroup>
                </select>
              </div>
            </div>
          </div>
          <h2 class="title side-h2">日付</h2>
          <div class="side-group-wrap">
            <div class="side-group">
              <h3 class="title side-title">開催月</h3>
              <div class="selectbox -index">
                <select class="select side-border" name="month">
                  <option value="0">未選択</option>
                  <?php for ($i = 1; $i <= 12; $i++) : ?>
                    <option value="<?= $i ?>"><?= $i ?>月</option>
                  <?php endfor; ?>
                </select>
              </div>
            </div>
            <div class="side-group">
              <h3 class="title side-title">期間</h3>
              <div class="side-input-date">
                <input type="date" class="input-date side-border" name="start">
              </div>
              <span class="span-sidebar">〜</span>
              <div class="side-input-date">
                <input type="date" class="input-date side-border" name="end">
              </div>
            </div>
          </div>
          <h2 class="title side-h2">会場形式</h2>
          <div class="side-group">
            <input type="checkbox" class="checkbox" name="format" value="1"><span class="sidebar-checkbox-font">屋内</span>
            <input type="checkbox" class="checkbox" name="format" value="2"><span class="sidebar-checkbox-font">屋外</span>
          </div>
          <input type="submit" class="submit sidebar-submit side-border" value="検索">
        </form>
      </aside>
      <!-- メイン -->
      <main id="index-main" class="main">
        <div class="search-title">
          <div class="search-left">
            <h2 class="main-title">イベント一覧</h2>
          </div>
          <div class="search-right">
            全<span class="total-num"><?= sanitize($dbEventData['total']) ?></span>件中 <span class="num"><?php echo (!empty($dbEventData['data'])) ? $currentMinNum + 1 : 0; ?></span> - <span class="num"><?php echo $currentMinNum + count($dbEventData['data']); ?></span> 件
          </div>
        </div>
        <div class="panel-list">
          <?php foreach ($dbEventData['data'] as $key => $val) : ?>
            <a href="eventDetail.php?p_id=<?= $val['id'] ?>" class="panel">
              <div class="panel-body">
                <img src="<?= sanitize($val['pic1']) ?>" class="img -index">
                <p class="panel-pref <?= areaClassCalled($val['area']) ?>"><?= areaNameCalled($val['area']) ?></p>
                <p class="panel-title">
                  <span class="panel-date"><?= date("Y年n月j日", strtotime($val['date_start'])) ?><?php if ($val['date_start'] !== $val['date_end']) '〜' . date("n月j日", strtotime($val['date_end'])) ?></span><br>
                  <?= $val['name'] ?>
                </p>
              </div>
            </a>
          <?php endforeach; ?>
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