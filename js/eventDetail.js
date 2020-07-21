// スクロール量を取得する関数
function getScrolled() {
  return window.pageYOffset !== undefined
    ? window.pageYOffset
    : document.documentElement.scrollTop;
}

// ***** トップに戻る *****
// トップに戻るボタンの要素を取得
const topButton = document.getElementById("js-return-top");
// ボタンの表示・非表示
window.onscroll = function () {
  if (getScrolled() > 500) {
    topButton.classList.add("is-fadein");
  } else {
    topButton.classList.remove("is-fadein");
  }
};
// トップに移動
topButton.addEventListener(
  "click",
  function () {
    const me = arguments.callee;
    const nowY = window.pageYOffset;
    // 0.8は任意の値(0.8倍減少する)
    window.scrollTo(0, Math.floor(nowY * 0.8));
    if (nowY > 0) {
      window.setTimeout(me, 10);
    }
  },
  false
);

// ***** 画像切替 *****
// メイン画像・サブ画像の要素を取得
const mainImg = document.getElementById("js-switch-img-main");
const subImgs = document.getElementsByClassName("js-switch-img-sub");
// attr関数作成
const attr = function (node, name, value) {
  if (typeof value === "undefined") {
    return node.getAttribute(name);
  }
  node.setAttribute(name, value);
};
for (let i = 0; i < subImgs.length; i++) {
  subImgs[i].addEventListener(
    "click",
    function () {
      // クリックしたsubImgのsrcを取得
      const subImgsSrc = attr(this, "src");
      // 画像切り替え
      attr(mainImg, "src", subImgsSrc);
    },
    false
  );
}

// ***** お気に入り追加・削除
let $like, likeEventId;
$like = $(".js-click-like") || null;
likeEventId = $like.data("eventid") || null;

if (likeEventId !== undefined && likeEventId !== null) {
  $like.on("click", function () {
    const $this = $(this);
    $.ajax({
      type: "POST",
      url: "ajaxLike.php",
      data: { eventId: likeEventId },
    })
      .done(function (data) {
        console.log("Ajax Success");
        // クラス属性をtoggleで付け外し
        $this.toggleClass("active");
        btnText();
      })
      .fail(function (msg) {
        console.log("Ajax Error");
      });
  });
}

// ***** 「お気に入りに追加」「お気に入りから削除」の設定 *****
// buttonのクラス名にactiveがあるかどうか
function btnText() {
  let isActive = $("#js-like").hasClass("active");
  console.log("buttonのクラスにactiveがあるかどうか→→→", isActive);
  if (isActive) {
    $(".btn-text").text("お気に入りから削除");
  } else {
    $(".btn-text").text("お気に入りに追加");
  }
}
// 読み込み時のチェック
btnText();
