/* الهيدر الموحّد لكل صفحات الموقع — مصدر واحد بدل تكراره في كل ملف HTML.
   يُدرَج مباشرةً أثناء تحليل الصفحة (بدون fetch) لتفادي أي وميض/تأخر في الظهور. */
(function () {
  'use strict';

  var HEADER_HTML =
    '<header class="site-header">' +
      '<div class="container header-inner">' +
        '<a href="index.html" class="brand">' +
          '<img src="assets/images/logo.webp" alt="شعار للخياطة أصول">' +
          '<span class="brand-text"><strong>للخياطة أصول</strong><span>أدوات خياطة + تعليم احترافي</span></span>' +
        '</a>' +
        '<nav class="main-nav">' +
          '<ul>' +
            '<li><a href="index.html" data-nav="index.html">الرئيسية</a></li>' +
            '<li><a href="store.html" data-nav="store.html">المتجر</a></li>' +
            '<li><a href="about.html" data-nav="about.html">من نحن</a></li>' +
            '<li><a href="contact.html" data-nav="contact.html">تواصل معنا</a></li>' +
          '</ul>' +
        '</nav>' +
        '<div class="header-actions">' +
          '<a href="login.html" class="icon-btn" data-auth-account-link title="تسجيل الدخول"><i class="fa-solid fa-user"></i></a>' +
          '<a href="cart.html" class="icon-btn"><i class="fa-solid fa-cart-shopping"></i><span class="cart-badge">0</span></a>' +
          '<button class="nav-toggle" aria-label="القائمة"><i class="fa-solid fa-bars"></i></button>' +
        '</div>' +
      '</div>' +
      '<div class="mobile-nav">' +
        '<ul>' +
          '<li><a href="index.html">الرئيسية <i class="fa-solid fa-house"></i></a></li>' +
          '<li><a href="store.html">المتجر <i class="fa-solid fa-store"></i></a></li>' +
          '<li><a href="about.html">من نحن <i class="fa-solid fa-circle-info"></i></a></li>' +
          '<li><a href="contact.html">تواصل معنا <i class="fa-solid fa-envelope"></i></a></li>' +
          '<li><a href="login.html" data-auth-account-link>حسابي <i class="fa-solid fa-user"></i></a></li>' +
        '</ul>' +
      '</div>' +
    '</header>';

  var thisScript = document.currentScript;
  thisScript.insertAdjacentHTML('afterend', HEADER_HTML);

  /* تحديد الرابط النشط تلقائيًا من اسم الصفحة الحالية */
  var currentPage = (location.pathname.split('/').pop() || 'index.html');
  var activeLink = document.querySelector('.main-nav a[data-nav="' + currentPage + '"]');
  if (activeLink) activeLink.classList.add('active');
})();
