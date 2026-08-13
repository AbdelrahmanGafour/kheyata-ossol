/* منطق صفحتي تسجيل الدخول وإنشاء الحساب */

(function () {
  'use strict';

  function setFieldError(input, hasError) {
    var field = input.closest('.form-field');
    if (field) field.classList.toggle('has-error', hasError);
    return !hasError;
  }

  /* ---------- تسجيل الدخول ---------- */
  var loginForm = document.getElementById('login-form');
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var phoneInput = document.getElementById('login-phone');
      var passInput = document.getElementById('login-password');
      var valid = true;
      valid = setFieldError(phoneInput, !isValidEgyptPhone(phoneInput.value)) && valid;
      valid = setFieldError(passInput, passInput.value.length < 1) && valid;
      if (!valid) return;

      var result = loginUser(phoneInput.value, passInput.value);
      if (!result.ok) {
        setFieldError(passInput, true);
        passInput.closest('.form-field').querySelector('.form-error').textContent = result.error;
        return;
      }
      startSession(result.user);
      showToast('تم تسجيل الدخول بنجاح', 'fa-solid fa-circle-check');
      setTimeout(function () { window.location.href = 'account.html'; }, 700);
    });
  }

  /* ---------- إنشاء حساب: خطوة البيانات ثم رمز التحقق ---------- */
  var registerForm = document.getElementById('register-form');
  if (registerForm) {
    var pendingUser = null;

    registerForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var nameInput = document.getElementById('reg-name');
      var phoneInput = document.getElementById('reg-phone');
      var passInput = document.getElementById('reg-password');
      var pass2Input = document.getElementById('reg-password2');

      var valid = true;
      valid = setFieldError(nameInput, nameInput.value.trim().length < 3) && valid;
      valid = setFieldError(phoneInput, !isValidEgyptPhone(phoneInput.value)) && valid;
      valid = setFieldError(passInput, passInput.value.length < 6) && valid;
      valid = setFieldError(pass2Input, pass2Input.value !== passInput.value) && valid;
      if (!valid) return;

      if (findUserByPhone(phoneInput.value)) {
        setFieldError(phoneInput, true);
        showToast('رقم الهاتف مسجل بالفعل', 'fa-solid fa-triangle-exclamation');
        return;
      }

      pendingUser = { name: nameInput.value.trim(), phone: phoneInput.value, password: passInput.value };

      var btn = document.getElementById('send-otp-btn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جارٍ الإرسال...';

      sendOtp(pendingUser.phone).then(function () {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> إرسال رمز التحقق';
        document.getElementById('otp-phone-display').textContent = '0' + normalizePhone(pendingUser.phone);
        document.getElementById('step-details').classList.add('step-hidden');
        document.getElementById('step-otp').classList.remove('step-hidden');
        document.querySelector('.otp-digit').focus();
        showToast('تم إرسال رمز التحقق', 'fa-solid fa-paper-plane');
      });
    });

    /* التنقل التلقائي بين خانات رمز التحقق */
    var otpDigits = document.querySelectorAll('.otp-digit');
    otpDigits.forEach(function (input, i) {
      input.addEventListener('input', function () {
        input.value = input.value.replace(/[^0-9]/g, '');
        if (input.value && otpDigits[i + 1]) otpDigits[i + 1].focus();
      });
      input.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace' && !input.value && otpDigits[i - 1]) otpDigits[i - 1].focus();
      });
    });

    document.getElementById('otp-form').addEventListener('submit', function (e) {
      e.preventDefault();
      var code = Array.prototype.map.call(otpDigits, function (i) { return i.value; }).join('');
      var errorEl = document.getElementById('otp-error');
      if (code.length < 4 || !verifyOtp(code)) {
        errorEl.style.display = 'block';
        if (typeof anime !== 'undefined') {
          anime.animate('.otp-inputs', { translateX: [-8, 8, -6, 6, 0], duration: 400, ease: 'linear' });
        }
        return;
      }
      errorEl.style.display = 'none';

      var result = registerUser(pendingUser.name, pendingUser.phone, pendingUser.password);
      if (!result.ok) {
        showToast(result.error, 'fa-solid fa-triangle-exclamation');
        return;
      }
      startSession(findUserByPhone(pendingUser.phone));
      showToast('تم إنشاء الحساب بنجاح', 'fa-solid fa-circle-check');
      setTimeout(function () { window.location.href = 'account.html'; }, 700);
    });

    document.getElementById('resend-otp').addEventListener('click', function () {
      if (pendingUser) {
        sendOtp(pendingUser.phone);
        showToast('تم إعادة إرسال رمز التحقق', 'fa-solid fa-paper-plane');
      }
    });
  }
})();
