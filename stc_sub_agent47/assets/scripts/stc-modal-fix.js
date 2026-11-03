/**
 * Keep Bootstrap 3 modals usable with Bootstrap-4-ish theme CSS.
 * Chat modal (#stcReqCommModal) manages its own open/close — skip it here.
 */
(function ($) {
  if (!$ || !$.fn || !$.fn.modal) return;

  function isChatModal(el) {
    return el && (el.id === 'stcReqCommModal' || $(el).closest('#stcReqCommModal').length);
  }

  $(document).on('show.bs.modal', '.modal', function () {
    if (isChatModal(this)) return;
    $(this).addClass('show in');
  });

  $(document).on('shown.bs.modal', '.modal', function () {
    if (isChatModal(this)) return;
    $(this).addClass('show in').css({ display: 'block', opacity: 1 });
  });

  $(document).on('hidden.bs.modal', '.modal', function () {
    if (isChatModal(this)) return;
    $(this).removeClass('show in').css({ display: '', opacity: '' });
    if (!$('.modal.in:visible, .modal.show:visible').length) {
      $('body').removeClass('modal-open').css({ 'padding-right': '', overflow: '' });
      $('.modal-backdrop').not('.stc-req-comm-backdrop').remove();
    }
  });
})(window.jQuery);
