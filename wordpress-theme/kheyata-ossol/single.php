<?php
get_header();
?>
<main>
	<div class="page-header">
		<div class="container">
			<h1><?php the_title(); ?></h1>
			<?php ko_render_breadcrumb(); ?>
		</div>
	</div>
	<section>
		<div class="container grid" style="grid-template-columns: 1fr; gap:32px; max-width:820px;">
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="mb-4"><?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;border-radius:var(--radius-lg);' ) ); ?></div>
					<?php endif; ?>
					<div class="entry-content"><?php the_content(); ?></div>
				</article>
			<?php endwhile; ?>
		</div>
	</section>
</main>
<?php get_footer(); ?>
