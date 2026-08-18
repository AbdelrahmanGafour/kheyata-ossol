/* منطق صفحة سلة المشتريات */

(function () {
  'use strict';

  function cartItemHTML(item) {
    var p = item.product;
    return (
      '<div class="cart-item" data-reveal>' +
        '<img src="' + p.image + '" alt="' + p.name + '">' +
        '<div>' +
          '<span class="cat">' + getCategoryById(p.category).name + (item.variationLabel ? ' · ' + item.variationLabel : '') + '</span>' +
          '<h3><a href="product.html?id=' + p.id + '">' + p.name + '</a></h3>' +
          '<span class="price">' + formatPrice(item.price) + '</span>' +
          '<div class="cart-qty mt-2">' +
            '<button type="button" data-qty-inc="' + item.cartKey + '"><i class="fa-solid fa-plus"></i></button>' +
            '<input type="text" readonly value="' + item.qty + '">' +
            '<button type="button" data-qty-dec="' + item.cartKey + '"><i class="fa-solid fa-minus"></i></button>' +
          '</div>' +
        '</div>' +
        '<div class="cart-item-actions">' +
          '<strong class="price">' + formatPrice(item.price * item.qty) + '</strong>' +
          '<button class="remove-btn" data-remove="' + item.cartKey + '"><i class="fa-solid fa-trash"></i> إزالة</button>' +
        '</div>' +
      '</div>'
    );
  }

  function render() {
    var items = getCartItems();
    var layout = document.getElementById('cart-layout');
    var empty = document.getElementById('cart-empty');

    if (!items.length) {
      layout.style.display = 'none';
      empty.style.display = 'block';
      return;
    }
    layout.style.display = 'grid';
    empty.style.display = 'none';

    renderInto('#cart-items', items.map(cartItemHTML));
    document.getElementById('summary-count').textContent = getCartCount();
    document.getElementById('summary-subtotal').textContent = formatPrice(getCartSubtotal());
    var shipping = getShippingCost();
    document.getElementById('summary-shipping').textContent = shipping === 0 ? 'مجاني' : formatPrice(shipping);
    document.getElementById('summary-total').textContent = formatPrice(getCartTotal());
  }

  document.addEventListener('click', function (e) {
    var inc = e.target.closest('[data-qty-inc]');
    var dec = e.target.closest('[data-qty-dec]');
    var rem = e.target.closest('[data-remove]');
    if (inc) {
      var cart1 = getCart();
      var key1 = inc.getAttribute('data-qty-inc');
      var product1 = getProductById(parseCartKey(key1).productId);
      setQty(key1, Math.min(product1.stock, (cart1[key1] || 0) + 1));
      render();
    } else if (dec) {
      var cart2 = getCart();
      var key2 = dec.getAttribute('data-qty-dec');
      setQty(key2, Math.max(1, (cart2[key2] || 0) - 1));
      render();
    } else if (rem) {
      removeFromCart(rem.getAttribute('data-remove'));
      showToast('تم حذف المنتج من السلة', 'fa-solid fa-trash');
      render();
    }
  });

  document.addEventListener('DOMContentLoaded', render);
})();
