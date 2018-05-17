<?php

  /*-----------------------------
   * ここにアップロードするファイルを所定のディレクトリに保存する処理を書く
   -----------------------------*/
  date_default_timezone_set('Asia/Tokyo');


  $uploaded_cnt = 0; // アップロードに成功した枚数


  for ($i=0; $i<count($_FILES['userfile']['name']); $i++)
  {
    $file_ext = pathinfo($_FILES["userfile"]["name"][$i], PATHINFO_EXTENSION);
    if(/*FileExtensionGetAllowUpload($file_ext) && */ is_uploaded_file($_FILES["userfile"]["tmp_name"][$i]))
    {
      if(move_uploaded_file($_FILES["userfile"]["tmp_name"][$i], "../data/{$_POST['user_dir']}/_tmp_img.jpg"))
      {

        // 撮影時間を取得して、保存名とする処理
        $new_file_name = @exif_read_data("../data/{$_POST['user_dir']}/_tmp_img.jpg");
        if($new_file_name['DateTime'] != null)
        {
          // これが撮影時間
          $new_file_name = strtotime($new_file_name['DateTime']);
        }
        elseif($new_file_name['FileDateTime'] != null)
        {
          // これはファイルの更新時間
          $new_file_name = $new_file_name['FileDateTime'];
        }
        else
        {
          $new_file_name = time();
        }



        // 同じファイル名があった時の処理
        $new_file_path = "../data/{$_POST['user_dir']}/{$new_file_name}.jpg";
        $index = 1;
        while(file_exists($new_file_path))
        {
          $new_file_path = "../data/{$_POST['user_dir']}/{$new_file_name}_{$index}.jpg";
          $index++;
        }
        // ファイルをリネームする
        $rename_result = rename("../data/{$_POST['user_dir']}/_tmp_img.jpg", $new_file_path);
        if($rename_result == true)
        {
          $uploaded_cnt++;
        }
      }
      else
      {
        //echo "ファイルをアップロードできません。<br>";
      }
    }
    else
    {
      //echo "ファイルが選択されていません。<br>";
    }
  }


  echo $uploaded_cnt;
 //header('Location: ../index.php'); // これだとtokenの情報がない
 ?>
