<?php
  include_once "functions.php";


  date_default_timezone_set('Asia/Tokyo');
  $expire = strtotime("72 hours ago");


  echo $expire;

  $list = getDirFiles("../data/");
  print_r($list);

  foreach ($list as $value) {
    $mod = filemtime( $value );
    echo $value.$mod."<br />";
    if($mod < $expire){
      echo "--".realpath($value)."<br />";
      unlink(realpath($value));
    }
  }






?>
