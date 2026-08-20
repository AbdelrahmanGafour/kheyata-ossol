<?php
/**
 * تجاوز صفحة السلة الافتراضية في WooCommerce ببطاقات .cart-item بنفس تصميم الموقع
 * الأصلي تمامًا، بدل الجدول الافتراضي — مع الحفاظ على كل منطق WooCommerce الحقيقي
 * (تحديث الكمية، الحذف، الكوبونات، حساب الشحن) عبر خطافاته ودواله الأصلية.
 *
 * @see woocommerce/templates/cart/cart.php (النسخة الأصلية في الإضافة)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<div class="cart-layout" id="cart-layout">
		<div>
			<div id="cart-items">
				<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) continue;
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					$cats = wc_get_product_category_list( $product_id );
					?>
					<div class="cart-item" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">
						<?php if ( $product_permalink ) : ?><a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( $_product->get_image() ); ?></a>
						<?php else : echo wp_kses_post( $_product->get_image() ); endif; ?>

						<div>
							<?php if ( $cats ) : ?><span class="cat"><?php echo wp_kses_post( $cats ); ?></span><?php endif; ?>
							<?php if ( $product_permalink ) : ?>
								<a href="<?php echo esc_url( $product_permalink ); ?>"><h3><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></h3></a>
							<?php else : ?>
								<h3><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></h3>
							<?php endif; ?>
							<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
							<div class="price"><?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore ?></div>
						</div>

						<div class="cart-item-actions">
							<?php if ( $_product->is_sold_individually() ) : ?>
								<span class="form-hint">1</span>
							<?php else : ?>
								<div class="cart-qty">
									<button type="button" class="ko-qty-minus" aria-label="إنقاص الكمية"><i class="fa-solid fa-minus"></i></button>
									<?php echo woocommerce_quantity_input( array(
										'input_name'   => "cart[{$cart_item_key}][qty]",
										'input_value'  => $cart_item['quantity'],
										'max_value'    => $_product->get_max_purchase_quantity(),
										'min_value'    => '0',
										'product_name' => $_product->get_name(),
									), $_product, false ); ?>
									<button type="button" class="ko-qty-plus" aria-label="زيادة الكمية"><i class="fa-solid fa-plus"></i></button>
								</div>
							<?php endif; ?>
							<a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="remove-btn" aria-label="<?php esc_attr_e( 'إزالة هذا المنتج', 'kheyata-ossol' ); ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>">
								<i class="fa-solid fa-trash-can"></i> حذف
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="flex flex-between mt-3" style="flex-wrap:wrap; gap:16px;">
				<div class="flex gap-2" style="align-items:center;">
					<input type="text" name="coupon_code" class="form-control" id="coupon_code" placeholder="كود الخصم" style="max-width:200px;">
					<button type="submit" class="btn btn-outline btn-sm" name="apply_coupon" value="apply_coupon">تطبيق</button>
				</div>
				<button type="submit" class="btn btn-primary btn-sm" name="update_cart" value="<?php esc_attr_e( 'تحديث السلة', 'kheyata-ossol' ); ?>"><i class="fa-solid fa-rotate"></i> تحديث السلة</button>
			</div>

			<?php do_action( 'woocommerce_cart_coupon' ); ?>
		</div>

		<aside class="summary-card">
			<?php do_action( 'woocommerce_cart_collaterals' ); ?>
		</aside>
	</div>

	<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php
if ( WC()->cart->is_empty() ) : ?>
	<div class="empty-state" id="cart-empty">
		<i class="fa-solid fa-cart-shopping"></i>
		<h3><?php esc_html_e( 'سلتك فارغة حاليًا', 'kheyata-ossol' ); ?></h3>
		<p class="mb-3"><?php esc_html_e( 'لم تقم بإضافة أي منتجات بعد. تصفّح المتجر واختر ما يناسبك.', 'kheyata-ossol' ); ?></p>
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'تصفّح المتجر', 'kheyata-ossol' ); ?></a>
	</div>
<?php endif;

do_action( 'woocommerce_after_cart' ); ?>

<style>
	.cart-layout { display: grid; grid-template-columns: 1fr; gap: 32px; align-items: start; }
	@media (min-width: 900px) { .cart-layout { grid-template-columns: 1fr 360px; } }
	.cart-item { display: grid; grid-template-columns: 84px 1fr auto; gap: 16px; align-items: center; padding: 18px; background: var(--color-white); border-radius: var(--radius-md); box-shadow: var(--shadow-soft); margin-bottom: 14px; }
	.cart-item img { width: 84px; height: 84px; object-fit: cover; border-radius: 10px; }
	.cart-item h3 { font-size: 16px; margin-bottom: 6px; }
	.cart-item .cat { font-size: 12px; color: var(--color-accent-dark); font-weight: 700; display: block; margin-bottom: 4px; }
	.cart-item .price { font-family: var(--font-numeric); font-weight: 700; color: var(--color-primary); }
	.cart-qty { display: flex; align-items: center; border: 1.5px solid var(--color-border); border-radius: var(--radius-sm); width: fit-content; }
	.cart-qty button { width: 34px; height: 34px; color: var(--color-primary); display:flex; align-items:center; justify-content:center; }
	.cart-qty .qty { width: 40px; text-align: center; border: none; font-weight: 700; -moz-appearance: textfield; }
	.cart-item-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 10px; }
	.remove-btn { color: var(--color-danger); font-size: 14px; display:flex; align-items:center; gap:6px; }
	.summary-card { background: var(--color-white); border-radius: var(--radius-lg); box-shadow: var(--shadow-soft); padding: 28px; position: sticky; top: calc(var(--header-height) + 24px); }
	.summary-card h2 { font-size: 20px; margin-bottom: 16px; }
	.summary-card .button, .summary-card a.checkout-button { display:flex; width:100%; justify-content:center; margin-top:16px; }
	@media (max-width: 560px) {
		.cart-item { grid-template-columns: 64px 1fr; }
		.cart-item-actions { grid-column: span 2; flex-direction: row; justify-content: space-between; align-items: center; }
		.cart-item img { width: 64px; height: 64px; }
	}
</style>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.ko-qty-minus, .ko-qty-plus').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var input = btn.parentElement.querySelector('input.qty');
				if (!input) return;
				var step = parseFloat(input.step) || 1;
				var min = parseFloat(input.min) || 0;
				var max = input.max ? parseFloat(input.max) : Infinity;
				var val = parseFloat(input.value) || 0;
				val = btn.classList.contains('ko-qty-plus') ? Math.min(max, val + step) : Math.max(min, val - step);
				input.value = val;
			});
		});
	});
</script>
