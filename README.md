# 🎲 Le Grand Dé — Prestige Roll

A luxury-themed PHP dice-race game. Three guests compete by rolling dice each turn; the first to reach 100 points claims the honour of the evening. If two or three cross 100 on the same roll, they share the honour.

A redesigned with a prestige aesthetic: dark backgrounds, gold accents, champagne typography, and an elegant podium results screen.

---

## Requirements

- XAMPP (Apache + PHP)
- Browser (Chrome, Firefox, Edge…)

---

## Installation

1. Clone into your `htdocs` folder:

```
C:\xampp\htdocs\kocke\
```

2. Start **Apache** in XAMPP Control Panel.

3. Open your browser and navigate to:

```
http://localhost/kocke/index.php
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
- Choose dice count (1–6) per roll
- Goal is fixed: first to 100 points wins
- Click **Begin the Game**

### Page 2 — The Roll
- Click **Roll the Dice** each turn
- PHP rolls dice for all three guests and displays results
- Each guest's goblet fills toward the 100-point goal
- Roll history is logged per guest
- The first guest to reach 100 ends the game: **View Final Results**

### Page 3 — Honours
- Guests placed on a tiered podium (gold / silver / bronze)
- Gold particle animation fills the screen
- Ties at 100+ become shared (co-winner) honours
- **Play Again** resets the session

---

## Session Variables

| Variable | Contents |
|---|---|
| `$_SESSION['ime1']` | Guest 1 name |
| `$_SESSION['kocke']` | Dice per round |
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
Created by Belingar Urh
