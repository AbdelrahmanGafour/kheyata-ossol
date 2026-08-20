<?php
/**
 * تكامل Contact Form 7: تُنسَّق مخرجات النموذج تلقائيًا بنفس تصميم .form-control/.btn
 * عبر style.css (قسم "Contact Form 7" فيه)، ويُنشئ القالب نموذج "تواصل معنا" افتراضيًا
 * تلقائيًا عند أول تفعيل — بنفس حقول نموذج التواصل في التصميم الأصلي — ويربطه تلقائيًا
 * بحقل "معرّف نموذج Contact Form 7" في لوحة خيارات القالب ← تبويب التواصل.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** إخراج نموذج CF7 المحدد في خيارات القالب، أو رسالة توجيهية إن لم يُضبط بعد. */
function ko_render_contact_form() {
	$form_id = ko_opt( 'cf7_contact_form_id', '' );
	if ( ! $form_id || ! function_exists( 'wpcf7_contact_form' ) ) {
		if ( current_user_can( 'manage_options' ) ) {
			echo '<p class="form-hint">لم يتم ربط نموذج Contact Form 7 بعد. اذهب إلى: خيارات القالب ← التواصل والسوشيال ميديا، وأدخل معرّف النموذج.</p>';
		}
		return;
	}
	echo do_shortcode( '[contact-form-7 id="' . absint( $form_id ) . '"]' );
}

/* إنشاء نموذج تواصل افتراضي مطابق لحقول التصميم الأصلي (الاسم، الهاتف، الموضوع، الرسالة)
   عند أول تفعيل للقالب — فقط إذا لم يوجد نموذج مربوط مسبقًا. */
add_action( 'after_switch_theme', 'ko_maybe_create_default_cf7_form' );
function ko_maybe_create_default_cf7_form() {
	if ( ko_opt( 'cf7_contact_form_id', '' ) ) return;
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) return;

	$contact_form = WPCF7_ContactForm::get_template( array( 'title' => 'تواصل معنا - للخياطة أصول' ) );
	$properties   = $contact_form->get_properties();

	$properties['form'] =
		'<p><label>الاسم بالكامل<br>[text* your-name placeholder "اسمك"]</label></p>' .
		'<p><label>رقم الهاتف<br>[tel* your-phone placeholder "01xxxxxxxxx"]</label></p>' .
		'<p><label>الموضوع<br>[select your-subject "استفسار عن منتج" "استفسار عن طلب" "الالتحاق بالأكاديمية" "الدعم الفني والضمان" "أخرى"]</label></p>' .
		'<p><label>رسالتك<br>[textarea* your-message placeholder "اكتب رسالتك هنا..."]</label></p>' .
		'<p>[submit "إرسال الرسالة"]</p>';

	$properties['mail']['subject']    = 'رسالة جديدة من موقع للخياطة أصول: [your-subject]';
	$properties['mail']['sender']     = '[your-name] <wordpress@' . preg_replace( '#^https?://#', '', parse_url( home_url(), PHP_URL_HOST ) ) . '>';
	$properties['mail']['body']       = "اسم المرسل: [your-name]\nرقم الهاتف: [your-phone]\nالموضوع: [your-subject]\n\nالرسالة:\n[your-message]";
	$properties['mail']['recipient']  = get_option( 'admin_email' );
	$properties['mail']['additional_headers'] = 'Reply-To: [your-name] <' . get_option( 'admin_email' ) . '>';

	$properties['messages']['mail_sent_ok']  = 'شكرًا لتواصلك معنا! تم استلام رسالتك وسيقوم فريقنا بالرد عليك في أقرب وقت.';
	$properties['messages']['mail_sent_ng']  = 'حدث خطأ أثناء إرسال الرسالة، برجاء المحاولة مرة أخرى أو التواصل عبر واتساب.';
	$properties['messages']['validation_error'] = 'برجاء مراجعة الحقول المطلوبة أدناه.';

	$contact_form->set_properties( $properties );
	$contact_form->set_locale( 'ar' );
	$id = $contact_form->save();

	if ( $id ) {
		$opts = get_option( KO_Theme_Options::OPTION_NAME, array() );
		$opts['cf7_contact_form_id'] = (string) $id;
		update_option( KO_Theme_Options::OPTION_NAME, $opts );
	}
}

/* إضافة كلاس CF7 الافتراضي للحاوية الخارجية حتى تلتقط أنماط .form-card تلقائيًا داخل قوالب الصفحات. */
add_filter( 'wpcf7_form_class_attr', function ( $class ) {
	return $class . ' ko-cf7-form';
} );
