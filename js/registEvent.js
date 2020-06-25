// ***** 文字カウント *****
function countLength(str, field, maxNumber, field2) {
  const length = str.length,
    max = maxNumber,
    attention = document.getElementById(field2);
  document.getElementById(field).innerHTML = length;
  attention.style.display = length > max ? "block" : "none";
}

// ***** ラジオボタンにチェックを入れた時に入力項目を表示 *****
function payChange() {
  // ラジオボタンの要素を取得
  const radio = document.getElementsByName("entry");
  // テキストエリアの要素を取得
  const textarea = document.getElementById("js-text-pay");
  if (radio[0].checked) {
    textarea.classList.add("no-display");
  } else if (radio[1].checked) {
    textarea.classList.remove("no-display");
  }
}

// 都道府県選択時、住所に都道府県を自動記入
function changePref(obj) {
  // 選択された値を取得
  const option = obj.selectedIndex;
  // 都道府県を配列へ
  const pref = [
    "北海道",
    "青森県",
    "岩手県",
    "宮城県",
    "秋田県",
    "山形県",
    "福島県",
    "茨城県",
    "栃木県",
    "群馬県",
    "埼玉県",
    "千葉県",
    "東京都",
    "神奈川県",
    "新潟県",
    "富山県",
    "石川県",
    "福井県",
    "山梨県",
    "長野県",
    "岐阜県",
    "静岡県",
    "愛知県",
    "三重県",
    "滋賀県",
    "京都府",
    "大阪府",
    "兵庫県",
    "奈良県",
    "和歌山県",
    "鳥取県",
    "島根県",
    "岡山県",
    "広島県",
    "山口県",
    "徳島県",
    "香川県",
    "愛媛県",
    "高知県",
    "福岡県",
    "佐賀県",
    "長崎県",
    "熊本県",
    "大分県",
    "宮崎県",
    "鹿児島県",
    "沖縄県",
  ];
  // 選択された値を都道府県に置き換える
  const changeOpt = pref[option - 1];
  // 住所のinput要素を取得
  const inputAddr = document.getElementById("js-addr");
  const attr = function (node, name, value) {
    if (typeof value === "undefined") {
      return node.getAttribute(name);
    }
    node.setAttribute(name, value);
  };
  if (option !== 0) {
    attr(inputAddr, "value", changeOpt);
  }
}

// 画像ライブプレビュー
var $dropArea = $(".img-drop");
var $fileInput = $(".input-file");
$dropArea.on("dragover", function (e) {
  e.stopPropagation();
  e.preventDefault();
  $(this).css({ "background-color": "#333", color: "#fff" });
});
$dropArea.on("dragleave", function (e) {
  e.stopPropagation();
  e.preventDefault();
  $(this).css({
    "background-color": "transparent",
    color: "#666",
    border: "1px dotted #aaa",
  });
});
$fileInput.on("change", function (e) {
  $(this).parent(".img-drop").css({
    "background-color": "transparent",
    color: "#666",
  });
  var file = this.files[0], //files配列にファイルが入ってる。0なので、一番最初のもの。
    // JQueryのsiblingsメソッドで兄弟のimgを取得
    $img = $(this).siblings(".prev-img"),
    // ファイルを読み込むFileReaderオブジェクト
    fileReader = new FileReader();

  // 読込が完了した際のイベントハンドラ。imgのsrcにデータをセット
  fileReader.onload = function (event) {
    // 読み込んだデータをimgに設定
    $img.attr("src", event.target.result).show();
  };

  // 画像読み込み
  fileReader.readAsDataURL(file);
});
