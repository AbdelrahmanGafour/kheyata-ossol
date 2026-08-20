/* سلوك صفحات المتجر: لوحة الفلاتر المنزلقة، التنقل بين التصنيفات، وشريط السعر،
   بالإضافة إلى منتقي التنويعات (المقاسات) على هيئة أزرار Pill فوق قائمة WooCommerce الأصلية. */
(function ($) {
  'use strict';

  function initFiltersDrawer() {
    var toggle = document.getElementById('filter-toggle');
    var panel = document.getElementById('filters-card');
    var backdrop = document.getElementById('filters-backdrop');
    var closeBtn = document.getElementById('filters-close');
    if (!toggle || !panel) return;

    function openDrawer() {
      panel.classList.add('is-open');
      backdrop.classList.add('is-open');
      document.body.classList.add('filters-open');
      toggle.setAttribute('aria-expanded', 'true');
    }
    function closeDrawer() {
      panel.classList.remove('is-open');
      backdrop.classList.remove('is-open');
      document.body.classList.remove('filters-open');
      toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', openDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    if (backdrop) backdrop.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && panel.classList.contains('is-open')) closeDrawer();
    });
  }

  function initCategoryNav() {
    document.querySelectorAll('input[name="ko-category-nav"]').forEach(function (input) {
      input.addEventListener('change', function () {
        if (input.value) window.location.href = input.value;
      });
    });
  }

  function initPriceRangeDisplay() {
    var range = document.getElementById('price-range');
    var display = document.getElementById('price-range-value');
    if (!range || !display) return;
    range.addEventListener('input', function () {
      display.textContent = range.value;
    });
  }

  /* منتقي التنويعات كأزرار Pill فوق قائمة <select> الأصلية الخاصة بـ WooCommerce،
     بحيث تبقى منطق حساب السعر/التوفر لكل تركيبة (variation) من WooCommerce نفسه دون إعادة بنائه. */
  function initVariationPills() {
    document.querySelectorAll('.variations select').forEach(function (select) {
      if (select.dataset.koPilled) return;
      select.dataset.koPilled = '1';

      var wrap = document.createElement('div');
      wrap.className = 'variation-options';

      Array.prototype.forEach.call(select.options, function (opt) {
        if (!opt.value) return;
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'variation-option';
        btn.textContent = opt.textContent;
        btn.addEventListener('click', function () {
          select.value = opt.value;
          $(select).trigger('change');
          wrap.querySelectorAll('.variation-option').forEach(function (b) { b.classList.remove('is-selected'); });
          btn.classList.add('is-selected');
        });
        wrap.appendChild(btn);
      });

      select.insertAdjacentElement('afterend', wrap);
      select.classList.add('screen-reader-text');
    });
  }

  /* شريط "أضف للسلة" الثابت أسفل الشاشة على صفحة المنتج (موبايل/تابلت فقط) — يظهر بمجرد
     خروج نموذج الإضافة للسلة الأصلي عن نطاق الرؤية، ويُفعّل نفس الزر الأصلي عند الضغط
     عليه (يشمل التنويعة المختارة تلقائيًا) بدل إعادة بناء منطق الإضافة للسلة من جديد. */
  function initStickyCartBar() {
    var bar = document.getElementById('sticky-add-to-cart');
    var anchor = document.querySelector('.single_add_to_cart_button');
    if (!bar || !anchor || typeof IntersectionObserver === 'undefined') return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        bar.classList.toggle('is-visible', !entry.isIntersecting);
      });
    }, { rootMargin: '0px 0px -10% 0px' });
    observer.observe(anchor);

    var trigger = bar.querySelector('.sticky-add-to-cart-btn');
    if (trigger) {
      trigger.addEventListener('click', function () {
        var liveBtn = document.querySelector('.single_add_to_cart_button');
        if (liveBtn) liveBtn.click();
      });
    }
  }

  function initAll() {
    initFiltersDrawer();
    initCategoryNav();
    initPriceRangeDisplay();
    initVariationPills();
    initStickyCartBar();
  }

  document.addEventListener('DOMContentLoaded', initAll);

  /* إعادة تشغيل منتقي التنويعات إذا استُبدل نموذج المنتج عبر AJAX (نادر لكن ممكن مع بعض الإضافات). */
  $(document.body).on('wc_variation_form', initVariationPills);

  /* يُستدعى من ملف quick-view.js بعد تحميل محتوى المنتج داخل النافذة المنبثقة، حتى تُبنى
     أزرار Pill لأي منتج متغيّر يُعرض هناك أيضًا (النافذة ليست موجودة عند تحميل الصفحة أصلاً). */
  window.koInitVariationPills = initVariationPills;
})(jQuery);
