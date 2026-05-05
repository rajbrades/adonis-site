(function () {
  'use strict';

  var KEY = 'adonis_gender';
  var COOKIE_DAYS = 365;
  var classes = ['men', 'women', 'neutral'];

  function read() {
    try { return localStorage.getItem(KEY); } catch (_) { return null; }
  }

  function write(value) {
    try { localStorage.setItem(KEY, value); } catch (_) {}
    var d = new Date();
    d.setTime(d.getTime() + COOKIE_DAYS * 864e5);
    document.cookie = KEY + '=' + value + '; expires=' + d.toUTCString() + '; path=/; SameSite=Lax';
  }

  function apply(value) {
    var body = document.body;
    classes.forEach(function (c) { body.classList.remove(c); });
    body.classList.add(value);
    document.querySelectorAll('[data-gender]').forEach(function (btn) {
      btn.setAttribute('aria-pressed', String(btn.dataset.gender === value));
    });
  }

  function init() {
    var stored = read();
    var fromBody = document.body.classList.contains('women') ? 'women'
                 : document.body.classList.contains('neutral') ? 'neutral'
                 : 'men';
    var current = stored || fromBody;
    apply(current);
    if (stored !== current) write(current);

    document.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-gender]');
      if (!btn) return;
      var next = btn.dataset.gender;
      if (next !== 'men' && next !== 'women') return;
      apply(next);
      write(next);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
