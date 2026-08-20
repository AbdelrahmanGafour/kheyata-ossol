<?php
/**
 * مستورد المحتوى التجريبي: ينشئ تلقائيًا (أو بضغطة زر) نفس محتوى الموقع الأصلي بالكامل —
 * 6 تصنيفات، 10 منتجات (منها منتج بثلاث تنويعات)، 3 صفحات ديناميكية مكتملة المحتوى
 * (الرئيسية/من نحن/تواصل معنا)، وقائمة تنقّل — حتى يظهر المتجر جاهزًا من أول تفعيل
 * للقالب بدل أن يبدأ الأدمن من صفحة فارغة.
 *
 * يعمل تلقائيًا بعد تفعيل القالب بمجرد أن تصبح كل الإضافات الأربع المطلوبة مفعّلة
 * (سواء فُعِّلت قبل القالب أو بعده)، ويظل متاحًا يدويًا من: خيارات القالب ← استيراد محتوى تجريبي.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

const KO_DEMO_IMPORTED_OPTION = 'ko_demo_imported';
const KO_DEMO_PENDING_OPTION  = 'ko_demo_import_pending';

/* ------------------------------------------------------------------
 * تشغيل تلقائي: يُعلَّم "معلّق" عند تفعيل القالب، ثم يُنفَّذ فعليًا أول مرة تكتمل
 * فيها كل الإضافات الأربع المطلوبة (بغض النظر عن ترتيب تفعيل القالب/الإضافات).
 * ------------------------------------------------------------------ */
add_action( 'after_switch_theme', function () {
	if ( ! get_option( KO_DEMO_IMPORTED_OPTION ) ) {
		update_option( KO_DEMO_PENDING_OPTION, 1 );
	}
	ko_maybe_run_pending_demo_import();
} );
add_action( 'activated_plugin', 'ko_maybe_run_pending_demo_import' );
add_action( 'admin_init', 'ko_maybe_run_pending_demo_import' );

function ko_demo_import_requirements_met() {
	return class_exists( 'WooCommerce' ) && function_exists( 'acf_add_local_field_group' );
}

function ko_maybe_run_pending_demo_import() {
	if ( ! get_option( KO_DEMO_PENDING_OPTION ) ) return;
	if ( get_option( KO_DEMO_IMPORTED_OPTION ) ) { delete_option( KO_DEMO_PENDING_OPTION ); return; }
	if ( ! ko_demo_import_requirements_met() ) return;
	ko_run_demo_import();
	delete_option( KO_DEMO_PENDING_OPTION );
}

/* ------------------------------------------------------------------
 * صفحة إدارية: خيارات القالب ← استيراد محتوى تجريبي
 * ------------------------------------------------------------------ */
add_action( 'admin_menu', function () {
	add_submenu_page(
		KO_Theme_Options::PAGE_SLUG,
		__( 'استيراد محتوى تجريبي', 'kheyata-ossol' ),
		__( 'استيراد محتوى تجريبي', 'kheyata-ossol' ),
		'manage_options',
		'ko-demo-import',
		'ko_render_demo_import_page'
	);
} );

function ko_render_demo_import_page() {
	if ( ! current_user_can( 'manage_options' ) ) return;

	if ( isset( $_POST['ko_run_demo_import'] ) && check_admin_referer( 'ko_demo_import' ) ) {
		$result = ko_run_demo_import();
		echo '<div class="notice notice-success"><p>' . esc_html__( 'تم استيراد المحتوى التجريبي بنجاح: ', 'kheyata-ossol' ) .
			esc_html( sprintf( '%d تصنيف، %d منتج، %d صفحة.', $result['categories'], $result['products'], $result['pages'] ) ) . '</p></div>';
	}

	$already_imported = get_option( KO_DEMO_IMPORTED_OPTION );
	$requirements_met = ko_demo_import_requirements_met();
	?>
	<div class="wrap ko-options-wrap">
		<div class="ko-options-header">
			<h1><span class="dashicons dashicons-download"></span> <?php esc_html_e( 'استيراد المحتوى التجريبي', 'kheyata-ossol' ); ?></h1>
			<p><?php esc_html_e( 'ينشئ هذا الاستيراد بضغطة واحدة نفس محتوى المتجر الأصلي بالكامل: 6 تصنيفات، 10 منتجات (منها منتج بثلاث مقاسات)، الصفحة الرئيسية وصفحة "من نحن" و"تواصل معنا" بكل نصوصها وصورها، بالإضافة إلى قائمة تنقّل جاهزة.', 'kheyata-ossol' ); ?></p>
		</div>

		<?php if ( ! $requirements_met ) : ?>
			<div class="notice notice-warning inline"><p>
				<?php esc_html_e( 'يجب تفعيل كل الإضافات المطلوبة أولاً (WooCommerce و Secure Custom Fields على الأقل).', 'kheyata-ossol' ); ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=ko-required-plugins' ) ); ?>"><?php esc_html_e( 'الذهاب لصفحة الإضافات المطلوبة', 'kheyata-ossol' ); ?></a>
			</p></div>
		<?php else : ?>
			<div class="ko-options-panel" style="max-width:700px;">
				<?php if ( $already_imported ) : ?>
					<p><span class="dashicons dashicons-yes-alt" style="color:#2e7d32;"></span> <?php esc_html_e( 'تم استيراد المحتوى التجريبي مسبقًا في هذا الموقع.', 'kheyata-ossol' ); ?></p>
					<p class="description"><?php esc_html_e( 'إعادة الاستيراد آمنة (لن يُنشئ تكرارًا لعناصر موجودة بنفس المعرّف/SKU)، لكنها ستُنشئ صفحات ومنتجات جديدة إن كنت قد عدّلت أو حذفت النسخ الأصلية.', 'kheyata-ossol' ); ?></p>
				<?php endif; ?>
				<form method="post">
					<?php wp_nonce_field( 'ko_demo_import' ); ?>
					<button type="submit" name="ko_run_demo_import" value="1" class="button button-primary button-hero">
						<?php echo $already_imported ? esc_html__( 'إعادة استيراد المحتوى التجريبي', 'kheyata-ossol' ) : esc_html__( 'استيراد المحتوى التجريبي الآن', 'kheyata-ossol' ); ?>
					</button>
				</form>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/* ------------------------------------------------------------------
 * التنفيذ الفعلي
 * ------------------------------------------------------------------ */
function ko_run_demo_import() {
	if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) require_once ABSPATH . 'wp-admin/includes/image.php';
	if ( ! function_exists( 'wp_handle_sideload' ) ) require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$cat_map  = ko_demo_import_categories();
	$prod_ids = class_exists( 'WooCommerce' ) ? ko_demo_import_products( $cat_map ) : array();
	$page_ids = function_exists( 'acf_add_local_field_group' ) ? ko_demo_import_pages() : array();
	ko_demo_import_menu( $page_ids );

	update_option( KO_DEMO_IMPORTED_OPTION, current_time( 'mysql' ) );
	delete_transient( 'ko_max_product_price' );

	return array(
		'categories' => count( $cat_map ),
		'products'   => count( $prod_ids ),
		'pages'      => count( $page_ids ),
	);
}

/** يستورد ملف صورة محلي من مجلد القالب إلى مكتبة الوسائط (مرة واحدة فقط لكل ملف). */
function ko_import_local_image( $relative_path, $title = '' ) {
	static $cache = array();
	if ( isset( $cache[ $relative_path ] ) ) return $cache[ $relative_path ];

	$existing = get_posts( array(
		'post_type'      => 'attachment',
		'meta_key'       => '_ko_demo_source',
		'meta_value'     => $relative_path,
		'posts_per_page' => 1,
		'fields'         => 'ids',
	) );
	if ( $existing ) { $cache[ $relative_path ] = $existing[0]; return $existing[0]; }

	$source_file = KO_THEME_DIR . '/' . ltrim( $relative_path, '/' );
	if ( ! file_exists( $source_file ) ) return 0;

	$upload_dir = wp_upload_dir();
	$filename   = wp_unique_filename( $upload_dir['path'], basename( $source_file ) );
	$dest_path  = trailingslashit( $upload_dir['path'] ) . $filename;
	if ( ! copy( $source_file, $dest_path ) ) return 0;

	$filetype  = wp_check_filetype( $filename, null );
	$attach_id = wp_insert_attachment( array(
		'post_mime_type' => $filetype['type'],
		'post_title'     => $title ?: pathinfo( $filename, PATHINFO_FILENAME ),
		'post_status'    => 'inherit',
	), $dest_path );

	if ( ! is_wp_error( $attach_id ) && $attach_id ) {
		update_post_meta( $attach_id, '_ko_demo_source', $relative_path );
		$metadata = wp_generate_attachment_metadata( $attach_id, $dest_path );
		wp_update_attachment_metadata( $attach_id, $metadata );
		$cache[ $relative_path ] = $attach_id;
		return $attach_id;
	}
	return 0;
}

/** يستورد التصنيفات الست الأصلية مع أيقوناتها (Font Awesome). */
function ko_demo_import_categories() {
	$defs = array(
		'machines'    => array( 'name' => 'مكن الخياطة',  'icon' => 'fa-solid fa-gear' ),
		'cutting'     => array( 'name' => 'أدوات القص',   'icon' => 'fa-solid fa-scissors' ),
		'measuring'   => array( 'name' => 'أدوات القياس', 'icon' => 'fa-solid fa-ruler-combined' ),
		'thread'      => array( 'name' => 'خيوط وإبر',    'icon' => 'fa-solid fa-thumbtack' ),
		'fabric'      => array( 'name' => 'أقمشة',        'icon' => 'fa-solid fa-shirt' ),
		'accessories' => array( 'name' => 'إكسسوارات',    'icon' => 'fa-solid fa-toolbox' ),
	);
	$map = array();
	foreach ( $defs as $slug => $def ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( ! $term ) {
			$inserted = wp_insert_term( $def['name'], 'product_cat', array( 'slug' => $slug ) );
			$term_id  = is_wp_error( $inserted ) ? 0 : $inserted['term_id'];
		} else {
			$term_id = $term->term_id;
		}
		if ( $term_id ) {
			update_term_meta( $term_id, 'ko_category_icon', $def['icon'] );
			$map[ $slug ] = $term_id;
		}
	}
	return $map;
}

/** يستورد المنتجات العشرة (منها منتج متغيّر بثلاث تنويعات) بنفس بيانات الموقع الأصلي بالضبط. */
function ko_demo_import_products( $cat_map ) {
	$products = array(
		array( 'sku' => 'KO-P01', 'name' => 'مقص عينات كهربائي', 'cat' => 'cutting', 'price' => 1850, 'old' => 1950, 'stock' => 12, 'featured' => true,
			'image' => 'assets/images/products/مقص عينات كهربائي.webp', 'gallery' => array( 'assets/images/products/مقص عينات كهربائي 2.webp' ),
			'desc' => 'مقص عينات كهربائي عالي الكفاءة لقص الأقمشة والعينات بسرعة ودقة عالية، يوفر الوقت والمجهود في الورش والمصانع مقارنة بالقص اليدوي، وبناء متين يتحمل الاستخدام المكثف.' ),
		array( 'sku' => 'KO-P02', 'name' => 'مكبس كباسين يدوي', 'cat' => 'accessories', 'price' => 350, 'old' => 400, 'stock' => 25,
			'image' => 'assets/images/products/مكبس كباسين يدوي.webp',
			'desc' => 'مكبس يدوي لتركيب الكباسين والأزرار الضاغطة على الأقمشة بإحكام واحترافية، سهل الاستخدام ومناسب للورش والاستخدام المنزلي المتكرر.' ),
		array( 'sku' => 'KO-P03', 'name' => 'جهاز تني وتركيب استك اوتوماتيك مستورد', 'cat' => 'machines', 'price' => 1850, 'old' => 1950, 'stock' => 6,
			'image' => 'assets/images/products/جهاز تني وتركيب استك اوتوماتيك مستورد.webp',
			'desc' => 'جهاز مستورد لتنّي وتركيب الأستك أوتوماتيكيًا بسرعة ودقة عالية، يقلل المجهود ووقت التصنيع مع نتائج ثابتة الجودة في كل قطعة.' ),
		array( 'sku' => 'KO-P05', 'name' => 'مسطره تحديد مقاس', 'cat' => 'measuring', 'price' => 420, 'old' => 470, 'stock' => 30,
			'image' => 'assets/images/products/مسطره تحديد مقاس.webp',
			'desc' => 'مسطرة معدنية دقيقة لتحديد المقاسات بسهولة واحترافية أثناء التفصيل والقص، من الأدوات الأساسية في كل ورشة خياطة.' ),
		array( 'sku' => 'KO-P06', 'name' => 'دكاكه حلزون', 'cat' => 'accessories', 'price' => 550, 'old' => 600, 'stock' => 18,
			'image' => 'assets/images/products/دكاكه حلزون.webp',
			'desc' => 'دكاكة حلزونية لعمل العراوي وتثبيت الكبسات بدقة وثبات، تصميم متين يدوم طويلًا مع الاستخدام المكثف في الورش.' ),
		array( 'sku' => 'KO-P07', 'name' => 'درج لمكينات الخياطه', 'cat' => 'accessories', 'price' => 550, 'old' => 600, 'stock' => 22,
			'image' => 'assets/images/products/درج لمكينات الخياطه.webp',
			'desc' => 'درج عملي لتنظيم أدوات الخياطة بجانب الماكينة، سهل التركيب ويوفر مساحة تخزين إضافية للإبر والخيوط والملحقات.' ),
		array( 'sku' => 'KO-P08', 'name' => 'علبة مسطرة تني الأورلية', 'cat' => 'measuring', 'price' => 450, 'old' => 550, 'stock' => 14,
			'image' => 'assets/images/products/علبة مسطرة تني الأورلية.webp',
			'desc' => 'علبة مع مسطرة مخصصة لضبط تنّي الأورلية بدقة، تحافظ على الأداة من التلف وتسهّل استخدامها أثناء العمل.' ),
		array( 'sku' => 'KO-P09', 'name' => 'دواسه تني متحركك', 'cat' => 'accessories', 'price' => 360, 'old' => 400, 'stock' => 27,
			'image' => 'assets/images/products/دواسه تني متحركك.webp',
			'desc' => 'دواسة متحركة لتنّي الأستك أثناء التفصيل، تمنح تحكمًا أفضل وسرعة أعلى في الإنتاج مقارنة بالطرق التقليدية.' ),
		array( 'sku' => 'KO-P10', 'name' => 'متر قياس إلكتروني', 'cat' => 'measuring', 'price' => 650, 'old' => 700, 'stock' => 20, 'featured' => true,
			'image' => 'assets/images/products/متر قياس إلكتروني.webp',
			'desc' => 'متر قياس إلكتروني رقمي يعرض القياسات بدقة وسرعة، بديل عملي وموفّر للوقت عن المتر التقليدي في أعمال التفصيل والقص.' ),
	);

	$ids = array();

	foreach ( $products as $p ) {
		$existing = wc_get_product_id_by_sku( $p['sku'] );
		if ( $existing ) { $ids[] = $existing; continue; }

		$product = new WC_Product_Simple();
		$product->set_name( $p['name'] );
		$product->set_sku( $p['sku'] );
		$product->set_description( $p['desc'] );
		$product->set_short_description( wp_trim_words( $p['desc'], 16 ) );
		$product->set_regular_price( $p['old'] ?: $p['price'] );
		if ( ! empty( $p['old'] ) ) $product->set_sale_price( $p['price'] );
		$product->set_manage_stock( true );
		$product->set_stock_quantity( $p['stock'] );
		$product->set_stock_status( 'instock' );
		$product->set_featured( ! empty( $p['featured'] ) );
		$product->set_status( 'publish' );
		if ( isset( $cat_map[ $p['cat'] ] ) ) $product->set_category_ids( array( $cat_map[ $p['cat'] ] ) );

		$image_id = ko_import_local_image( $p['image'], $p['name'] );
		if ( $image_id ) $product->set_image_id( $image_id );
		if ( ! empty( $p['gallery'] ) ) {
			$gallery_ids = array_filter( array_map( function ( $path ) use ( $p ) { return ko_import_local_image( $path, $p['name'] ); }, $p['gallery'] ) );
			if ( $gallery_ids ) $product->set_gallery_image_ids( $gallery_ids );
		}

		$id = $product->save();
		if ( $id ) $ids[] = $id;
	}

	$ids[] = ko_demo_import_variable_product( $cat_map );

	return array_filter( $ids );
}

/** المنتج المتغيّر: مقص pin الاحمر — بثلاث مقاسات وأسعار مختلفة. */
function ko_demo_import_variable_product( $cat_map ) {
	$existing = wc_get_product_id_by_sku( 'KO-P04' );
	if ( $existing ) return $existing;

	$name  = 'مقص pin الاحمر';
	$desc  = 'مقص تفريز احترافي (Pin) يعطي حواف مسننة أنيقة ويمنع تنسل الأقمشة، متوفر بثلاثة مقاسات لتناسب كل احتياجات القص والتفصيل.';
	$sizes = array( 'مقاس 8' => 420, 'مقاس 10' => 460, 'مقاس 12' => 520 );

	$product = new WC_Product_Variable();
	$product->set_name( $name );
	$product->set_sku( 'KO-P04' );
	$product->set_description( $desc );
	$product->set_short_description( wp_trim_words( $desc, 16 ) );
	$product->set_status( 'publish' );
	if ( isset( $cat_map['cutting'] ) ) $product->set_category_ids( array( $cat_map['cutting'] ) );

	$image_id = ko_import_local_image( 'assets/images/products/مقص pin الاحمر.webp', $name );
	if ( $image_id ) $product->set_image_id( $image_id );

	$attribute = new WC_Product_Attribute();
	$attribute->set_id( 0 );
	$attribute->set_name( 'المقاس' );
	$attribute->set_options( array_keys( $sizes ) );
	$attribute->set_position( 0 );
	$attribute->set_visible( true );
	$attribute->set_variation( true );
	$product->set_attributes( array( $attribute ) );

	$product_id = $product->save();

	foreach ( $sizes as $label => $price ) {
		$variation = new WC_Product_Variation();
		$variation->set_parent_id( $product_id );
		$variation->set_sku( 'KO-P04-' . sanitize_title( $label ) );
		$variation->set_attributes( array( 'المقاس' => $label ) );
		$variation->set_regular_price( $price );
		$variation->set_stock_status( 'instock' );
		$variation->save();
	}

	// نعيد حفظ المنتج الأب حتى يُحدَّث نطاق السعر (من - إلى) بعد إضافة التنويعات.
	$product = wc_get_product( $product_id );
	$product->save();

	return $product_id;
}

/** يستورد الصفحات الثلاث الديناميكية مكتملة المحتوى، ويضبط الصفحة الرئيسية الثابتة. */
function ko_demo_import_pages() {
	$ids = array();

	// ---- الصفحة الرئيسية ----
	$home_id = ko_get_or_create_page( 'الرئيسية' );
	$ids['home'] = $home_id;
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );
	update_option( 'page_for_posts', 0 );

	if ( function_exists( 'update_field' ) ) {
		update_field( 'hero_badge_text', 'خبرة أكثر من 5 سنوات في عالم الخياطة', $home_id );
		update_field( 'hero_heading', 'للخياطة أصول.. وللأصول أهلها', $home_id );
		update_field( 'hero_lead', 'المنصة الأولى المتكاملة في عالم الخياطة: متجر لأدوات وماكينات الخياطة الأصلية والنادرة، أكاديمية تعليم احترافي من الصفر حتى إدارة المشاريع، ودعم فني حقيقي بعد البيع.', $home_id );
		$hero_video_id = ko_import_local_image( 'assets/images/hero/HeroVideo.mp4', 'فيديو الهيرو' );
		if ( $hero_video_id ) update_field( 'hero_video', wp_get_attachment_url( $hero_video_id ), $home_id );
		$poster_id = ko_import_local_image( 'assets/images/hero/hero-main.jpg', 'صورة الهيرو البديلة' );
		if ( $poster_id ) update_field( 'hero_poster', $poster_id, $home_id );
		update_field( 'hero_btn1_text', 'تسوّق الآن', $home_id );
		update_field( 'hero_btn1_link', class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : '/store/', $home_id );
		update_field( 'hero_btn2_text', 'تعرف علينا', $home_id );
		update_field( 'hero_btn2_link', '/about/', $home_id );
		update_field( 'hero_stats', array(
			array( 'number' => 5, 'suffix' => '+', 'label' => 'سنوات خبرة' ),
			array( 'number' => 1200, 'suffix' => '+', 'label' => 'عميل راضٍ' ),
			array( 'number' => 15, 'suffix' => '+', 'label' => 'منتج أصلي' ),
			array( 'number' => 27, 'suffix' => '', 'label' => 'محافظة توصيل' ),
		), $home_id );

		update_field( 'categories_eyebrow', 'التصنيفات', $home_id );
		update_field( 'categories_heading', 'تسوّق حسب احتياجك', $home_id );
		update_field( 'newest_eyebrow', 'وصل حديثًا', $home_id );
		update_field( 'newest_heading', 'أحدث المنتجات', $home_id );
		update_field( 'newest_text', 'آخر ما أضفناه لمتجرنا من أدوات وماكينات أصلية، تصفّحها أولًا بأول.', $home_id );

		update_field( 'services_eyebrow', 'خدماتنا', $home_id );
		update_field( 'services_heading', 'ثلاث ركائز في منصة واحدة', $home_id );
		update_field( 'services_text', 'لا نبيع أدوات فقط، بل نبني معك مسارًا متكاملًا للاحتراف في عالم الخياطة.', $home_id );
		update_field( 'services', array(
			array( 'icon' => 'fa-solid fa-store', 'title' => 'متجر النوادر', 'text' => 'أدوات وماكينات خياطة أصلية ونادرة يصعب إيجادها، بجودة تصدير وأسعار منافسة.', 'link_text' => 'تصفح المتجر', 'link_url' => class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : '/store/' ),
			array( 'icon' => 'fa-solid fa-graduation-cap', 'title' => 'أكاديمية الأصول', 'text' => 'تعليم احترافي للخياطة من الأساسيات وحتى إدارة المشاريع، بإشراف مدربين متخصصين.', 'link_text' => 'اعرف أكثر', 'link_url' => '/contact/' ),
			array( 'icon' => 'fa-solid fa-screwdriver-wrench', 'title' => 'دعم فني وضمان', 'text' => 'ضمان حقيقي بعد البيع وتوفر قطع غيار أصلية، مع فريق فني جاهز لمساعدتك دائمًا.', 'link_text' => 'تواصل معنا', 'link_url' => '/contact/' ),
		), $home_id );

		update_field( 'featured_eyebrow', 'الأكثر طلبًا', $home_id );
		update_field( 'featured_heading', 'منتجات مميزة', $home_id );
		update_field( 'featured_text', 'مختارات من أفضل الأدوات والماكينات الأصلية، بضمان الجودة وسرعة التوصيل.', $home_id );

		update_field( 'steps_eyebrow', 'كيف تطلب؟', $home_id );
		update_field( 'steps_heading', 'رحلتك معنا في 4 خطوات بسيطة', $home_id );
		update_field( 'steps', array(
			array( 'title' => 'تصفّح المنتجات', 'text' => 'اختر من مجموعة واسعة من الأدوات والماكينات الأصلية حسب احتياجك.' ),
			array( 'title' => 'أضف إلى السلة', 'text' => 'أضف منتجاتك المفضلة إلى السلة وراجع طلبك بسهولة تامة.' ),
			array( 'title' => 'أدخل بياناتك', 'text' => 'أكمل بيانات الشحن واختر الدفع عند الاستلام في خطوات سريعة.' ),
			array( 'title' => 'استلم طلبك', 'text' => 'يصلك طلبك مُغلّفًا بعناية خلال 2-4 أيام عمل إلى باب منزلك.' ),
		), $home_id );

		update_field( 'cta_badge_text', 'أكاديمية الأصول', $home_id );
		update_field( 'cta_heading', 'احترف الخياطة خطوة بخطوة', $home_id );
		update_field( 'cta_text', 'من أول غرزة حتى إدارة مشروعك الخاص، برامج تدريبية متكاملة بإشراف خبراء لهم أكثر من 5 سنوات في المجال.', $home_id );
		update_field( 'cta_button_text', 'اعرف تفاصيل الالتحاق', $home_id );
		update_field( 'cta_button_link', '/contact/', $home_id );

		update_field( 'testimonials_eyebrow', 'آراء العملاء', $home_id );
		update_field( 'testimonials_heading', 'ثقة أكثر من 1200 عميل', $home_id );
		$t_img_1 = ko_import_local_image( 'assets/images/about/measuring-waist.jpg' );
		$t_img_2 = ko_import_local_image( 'assets/images/about/about-measuring.jpg' );
		$t_img_3 = ko_import_local_image( 'assets/images/hero/hero-vintage.jpg' );
		update_field( 'testimonials', array(
			array( 'text' => 'تعاملت مع أكتر من متجر أدوات خياطة، بس للخياطة أصول مختلفين في جودة المنتج والتغليف. الماكينة وصلتني سليمة تمامًا.', 'image' => $t_img_1 ? wp_get_attachment_url( $t_img_1 ) : '', 'name' => 'سارة محمود', 'role' => 'خياطة منزلية' ),
			array( 'text' => 'الدعم الفني بجد حقيقي مش شعار بس. اتصلت لاستفسار بسيط ولقيت رد سريع وحل فعلي لمشكلتي في نفس اليوم.', 'image' => $t_img_2 ? wp_get_attachment_url( $t_img_2 ) : '', 'name' => 'أحمد رضا', 'role' => 'صاحب ورشة خياطة' ),
			array( 'text' => 'اشتريت مسطرة تحديد المقاسات وكانت بالظبط زي ما محتاجة، والتوصيل كان أسرع من المتوقع. هرجع أطلب تاني أكيد.', 'image' => $t_img_3 ? wp_get_attachment_url( $t_img_3 ) : '', 'name' => 'منى السيد', 'role' => 'مصممة أزياء' ),
		), $home_id );
	}

	// ---- من نحن ----
	$about_id = ko_get_or_create_page( 'من نحن', 'page-about.php' );
	$ids['about'] = $about_id;
	if ( function_exists( 'update_field' ) ) {
		update_field( 'story_eyebrow', 'قصتنا', $about_id );
		update_field( 'story_heading', 'للخياطة أصول.. وللأصول أهلها', $about_id );
		update_field( 'story_text_1', 'بدأنا رحلتنا من إيمان بسيط: أن عالم الخياطة في مصر يستحق منصة تحترم تفاصيله. بعد أكثر من 5 سنوات من الخبرة الميدانية في تجارة وصيانة ماكينات وأدوات الخياطة، أطلقنا "للخياطة أصول" لتكون المنصة الأولى المتكاملة التي تجمع بين متجر أدوات أصلية، وأكاديمية تعليم احترافي، ودعم فني حقيقي بعد البيع — في مكان واحد.', $about_id );
		update_field( 'story_text_2', 'نؤمن أن الأدوات الجيدة وحدها لا تكفي، لذلك نحرص على أن يصل كل منتج بجودة تصدير وتغليف مدرّع، وأن يجد كل عميل فريقًا يسمعه ويحل مشكلته بصدق، لا مجرد بائع.', $about_id );
		$img1 = ko_import_local_image( 'assets/images/about/about-measuring.jpg' );
		$img2 = ko_import_local_image( 'assets/images/hero/hero-vintage.jpg' );
		if ( $img1 ) update_field( 'story_image_1', wp_get_attachment_url( $img1 ), $about_id );
		if ( $img2 ) update_field( 'story_image_2', wp_get_attachment_url( $img2 ), $about_id );

		update_field( 'why_choose_eyebrow', 'لماذا تختارنا', $about_id );
		update_field( 'why_choose_heading', 'خبرة حقيقية تصنع فرقًا', $about_id );
		update_field( 'why_choose_text', 'نجمع بين جودة الأدوات وصدق التعامل، لنكون الوجهة الأولى لكل خيّاط ومصمم أزياء في مصر.', $about_id );
		update_field( 'why_choose_items', array(
			array( 'icon' => 'fa-solid fa-medal', 'title' => 'أدوات أصلية ومتينة', 'text' => 'ماكينات وأدوات مضمونة المصدر تدوم طويلًا مع الاستخدام اليومي المكثف.' ),
			array( 'icon' => 'fa-solid fa-headset', 'title' => 'دعم فني حقيقي', 'text' => 'ضمان بعد البيع وتوفر قطع الغيار مع فريق دعم يرد على استفساراتك بسرعة.' ),
			array( 'icon' => 'fa-solid fa-box', 'title' => 'تغليف مدرّع', 'text' => 'تغليف مقاوم للصدمات يحافظ على منتجك من باب المصنع حتى باب بيتك.' ),
			array( 'icon' => 'fa-solid fa-truck-fast', 'title' => 'توصيل سريع وآمن', 'text' => 'شحن لكل محافظات مصر خلال 2-4 أيام عمل، مع الدفع عند الاستلام.' ),
		), $about_id );

		update_field( 'pillars_eyebrow', 'رسالتنا وركائزنا', $about_id );
		update_field( 'pillars_heading', 'ثلاث ركائز تصنع الفرق', $about_id );
		update_field( 'pillars', array(
			array( 'icon' => 'fa-solid fa-store', 'title' => 'متجر النوادر', 'text' => 'ماكينات وأدوات خياطة أصلية ونادرة يصعب إيجادها في مكان واحد، بأسعار منافسة وجودة موثوقة.' ),
			array( 'icon' => 'fa-solid fa-graduation-cap', 'title' => 'أكاديمية الأصول', 'text' => 'برامج تدريبية تبدأ من الأساسيات وتصل إلى إدارة المشاريع الاحترافية، بإشراف مدربين متخصصين.' ),
			array( 'icon' => 'fa-solid fa-screwdriver-wrench', 'title' => 'دعم فني وضمان', 'text' => 'ضمان حقيقي بعد البيع، وتوفر قطع الغيار، وفريق دعم فني يرد بسرعة وصدق على كل استفسار.' ),
		), $about_id );

		update_field( 'about_stats', array(
			array( 'icon' => 'fa-solid fa-award', 'number' => 5, 'suffix' => '+', 'label' => 'سنوات خبرة' ),
			array( 'icon' => 'fa-solid fa-face-smile', 'number' => 1200, 'suffix' => '+', 'label' => 'عميل راضٍ' ),
			array( 'icon' => 'fa-solid fa-box-open', 'number' => 15, 'suffix' => '+', 'label' => 'منتج أصلي' ),
			array( 'icon' => 'fa-solid fa-truck-fast', 'number' => 27, 'suffix' => '', 'label' => 'محافظة توصيل' ),
		), $about_id );

		update_field( 'quote_heading', '"للخياطة أصول.. وللأصول أهلها"', $about_id );
		update_field( 'quote_text', 'هذه ليست مجرد شعار، بل فلسفة عمل: كل غرزة لها أصول، وكل أداة لها معيار، وكل عميل يستحق تعاملًا صادقًا يليق بحرفة الخياطة العريقة.', $about_id );
		update_field( 'final_cta_heading', 'ابدأ رحلتك معنا اليوم', $about_id );
		update_field( 'final_cta_text', 'تصفّح متجرنا أو تواصل معنا لمعرفة تفاصيل الالتحاق بأكاديمية الأصول.', $about_id );
	}

	// ---- تواصل معنا ----
	$contact_id = ko_get_or_create_page( 'تواصل معنا', 'page-contact.php' );
	$ids['contact'] = $contact_id;
	if ( function_exists( 'update_field' ) ) {
		update_field( 'contact_intro', 'فريقنا جاهز للرد على استفساراتك بخصوص المنتجات أو الطلبات أو الالتحاق بأكاديمية الأصول.', $contact_id );
		update_field( 'faq_eyebrow', 'الأسئلة الشائعة', $contact_id );
		update_field( 'faq_heading', 'عندك سؤال؟ يمكن نكون جاوبنا عليه', $contact_id );
		update_field( 'faq_items', array(
			array( 'question' => 'كم تستغرق مدة التوصيل؟', 'answer' => 'يصل طلبك خلال 2-4 أيام عمل لجميع محافظات مصر، مع تغليف مقاوم للصدمات للحفاظ على المنتج.' ),
			array( 'question' => 'هل يوجد ضمان على الماكينات؟', 'answer' => 'نعم، جميع منتجاتنا الأصلية مضمونة بعد البيع مع توفر قطع الغيار ودعم فني حقيقي عند الحاجة.' ),
			array( 'question' => 'هل يمكن الدفع عند الاستلام؟', 'answer' => 'بالتأكيد، نوفر خاصية الدفع عند الاستلام نقدًا لجميع الطلبات في جميع محافظات مصر.' ),
			array( 'question' => 'كيف ألتحق بأكاديمية الأصول؟', 'answer' => 'تواصل معنا عبر الهاتف أو واتساب أو نموذج التواصل، وسيقوم فريقنا بشرح البرامج التدريبية المتاحة ومواعيدها.' ),
		), $contact_id );
	}

	return $ids;
}

/** يبحث عن صفحة بنفس العنوان أو ينشئها، ويضبط قالبها إن طُلب. */
function ko_get_or_create_page( $title, $template = '' ) {
	$found = get_posts( array(
		'post_type'      => 'page',
		'title'          => $title,
		'post_status'    => 'any',
		'posts_per_page' => 1,
		'fields'         => 'ids',
	) );
	if ( $found ) {
		$existing_id = $found[0];
		if ( $template ) update_post_meta( $existing_id, '_wp_page_template', $template );
		return $existing_id;
	}
	$id = wp_insert_post( array(
		'post_title'  => $title,
		'post_type'   => 'page',
		'post_status' => 'publish',
	) );
	if ( $id && $template ) update_post_meta( $id, '_wp_page_template', $template );
	return $id;
}

/** ينشئ قائمة تنقّل رئيسية بالصفحات الأربع ويربطها بمواضع الهيدر/الموبايل. */
function ko_demo_import_menu( $page_ids ) {
	$menu_name = 'القائمة الرئيسية';
	$menu_id   = 0;
	$existing_menu = wp_get_nav_menu_object( $menu_name );
	if ( $existing_menu ) {
		$menu_id = $existing_menu->term_id;
	} else {
		$created = wp_create_nav_menu( $menu_name );
		if ( ! is_wp_error( $created ) ) $menu_id = $created;
	}
	if ( ! $menu_id ) return;

	$items = array(
		array( 'title' => 'الرئيسية', 'url' => home_url( '/' ) ),
		array( 'title' => 'المتجر', 'url' => class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ),
	);
	if ( ! empty( $page_ids['about'] ) ) $items[] = array( 'title' => 'من نحن', 'object_id' => $page_ids['about'] );
	if ( ! empty( $page_ids['contact'] ) ) $items[] = array( 'title' => 'تواصل معنا', 'object_id' => $page_ids['contact'] );

	$existing_items = wp_get_nav_menu_items( $menu_id );
	if ( empty( $existing_items ) ) {
		foreach ( $items as $item ) {
			if ( ! empty( $item['object_id'] ) ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'     => $item['title'],
					'menu-item-object-id' => $item['object_id'],
					'menu-item-object'    => 'page',
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
				) );
			} else {
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'  => $item['title'],
					'menu-item-url'    => $item['url'],
					'menu-item-type'   => 'custom',
					'menu-item-status' => 'publish',
				) );
			}
		}
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary'] = $menu_id;
	$locations['mobile']  = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}
