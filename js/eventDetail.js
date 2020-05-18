// スクロール量を取得する関数
function getScrolled() {
  return window.pageYOffset !== undefined
    ? window.pageYOffset
    : document.documentElement.scrollTop;
}

// トップに戻るボタンの要素を取得
const topButton = document.getElementById("js-return-top");

// ボタンの表示・非表示
window.onscroll = function () {
  getScrolled() > 500
    ? topButton.classList.add("is-fadein")
    : topButton.classList.remove("is-fadein");
};

// トップに移動する関数
function scrollToTop() {
  let scrolled = getScrolled();
  window.scrollTo(0, Math.floor(scrolled / 3));
  if (scrolled < 0) {
    window.setTimeout(scrollToTop, 30);
  }
}

// イベント登録
topButton.onclick = function () {
  scrollToTop();
};
