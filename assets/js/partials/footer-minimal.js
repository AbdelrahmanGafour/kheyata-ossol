/* فوتر مبسّط (سطر الحقوق فقط، بدون تشتيت) — يُستخدم في صفحات إتمام الطلب
   وتأكيد الطلب وحسابي، للحفاظ على تركيز المستخدم أثناء هذه الخطوات. */
(function () {
  'use strict';

  var FOOTER_HTML =
    '<footer class="site-footer">' +
      '<div class="container">' +
        '<div class="footer-bottom" style="border-top:none; padding-top:0;">' +
          '<span>© <span data-year></span> للخياطة أصول. جميع الحقوق محفوظة.</span>' +
        '</div>' +
      '</div>' +
    '</footer>';

  document.currentScript.insertAdjacentHTML('afterend', FOOTER_HTML);
})();
