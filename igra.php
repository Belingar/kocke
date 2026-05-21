<?php
session_start();

if (isset($_POST['ime1'])) {
  if (empty(trim($_POST['ime1'])) || empty(trim($_POST['ime2'])) || empty(trim($_POST['ime3']))) {
    header('Location: index.php?napaka=1');
    exit;
  }

  $_SESSION['ime1'] = trim($_POST['ime1']);
  $_SESSION['ime2'] = trim($_POST['ime2']);
  $_SESSION['ime3'] = trim($_POST['ime3']);
  $_SESSION['kocke'] = intval($_POST['kocke']);
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

define('CILJ', 100); // points needed to win

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
$tocke1 = $_SESSION['tocke1'];
$tocke2 = $_SESSION['tocke2'];
$tocke3 = $_SESSION['tocke3'];
$trenutna_runda = $_SESSION['trenutna_runda'];

// First to CILJ (100) points wins. Anyone who crosses on the same roll ties.
$igra_koncana = ($tocke1 >= CILJ || $tocke2 >= CILJ || $tocke3 >= CILJ);

function fill_odstotek($tocke) {
  $p = ($tocke / CILJ) * 100;
  if ($p > 100) $p = 100;
  if ($p < 0) $p = 0;
  return round($p, 1);
}

$poz1 = fill_odstotek($tocke1);
$poz2 = fill_odstotek($tocke2);
$poz3 = fill_odstotek($tocke3);
?>
<!DOCTYPE html>
<html lang="sl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Le Grand Dé — The Roll</title>
  <link rel="stylesheet" href="css/igra.css">
  <link rel="icon" type="image/png" href="img/ikona.png">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>

  <header class="site-header">
    <div class="logo-crest">✦</div>
    <h1 class="logo-text">Le Grand Dé</h1>
  </header>

  <?php if ($igra_koncana): ?>
    <div class="runda-info finale">✦ &nbsp; A Champion Has Reached <?php echo CILJ; ?> &nbsp; ✦</div>
  <?php else: ?>
    <div class="runda-info">
      First to &nbsp;<span class="runda-num"><?php echo CILJ; ?></span>&nbsp; points claims the honour
    </div>
  <?php endif; ?>

  <div class="arena">

    <!-- Player 1 -->
    <div class="player-card">
      <div class="player-name"><?php echo htmlspecialchars($ime1); ?></div>
      <div class="score-display">
        <span class="score-val"><?php echo $tocke1; ?></span>
        <span class="score-max">/ <?php echo CILJ; ?></span>
      </div>

      <div class="goblet-wrap">
        <div class="goblet-fill-label"><?php echo $poz1; ?>%</div>
        <svg class="goblet goblet-gold" viewBox="0 0 120 170" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <clipPath id="cupClip1">
              <path d="M28 24 Q22 92 56 96 L64 96 Q98 92 92 24 Z"/>
            </clipPath>
            <linearGradient id="liquid1" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#F5E6B0"/>
              <stop offset="100%" stop-color="#C9A84C"/>
            </linearGradient>
          </defs>
          <!-- liquid (clipped to cup), height driven by fill % -->
          <g clip-path="url(#cupClip1)">
            <rect class="liquid" x="20" y="<?php echo 96 - ($poz1 * 0.72); ?>"
                  width="80" height="<?php echo ($poz1 * 0.72) + 4; ?>" fill="url(#liquid1)"/>
            <rect class="liquid-shine" x="20" y="<?php echo 96 - ($poz1 * 0.72); ?>"
                  width="80" height="5" fill="rgba(255,255,255,0.35)"/>
          </g>
          <!-- cup outline -->
          <path d="M28 24 Q22 92 56 96 L64 96 Q98 92 92 24 Z" fill="none" stroke="#C9A84C" stroke-width="2.5"/>
          <ellipse cx="60" cy="24" rx="32" ry="6" fill="none" stroke="#E2C97E" stroke-width="2.5"/>
          <!-- stem & base -->
          <rect x="55" y="96" width="10" height="44" rx="3" fill="#C9A84C"/>
          <ellipse cx="60" cy="146" rx="30" ry="7" fill="#8A6825"/>
          <ellipse cx="60" cy="143" rx="30" ry="6" fill="#C9A84C"/>
        </svg>
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
            <div class="luxury-die" data-value="<?php echo $v; ?>">
              <?php for ($p = 0; $p < $v; $p++): ?><div class="pip"></div><?php endfor; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <span class="dice-waiting">Awaiting the roll…</span>
        <?php endif; ?>
      </div>
    </div>

    <!-- Player 2 -->
    <div class="player-card">
      <div class="player-name"><?php echo htmlspecialchars($ime2); ?></div>
      <div class="score-display">
        <span class="score-val"><?php echo $tocke2; ?></span>
        <span class="score-max">/ <?php echo CILJ; ?></span>
      </div>

      <div class="goblet-wrap">
        <div class="goblet-fill-label"><?php echo $poz2; ?>%</div>
        <svg class="goblet goblet-silver" viewBox="0 0 120 170" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <clipPath id="cupClip2">
              <path d="M28 24 Q22 92 56 96 L64 96 Q98 92 92 24 Z"/>
            </clipPath>
            <linearGradient id="liquid2" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#F0F0F0"/>
              <stop offset="100%" stop-color="#AEAEAE"/>
            </linearGradient>
          </defs>
          <g clip-path="url(#cupClip2)">
            <rect class="liquid" x="20" y="<?php echo 96 - ($poz2 * 0.72); ?>"
                  width="80" height="<?php echo ($poz2 * 0.72) + 4; ?>" fill="url(#liquid2)"/>
            <rect class="liquid-shine" x="20" y="<?php echo 96 - ($poz2 * 0.72); ?>"
                  width="80" height="5" fill="rgba(255,255,255,0.45)"/>
          </g>
          <path d="M28 24 Q22 92 56 96 L64 96 Q98 92 92 24 Z" fill="none" stroke="#AEAEAE" stroke-width="2.5"/>
          <ellipse cx="60" cy="24" rx="32" ry="6" fill="none" stroke="#D8D8D8" stroke-width="2.5"/>
          <rect x="55" y="96" width="10" height="44" rx="3" fill="#AEAEAE"/>
          <ellipse cx="60" cy="146" rx="30" ry="7" fill="#666"/>
          <ellipse cx="60" cy="143" rx="30" ry="6" fill="#AEAEAE"/>
        </svg>
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
            <div class="luxury-die" data-value="<?php echo $v; ?>">
              <?php for ($p = 0; $p < $v; $p++): ?><div class="pip"></div><?php endfor; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <span class="dice-waiting">Awaiting the roll…</span>
        <?php endif; ?>
      </div>
    </div>

    <!-- Player 3 -->
    <div class="player-card">
      <div class="player-name"><?php echo htmlspecialchars($ime3); ?></div>
      <div class="score-display">
        <span class="score-val"><?php echo $tocke3; ?></span>
        <span class="score-max">/ <?php echo CILJ; ?></span>
      </div>

      <div class="goblet-wrap">
        <div class="goblet-fill-label"><?php echo $poz3; ?>%</div>
        <svg class="goblet goblet-bronze" viewBox="0 0 120 170" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <clipPath id="cupClip3">
              <path d="M28 24 Q22 92 56 96 L64 96 Q98 92 92 24 Z"/>
            </clipPath>
            <linearGradient id="liquid3" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#C08050"/>
              <stop offset="100%" stop-color="#9C6235"/>
            </linearGradient>
          </defs>
          <g clip-path="url(#cupClip3)">
            <rect class="liquid" x="20" y="<?php echo 96 - ($poz3 * 0.72); ?>"
                  width="80" height="<?php echo ($poz3 * 0.72) + 4; ?>" fill="url(#liquid3)"/>
            <rect class="liquid-shine" x="20" y="<?php echo 96 - ($poz3 * 0.72); ?>"
                  width="80" height="5" fill="rgba(255,255,255,0.30)"/>
          </g>
          <path d="M28 24 Q22 92 56 96 L64 96 Q98 92 92 24 Z" fill="none" stroke="#9C6235" stroke-width="2.5"/>
          <ellipse cx="60" cy="24" rx="32" ry="6" fill="none" stroke="#C08050" stroke-width="2.5"/>
          <rect x="55" y="96" width="10" height="44" rx="3" fill="#9C6235"/>
          <ellipse cx="60" cy="146" rx="30" ry="7" fill="#5C3016"/>
          <ellipse cx="60" cy="143" rx="30" ry="6" fill="#9C6235"/>
        </svg>
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
            <div class="luxury-die" data-value="<?php echo $v; ?>">
              <?php for ($p = 0; $p < $v; $p++): ?><div class="pip"></div><?php endfor; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <span class="dice-waiting">Awaiting the roll…</span>
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
          Roll the Dice &nbsp;⚄
        </button>
      </form>
    <?php endif; ?>
  </div>

</body>
</html>
