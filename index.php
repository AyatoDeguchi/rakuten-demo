<?php
  include_once 'db_config.php';


  $user_dir = "";

  // ユーザディレクトリの取得
  if(!empty($_GET['token']))
  {
    try
    {
       // connect
       $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
       $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

       $stmt = $db->query("SELECT id FROM login WHERE token='{$_GET['token']}'");
       $user_dir = $stmt->fetch(PDO::FETCH_ASSOC);
       $user_dir = $user_dir['id'];

       $db = null;
    }
    catch(PDOException $e)
    {
     echo $e->getMessage();
     exit;
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
    <link href="css/open-iconic-bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css"/>
    <?php
      $progress_bar_class = "bg-warning";

      if($_GET['style'] == "girl")
      {
        echo "<link rel='stylesheet' href='css/girl.css'/>";
      }
      elseif($_GET['style'] == "girl-jp")
      {
        echo "<link rel='stylesheet' href='css/girl-jp.css'/>";
        $progress_bar_class = "bg-danger";
      }
      elseif($_GET['style'] == "boy")
      {
        echo "<link rel='stylesheet' href='css/boy.css'/>";
        $progress_bar_class = "bg-info";
      }
      else
      {
        echo "<link rel='stylesheet' href='css/girl.css'/>";
      }
    ?>
  </head>
  <body>

    <div class="container">
      <h1 class="top-header">Photo Sharing !</h1>

      <form id="upload-form" method="POST" action="functions/upload.php" enctype="multipart/form-data">
        <input type="hidden" name="user_dir" value="<?php echo $user_dir; ?>">

        <!--<br /><br /><input class="my-btn" style="font-size:30px; text-align: center; padding: 10px 1px 0px 1px;" name="userfile[]" type="file" id="upfile" onChange="printfile()"  multiple><br />
        <div id="result"></div><br />-->
        <input id="upfile" class="my-btn" style="display: none;" name="userfile[]" type="file" accept="image/*" onChange="printfile()" multiple>
        <button id="upfile-submit" type="submit" name="upload" style="display: none;"><span>UPLOAD</span></button>

        <button type="button" class="my-btn" onClick="UploadFiles()"><span>UPLOAD</span></button>
      </form>

<br>
      <form method="POST" action="view.php?style=<?php echo $_GET['style'];?>">
        <input type="hidden" name="user_dir" value="<?php echo $user_dir; ?>">
        <button type="submit" name="view" class="my-btn"><span>VIEW</span></button>
      </form>
      <!--
      <form method="POST" action="list.php?style=<?php echo $_GET['style'];?>">
        <input type="hidden" name="user_dir" value="<?php echo $user_dir; ?>">
        <button type="submit" name="list" class="my-btn"><span>LIST</span></button>
      </form>
      -->


      <!-- ファイルのアップロード中に表示するモーダル -->
      <div id="upload-modal" class="modal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="h6 modal-title">アップロード中...</h5>
            </div>
            <div class="modal-body">
              <div id="progress" class="progress">
                <div class="progress-bar progress-bar-striped <?php echo $progress_bar_class;?>"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ファイルのアップロード完了時に表示するモーダル -->
      <div id="uploaded-modal" class="modal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <span class="oi oi-check" style="color: #9BD77B;"></span> アップロード完了!
            </div>
          </div>
        </div>
      </div>


    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>


    <script>
      /* -------------------------------------
       * ファイルを選択する処理
       * -----------------------------------*/
      function UploadFiles()
      {
        document.getElementById("upfile").click();
      }


      /* -------------------------------------
       * ファイルをアップロードする処理
       * -----------------------------------*/
      function printfile(){
        /*
        var fileList = document.getElementById("upfile").files;
        var list = "選択したファイル";
        if(fileList.length==0){
          list += "がありません。もう一度選択してください。<br />";
        }else{
          list += "<br />";
        }
        for(var i=0; i<fileList.length; i++){
          list += fileList[i].name + "<br>";
        }
        document.getElementById("result").innerHTML = list;
        */

        //document.getElementById("upfile-submit").click();


        // プログレスバーを表示
        $('#upload-modal').modal({'show': true, backdrop: 'static'});

        // ファイルのアップロード処理
        var $form, fd;
        $form = $("#upload-form").get()[0];
        fd = new FormData($form);
        $.ajax({
          url: "functions/upload.php",
          type: 'POST',
          processData: false,
          contentType: false,
          data: fd,
          dataType: 'html',
          async: true,
          success: function(data){
            console.log("done upload!");
            console.log(data);

            // data: アップロードに成功した枚数を返す
            $('#upload-modal').modal('hide'); // プログレスバーを非表示
            if(data > 0)
            {
              $('#uploaded-modal').modal({'show': true, backdrop: true});
            }
          },
          fail: function(data){
            // 何かが失敗した時
            $('#upload-modal').modal('hide'); // プログレスバーを非表示
          },
          xhr: function(){
              var XHR = $.ajaxSettings.xhr();
              if(XHR.upload){
                  XHR.upload.addEventListener('progress',function(e){
                      var progre = parseInt(e.loaded/e.total*100);
                      console.log(progre);
                      $('#progress .progress-bar').css(
                            'width',
                            progre + '%'
                      );
                  }, false);
              }
              return XHR;
          },
        });
      }
    </script>
  </body>
</html>
