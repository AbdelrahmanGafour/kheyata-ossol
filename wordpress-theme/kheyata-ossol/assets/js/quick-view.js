/* المعاينة السريعة (Quick View): يفتح نافذة منبثقة بتفاصيل المنتج عبر AJAX دون مغادرة
   الصفحة الحالية، ويعتمد على PHP (inc/quick-view.php) لإرجاع نموذج WooCommerce الأصلي
   لـ"أضف للسلة" (بما في ذلك التنويعات) حتى تبقى كل حسابات السعر/المخزون صحيحة. */
(function () {
  'use strict';

  function els() {
    return {
      backdrop: document.getElementById('quick-view-backdrop'),
      modal: document.getElementById('quick-view-modal'),
      body: document.getElementById('quick-view-body'),
    };
  }

  function openModal() {
    var e = els();
    if (!e.modal) return;
    e.modal.classList.add('is-open');
    if (e.backdrop) e.backdrop.classList.add('is-open');
    document.body.classList.add('quick-view-open');
  }

  function closeModal() {
    var e = els();
    if (!e.modal) return;
    e.modal.classList.remove('is-open');
    if (e.backdrop) e.backdrop.classList.remove('is-open');
    document.body.classList.remove('quick-view-open');
    if (e.body) e.body.innerHTML = '';
  }

  function loadProduct(id) {
    var e = els();
    if (!e.body || typeof koSettings === 'undefined') return;
    e.body.innerHTML = '';
    openModal();

    var xhr = new XMLHttpRequest();
    xhr.open('POST', koSettings.ajaxUrl, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
      var html = '<p class="text-center" style="padding:40px;">تعذّر تحميل المنتج، حاول مرة أخرى.</p>';
      try {
        var res = JSON.parse(xhr.responseText);
        if (res && res.success && res.data && res.data.html) html = res.data.html;
      } catch (err) { /* تُعرض رسالة الخطأ الافتراضية أعلاه */ }
      e.body.innerHTML = html;
      if (typeof window.koInitVariationPills === 'function') window.koInitVariationPills();
    };
    xhr.onerror = function () {
      e.body.innerHTML = '<p class="text-center" style="padding:40px;">تعذّر تحميل المنتج، حاول مرة أخرى.</p>';
    };
    xhr.send('action=ko_quick_view&nonce=' + encodeURIComponent(koSettings.quickViewNonce) + '&product_id=' + encodeURIComponent(id));
  }

  document.addEventListener('click', function (e) {
    var qvBtn = e.target.closest('[data-quick-view]');
    if (qvBtn) {
      e.preventDefault();
      loadProduct(qvBtn.getAttribute('data-quick-view'));
      return;
    }
    if (e.target.closest('#quick-view-close') || e.target.id === 'quick-view-backdrop') {
      closeModal();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeModal();
  });
})();
