<?php


/* ------------------------------
 * PHPの変数をjsで使えるようにエンコードする処理
 * ------------------------------*/
function json_safe_encode($data){
  echo json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

/*-----------------------------
 * ディレクトリのファイル一覧を取得する
 -----------------------------*/
function getDirFiles($dir)
{
  $files = glob(rtrim($dir, '/') . '/*');

  if($files == null)
  {
    return array();
  }

  $list = array();
  foreach ($files as $file)
  {
    if(is_file($file))
    {
      $file = mb_convert_encoding($file, 'UTF-8', 'SJIS'); // 日本語をエンコード
      $list[] = $file;
    }
    if(is_dir($file))
    {
        $list = array_merge($list, getDirFiles($file));
    }
  }

   return $list;
}


/*-----------------------------
 * ファイルのexif情報を取得する
 -----------------------------*/
function getFileTimeStamp($file_path)
{
  date_default_timezone_set('Asia/Tokyo');


  // Exifを取得し、[$exif]に代入する
  $exif = @exif_read_data($file_path);


  if($exif == null)
  {
    return time();
  }
  elseif($exif['DateTime'] != null)
  {
    return strtotime($exif['DateTime']);
  }
  elseif($eixf['FileDateTime'] != null)
  {
    //FileDateTime(Unixタイム)を取得
    return $exif['FileDateTime'];
  }


  return time();



  // iphoneで撮影したものは回転されて表示されることがある
  // Orientationで回転情報を取得できる
  // https://qiita.com/RichardImaokaJP/items/385beb77eb39243e50a6
}

?>
