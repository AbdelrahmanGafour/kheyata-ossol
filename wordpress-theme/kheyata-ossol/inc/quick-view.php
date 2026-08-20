<?php
/**
 * المعاينة السريعة (Quick View): يعيد هذا المعالج تفاصيل منتج مختصرة عبر AJAX لعرضها
 * داخل نافذة منبثقة (footer.php → #quick-view-modal) بدون مغادرة صفحة المتجر/الرئيسية،
 * معتمدًا على قوالب WooCommerce الأصلية لنموذج "أضف للسلة" (بما فيها التنويعات) حتى
 * يعمل الشراء والتسعير بشكل صحيح 100% دون إعادة بناء منطق WooCommerce يدويًا.
 */

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'WooCommerce' ) ) return;

add_action( 'wp_ajax_ko_quick_view', 'ko_ajax_quick_view' );
add_action( 'wp_ajax_nopriv_ko_quick_view', 'ko_ajax_quick_view' );

function ko_ajax_quick_view() {
	check_ajax_referer( 'ko_quick_view', 'nonce' );

	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	$product    = $product_id ? wc_get_product( $product_id ) : null;
	if ( ! $product || ! $product->is_visible() ) {
		wp_send_json_error( array( 'message' => __( 'المنتج غير متاح.', 'kheyata-ossol' ) ) );
	}

	global $post;
	$post = get_post( $product_id );
	setup_postdata( $post );
	$GLOBALS['product'] = $product;

	$cats = wc_get_product_category_list( $product_id );

	ob_start();
	?>
	<div class="quick-view-grid">
		<div class="quick-view-image"><?php echo wp_kses_post( $product->get_image( 'large' ) ); ?></div>
		<div class="quick-view-info">
			<?php if ( $cats ) : ?><span class="badge-pill mb-2"><?php echo wp_kses_post( $cats ); ?></span><?php endif; ?>
			<h2 style="font-size:26px;"><?php echo esc_html( $product->get_name() ); ?></h2>
			<div class="mt-2 mb-2"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
			<div class="quick-view-desc mb-3">
				<?php
				$short = $product->get_short_description();
				echo wp_kses_post( wpautop( $short ? $short : wp_trim_words( $product->get_description(), 26 ) ) );
				?>
			</div>
			<?php woocommerce_template_single_add_to_cart(); ?>
			<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="link-arrow mt-3">عرض التفاصيل الكاملة <i class="fa-solid fa-arrow-left"></i></a>
		</div>
	</div>
	<?php
	$html = ob_get_clean();
	wp_reset_postdata();

	wp_send_json_success( array( 'html' => $html ) );
}
