<?php
  include("setup.php");

  // Fetch the goal amount from the data table
  $str = "SELECT numValue FROM data WHERE attribute=\"goal\";";
  $entryresult = mysqli_query($con, $str);
  if(!$entryresult){
    $err = mysqli_error($con);
    echo $err;
    exit();
  } 
  $goal = mysqli_fetch_assoc($entryresult)["numValue"];

  // Count games played so far
  $year = date('Y');
  $str = "SELECT COUNT(*) as count FROM `logs` WHERE YEAR(date) = $year;";
  $entryresult = mysqli_query($con, $str);
  if(!$entryresult){
    $err = mysqli_error($con);
    echo $err;
    exit();
  }
  $playedCount = mysqli_fetch_assoc($entryresult)["count"];

  // Games expected to be played vs actual
  $yearPct = (date('z') + 1) / 365;
  $expectedGames = round($yearPct * $goal);
  $diff = $expectedGames - $playedCount;
  $scheduleStr = "behind";
  if ($diff < 0) {
    $scheduleStr = "ahead of";
    $diff = $diff * -1;
  }

  // How many games to play remaining
  $weeksLeft = 52 - date('W');
  $gamesLeft = $goal - $playedCount;
  $gamesPerWeek = $gamesLeft / $weeksLeft;
  if ($gamesPerWeek < 1) {
    $gamesPerWeek = round($gamesPerWeek, 1);
  } else {
    $gamesPerWeek = round($gamesPerWeek);
  }

  $pct = round(($playedCount / $goal) * 100);
?>

<div class="widget">
  <div class="sidebar">
    <div class="year"><?=$year?></div>
    <img src="/vgames/images/controller.png" />
    GAMING CHALLENGE
  </div>
  <div class="details">
    <div>
      <?=$playedCount?> games completed
    </div>
    <div>
      <?=$diff?> games <?=$scheduleStr?> schedule
    </div>
    <div class="progressContainer">
      <div class="progressBar">
        <div class="progressBarInner" style="width: <?=min(100,$pct)?>%"></div>
      </div>
      <?=$playedCount?>/<?=$goal?> (<?=$pct?>%)
    </div>
    <div>
      <?php if ($gamesLeft <= 0) { ?>
        🎉 CHALLENGE COMPLETED!!! 🎉
      <?php } else { ?>
        Play <?=$gamesPerWeek?> games per week to complete the challenge this year
      <?php } ?>
    </div>
  </div>
</div>