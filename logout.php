<?php
require('function.php');

// デバッグ
debug('**********************************************');
debug('****************** ログアウト ******************');
debug('**********************************************');
debugLogStart();

debug('***** ログアウト実行 *****');
// セッション削除
session_destroy();
debug('TOP画面へ遷移');
// TOP画面へ
header("Location:index.php");
exit;
