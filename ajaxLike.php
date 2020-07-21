<?php
// 共通変数・関数ファイルを読み込み
require('function.php');

// デバッグ
debug('**********************************************');
debug('******************** Ajax *******************');
debug('**********************************************');
debugLogStart();

// ================================
// Ajax処理
// ================================

// POSTが有り、ユーザーIDが有り、ログインしている場合
if (isset($_POST['eventId']) && isset($_SESSION['user_id']) && isLogin()) {
    debug('***** POST送信有り *****');
    $e_id = $_POST['eventId'];
    debug('イベントID→→→' . $e_id);
    // 例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT * FROM favo WHERE f_id = :f_id AND u_id = :u_id';
        $data = array(':f_id' => $e_id, ':u_id' => $_SESSION['user_id']);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        debug('$stmtの中身→→→' . print_r($stmt, true));
        // カウント
        $resultCount = $stmt->rowCount();
        debug('$resultCountの中身→→→' . $resultCount);
        // レコードが1件でもある場合
        if (!empty($resultCount)) {
            // レコードの削除
            $sql = 'DELETE FROM favo WHERE f_id = :f_id AND u_id = :u_id';
            $data = array(':f_id' => $e_id, ':u_id' => $_SESSION['user_id']);
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
        } else {
            // レコード挿入
            $sql = 'INSERT INTO favo (f_id, u_id, created_at) VALUES (:f_id, :u_id, :date)';
            $data = array(':f_id' => $e_id, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
        }
    } catch (Exception $e) {
        error_log('!!!!! エラー発生 !!!!!') . $e->getMessage();
    }
}
debug('************* Ajax処理終了 *************');
