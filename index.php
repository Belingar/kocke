<?php
?>
<!DOCTYPE html>
<html lang="sl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Le Grand Dé — Prestige Roll</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="icon" type="image/png" href="img/ikona.png">
</head>
<body>

  <div class="stars" id="stars"></div>

  <header class="site-header">
    <div class="logo-crest">✦</div>
    <h1 class="logo-text">Le Grand Dé</h1>
    <p class="logo-sub">PRESTIGE ROLL</p>
  </header>

  <main>
    <div class="kartica">

      <?php if (isset($_GET['napaka'])): ?>
      <div class="napaka">
        ⚠ All three guest names must be provided to begin.
      </div>
      <?php endif; ?>

      <form action="igra.php" method="POST">

        <h2 class="section-title">Guest Registry</h2>

        <div class="igralci-vrstica">
          <div class="igralec-blok">
            <label class="guest-label" for="ime1">Guest I</label>
            <input
              type="text" id="ime1" name="ime1"
              class="vnosno-polje"
              maxlength="15"
              placeholder="Enter name"
              value="<?php echo isset($_GET['ime1']) ? htmlspecialchars($_GET['ime1']) : ''; ?>"
            >
            <div class="player-emblem emblem-1">I</div>
          </div>

          <div class="igralec-blok">
            <label class="guest-label" for="ime2">Guest II</label>
            <input
              type="text" id="ime2" name="ime2"
              class="vnosno-polje"
              maxlength="15"
              placeholder="Enter name"
              value="<?php echo isset($_GET['ime2']) ? htmlspecialchars($_GET['ime2']) : ''; ?>"
            >
            <div class="player-emblem emblem-2">II</div>
          </div>

          <div class="igralec-blok">
            <label class="guest-label" for="ime3">Guest III</label>
            <input
              type="text" id="ime3" name="ime3"
              class="vnosno-polje"
              maxlength="15"
              placeholder="Enter name"
              value="<?php echo isset($_GET['ime3']) ? htmlspecialchars($_GET['ime3']) : ''; ?>"
            >
            <div class="player-emblem emblem-3">III</div>
          </div>
        </div>

        <div class="divider"><span>⸻ &nbsp; Game Settings &nbsp; ⸻</span></div>

        <div class="nastavitve-vrstica">
          <div class="nastavitev-blok">
            <label class="guest-label" for="kocke">Dice per Round</label>
            <select id="kocke" name="kocke" class="vnosno-polje select-field">
              <?php
                for ($i = 1; $i <= 6; $i++) {
                  $sel = ($i == 3) ? 'selected' : '';
                  echo '<option value="' . $i . '" ' . $sel . '>' . $i . ' ' . ($i == 1 ? 'die' : 'dice') . '</option>';
                }
              ?>
            </select>
          </div>

          <div class="nastavitev-blok">
            <label class="guest-label" for="runde">Number of Rounds</label>
            <input
              type="number" id="runde" name="runde"
              class="vnosno-polje"
              min="1" max="20" value="5"
            >
          </div>
        </div>

        <div class="gumbi-vrstica">
          <button type="submit" class="gumb gumb-primary">
            ✦ &nbsp; BEGIN THE GAME
          </button>
        </div>

      </form>
    </div>
  </main>


  <script>
    // Generate star field
    const starsEl = document.getElementById('stars');
    for (let i = 0; i < 120; i++) {
      const s = document.createElement('div');
      s.className = 'star';
      s.style.left = Math.random() * 100 + '%';
      s.style.top = Math.random() * 100 + '%';
      s.style.animationDelay = (Math.random() * 4) + 's';
      s.style.width = s.style.height = (Math.random() * 2 + 1) + 'px';
      starsEl.appendChild(s);
    }
  </script>
</body>
</html>
