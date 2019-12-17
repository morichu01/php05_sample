<?php
include('functions.php');

//DB接続
$pdo = connectToDb();

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

