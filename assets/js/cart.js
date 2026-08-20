/* إدارة سلة الشراء عبر localStorage */

var CART_KEY = 'ko_cart';

function getCart() {
  try {
    return JSON.parse(localStorage.getItem(CART_KEY)) || {};
  } catch (e) {
    return {};
  }
}

function saveCart(cart) {
  localStorage.setItem(CART_KEY, JSON.stringify(cart));
  updateCartBadge();
  window.dispatchEvent(new CustomEvent('ko:cart-updated'));
}

function addToCart(productId, qty) {
  qty = qty || 1;
  var cart = getCart();
  cart[productId] = (cart[productId] || 0) + qty;
  saveCart(cart);
  showToast('تمت إضافة المنتج إلى السلة', 'fa-solid fa-cart-circle-check');
}

function setQty(productId, qty) {
  var cart = getCart();
  if (qty <= 0) {
    delete cart[productId];
  } else {
    cart[productId] = qty;
  }
  saveCart(cart);
}

function removeFromCart(productId) {
  var cart = getCart();
  delete cart[productId];
  saveCart(cart);
}

function clearCart() {
  localStorage.removeItem(CART_KEY);
  updateCartBadge();
  window.dispatchEvent(new CustomEvent('ko:cart-updated'));
}

/* مفتاح عنصر السلة: "productId" للمنتجات العادية، أو "productId::تسمية التنويع"
   للمنتجات ذات المتغيرات (مثل المقاسات)، حتى يُحسب كل تنويع كسطر منفصل بسعره الخاص */
function buildCartKey(productId, variationLabel) {
  return variationLabel ? (productId + '::' + variationLabel) : productId;
}

function parseCartKey(key) {
  var parts = key.split('::');
  return { productId: parts[0], variationLabel: parts[1] || null };
}

function getCartItems() {
  var cart = getCart();
  var items = [];
  Object.keys(cart).forEach(function (key) {
    var parsed = parseCartKey(key);
    var product = typeof getProductById === 'function' ? getProductById(parsed.productId) : null;
    if (!product) return;
    var variation = parsed.variationLabel ? getVariationOption(product, parsed.variationLabel) : null;
    var price = variation ? variation.price : product.price;
    items.push({
      cartKey: key,
      product: product,
      qty: cart[key],
      variationLabel: parsed.variationLabel,
      price: price
    });
  });
  return items;
}

function getCartCount() {
  var cart = getCart();
  return Object.keys(cart).reduce(function (sum, id) { return sum + cart[id]; }, 0);
}

function getCartSubtotal() {
  return getCartItems().reduce(function (sum, item) { return sum + item.price * item.qty; }, 0);
}

var SHIPPING_COST = 60;
var FREE_SHIPPING_THRESHOLD = 1500;

function getShippingCost() {
  var subtotal = getCartSubtotal();
  if (subtotal === 0) return 0;
  return subtotal >= FREE_SHIPPING_THRESHOLD ? 0 : SHIPPING_COST;
}

function getCartTotal() {
  return getCartSubtotal() + getShippingCost();
}

function updateCartBadge() {
  var count = getCartCount();
  document.querySelectorAll('.cart-badge').forEach(function (badge) {
    badge.textContent = count;
    badge.style.display = count > 0 ? 'flex' : 'none';
  });
}

function formatPrice(value) {
  return value.toLocaleString('en-US') + ' ج.م';
}

document.addEventListener('DOMContentLoaded', updateCartBadge);
