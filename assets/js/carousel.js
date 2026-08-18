/* كاروسيل أفقي: أزرار تنقل + سحب بالماوس لسطح المكتب (اللمس يعمل بشكل طبيعي عبر التمرير الأصلي للمتصفح) */

(function () {
  'use strict';

  function initCarousels() {
    document.querySelectorAll('.carousel-wrap').forEach(function (wrap) {
      var track = wrap.querySelector('.carousel-track');
      var prevBtn = wrap.querySelector('.carousel-arrow-prev');
      var nextBtn = wrap.querySelector('.carousel-arrow-next');
      if (!track) return;

      /* المسار عرضه مطابق تمامًا لعرض البطاقة الواحدة (انظر style.css)، لذا التنقل
         يعني تحريك المسار بمقدار عرض بطاقة واحدة + الفراغ بينها كل ضغطة */
      function itemStep() {
        var first = track.firstElementChild;
        if (!first) return 0;
        var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
        return first.getBoundingClientRect().width + gap;
      }

      function scrollByDir(dir) {
        var step = itemStep();
        if (!step) return;
        track.scrollBy({ left: step * dir, behavior: 'smooth' });
      }

      /* في RTL: scrollLeft يصبح سالبًا كلما تحركنا للأمام (يسارًا) داخل المحتوى */
      if (nextBtn) nextBtn.addEventListener('click', function () { scrollByDir(-1); });
      if (prevBtn) prevBtn.addEventListener('click', function () { scrollByDir(1); });

      /* سحب بالماوس (drag-to-swipe) لسطح المكتب فقط — اللمس على الموبايل/التابلت
         يستخدم التمرير الأصلي للمتصفح لأنه أنعم وله زخم (momentum) حقيقي.
         نعتمد هنا على أحداث الماوس العادية (بدون Pointer Capture) حتى لا نتعارض
         مع سلوك النقر الافتراضي على روابط المنتجات/التصنيفات داخل الكاروسيل. */
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
        /* لا نعطّل pointer-events على العناصر الفرعية (ما يمنع فتح الرابط لاحقًا)
           إلا بعد تأكّد وجود سحب فعلي، حتى لا تنكسر النقرات العادية غير المصحوبة بسحب */
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

      /* منع فتح رابط المنتج/التصنيف عن طريق الخطأ بعد سحب فعلي */
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
})();
