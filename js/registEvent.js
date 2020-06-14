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
