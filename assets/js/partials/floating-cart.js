/* شريط السلة العائم: يظهر أسفل الشاشة تلقائيًا بمجرد وجود منتج في السلة، ويعرض
   عدد المنتجات وإجمالي السلة لحظيًا، مع نبضة بصرية خفيفة عند إضافة منتج جديد. */
(function () {
  'use strict';

  var BAR_HTML =
    '<div class="floating-cart" id="floating-cart">' +
      '<a href="cart.html" class="floating-cart-inner" aria-label="عرض السلة">' +
        '<span class="floating-cart-icon">' +
          '<i class="fa-solid fa-cart-shopping"></i>' +
          '<span class="floating-cart-count" id="floating-cart-count">0</span>' +
        '</span>' +
        '<span class="floating-cart-info">' +
          '<strong id="floating-cart-label">0 منتج بالسلة</strong>' +
          '<span id="floating-cart-total">0 ج.م</span>' +
        '</span>' +
        '<span class="floating-cart-cta"><i class="fa-solid fa-arrow-left"></i></span>' +
      '</a>' +
    '</div>';

  function init() {
    document.body.insertAdjacentHTML('beforeend', BAR_HTML);
    var bar = document.getElementById('floating-cart');
    var countEl = document.getElementById('floating-cart-count');
    var labelEl = document.getElementById('floating-cart-label');
    var totalEl = document.getElementById('floating-cart-total');
    var lastCount = 0;

    function update(withPulse) {
      var count = getCartCount();
      countEl.textContent = count;
      labelEl.textContent = count + ' منتج بالسلة';
      totalEl.textContent = formatPrice(getCartTotal());

      var visible = count > 0;
      bar.classList.toggle('is-visible', visible);
      document.body.classList.toggle('has-floating-cart', visible);

      if (withPulse && count > lastCount) {
        bar.classList.remove('pulse');
        void bar.offsetWidth; /* إعادة تشغيل الأنيميشن */
        bar.classList.add('pulse');
      }
      lastCount = count;
    }

    update(false);
    window.addEventListener('ko:cart-updated', function () { update(true); });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
