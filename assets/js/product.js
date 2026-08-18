/* منطق صفحة تفاصيل المنتج */

(function () {
  'use strict';

  var params = new URLSearchParams(window.location.search);
  var id = params.get('id');
  var product = getProductById(id) || PRODUCTS[0];
  var cat = getCategoryById(product.category);
  var gallery = product.images && product.images.length ? product.images : [product.image];
  var selectedVariation = product.variations ? product.variations.options[0] : null;

  document.getElementById('page-title').textContent = product.name + ' | للخياطة أصول';
  document.getElementById('breadcrumb-title').textContent = product.name;
  document.getElementById('breadcrumb-current').textContent = product.name;

  function variationSelectorHTML() {
    if (!product.variations) return '';
    var buttons = product.variations.options.map(function (opt, i) {
      return '<button type="button" class="variation-option' + (i === 0 ? ' is-selected' : '') + '" data-variation="' + opt.label + '" data-price="' + opt.price + '">' + opt.label + '</button>';
    }).join('');
    return (
      '<div class="variation-picker mb-3">' +
        '<label style="font-weight:700; font-size:14px; display:block; margin-bottom:10px;">' + product.variations.label + '</label>' +
        '<div class="variation-options">' + buttons + '</div>' +
      '</div>'
    );
  }

  var root = document.getElementById('product-detail-root');
  root.innerHTML =
    '<div class="product-gallery" data-reveal>' +
      '<img src="' + gallery[0] + '" alt="' + product.name + '" id="main-product-image">' +
      (gallery.length > 1
        ? '<div class="gallery-thumbs">' + gallery.map(function (src, i) {
            return '<button type="button" class="gallery-thumb' + (i === 0 ? ' is-active' : '') + '" data-src="' + src + '"><img src="' + src + '" alt="' + product.name + ' - صورة ' + (i + 1) + '"></button>';
          }).join('') + '</div>'
        : '') +
    '</div>' +
    '<div data-reveal>' +
      '<span class="badge-pill mb-2"><i class="' + cat.icon + '"></i> ' + cat.name + '</span>' +
      '<h1 style="font-size:30px;">' + product.name + '</h1>' +
      '<div class="rating mt-2 mb-2">' + starsHTML(product.rating) + ' <span style="color:var(--color-text-muted)">' + product.rating + ' (' + product.reviews + ' تقييم)</span></div>' +
      '<div class="flex gap-2" style="align-items:baseline; margin:16px 0;">' +
        '<span class="price" id="product-price" style="font-size:32px;">' + formatPrice(selectedVariation ? selectedVariation.price : product.price) + '</span>' +
        (product.oldPrice ? '<span class="price-old" style="font-size:18px;">' + formatPrice(product.oldPrice) + '</span>' : '') +
      '</div>' +
      '<p class="mb-3">' + product.description + '</p>' +
      variationSelectorHTML() +
      '<div class="stock-badge mb-3"><i class="fa-solid fa-circle-check"></i> متوفر في المخزون (' + product.stock + ' قطعة)</div>' +
      '<div class="flex gap-2 mb-4" style="align-items:center; flex-wrap:wrap;">' +
        '<div class="qty-selector">' +
          '<button type="button" id="qty-inc"><i class="fa-solid fa-plus"></i></button>' +
          '<input type="text" id="qty-input" value="1" readonly>' +
          '<button type="button" id="qty-dec"><i class="fa-solid fa-minus"></i></button>' +
        '</div>' +
        '<button class="btn btn-accent" id="add-to-cart-main"><i class="fa-solid fa-cart-plus"></i> أضف إلى السلة</button>' +
      '</div>' +
      '<div class="divider mb-3"></div>' +
      '<div class="grid grid-2" style="gap:14px;">' +
        '<div class="feature-mini"><i class="fa-solid fa-truck-fast"></i> توصيل 2-4 أيام عمل</div>' +
        '<div class="feature-mini"><i class="fa-solid fa-money-bill-wave"></i> الدفع عند الاستلام</div>' +
        '<div class="feature-mini"><i class="fa-solid fa-shield-halved"></i> ضمان ودعم فني</div>' +
        '<div class="feature-mini"><i class="fa-solid fa-box"></i> تغليف مقاوم للصدمات</div>' +
      '</div>' +
    '</div>';

  /* معرض الصور المصغّرة */
  var mainImage = document.getElementById('main-product-image');
  document.querySelectorAll('.gallery-thumb').forEach(function (thumb) {
    thumb.addEventListener('click', function () {
      mainImage.src = thumb.getAttribute('data-src');
      document.querySelectorAll('.gallery-thumb').forEach(function (t) { t.classList.remove('is-active'); });
      thumb.classList.add('is-active');
    });
  });

  /* اختيار التنويع (المقاس) وتحديث السعر المعروض فورًا */
  var priceEl = document.getElementById('product-price');
  document.querySelectorAll('.variation-option').forEach(function (opt) {
    opt.addEventListener('click', function () {
      document.querySelectorAll('.variation-option').forEach(function (o) { o.classList.remove('is-selected'); });
      opt.classList.add('is-selected');
      selectedVariation = { label: opt.getAttribute('data-variation'), price: parseFloat(opt.getAttribute('data-price')) };
      priceEl.textContent = formatPrice(selectedVariation.price);
    });
  });

  var qtyInput = document.getElementById('qty-input');
  document.getElementById('qty-inc').addEventListener('click', function () {
    qtyInput.value = Math.min(product.stock, parseInt(qtyInput.value, 10) + 1);
  });
  document.getElementById('qty-dec').addEventListener('click', function () {
    qtyInput.value = Math.max(1, parseInt(qtyInput.value, 10) - 1);
  });
  document.getElementById('add-to-cart-main').addEventListener('click', function (e) {
    var cartKey = buildCartKey(product.id, selectedVariation ? selectedVariation.label : null);
    addToCart(cartKey, parseInt(qtyInput.value, 10));
    animateAddToCart(e.currentTarget);
  });

  var related = PRODUCTS.filter(function (p) { return p.category === product.category && p.id !== product.id; }).slice(0, 4);
  if (related.length < 4) {
    related = related.concat(PRODUCTS.filter(function (p) { return p.id !== product.id && related.indexOf(p) === -1; }).slice(0, 4 - related.length));
  }
  renderInto('#related-products', related.map(productCardHTML));
})();
