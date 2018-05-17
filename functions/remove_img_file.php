<?php

if(!empty($_POST['user_dir']))
{
  // ディレクトリの存在チェック
  $user_dir = "../data/clean/{$_POST['user_dir']}";
  $img_path = $_POST['img_path'];
  $img_path_tmp = pathinfo($img_path);
  $img_path_base = $img_path_tmp["basename"];
  if(file_exists($user_dir))
  {
    /*
    echo $user_dir." = ディレクトリ確認 ";
    echo "  ";
    echo "POST['user_dir'] = ".$_POST['user_dir'];
    echo "  ";
    echo "basename = ".$img_path_base;
    */
    if (rename("../{$img_path}", "../data/clean/{$_POST['user_dir']}/{$img_path_base}")) {
      //echo '  ゴミ箱に移動しました。';
      echo 1;
    } else {
      //echo '  移動できません';
      echo 0;
    }
  }
  else
  {
      //echo "remove-img-file:ディレクトリ確認 不可";
      echo 0;
      return;
  }

}

?>
