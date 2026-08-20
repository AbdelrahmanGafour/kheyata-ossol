<?php
/**
 * قالب الصفحة العام — يُستخدم لأي صفحة عادية، وأيضًا لصفحات السلة/الدفع/حسابي التي
 * يُنشئها WooCommerce تلقائيًا (تحتوي على [woocommerce_cart] / [woocommerce_checkout]
 * إلخ)، حيث تُنسَّق مخرجاتها بالكامل عبر طبقة التوافق في style.css. الفوتر يتحول
 * تلقائيًا للنسخة المبسّطة في صفحات السلة/الدفع/الحساب للحفاظ على تركيز المستخدم.
 */
get_header();

$is_focus_page = class_exists( 'WooCommerce' ) && ( is_cart() || is_checkout() || is_account_page() );
if ( $is_focus_page ) $ko_footer_variant = 'minimal';
?>

<main>

	<div class="page-header">
		<div class="container">
			<h1><?php the_title(); ?></h1>
			<?php ko_render_breadcrumb(); ?>
		</div>
	</div>

	<section>
		<div class="container">
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			<?php endwhile; ?>
		</div>
	</section>

</main>

<?php get_footer(); ?>
