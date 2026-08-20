<?php
/**
 * صفحة تفاصيل المنتج. نستخدم خطافات (hooks) WooCommerce الأصلية لعرض المعرض ونموذج
 * الإضافة للسلة/التنويعات (بدلاً من إعادة بنائها يدويًا) لضمان عمل الأسعار والمخزون
 * والتنويعات بشكل صحيح 100%، مع تطبيق تصميم القالب بالكامل عبر CSS ومنتقي تنويعات
 * على هيئة أزرار Pill (assets/js/shop.js) بدل القائمة المنسدلة الافتراضية.
 */
get_header();
?>

<main>

	<div class="page-header">
		<div class="container">
			<h1><?php the_title(); ?></h1>
			<div class="breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a><span>/</span>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">المتجر</a><span>/</span>
				<span><?php the_title(); ?></span>
			</div>
		</div>
	</div>

	<section>
		<div class="container">
			<?php while ( have_posts() ) : the_post(); global $product; ?>
			<div class="product-detail">
				<div class="product-gallery" data-reveal>
					<?php woocommerce_show_product_images(); ?>
				</div>
				<div data-reveal>
					<?php
					$cats = wc_get_product_category_list( get_the_ID() );
					if ( $cats ) echo '<span class="badge-pill mb-2">' . wp_kses_post( $cats ) . '</span>';
					do_action( 'woocommerce_single_product_summary' );
					?>
				</div>
			</div>
			<?php endwhile; ?>
		</div>
	</section>

	<?php if ( ko_opt( 'show_related_products', '1' ) === '1' ) : ?>
	<section class="section-alt">
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-eyebrow">قد يعجبك أيضًا</span>
				<h2>منتجات ذات صلة</h2>
			</div>
			<div class="grid grid-4">
				<?php
				global $product;
				$related_ids = wc_get_related_products( $product->get_id(), (int) ko_opt( 'related_products_count', '4' ) );
				foreach ( $related_ids as $rid ) {
					$rp = wc_get_product( $rid );
					if ( $rp ) echo ko_product_card_html( $rp );
				}
				?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php
	global $product;
	if ( ko_opt( 'sticky_mobile_cart_bar', '1' ) === '1' && $product instanceof WC_Product ) : ?>
		<div class="sticky-add-to-cart" id="sticky-add-to-cart">
			<?php echo wp_kses_post( $product->get_image( array( 44, 44 ) ) ); ?>
			<div class="sticky-add-to-cart-info">
				<strong><?php echo esc_html( $product->get_name() ); ?></strong>
				<span><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
			</div>
			<button type="button" class="btn btn-accent btn-sm sticky-add-to-cart-btn"><i class="fa-solid fa-cart-plus"></i> <?php esc_html_e( 'أضف للسلة', 'kheyata-ossol' ); ?></button>
		</div>
	<?php endif; ?>

</main>

<style>
	.product-detail { display: grid; grid-template-columns: 1fr; gap: 40px; }
	@media (min-width: 900px) { .product-detail { grid-template-columns: 1fr 1fr; } }
	.product-gallery .woocommerce-product-gallery__wrapper img { border-radius: var(--radius-lg); box-shadow: var(--shadow-soft); }
	.product-gallery .flex-control-thumbs { display: flex; gap: 10px; margin-top: 12px; list-style: none; padding: 0; }
	.product-gallery .flex-control-thumbs li { width: 64px; height: 64px; }
	.product-gallery .flex-control-thumbs img { width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-sm); opacity: .65; transition: opacity .2s ease; cursor: pointer; }
	.product-gallery .flex-control-thumbs img.flex-active, .product-gallery .flex-control-thumbs img:hover { opacity: 1; }
	.qty-selector, .quantity { display: flex; align-items: center; gap: 0; border: 1.5px solid var(--color-border); border-radius: var(--radius-sm); width: fit-content; }
	.quantity input.qty { width: 52px; text-align: center; border: none; font-weight: 700; font-size: 16px; }
	.woocommerce div.product form.cart .variations { margin-bottom: 16px; }
	.woocommerce div.product form.cart .variations table, .woocommerce div.product form.cart .variations tbody, .woocommerce div.product form.cart .variations tr, .woocommerce div.product form.cart .variations td { display: block; border: none; padding: 0; }
	.woocommerce div.product form.cart .variations label { font-weight: 700; font-size: 14px; display: block; margin-bottom: 10px; }
	.stock-badge { display: inline-flex; align-items: center; gap: 6px; color: var(--color-primary); font-size: 14px; font-weight: 700; }
	.feature-mini { display: flex; align-items: center; gap: 10px; font-size: 14px; color: var(--color-text-muted); }
	.feature-mini i { color: var(--color-accent-dark); }
	.product_meta { font-size: 13px; color: var(--color-text-muted); margin-top: 16px; }
</style>

<?php get_footer(); ?>
