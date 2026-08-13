/* نظام تسجيل الدخول بالهاتف — تجريبي (تخزين محلي، بدون خادم فعلي) */

var USERS_KEY = 'ko_users';
var SESSION_KEY = 'ko_session';
var DEMO_OTP = '1234'; /* رمز تحقق ثابت للعرض التجريبي فقط */

function normalizePhone(raw) {
  return (raw || '').replace(/[^\d]/g, '').replace(/^0+/, '');
}

function isValidEgyptPhone(raw) {
  var digits = normalizePhone(raw);
  return /^1[0125]\d{8}$/.test(digits);
}

function getUsers() {
  try {
    return JSON.parse(localStorage.getItem(USERS_KEY)) || [];
  } catch (e) {
    return [];
  }
}

function saveUsers(users) {
  localStorage.setItem(USERS_KEY, JSON.stringify(users));
}

function findUserByPhone(phone) {
  var digits = normalizePhone(phone);
  return getUsers().find(function (u) { return u.phone === digits; });
}

function registerUser(name, phone, password) {
  var digits = normalizePhone(phone);
  var users = getUsers();
  if (findUserByPhone(digits)) {
    return { ok: false, error: 'رقم الهاتف مسجل بالفعل، برجاء تسجيل الدخول.' };
  }
  users.push({ name: name, phone: digits, password: password, createdAt: Date.now() });
  saveUsers(users);
  return { ok: true };
}

function loginUser(phone, password) {
  var user = findUserByPhone(phone);
  if (!user) return { ok: false, error: 'لا يوجد حساب بهذا الرقم. سجّل حسابًا جديدًا أولاً.' };
  if (user.password !== password) return { ok: false, error: 'كلمة المرور غير صحيحة.' };
  return { ok: true, user: user };
}

function startSession(user) {
  sessionStorage.setItem(SESSION_KEY, JSON.stringify({ name: user.name, phone: user.phone }));
}

function getCurrentUser() {
  try {
    return JSON.parse(sessionStorage.getItem(SESSION_KEY));
  } catch (e) {
    return null;
  }
}

function logoutUser() {
  sessionStorage.removeItem(SESSION_KEY);
  window.location.href = 'login.html';
}

/* محاكاة إرسال رمز تحقق OTP عبر SMS (تجريبي: الرمز الثابت 1234) */
function sendOtp(phone) {
  return new Promise(function (resolve) {
    setTimeout(function () { resolve({ ok: true, demoOtp: DEMO_OTP }); }, 900);
  });
}

function verifyOtp(code) {
  return code === DEMO_OTP;
}

function refreshAuthUI() {
  var user = getCurrentUser();
  document.querySelectorAll('[data-auth-account-link]').forEach(function (el) {
    el.setAttribute('href', user ? 'account.html' : 'login.html');
    el.setAttribute('title', user ? ('حسابي — ' + user.name) : 'تسجيل الدخول');
  });
}

document.addEventListener('DOMContentLoaded', refreshAuthUI);
