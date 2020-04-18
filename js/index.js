// サイドバーの位置を取得
var index_sidebar_offset = $("#index-sidebar").offset().top;
// サイドバーのwidth(margin含む)を取得
var index_sidebar_width = $("#index-sidebar").outerWidth(true);
$(window).scroll(function() {
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
