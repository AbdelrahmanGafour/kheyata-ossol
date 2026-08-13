/* منطق صفحة التواصل: نموذج التواصل + أكورديون الأسئلة الشائعة */

(function () {
  'use strict';

  function setFieldError(input, hasError) {
    var field = input.closest('.form-field');
    field.classList.toggle('has-error', hasError);
    return !hasError;
  }

  var form = document.getElementById('contact-form');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var name = document.getElementById('c-name');
      var phone = document.getElementById('c-phone');
      var message = document.getElementById('c-message');

      var valid = true;
      valid = setFieldError(name, name.value.trim().length < 3) && valid;
      valid = setFieldError(phone, !isValidEgyptPhone(phone.value)) && valid;
      valid = setFieldError(message, message.value.trim().length < 5) && valid;
      if (!valid) return;

      showToast('تم إرسال رسالتك بنجاح، سنتواصل معك قريبًا', 'fa-solid fa-paper-plane');
      form.reset();
    });
  }

  document.querySelectorAll('.faq-question').forEach(function (q) {
    q.addEventListener('click', function () {
      var item = q.closest('.faq-item');
      var answer = item.querySelector('.faq-answer');
      var isOpen = item.classList.contains('is-open');

      document.querySelectorAll('.faq-item.is-open').forEach(function (openItem) {
        if (openItem !== item) {
          openItem.classList.remove('is-open');
          openItem.querySelector('.faq-answer').style.maxHeight = null;
        }
      });

      item.classList.toggle('is-open', !isOpen);
      answer.style.maxHeight = !isOpen ? answer.scrollHeight + 'px' : null;
    });
  });
})();
