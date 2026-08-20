<?php
get_header();
?>
<main>
	<div class="container" style="padding-top: calc(var(--header-height) + 80px); padding-bottom:80px;">
		<div class="empty-state">
			<i class="fa-solid fa-scissors"></i>
			<h3><?php esc_html_e( 'الصفحة غير موجودة', 'kheyata-ossol' ); ?></h3>
			<p class="mb-3"><?php esc_html_e( 'الرابط الذي وصلت إليه غير صحيح أو تم نقل الصفحة.', 'kheyata-ossol' ); ?></p>
			<div class="flex flex-center gap-2" style="flex-wrap:wrap;">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">العودة للرئيسية</a>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-outline">تصفّح المتجر</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</main>
<?php get_footer(); ?>
