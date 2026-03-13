/**
 * Sidebar toggle with localStorage persistence.
 * Load this BEFORE main.js so our handler runs first (capture phase).
 */
(function() {
  var KEY = 'stc_sidebar_closed';

  function getContainer() { return document.querySelector('.app-container'); }
  function getBtns() { return document.querySelectorAll('.close-sidebar-btn'); }

  function applyClosed(closed) {
    var c = getContainer();
    if (!c) return;
    var btns = getBtns();
    if (closed) {
      c.classList.add('closed-sidebar');
      for (var i = 0; i < btns.length; i++) btns[i].classList.add('is-active');
    } else {
      c.classList.remove('closed-sidebar');
      for (var i = 0; i < btns.length; i++) btns[i].classList.remove('is-active');
    }
  }

  function toggle() {
    var c = getContainer();
    if (!c) return;
    var closed = c.classList.contains('closed-sidebar');
    applyClosed(!closed);
    try { localStorage.setItem(KEY, closed ? '0' : '1'); } catch (e) {}
  }

  function restore() {
    try {
      if (localStorage.getItem(KEY) === '1') applyClosed(true);
    } catch (e) {}
  }

  function isCloseBtn(el) {
    while (el && el !== document) {
      if (el.classList && el.classList.contains('close-sidebar-btn')) return true;
      el = el.parentNode;
    }
    return false;
  }

  document.addEventListener('click', function(e) {
    if (isCloseBtn(e.target)) {
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();
      toggle();
    }
  }, true);

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      restore();
    });
  } else {
    restore();
  }
})();
