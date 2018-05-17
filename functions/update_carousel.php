<?php
  /*-----------------------------
   * カルーセル表示の更新
   -----------------------------*/
  require_once 'functions.php';


  $user_dir = $_POST['user_dir'];
  $file_list = getDirFiles("../".$user_dir);



  $tag = "<ol class='carousel-indicators'>";

  $option = "";
  for($i=0; $i<count($file_list); $i++)
  {
    if($i==0)
    {
      $option = " class='active'";
    }
    else
    {
      $option = "";
    }

    $tag .= "<li data-target='#carouselExampleIndicators' data-slide-to='{$i}'{$option}></li>";
  }

  $tag .= "</ol>
     <div class='carousel-inner' role='listbox'>";


  $option = "";
  for($i=0; $i<count($file_list); $i++)
  {
    if($i==0)
    {
      $option = " active";
    }
    else
    {
      $option = "";
    }

    $img_file_name = basename($file_list[$i]);
    $tag .= "<div class='carousel-item{$option}'><figure class=''><img class='d-block img-fluid' src='{$user_dir}/{$img_file_name}'></figure></div>";
  }


  $tag .= "</div>
     <a class='carousel-control-prev' href='#carouselExampleIndicators' role='button' data-slide='prev'>
       <span class='carousel-control-prev-icon' aria-hidden='true'></span>
       <span class='sr-only'>Previous</span>
     </a>
     <a class='carousel-control-next' href='#carouselExampleIndicators' role='button' data-slide='next'>
       <span class='carousel-control-next-icon' aria-hidden='true'></span>
       <span class='sr-only'>Next</span>
     </a>";


  echo $tag;


?>
