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

//Fileアップロードチェック
if (isset($_FILES['upfile']) && $_FILES['upfile']['error'] == 0) {
  // ファイルをアップロードしたときの処理
  //アップロードしたファイルの情報取得
  $uploadedFileName = $_FILES['upfile']['name']; //ファイル名の取得
  $tempPathName = $_FILES['upfile']['tmp_name']; //tmpフォルダの場所
  $fileDirectoryPath = 'upload/'; //uploadフォルダに保存

  //File名の変更
  $extension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);
  $uniqueName = date('YmdHis') . md5(session_id()) . "." . $extension;
  $fileNameToSave = $fileDirectoryPath . $uniqueName;

  // var_dump($fileNameToSave);
  // exit();

  // FileUpload開始
  $img = '';
  if (is_uploaded_file($tempPathName)) {
    if (move_uploaded_file($tempPathName, $fileNameToSave)) {
      chmod($fileNameToSave, 0644); // 権限の変更
    } else {
      exit('Error:アップロードできませんでした'); // 画像の保存に失敗
    }
  } else {
    exit('Error:画像がありません'); // tmpフォルダにデータがない
  }
  // FileUpload終了
} else {
  // ファイルをアップしていないときの処理
  $img = '画像が送信されていません';
}

//DB接続
$pdo = connectToDb();

//データ登録SQL作成
$sql = 'INSERT INTO php02_table(id, task, deadline, comment, image, indate) VALUES(NULL, :a1, :a2, :a3, :image, sysdate())';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':a1', $task, PDO::PARAM_STR);
$stmt->bindValue(':a2', $deadline, PDO::PARAM_STR);
$stmt->bindValue(':a3', $comment, PDO::PARAM_STR);
$stmt->bindValue(':image', $fileNameToSave, PDO::PARAM_STR);
$status = $stmt->execute();

//データ登録処理後
if ($status == false) {
  showSqlErrorMsg($stmt);
} else {
  //index.phpへリダイレクト
  header('Location: index.php');
}
