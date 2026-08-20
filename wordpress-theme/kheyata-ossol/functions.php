<?php
/**
 * Kheyata Ossol theme bootstrap.
 * القالب عربي/RTL فقط — لا يوجد دعم متعدد اللغات (Polylang غير مستخدم بقرار صريح من صاحب المتجر).
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'KO_THEME_VERSION', '1.1.0' );
define( 'KO_THEME_DIR', get_template_directory() );
define( 'KO_THEME_URI', get_template_directory_uri() );

/* ------------------------------------------------------------------
 * إعداد القالب الأساسي
 * ------------------------------------------------------------------ */
function ko_theme_setup() {
	load_theme_textdomain( 'kheyata-ossol', KO_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'automatic-feed-links' );

	// المتجر ثابت على العربية RTL فقط بحسب متطلبات المشروع.
	add_theme_support( 'align-wide' );

	set_post_thumbnail_size( 800, 1000, true );
	add_image_size( 'ko-product-thumb', 600, 750, true );
	add_image_size( 'ko-card', 480, 320, true );

	register_nav_menus( array(
		'primary'      => __( 'القائمة الرئيسية (الهيدر)', 'kheyata-ossol' ),
		'mobile'       => __( 'قائمة الموبايل (اختياري، تُستخدم القائمة الرئيسية إن لم تُحدَّد)', 'kheyata-ossol' ),
		'footer-links' => __( 'روابط الفوتر السريعة', 'kheyata-ossol' ),
	) );

	// دعم WooCommerce.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'ko_theme_setup' );

/* ------------------------------------------------------------------
 * تحميل ملفات القالب الداخلية
 * ------------------------------------------------------------------ */
require KO_THEME_DIR . '/inc/theme-options/class-ko-theme-options.php';
require KO_THEME_DIR . '/inc/theme-options/default-options.php';
require KO_THEME_DIR . '/inc/scf-fields.php';
require KO_THEME_DIR . '/inc/woocommerce.php';
require KO_THEME_DIR . '/inc/contact-form7.php';
require KO_THEME_DIR . '/inc/yoast-seo.php';
require KO_THEME_DIR . '/inc/template-tags.php';
require KO_THEME_DIR . '/inc/plugin-installer.php';
require KO_THEME_DIR . '/inc/demo-import.php';
require KO_THEME_DIR . '/inc/quick-view.php';

/* ------------------------------------------------------------------
 * تحميل الأنماط والسكربتات
 * ------------------------------------------------------------------ */
/** يبني رابط Google Fonts ديناميكيًا حسب الخطوط المختارة فعليًا في تبويب "الخطوط" (بدون تحميل خطوط غير مستخدمة). */
function ko_google_fonts_url() {
	$families = array(
		'El Messiri' => 'El+Messiri:wght@500;600;700',
		'Tajawal'    => 'Tajawal:wght@400;500;700;800',
		'Cairo'      => 'Cairo:wght@400;500;600;700;800',
		'Almarai'    => 'Almarai:wght@400;700;800',
		'DM Sans'    => 'DM+Sans:wght@500;700',
	);
	$wanted = array_unique( array(
		ko_opt( 'heading_font', 'El Messiri' ),
		ko_opt( 'body_font', 'Tajawal' ),
		ko_opt( 'numeric_font', 'DM Sans' ),
	) );
	$parts = array();
	foreach ( $wanted as $font ) {
		if ( isset( $families[ $font ] ) ) $parts[] = $families[ $font ];
	}
	if ( ! $parts ) $parts[] = $families['Tajawal'];
	return 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $parts ) . '&display=swap';
}

/** يبني سلسلة خط CSS كاملة (الخط المختار + بدائل احتياطية مناسبة). */
function ko_font_stack( $font, $fallback = "'Tajawal', sans-serif" ) {
	if ( ! $font ) return $fallback;
	return "'" . $font . "', " . $fallback;
}

function ko_enqueue_assets() {
	wp_enqueue_style( 'ko-google-fonts', ko_google_fonts_url(), array(), null );

	wp_enqueue_style( 'font-awesome', KO_THEME_URI . '/assets/vendor/fontawesome/css/all.min.css', array(), KO_THEME_VERSION );

	wp_enqueue_style( 'kheyata-ossol-style', get_stylesheet_uri(), array( 'font-awesome' ), KO_THEME_VERSION );

	// مكتبات الحركة.
	wp_enqueue_script( 'gsap', KO_THEME_URI . '/assets/vendor/gsap/gsap.min.js', array(), KO_THEME_VERSION, true );
	wp_enqueue_script( 'gsap-scrolltrigger', KO_THEME_URI . '/assets/vendor/gsap/ScrollTrigger.min.js', array( 'gsap' ), KO_THEME_VERSION, true );
	wp_enqueue_script( 'animejs', KO_THEME_URI . '/assets/vendor/animejs/anime.min.js', array(), KO_THEME_VERSION, true );

	// سكربتات القالب.
	wp_enqueue_script( 'ko-main', KO_THEME_URI . '/assets/js/main.js', array( 'gsap', 'gsap-scrolltrigger' ), KO_THEME_VERSION, true );
	wp_enqueue_script( 'ko-anime-scroll', KO_THEME_URI . '/assets/js/anime-scroll.js', array( 'animejs' ), KO_THEME_VERSION, true );
	wp_enqueue_script( 'ko-carousel', KO_THEME_URI . '/assets/js/carousel.js', array(), KO_THEME_VERSION, true );

	if ( class_exists( 'WooCommerce' ) ) {
		// يُحمَّل في كل الصفحات (وليس فقط المتجر/المنتج) لأن أزرار المعاينة السريعة ومنتقي
		// التنويعات قد تظهران داخل بطاقات منتجات على أي صفحة (الرئيسية مثلاً)؛ كل دوال
		// الملف "no-op" بأمان عند غياب عناصرها المستهدفة في الصفحة الحالية.
		wp_enqueue_script( 'ko-shop', KO_THEME_URI . '/assets/js/shop.js', array( 'jquery', 'ko-main' ), KO_THEME_VERSION, true );
		wp_enqueue_script( 'ko-mini-cart', KO_THEME_URI . '/assets/js/mini-cart.js', array( 'ko-main' ), KO_THEME_VERSION, true );
		if ( ko_opt( 'show_quick_view', '1' ) === '1' ) {
			wp_enqueue_script( 'ko-quick-view', KO_THEME_URI . '/assets/js/quick-view.js', array( 'ko-main', 'ko-shop' ), KO_THEME_VERSION, true );
		}
	}

	wp_localize_script( 'ko-main', 'koSettings', array(
		'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
		'whatsapp'       => ko_opt( 'contact_whatsapp', '' ),
		'homeUrl'        => home_url( '/' ),
		'quickViewNonce' => wp_create_nonce( 'ko_quick_view' ),
	) );

	$custom_js = trim( ko_opt( 'custom_js', '' ) );
	if ( $custom_js ) {
		wp_add_inline_script( 'ko-main', $custom_js, 'after' );
	}
}
add_action( 'wp_enqueue_scripts', 'ko_enqueue_assets' );

/* ------------------------------------------------------------------
 * الإعدادات الشاملة (Global Settings) — كل قيمة يضبطها الأدمن من خيارات القالب
 * (الألوان، الخطوط، نمط العرض) تُترجم هنا لمتغيّرات CSS تُطبَّق على الموقع بالكامل
 * فوق design tokens الأساسية في style.css، بنفس فكرة Woodmart Theme Settings.
 * ------------------------------------------------------------------ */
function ko_dynamic_css_vars() {
	$vars = array(
		'--color-primary'       => ko_opt( 'color_primary', '#1B4C8C' ),
		'--color-primary-dark'  => ko_opt( 'color_primary_dark', '#0F3164' ),
		'--color-primary-light' => ko_opt( 'color_primary_light', '#3A6DB5' ),
		'--color-accent'        => ko_opt( 'color_accent', '#C9A24B' ),
		'--color-accent-light'  => ko_opt( 'color_accent_light', '#E8D5A0' ),
		'--color-accent-dark'   => ko_opt( 'color_accent_dark', '#82631E' ),
		'--color-bg'            => ko_opt( 'color_bg', '#FBF7EC' ),
		'--color-bg-alt'        => ko_opt( 'color_bg_alt', '#F2E9D4' ),
		'--color-text'          => ko_opt( 'color_text', '#16233B' ),
		'--color-text-muted'    => ko_opt( 'color_text_muted', '#5B6472' ),
		'--color-whatsapp'      => ko_opt( 'whatsapp_color', '#25D366' ),
		'--header-bg-color'     => ko_opt( 'header_bg_color', '#FBF7EC' ),
		'--footer-bg-color'     => ko_opt( 'footer_bg_color', ko_opt( 'color_primary', '#1B4C8C' ) ),
		'--footer-text-color'   => ko_opt( 'footer_text_color', '#FFFFFF' ),
		'--price-color'         => ko_opt( 'price_color', ko_opt( 'color_primary', '#1B4C8C' ) ),
		'--sale-badge-color'    => ko_opt( 'sale_badge_color', ko_opt( 'color_accent', '#C9A24B' ) ),
		'--header-height'       => absint( ko_opt( 'header_height', 84 ) ) . 'px',
		'--radius-lg'           => absint( ko_opt( 'card_radius', 16 ) ) . 'px',
		'--font-heading'        => ko_font_stack( ko_opt( 'heading_font', 'El Messiri' ), "'Tajawal', sans-serif" ),
		'--font-body'           => ko_font_stack( ko_opt( 'body_font', 'Tajawal' ) ),
		'--font-numeric'        => ko_font_stack( ko_opt( 'numeric_font', 'DM Sans' ) ),
		'--font-size-base'      => absint( ko_opt( 'base_font_size', 16 ) ) . 'px',
		'--h1-size'             => absint( ko_opt( 'h1_size', 44 ) ) . 'px',
		'--h2-size'             => absint( ko_opt( 'h2_size', 32 ) ) . 'px',
		'--h3-size'             => absint( ko_opt( 'h3_size', 20 ) ) . 'px',
	);

	$css = ':root{';
	foreach ( $vars as $name => $value ) {
		$css .= $name . ':' . $value . ';';
	}
	$css .= '}';

	if ( ko_opt( 'sticky_header', '1' ) !== '1' ) {
		$css .= '.site-header{position:relative;}body{padding-top:0 !important;}';
	}

	$custom_css = trim( ko_opt( 'custom_css', '' ) );
	if ( $custom_css ) $css .= "\n" . $custom_css;

	wp_add_inline_style( 'kheyata-ossol-style', $css );
}
add_action( 'wp_enqueue_scripts', 'ko_dynamic_css_vars', 20 );

/* -------- كلاس على <body> لتعطيل تكبير صورة المنتج عند المرور (حسب تبويب "المتجر") -------- */
add_filter( 'body_class', function ( $classes ) {
	if ( ko_opt( 'product_card_hover_zoom', '1' ) !== '1' ) $classes[] = 'no-product-zoom';
	return $classes;
} );

/* ------------------------------------------------------------------
 * Widget areas (تُستخدم داخل أعمدة الفوتر، قابلة للتحرير من مظهر ← ودجات)
 * ------------------------------------------------------------------ */
function ko_register_widget_areas() {
	register_sidebar( array(
		'name'          => __( 'عمود الفوتر 2 (الحساب/المتجر)', 'kheyata-ossol' ),
		'id'            => 'footer-col-2',
		'before_widget' => '<div class="footer-col">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => __( 'عمود الفوتر 3 (روابط مساعدة)', 'kheyata-ossol' ),
		'id'            => 'footer-col-3',
		'before_widget' => '<div class="footer-col">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => __( 'الشريط الجانبي للمدونة', 'kheyata-ossol' ),
		'id'            => 'blog-sidebar',
		'before_widget' => '<div class="card" style="padding:20px;margin-bottom:20px;">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="mb-2">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'ko_register_widget_areas' );

/* ------------------------------------------------------------------
 * مساعدات عامة
 * ------------------------------------------------------------------ */

/** اختصار قراءة قيمة من خيارات القالب (لوحة "خيارات القالب" الشبيهة بـ Woodmart). */
function ko_opt( $key, $default = '' ) {
	return KO_Theme_Options::get( $key, $default );
}

/** اختصار قراءة قيمة من صفحة خيارات SCF (المحتوى الديناميكي). */
function ko_field( $key, $post_id = 'option', $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) return $default;
	$value = get_field( $key, $post_id );
	return ( $value === null || $value === false || $value === '' ) ? $default : $value;
}

/** تنسيق السعر بنفس أسلوب الموقع الثابت الأصلي (رقم إنجليزي + "ج.م"). */
function ko_format_price( $amount ) {
	return number_format_i18n( (float) $amount ) . ' ج.م';
}

/** رابط واتساب جاهز برقم من الإعدادات + رسالة اختيارية. */
function ko_whatsapp_link( $message = '' ) {
	$phone = preg_replace( '/[^0-9]/', '', ko_opt( 'contact_whatsapp', '' ) );
	if ( ! $phone ) return '#';
	$url = 'https://wa.me/' . $phone;
	if ( $message ) $url .= '?text=' . rawurlencode( $message );
	return esc_url( $url );
}

/* ------------------------------------------------------------------
 * فك ارتباط الأيقونة القديمة عن jQuery Migrate لتقليل تحذيرات الكونسول (اختياري تحسين أداء).
 * ------------------------------------------------------------------ */
function ko_remove_jquery_migrate( $scripts ) {
	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
		$script = $scripts->registered['jquery'];
		if ( $script->deps ) {
			$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
		}
	}
}
add_action( 'wp_default_scripts', 'ko_remove_jquery_migrate' );

/* ------------------------------------------------------------------
 * حجم صورة المقالات الافتراضي داخل loop.
 * ------------------------------------------------------------------ */
add_filter( 'excerpt_length', function () { return 24; } );
add_filter( 'excerpt_more', function () { return '…'; } );
