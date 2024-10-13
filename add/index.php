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
        if(isset($_POST["game"])){
          $game = mysqli_real_escape_string($con,$_POST['game']);
        }
        if (isset($_POST["date"])) {
          $date = mysqli_real_escape_string($con,$_POST['date']);
        }
        if (isset($_POST["game"]) && !$game) {
          echo "Game \"$game\" not valid";
        } elseif ($date && $game){
          $str = "INSERT INTO logs (date, name) VALUES ('$date','$game');";
          if(!mysqli_query($con, $str)){
            $err = mysqli_error($con);
            echo $err;
          } else {
            echo "$game successfully logged";
          }
        }
      ?>
    </div>
    <div class="main">
      <form action="" method="post">
        <p>
          <input type="date" name="date" value="<?=date('Y-m-d')?>">
        </p><p>
          Played:
        </p><p>
          <input type="text" name="game">
        </p><p>
          <input type="submit" />
        </p>
      </form>
    </div>
  </body>
</html>
	
