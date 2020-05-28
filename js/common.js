// サクセスメッセージの表示
// サクセスメッセージの要素を取得
const jsShowMsg = document.getElementById("js-show-msg");
// サクセスメッセージ内のテキストを取得
const msg = jsShowMsg.textContent;
// フェードアウトさせる
function jsShowMsgFadeout() {
  jsShowMsg.classList.remove("is-show");
}
//取得したテキストからスペースを除去し、文字が残っていた場合、処理を行う
if (msg.replace(/\s+/g, "").length) {
  jsShowMsg.classList.add("is-show");
  setTimeout(jsShowMsgFadeout, 3000);
}
