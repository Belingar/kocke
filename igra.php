<?php
session_start();

if (isset($_POST['ime1'])) {
  if (empty(trim($_POST['ime1'])) || empty(trim($_POST['ime2'])) || empty(trim($_POST['ime3']))) {
    header('Location: index.php?napaka=1');
    exit;
  }
  $runde = intval($_POST['runde']);
  if ($runde < 1) $runde = 1;
  if ($runde > 20) $runde = 20;

  $_SESSION['ime1'] = trim($_POST['ime1']);
  $_SESSION['ime2'] = trim($_POST['ime2']);
  $_SESSION['ime3'] = trim($_POST['ime3']);
  $_SESSION['kocke'] = intval($_POST['kocke']);
  $_SESSION['runde'] = $runde;
  $_SESSION['tocke1'] = 0;
  $_SESSION['tocke2'] = 0;
  $_SESSION['tocke3'] = 0;
  $_SESSION['trenutna_runda'] = 1;
  $_SESSION['zgodovina1'] = array();
  $_SESSION['zgodovina2'] = array();
  $_SESSION['zgodovina3'] = array();
  $_SESSION['zadnje_kocke1'] = array();
  $_SESSION['zadnje_kocke2'] = array();
  $_SESSION['zadnje_kocke3'] = array();
}

if (isset($_POST['vrzi'])) {
  $kocke = $_SESSION['kocke'];

  $vsota1 = 0; $meti1 = array();
  for ($k = 0; $k < $kocke; $k++) { $m = rand(1,6); $vsota1 += $m; $meti1[] = $m; }
  $_SESSION['tocke1'] += $vsota1;
  $_SESSION['zadnje_kocke1'] = $meti1;
  $_SESSION['zgodovina1'][] = array('tocke' => $vsota1, 'kocke' => $meti1);

  $vsota2 = 0; $meti2 = array();
  for ($k = 0; $k < $kocke; $k++) { $m = rand(1,6); $vsota2 += $m; $meti2[] = $m; }
  $_SESSION['tocke2'] += $vsota2;
  $_SESSION['zadnje_kocke2'] = $meti2;
  $_SESSION['zgodovina2'][] = array('tocke' => $vsota2, 'kocke' => $meti2);

  $vsota3 = 0; $meti3 = array();
  for ($k = 0; $k < $kocke; $k++) { $m = rand(1,6); $vsota3 += $m; $meti3[] = $m; }
  $_SESSION['tocke3'] += $vsota3;
  $_SESSION['zadnje_kocke3'] = $meti3;
  $_SESSION['zgodovina3'][] = array('tocke' => $vsota3, 'kocke' => $meti3);

  $_SESSION['trenutna_runda']++;
}

$ime1 = $_SESSION['ime1'];
$ime2 = $_SESSION['ime2'];
$ime3 = $_SESSION['ime3'];
$kocke = $_SESSION['kocke'];
$runde = $_SESSION['runde'];
$tocke1 = $_SESSION['tocke1'];
$tocke2 = $_SESSION['tocke2'];
$tocke3 = $_SESSION['tocke3'];
$trenutna_runda = $_SESSION['trenutna_runda'];
$max_tocke = $runde * $kocke * 6;

function pozicija($tocke, $max_tocke) {
  if ($max_tocke == 0) return 2;
  $p = ($tocke / $max_tocke) * 78;
  if ($p > 78) $p = 78;
  return $p + 2;
}

$poz1 = pozicija($tocke1, $max_tocke);
$poz2 = pozicija($tocke2, $max_tocke);
$poz3 = pozicija($tocke3, $max_tocke);
$igra_koncana = ($trenutna_runda > $runde);
?>
<!DOCTYPE html>
<html lang="sl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Le Grand Dé — The Roll</title>
  <link rel="stylesheet" href="css/igra.css">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>

  <header class="site-header">
    <div class="logo-crest">✦</div>
    <h1 class="logo-text">Le Grand Dé</h1>
  </header>

  <?php if ($igra_koncana): ?>
    <div class="runda-info finale">✦ &nbsp; The Final Roll Has Been Cast &nbsp; ✦</div>
  <?php else: ?>
    <div class="runda-info">
      Round &nbsp;<span class="runda-num"><?php echo $trenutna_runda; ?></span>&nbsp; of &nbsp;<span class="runda-num"><?php echo $runde; ?></span>
    </div>
  <?php endif; ?>

  <div class="arena">

    <!-- Player 1 -->
    <div class="player-card">
      <div class="player-name"><?php echo htmlspecialchars($ime1); ?></div>
      <div class="score-display">
        <span class="score-val"><?php echo $tocke1; ?></span>
        <span class="score-max">/ <?php echo $max_tocke; ?></span>
      </div>

      <div class="track-wrap">
        <div class="track-finish">⬛</div>
        <div class="track">
          <div class="token token-1" style="left:<?php echo $poz1; ?>%">
            <svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
              <ellipse cx="20" cy="32" rx="14" ry="5" fill="rgba(0,0,0,0.3)"/>
              <rect x="4" y="14" width="32" height="16" rx="8" fill="#C9A84C"/>
              <rect x="10" y="8" width="20" height="12" rx="5" fill="#D4AF5A"/>
              <circle cx="11" cy="30" r="4" fill="#1a1a1a"/><circle cx="11" cy="30" r="2" fill="#888"/>
              <circle cx="29" cy="30" r="4" fill="#1a1a1a"/><circle cx="29" cy="30" r="2" fill="#888"/>
              <rect x="6" y="18" width="8" height="6" rx="2" fill="rgba(255,255,255,0.15)"/>
            </svg>
          </div>
        </div>
      </div>

      <div class="history-box">
        <table class="povijest">
          <?php foreach ($_SESSION['zgodovina1'] as $i => $r): ?>
          <tr>
            <td class="r-label">Round <?php echo $i+1; ?></td>
            <td class="r-score"><?php echo $r['tocke']; ?> pts</td>
          </tr>
          <?php endforeach; ?>
        </table>
      </div>

      <div class="dice-area">
        <?php if (count($_SESSION['zadnje_kocke1']) > 0): ?>
          <?php foreach ($_SESSION['zadnje_kocke1'] as $v): ?>
            <img src="img/dice/dice<?php echo $v; ?>.gif" alt="<?php echo $v; ?>" class="kocka-slika">
          <?php endforeach; ?>
        <?php else: ?>
          <img src="img/dice/dice-anim.gif" alt="roll" class="kocka-slika kocka-idle">
        <?php endif; ?>
      </div>
    </div>

    <!-- Player 2 -->
    <div class="player-card">
      <div class="player-name"><?php echo htmlspecialchars($ime2); ?></div>
      <div class="score-display">
        <span class="score-val"><?php echo $tocke2; ?></span>
        <span class="score-max">/ <?php echo $max_tocke; ?></span>
      </div>

      <div class="track-wrap">
        <div class="track-finish">⬛</div>
        <div class="track">
          <div class="token token-2" style="left:<?php echo $poz2; ?>%">
            <svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
              <ellipse cx="20" cy="32" rx="14" ry="5" fill="rgba(0,0,0,0.3)"/>
              <rect x="4" y="14" width="32" height="16" rx="8" fill="#8A8A8A"/>
              <rect x="10" y="8" width="20" height="12" rx="5" fill="#AEAEAE"/>
              <circle cx="11" cy="30" r="4" fill="#1a1a1a"/><circle cx="11" cy="30" r="2" fill="#888"/>
              <circle cx="29" cy="30" r="4" fill="#1a1a1a"/><circle cx="29" cy="30" r="2" fill="#888"/>
              <rect x="6" y="18" width="8" height="6" rx="2" fill="rgba(255,255,255,0.15)"/>
            </svg>
          </div>
        </div>
      </div>

      <div class="history-box">
        <table class="povijest">
          <?php foreach ($_SESSION['zgodovina2'] as $i => $r): ?>
          <tr>
            <td class="r-label">Round <?php echo $i+1; ?></td>
            <td class="r-score"><?php echo $r['tocke']; ?> pts</td>
          </tr>
          <?php endforeach; ?>
        </table>
      </div>

      <div class="dice-area">
        <?php if (count($_SESSION['zadnje_kocke2']) > 0): ?>
          <?php foreach ($_SESSION['zadnje_kocke2'] as $v): ?>
            <img src="img/dice/dice<?php echo $v; ?>.gif" alt="<?php echo $v; ?>" class="kocka-slika">
          <?php endforeach; ?>
        <?php else: ?>
          <img src="img/dice/dice-anim.gif" alt="roll" class="kocka-slika kocka-idle">
        <?php endif; ?>
      </div>
    </div>

    <!-- Player 3 -->
    <div class="player-card">
      <div class="player-name"><?php echo htmlspecialchars($ime3); ?></div>
      <div class="score-display">
        <span class="score-val"><?php echo $tocke3; ?></span>
        <span class="score-max">/ <?php echo $max_tocke; ?></span>
      </div>

      <div class="track-wrap">
        <div class="track-finish">⬛</div>
        <div class="track">
          <div class="token token-3" style="left:<?php echo $poz3; ?>%">
            <svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
              <ellipse cx="20" cy="32" rx="14" ry="5" fill="rgba(0,0,0,0.3)"/>
              <rect x="4" y="14" width="32" height="16" rx="8" fill="#7B4F2E"/>
              <rect x="10" y="8" width="20" height="12" rx="5" fill="#9C6235"/>
              <circle cx="11" cy="30" r="4" fill="#1a1a1a"/><circle cx="11" cy="30" r="2" fill="#888"/>
              <circle cx="29" cy="30" r="4" fill="#1a1a1a"/><circle cx="29" cy="30" r="2" fill="#888"/>
              <rect x="6" y="18" width="8" height="6" rx="2" fill="rgba(255,255,255,0.15)"/>
            </svg>
          </div>
        </div>
      </div>

      <div class="history-box">
        <table class="povijest">
          <?php foreach ($_SESSION['zgodovina3'] as $i => $r): ?>
          <tr>
            <td class="r-label">Round <?php echo $i+1; ?></td>
            <td class="r-score"><?php echo $r['tocke']; ?> pts</td>
          </tr>
          <?php endforeach; ?>
        </table>
      </div>

      <div class="dice-area">
        <?php if (count($_SESSION['zadnje_kocke3']) > 0): ?>
          <?php foreach ($_SESSION['zadnje_kocke3'] as $v): ?>
            <img src="img/dice/dice<?php echo $v; ?>.gif" alt="<?php echo $v; ?>" class="kocka-slika">
          <?php endforeach; ?>
        <?php else: ?>
          <img src="img/dice/dice-anim.gif" alt="roll" class="kocka-slika kocka-idle">
        <?php endif; ?>
      </div>
    </div>

  </div><!-- /arena -->

  <div class="action-bar">
    <?php if ($igra_koncana): ?>
      <a href="rezultati.php" class="gumb gumb-primary">View Final Results ✦</a>
    <?php else: ?>
      <form action="igra.php" method="POST">
        <input type="hidden" name="vrzi" value="1">
        <button type="submit" class="gumb gumb-primary">
          Roll Round <?php echo $trenutna_runda; ?> &nbsp;⚄
        </button>
      </form>
    <?php endif; ?>
  </div>

</body>
</html>
