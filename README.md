# 🎲 Le Grand Dé — Prestige Roll

A luxury-themed PHP dice-race game. Three guests compete across multiple rounds of dice rolls; the one who accumulates the most points claims the honour of the evening.

Inspired by the original [Dice Race](https://github.com/leonilc07/dirka) project, redesigned with a prestige aesthetic: dark backgrounds, gold accents, champagne typography, and an elegant podium results screen.

---

## Requirements

- XAMPP (Apache + PHP)
- Browser (Chrome, Firefox, Edge…)

---

## Installation

1. Clone into your `htdocs` folder:

```
C:\xampp\htdocs\luxury-dice\
```

2. Start **Apache** in XAMPP Control Panel.

3. Open your browser and navigate to:

```
http://localhost/luxury-dice/index.php
```

---

## File Structure

```
luxury-dice/
├── index.php          ← Guest registry & game settings
├── igra.php           ← The roll — live game page
├── rezultati.php      ← Honours podium
│
├── css/
│   ├── style.css      ← Index page styles
│   ├── igra.css       ← Game page styles
│   └── rezultati.css  ← Results page styles
│
└── js/
    ├── dialogi.js     ← SweetAlert modals (rules, credits)
    └── sparkles.js    ← Gold particle animation on results page
```

---

## How It Works

### Page 1 — Guest Registry
- Enter names for 3 guests
- Choose dice count (1–6) and number of rounds (1–20)
- Click **Begin the Game**

### Page 2 — The Roll
- Each round: click **Roll Round X**
- PHP rolls dice for all three guests and displays results
- Elegant car tokens move along the track
- Round history is logged per guest
- When all rounds are complete: **View Final Results**

### Page 3 — Honours
- Guests placed on a tiered podium (gold / silver / bronze)
- Gold particle animation fills the screen
- Ties broken randomly
- **Play Again** resets the session

---

## Session Variables

| Variable | Contents |
|---|---|
| `$_SESSION['ime1']` | Guest 1 name |
| `$_SESSION['kocke']` | Dice per round |
| `$_SESSION['runde']` | Total rounds |
| `$_SESSION['tocke1']` | Guest 1 cumulative score |
| `$_SESSION['trenutna_runda']` | Current round number |
| `$_SESSION['zgodovina1']` | Guest 1 round-by-round history |

---

## Technologies

| Technology | Use |
|---|---|
| PHP | Game logic, sessions, dice rolls |
| HTML / CSS | Structure & luxury styling |
| JavaScript | SweetAlert dialogs, particle effects |
| Google Fonts | Cormorant Garamond + Montserrat |

---

## Credits

School project — Spletno programiranje  
Based on the original Dice Race by [leonilc07](https://github.com/leonilc07/dirka)
