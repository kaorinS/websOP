<?php
// 共通変数・関数を読み込む
require('function.php');
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
                                イベント名<br>
                                <input type="text" class="input-text -mypage-edit -regist" name="username">
                            </label>
                            <label class="label block-regist">
                                開催日<br>
                                <div class="wrap-input-date-mypage">
                                    <input type="date" class="input-date date-mypage-edit -regist" name="event_date">
                                </div>
                            </label>
                            <label class="label block-regist">
                                カテゴリー<br>
                                <div class="selectbox -mypage-edit -regist">
                                    <select name="category" class="select select-mypage-edit">
                                        <option value="0">選択してください</option>
                                        <optgroup label="季節のイベント">
                                            <option value="イルミネーション">イルミネーション</option>
                                            <option value="カウントダウン">カウントダウン</option>
                                            <option value="花・自然">花・自然</option>
                                            <option value="味覚狩り">味覚狩り</option>
                                            <option value="クリスマスイベント">クリスマスイベント</option>
                                            <option value="福袋・初売り">福袋・初売り</option>
                                        </optgroup>
                                        <optgroup label="祭り">
                                            <option value="祭り">祭り</option>
                                            <option value="市・縁日">市・縁日</option>
                                            <option value="フェスティバル">フェスティバル</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </label>
                            <label class="label block-regist">
                                開催地<br>
                                <div class="selectbox -mypage-edit -regist">
                                    <select class="select select-mypage-edit" name="pref">
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
                            </label>
                            <div class="block-regist">
                                会場形式<br>
                                <label>
                                    <input type="checkbox" class="checkbox" name="format" value="1"><span class="regist-checkbox-font">屋内</span>
                                </label>
                                <label>
                                    <input type="checkbox" class="checkbox" name="format" value="2"><span class="regist-checkbox-font">屋外</span>
                                </label>
                            </div>
                            <div class="block-regist">
                                参加対象<br>
                                <label><input type="checkbox" class="checkbox" name="target" value="1"><span class="regist-checkbox-font">子供</span></label>
                                <label><input type="checkbox" class="checkbox" name="target" value="2"><span class="regist-checkbox-font">大人</span></label>
                                <label><input type="checkbox" class="checkbox" name="target" value="3"><span class="regist-checkbox-font">高齢者</span></label>
                                <label><input type="checkbox" class="checkbox" name="target" value="4"><span class="regist-checkbox-font">男</span></label>
                                <label><input type="checkbox" class="checkbox" name="target" value="5"><span class="regist-checkbox-font">女</span></label>
                                <label><input type="checkbox" class="checkbox" name="target" value="6"><span class="regist-checkbox-font">その他</span></label>
                                <input type="text" class="input-text -mypage-edit -regist_target" name="target-other" placeholder="例：家族">
                            </div>
                            <div class="label block-regist">
                                参加費<br>
                                <label><input type="radio" class="radio" name="entry"><span class="regist-checkbox-font">無料</span></label>
                                <label><input type="radio" class="radio" name="entry"><span class="regist-checkbox-font">有料</span></label>
                                <label>
                                    <input type="text" class="input-text -mypage-edit -regist_target" name="entry-fee"><span class="regist-checkbox-font">円</span>
                                </label>
                            </div>
                            <label class="label block-regist">
                                詳細<br>
                                <textarea name="detail" id="js-count-regist" cols="50" rows="10" class="textarea-regist"></textarea>
                            </label>
                            <p class="regist-counter-text"><span id="js-counter-view-regist">0</span>/500文字</p>
                            <div class="block-regist">
                                画像
                                <div class="img_drop-container">
                                    <div class="area-img_drop -regist">
                                        ここに画像をドラッグ＆ドロップ
                                    </div>
                                    <div class="area-img_drop -regist">
                                        ここに画像をドラッグ＆ドロップ
                                    </div>
                                    <div class="area-img_drop -regist">
                                        ここに画像をドラッグ＆ドロップ
                                    </div>
                                </div>
                            </div>
                            <label class="label block-regist">
                                主催・問い合わせ・申込先<br>
                                <textarea name="sponcer" cols="50" rows="10" class="textarea-regist"></textarea>
                            </label>
                            <div class="submit-container-mypage-edit">
                                <input type="submit" class="submit submit-mypage-edit -regist" value="登録する">
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
        <?php
        require('footer.php');
        ?>