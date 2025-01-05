<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <title>RADina gaming challenge so far</title>
  </head>
  <body>
    <div class="header"><h2>The challenge so far</h2></div>
    <div class="main withCols">
      <div class="col1">
        <div class="widgetContainer">
          <?php 
            include("widgetCode.php");
          ?>
        </div>
        <?php 
          include("setup.php");
          $year = date("Y");
          $str = "SELECT * FROM `logs` WHERE YEAR(date) = $year ORDER BY `date` DESC;";

          $entryresult = mysqli_query($con, $str);
          if(!$entryresult){
            $err = mysqli_error($con);
            echo $err;
            exit();
          }
          
          $i = 0;
          while ($entry = mysqli_fetch_assoc($entryresult)) {
            $name = trim($entry["name"]);
            $date = $entry["date"];
            $datestr = date('F j', strtotime($date));

            $imageFile = strtolower(str_replace(" ", "_", $name));
            if (!file_exists("images/".$imageFile.".png")) {
              $imageFile = "image".$i;
              $i = ($i + 1) % 5;
            }
        ?>
          <div class="game">
            <a href="https://radinagames2020.karantza.org/tagged/<?=$name?>"></a>
            <img src="images/<?=$imageFile?>.png" />
            <div class="info">
              <time datetime="<?=$date?>"><?=$datestr?></time>
              <div class="name"><?=$name?></div>
            </div>
          </div>
        <?php } ?>
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
          $i = 0;
          $lastStatus = -1;
          while ($entry = mysqli_fetch_assoc($entryresult)) {
            $name = trim($entry["name"]);
            $status = $entry["status"];
            if ($lastStatus != $status && $status == 1) {
            ?>
              <h3>Currently Playing</h3>
            <?php
            } elseif ($lastStatus != $status && $status == 0) {
            ?>
              <h3>To Play</h3>
            <?php
            }
            $lastStatus = $status;
            $imageFile = strtolower(str_replace(" ", "_", $name));
            if (!file_exists("images/".$imageFile.".png")) {
              $imageFile = "image".$i;
              $i = ($i + 1) % 5;
            }
          ?>
            <div class="game nolink">
              <img src="images/<?=$imageFile?>.png" />
              <div class="info">
                <div class="name"><?=$name?></div>
              </div>
            </div>
        <?php
          } ?>
      </div>
    </div>
  </body>
</html>
	
