<?php
// var_dump($_FILES);
// exit();

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
      $img = '<img src="' . $fileNameToSave . '" >'; // imgタグを設定
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

?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>FileUploadテスト</title>
</head>

<body>
  <?= $img ?>
</body>

</html>