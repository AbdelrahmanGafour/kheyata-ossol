<?php
/**
 * تكامل WooCommerce مع تصميم القالب: نلغي الأغلفة والمسارات الافتراضية لأن قوالب
 * woocommerce/archive-product.php و woocommerce/single-product.php الخاصة بالقالب
 * توفّر نفس البنية (page-header + breadcrumb) بنفس تصميم الموقع الأصلي تمامًا.
 */

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'WooCommerce' ) ) return;

/* إلغاء غلاف <div class="woocommerce"> ونظام breadcrumb الافتراضيين لأننا نبنيهما يدويًا. */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/* إلغاء عنوان الصفحة الافتراضي المكرر (نضع عنواننا داخل .page-header بأنفسنا). */
add_filter( 'woocommerce_show_page_title', '__return_false' );

/* شارة "خصم" و"من X ج.م" تتبعان تنسيق السعر بنفس عملة الموقع (جنيه مصري: ج.م). */
add_filter( 'woocommerce_currency_symbol', function ( $symbol, $currency ) {
	return $currency === 'EGP' ? 'ج.م' : $symbol;
}, 10, 2 );

/* عدد المنتجات في صفحة المتجر + الأعمدة يُقرآن من لوحة خيارات القالب (تبويب "المتجر"). */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' ); // نستخدم أنماطنا الخاصة بالكامل بدل أنماط WooCommerce الافتراضية.

/* -------- شارة "متوفر في المخزون" بنفس تصميم style.css -------- */
add_action( 'woocommerce_single_product_summary', function () {
	if ( ko_opt( 'show_stock_badge', '1' ) !== '1' ) return;
	global $product;
	if ( ! $product || ! $product->is_in_stock() ) return;
	$qty = $product->get_stock_quantity();
	echo '<div class="stock-badge mb-3"><i class="fa-solid fa-circle-check"></i> متوفر في المخزون' . ( $qty ? ' (' . esc_html( $qty ) . ' قطعة)' : '' ) . '</div>';
}, 25 );

/* -------- صف مزايا الثقة أسفل زر الإضافة للسلة (من إعدادات تبويب "صفحة المنتج") -------- */
add_action( 'woocommerce_single_product_summary', function () {
	if ( ko_opt( 'show_trust_badges', '1' ) !== '1' ) return;
	$badges = array(
		array( 'fa-solid fa-truck-fast', ko_opt( 'trust_badge_1', 'توصيل 2-4 أيام عمل' ) ),
		array( 'fa-solid fa-money-bill-wave', ko_opt( 'trust_badge_2', 'الدفع عند الاستلام' ) ),
		array( 'fa-solid fa-shield-halved', ko_opt( 'trust_badge_3', 'ضمان ودعم فني' ) ),
		array( 'fa-solid fa-box', ko_opt( 'trust_badge_4', 'تغليف مقاوم للصدمات' ) ),
	);
	echo '<div class="divider mb-3"></div><div class="grid grid-2" style="gap:14px;">';
	foreach ( $badges as $b ) {
		echo '<div class="feature-mini"><i class="' . esc_attr( $b[0] ) . '"></i> ' . esc_html( $b[1] ) . '</div>';
	}
	echo '</div>';
}, 35 );

/* -------- فلتر "السعر الأقصى" من الشريط الجانبي في صفحة المتجر (?max_price=) -------- */
add_action( 'woocommerce_product_query', function ( $q ) {
	if ( empty( $_GET['max_price'] ) ) return;
	$max_price = floatval( $_GET['max_price'] );
	$meta_query = $q->get( 'meta_query' ) ?: array();
	$meta_query[] = array( 'key' => '_price', 'value' => $max_price, 'compare' => '<=', 'type' => 'NUMERIC' );
	$q->set( 'meta_query', $meta_query );
} );

/* -------- نص "نفدت الكمية" مخصص (من تبويب "المتجر") -------- */
add_filter( 'woocommerce_get_availability_text', function ( $text, $product ) {
	if ( $product && ! $product->is_in_stock() ) {
		$custom = ko_opt( 'out_of_stock_text', '' );
		if ( $custom ) return $custom;
	}
	return $text;
}, 10, 2 );

/* -------- شريحة السعر: "يبدأ من" على منتج متغيّر (variable product) داخل الحلقة -------- */
add_filter( 'woocommerce_get_price_html', function ( $html, $product ) {
	if ( $product->is_type( 'variable' ) && ! is_product() ) {
		$html = '<span class="form-hint" style="display:block;">يبدأ من</span>' . $html;
	}
	return $html;
}, 10, 2 );

/** يبني محتوى صندوق السلة المنزلقة (Mini Cart): عناصر مختصرة + المجموع + أزرار السلة/الدفع. */
function ko_mini_cart_content_html() {
	if ( ! WC()->cart || WC()->cart->is_empty() ) {
		ob_start(); ?>
		<div class="empty-state" style="padding:32px 16px;">
			<i class="fa-solid fa-cart-shopping" style="font-size:40px;"></i>
			<h3 style="font-size:16px;"><?php esc_html_e( 'سلتك فارغة حاليًا', 'kheyata-ossol' ); ?></h3>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary btn-sm mt-2"><?php esc_html_e( 'تصفّح المتجر', 'kheyata-ossol' ); ?></a>
		</div>
		<?php return ob_get_clean();
	}
	ob_start(); ?>
	<div class="mini-cart-items">
		<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 ) continue;
			$permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
			?>
			<div class="mini-cart-item">
				<a href="<?php echo esc_url( $permalink ); ?>"><?php echo wp_kses_post( $_product->get_image( array( 56, 56 ) ) ); ?></a>
				<div class="mini-cart-item-info">
					<a href="<?php echo esc_url( $permalink ); ?>"><?php echo wp_kses_post( $_product->get_name() ); ?></a>
					<span><?php echo esc_html( $cart_item['quantity'] ); ?> × <?php echo wp_kses_post( wc_price( wc_get_price_to_display( $_product ) ) ); ?></span>
				</div>
				<a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="mini-cart-item-remove" aria-label="<?php esc_attr_e( 'إزالة', 'kheyata-ossol' ); ?>"><i class="fa-solid fa-xmark"></i></a>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="mini-cart-footer">
		<div class="summary-row total"><span><?php esc_html_e( 'المجموع الفرعي', 'kheyata-ossol' ); ?></span><span><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></span></div>
		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn btn-outline btn-block mt-2"><?php esc_html_e( 'عرض السلة', 'kheyata-ossol' ); ?></a>
		<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn btn-primary btn-block mt-2"><?php esc_html_e( 'إتمام الطلب', 'kheyata-ossol' ); ?></a>
	</div>
	<?php
	return ob_get_clean();
}

/* -------- تحديث شارة عداد السلة + محتوى السلة المنزلقة عبر AJAX Fragments (بدون إعادة تحميل الصفحة) -------- */
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
	$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	ob_start();
	?>
	<span class="cart-badge" style="<?php echo $count > 0 ? '' : 'display:none;'; ?>"><?php echo esc_html( $count ); ?></span>
	<?php
	$fragments['.cart-badge'] = ob_get_clean();
	$fragments['#mini-cart-body'] = '<div class="mini-cart-body" id="mini-cart-body">' . ko_mini_cart_content_html() . '</div>';
	return $fragments;
} );

/* -------- الدفع عند الاستلام فقط: توصية تلقائية عند أول تفعيل للقالب --------
   ملاحظة: قيمتا "تكلفة الشحن" و"حد الشحن المجاني" في تبويب "المتجر" بخيارات القالب
   استرشاديتان للعرض فقط؛ اضبط نفس القيمتين فعليًا من: WooCommerce ← الشحن ← مناطق الشحن. */
add_action( 'after_switch_theme', function () {
	if ( ! class_exists( 'WC_Payment_Gateways' ) ) return;
	$settings = get_option( 'woocommerce_cod_settings', array() );
	if ( empty( $settings ) ) {
		update_option( 'woocommerce_cod_settings', array(
			'enabled'      => 'yes',
			'title'        => 'الدفع عند الاستلام',
			'description'  => 'ادفع نقدًا للمندوب عند استلام طلبك.',
			'instructions' => 'ادفع نقدًا للمندوب عند استلام طلبك.',
		) );
	}
} );

/** أعلى سعر منتج فعلي في المتجر — يُستخدم كحد أقصى لشريط فلتر السعر (مع تخزين مؤقت لمدة ساعة). */
function ko_get_max_product_price() {
	$cached = get_transient( 'ko_max_product_price' );
	if ( $cached !== false ) return (float) $cached;
	global $wpdb;
	$max = $wpdb->get_var( "SELECT MAX(CAST(meta_value AS UNSIGNED)) FROM {$wpdb->postmeta} WHERE meta_key = '_price'" );
	$max = $max ? ceil( $max / 50 ) * 50 : 9000;
	set_transient( 'ko_max_product_price', $max, HOUR_IN_SECONDS );
	return (float) $max;
}
