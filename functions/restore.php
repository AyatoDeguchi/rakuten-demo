<?php
include_once 'functions.php';
date_default_timezone_set('Asia/Tokyo');
$file_list = array();
if(!empty($_POST['user_dir']))
{
  // ディレクトリの存在チェック
  $user_dir = "../data/clean/{$_POST['user_dir']}";
  if(file_exists($user_dir))
  {
    /*
    echo $user_dir." = ディレクトリ確認 ";
    echo "  ";
    echo "POST['user_dir'] = ".$_POST['user_dir'];
    echo "  ";
    */

    $file_list = getDirFiles($user_dir);
    //print_r($file_list);
    if(count($file_list) == 0)
    {
      echo "no-file";
      return;
    }

    $last_file = $file_list[0];
    $last_mod = filemtime($last_file);

    // $file_list から最新のファイルを探す
    for($i=1; $i<count($file_list); $i++)
    {
      $mod = filemtime($file_list[$i]);
      if($last_mod < $mod)
      {
        $last_mod = $mod;
        $last_file = $file_list[$i];
      }
    }
    $restore_file = basename($last_file);


    if(copy($last_file, "../data/{$_POST['user_dir']}/{$restore_file}"))
    {
      //echo 'copy success';
      echo "data/{$_POST['user_dir']}/{$restore_file}";
      unlink($last_file);
    }
    else
    {
      //echo 'copy ng';
    }

    /*
    foreach ($file_list as $key => $value) {
      print_r($value);echo "  ";
      $new_file_name = @exif_read_data($value);
      print_r($new_file_name['FileDateTime']);echo "  ";

      if($new_file_name['FileDateTime'] != null)
      {
        // これはファイルの更新時間
        $new_file_name = $new_file_name['FileDateTime'];

        $img_path = $value;
        $img_path_tmp = pathinfo($img_path);
        $img_path_base = $img_path_tmp["basename"];
        if($key==0){
          $restore_time = $new_file_name;
          $restore_file = $img_path_base;
        }else{
          if($restore_time<$new_file_name){
            $restore_time = $new_file_name;
            $restore_file = $img_path_base;
          }
        }
      }
      else{
        echo "更新時刻を取得出来ませんでした。".$key;
        echo "  ";
        // return するようにする
      }
    }
    */



  }
  else
  {
      //echo "restore-ディレクトリ確認 不可";
      echo 0;
      return;
  }

}

?>
