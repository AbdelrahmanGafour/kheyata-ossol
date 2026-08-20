<?php
/**
 * الصفحة الرئيسية. كل نص وصورة وفيديو في هذه الصفحة قابل للتحرير من لوحة تحكم ووردبريس
 * عبر حقول Secure Custom Fields (مجموعة "محتوى الصفحة الرئيسية")، والتصنيفات/المنتجات
 * تُقرأ حيًّا من WooCommerce.
 */
get_header();

$hero_video  = ko_field( 'hero_video', get_the_ID(), '' );
$hero_poster = ko_field( 'hero_poster', get_the_ID(), KO_THEME_URI . '/assets/images/hero/hero-main.jpg' );
?>

<main>

	<!-- Hero -->
	<section class="hero">
		<div class="hero-bg">
			<?php if ( $hero_video ) : ?>
				<video autoplay muted loop playsinline preload="auto" poster="<?php echo esc_url( $hero_poster ); ?>" aria-hidden="true">
					<source src="<?php echo esc_url( $hero_video ); ?>" type="video/mp4">
				</video>
			<?php else : ?>
				<img class="hero-bg-fallback" src="<?php echo esc_url( $hero_poster ); ?>" alt="">
			<?php endif; ?>
		</div>
		<div class="hero-main">
			<div class="container hero-content">
				<span class="hero-badge"><i class="fa-solid fa-award"></i> <?php echo esc_html( ko_field( 'hero_badge_text', get_the_ID(), 'خبرة أكثر من 5 سنوات في عالم الخياطة' ) ); ?></span>
				<h1><?php echo wp_kses_post( nl2br( ko_field( 'hero_heading', get_the_ID(), 'للخياطة أصول.. وللأصول أهلها' ) ) ); ?></h1>
				<p class="lead"><?php echo esc_html( ko_field( 'hero_lead', get_the_ID(), 'المنصة الأولى المتكاملة في عالم الخياطة.' ) ); ?></p>
				<div class="hero-actions">
					<a href="<?php echo esc_url( ko_field( 'hero_btn1_link', get_the_ID(), '/store/' ) ); ?>" class="btn btn-accent"><i class="fa-solid fa-cart-shopping"></i> <?php echo esc_html( ko_field( 'hero_btn1_text', get_the_ID(), 'تسوّق الآن' ) ); ?></a>
					<a href="<?php echo esc_url( ko_field( 'hero_btn2_link', get_the_ID(), '/about/' ) ); ?>" class="btn btn-outline-light"><i class="fa-solid fa-play"></i> <?php echo esc_html( ko_field( 'hero_btn2_text', get_the_ID(), 'تعرف علينا' ) ); ?></a>
				</div>
			</div>
		</div>
		<div class="hero-stats-bar">
			<div class="container">
				<div class="hero-stats-row">
					<?php
					$stats = ko_field( 'hero_stats', get_the_ID(), array() );
					if ( ! $stats ) {
						$stats = array(
							array( 'number' => 5, 'suffix' => '+', 'label' => 'سنوات خبرة' ),
							array( 'number' => 1200, 'suffix' => '+', 'label' => 'عميل راضٍ' ),
							array( 'number' => 15, 'suffix' => '+', 'label' => 'منتج أصلي' ),
							array( 'number' => 27, 'suffix' => '', 'label' => 'محافظة توصيل' ),
						);
					}
					foreach ( $stats as $s ) : ?>
						<div class="stat-item">
							<strong><span data-counter="<?php echo esc_attr( $s['number'] ); ?>">0</span><?php echo esc_html( $s['suffix'] ); ?></strong>
							<span><?php echo esc_html( $s['label'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- التصنيفات -->
	<section class="section-alt">
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-eyebrow"><?php echo esc_html( ko_field( 'categories_eyebrow', get_the_ID(), 'التصنيفات' ) ); ?></span>
				<h2><?php echo esc_html( ko_field( 'categories_heading', get_the_ID(), 'تسوّق حسب احتياجك' ) ); ?></h2>
			</div>
			<div class="carousel-wrap" data-reveal>
				<?php ko_carousel_arrows_open(); ?>
				<div class="carousel-track" id="categories-grid">
					<?php
					if ( class_exists( 'WooCommerce' ) ) {
						$cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'exclude' => array( get_option( 'default_product_cat' ) ) ) );
						foreach ( $cats as $term ) echo ko_category_card_html( $term );
					}
					?>
				</div>
				<?php ko_carousel_arrows_close(); ?>
			</div>
		</div>
	</section>

	<!-- أحدث المنتجات -->
	<?php if ( class_exists( 'WooCommerce' ) ) :
		$newest = wc_get_products( array( 'status' => 'publish', 'limit' => 8, 'orderby' => 'date', 'order' => 'DESC' ) );
		if ( $newest ) : ?>
	<section>
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-eyebrow"><?php echo esc_html( ko_field( 'newest_eyebrow', get_the_ID(), 'وصل حديثًا' ) ); ?></span>
				<h2><?php echo esc_html( ko_field( 'newest_heading', get_the_ID(), 'أحدث المنتجات' ) ); ?></h2>
				<p><?php echo esc_html( ko_field( 'newest_text', get_the_ID(), '' ) ); ?></p>
			</div>
			<div class="carousel-wrap" data-reveal>
				<?php ko_carousel_arrows_open(); ?>
				<div class="carousel-track products" id="newest-products">
					<?php foreach ( $newest as $p ) echo ko_product_card_html( $p ); ?>
				</div>
				<?php ko_carousel_arrows_close(); ?>
			</div>
		</div>
	</section>
	<?php endif; endif; ?>

	<!-- خدماتنا -->
	<section class="section-alt">
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-eyebrow"><?php echo esc_html( ko_field( 'services_eyebrow', get_the_ID(), 'خدماتنا' ) ); ?></span>
				<h2><?php echo esc_html( ko_field( 'services_heading', get_the_ID(), 'ثلاث ركائز في منصة واحدة' ) ); ?></h2>
				<p><?php echo esc_html( ko_field( 'services_text', get_the_ID(), '' ) ); ?></p>
			</div>
			<div class="grid grid-3">
				<?php
				$services = ko_field( 'services', get_the_ID(), array() );
				foreach ( $services as $s ) : ?>
					<div class="card service-card" data-reveal>
						<div class="service-icon"><i class="<?php echo esc_attr( $s['icon'] ); ?>"></i></div>
						<h3><?php echo esc_html( $s['title'] ); ?></h3>
						<p><?php echo esc_html( $s['text'] ); ?></p>
						<?php if ( ! empty( $s['link_text'] ) ) : ?>
							<a href="<?php echo esc_url( $s['link_url'] ); ?>" class="link-arrow mt-2"><?php echo esc_html( $s['link_text'] ); ?> <i class="fa-solid fa-arrow-left"></i></a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- منتجات مميزة -->
	<?php if ( class_exists( 'WooCommerce' ) ) :
		$featured = wc_get_products( array( 'status' => 'publish', 'limit' => 8, 'featured' => true ) );
		if ( ! $featured ) $featured = wc_get_products( array( 'status' => 'publish', 'limit' => 8, 'orderby' => 'popularity' ) );
		if ( $featured ) : ?>
	<section>
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-eyebrow"><?php echo esc_html( ko_field( 'featured_eyebrow', get_the_ID(), 'الأكثر طلبًا' ) ); ?></span>
				<h2><?php echo esc_html( ko_field( 'featured_heading', get_the_ID(), 'منتجات مميزة' ) ); ?></h2>
				<p><?php echo esc_html( ko_field( 'featured_text', get_the_ID(), '' ) ); ?></p>
			</div>
			<div class="grid grid-4" id="featured-products">
				<?php foreach ( $featured as $p ) echo ko_product_card_html( $p ); ?>
			</div>
			<div class="text-center mt-4" data-reveal>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary">عرض كل المنتجات <i class="fa-solid fa-arrow-left"></i></a>
			</div>
		</div>
	</section>
	<?php endif; endif; ?>

	<!-- خطوات الطلب -->
	<section class="section-alt">
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-eyebrow"><?php echo esc_html( ko_field( 'steps_eyebrow', get_the_ID(), 'كيف تطلب؟' ) ); ?></span>
				<h2><?php echo esc_html( ko_field( 'steps_heading', get_the_ID(), 'رحلتك معنا في 4 خطوات بسيطة' ) ); ?></h2>
			</div>
			<div class="steps-grid">
				<?php
				$steps = ko_field( 'steps', get_the_ID(), array() );
				$i = 1;
				foreach ( $steps as $step ) : ?>
					<div class="step-card" data-reveal>
						<span class="step-num"><?php echo esc_html( str_pad( $i, 2, '0', STR_PAD_LEFT ) ); ?></span>
						<h3><?php echo esc_html( $step['title'] ); ?></h3>
						<p><?php echo esc_html( $step['text'] ); ?></p>
					</div>
				<?php $i++; endforeach; ?>
			</div>
		</div>
	</section>

	<!-- CTA الأكاديمية -->
	<section>
		<div class="container">
			<div class="cta-band" data-reveal>
				<span class="badge-pill" style="background:rgba(255,255,255,.12); color:#fff; border-color:rgba(255,255,255,.3);"><i class="fa-solid fa-graduation-cap"></i> <?php echo esc_html( ko_field( 'cta_badge_text', get_the_ID(), 'أكاديمية الأصول' ) ); ?></span>
				<h2 class="mt-2"><?php echo esc_html( ko_field( 'cta_heading', get_the_ID(), 'احترف الخياطة خطوة بخطوة' ) ); ?></h2>
				<p><?php echo esc_html( ko_field( 'cta_text', get_the_ID(), '' ) ); ?></p>
				<a href="<?php echo esc_url( ko_field( 'cta_button_link', get_the_ID(), '/contact/' ) ); ?>" class="btn btn-accent"><?php echo esc_html( ko_field( 'cta_button_text', get_the_ID(), 'اعرف تفاصيل الالتحاق' ) ); ?></a>
			</div>
		</div>
	</section>

	<!-- آراء العملاء -->
	<?php $testimonials = ko_field( 'testimonials', get_the_ID(), array() ); if ( $testimonials ) : ?>
	<section class="section-alt">
		<div class="container">
			<div class="section-head" data-reveal>
				<span class="section-eyebrow"><?php echo esc_html( ko_field( 'testimonials_eyebrow', get_the_ID(), 'آراء العملاء' ) ); ?></span>
				<h2><?php echo esc_html( ko_field( 'testimonials_heading', get_the_ID(), 'ثقة أكثر من 1200 عميل' ) ); ?></h2>
			</div>
			<div class="grid grid-3">
				<?php foreach ( $testimonials as $t ) : ?>
					<div class="card testimonial-card" data-reveal>
						<i class="fa-solid fa-quote-right"></i>
						<p>"<?php echo esc_html( $t['text'] ); ?>"</p>
						<div class="testimonial-person">
							<?php if ( ! empty( $t['image'] ) ) : ?><img src="<?php echo esc_url( $t['image'] ); ?>" alt="<?php echo esc_attr( $t['name'] ); ?>"><?php endif; ?>
							<div><strong><?php echo esc_html( $t['name'] ); ?></strong><span><?php echo esc_html( $t['role'] ); ?></span></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
