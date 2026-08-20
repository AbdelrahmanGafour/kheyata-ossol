/* السلة المنزلقة (Mini Cart Drawer): تفتح عند الضغط على أيقونة السلة في الهيدر بدل
   الانتقال مباشرة لصفحة السلة، وتفتح تلقائيًا أيضًا بعد أي إضافة ناجحة لمنتج للسلة. */
(function () {
  'use strict';

  function initMiniCart() {
    var toggle = document.getElementById('mini-cart-toggle');
    var drawer = document.getElementById('mini-cart-drawer');
    var backdrop = document.getElementById('mini-cart-backdrop');
    var closeBtn = document.getElementById('mini-cart-close');
    if (!toggle || !drawer) return;
    if (toggle.getAttribute('data-mini-cart') !== '1') return;

    function open() {
      drawer.classList.add('is-open');
      if (backdrop) backdrop.classList.add('is-open');
      document.body.classList.add('mini-cart-open');
    }
    function close() {
      drawer.classList.remove('is-open');
      if (backdrop) backdrop.classList.remove('is-open');
      document.body.classList.remove('mini-cart-open');
    }

    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      open();
    });
    if (closeBtn) closeBtn.addEventListener('click', close);
    if (backdrop) backdrop.addEventListener('click', close);
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && drawer.classList.contains('is-open')) close();
    });

    if (window.jQuery) {
      jQuery(document.body).on('added_to_cart', function () { open(); });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMiniCart);
  } else {
    initMiniCart();
  }
})();
