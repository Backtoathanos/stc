/**
 * Directory-wide modal fix for stc_sub_agent47.
 *
 * Root cause of close "blink":
 * we synced both .show and .in, but Bootstrap 4 only removes .show on hide,
 * so .in kept opacity:1 until a hard snap.
 *
 * Rules:
 * - BS4: use .show only (strip leftover .in on show/hide)
 * - BS3: use .in (and .show for theme CSS)
 * - Never strip .show yourself on hide — let Bootstrap finish the fade
 * - Skip #stcReqCommModal (custom open/close)
 */
(function ($) {
  if (!$ || !$.fn || !$.fn.modal || window.__stcModalFixBound) return;
  window.__stcModalFixBound = true;

  function isChatModal(el) {
    return !!(el && el.id === 'stcReqCommModal');
  }

  function bsMajor() {
    try {
      var v = ($.fn.modal.Constructor && $.fn.modal.Constructor.VERSION) || '';
      return parseInt(String(v).charAt(0), 10) || 4;
    } catch (e) {
      return 4;
    }
  }

  var major = bsMajor();

  $(document).on('show.bs.modal', '.modal', function () {
    if (isChatModal(this)) return;
    var $m = $(this);
    if (major >= 4) {
      $m.addClass('show').removeClass('in');
    } else {
      $m.addClass('in show');
    }
  });

  $(document).on('hide.bs.modal', '.modal', function () {
    if (isChatModal(this)) return;
    // Only clear leftover .in so fade-out via .show removal can run
    $(this).removeClass('in');
  });

  $(document).on('hidden.bs.modal', '.modal', function () {
    if (isChatModal(this)) return;
    var $m = $(this);
    $m.removeClass('in show');
    // Clear only our inline tweaks if any; do not fight Bootstrap display
    if ($m[0] && $m[0].style) {
      $m[0].style.removeProperty('opacity');
    }
    setTimeout(function () {
      if (!$('.modal.show:visible, .modal.in:visible').length) {
        $('body').removeClass('modal-open').css({ 'padding-right': '', overflow: '' });
        $('.modal-backdrop').not('.stc-req-comm-backdrop').remove();
      }
    }, 20);
  });
})(window.jQuery);
