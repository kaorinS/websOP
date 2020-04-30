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
          <h2 class="title side-h2">地域</h2>
          <div class="side-group-wrap">
            <div class="side-group">
              <h3 class="title side-title">エリア</h3>
              <div class="selectbox -index">
                <select class="select side-border" name="area">
                  <option value="0">選択してください</option>
                  <option value="北海道">北海道</option>
                  <option value="東北">東北</option>
                  <option value="甲信越・北陸">甲信越・北陸</option>
                  <option value="関東">関東</option>
                  <option value="東海">東海</option>
                  <option value="関西">関西</option>
                  <option value="中国">中国</option>
                  <option value="四国">四国</option>
                  <option value="九州・沖縄">九州・沖縄</option>
                </select>
              </div>
            </div>
            <div class="side-group">
              <h3 class="title side-title">都道府県</h3>
              <div class="selectbox -index">
                <select class="select side-border" name="pref">
                  <option value="0">選択してください</option>
                  <optgroup label="北海道・東北">
                    <option value="北海道">北海道</option>
                    <option value="青森県">青森県</option>
                    <option value="秋田県">秋田県</option>
                    <option value="岩手県">岩手県</option>
                    <option value="山形県">山形県</option>
                    <option value="宮城県">宮城県</option>
                    <option value="福島県">福島県</option>
                  </optgroup>
                  <optgroup label="甲信越・北陸">
                    <option value="山梨県">山梨県</option>
                    <option value="長野県">長野県</option>
                    <option value="新潟県">新潟県</option>
                    <option value="富山県">富山県</option>
                    <option value="石川県">石川県</option>
                    <option value="福井県">福井県</option>
                  </optgroup>
                  <optgroup label="関東">
                    <option value="茨城県">茨城県</option>
                    <option value="栃木県">栃木県</option>
                    <option value="群馬県">群馬県</option>
                    <option value="埼玉県">埼玉県</option>
                    <option value="千葉県">千葉県</option>
                    <option value="東京都">東京都</option>
                    <option value="神奈川県">神奈川県</option>
                  </optgroup>
                  <optgroup label="東海">
                    <option value="愛知県">愛知県</option>
                    <option value="静岡県">静岡県</option>
                    <option value="岐阜県">岐阜県</option>
                    <option value="三重県">三重県</option>
                  </optgroup>
                  <optgroup label="関西">
                    <option value="大阪府">大阪府</option>
                    <option value="兵庫県">兵庫県</option>
                    <option value="京都府">京都府</option>
                    <option value="滋賀県">滋賀県</option>
                    <option value="奈良県">奈良県</option>
                    <option value="和歌山県">和歌山県</option>
                  </optgroup>
                  <optgroup label="中国">
                    <option value="岡山県">岡山県</option>
                    <option value="広島県">広島県</option>
                    <option value="鳥取県">鳥取県</option>
                    <option value="島根県">島根県</option>
                    <option value="山口県">山口県</option>
                  </optgroup>
                  <optgroup label="四国">
                    <option value="徳島県">徳島県</option>
                    <option value="香川県">香川県</option>
                    <option value="愛媛県">愛媛県</option>
                    <option value="高知県">高知県</option>
                  </optgroup>
                  <optgroup label="九州・沖縄">
                    <option value="福岡県">福岡県</option>
                    <option value="佐賀県">佐賀県</option>
                    <option value="長崎県">長崎県</option>
                    <option value="熊本県">熊本県</option>
                    <option value="大分県">大分県</option>
                    <option value="宮崎県">宮崎県</option>
                    <option value="鹿児島県">鹿児島県</option>
                    <option value="沖縄県">沖縄県</option>
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
                  <option value="0">選択してください</option>
                  <option value="1">1月</option>
                  <option value="2">2月</option>
                  <option value="3">3月</option>
                  <option value="4">4月</option>
                  <option value="5">5月</option>
                  <option value="6">6月</option>
                  <option value="7">7月</option>
                  <option value="8">8月</option>
                  <option value="9">9月</option>
                  <option value="10">10月</option>
                  <option value="11">11月</option>
                  <option value="12">12月</option>
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
            全45件中１〜30件
          </div>
        </div>
        <div class="panel-list">
          <a href="eventDetail.php" class="panel">
            <div class="panel-body">
              <img src="images/bread1.jpg" class="img -index">
              <p class="panel-pref kanto">神奈川県</p>
              <p class="panel-title">
                <span class="panel-date">2020年4月1日</span><br>
                親子パン教室
              </p>
            </div>
          </a>
          <a href="eventDetail.php" class="panel">
            <div class="panel-body">
              <img src="images/coffee1.jpg" class="img -index" alt="">
              <p class="panel-pref tokai">愛知県</p>
              <p class="panel-title">
                <span class="panel-date">2020年4月1日</span><br>
                コーヒー試飲会
              </p>
            </div>
          </a>
          <a href="eventDetail.php" class="panel">
            <div class="panel-body">
              <img src="images/pflower1.jpg" class="img -index" alt="">
              <p class="panel-pref kyusyu">鹿児島県</p>
              <p class="panel-title">
                <span class="panel-date">2020年4月1日</span><br>
                プリザーブドフラワーレッスン
              </p>
            </div>
          </a>
          <a href="eventDetail.php" class="panel">
            <div class="panel-body">
              <img src="images/yukata1.jpg" class="img -index" alt="">
              <p class="panel-pref kinki">滋賀県</p>
              <p class="panel-title">
                <span class="panel-date">2020年4月1日</span><br>
                浴衣着付けレッスン
              </p>
            </div>
          </a>
          <a href="eventDetail.php" class="panel">
            <div class="panel-body">
              <img src="images/bread1.jpg" class="img -index" alt="">
              <p class="panel-pref kinki">京都府</p>
              <p class="panel-title">
                <span class="panel-date">2020年4月1日</span><br>
                親子パン教室
              </p>
            </div>
          </a>
          <a href="eventDetail.php" class="panel">
            <div class="panel-body">
              <img src="images/coffee1.jpg" class="img -index" alt="">
              <p class="panel-pref hokkaido">北海道</p>
              <p class="panel-title">
                <span class="panel-date">2020年4月1日</span><br>
                コーヒー試飲会
              </p>
            </div>
          </a>
          <a href="eventDetail.php" class="panel">
            <div class="panel-body">
              <img src="images/pflower1.jpg" class="img -index" alt="">
              <p class="panel-pref tohoku">山形県</p>
              <p class="panel-title">
                <span class="panel-date">2020年4月1日</span><br>
                プリザーブドフラワーレッスン
              </p>
            </div>
          </a>
          <a href="eventDetail.php" class="panel">
            <div class="panel-body">
              <img src="images/yukata1.jpg" class="img -index" alt="">
              <p class="panel-pref shikoku">香川県</p>
              <p class="panel-title">
                <span class="panel-date">2020年4月1日</span><br>
                浴衣着付けレッスン
              </p>
            </div>
          </a>
          <a href="eventDetail.php" class="panel">
            <div class="panel-body">
              <img src="images/bread1.jpg" class="img -index" alt="">
              <p class="panel-pref koshinetsu-hokuriku">新潟県</p>
              <p class="panel-title">
                <span class="panel-date">2020年4月1日</span><br>
                親子パン教室
              </p>
            </div>
          </a>
          <a href="eventDetail.php" class="panel">
            <div class="panel-body">
              <img src="images/coffee1.jpg" class="img -index" alt="">
              <p class="panel-pref kanto">千葉県</p>
              <p class="panel-title">
                <span class="panel-date">2020年4月1日</span><br>
                コーヒー試飲会
              </p>
            </div>
          </a>
          <a href="eventDetail.php" class="panel">
            <div class="panel-body">
              <img src="images/pflower1.jpg" class="img -index" alt="">
              <p class="panel-pref kanto">東京都</p>
              <p class="panel-title">
                <span class="panel-date">2020年4月1日</span><br>
                プリザーブドフラワーレッスン
              </p>
            </div>
          </a>
          <a href="eventDetail.php" class="panel">
            <div class="panel-body">
              <img src="images/yukata1.jpg" class="img -index" alt="">
              <p class="panel-pref kinki">和歌山県</p>
              <p class="panel-title">
                <span class="panel-date">2020年4月1日</span><br>
                浴衣着付けレッスン
              </p>
            </div>
          </a>
        </div>
        <div class="pagination">
          <ul class="pagination-list">
            <li class="list-item"><a href="#" class="a-pagination">&lt;&lt;</a></li>
            <li class="list-item"><a href="#" class="a-pagination">3</a></li>
            <li class="list-item"><a href="#" class="a-pagination">4</a></li>
            <li class="list-item"><a href="#" class="a-pagination active">5</a></li>
            <li class="list-item"><a href="#" class="a-pagination">6</a></li>
            <li class="list-item"><a href="#" class="a-pagination">7</a></li>
            <li class="list-item"><a href="#" class="a-pagination">&gt;&gt;</a></li>
          </ul>
        </div>
      </main>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
    <!-- footer -->
    <?php
    require('footer.php');
    ?>