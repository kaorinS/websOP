<?php
// 共通変数・関数を読み込む
require('function.php');
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
                    <h2 class="mypage-edit-title">
                        プロフィール編集
                    </h2>
                    <div class="mypage-edit-body">
                        <form method="post" class="form-mypage-edit -prof">
                            <label class="label label-mypage-edit">
                                ユーザーネーム<br>
                                <input type="text" class="input-text -mypage-edit" name="username">
                            </label>
                            <label class="label label-mypage-edit">
                                都道府県<br>
                                <div class="selectbox -mypage-edit">
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
                            <label class="label label-mypage-edit">
                                年齢<br>
                                <div class="selectbox -mypage-edit">
                                    <select class="select select-mypage-edit" name="age">
                                        <option value="0">選択してください</option>
                                        <option value="-15">〜15歳</option>
                                        <option value="16-20">16歳〜20歳</option>
                                        <option value="21-25">21歳〜25歳</option>
                                        <option value="26-30">26歳〜30歳</option>
                                        <option value="31-35">31歳〜35歳</option>
                                        <option value="36-40">36歳〜40歳</option>
                                        <option value="41-45">41歳〜45歳</option>
                                        <option value="46-50">46歳〜50歳</option>
                                        <option value="51-55">51歳〜55歳</option>
                                        <option value="55-60">55歳〜60歳</option>
                                        <option value="61-65">61歳〜65歳</option>
                                        <option value="66-70">66歳〜70歳</option>
                                        <option value="71-">71歳〜</option>
                                    </select>
                                </div>
                            </label>
                            <label class="label label-mypage-edit">
                                メールアドレス<br>
                                <input type="text" class="input-text -mypage-edit" name="mail">
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