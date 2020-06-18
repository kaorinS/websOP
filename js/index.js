// ===== サイドバー追従 =====
// サイドバーの位置を取得
var index_sidebar_offset = $("#index-sidebar").offset().top;
// サイドバーのwidth(margin含む)を取得
var index_sidebar_width = $("#index-sidebar").outerWidth(true);
$(window).scroll(function () {
  // 現在のスクロール量を取得
  var my_offset = $(window).scrollTop();
  // 現在のスクロール量 > サイドバーの位置
  if (index_sidebar_offset < my_offset) {
    // js-fixedのクラス付与
    $("#index-sidebar").addClass("js-fixed");
    // メインにmargin-leftを付与
    $("#index-main").css("margin-left", index_sidebar_width);
  } else {
    // js-fixedのクラス除去
    $("#index-sidebar").removeClass("js-fixed");
    // メインのmargin-leftをautoに変更
    $("#index-main").css("margin-left", "auto");
  }
});

// ===== エリア選択or都道府県選択 =====
// エリアの選択をした場合
function AreaChange(value) {
  // 都道府県のセレクトボックス要素を取得
  const pref_select = document.getElementById("js-pref-select");
  // エリア選択が0以外の場合、都道府県セレクトボックスを未選択にする
  if (value !== 0) {
    pref_select.options[0].selected = true;
  }
}
// 都道府県の選択をした場合
function PrefChange(value) {
  // エリアのセレクトボックス要素を取得
  const area_select = document.getElementById("js-area-select");
  // 都道府県が選択された場合、エリアのセレクトボックスを未選択にする
  if (value !== 0) {
    area_select.options[0].selected = true;
  }
}
