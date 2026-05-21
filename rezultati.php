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
  array('ime' => $ime1, 'tocke' => $tocke1),
  array('ime' => $ime2, 'tocke' => $tocke2),
  array('ime' => $ime3, 'tocke' => $tocke3),
);

// Sort highest score first
usort($igralci, function($a, $b) { return $b['tocke'] - $a['tocke']; });

$najvisje = $igralci[0]['tocke'];

// Standard competition ranking (ties share the same rank).
// Players tied at the top score are all 1st-place co-winners.
$rang = array();
$prejsnje = null;
$mesto = 0;
foreach ($igralci as $idx => $ig) {
  $mesto++;
  if ($ig['tocke'] === $prejsnje) {
    $rang[$idx] = $rang[$idx - 1]; // same rank as the one before
  } else {
    $rang[$idx] = $mesto;
  }
  $prejsnje = $ig['tocke'];
}

$rimske = array(1 => 'I', 2 => 'II', 3 => 'III');
$klase  = array(1 => 'gold', 2 => 'silver', 3 => 'bronze');
$oznake = array(1 => '1st', 2 => '2nd', 3 => '3rd');

for ($i = 0; $i < 3; $i++) {
  $r = $rang[$i];
  $igralci[$i]['rang']   = $r;
  $igralci[$i]['rimska'] = $rimske[$r];
  $igralci[$i]['klasa']  = $klase[$r];
  $igralci[$i]['oznaka'] = $oznake[$r];
  $igralci[$i]['zmagovalec'] = ($r === 1);
}

$stevilo_zmagovalcev = count(array_filter($igralci, function($g){ return $g['zmagovalec']; }));
?>
<!DOCTYPE html>
<html lang="sl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Le Grand Dé — Honours</title>
  <link rel="stylesheet" href="css/rezultati.css">
  <link rel="icon" type="image/png" href="img/ikona.png">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>

  <canvas id="sparkles"></canvas>

  <header class="site-header">
    <div class="logo-crest">✦</div>
    <h1 class="logo-text">Le Grand Dé</h1>
    <p class="logo-sub">HONOURS OF THE EVENING</p>
  </header>

  <?php
    // Build a winner announcement
    $zmag_imena = array();
    foreach ($igralci as $g) { if ($g['zmagovalec']) $zmag_imena[] = $g['ime']; }
    if ($stevilo_zmagovalcev === 1) {
      $naslov = htmlspecialchars($zmag_imena[0]) . ' Claims the Honour';
    } elseif ($stevilo_zmagovalcev === 2) {
      $naslov = htmlspecialchars($zmag_imena[0]) . ' &amp; ' . htmlspecialchars($zmag_imena[1]) . ' Share the Honour';
    } else {
      $naslov = 'A Three-Way Tie — All Share the Honour';
    }
  ?>

  <p class="champion-banner"><?php echo $naslov; ?></p>

  <?php if ($stevilo_zmagovalcev === 1): ?>
  <!-- Classic staggered podium (single winner) -->
  <div class="podium-scene">

    <!-- 2nd place -->
    <div class="podium-slot slot-2">
      <div class="guest-card card-<?php echo $igralci[1]['klasa']; ?>">
        <div class="rank-numeral <?php echo $igralci[1]['klasa']; ?>"><?php echo $igralci[1]['rimska']; ?></div>
        <div class="guest-name"><?php echo htmlspecialchars($igralci[1]['ime']); ?></div>
        <div class="guest-score"><?php echo $igralci[1]['tocke']; ?><span>pts</span></div>
      </div>
      <div class="pedestal ped-<?php echo $igralci[1]['klasa']; ?>">
        <div class="ped-ornament">⸻</div>
        <span class="ped-label"><?php echo $igralci[1]['oznaka']; ?></span>
      </div>
    </div>

    <!-- 1st place -->
    <div class="podium-slot slot-1">
      <div class="crown-wrap">♛</div>
      <div class="guest-card card-<?php echo $igralci[0]['klasa']; ?>">
        <div class="rank-numeral <?php echo $igralci[0]['klasa']; ?>"><?php echo $igralci[0]['rimska']; ?></div>
        <div class="guest-name"><?php echo htmlspecialchars($igralci[0]['ime']); ?></div>
        <div class="guest-score"><?php echo $igralci[0]['tocke']; ?><span>pts</span></div>
      </div>
      <div class="pedestal ped-<?php echo $igralci[0]['klasa']; ?>">
        <div class="ped-ornament">⸻</div>
        <span class="ped-label"><?php echo $igralci[0]['oznaka']; ?></span>
      </div>
    </div>

    <!-- 3rd place -->
    <div class="podium-slot slot-3">
      <div class="guest-card card-<?php echo $igralci[2]['klasa']; ?>">
        <div class="rank-numeral <?php echo $igralci[2]['klasa']; ?>"><?php echo $igralci[2]['rimska']; ?></div>
        <div class="guest-name"><?php echo htmlspecialchars($igralci[2]['ime']); ?></div>
        <div class="guest-score"><?php echo $igralci[2]['tocke']; ?><span>pts</span></div>
      </div>
      <div class="pedestal ped-<?php echo $igralci[2]['klasa']; ?>">
        <div class="ped-ornament">⸻</div>
        <span class="ped-label"><?php echo $igralci[2]['oznaka']; ?></span>
      </div>
    </div>

  </div>

  <?php else: ?>
  <!-- Tie layout: co-winners shown equal, in ranked order -->
  <div class="podium-scene podium-tie">
    <?php foreach ($igralci as $g): ?>
    <div class="podium-slot">
      <?php if ($g['zmagovalec']): ?><div class="crown-wrap">♛</div><?php endif; ?>
      <div class="guest-card card-<?php echo $g['klasa']; ?>">
        <div class="rank-numeral <?php echo $g['klasa']; ?>"><?php echo $g['rimska']; ?></div>
        <div class="guest-name"><?php echo htmlspecialchars($g['ime']); ?></div>
        <div class="guest-score"><?php echo $g['tocke']; ?><span>pts</span></div>
      </div>
      <div class="pedestal ped-<?php echo $g['klasa']; ?>">
        <div class="ped-ornament">⸻</div>
        <span class="ped-label"><?php echo $g['oznaka']; ?></span>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="action-bar" style="flex-direction:column;gap:14px;align-items:center;">
    <a href="rezultati.php?reset=1" class="gumb gumb-primary">Play Again &nbsp;✦</a>
    <div class="countdown-wrap">
      <div class="countdown-bar-track"><div class="countdown-bar" id="cbar"></div></div>
      <p class="countdown-text">Returning to the lobby in <span id="ctick">10</span>s</p>
    </div>
  </div>

  <script src="js/sparkles.js"></script>
  <script>
    (function () {
      var total = 10;
      var remaining = total;
      var bar = document.getElementById('cbar');
      var tick = document.getElementById('ctick');

      var iv = setInterval(function () {
        remaining--;
        tick.textContent = remaining;
        bar.style.width = ((remaining / total) * 100) + '%';
        if (remaining <= 0) {
          clearInterval(iv);
          window.location.href = 'rezultati.php?reset=1';
        }
      }, 1000);
    })();
  </script>
</body>
</html>
