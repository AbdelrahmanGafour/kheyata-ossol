<?php
/**
 * أفضل تهيئة لـ Yoast SEO لمتجر إلكتروني: مخطط Organization يقرأ بيانات التواصل والشعار
 * من لوحة خيارات القالب مباشرةً (بدون تكرار إدخالها في Yoast)، صورة مشاركة افتراضية،
 * وربط نظام الـ breadcrumb الخاص بياست بشريط .breadcrumb في كل صفحة.
 */

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! defined( 'WPSEO_VERSION' ) ) return;

/* ملاحظة: ko_render_breadcrumb() تعيش في inc/template-tags.php (يُحمَّل دائمًا بغض النظر عن
   تفعيل Yoast SEO) لأن كل القوالب تستدعيها؛ هذا الملف يحتوي فقط على تحسينات خاصة بـ Yoast. */

/* -------- إثراء مخطط (Schema) المؤسسة تلقائيًا من بيانات "خيارات القالب ← التواصل" -------- */
add_filter( 'wpseo_schema_organization', function ( $data ) {
	$phone = ko_opt( 'contact_phone', '' );
	$logo  = ko_opt( 'site_logo', '' );

	if ( $phone ) {
		$data['contactPoint'] = array(
			'@type'             => 'ContactPoint',
			'telephone'         => $phone,
			'contactType'       => 'customer service',
			'areaServed'        => 'EG',
			'availableLanguage' => array( 'Arabic' ),
		);
	}
	if ( $logo && empty( $data['logo'] ) ) {
		$data['logo'] = $logo;
	}

	$social = array_filter( array(
		ko_opt( 'social_facebook', '' ),
		ko_opt( 'social_instagram', '' ),
		ko_opt( 'social_tiktok', '' ),
		ko_opt( 'contact_whatsapp', '' ) ? ko_whatsapp_link() : '',
	) );
	if ( $social ) {
		$existing = isset( $data['sameAs'] ) ? (array) $data['sameAs'] : array();
		$data['sameAs'] = array_values( array_unique( array_merge( $existing, $social ) ) );
	}

	return $data;
} );

/* -------- صورة مشاركة اجتماعية افتراضية = شعار المتجر عند عدم وجود صورة مميزة -------- */
add_filter( 'wpseo_opengraph_image', function ( $image ) {
	if ( $image ) return $image;
	return ko_opt( 'site_logo', '' );
} );

/* تفعيل شريط تنقّل Yoast (إن لم يكن مفعلًا) حتى تعمل yoast_breadcrumb() بدون خطوات إضافية. */
add_action( 'after_switch_theme', function () {
	$titles = get_option( 'wpseo_titles', array() );
	if ( empty( $titles['breadcrumbs-enable'] ) ) {
		$titles['breadcrumbs-enable'] = true;
		$titles['breadcrumbs-sep']    = '/';
		$titles['breadcrumbs-home']   = 'الرئيسية';
		update_option( 'wpseo_titles', $titles );
	}
	// تعبئة أولية لبيانات "المؤسسة" في الرسم البياني المعرفي (Knowledge Graph) إذا لم تُضبط من قبل.
	if ( empty( $titles['company_or_person'] ) ) {
		$titles['company_or_person'] = 'company';
		$titles['company_name']      = get_bloginfo( 'name' );
		update_option( 'wpseo_titles', $titles );
	}
} );

/* -------- قوالب عناوين/أوصاف مقترحة لصفحات المتجر (تُضبط مرة واحدة فقط، ولا تُكرَّر إن عدّلها الأدمن يدويًا) -------- */
add_action( 'after_switch_theme', function () {
	$titles = get_option( 'wpseo_titles', array() );
	$defaults = array(
		'title-tax-product_cat' => '%%term_title%% | للخياطة أصول',
		'title-product'         => '%%title%% | للخياطة أصول',
		'metadesc-product'      => '%%excerpt%% تسوّق الآن من متجر للخياطة أصول - شحن لجميع محافظات مصر والدفع عند الاستلام.',
	);
	$changed = false;
	foreach ( $defaults as $key => $val ) {
		if ( empty( $titles[ $key ] ) ) {
			$titles[ $key ] = $val;
			$changed = true;
		}
	}
	if ( $changed ) update_option( 'wpseo_titles', $titles );
} );
