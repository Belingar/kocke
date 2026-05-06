<?php
require_once 'config.php';

// ── Sprejem POST podatkov ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

// Validacija vseh polj
$fields = ['ime', 'priimek', 'naslov'];
$formData = [];
$valid = true;

for ($p = 1; $p <= NUM_PLAYERS; $p++) {
    foreach ($fields as $f) {
        $key = $f . $p;
        $val = post($key);
        $formData[$key] = $val;
        if ($val === '') $valid = false;
    }
}

if (!$valid) {
    $_SESSION['form_data']  = $formData;
    $_SESSION['form_error'] = 'Prosim izpolnite vsa polja za vse tri igralce.';
    redirect('index.php');
}

// ── 2D tabela uporabnikov ────────────────────────────────────────────────────
$users = [];
for ($p = 1; $p <= NUM_PLAYERS; $p++) {
    $users[] = [
        'ime'     => $formData["ime{$p}"],
        'priimek' => $formData["priimek{$p}"],
        'naslov'  => $formData["naslov{$p}"],
    ];
}

// ── 1D tabele z rezultati kock (ena tabela na igralca) ───────────────────────
$rolls  = [];   // $rolls[i] = [die1, die2, die3]
$totals = [];   // $totals[i] = vsota

foreach ($users as $i => $_u) {
    $playerRolls = [];
    for ($d = 0; $d < NUM_DICE; $d++) {
        $playerRolls[] = rand(1, 6);
    }
    $rolls[]  = $playerRolls;           // 1D tabela za tega igralca
    $totals[] = array_sum($playerRolls);
}

// ── Določitev zmagovalca/ev ──────────────────────────────────────────────────
$maxScore = max($totals);
$winners  = array_keys(array_filter($totals, fn($t) => $t === $maxScore));

// Shrani v sejo (opcijsko, za morebitno kasnejšo rabo)
$_SESSION['last_game'] = [
    'users'   => $users,
    'rolls'   => $rolls,
    'totals'  => $totals,
    'winners' => $winners,
];
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎲 Igra s Kockami – Rezultati</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Nunito:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green:  #1db954;
            --gold:   #ffd700;
            --dark:   #0d1117;
            --card:   #161b22;
            --border: #30363d;
            --text:   #e6edf3;
            --muted:  #8b949e;
            --radius: 14px;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--dark);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 16px 60px;
            background-image:
                radial-gradient(ellipse at 20% 0%,   rgba(29,185,84,.12) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 100%, rgba(255,215,0,.08) 0%, transparent 60%);
        }

        header { text-align: center; margin-bottom: 36px; }
        header .icon { font-size: 2.5rem; display: block; margin-bottom: 4px; }
        header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.4rem, 7vw, 4.2rem);
            letter-spacing: .08em;
            color: var(--green);
            text-shadow: 0 0 30px rgba(29,185,84,.4);
            line-height: 1;
        }

        .wrapper { width: 100%; max-width: 960px; }

        /* ── Winner banner ── */
        .winner-banner {
            background: linear-gradient(135deg, #1a1500 0%, #2e2400 100%);
            border: 2px solid var(--gold);
            border-radius: var(--radius);
            padding: 28px 32px;
            text-align: center;
            margin-bottom: 32px;
            box-shadow: 0 0 60px rgba(255,215,0,.15);
            animation: pulse 2.2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%,100% { box-shadow: 0 0 40px rgba(255,215,0,.12); }
            50%      { box-shadow: 0 0 80px rgba(255,215,0,.28); }
        }
        .winner-banner .trophy { font-size: 2.6rem; margin-bottom: 8px; }
        .winner-banner h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            letter-spacing: .1em;
            color: var(--gold);
        }
        .winner-banner .names {
            font-size: 1.2rem;
            font-weight: 900;
            margin-top: 6px;
        }
        .winner-banner .score-note {
            font-size: .85rem;
            color: var(--muted);
            margin-top: 4px;
        }

        /* ── Player cards grid ── */
        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 22px;
            margin-bottom: 36px;
        }

        .result-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 28px 22px 22px;
            text-align: center;
            position: relative;
            animation: slideUp .5s ease both;
        }
        .result-card:nth-child(2) { animation-delay: .12s; }
        .result-card:nth-child(3) { animation-delay: .24s; }
        @keyframes slideUp {
            from { opacity:0; transform: translateY(28px); }
            to   { opacity:1; transform: translateY(0); }
        }

        .result-card.winner {
            border-color: var(--gold);
            box-shadow: 0 0 0 1px var(--gold), 0 0 40px rgba(255,215,0,.18);
        }

        .crown { display: none; font-size: 1.5rem; margin-bottom: 4px; }
        .result-card.winner .crown { display: block; }

        .player-num {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 6px;
        }
        .result-card h2 { font-size: 1.3rem; font-weight: 900; margin-bottom: 2px; }
        .address { font-size: .82rem; color: var(--muted); margin-bottom: 18px; }

        /* ── Dice images ── */
        .dice-row {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 16px;
        }
        .dice-row img {
            width: 64px;
            height: 64px;
            border-radius: 10px;
            border: 2px solid var(--border);
            background: #fff;
            padding: 2px;
            transition: transform .2s;
        }
        .dice-row img:hover { transform: scale(1.12) rotate(-5deg); }

        .score-badge {
            display: inline-block;
            padding: 6px 22px;
            border-radius: 30px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.5rem;
            letter-spacing: .1em;
            background: rgba(255,255,255,.07);
            border: 1px solid var(--border);
        }
        .result-card.winner .score-badge {
            background: var(--gold);
            color: #000;
            border-color: var(--gold);
        }

        /* ── Dice values list ── */
        .dice-values {
            font-size: .78rem;
            color: var(--muted);
            margin-bottom: 12px;
        }

        /* ── Countdown ── */
        .countdown-wrap {
            background: rgba(255,255,255,.04);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px 24px;
            text-align: center;
        }
        .bar-track {
            background: rgba(255,255,255,.06);
            border-radius: 30px;
            height: 6px;
            overflow: hidden;
            margin: 12px 0 8px;
        }
        .bar-fill {
            height: 100%;
            width: 100%;
            background: var(--green);
            border-radius: 30px;
            transform-origin: left;
            animation: shrink <?= REDIRECT_SECONDS ?>s linear forwards;
        }
        @keyframes shrink { to { transform: scaleX(0); } }
        .countdown-text { font-size: .88rem; color: var(--muted); }
        #cd { color: var(--green); font-weight: 700; }
    </style>
</head>
<body>

<header>
    <span class="icon">🎲</span>
    <h1>Rezultati</h1>
</header>

<div class="wrapper">

    <!-- Zmagovalec(i) -->
    <div class="winner-banner">
        <div class="trophy">🏆</div>
        <?php if (count($winners) === 1): ?>
            <h2>Zmagovalec</h2>
            <div class="names">
                <?= $users[$winners[0]]['ime'] . ' ' . $users[$winners[0]]['priimek'] ?>
            </div>
        <?php else: ?>
            <h2>Izenačenje! Zmagovalci:</h2>
            <div class="names">
                <?php
                    $wNames = array_map(
                        fn($wi) => $users[$wi]['ime'] . ' ' . $users[$wi]['priimek'],
                        $winners
                    );
                    echo implode(' &amp; ', $wNames);
                ?>
            </div>
        <?php endif; ?>
        <div class="score-note">Skupni seštevek: <strong><?= $maxScore ?> točk</strong></div>
    </div>

    <!-- Kartice igralcev -->
    <div class="results-grid">
        <?php foreach ($users as $i => $u):
            $isWinner = in_array($i, $winners);
        ?>
        <div class="result-card <?= $isWinner ? 'winner' : '' ?>">
            <div class="crown">👑</div>
            <div class="player-num">Igralec <?= $i + 1 ?></div>
            <h2><?= $u['ime'] . ' ' . $u['priimek'] ?></h2>
            <div class="address">📍 <?= $u['naslov'] ?></div>

            <!-- 3 kocke (slike) -->
            <div class="dice-row">
                <?php foreach ($rolls[$i] as $die): ?>
                <img src="<?= diceImg($die) ?>"
                     alt="Kocka <?= $die ?>"
                     title="Vrednost: <?= $die ?>"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <span class="dice-fallback" style="display:none;width:64px;height:64px;
                    background:#fff;border-radius:10px;border:2px solid var(--border);
                    align-items:center;justify-content:center;font-size:2rem;font-weight:900;color:#333;">
                    <?= $die ?>
                </span>
                <?php endforeach; ?>
            </div>

            <!-- Vrednosti posameznih kock -->
            <div class="dice-values">
                <?= implode(' + ', $rolls[$i]) ?> = <?= $totals[$i] ?>
            </div>

            <div class="score-badge">Skupaj: <?= $totals[$i] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Odštevalnik -->
    <div class="countdown-wrap">
        <div class="countdown-text">Preusmeritev na začetek igre čez <span id="cd"><?= REDIRECT_SECONDS ?></span> s</div>
        <div class="bar-track"><div class="bar-fill"></div></div>
        <div class="countdown-text" style="font-size:.78rem;">Stran se bo samodejno osvežila.</div>
    </div>

</div>

<script>
    let sec = <?= REDIRECT_SECONDS ?>;
    const el = document.getElementById('cd');
    const timer = setInterval(() => {
        sec--;
        el.textContent = sec;
        if (sec <= 0) {
            clearInterval(timer);
            window.location.href = 'index.php?reset=1';
        }
    }, 1000);
</script>

</body>
</html>
