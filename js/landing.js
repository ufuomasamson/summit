(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Nav: hide on scroll down, glass on scroll */
  var nav = document.querySelector('[data-nx-nav]');
  var hero = document.querySelector('[data-nx-hero]');
  var lastY = 0;

  function onScroll() {
    var y = window.scrollY || 0;
    if (!nav) return;
    nav.classList.toggle('is-scrolled', y > 24);
    if (y > 120 && y > lastY) nav.classList.add('is-hidden');
    else nav.classList.remove('is-hidden');
    lastY = y;
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* Mobile menu */
  var toggle = document.querySelector('[data-nx-menu-toggle]');
  var menu = document.querySelector('[data-nx-menu]');
  if (toggle && menu) {
    toggle.addEventListener('click', function () {
      var open = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!open));
      menu.hidden = open;
    });
    menu.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () {
        menu.hidden = true;
        toggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  /* Particles in hero */
  var particleHost = document.querySelector('[data-nx-particles]');
  if (particleHost && !reduceMotion) {
    for (var i = 0; i < 28; i++) {
      var p = document.createElement('span');
      p.className = 'nx-particle';
      p.style.left = Math.random() * 100 + '%';
      p.style.bottom = Math.random() * 20 + '%';
      p.style.animationDuration = 8 + Math.random() * 12 + 's';
      p.style.animationDelay = Math.random() * 8 + 's';
      p.style.width = p.style.height = 2 + Math.random() * 4 + 'px';
      particleHost.appendChild(p);
    }
  }

  /* Confetti dots on phone 1 */
  var confetti = document.querySelector('[data-nx-confetti]');
  if (confetti && !reduceMotion) {
    var colors = ['#D6001C', '#1A1A1A', '#ffffff', '#ff6b7d'];
    for (var c = 0; c < 12; c++) {
      var s = document.createElement('span');
      s.style.left = 10 + Math.random() * 80 + '%';
      s.style.bottom = '10%';
      s.style.background = colors[c % colors.length];
      s.style.animationDelay = (c * 0.35) + 's';
      confetti.appendChild(s);
    }
  }

  /* Balance tick */
  var bal = document.querySelector('[data-nx-balance]');
  if (bal && !reduceMotion) {
    var base = 12450;
    setInterval(function () {
      base += Math.floor(Math.random() * 900) + 100;
      bal.textContent = '$' + base.toLocaleString();
    }, 4200);
  }

  /* Parallax phones + mouse tilt */
  var phonesWrap = document.querySelector('[data-nx-phones]');
  if (phonesWrap && !reduceMotion) {
    var phones = phonesWrap.querySelectorAll('.nx-phone');
    window.addEventListener('mousemove', function (e) {
      var cx = (e.clientX / window.innerWidth - 0.5) * 2;
      var cy = (e.clientY / window.innerHeight - 0.5) * 2;
      phones.forEach(function (phone, idx) {
        var depth = (idx + 1) * 8;
        phone.style.animation = 'none';
        if (idx === 1) {
          phone.style.transform = 'translate(calc(-50% + ' + (cx * depth) + 'px), ' + (cy * depth) + 'px)';
        } else {
          var rot = idx === 0 ? -6 : 7;
          phone.style.transform = 'translate(' + (cx * depth) + 'px, ' + (cy * depth) + 'px) rotate(' + rot + 'deg)';
        }
      });
    }, { passive: true });

    window.addEventListener('scroll', function () {
      if (!hero) return;
      var rect = hero.getBoundingClientRect();
      var progress = Math.min(1, Math.max(0, -rect.top / Math.max(rect.height * 0.55, 1)));
      phones.forEach(function (phone, idx) {
        if (idx === 0) phone.style.left = (4 - progress * 8) + '%';
        if (idx === 2) phone.style.right = (2 - progress * 8) + '%';
        if (idx === 1) phone.style.scale = String(1 + progress * 0.08);
      });
    }, { passive: true });

    if (window.DeviceOrientationEvent) {
      window.addEventListener('deviceorientation', function (e) {
        if (e.gamma == null) return;
        var gx = (e.gamma || 0) / 45;
        var gy = ((e.beta || 45) - 45) / 90;
        phones.forEach(function (phone, idx) {
          var d = (idx + 1) * 5;
          phone.style.animation = 'none';
          if (idx === 1) {
            phone.style.transform = 'translate(calc(-50% + ' + (gx * d) + 'px), ' + (gy * d) + 'px)';
          } else {
            phone.style.transform = 'translate(' + (gx * d) + 'px, ' + (gy * d) + 'px) rotate(' + (idx === 0 ? -6 : 7) + 'deg)';
          }
        });
      }, { passive: true });
    }
  }

  /* Reveal on view */
  var reveals = document.querySelectorAll('.nx-reveal:not(.nx-reveal--instant)');
  document.querySelectorAll('.nx-reveal--instant, .nx-legal-wrap--visible').forEach(function (el) {
    el.classList.add('is-in');
  });
  if ('IntersectionObserver' in window) {
    var revObs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-in');
          revObs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    reveals.forEach(function (el) { revObs.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('is-in'); });
  }

  /* Counters */
  var counters = document.querySelectorAll('[data-nx-count]');
  if ('IntersectionObserver' in window) {
    var countObs = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        animateCount(entry.target);
        countObs.unobserve(entry.target);
      });
    }, { threshold: 0.4 });
    counters.forEach(function (el) { countObs.observe(el); });
  }

  function animateCount(el) {
    var target = parseFloat(el.getAttribute('data-target') || '0');
    var decimals = parseInt(el.getAttribute('data-decimals') || '0', 10);
    var prefix = el.getAttribute('data-prefix') || '';
    var suffix = el.getAttribute('data-suffix') || '';
    var duration = 1600;
    var start = performance.now();
    function frame(now) {
      var t = Math.min(1, (now - start) / duration);
      var eased = 1 - Math.pow(1 - t, 3);
      var val = target * eased;
      el.textContent = prefix + (decimals ? val.toFixed(decimals) : Math.round(val)) + suffix;
      if (t < 1) requestAnimationFrame(frame);
    }
    if (reduceMotion) {
      el.textContent = prefix + (decimals ? target.toFixed(decimals) : target) + suffix;
    } else {
      requestAnimationFrame(frame);
    }
  }

  /* Dashboard progress bars when visible */
  document.querySelectorAll('.nx-dash-card').forEach(function (card) {
    if (!('IntersectionObserver' in window)) {
      card.classList.add('is-in');
      return;
    }
    var o = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          e.target.classList.add('is-in');
          o.unobserve(e.target);
        }
      });
    }, { threshold: 0.3 });
    o.observe(card);
  });

  /* Experience sticky steps */
  var expRoot = document.querySelector('[data-nx-experience]');
  if (expRoot) {
    var steps = expRoot.querySelectorAll('[data-exp-step]');
    var screen = expRoot.querySelector('[data-exp-screen]');
    var screens = [
      { title: 'Dashboard', bal: '$48,200', lines: [['Available', '$28,420'], ['Invested', '$14,310'], ['Savings', '$5,470']] },
      { title: 'Transfer', bal: 'Send', lines: [['To', 'Any bank'], ['Speed', '0.8s'], ['Fee', 'Free*']] },
      { title: 'Cards', bal: 'Ruby', lines: [['Number', '•••• 8842'], ['Status', 'Active'], ['Limit', '$5,000']] },
      { title: 'Invest', bal: 'Grow', lines: [['Plans', '3 active'], ['ROI', '+8.2%'], ['Next', '12 days']] },
    ];
    function setExp(i) {
      steps.forEach(function (s) { s.classList.toggle('is-active', s.getAttribute('data-exp-step') === String(i)); });
      if (!screen) return;
      var data = screens[i] || screens[0];
      screen.setAttribute('data-screen', String(i));
      screen.innerHTML =
        '<div class="nx-phone__label">' + data.title + '</div>' +
        '<div class="nx-phone__balance">' + data.bal + '</div>' +
        data.lines.map(function (l) {
          return '<div class="nx-tx"><span>' + l[0] + '</span><strong>' + l[1] + '</strong></div>';
        }).join('') +
        '<div class="nx-chart-bars" style="height:80px;margin-top:24px"><i></i><i></i><i></i><i></i><i></i></div>';
    }
    if ('IntersectionObserver' in window) {
      var expObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) setExp(entry.target.getAttribute('data-exp-step'));
        });
      }, { threshold: 0.6, rootMargin: '-20% 0px -35% 0px' });
      steps.forEach(function (s) { expObs.observe(s); });
    }
  }

  /* Card tilt */
  document.querySelectorAll('[data-nx-tilt]').forEach(function (card) {
    card.addEventListener('mousemove', function (e) {
      if (reduceMotion) return;
      var r = card.getBoundingClientRect();
      var x = (e.clientX - r.left) / r.width - 0.5;
      var y = (e.clientY - r.top) / r.height - 0.5;
      card.style.transform =
        'translateY(-16px) rotateX(' + (-y * 14) + 'deg) rotateY(' + (x * 18) + 'deg) scale(1.04)';
    });
    card.addEventListener('mouseleave', function () {
      card.style.transform = '';
    });
  });

  /* Mobile bank screen swap */
  var mbRoot = document.querySelector('[data-nx-mobile-bank]');
  if (mbRoot) {
    var mbSteps = mbRoot.querySelectorAll('[data-mb-step]');
    var mbFrames = mbRoot.querySelectorAll('[data-mb-frame]');
    function setMb(i) {
      mbSteps.forEach(function (s) { s.classList.toggle('is-active', s.getAttribute('data-mb-step') === String(i)); });
      mbFrames.forEach(function (f) { f.classList.toggle('is-active', f.getAttribute('data-mb-frame') === String(i)); });
    }
    mbSteps.forEach(function (btn) {
      btn.addEventListener('click', function () { setMb(btn.getAttribute('data-mb-step')); });
    });
    var mbIndex = 0;
    if (!reduceMotion) {
      window.addEventListener('scroll', function () {
        var rect = mbRoot.getBoundingClientRect();
        if (rect.top > window.innerHeight || rect.bottom < 0) return;
        var p = 1 - (rect.bottom / (rect.height + window.innerHeight));
        var next = Math.min(mbFrames.length - 1, Math.max(0, Math.floor(p * mbFrames.length)));
        if (next !== mbIndex) {
          mbIndex = next;
          setMb(mbIndex);
        }
      }, { passive: true });
    }
  }

  /* Horizontal story: wheel → horizontal on desktop */
  var hscroll = document.querySelector('[data-nx-hscroll]');
  if (hscroll && window.matchMedia('(min-width: 900px)').matches) {
    hscroll.addEventListener('wheel', function (e) {
      if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
        hscroll.scrollLeft += e.deltaY;
        if (hscroll.scrollLeft > 0 && hscroll.scrollLeft < hscroll.scrollWidth - hscroll.clientWidth - 2) {
          e.preventDefault();
        }
      }
    }, { passive: false });
  }

  /* Footer wave canvas */
  var canvas = document.querySelector('[data-nx-footer-wave]');
  if (canvas && canvas.getContext && !reduceMotion) {
    var ctx = canvas.getContext('2d');
    var w, h, t = 0;
    function resize() {
      w = canvas.width = canvas.parentElement.clientWidth;
      h = canvas.height = 100;
    }
    function draw() {
      ctx.clearRect(0, 0, w, h);
      ctx.beginPath();
      for (var x = 0; x <= w; x++) {
        var y = h * 0.55 + Math.sin(x * 0.012 + t) * 12 + Math.sin(x * 0.03 + t * 1.4) * 6;
        if (x === 0) ctx.moveTo(x, y);
        else ctx.lineTo(x, y);
      }
      ctx.lineTo(w, h);
      ctx.lineTo(0, h);
      ctx.closePath();
      ctx.fillStyle = 'rgba(214,0,28,0.35)';
      ctx.fill();
      t += 0.03;
      requestAnimationFrame(draw);
    }
    resize();
    window.addEventListener('resize', resize);
    draw();
  }

  /* Magnetic buttons */
  document.querySelectorAll('.nx-btn--magnetic').forEach(function (btn) {
    btn.addEventListener('mousemove', function (e) {
      if (reduceMotion) return;
      var r = btn.getBoundingClientRect();
      var x = e.clientX - r.left - r.width / 2;
      var y = e.clientY - r.top - r.height / 2;
      btn.style.transform = 'translate(' + (x * 0.15) + 'px,' + (y * 0.2) + 'px) scale(1.03)';
    });
    btn.addEventListener('mouseleave', function () {
      btn.style.transform = '';
    });
  });
})();
