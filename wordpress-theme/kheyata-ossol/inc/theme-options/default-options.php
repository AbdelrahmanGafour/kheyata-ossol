<?php
/**
 * يربط خيارات لوحة القالب الفعلية بسلوك الموقع: تعطيل سكربتات الأداء، دفع القيم لـ WooCommerce، إلخ.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* -------- تبويب "الأداء" -------- */
add_action( 'init', function () {
	if ( ko_opt( 'disable_emojis', '1' ) === '1' ) {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}
	if ( ko_opt( 'disable_embeds', '1' ) === '1' ) {
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	}
} );

add_action( 'wp_enqueue_scripts', function () {
	if ( ko_opt( 'disable_block_css', '0' ) === '1' ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-blocks-style' );
	}
}, 100 );

add_filter( 'wp_lazy_loading_enabled', function ( $default ) {
	return ko_opt( 'lazy_load_images', '1' ) === '1' ? $default : false;
} );

/* -------- دفع قيم تبويب "المتجر" إلى WooCommerce -------- */
add_filter( 'loop_shop_columns', function () {
	return (int) ko_opt( 'shop_columns_desktop', '3' );
} );
add_filter( 'loop_shop_per_page', function ( $cols ) {
	return (int) ko_opt( 'shop_products_per_page', '12' );
}, 20 );
add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
	$args['posts_count'] = (int) ko_opt( 'related_products_count', '4' );
	$args['columns']     = (int) ko_opt( 'related_products_count', '4' );
	return $args;
} );
if ( ko_opt( 'show_related_products', '1' ) !== '1' ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
}

/* -------- شعار الموقع من خيارات القالب إن لم يُضبط عبر مظهر ← تخصيص -------- */
add_filter( 'get_custom_logo', function ( $html ) {
	if ( $html ) return $html;
	$logo = ko_opt( 'site_logo', '' );
	if ( ! $logo ) return $html;
	return '<a href="' . esc_url( home_url( '/' ) ) . '" class="custom-logo-link"><img src="' . esc_url( $logo ) . '" class="custom-logo" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '"></a>';
} );
