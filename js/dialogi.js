// Le Grand Dé — dialogi.js

document.getElementById('gumb-navodila').addEventListener('click', function () {
  Swal.fire({
    title: 'How to Play',
    html: `
      <div style="text-align:left;font-family:'Montserrat',sans-serif;font-size:0.85rem;line-height:1.8;color:#c8c0b0;">
        <p style="margin-bottom:12px;">Three guests take their seats at the table. Each round, all three roll the dice simultaneously.</p>
        <p style="margin-bottom:12px;"><strong style="color:#C9A84C;">Points</strong> are awarded equal to the sum of all dice rolled. The guest who accumulates the most points across all rounds claims the honour.</p>
        <p style="margin-bottom:12px;">In the event of a tie, fortune shall decide.</p>
        <hr style="border-color:rgba(201,168,76,0.2);margin:16px 0;">
        <p style="font-size:0.75rem;color:#8A8478;text-align:center;letter-spacing:0.1em;">MAY FORTUNE FAVOUR THE BOLD</p>
      </div>
    `,
    background: '#18181E',
    color: '#E8E0D0',
    confirmButtonText: 'Close',
    confirmButtonColor: '#8A6825',
    customClass: { title: 'swal-title', popup: 'swal-popup' },
  });
});

document.getElementById('gumb-krediti').addEventListener('click', function () {
  Swal.fire({
    title: 'Credits',
    html: `
      <div style="text-align:center;font-family:'Montserrat',sans-serif;font-size:0.82rem;line-height:2;color:#c8c0b0;">
        <p style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;color:#C9A84C;margin-bottom:8px;">Le Grand Dé</p>
        <p>Prestige Roll Edition</p>
        <p style="color:#8A8478;margin-top:12px;font-size:0.72rem;letter-spacing:0.15em;">A school project — Spletno programiranje</p>
        <p style="color:#8A8478;font-size:0.72rem;letter-spacing:0.15em;">Based on the original Dice Race by leonilc07</p>
      </div>
    `,
    background: '#18181E',
    color: '#E8E0D0',
    confirmButtonText: '✦ Dismiss',
    confirmButtonColor: '#8A6825',
  });
});
