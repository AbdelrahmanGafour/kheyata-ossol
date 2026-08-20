/* كاروسيل أفقي: أزرار تنقل + سحب بالماوس لسطح المكتب (اللمس يعمل بشكل طبيعي عبر التمرير الأصلي للمتصفح) */

(function () {
  'use strict';

  var desktopQuery = window.matchMedia('(min-width: 900px)');

  function initCarousels() {
    document.querySelectorAll('.carousel-wrap').forEach(function (wrap) {
      var track = wrap.querySelector('.carousel-track');
      var prevBtn = wrap.querySelector('.carousel-arrow-prev');
      var nextBtn = wrap.querySelector('.carousel-arrow-next');
      if (!track) return;

      function itemStep() {
        var first = track.firstElementChild;
        if (!first) return 0;
        var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
        return first.getBoundingClientRect().width + gap;
      }

      function scrollByDir(dir) {
        if (desktopQuery.matches) {
          track.scrollBy({ left: track.clientWidth * 0.8 * dir, behavior: 'smooth' });
          return;
        }
        var step = itemStep();
        if (!step) return;
        track.scrollBy({ left: step * dir, behavior: 'smooth' });
      }

      if (nextBtn) nextBtn.addEventListener('click', function () { scrollByDir(-1); });
      if (prevBtn) prevBtn.addEventListener('click', function () { scrollByDir(1); });

      var isDragging = false;
      var startX = 0;
      var startScroll = 0;
      var moved = false;

      track.addEventListener('mousedown', function (e) {
        isDragging = true;
        moved = false;
        startX = e.clientX;
        startScroll = track.scrollLeft;
      });

      document.addEventListener('mousemove', function (e) {
        if (!isDragging) return;
        var delta = e.clientX - startX;
        if (!moved && Math.abs(delta) > 4) {
          moved = true;
          track.classList.add('is-dragging');
        }
        if (moved) track.scrollLeft = startScroll + delta;
      });

      document.addEventListener('mouseup', function () {
        if (!isDragging) return;
        isDragging = false;
        track.classList.remove('is-dragging');
      });

      track.addEventListener('click', function (e) {
        if (moved) {
          e.preventDefault();
          e.stopPropagation();
          moved = false;
        }
      }, true);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCarousels);
  } else {
    initCarousels();
  }

  /* إعادة تهيئة الكاروسيل لأي مسارات تُضاف لاحقًا (مثل حلقات WooCommerce المُحمَّلة عبر AJAX). */
  if (window.jQuery) {
    jQuery(document.body).on('wc_fragments_refreshed', initCarousels);
  }
})();
