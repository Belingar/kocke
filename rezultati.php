<?php
session_start();

if (!isset($_SESSION['tocke1'])) {
  header('Location: index.php');
  exit;
}

if (isset($_GET['reset'])) {
  session_destroy();
  header('Location: index.php');
  exit;
}

$ime1 = $_SESSION['ime1'];
$ime2 = $_SESSION['ime2'];
$ime3 = $_SESSION['ime3'];
$tocke1 = $_SESSION['tocke1'];
$tocke2 = $_SESSION['tocke2'];
$tocke3 = $_SESSION['tocke3'];

$igralci = array(
  array('ime' => $ime1, 'tocke' => $tocke1, 'klasa' => 'gold'),
  array('ime' => $ime2, 'tocke' => $tocke2, 'klasa' => 'silver'),
  array('ime' => $ime3, 'tocke' => $tocke3, 'klasa' => 'bronze'),
);

usort($igralci, function($a, $b) { return $b['tocke'] - $a['tocke']; });

if ($igralci[0]['tocke'] == $igralci[1]['tocke']) {
  if (rand(1,2) == 2) { $t = $igralci[0]; $igralci[0] = $igralci[1]; $igralci[1] = $t; }
}
if ($igralci[1]['tocke'] == $igralci[2]['tocke']) {
  if (rand(1,2) == 2) { $t = $igralci[1]; $igralci[1] = $igralci[2]; $igralci[2] = $t; }
}

$klase = ['gold', 'silver', 'bronze'];
for ($i = 0; $i < 3; $i++) $igralci[$i]['klasa'] = $klase[$i];
?>
<!DOCTYPE html>
<html lang="sl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Le Grand Dé — Honours</title>
  <link rel="stylesheet" href="css/rezultati.css">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>

  <canvas id="sparkles"></canvas>

  <header class="site-header">
    <div class="logo-crest">✦</div>
    <h1 class="logo-text">Le Grand Dé</h1>
    <p class="logo-sub">HONOURS OF THE EVENING</p>
  </header>

  <div class="podium-scene">

    <!-- 2nd place -->
    <div class="podium-slot slot-2">
      <div class="guest-card card-silver">
        <div class="medal silver">II</div>
        <div class="guest-name"><?php echo htmlspecialchars($igralci[1]['ime']); ?></div>
        <div class="guest-score"><?php echo $igralci[1]['tocke']; ?> <span>points</span></div>
      </div>
      <div class="pedestal ped-silver">
        <span class="ped-label">2nd</span>
      </div>
    </div>

    <!-- 1st place -->
    <div class="podium-slot slot-1">
      <div class="laurels">❧ &nbsp; ❧</div>
      <div class="guest-card card-gold">
        <div class="medal gold">I</div>
        <div class="guest-name"><?php echo htmlspecialchars($igralci[0]['ime']); ?></div>
        <div class="guest-score"><?php echo $igralci[0]['tocke']; ?> <span>points</span></div>
      </div>
      <div class="pedestal ped-gold">
        <span class="ped-label">1st</span>
      </div>
    </div>

    <!-- 3rd place -->
    <div class="podium-slot slot-3">
      <div class="guest-card card-bronze">
        <div class="medal bronze">III</div>
        <div class="guest-name"><?php echo htmlspecialchars($igralci[2]['ime']); ?></div>
        <div class="guest-score"><?php echo $igralci[2]['tocke']; ?> <span>points</span></div>
      </div>
      <div class="pedestal ped-bronze">
        <span class="ped-label">3rd</span>
      </div>
    </div>

  </div>

  <div class="action-bar">
    <a href="rezultati.php?reset=1" class="gumb gumb-primary">Play Again &nbsp;✦</a>
  </div>

  <script src="js/sparkles.js"></script>
</body>
</html>
