/* منطق صفحة إتمام الطلب (الدفع عند الاستلام) */

(function () {
  'use strict';

  function miniItemHTML(item) {
    var name = item.product.name + (item.variationLabel ? ' (' + item.variationLabel + ')' : '');
    return (
      '<div class="mini-item">' +
        '<span>' + name + ' × ' + item.qty + '</span>' +
        '<span>' + formatPrice(item.price * item.qty) + '</span>' +
      '</div>'
    );
  }

  /* تعبئة قائمة المحافظات الـ27 من assets/js/shipping-data.js، كل محافظة تحمل رسوم شحنها
     الخاصة عبر data-fee حتى تُحتسب تلقائيًا في ملخص الطلب عند الاختيار */
  function populateGovernorates() {
    var select = document.getElementById('governorate');
    (window.EGYPT_GOVERNORATES || []).forEach(function (g) {
      var opt = document.createElement('option');
      opt.value = g.name;
      opt.textContent = g.name;
      opt.setAttribute('data-fee', g.fee);
      select.appendChild(opt);
    });
  }

  /* رسوم الشحن: مجانية فوق حد الشحن المجاني (FREE_SHIPPING_THRESHOLD من cart.js)،
     وإلا فرسوم المحافظة المختارة، أو الرسوم الافتراضية قبل اختيار محافظة */
  function currentShippingCost() {
    var subtotal = getCartSubtotal();
    if (subtotal === 0) return 0;
    if (subtotal >= FREE_SHIPPING_THRESHOLD) return 0;
    var select = document.getElementById('governorate');
    var opt = select.options[select.selectedIndex];
    var fee = opt && opt.getAttribute('data-fee');
    return fee ? parseFloat(fee) : SHIPPING_COST;
  }

  function currentTotal() {
    return getCartSubtotal() + currentShippingCost();
  }

  function updateShippingHint() {
    var select = document.getElementById('governorate');
    var opt = select.options[select.selectedIndex];
    var hint = document.getElementById('governorate-fee-hint');
    var fee = opt && opt.getAttribute('data-fee');
    if (!fee) {
      hint.classList.remove('is-visible');
      return;
    }
    var subtotal = getCartSubtotal();
    var text = subtotal >= FREE_SHIPPING_THRESHOLD
      ? 'الشحن مجاني لهذا الطلب 🎉'
      : 'رسوم الشحن إلى ' + opt.value + ': ' + formatPrice(parseFloat(fee));
    hint.querySelector('span').textContent = text;
    hint.classList.add('is-visible');
  }

  function renderSummary() {
    var items = getCartItems();
    if (!items.length) {
      window.location.href = 'cart.html';
      return;
    }
    renderInto('#checkout-items', items.map(miniItemHTML));
    document.getElementById('c-subtotal').textContent = formatPrice(getCartSubtotal());
    var shipping = currentShippingCost();
    document.getElementById('c-shipping').textContent = shipping === 0 ? 'مجاني' : formatPrice(shipping);
    document.getElementById('c-total').textContent = formatPrice(currentTotal());
    updateShippingHint();

    var row = document.getElementById('shipping-row');
    row.classList.remove('is-updated');
    void row.offsetWidth;
    row.classList.add('is-updated');
  }

  function setFieldError(id, hasError) {
    var field = document.getElementById(id).closest('.form-field');
    field.classList.toggle('has-error', hasError);
    return !hasError;
  }

  function validateForm() {
    var valid = true;
    var name = document.getElementById('full-name').value.trim();
    var phone = document.getElementById('phone').value.trim();
    var gov = document.getElementById('governorate').value;
    var area = document.getElementById('area').value.trim();
    var address = document.getElementById('address').value.trim();

    valid = setFieldError('full-name', name.length < 3) && valid;
    valid = setFieldError('phone', !isValidEgyptPhone(phone)) && valid;
    valid = setFieldError('governorate', !gov) && valid;
    valid = setFieldError('area', area.length < 2) && valid;
    valid = setFieldError('address', address.length < 6) && valid;

    return valid;
  }

  function placeOrder(e) {
    e.preventDefault();
    if (!validateForm()) {
      showToast('برجاء مراجعة البيانات المطلوبة', 'fa-solid fa-triangle-exclamation');
      return;
    }
    var order = {
      id: 'KO' + Date.now().toString().slice(-8),
      date: new Date().toISOString(),
      name: document.getElementById('full-name').value.trim(),
      phone: normalizePhone(document.getElementById('phone').value.trim()),
      governorate: document.getElementById('governorate').value,
      area: document.getElementById('area').value.trim(),
      address: document.getElementById('address').value.trim(),
      notes: document.getElementById('notes').value.trim(),
      payment: 'الدفع عند الاستلام',
      items: getCartItems().map(function (i) {
        return {
          id: i.product.id,
          name: i.product.name + (i.variationLabel ? ' (' + i.variationLabel + ')' : ''),
          price: i.price,
          qty: i.qty
        };
      }),
      subtotal: getCartSubtotal(),
      shipping: currentShippingCost(),
      total: currentTotal()
    };
    var orders = JSON.parse(localStorage.getItem('ko_orders') || '[]');
    orders.unshift(order);
    localStorage.setItem('ko_orders', JSON.stringify(orders));
    localStorage.setItem('ko_last_order', order.id);
    clearCart();
    window.location.href = 'order-success.html';
  }

  document.addEventListener('DOMContentLoaded', function () {
    populateGovernorates();
    renderSummary();
    document.getElementById('governorate').addEventListener('change', renderSummary);
    document.getElementById('checkout-form').addEventListener('submit', placeOrder);
  });
})();
