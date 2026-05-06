<?php
require_once 'config.php';

// Če seja že ima podatke in gremo nazaj, počistimo
if (isset($_GET['reset'])) {
    session_destroy();
    redirect('index.php');
}

// Preberi morebitne prejšnje vrednosti iz seje (za pre-fill po napaki)
$prev = $_SESSION['form_data'] ?? [];
$error = $_SESSION['form_error'] ?? '';
unset($_SESSION['form_error']);
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎲 Igra s Kockami – Vnos Igralcev</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Nunito:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green:  #1db954;
            --green2: #17a347;
            --red:    #e63946;
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
                radial-gradient(ellipse at 80% 100%, rgba(230,57,70,.10) 0%, transparent 60%);
        }

        header { text-align: center; margin-bottom: 40px; }
        header .icon { font-size: 3rem; display: block; margin-bottom: 6px; }
        header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.8rem, 8vw, 5rem);
            letter-spacing: .08em;
            color: var(--green);
            text-shadow: 0 0 30px rgba(29,185,84,.4);
            line-height: 1;
        }
        header p { color: var(--muted); margin-top: 8px; font-size: 1rem; }

        .form-wrapper { width: 100%; max-width: 860px; }

        .error-box {
            background: rgba(230,57,70,.1);
            border: 1px solid var(--red);
            border-radius: 10px;
            color: var(--red);
            padding: 12px 18px;
            margin-bottom: 20px;
            font-size: .9rem;
        }

        .players-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .player-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px 20px;
            position: relative;
            transition: border-color .25s;
        }
        .player-card:hover { border-color: var(--green); }

        .player-card .badge {
            position: absolute;
            top: -14px; left: 20px;
            background: var(--green);
            color: #000;
            font-weight: 900;
            font-size: .78rem;
            letter-spacing: .1em;
            padding: 3px 12px;
            border-radius: 20px;
            text-transform: uppercase;
        }

        .player-card label {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 6px;
            margin-top: 14px;
        }
        .player-card label:first-of-type { margin-top: 10px; }

        .player-card input {
            width: 100%;
            background: rgba(255,255,255,.05);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: 'Nunito', sans-serif;
            font-size: .95rem;
            padding: 9px 12px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .player-card input:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(29,185,84,.15);
        }
        .player-card input.invalid {
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(230,57,70,.15);
        }

        .btn-play {
            display: block;
            width: 100%;
            padding: 16px;
            background: var(--green);
            color: #000;
            border: none;
            border-radius: var(--radius);
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.6rem;
            letter-spacing: .12em;
            cursor: pointer;
            transition: background .2s, transform .1s, box-shadow .2s;
            box-shadow: 0 4px 24px rgba(29,185,84,.3);
        }
        .btn-play:hover {
            background: var(--green2);
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(29,185,84,.45);
        }
        .btn-play:active { transform: translateY(0); }
    </style>
</head>
<body>

<header>
    <span class="icon">🎲</span>
    <h1>Igra s Kockami</h1>
    <p>Vnesite podatke za <?= NUM_PLAYERS ?> igralce in začnite igro!</p>
</header>

<div class="form-wrapper">

    <?php if ($error): ?>
        <div class="error-box">⚠ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="play.php" onsubmit="return validateForm()">
        <div class="players-grid">
            <?php for ($p = 1; $p <= NUM_PLAYERS; $p++): ?>
            <div class="player-card">
                <span class="badge">Igralec <?= $p ?></span>

                <label for="ime<?= $p ?>">Ime</label>
                <input type="text" id="ime<?= $p ?>" name="ime<?= $p ?>"
                       placeholder="npr. Ana" maxlength="60"
                       value="<?= $prev["ime{$p}"] ?? '' ?>">

                <label for="priimek<?= $p ?>">Priimek</label>
                <input type="text" id="priimek<?= $p ?>" name="priimek<?= $p ?>"
                       placeholder="npr. Novak" maxlength="60"
                       value="<?= $prev["priimek{$p}"] ?? '' ?>">

                <label for="naslov<?= $p ?>">Naslov</label>
                <input type="text" id="naslov<?= $p ?>" name="naslov<?= $p ?>"
                       placeholder="npr. Ljubljanska 5" maxlength="100"
                       value="<?= $prev["naslov{$p}"] ?? '' ?>">
            </div>
            <?php endfor; ?>
        </div>

        <button type="submit" class="btn-play">🎲 Vrzi Kocke!</button>
    </form>
</div>

<script>
function validateForm() {
    const fields = ['ime', 'priimek', 'naslov'];
    let ok = true;
    for (let p = 1; p <= <?= NUM_PLAYERS ?>; p++) {
        for (let f of fields) {
            const el = document.getElementById(f + p);
            if (!el || el.value.trim() === '') {
                el.classList.add('invalid');
                el.addEventListener('input', () => el.classList.remove('invalid'), { once: true });
                if (ok) el.focus();
                ok = false;
            }
        }
    }
    return ok;
}
</script>

</body>
</html>
