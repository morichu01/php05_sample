<?php
include('functions.php');

// 入力チェック
if (
  !isset($_POST['task']) || $_POST['task'] == '' ||
  !isset($_POST['deadline']) || $_POST['deadline'] == ''
) {
  exit('ParamError');
}

//POSTデータ取得
$task = $_POST['task'];
$deadline = $_POST['deadline'];
$comment = $_POST['comment'];

//DB接続
$pdo = connectToDb();

$sql = 'INSERT INTO php02_table(id, task, deadline, comment, indate) VALUES(NULL, :a1, :a2, :a3, sysdate())';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':a1', $task, PDO::PARAM_STR);    //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a2', $deadline, PDO::PARAM_STR);   //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a3', $comment, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

if ($status == false) {
  showSqlErrorMsg($stmt);
}


//データ表示SQL作成
$sql = 'SELECT * FROM php02_table ORDER BY deadline DESC';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

//データ表示
if ($status == false) {
  showSqlErrorMsg($stmt);
} else {
  $res = [];
  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $res[] = $result; // 配列に追加する
  }
  echo json_encode($res); // json形式にして出力
}