<?php
/**
 * قالب احتياطي عام (المدونة/الأرشيفات غير المخصصة). يُستخدم أيضًا كأرشيف مدونة إن أُضيفت مقالات لاحقًا.
 */
get_header();
?>

<main>

	<div class="page-header">
		<div class="container">
			<h1><?php echo is_home() ? esc_html__( 'المدونة', 'kheyata-ossol' ) : wp_kses_post( get_the_archive_title() ); ?></h1>
			<?php ko_render_breadcrumb(); ?>
		</div>
	</div>

	<section>
		<div class="container grid" style="grid-template-columns: 1fr; gap:32px;">
			<div class="<?php echo is_active_sidebar( 'blog-sidebar' ) ? 'grid' : ''; ?>" style="<?php echo is_active_sidebar( 'blog-sidebar' ) ? 'grid-template-columns:1fr;gap:32px;' : ''; ?>">
				<?php if ( have_posts() ) : ?>
					<div class="grid grid-3">
						<?php while ( have_posts() ) : the_post(); ?>
							<article <?php post_class( 'card' ); ?> style="overflow:hidden;">
								<?php if ( has_post_thumbnail() ) : ?>
									<a href="<?php the_permalink(); ?>" class="product-media" style="aspect-ratio:16/10;">
										<?php the_post_thumbnail( 'ko-card', array( 'loading' => 'lazy' ) ); ?>
									</a>
								<?php endif; ?>
								<div class="product-body">
									<a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
									<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
									<a href="<?php the_permalink(); ?>" class="link-arrow">اقرأ المزيد <i class="fa-solid fa-arrow-left"></i></a>
								</div>
							</article>
						<?php endwhile; ?>
					</div>
					<div class="mt-4"><?php the_posts_pagination(); ?></div>
				<?php else : ?>
					<div class="empty-state">
						<i class="fa-solid fa-inbox"></i>
						<h3><?php esc_html_e( 'لا يوجد محتوى بعد', 'kheyata-ossol' ); ?></h3>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
