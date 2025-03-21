<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="../styles.css" rel="stylesheet">
    <title>Add a game played</title>
  </head>
  <body>
    <div class="header">
      <?php 
        include("../setup.php");

        // Logging games
        $date = null;
        $game = null;
        if(isset($_POST["game"])){
          $game = mysqli_real_escape_string($con,$_POST['game']);
        }
        if (isset($_POST["date"])) {
          $date = mysqli_real_escape_string($con,$_POST['date']);
        }
        if (isset($_POST["status"])) {
          $status = mysqli_real_escape_string($con,$_POST['status']);
        }
        if (isset($_POST["game"]) && !$game) {
          echo "Game \"$game\" not valid";
        } elseif ($status && $game) {
          $newstatus = "currently playing";
          if ($status > 1) {
            $newstatus = "played";
          }
          $sql = "UPDATE `toplay` SET `status`='$status' WHERE `name`='$game';";
          if(!mysqli_query($con, $sql)){
            $err = mysqli_error($con);
            echo $err;
          } else {
            echo "$game successfully set to '$newstatus'";
          }
          if ($status == 2) {
            // Need to also log the play
            if (empty($date)) { 
              $date = date ("Y-m-d", time());
            }
            $sql = "INSERT INTO logs (date, name) VALUES ('$date','$game');";
            if(!mysqli_query($con, $sql)){
              $err = mysqli_error($con);
              echo $err;
            } else {
              echo "<br />";
              echo "$game successfully logged";
            }
          }
        } elseif ($date && $game){
          $str = "INSERT INTO logs (date, name) VALUES ('$date','$game');";
          if(!mysqli_query($con, $str)){
            $err = mysqli_error($con);
            echo $err;
          } else {
            echo "$game successfully logged";
          }
        }

        // Uploading images
        if(isset($_FILES["fileupload"])) {
          $filename = basename($_FILES["fileupload"]["name"]);
          $filepath = "../images/$filename"; 
          $uploadOk = true;
          // Check if image file is a actual image or fake image
          if(getimagesize($_FILES["fileupload"]["tmp_name"]) === false) {
            echo "Error uploading $filename - file is not an image.";
            $uploadOk = false;
          }
          if ($uploadOk && file_exists($filepath)) {
            echo "Error uploading $filename - file already exists.";
            $uploadOk = false;
          }
          $size = $_FILES["fileupload"]["size"];
          if ($uploadOk && $size > 500000) {
            echo "Error uploading $filename - $size bytes is too large.";
            $uploadOk = false;
          }
          $imageFileType = strtolower(pathinfo($filepath ,PATHINFO_EXTENSION));
          if($uploadOk && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
              && $imageFileType != "gif" ) {
            echo "Error uploading $filename - $imageFileType not allowed. Must be PNG, JPG, or GIF.";
            $uploadOk = false;
          }
          if ($uploadOk) {
            if (move_uploaded_file($_FILES["fileupload"]["tmp_name"], $filepath)) {
              echo "The file $filename has been uploaded.";
            } else {
              echo "Error uploading $filename - file could not be moved.";
            }
          }
        }
      ?>
    </div>
    <div class="main withCols">
      <div class="col1">
        <form action="" method="post">
          <p>
            <input type="date" name="date" value="<?=date('Y-m-d')?>" />
          </p><p>
            Played:
          </p><p>
            <input type="text" name="game" />
          </p><p>
            <input type="submit" />
          </p>
        </form>

        <form action="" method="post" enctype="multipart/form-data">
          <p>
            Add an image:
          </p><p>
            <input type="file" name="fileupload" accept=".png,.jpg,.jpeg,.gif" />
          </p><p>
            <input type="submit" value="Upload Image" name="image" />
          </p><p>
        </form>
      </div>
      <div class="col2">
      <?php 
        // Upcoming games
        $str = "SELECT * FROM `toplay` ORDER BY `status` DESC;";
        $entryresult = mysqli_query($con, $str);
        if(!$entryresult){
          $err = mysqli_error($con);
          echo $err;
          exit();
        }
        while ($entry = mysqli_fetch_assoc($entryresult)) {
          $name = trim($entry["name"]);
          $status = $entry["status"] + 1;
          if ($status > 2) {
            continue;
          }
          $submitText = "Start playing";
          if ($status == 2) {
            $submitText = "Mark completed";
          }
        ?>
          <form action="" method="post" class="upnext">
            <input type="hidden" name="game" value="<?=$name?>" />
            <input type="hidden" name="status" value="<?=$status?>" />
            <div class="name"><?=$name?></div>
            <input type="submit" value ="<?=$submitText?>" />
          </form>
        <?php
          if ($status > 1) {
            echo "<hr>";
          }
        } ?>      
      </div>
    </div>
  </body>
</html>
	
