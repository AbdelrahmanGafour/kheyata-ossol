/* كاروسيل أفقي بسيط (سحب/تمرير طبيعي + أزرار تنقل) */

(function () {
  'use strict';

  function initCarousels() {
    document.querySelectorAll('.carousel-wrap').forEach(function (wrap) {
      var track = wrap.querySelector('.carousel-track');
      var prevBtn = wrap.querySelector('.carousel-arrow-prev');
      var nextBtn = wrap.querySelector('.carousel-arrow-next');
      if (!track) return;

      function scrollByDir(dir) {
        track.scrollBy({ left: track.clientWidth * 0.8 * dir, behavior: 'smooth' });
      }

      /* في RTL: scrollLeft يصبح سالبًا كلما تحركنا للأمام (يسارًا) داخل المحتوى */
      if (nextBtn) nextBtn.addEventListener('click', function () { scrollByDir(-1); });
      if (prevBtn) prevBtn.addEventListener('click', function () { scrollByDir(1); });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCarousels);
  } else {
    initCarousels();
  }
})();
