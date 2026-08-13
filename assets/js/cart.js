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
}

function getCartItems() {
  var cart = getCart();
  var items = [];
  Object.keys(cart).forEach(function (id) {
    var product = typeof getProductById === 'function' ? getProductById(id) : null;
    if (product) {
      items.push({ product: product, qty: cart[id] });
    }
  });
  return items;
}

function getCartCount() {
  var cart = getCart();
  return Object.keys(cart).reduce(function (sum, id) { return sum + cart[id]; }, 0);
}

function getCartSubtotal() {
  return getCartItems().reduce(function (sum, item) { return sum + item.product.price * item.qty; }, 0);
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
