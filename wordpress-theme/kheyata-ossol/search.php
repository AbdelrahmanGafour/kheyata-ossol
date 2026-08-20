<?php
/**
 * نتائج البحث: إن كان البحث عن منتجات (post_type=product) تُعرض كبطاقات منتج،
 * وإلا تُعرض كنتائج مدونة عادية.
 */
get_header();
$is_product_search = get_query_var( 'post_type' ) === 'product';
?>
<main>
	<div class="page-header">
		<div class="container">
			<h1><?php printf( esc_html__( 'نتائج البحث عن: %s', 'kheyata-ossol' ), '<span dir="rtl">' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
			<?php ko_render_breadcrumb(); ?>
		</div>
	</div>
	<section>
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="grid <?php echo $is_product_search ? 'grid-3' : 'grid-3'; ?>">
					<?php while ( have_posts() ) : the_post();
						if ( $is_product_search && class_exists( 'WC_Product' ) ) {
							global $product;
							$product = wc_get_product( get_the_ID() );
							if ( $product ) echo ko_product_card_html( $product );
						} else { ?>
							<article <?php post_class( 'card' ); ?> style="padding:20px;">
								<a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
							</article>
						<?php }
					endwhile; ?>
				</div>
				<div class="mt-4"><?php the_posts_pagination(); ?></div>
			<?php else : ?>
				<div class="empty-state">
					<i class="fa-solid fa-magnifying-glass-minus"></i>
					<h3><?php esc_html_e( 'لا توجد نتائج مطابقة', 'kheyata-ossol' ); ?></h3>
					<a href="<?php echo class_exists( 'WooCommerce' ) ? esc_url( wc_get_page_permalink( 'shop' ) ) : esc_url( home_url( '/' ) ); ?>" class="btn btn-primary mt-3">تصفّح المتجر</a>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>
