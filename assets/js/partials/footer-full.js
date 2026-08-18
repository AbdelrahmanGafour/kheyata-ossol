/* الفوتر الكامل (4 أعمدة + زر واتساب عائم) — يُستخدم في صفحات المحتوى الرئيسية
   (الرئيسية، من نحن، المتجر، صفحة المنتج، السلة، تواصل معنا). */
(function () {
  'use strict';

  var FOOTER_HTML =
    '<footer class="site-footer">' +
      '<div class="container">' +
        '<div class="footer-top">' +
          '<div class="footer-col">' +
            '<div class="footer-brand"><img src="assets/images/logo.webp" alt="شعار للخياطة أصول"><strong>للخياطة أصول</strong></div>' +
            '<p>المنصة الأولى المتكاملة في عالم الخياطة: متجر أدوات، أكاديمية تعليم احترافي، ودعم فني حقيقي بعد البيع.</p>' +
            '<div class="social-row">' +
              '<a href="#" aria-label="فيسبوك"><i class="fa-brands fa-facebook-f"></i></a>' +
              '<a href="#" aria-label="إنستجرام"><i class="fa-brands fa-instagram"></i></a>' +
              '<a href="#" aria-label="تيك توك"><i class="fa-brands fa-tiktok"></i></a>' +
              '<a href="https://wa.me/201090872716" aria-label="واتساب"><i class="fa-brands fa-whatsapp"></i></a>' +
            '</div>' +
          '</div>' +
          '<div class="footer-col">' +
            '<h4>روابط سريعة</h4>' +
            '<ul>' +
              '<li><a href="index.html">الرئيسية</a></li>' +
              '<li><a href="store.html">المتجر</a></li>' +
              '<li><a href="about.html">من نحن</a></li>' +
              '<li><a href="contact.html">تواصل معنا</a></li>' +
            '</ul>' +
          '</div>' +
          '<div class="footer-col">' +
            '<h4>حسابي</h4>' +
            '<ul>' +
              '<li><a href="login.html">تسجيل الدخول</a></li>' +
              '<li><a href="register.html">حساب جديد</a></li>' +
              '<li><a href="cart.html">سلة المشتريات</a></li>' +
              '<li><a href="account.html">طلباتي</a></li>' +
            '</ul>' +
          '</div>' +
          '<div class="footer-col">' +
            '<h4>تواصل معنا</h4>' +
            '<ul class="footer-contact">' +
              '<li><i class="fa-solid fa-phone"></i><span>01090872716</span></li>' +
              '<li><i class="fa-brands fa-whatsapp"></i><span>01090872716</span></li>' +
              '<li><i class="fa-solid fa-envelope"></i><span>info@khayataosol.com</span></li>' +
              '<li><i class="fa-solid fa-location-dot"></i><span>شحن لجميع محافظات مصر</span></li>' +
            '</ul>' +
          '</div>' +
        '</div>' +
        '<div class="footer-bottom">' +
          '<span>© <span data-year></span> للخياطة أصول. جميع الحقوق محفوظة.</span>' +
          '<span>صُنع بحب لأهل الأصول</span>' +
        '</div>' +
      '</div>' +
    '</footer>' +
    '<a href="https://wa.me/201090872716" class="whatsapp-float" target="_blank" rel="noopener" aria-label="تواصل عبر واتساب"><i class="fa-brands fa-whatsapp"></i></a>';

  document.currentScript.insertAdjacentHTML('afterend', FOOTER_HTML);
})();
