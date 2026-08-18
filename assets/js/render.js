/* قوالب عرض مشتركة: بطاقة منتج / بطاقة تصنيف + التعامل مع زر "أضف للسلة" */

function starsHTML(rating) {
  var full = Math.round(rating);
  var html = '';
  for (var i = 0; i < 5; i++) {
    html += '<i class="' + (i < full ? 'fa-solid' : 'fa-regular') + ' fa-star"></i>';
  }
  return html;
}

function productCardHTML(p) {
  var cat = getCategoryById(p.category);
  var hasVariations = !!p.variations;
  var actionBtn = hasVariations
    ? '<a href="product.html?id=' + p.id + '" class="add-cart-btn" aria-label="اختر المقاس"><i class="fa-solid fa-arrow-left"></i></a>'
    : '<button class="add-cart-btn" data-add-to-cart="' + p.id + '" aria-label="أضف للسلة"><i class="fa-solid fa-cart-plus"></i></button>';
  return (
    '<div class="card product-card" data-reveal>' +
      '<a href="product.html?id=' + p.id + '" class="product-media">' +
        '<img src="' + p.image + '" alt="' + p.name + '" loading="lazy">' +
        (p.badge ? '<span class="product-tag">' + p.badge + '</span>' : '') +
      '</a>' +
      '<div class="product-body">' +
        '<span class="product-cat">' + (cat ? cat.name : '') + '</span>' +
        '<a href="product.html?id=' + p.id + '"><h3>' + p.name + '</h3></a>' +
        '<div class="rating">' + starsHTML(p.rating) + ' <span style="color:var(--color-text-muted)">(' + p.reviews + ')</span></div>' +
        '<div class="product-price-row">' +
          '<div>' + (hasVariations ? '<span class="form-hint" style="display:block;">يبدأ من</span>' : '') +
          '<span class="price">' + formatPrice(p.price) + '</span>' +
          (p.oldPrice ? '<span class="price-old">' + formatPrice(p.oldPrice) + '</span>' : '') +
          '</div>' +
          actionBtn +
        '</div>' +
      '</div>' +
    '</div>'
  );
}

function categoryCardHTML(c) {
  return (
    '<a href="store.html?category=' + c.id + '" class="card service-card">' +
      '<div class="service-icon"><i class="' + c.icon + '"></i></div>' +
      '<h3>' + c.name + '</h3>' +
    '</a>'
  );
}

function renderInto(selector, htmlArray) {
  var el = document.querySelector(selector);
  if (!el) return;
  el.innerHTML = htmlArray.join('');
  if (typeof window.revealNewElements === 'function') window.revealNewElements();
}

document.addEventListener('click', function (e) {
  var btn = e.target.closest('[data-add-to-cart]');
  if (!btn) return;
  e.preventDefault();
  addToCart(btn.getAttribute('data-add-to-cart'), 1);
  animateAddToCart(btn);
});

function animateAddToCart(btn) {
  if (typeof anime === 'undefined') return;
  anime.animate(btn, {
    scale: [1, 1.35, 1],
    duration: 420,
    ease: 'outBack'
  });
  var cartIcon = document.querySelector('.icon-btn .fa-cart-shopping');
  if (cartIcon) {
    anime.animate(cartIcon, {
      scale: [1, 1.3, 1],
      rotate: [0, -12, 0],
      duration: 420,
      ease: 'outBack'
    });
  }
}
