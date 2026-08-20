<?php
/**
 * تجاوز صندوق "ملخص الطلب" الجانبي في السلة بنفس تصميم .summary-card/.summary-row الأصلي.
 * @see woocommerce/templates/cart/cart-totals.php
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<h3 class="mb-3"><?php esc_html_e( 'ملخص الطلب', 'kheyata-ossol' ); ?></h3>

	<div class="summary-row">
		<span><?php esc_html_e( 'عدد القطع', 'kheyata-ossol' ); ?></span>
		<span><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
	</div>

	<div class="summary-row">
		<span><?php esc_html_e( 'المجموع الفرعي', 'kheyata-ossol' ); ?></span>
		<span><?php wc_cart_totals_subtotal_html(); ?></span>
	</div>

	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<div class="summary-row">
			<span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
			<span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
		</div>
	<?php endforeach; ?>

	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
		<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
		<?php wc_cart_totals_shipping_html(); ?>
		<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
	<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
		<div class="summary-row">
			<span><?php esc_html_e( 'الشحن', 'kheyata-ossol' ); ?></span>
			<span><?php woocommerce_shipping_calculator(); ?></span>
		</div>
	<?php endif; ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<div class="summary-row">
			<span><?php echo esc_html( $fee->name ); ?></span>
			<span><?php wc_cart_totals_fee_html( $fee ); ?></span>
		</div>
	<?php endforeach; ?>

	<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) :
		if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) :
			foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
				<div class="summary-row"><span><?php echo esc_html( $tax->label ); ?></span><span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span></div>
			<?php endforeach;
		else : ?>
			<div class="summary-row"><span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span><span><?php wc_cart_totals_taxes_total_html(); ?></span></div>
		<?php endif;
	endif; ?>

	<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

	<div class="summary-row total">
		<span><?php esc_html_e( 'الإجمالي', 'kheyata-ossol' ); ?></span>
		<span><?php wc_cart_totals_order_total_html(); ?></span>
	</div>

	<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	<p class="form-hint mb-3">
		<?php
		$threshold = (float) ko_opt( 'free_shipping_threshold', '1500' );
		if ( $threshold > 0 ) printf( esc_html__( 'التوصيل مجاني للطلبات فوق %s ج.م', 'kheyata-ossol' ), esc_html( number_format_i18n( $threshold ) ) );
		?>
	</p>

	<div class="wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="link-arrow mt-3" style="justify-content:center; display:flex;"><?php esc_html_e( 'متابعة التسوق', 'kheyata-ossol' ); ?> <i class="fa-solid fa-arrow-left"></i></a>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
