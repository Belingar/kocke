// Le Grand Dé — sparkles.js
// Confetti burst on load + continuous gentle rain

(function () {
  const canvas = document.getElementById('sparkles');
  const ctx = canvas.getContext('2d');

  function resize() {
    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight;
  }
  resize();
  window.addEventListener('resize', resize);

  // Luxury colour palette: gold, champagne, white, rose-gold, silver
  const COLORS = [
    '#C9A84C', '#E2C97E', '#F5E6B0',
    '#ffffff', '#E8D5B7', '#D4AF8A',
    '#AEAEAE', '#F0D0C0'
  ];

  const SHAPES = ['rect', 'circle', 'ribbon'];

  const particles = [];

  function Particle(fromBurst) {
    this.fromBurst = fromBurst || false;
    this.init(fromBurst);
  }

  Particle.prototype.init = function (burst) {
    if (burst) {
      // Burst: launch from random point along top 30% of screen
      this.x = Math.random() * canvas.width;
      this.y = canvas.height * 0.1 + Math.random() * canvas.height * 0.2;
      this.vx = (Math.random() - 0.5) * 14;
      this.vy = (Math.random() * -12) - 4; // shoot upward
      this.gravity = 0.35;
    } else {
      // Continuous: fall from top
      this.x = Math.random() * canvas.width;
      this.y = -20;
      this.vx = (Math.random() - 0.5) * 2;
      this.vy = Math.random() * 2.5 + 1;
      this.gravity = 0.02;
    }

    this.color  = COLORS[Math.floor(Math.random() * COLORS.length)];
    this.shape  = SHAPES[Math.floor(Math.random() * SHAPES.length)];
    this.w      = Math.random() * 10 + 4;
    this.h      = this.shape === 'ribbon' ? Math.random() * 3 + 2 : this.w;
    this.rot    = Math.random() * Math.PI * 2;
    this.rotV   = (Math.random() - 0.5) * 0.18;
    this.alpha  = 1;
    this.fade   = Math.random() * 0.008 + 0.003;
    this.wobble = Math.random() * Math.PI * 2;
    this.wobbleV = Math.random() * 0.08 + 0.02;
    this.dead   = false;
  };

  Particle.prototype.update = function () {
    this.wobble += this.wobbleV;
    this.vx += Math.sin(this.wobble) * 0.05;
    this.vy += this.gravity;
    this.x  += this.vx;
    this.y  += this.vy;
    this.rot += this.rotV;
    this.alpha -= this.fade;

    if (this.alpha <= 0 || this.y > canvas.height + 30) {
      if (this.fromBurst) {
        this.dead = true;
      } else {
        this.init(false);
      }
    }
  };

  Particle.prototype.draw = function () {
    ctx.save();
    ctx.globalAlpha = Math.max(0, this.alpha);
    ctx.translate(this.x, this.y);
    ctx.rotate(this.rot);
    ctx.fillStyle = this.color;

    if (this.shape === 'circle') {
      ctx.beginPath();
      ctx.arc(0, 0, this.w / 2, 0, Math.PI * 2);
      ctx.fill();
    } else if (this.shape === 'ribbon') {
      ctx.beginPath();
      ctx.ellipse(0, 0, this.w / 2, this.h / 2, 0, 0, Math.PI * 2);
      ctx.fill();
    } else {
      ctx.fillRect(-this.w / 2, -this.h / 2, this.w, this.h);
    }

    ctx.restore();
  };

  // Initial burst: 220 particles fired at once
  for (let i = 0; i < 220; i++) {
    particles.push(new Particle(true));
  }

  // Continuous gentle rain: 60 pre-scattered pieces
  for (let i = 0; i < 60; i++) {
    const p = new Particle(false);
    p.y = Math.random() * canvas.height;
    particles.push(p);
  }

  let frameCount = 0;

  function loop() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    frameCount++;

    // Keep spawning new continuous pieces
    if (frameCount % 6 === 0) {
      particles.push(new Particle(false));
    }

    for (let i = particles.length - 1; i >= 0; i--) {
      const p = particles[i];
      p.update();
      p.draw();
      if (p.dead) particles.splice(i, 1);
    }

    requestAnimationFrame(loop);
  }

  loop();
})();
