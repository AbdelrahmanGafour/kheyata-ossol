<?php
/**
 * Template Name: صفحة من نحن
 * كل نص وصورة في هذه الصفحة قابل للتحرير من حقول Secure Custom Fields (مجموعة "محتوى صفحة من نحن").
 */
get_header();
$pid = get_the_ID();
?>

<main>

	<div class="page-header">
		<div class="container">
			<h1><?php the_title(); ?></h1>
			<?php ko_render_breadcrumb(); ?>
		</div>
	</div>

	<section>
		<div class="container grid grid-2" style="align-items:center; gap:48px;">
			<div data-reveal>
				<span class="section-eyebrow"><?php echo esc_html( ko_field( 'story_eyebrow', $pid, 'قصتنا' ) ); ?></span>
				<h2 class="mb-3"><?php echo esc_html( ko_field( 'story_heading', $pid, 'للخياطة أصول.. وللأصول أهلها' ) ); ?></h2>
				<p class="mb-2"><?php echo esc_html( ko_field( 'story_text_1', $pid, '' ) ); ?></p>
				<p><?php echo esc_html( ko_field( 'story_text_2', $pid, '' ) ); ?></p>
			</div>
			<div class="grid" style="grid-template-columns: 1fr 1fr; gap:16px;" data-reveal>
				<?php $img1 = ko_field( 'story_image_1', $pid, KO_THEME_URI . '/assets/images/about/about-measuring.jpg' ); ?>
				<?php $img2 = ko_field( 'story_image_2', $pid, KO_THEME_URI . '/assets/images/hero/hero-vintage.jpg' ); ?>
				<img src="<?php echo esc_url( $img1 ); ?>" alt="<?php the_title_attribute(); ?>" style="border-radius:16px; height:100%; object-fit:cover;">
				<img src="<?php echo esc_url( $img2 ); ?>" alt="<?php the_title_attribute(); ?>" style="border-radius:16px; margin-top:32px;">
			</div>
		</div>
	</section>

	<section class="section-alt">
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-eyebrow"><?php echo esc_html( ko_field( 'why_choose_eyebrow', $pid, 'لماذا تختارنا' ) ); ?></span>
				<h2><?php echo esc_html( ko_field( 'why_choose_heading', $pid, 'خبرة حقيقية تصنع فرقًا' ) ); ?></h2>
				<p><?php echo esc_html( ko_field( 'why_choose_text', $pid, '' ) ); ?></p>
			</div>
			<div class="grid grid-4">
				<?php foreach ( ko_field( 'why_choose_items', $pid, array() ) as $item ) : ?>
					<div class="card service-card" data-reveal data-anime-move>
						<div class="service-icon"><i class="<?php echo esc_attr( $item['icon'] ); ?>"></i></div>
						<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<p><?php echo esc_html( $item['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section>
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-eyebrow"><?php echo esc_html( ko_field( 'pillars_eyebrow', $pid, 'رسالتنا وركائزنا' ) ); ?></span>
				<h2><?php echo esc_html( ko_field( 'pillars_heading', $pid, 'ثلاث ركائز تصنع الفرق' ) ); ?></h2>
			</div>
			<div class="grid grid-3">
				<?php foreach ( ko_field( 'pillars', $pid, array() ) as $item ) : ?>
					<div class="card service-card" data-reveal data-anime-move>
						<div class="service-icon"><i class="<?php echo esc_attr( $item['icon'] ); ?>"></i></div>
						<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<p><?php echo esc_html( $item['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php $stats = ko_field( 'about_stats', $pid, array() ); if ( $stats ) : ?>
	<section class="section-alt">
		<div class="container">
			<div class="stats-card" data-reveal>
				<?php foreach ( $stats as $s ) : ?>
					<div class="stat-item">
						<div class="stat-icon"><i class="<?php echo esc_attr( $s['icon'] ); ?>"></i></div>
						<strong><span data-counter="<?php echo esc_attr( $s['number'] ); ?>">0</span><?php echo esc_html( $s['suffix'] ); ?></strong>
						<span><?php echo esc_html( $s['label'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<section>
		<div class="container text-center" style="max-width:820px;" data-reveal>
			<i class="fa-solid fa-quote-right" style="font-size:36px; color:var(--color-accent);"></i>
			<h2 class="mt-3 mb-2"><?php echo esc_html( ko_field( 'quote_heading', $pid, '"للخياطة أصول.. وللأصول أهلها"' ) ); ?></h2>
			<p><?php echo esc_html( ko_field( 'quote_text', $pid, '' ) ); ?></p>
		</div>
	</section>

	<section class="section-alt">
		<div class="container">
			<div class="cta-band" data-reveal>
				<h2><?php echo esc_html( ko_field( 'final_cta_heading', $pid, 'ابدأ رحلتك معنا اليوم' ) ); ?></h2>
				<p><?php echo esc_html( ko_field( 'final_cta_text', $pid, '' ) ); ?></p>
				<div class="flex flex-center gap-2" style="flex-wrap:wrap;">
					<a href="<?php echo class_exists( 'WooCommerce' ) ? esc_url( wc_get_page_permalink( 'shop' ) ) : '#'; ?>" class="btn btn-accent">تصفّح المتجر</a>
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline-light">تواصل معنا</a>
				</div>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
