<?php
  include_once 'functions/functions.php';


  $file_list = array();
  if(!empty($_POST['user_dir']))
  {
    // ディレクトリの存在チェック
    $user_dir2 = $_POST['user_dir'];
    $user_dir = "data/{$_POST['user_dir']}";
    //print_r($user_dir2);
    //print_r($_POST['user_dir']);
    if(file_exists($user_dir))
    {
      $file_list = getDirFiles($user_dir);
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

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
     <link rel='stylesheet' href='css/style.css'/>
     <link rel="stylesheet" href="https://cssgram-cssgram.netdna-ssl.com/cssgram.min.css">

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
     <style>
     .list_side_par{
       width: 100%;
       display: flex;
     }
     .list_side_chi{
       width: 100%;
     }
     .list_botton{
       margin-top: 10%;
     }
     </style>
   </head>
   <body>
     <div>
       <button class="my-btn list_botton" onClick="undo_img('<?php echo "{$user_dir2}" ?>');"><span>元に戻す</span></button>
     </div>

     <div id="list-wrap">
     <?php

      $cnt = 0;
      if(count($file_list) == 0)
      {
        echo "写真がありません";
      }


      foreach ($file_list as $value)
      {
echo <<< EOM
<br />
  <div id="list{$cnt}" class="list_side_par">\
    <div class="list_side_chi">
    <img src="{$value}" style="width:80%;">
  </div>
  <div class="list_side_chi">
    <button class="my-btn list_botton" onClick="delete_img('{$value}','{$user_dir2}', 'list{$cnt}');"><span>DELETE</span></button>
  </div>
  </div>
  <br /><hr />
EOM;
        $cnt++;
      }

     ?>

    </div>
     <!-- Optional JavaScript -->
     <!-- jQuery first, then Popper.js, then Bootstrap JS -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>



     <script>
       /* -----------------------------------
        * 画像を削除する処理
        * ----------------------------------*/
      function delete_img(img_path,user_dir,list_cnt)
      {
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
           $("#"+list_cnt).empty();
         }
       });
      }


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
          var tag = "<div id='list<?php echo $cnt; ?>' class='list_side_par'>\
            <div class='list_side_chi'>\
            <img src='"+ data +"' style='width:80%;'>\
          </div>\
          <div class='list_side_chi'>\
            <button class='my-btn list_botton' onClick='delete_img(\""+ data +"\", \"<?php echo $user_dir2; ?>\", \"list<?php echo $cnt; ?>\");'><span>DELETE</span></button>\
          </div>\
          </div>";

          $("#list-wrap").prepend(tag);

          <?php $cnt++; ?>
        }
      });
     }



     </script>
   </body>
 </html>
