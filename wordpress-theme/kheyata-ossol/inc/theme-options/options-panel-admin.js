jQuery(function ($) {
  'use strict';

  $('.ko-color-field').wpColorPicker();

  $('.ko-image-upload').on('click', function (e) {
    e.preventDefault();
    var $btn = $(this);
    var $wrap = $btn.closest('.ko-image-field');
    var frame = wp.media({
      title: 'اختر صورة',
      multiple: false,
      library: { type: 'image' }
    });
    frame.on('select', function () {
      var attachment = frame.state().get('selection').first().toJSON();
      $wrap.find('input[type="hidden"]').val(attachment.url);
      $wrap.find('.ko-image-preview img').attr('src', attachment.url);
      $wrap.find('.ko-image-preview').show();
      $btn.hide();
    });
    frame.open();
  });

  $('.ko-image-remove').on('click', function (e) {
    e.preventDefault();
    var $wrap = $(this).closest('.ko-image-field');
    $wrap.find('input[type="hidden"]').val('');
    $wrap.find('.ko-image-preview').hide();
    $wrap.find('.ko-image-upload').show();
  });
});
