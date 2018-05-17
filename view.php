<?php
  include_once 'functions/functions.php';


  $file_list = array();
  $trush_cnt = 0;
  $user_dir2 = $_POST['user_dir'];

  if(!empty($_POST['user_dir']))
  {
    // ディレクトリの存在チェック
    $user_dir = "data/{$_POST['user_dir']}";


    if(file_exists($user_dir))
    {
      $file_list = getDirFiles($user_dir);

      $trush_list = getDirFiles("data/clean/{$_POST['user_dir']}");
      $trush_cnt = count($trush_list);

      // ここで撮影時間ごとに並べ替えの処理を書く
        /*
      foreach ($file_list as $f)
      {
        $exif = getFileTimeStamp($f);
        print_r($exif);
        echo "<br>===============<br>";

      }
        */
    }
    else
    {
        return;
    }

  }

 ?>



 <!DOCTYPE html>
 <html lang="ja">
   <head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <meta name="apple-mobile-web-app-capable" content="yes">
     <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
     <link rel="shortcut icon" href="img/favicon.ico" />

     <title>Photo Sharing !</title>

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
     <link rel='stylesheet' href='css/sticky-footer.css'/>
     <link rel="stylesheet" href="css/toggle-style.css">
     <link rel='stylesheet' href='css/style.css'/>
     <link rel="stylesheet" href="https://cssgram-cssgram.netdna-ssl.com/cssgram.min.css">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

     <?php
       if($_GET['style'] == "girl")
       {
         echo "<link rel='stylesheet' href='css/girl.css'/>";
       }
       elseif($_GET['style'] == "girl-jp")
       {
         echo "<link rel='stylesheet' href='css/girl-jp.css'/>";
       }
       elseif($_GET['style'] == "boy")
       {
         echo "<link rel='stylesheet' href='css/boy.css'/>";
       }
       else
       {
         echo "<link rel='stylesheet' href='css/girl.css'/>";
       }
     ?>

   </head>
   <body>

     <div class="container">
     <?php
        // 写真の一覧
        /*
        print_r($file_list);
        foreach ($file_list as $f)
        {
          echo "<img src='{$f}' width='300' class='rounded'>";
        }
        */

        if(count($file_list) == 0)
        {
          echo "写真がありません";
        }
     ?>

     <!-- // 写真をスライドショーで表示  -->
     <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
       <ol class="carousel-indicators">
         <?php
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

              echo "<li data-target='#carouselExampleIndicators' data-slide-to='{$i}'{$option}></li>";
            }
         ?>
       </ol>
       <div class="carousel-inner" role="listbox">
         <?php
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

              echo "<div class='carousel-item{$option}'><figure class=''><img class='d-block img-fluid' src='{$file_list[$i]}'></figure></div>";

            }
         ?>
       </div>
       <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
         <span class="sr-only">Previous</span>
       </a>
       <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
         <span class="carousel-control-next-icon" aria-hidden="true"></span>
         <span class="sr-only">Next</span>
       </a>
     </div>



    <footer class="footer">
      <div style="position:absolute; bottom: 0px; left: -6px;">
        <button id="playBtn" type="button" class="btn btn-light btn-sm" onClick="play()"><i class="fa fa-pause" aria-hidden="true"></i> 停止</button>
      </div>
      <div class="float-right">
        <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#configModal"><i class="fa fa-cog" aria-hidden="true"></i> 設定</button>
        <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#configPhotoModal"><i class="fa fa-list-ul" aria-hidden="true"></i> リスト</button>
      </div>
    </footer>




    <!-- 設定モーダル -->
    <div id="configModal" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-80vh">
          <div class="modal-header">
            <h5 class="modal-title">Setting</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body modal-body-100vh">
            <!--
            <h6>スライドショーの自動再生とか速さの設定?</h6>
            <hr>
            -->

            <div class="alert alert-light config-header">Photo Filter
              <span class="float-right config-header-toggle">
                <input id="photo-filter-toggle" name="photo-filter" class="cmn-toggle cmn-toggle-round" type="checkbox">
                <label for="photo-filter-toggle"></label>
              </span>
            </div>




            <div id="filter-container">
              <ul id="filter-list">
                <!--
                <li class="filter-item">
                  <div class="card">
                    <figure><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">nofilter</h4>
                      <p class="card-text"><button type="button" class="btn btn-outline-secondary" onClick="changePhotoFilter('nofilter')">Select</button></p>
                    </div>
                  </div>
                </li>
                -->
                <li class="filter-item">
                  <div class="card">
                    <figure class="_1997"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">1977</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('_1997')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="aden"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Aden</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('aden')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="brannan"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Brannan</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('brannan')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="brooklyn"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Brooklyn</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('brooklyn')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="clarendon"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Clarendon</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('clarendon')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="earlybird"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Earlybird</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('earlybird')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="gingham"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Gingham</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('gingham')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="hudson"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Hudson</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('hudson')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="inkwell"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Inkwell</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('inkwell')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="kelvin"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Kelvin</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('kelvin')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="lark"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Lark</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('lark')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="lofi"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Lo-Fi</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('lofi')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="maven"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Maven</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('maven')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="mayfair"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Mayfair</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('mayfair')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="moon"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Moon</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('moon')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="nashville"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Nashville</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('nashville')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="perpetua"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Perpetua</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('perpetua')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="reyes"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Reyes</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('reyes')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="rise"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Rise</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('rise')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <figure class="slumber"><img class="card-img-top" src="<?php echo $file_list[0]; ?>"></figure>
                    <div class="card-body">
                      <h4 class="card-title">Slumber</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm photo-filter-btn" onClick="changePhotoFilter('slumber')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
              </ul>
            </div>


            <hr>
            <div class="alert alert-light config-header">BGM
            <span class="float-right config-header-toggle">
              <input id="bgm-toggle" name="bgm-btn" class="cmn-toggle cmn-toggle-round" type="checkbox">
              <label for="bgm-toggle"></label>
            </span>
            </div>

            <div id="filter-container">
              <ul id="filter-list">
                <!--
                <li class="filter-item">
                  <div class="card">
                    <span></span>
                    <div class="card-body">
                      <h4 class="card-title">no BGM</h4>
                      <p class="card-text"><button type="button" class="btn btn-outline-secondary" onClick="changeBGM('no_BGM')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
              -->
                <li class="filter-item">
                  <div class="card">
                    <span></span>
                    <div class="card-body">
                      <h4 class="card-title">BGM1</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm bgm-select-btn" onClick="changeBGM('BGM1')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <span></span>
                    <div class="card-body">
                      <h4 class="card-title">BGM2</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm bgm-select-btn" onClick="changeBGM('BGM2')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
                <li class="filter-item">
                  <div class="card">
                    <span></span>
                    <div class="card-body">
                      <h4 class="card-title">BGM3</h4>
                      <p class="card-text"><button type="button" class="btn btn-secondary btn-sm bgm-select-btn" onClick="changeBGM('BGM3')" disabled>Select</button></p>
                    </div>
                  </div>
                </li>
              </ul>
            </div>

          </div>
          <!--
          <div class="modal-footer">
            <button type="button" class="btn btn-primary">Save changes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        -->
        </div>
      </div>
    </div><!-- end:設定モーダル -->


    <!-- 写真リストモーダル -->
    <div id="configPhotoModal" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-80vh">
          <div class="modal-header">
            <h5 class="modal-title">Photo List</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body modal-body-100vh">
            <div class="alert alert-light" style="text-align:right; padding: 0;">
              <button id="undo-img-btn" class="btn btn-outline-secondary" type="button" onClick="undo_img('<?php echo "{$user_dir2}" ?>')"><i class="fa fa-undo" aria-hidden="true"></i> <i class="fa fa-trash" aria-hidden="true"></i><span id="trush-cnt">(<?php echo $trush_cnt;?>)</span></button>
            </div>

            <div id="list-wrap">
            <?php

             $cnt = 0;
             if(count($file_list) == 0)
             {
               echo "写真がありません";
             }


             // リストの表示
             echo "<ul class='list-group'>";

             foreach ($file_list as $value)
             {
               $path_data = pathinfo($value);
               $file_name = $path_data["filename"];
               if(strlen($file_name) > 10)
               {
                 $file_name = substr($file_name, 0, 10);
               }

               //$time = date('Y/m/d H:i:s', $path_data["filename"]);
               $time = date('Y/m/d H:i:s', $file_name);
echo <<< EOM
                <li id="list{$cnt}" class='list-group-item d-flex justify-content-between align-items-center'>
                  <div class="photo-item-time badge badge-dark">{$time}</div>
                  <img src="{$value}" class='photo-list-item'>
                  <span class='btn btn-outline-danger btn-sm' onClick="delete_img('{$value}','{$user_dir2}', 'list{$cnt}');">DELETE</span>
                </li>
EOM;
               $cnt++;
             }
             echo "</ul>";
            ?>
          </div>
        </div>
      </div>
      </div>
    </div><!-- end:写真リストモーダル -->


    <!-- 削除確認 -->
    <!--
    <div id="confirmPhotoModal" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <p>削除しますか?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" onClick="deleteImgConfirm();">Delete</button>
            <button type="button" class="btn btn-secondary" onClick="deleteImgCancel();">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    -->
    <!-- end:削除確認 -->



    </div><!-- end: container -->


     <!-- Optional JavaScript -->
     <!-- jQuery first, then Popper.js, then Bootstrap JS -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>


     <script>
     var CAROUSEL_TIME = 600000;

     $('.carousel').carousel({
       interval: CAROUSEL_TIME
     })




     /* ------------------------------
      * スライドショーの写真を更新する処理
      * ------------------------------*/
     function updateCarousel()
     {
       var user_dir = "<?php echo $user_dir; ?>";
       var param = {"user_dir": user_dir};

       $.ajax({
         url: "functions/update_carousel.php",
         type: 'POST',
         data: param,
         success: function(data){
           $("#carouselExampleIndicators").empty();
           $("#carouselExampleIndicators").append(data);
         }
       });
     }


     /* ------------------------------
      * スライドショーの写真にフィルタをかける
      * ------------------------------*/
     function changePhotoFilter(filter_name)
     {
       $("#carouselExampleIndicators figure").removeClass(); // 全てのクラスを削除
       $("#carouselExampleIndicators figure").addClass(filter_name);

       /*
       if(filter_name != "nofilter")
       {
         $("#carouselExampleIndicators figure").addClass(filter_name);
       }
       */
     }


     /* ------------------------------
      * BGMをかける
      * ------------------------------*/
     function changeBGM(bgm_name)
     {
       $('#BGM1').get(0).pause();
       $('#BGM2').get(0).pause();
       $('#BGM3').get(0).pause();

       if(bgm_name != "no_BGM")
       {
         $('#'+bgm_name).get(0).play();
       }
     }

     /* -----------------------------------
      * 画像を削除する処理
      * ----------------------------------*/
      //var current_delete_img_path, current_delete_user_dir, current_delete_list_cnt;

      function delete_img(img_path, user_dir, list_cnt)
      {
        /*
        current_delete_img_path = img_path;
        current_delete_user_dir = user_dir;
        current_delete_list_cnt = list_cnt;

        $('#confirmPhotoModal').modal('show');
        */
        var param = {"img_path": img_path,"user_dir":user_dir};

        console.log(user_dir);
        console.log(img_path);
        //return;
        $.ajax({
          url: "functions/remove_img_file.php",
          type: 'POST',
          data: param,
          async: true,
          success: function(data){
            console.log(data);
            // ここに削除したファイルを非表示にする処理を書く
            $("#"+list_cnt).remove();

            // ゴミ箱の中のファイル数を更新
            if(data != 0)
            {
              <?php $trush_cnt++; ?>
            }
            $("#trush-cnt").html("(<?php echo $trush_cnt;?>)");

            // スライドショーを更新する処理
            updateCarousel();
          }
        });
      }

      /*
      function deleteImgConfirm()
      {
        var param = {"img_path": current_delete_img_path, "user_dir":current_delete_user_dir};
        console.log(current_delete_user_dir);
        console.log(current_delete_img_path);

        $.ajax({
          url: "functions/remove_img_file.php",
          type: 'POST',
          data: param,
          async: true,
          success: function(data){
            console.log(data);
            // ここに削除したファイルを非表示にする処理を書く
            $("#"+list_cnt).empty();

            current_delete_img_path = "";
            current_delete_user_dir = "";
            current_delete_list_cnt = "";
          }
        });
      }
      */

      /*
      function deleteImgCancel()
      {
        $('#confirmPhotoModal').modal('hide');
        current_delete_img_path = "";
        current_delete_user_dir = "";
        current_delete_list_cnt = "";
      }
      */


    /* -----------------------------------
     * 画像を元に戻す処理
     * ----------------------------------*/
     function undo_img(user_dir)
     {
      var param = {"user_dir":user_dir};

      $.ajax({
        url: "functions/restore.php",
        type: 'POST',
        data: param,
        async: true,
        success: function(data){
          console.log(data);

          if(data == "no-file")
          {
            return;
          }

          // ここに戻したファイルを表示にする処理を書く
          <?php
            $cnt++;
          ?>
          var filename = data.match(".+/(.+?)\.[a-z]+([\?#;].*)?$")[1];
          console.log(filename);
          var time = unixdateformat(filename);
          console.log(time);

          var tag = "<li id='list<?php echo $cnt; ?>' class='list-group-item d-flex justify-content-between align-items-center'>\
                  <div class='photo-item-time badge badge-dark'>"+time+"</div>\
                  <img src='"+ data +"' class='photo-list-item'>\
                  <span class='btn btn-outline-danger btn-sm' onClick='delete_img(\""+ data +"\", \"<?php echo $user_dir2; ?>\", \"list<?php echo $cnt; ?>\");'>DELETE</span>\
                </li>";
          $("#list-wrap").prepend(tag);

          // ゴミ箱の中のファイル数を更新
          <?php $trush_cnt--; ?>
          $("#trush-cnt").html("(<?php echo $trush_cnt;?>)");

          // スライドショーを更新する処理
          updateCarousel();
        }
      });
     }


     /* ------------------------------
      * スライドショーの再生・停止
      * flag:1 再生する処理
      * flag:0 停止する処理
      * ------------------------------*/
     var play_flag = 0;
     function play()
     {
       if(play_flag == 0)
       {
         console.log("0:"+play_flag);
         $('.carousel').carousel('pause');
         $("#playBtn").html("<i class='fa fa-play' aria-hidden='true'></i> 再生");
         play_flag = 1;
       }
       else
       {
         console.log("1:"+play_flag);
         $('.carousel').carousel({
            interval: CAROUSEL_TIME
          });
          $("#playBtn").html("<i class='fa fa-pause' aria-hidden='true'></i> 停止");
          play_flag = 0;
       }
     }

      /* ------------------------------
       * タイムスタンプを指定の日時にフォーマット化
       * ------------------------------*/
      function unixdateformat(str){
        var objDate = new Date(str*1000);
        var nowDate = new Date();
        //現在時間との差
        myHour = Math.floor((nowDate.getTime()-objDate.getTime()) / (1000*60*60)) + 1;

        var year = objDate.getFullYear();
        var month = objDate.getMonth() + 1;
        var date = objDate.getDate();
        var hours = objDate.getHours();
        var minutes = objDate.getMinutes();
        var seconds = objDate.getSeconds();
        if ( hours < 10 ) { hours = "0" + hours; }
        if ( minutes < 10 ) { minutes = "0" + minutes; }
        if ( seconds < 10 ) { seconds = "0" + seconds; }
        str = year + '/' + month + '/' + date + ' ' + hours + ':' + minutes + ':' + seconds;
        var rtnValue = str;

        return rtnValue;
      }



     $(function() {
       /* ------------------------------
        * 画像フィルタのチェックが変化した時の処理
        * ------------------------------*/
       $('input[name="photo-filter"]').change(function()
       {
         var prop = $('#photo-filter-toggle').prop('checked');
         if (prop)
         {
           // checked状態
           $(".photo-filter-btn").prop("disabled", false);
           $(".photo-filter-btn").removeClass("btn-secondary");
           $(".photo-filter-btn").addClass("btn-success");

         }
         else
         {
           $("#carouselExampleIndicators figure").removeClass(); // 全てのクラスを削除
           // ボタンを無効にする
           $(".photo-filter-btn").prop("disabled", true);
           $(".photo-filter-btn").removeClass("btn-success");
           $(".photo-filter-btn").addClass("btn-secondary");
         }
       });

       /* ------------------------------
        * BGMの有無チェックが変化した時の処理
        * ------------------------------*/
       $('input[name="bgm-btn"]').change(function()
       {
         var prop = $('#bgm-toggle').prop('checked');
         if (prop)
         {
           // checked状態
           $(".bgm-select-btn").prop("disabled", false);
           $(".bgm-select-btn").removeClass("btn-secondary");
           $(".bgm-select-btn").addClass("btn-success");
         }
         else
         {
           $('#BGM1').get(0).pause();
           $('#BGM2').get(0).pause();
           $('#BGM3').get(0).pause();


           // ボタンを無効にする
           $(".bgm-select-btn").prop("disabled", true);
           $(".bgm-select-btn").removeClass("btn-success");
           $(".bgm-select-btn").addClass("btn-secondary");
         }
       });
     });


     </script>

    <audio id="BGM1" loop>
      <source src="audio/slow01.mp3" type="audio/mp3">
    </audio>

    <audio id="BGM2" loop>
      <source src="audio/enjoy01.mp3" type="audio/mp3">
    </audio>

    <audio id="BGM3" loop>
      <source src="audio/enjoy02.mp3" type="audio/mp3">
    </audio>
   </body>
 </html>
