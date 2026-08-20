<?php
/**
 * صفحة المتجر (Shop) وأرشيف التصنيفات — نفس تصميم store.html الأصلي: شريط فلاتر جانبي على
 * سطح المكتب يتحول للوحة منزلقة (Drawer) على الموبايل/التابلت (حسب تبويب "المتجر" بخيارات القالب).
 */
get_header();

$max_price     = ko_get_max_product_price();
$selected_max  = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : $max_price;
$search_term   = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
$current_term  = is_product_category() ? get_queried_object() : null;
$archive_url   = is_product_category() ? get_term_link( $current_term ) : ( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) );
$product_cats  = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'exclude' => array( get_option( 'default_product_cat' ) ) ) );
global $wp_query;
?>

<main>

	<div class="page-header">
		<div class="container">
			<h1><?php echo is_product_category() ? esc_html( single_term_title( '', false ) ) : esc_html__( 'المتجر', 'kheyata-ossol' ); ?></h1>
			<?php ko_render_breadcrumb(); ?>
		</div>
	</div>

	<section>
		<div class="container store-layout">

			<div class="filters-backdrop" id="filters-backdrop"></div>

			<aside class="filters-card" id="filters-card">
				<div class="filters-card-head">
					<h4><i class="fa-solid fa-sliders"></i> الفلاتر</h4>
					<button type="button" class="filters-close" id="filters-close" aria-label="إغلاق الفلاتر"><i class="fa-solid fa-xmark"></i></button>
				</div>

				<div class="filter-group">
					<strong style="font-size:14px;">التصنيف</strong>
					<label><input type="radio" name="ko-category-nav" value="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" <?php checked( ! is_product_category() ); ?>> كل التصنيفات</label>
					<?php foreach ( $product_cats as $term ) : ?>
						<label>
							<input type="radio" name="ko-category-nav" value="<?php echo esc_url( get_term_link( $term ) ); ?>" <?php checked( is_product_category() && $current_term->term_id === $term->term_id ); ?>>
							<?php $icon = get_term_meta( $term->term_id, 'ko_category_icon', true ) ?: 'fa-solid fa-tag'; ?>
							<i class="<?php echo esc_attr( $icon ); ?>" style="width:18px; color:var(--color-accent-dark);"></i> <?php echo esc_html( $term->name ); ?>
						</label>
					<?php endforeach; ?>
				</div>

				<form method="get" action="<?php echo esc_url( $archive_url ); ?>" id="ko-filters-form">
					<?php if ( $search_term ) : ?><input type="hidden" name="s" value="<?php echo esc_attr( $search_term ); ?>"><?php endif; ?>
					<?php if ( ! empty( $_GET['orderby'] ) ) : ?><input type="hidden" name="orderby" value="<?php echo esc_attr( sanitize_text_field( $_GET['orderby'] ) ); ?>"><?php endif; ?>
					<div class="filter-group">
						<strong style="font-size:14px;">السعر الأقصى</strong>
						<input type="range" id="price-range" name="max_price" min="50" max="<?php echo esc_attr( $max_price ); ?>" step="50" value="<?php echo esc_attr( $selected_max ); ?>" style="width:100%; accent-color: var(--color-primary); margin-top:12px;">
						<div class="price-range-value">حتى <span id="price-range-value"><?php echo esc_html( $selected_max ); ?></span> ج.م</div>
					</div>
					<a href="<?php echo esc_url( $archive_url ); ?>" class="btn btn-outline btn-block btn-sm"><i class="fa-solid fa-rotate-left"></i> إعادة ضبط</a>
					<button type="submit" class="btn btn-primary btn-block filters-apply" id="filters-apply"><i class="fa-solid fa-check"></i> عرض النتائج</button>
				</form>
			</aside>

			<div>
				<div class="store-toolbar">
					<button type="button" class="filter-toggle-btn" id="filter-toggle" aria-label="فتح الفلاتر"><i class="fa-solid fa-sliders"></i> الفلاتر</button>

					<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-box">
						<input type="hidden" name="post_type" value="product">
						<input type="text" name="s" value="<?php echo esc_attr( $search_term ); ?>" placeholder="ابحث عن أداة أو ماكينة...">
						<button type="submit" style="position:absolute; inset:0; opacity:0;" aria-label="بحث"></button>
						<i class="fa-solid fa-magnifying-glass"></i>
					</form>

					<div class="flex gap-2" style="align-items:center;">
						<span class="results-count"><span id="results-count"><?php echo esc_html( $wp_query->found_posts ); ?></span> منتج</span>
						<form method="get" action="<?php echo esc_url( $archive_url ); ?>" id="ko-sort-form">
							<?php if ( $search_term ) : ?><input type="hidden" name="s" value="<?php echo esc_attr( $search_term ); ?>"><?php endif; ?>
							<?php if ( ! empty( $_GET['max_price'] ) ) : ?><input type="hidden" name="max_price" value="<?php echo esc_attr( $selected_max ); ?>"><?php endif; ?>
							<select name="orderby" class="form-control" onchange="this.form.submit()">
								<option value="menu_order" <?php selected( empty( $_GET['orderby'] ) || $_GET['orderby'] === 'menu_order' ); ?>>الترتيب الافتراضي</option>
								<option value="price" <?php selected( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'price' ); ?>>السعر: من الأقل للأعلى</option>
								<option value="price-desc" <?php selected( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'price-desc' ); ?>>السعر: من الأعلى للأقل</option>
								<option value="rating" <?php selected( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'rating' ); ?>>الأعلى تقييمًا</option>
							</select>
						</form>
					</div>
				</div>

				<?php if ( have_posts() ) : ?>
					<div class="grid grid-3" id="store-grid">
						<?php while ( have_posts() ) : the_post(); wc_get_template_part( 'content', 'product' ); endwhile; ?>
					</div>
					<div class="mt-4">
						<?php woocommerce_pagination(); ?>
					</div>
				<?php else : ?>
					<div class="empty-state" id="empty-state">
						<i class="fa-solid fa-magnifying-glass-minus"></i>
						<h3>لا توجد منتجات مطابقة</h3>
						<p>جرّب تعديل الفلاتر أو كلمة البحث.</p>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</section>

</main>

<style>
	.store-layout { display: grid; grid-template-columns: 1fr; gap: 32px; align-items: start; }
	@media (min-width: 960px) { .store-layout { grid-template-columns: 260px 1fr; } }
	.filters-card { background: var(--color-white); border-radius: var(--radius-lg); box-shadow: var(--shadow-soft); padding: 24px; position: sticky; top: calc(var(--header-height) + 24px); }
	.filters-card-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
	.filters-card h4 { font-family: var(--font-heading); margin-bottom: 0; }
	.filter-group { margin-bottom: 24px; }
	.filter-group label { display: flex; align-items: center; gap: 10px; padding: 8px 0; font-size: 14px; cursor: pointer; }
	.filter-group input[type="radio"], .filter-group input[type="checkbox"] { accent-color: var(--color-primary); width: 16px; height: 16px; }
	.store-toolbar { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: space-between; margin-bottom: 28px; }
	.search-box { position: relative; flex: 1; min-width: 220px; }
	.search-box i { position: absolute; top: 50%; right: 16px; transform: translateY(-50%); color: var(--color-text-muted); pointer-events: none; }
	.search-box input { width: 100%; padding: 12px 44px 12px 16px; border-radius: var(--radius-pill); border: 1.5px solid var(--color-border); background: var(--color-white); }
	select.form-control { width: auto; }
	.price-range-value { font-size: 13px; color: var(--color-text-muted); margin-top: 6px; }
	.results-count { font-size: 14px; color: var(--color-text-muted); }

	.filter-toggle-btn { display: none; align-items: center; gap: 8px; padding: 12px 20px; border-radius: var(--radius-pill); background: var(--color-primary); color: #fff; font-weight: 700; font-size: 14px; white-space: nowrap; transition: background .2s ease, transform .2s ease; }
	.filter-toggle-btn:hover { background: var(--color-primary-dark); }
	.filter-toggle-btn:active { transform: scale(.96); }
	.filters-close { display: none; width: 36px; height: 36px; border-radius: 50%; align-items: center; justify-content: center; background: var(--color-bg-alt); color: var(--color-text); font-size: 16px; flex-shrink: 0; }
	.filters-apply { display: none; margin-top: 20px; }
	.filters-backdrop { display: none; }

	@media (max-width: 959px) {
		.filter-toggle-btn { display: inline-flex; order: -1; }
		.filters-backdrop { display: block; position: fixed; inset: 0; background: rgba(15, 20, 30, .55); z-index: 1150; opacity: 0; visibility: hidden; transition: opacity .3s ease, visibility .3s; }
		.filters-backdrop.is-open { opacity: 1; visibility: visible; }
		.filters-card { position: fixed; top: 0; bottom: 0; right: 0; width: min(320px, 85vw); max-width: 320px; height: 100%; z-index: 1200; border-radius: 0; transform: translateX(100%); transition: transform .32s ease; overflow-y: auto; box-shadow: -8px 0 30px rgba(0,0,0,.18); }
		.filters-card.is-open { transform: translateX(0); }
		.filters-close { display: flex; }
		.filters-apply { display: flex; }
	}
	body.filters-open { overflow: hidden; }
</style>

<?php get_footer(); ?>
