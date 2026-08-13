/* سلوك عام للموقع: الهيدر، القائمة، Toast، وحركات GSAP */

(function () {
  'use strict';

  /* ---------- الهيدر عند التمرير ---------- */
  var header = document.querySelector('.site-header');
  function onScrollHeader() {
    if (!header) return;
    if (window.scrollY > 20) header.classList.add('is-scrolled');
    else header.classList.remove('is-scrolled');
  }
  window.addEventListener('scroll', onScrollHeader, { passive: true });
  onScrollHeader();

  /* ---------- قائمة الموبايل ---------- */
  var navToggle = document.querySelector('.nav-toggle');
  var mobileNav = document.querySelector('.mobile-nav');
  if (navToggle && mobileNav) {
    navToggle.addEventListener('click', function () {
      mobileNav.classList.toggle('is-open');
      document.body.classList.toggle('nav-open');
      var icon = navToggle.querySelector('i');
      if (icon) icon.className = mobileNav.classList.contains('is-open') ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
    });
    mobileNav.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () {
        mobileNav.classList.remove('is-open');
        document.body.classList.remove('nav-open');
        var icon = navToggle.querySelector('i');
        if (icon) icon.className = 'fa-solid fa-bars';
      });
    });
  }

  /* ---------- سنة الفوتر ---------- */
  document.querySelectorAll('[data-year]').forEach(function (el) {
    el.textContent = new Date().getFullYear();
  });

  /* ---------- Toast ---------- */
  window.showToast = function (message, iconClass) {
    var toast = document.querySelector('.toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.className = 'toast';
      toast.innerHTML = '<i class="' + (iconClass || 'fa-solid fa-circle-check') + '"></i><span></span>';
      document.body.appendChild(toast);
    }
    toast.querySelector('i').className = iconClass || 'fa-solid fa-circle-check';
    toast.querySelector('span').textContent = message;
    toast.classList.add('is-visible');
    clearTimeout(toast._t);
    toast._t = setTimeout(function () { toast.classList.remove('is-visible'); }, 2600);
  };

  /* ---------- حركات GSAP عند التمرير ---------- */

  /* يكشف عناصر [data-reveal] الجديدة فقط (تُستدعى أيضًا بعد أي عرض ديناميكي للمحتوى
     عبر renderInto، لأن العناصر المضافة بعد إعداد GSAP الأولي لن تُكتشف تلقائيًا) */
  window.revealNewElements = function () {
    if (typeof gsap === 'undefined') return;
    gsap.utils.toArray('[data-reveal]:not(.gsap-reveal-bound)').forEach(function (el, i) {
      el.classList.add('gsap-reveal-bound');
      gsap.fromTo(el,
        { opacity: 0, y: 36 },
        {
          opacity: 1,
          y: 0,
          duration: 0.8,
          ease: 'power2.out',
          delay: (i % 4) * 0.08,
          scrollTrigger: {
            trigger: el,
            start: 'top 88%',
            toggleActions: 'play none none none'
          }
        }
      );
    });
  };

  function initGsapReveal() {
    if (typeof gsap === 'undefined') return;
    gsap.registerPlugin(ScrollTrigger);

    window.revealNewElements();

    /* عدّاد الإحصائيات */
    gsap.utils.toArray('[data-counter]').forEach(function (el) {
      var target = parseFloat(el.getAttribute('data-counter'));
      var decimals = (el.getAttribute('data-counter').split('.')[1] || '').length;
      var counter = { val: 0 };
      var runCounter = function () {
        gsap.to(counter, {
          val: target,
          duration: 1.6,
          ease: 'power1.out',
          onUpdate: function () {
            el.textContent = counter.val.toFixed(decimals);
          }
        });
      };
      /* شريط إحصائيات الهيرو محتوى ظاهر فورًا (وليس عنصر "يُكتشف عند التمرير")،
         فهو دائمًا محسوب على أنه في نطاق الرؤية بغض النظر عن ارتفاع الشاشة */
      if (el.closest('.hero-stats-bar')) {
        runCounter();
      } else {
        ScrollTrigger.create({
          trigger: el,
          start: 'top bottom',
          once: true,
          onEnter: runCounter
        });
      }
    });

    /* رفع خفيف لعنوان الهيدر عند التحميل */
    gsap.from('.header-inner > *', {
      opacity: 0,
      y: -14,
      duration: 0.6,
      stagger: 0.08,
      ease: 'power2.out'
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGsapReveal);
  } else {
    initGsapReveal();
  }

  /* إعادة حساب مواضع ScrollTrigger بعد اكتمال تحميل الصفحة (فيديو/خطوط قد تُغيّر الارتفاعات)
     حتى تُكتشف صحيحًا العناصر التي تكون ظاهرة أصلاً فوق الطية دون الحاجة لتمرير فعلي */
  window.addEventListener('load', function () {
    if (typeof ScrollTrigger !== 'undefined') ScrollTrigger.refresh();
  });
})();
