<?php
/**
 * دوال مساعدة للقوالب: بطاقة منتج، بطاقة تصنيف، نجوم التقييم — تُنتج نفس بنية HTML
 * الموجودة في التصميم الأصلي (product-card / service-card) لكن مبنية على بيانات WooCommerce حية.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function ko_stars_html( $rating ) {
	$full = round( $rating );
	$html = '';
	for ( $i = 0; $i < 5; $i++ ) {
		$html .= '<i class="' . ( $i < $full ? 'fa-solid' : 'fa-regular' ) . ' fa-star"></i>';
	}
	return $html;
}

/** بطاقة تصنيف منتج (تُستخدم داخل كاروسيل "تسوّق حسب احتياجك"). */
function ko_category_card_html( $term ) {
	$icon = get_term_meta( $term->term_id, 'ko_category_icon', true );
	if ( ! $icon ) $icon = 'fa-solid fa-tag';
	$link = get_term_link( $term );
	return sprintf(
		'<a href="%s" class="card service-card"><div class="service-icon"><i class="%s"></i></div><h3>%s</h3></a>',
		esc_url( $link ),
		esc_attr( $icon ),
		esc_html( $term->name )
	);
}

/** بطاقة منتج مطابقة تمامًا لتصميم .product-card الأصلي، مبنية على كائن WC_Product. */
function ko_product_card_html( $product ) {
	if ( ! $product instanceof WC_Product ) return '';

	$permalink   = get_permalink( $product->get_id() );
	$is_variable = $product->is_type( 'variable' );
	$categories  = wc_get_product_category_list( $product->get_id(), ', ' );
	$badge       = '';
	if ( $product->is_on_sale() ) {
		$badge = ko_opt( 'sale_badge_text', 'خصم' );
	} elseif ( $product->is_featured() ) {
		$badge = 'الأكثر مبيعًا';
	}

	$rating  = (float) $product->get_average_rating();
	$reviews = (int) $product->get_review_count();

	$image_id  = $product->get_image_id();
	$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'ko-product-thumb' ) : wc_placeholder_img_src();

	$show_cat        = ko_opt( 'show_product_category_label', '1' ) === '1';
	$show_rating     = ko_opt( 'show_product_rating', '1' ) === '1';
	$show_quick_view = ko_opt( 'show_quick_view', '1' ) === '1';

	ob_start();
	?>
	<div class="card product-card" data-reveal>
		<div class="product-media">
			<a href="<?php echo esc_url( $permalink ); ?>">
				<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy">
			</a>
			<?php if ( $badge ) : ?><span class="product-tag"><?php echo esc_html( $badge ); ?></span><?php endif; ?>
			<?php if ( $show_quick_view ) : ?>
				<button type="button" class="quick-view-btn" data-quick-view="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'معاينة سريعة', 'kheyata-ossol' ); ?>"><i class="fa-solid fa-eye"></i></button>
			<?php endif; ?>
		</div>
		<div class="product-body">
			<?php if ( $show_cat && $categories ) : ?><span class="product-cat"><?php echo wp_kses_post( $categories ); ?></span><?php endif; ?>
			<a href="<?php echo esc_url( $permalink ); ?>"><h3><?php echo esc_html( $product->get_name() ); ?></h3></a>
			<?php if ( $show_rating && $reviews > 0 ) : ?>
				<div class="rating"><?php echo ko_stars_html( $rating ); ?> <span style="color:var(--color-text-muted)">(<?php echo esc_html( $reviews ); ?>)</span></div>
			<?php endif; ?>
			<div class="product-price-row">
				<div><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
				<?php if ( $is_variable ) : ?>
					<a href="<?php echo esc_url( $permalink ); ?>" class="add-cart-btn" aria-label="اختر المقاس"><i class="fa-solid fa-arrow-left"></i></a>
				<?php else : ?>
					<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" data-quantity="1" class="add-cart-btn ajax_add_to_cart add_to_cart_button" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" aria-label="أضف للسلة"><i class="fa-solid fa-cart-plus"></i></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/** يطبع مصفوفة عناصر HTML داخل عنصر بمُعرِّف معيّن (نسخة PHP من renderInto القديمة في JS). */
function ko_print_items( $items ) {
	echo implode( '', $items );
}

/** أزرار الكاروسيل (سابق/تالي) بنفس بنية HTML الأصلية. */
function ko_carousel_arrows_open() {
	echo '<button class="carousel-arrow carousel-arrow-prev" aria-label="السابق"><i class="fa-solid fa-chevron-right"></i></button>';
}
function ko_carousel_arrows_close() {
	echo '<button class="carousel-arrow carousel-arrow-next" aria-label="التالي"><i class="fa-solid fa-chevron-left"></i></button>';
}

/** يطبع شريط .breadcrumb — عبر Yoast SEO إن كان مفعّلاً (لأفضل تكامل SEO)، وإلا نسخة بسيطة بديلة. */
function ko_render_breadcrumb() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<div class="breadcrumb">', '</div>' );
		return;
	}
	echo '<div class="breadcrumb"><a href="' . esc_url( home_url( '/' ) ) . '">الرئيسية</a><span>/</span><span>' . esc_html( wp_get_document_title() ) . '</span></div>';
}

/** قائمة رئيسية احتياطية تظهر تلقائيًا قبل أن يُنشئ الأدمن قائمة من مظهر ← قوائم. */
function ko_default_primary_menu() {
	$items = array(
		home_url( '/' )     => 'الرئيسية',
		home_url( '/store/' ) => 'المتجر',
		home_url( '/about/' ) => 'من نحن',
		home_url( '/contact/' ) => 'تواصل معنا',
	);
	echo '<ul>';
	foreach ( $items as $url => $label ) {
		$active = untrailingslashit( $url ) === untrailingslashit( home_url( $_SERVER['REQUEST_URI'] ?? '' ) ) ? ' class="active"' : '';
		echo '<li><a href="' . esc_url( $url ) . '"' . $active . '>' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}

/** قائمة موبايل احتياطية بنفس أيقونات التصميم الأصلي. */
function ko_default_mobile_menu() {
	$items = array(
		array( home_url( '/' ), 'الرئيسية', 'fa-solid fa-house' ),
		array( home_url( '/store/' ), 'المتجر', 'fa-solid fa-store' ),
		array( home_url( '/about/' ), 'من نحن', 'fa-solid fa-circle-info' ),
		array( home_url( '/contact/' ), 'تواصل معنا', 'fa-solid fa-envelope' ),
	);
	foreach ( $items as $item ) {
		echo '<li><a href="' . esc_url( $item[0] ) . '">' . esc_html( $item[1] ) . ' <i class="' . esc_attr( $item[2] ) . '"></i></a></li>';
	}
}

/** يبني رابط أيقونة الحساب: صفحة "حسابي" إن كان الزائر مسجلاً، أو صفحة الدخول إن لم يكن. */
function ko_account_link() {
	if ( ! class_exists( 'WooCommerce' ) ) return wp_login_url();
	return is_user_logged_in() ? wc_get_page_permalink( 'myaccount' ) : wc_get_page_permalink( 'myaccount' );
}
