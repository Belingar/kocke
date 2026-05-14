# 🎲 Le Grand Dé — Prestige Roll

PHP igra metanja kocke s tematiko prestiža in luksuza. Trije gosti tekmujejo v več krogih metanja kocke; tisti, ki zbere največ točk, si prisluži čast večera.

---

## Zahteve

* **XAMPP** (Apache + PHP)
* **Brskalnik** (Chrome, Firefox, Edge…)

---

## Namestitev

1. Kloniraj projekt v svojo `htdocs` mapo:
C:\xampp\htdocs\luxury-dice\

2. Zaženi **Apache** v XAMPP nadzorni plošči.
3. Odpri brskalnik in obišči:
http://localhost/luxury-dice/index.php


---

## Struktura datotek

luxury-dice/
├── index.php          ← Registracija gostov in nastavitve igre
├── igra.php           ← Met kocke — stran z igro v živo
├── rezultati.php      ← Stopničke in časti
│
├── css/
│   ├── style.css      ← Stili za začetno stran
│   ├── igra.css       ← Stili za igralno stran
│   └── rezultati.css  ← Stili za stran z rezultati
│
└── js/
├── dialogi.js     ← SweetAlert modalna okna (pravila, zasluge)
└── sparkles.js    ← Animacija zlatih delcev na strani z rezultati


---

## Kako deluje

### 1. stran — Registracija gostov
* Vnesite imena za 3 goste.
* Izberite število kock (1–6) in število krogov (1–20).
* Kliknite **Begin the Game** (Začni igro).

### 2. stran — Met kocke
* Vsak krog: kliknite **Roll Round X** (Vrzi za krog X).
* PHP vrže kocke za vse tri goste in prikaže rezultate.
* Elegantni avtomobilski žetoni se premikajo po progi.
* Zgodovina krogov se beleži za vsakega gosta posebej.
* Ko so vsi krogi zaključeni: **View Final Results** (Ogled končnih rezultatov).

### 3. stran — Časti
* Gosti se razvrstijo na stopničke (zlata / srebrna / bronasta).
* Animacija zlatih delcev napolni zaslon.
* Izenačeni rezultati se razrešijo naključno.
* **Play Again** (Igraj ponovno) ponastavi sejo.

---

## Sejne spremenljivke (Session Variables)

| Spremenljivka | Vsebina |
|---|---|
| `$_SESSION['ime1']` | Ime 1. gosta |
| `$_SESSION['kocke']` | Število kock na krog |
| `$_SESSION['runde']` | Skupno število krogov |
| `$_SESSION['tocke1']` | Skupne točke 1. gosta |
| `$_SESSION['trenutna_runda']` | Številka trenutnega kroga |
| `$_SESSION['zgodovina1']` | Zgodovina metov po krogih za 1. gosta |

---

## Tehnologije

* **PHP**: Logika igre, seje, meti kocke
* **HTML / CSS**: Struktura in luksuzno oblikovanje
* **JavaScript**: SweetAlert dialoška okna, učinki delcev
* **Google Fonts**: Cormorant Garamond + Montserrat
