/* تأثير التمرير من قسم الـ Hero باستخدام anime.js v4 */

(function () {
  'use strict';

  function initHeroScrollEffect() {
    if (typeof anime === 'undefined') return;
    var hero = document.querySelector('.hero');
    var heroBg = document.querySelector('.hero-bg');
    var heroContent = document.querySelector('.hero-content');
    if (!hero || !heroBg) return;

    anime.animate(heroBg, {
      translateY: ['0%', '18%'],
      scale: [1, 1.12],
      opacity: [1, 0.35],
      ease: 'linear',
      autoplay: anime.onScroll({
        target: hero,
        enter: 'top top',
        leave: 'bottom top',
        sync: true
      })
    });

    if (heroContent) {
      anime.animate(heroContent, {
        translateY: ['0px', '-90px'],
        opacity: [1, 0],
        ease: 'linear',
        autoplay: anime.onScroll({
          target: hero,
          enter: 'top top',
          leave: 'center top',
          sync: true
        })
      });
    }

    var movers = document.querySelectorAll('[data-anime-move]');
    movers.forEach(function (el, i) {
      anime.animate(el, {
        translateY: [46, 0],
        opacity: [0, 1],
        ease: 'outExpo',
        duration: 900,
        delay: (i % 3) * 90,
        autoplay: anime.onScroll({
          target: el,
          enter: 'top 85%',
          once: true
        })
      });
    });

    var cueDot = document.querySelector('.scroll-cue .dot');
    if (cueDot) {
      anime.animate(cueDot, {
        translateY: [-34, 34],
        opacity: [1, 0.2, 1],
        duration: 1400,
        loop: true,
        ease: 'inOutSine'
      });
    }
  }

  function initIconHover() {
    if (typeof anime === 'undefined') return;
    document.querySelectorAll('.service-icon').forEach(function (icon) {
      icon.addEventListener('mouseenter', function () {
        anime.animate(icon, {
          rotate: [0, -12, 8, 0],
          duration: 500,
          ease: 'outElastic(1, .6)'
        });
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      initHeroScrollEffect();
      initIconHover();
    });
  } else {
    initHeroScrollEffect();
    initIconHover();
  }
})();
