<?php
/**
 * لوحة "خيارات القالب" — منبنية بالكامل على WordPress Settings API (بدون أي مكتبة خارجية)،
 * بواجهة تبويبات شبيهة بلوحة Woodmart (Theme Settings) لأن إطار XTS الخاص بـ Woodmart إطار
 * مملوك (proprietary) لا يمكن استخدامه هنا. كل الإعدادات تُخزَّن في خيار واحد (ko_theme_options)
 * ويُقرأ منها عبر الدالة المساعدة ko_opt( $key, $default ).
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class KO_Theme_Options {

	const OPTION_NAME = 'ko_theme_options';
	const PAGE_SLUG   = 'ko-theme-options';

	private static $values = null;

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_setting' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
	}

	public static function add_menu() {
		add_menu_page(
			__( 'خيارات قالب للخياطة أصول', 'kheyata-ossol' ),
			__( 'خيارات القالب', 'kheyata-ossol' ),
			'manage_options',
			self::PAGE_SLUG,
			array( __CLASS__, 'render_page' ),
			'dashicons-art',
			61
		);
	}

	public static function register_setting() {
		register_setting( self::OPTION_NAME, self::OPTION_NAME, array(
			'sanitize_callback' => array( __CLASS__, 'sanitize' ),
		) );
	}

	public static function enqueue_admin_assets( $hook ) {
		if ( strpos( $hook, self::PAGE_SLUG ) === false ) return;
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_media();
		wp_enqueue_style( 'ko-options-admin', KO_THEME_URI . '/inc/theme-options/options-panel-admin.css', array(), KO_THEME_VERSION );
		wp_enqueue_script( 'ko-options-admin', KO_THEME_URI . '/inc/theme-options/options-panel-admin.js', array( 'jquery', 'wp-color-picker' ), KO_THEME_VERSION, true );
	}

	/* ---------------------------------------------------------
	 * تعريف التبويبات والحقول — كل تبويب يقابل مجموعة إعدادات في Woodmart
	 * (عام، الهيدر، الفوتر، الألوان والخطوط، المتجر، صفحة المنتج، التواصل، الأداء)
	 * --------------------------------------------------------- */
	public static function get_tabs() {
		return array(

			'general' => array(
				'label' => __( 'عام', 'kheyata-ossol' ),
				'icon'  => 'dashicons-admin-generic',
				'fields' => array(
					'site_logo' => array( 'type' => 'image', 'label' => __( 'شعار الموقع', 'kheyata-ossol' ), 'desc' => __( 'يظهر في الهيدر والفوتر وبريد الطلبات.', 'kheyata-ossol' ) ),
					'preloader_enable' => array( 'type' => 'checkbox', 'label' => __( 'تفعيل شاشة تحميل أولية (Preloader)', 'kheyata-ossol' ), 'default' => '0' ),
					'back_to_top' => array( 'type' => 'checkbox', 'label' => __( 'إظهار زر "العودة للأعلى"', 'kheyata-ossol' ), 'default' => '1' ),
					'whatsapp_float_button' => array( 'type' => 'checkbox', 'label' => __( 'إظهار زر واتساب العائم', 'kheyata-ossol' ), 'default' => '1' ),
					'card_radius' => array( 'type' => 'number', 'label' => __( 'استدارة زوايا البطاقات (px)', 'kheyata-ossol' ), 'default' => '16', 'min' => 0, 'max' => 40 ),
				),
			),

			'header' => array(
				'label' => __( 'الهيدر', 'kheyata-ossol' ),
				'icon'  => 'dashicons-align-center',
				'fields' => array(
					'sticky_header' => array( 'type' => 'checkbox', 'label' => __( 'هيدر ثابت أثناء التمرير (Sticky)', 'kheyata-ossol' ), 'default' => '1' ),
					'header_height' => array( 'type' => 'number', 'label' => __( 'ارتفاع الهيدر على سطح المكتب (px)', 'kheyata-ossol' ), 'default' => '84', 'min' => 60, 'max' => 140 ),
					'show_account_icon' => array( 'type' => 'checkbox', 'label' => __( 'إظهار أيقونة الحساب في الهيدر (سطح المكتب)', 'kheyata-ossol' ), 'default' => '1' ),
					'show_cart_icon' => array( 'type' => 'checkbox', 'label' => __( 'إظهار أيقونة السلة في الهيدر', 'kheyata-ossol' ), 'default' => '1' ),
					'mini_cart_drawer' => array( 'type' => 'checkbox', 'label' => __( 'فتح سلة منزلقة (Mini Cart) عند الضغط على أيقونة السلة بدل الانتقال لصفحة السلة', 'kheyata-ossol' ), 'default' => '1' ),
					'header_cta_text' => array( 'type' => 'text', 'label' => __( 'نص زر دعوة إضافي (اختياري، فارغ = بدون زر)', 'kheyata-ossol' ), 'default' => '' ),
					'header_cta_link' => array( 'type' => 'url', 'label' => __( 'رابط زر الدعوة', 'kheyata-ossol' ), 'default' => '' ),
				),
			),

			'footer' => array(
				'label' => __( 'الفوتر', 'kheyata-ossol' ),
				'icon'  => 'dashicons-align-wide',
				'fields' => array(
					'footer_columns' => array( 'type' => 'select', 'label' => __( 'نوع الفوتر الافتراضي للصفحات', 'kheyata-ossol' ), 'default' => 'full', 'options' => array( 'full' => __( 'كامل (4 أعمدة + واتساب عائم)', 'kheyata-ossol' ), 'minimal' => __( 'مبسّط (سطر الحقوق فقط)', 'kheyata-ossol' ) ) ),
					'footer_about_text' => array( 'type' => 'textarea', 'label' => __( 'نبذة مختصرة تحت الشعار في الفوتر', 'kheyata-ossol' ), 'default' => 'المنصة الأولى المتكاملة في عالم الخياطة: أدوات وماكينات أصلية، تعليم احترافي، ودعم فني حقيقي.' ),
					'footer_copyright' => array( 'type' => 'text', 'label' => __( 'نص الحقوق (يدعم [year] و [site_name])', 'kheyata-ossol' ), 'default' => '© [year] [site_name]. جميع الحقوق محفوظة.' ),
				),
			),

			'colors' => array(
				'label' => __( 'الألوان', 'kheyata-ossol' ),
				'icon'  => 'dashicons-admin-customizer',
				'fields' => array(
					'color_primary'       => array( 'type' => 'color', 'label' => __( '[عام] اللون الأساسي (Primary)', 'kheyata-ossol' ), 'default' => '#1B4C8C' ),
					'color_primary_dark'  => array( 'type' => 'color', 'label' => __( '[عام] اللون الأساسي الغامق (Hover)', 'kheyata-ossol' ), 'default' => '#0F3164' ),
					'color_primary_light' => array( 'type' => 'color', 'label' => __( '[عام] اللون الأساسي الفاتح', 'kheyata-ossol' ), 'default' => '#3A6DB5' ),
					'color_accent'        => array( 'type' => 'color', 'label' => __( '[عام] لون التمييز (Accent - ذهبي)', 'kheyata-ossol' ), 'default' => '#C9A24B' ),
					'color_accent_light'  => array( 'type' => 'color', 'label' => __( '[عام] لون التمييز الفاتح', 'kheyata-ossol' ), 'default' => '#E8D5A0' ),
					'color_accent_dark'   => array( 'type' => 'color', 'label' => __( '[عام] لون التمييز الغامق (نصوص متباينة)', 'kheyata-ossol' ), 'default' => '#82631E' ),
					'color_bg'            => array( 'type' => 'color', 'label' => __( '[عام] خلفية الموقع', 'kheyata-ossol' ), 'default' => '#FBF7EC' ),
					'color_bg_alt'        => array( 'type' => 'color', 'label' => __( '[عام] خلفية الأقسام البديلة', 'kheyata-ossol' ), 'default' => '#F2E9D4' ),
					'color_text'          => array( 'type' => 'color', 'label' => __( '[عام] لون النص الأساسي', 'kheyata-ossol' ), 'default' => '#16233B' ),
					'color_text_muted'    => array( 'type' => 'color', 'label' => __( '[عام] لون النص الثانوي (الوصف)', 'kheyata-ossol' ), 'default' => '#5B6472' ),
					'header_bg_color'     => array( 'type' => 'color', 'label' => __( '[الهيدر] لون خلفية الهيدر', 'kheyata-ossol' ), 'default' => '#FBF7EC' ),
					'footer_bg_color'     => array( 'type' => 'color', 'label' => __( '[الفوتر] لون الخلفية', 'kheyata-ossol' ), 'default' => '#1B4C8C' ),
					'footer_text_color'   => array( 'type' => 'color', 'label' => __( '[الفوتر] لون النص', 'kheyata-ossol' ), 'default' => '#FFFFFF' ),
					'price_color'         => array( 'type' => 'color', 'label' => __( '[المنتجات] لون السعر', 'kheyata-ossol' ), 'default' => '#1B4C8C' ),
					'sale_badge_color'    => array( 'type' => 'color', 'label' => __( '[المنتجات] لون شارة الخصم', 'kheyata-ossol' ), 'default' => '#C9A24B' ),
					'whatsapp_color'      => array( 'type' => 'color', 'label' => __( 'لون زر واتساب العائم', 'kheyata-ossol' ), 'default' => '#25D366' ),
				),
			),

			'typography' => array(
				'label' => __( 'الخطوط', 'kheyata-ossol' ),
				'icon'  => 'dashicons-editor-textcolor',
				'fields' => array(
					'heading_font'  => array( 'type' => 'select', 'label' => __( 'خط العناوين', 'kheyata-ossol' ), 'default' => 'El Messiri', 'options' => array( 'El Messiri' => 'El Messiri', 'Tajawal' => 'Tajawal', 'Cairo' => 'Cairo', 'Almarai' => 'Almarai' ) ),
					'body_font'     => array( 'type' => 'select', 'label' => __( 'خط النصوص العامة', 'kheyata-ossol' ), 'default' => 'Tajawal', 'options' => array( 'Tajawal' => 'Tajawal', 'Cairo' => 'Cairo', 'Almarai' => 'Almarai' ) ),
					'numeric_font'  => array( 'type' => 'select', 'label' => __( 'خط الأرقام (الأسعار والعدّادات)', 'kheyata-ossol' ), 'default' => 'DM Sans', 'options' => array( 'DM Sans' => 'DM Sans', 'Tajawal' => 'Tajawal' ) ),
					'base_font_size'=> array( 'type' => 'number', 'label' => __( 'حجم الخط الأساسي (px)', 'kheyata-ossol' ), 'default' => '16', 'min' => 13, 'max' => 20 ),
					'h1_size'       => array( 'type' => 'number', 'label' => __( 'حجم عنوان H1 على سطح المكتب (px)', 'kheyata-ossol' ), 'default' => '44', 'min' => 24, 'max' => 72 ),
					'h2_size'       => array( 'type' => 'number', 'label' => __( 'حجم عنوان H2 (px)', 'kheyata-ossol' ), 'default' => '32', 'min' => 20, 'max' => 56 ),
					'h3_size'       => array( 'type' => 'number', 'label' => __( 'حجم عنوان H3 (px)', 'kheyata-ossol' ), 'default' => '20', 'min' => 14, 'max' => 32 ),
				),
			),

			'shop' => array(
				'label' => __( 'المتجر ونمط العرض (WooCommerce)', 'kheyata-ossol' ),
				'icon'  => 'dashicons-cart',
				'fields' => array(
					'shop_columns_desktop' => array( 'type' => 'select', 'label' => __( 'عدد أعمدة المنتجات (سطح المكتب)', 'kheyata-ossol' ), 'default' => '3', 'options' => array( '3' => '3', '4' => '4' ) ),
					'shop_products_per_page' => array( 'type' => 'number', 'label' => __( 'عدد المنتجات في صفحة المتجر', 'kheyata-ossol' ), 'default' => '12', 'min' => 4, 'max' => 48 ),
					'shop_filters_style' => array( 'type' => 'select', 'label' => __( 'شكل الفلاتر على الموبايل/التابلت', 'kheyata-ossol' ), 'default' => 'drawer', 'options' => array( 'drawer' => __( 'لوحة منزلقة (Drawer) — كالتصميم الأصلي', 'kheyata-ossol' ), 'inline' => __( 'أعلى الصفحة مباشرة', 'kheyata-ossol' ) ) ),
					'product_card_hover_zoom' => array( 'type' => 'checkbox', 'label' => __( 'تكبير صورة المنتج عند المرور بالماوس', 'kheyata-ossol' ), 'default' => '1' ),
					'show_product_rating' => array( 'type' => 'checkbox', 'label' => __( 'إظهار تقييم النجوم في بطاقة المنتج', 'kheyata-ossol' ), 'default' => '1' ),
					'show_product_category_label' => array( 'type' => 'checkbox', 'label' => __( 'إظهار اسم التصنيف أعلى بطاقة المنتج', 'kheyata-ossol' ), 'default' => '1' ),
					'show_quick_view' => array( 'type' => 'checkbox', 'label' => __( 'إظهار زر "معاينة سريعة" (Quick View) في بطاقة المنتج', 'kheyata-ossol' ), 'default' => '1' ),
					'sale_badge_text' => array( 'type' => 'text', 'label' => __( 'نص شارة الخصم', 'kheyata-ossol' ), 'default' => 'خصم' ),
					'out_of_stock_text' => array( 'type' => 'text', 'label' => __( 'نص "نفدت الكمية"', 'kheyata-ossol' ), 'default' => 'نفدت الكمية' ),
					'free_shipping_threshold' => array( 'type' => 'number', 'label' => __( 'الحد الأدنى للشحن المجاني (ج.م، 0 = تعطيل)', 'kheyata-ossol' ), 'default' => '1500', 'min' => 0 ),
					'shipping_cost' => array( 'type' => 'number', 'label' => __( 'تكلفة الشحن الثابتة (ج.م)', 'kheyata-ossol' ), 'default' => '60', 'min' => 0 ),
					'show_related_products' => array( 'type' => 'checkbox', 'label' => __( 'إظهار "منتجات ذات صلة" في صفحة المنتج', 'kheyata-ossol' ), 'default' => '1' ),
					'related_products_count' => array( 'type' => 'number', 'label' => __( 'عدد المنتجات ذات الصلة', 'kheyata-ossol' ), 'default' => '4', 'min' => 2, 'max' => 8 ),
				),
			),

			'single_product' => array(
				'label' => __( 'صفحة المنتج', 'kheyata-ossol' ),
				'icon'  => 'dashicons-tag',
				'fields' => array(
					'show_stock_badge' => array( 'type' => 'checkbox', 'label' => __( 'إظهار شارة "متوفر في المخزون"', 'kheyata-ossol' ), 'default' => '1' ),
					'show_trust_badges' => array( 'type' => 'checkbox', 'label' => __( 'إظهار صف مزايا الثقة (توصيل / دفع عند الاستلام / ضمان / تغليف)', 'kheyata-ossol' ), 'default' => '1' ),
					'sticky_mobile_cart_bar' => array( 'type' => 'checkbox', 'label' => __( 'شريط "أضف للسلة" ثابت أسفل الشاشة على الموبايل', 'kheyata-ossol' ), 'default' => '1' ),
					'trust_badge_1' => array( 'type' => 'text', 'label' => __( 'نص الميزة 1', 'kheyata-ossol' ), 'default' => 'توصيل 2-4 أيام عمل' ),
					'trust_badge_2' => array( 'type' => 'text', 'label' => __( 'نص الميزة 2', 'kheyata-ossol' ), 'default' => 'الدفع عند الاستلام' ),
					'trust_badge_3' => array( 'type' => 'text', 'label' => __( 'نص الميزة 3', 'kheyata-ossol' ), 'default' => 'ضمان ودعم فني' ),
					'trust_badge_4' => array( 'type' => 'text', 'label' => __( 'نص الميزة 4', 'kheyata-ossol' ), 'default' => 'تغليف مقاوم للصدمات' ),
				),
			),

			'contact' => array(
				'label' => __( 'التواصل والسوشيال ميديا', 'kheyata-ossol' ),
				'icon'  => 'dashicons-email',
				'fields' => array(
					'contact_phone' => array( 'type' => 'text', 'label' => __( 'رقم الهاتف (بصيغة +20...)', 'kheyata-ossol' ), 'default' => '+201090872716' ),
					'contact_whatsapp' => array( 'type' => 'text', 'label' => __( 'رقم واتساب (أرقام فقط بصيغة دولية، بدون +)', 'kheyata-ossol' ), 'default' => '201090872716' ),
					'contact_email' => array( 'type' => 'text', 'label' => __( 'البريد الإلكتروني', 'kheyata-ossol' ), 'default' => 'info@khayataosol.com' ),
					'contact_service_area' => array( 'type' => 'text', 'label' => __( 'نطاق الخدمة', 'kheyata-ossol' ), 'default' => 'شحن وتوصيل لجميع محافظات مصر (27 محافظة)' ),
					'social_facebook' => array( 'type' => 'url', 'label' => __( 'رابط فيسبوك', 'kheyata-ossol' ), 'default' => '' ),
					'social_instagram' => array( 'type' => 'url', 'label' => __( 'رابط إنستجرام', 'kheyata-ossol' ), 'default' => '' ),
					'social_tiktok' => array( 'type' => 'url', 'label' => __( 'رابط تيك توك', 'kheyata-ossol' ), 'default' => '' ),
					'cf7_contact_form_id' => array( 'type' => 'text', 'label' => __( 'معرّف نموذج Contact Form 7 لصفحة تواصل معنا', 'kheyata-ossol' ), 'desc' => __( 'انسخ المعرّف [id] من صفحة "نماذج الاتصال" في القائمة الجانبية.', 'kheyata-ossol' ), 'default' => '' ),
				),
			),

			'performance' => array(
				'label' => __( 'الأداء', 'kheyata-ossol' ),
				'icon'  => 'dashicons-performance',
				'fields' => array(
					'disable_emojis' => array( 'type' => 'checkbox', 'label' => __( 'تعطيل سكربت الإيموجي الافتراضي بووردبريس', 'kheyata-ossol' ), 'default' => '1' ),
					'disable_embeds' => array( 'type' => 'checkbox', 'label' => __( 'تعطيل سكربت oEmbed الافتراضي', 'kheyata-ossol' ), 'default' => '1' ),
					'disable_block_css' => array( 'type' => 'checkbox', 'label' => __( 'تعطيل أنماط قوالب الكتل (Block Library CSS) غير المستخدمة', 'kheyata-ossol' ), 'default' => '0' ),
					'lazy_load_images' => array( 'type' => 'checkbox', 'label' => __( 'التحميل الكسول للصور (Lazy Load)', 'kheyata-ossol' ), 'default' => '1' ),
				),
			),

			'custom_code' => array(
				'label' => __( 'كود مخصص', 'kheyata-ossol' ),
				'icon'  => 'dashicons-editor-code',
				'fields' => array(
					'custom_css' => array( 'type' => 'code', 'label' => __( 'CSS مخصص', 'kheyata-ossol' ), 'desc' => __( 'يُضاف داخل <style> في رأس كل صفحة، بعد أنماط القالب — فوقها في الأولوية.', 'kheyata-ossol' ), 'default' => '' ),
					'custom_js'  => array( 'type' => 'code', 'label' => __( 'JavaScript مخصص', 'kheyata-ossol' ), 'desc' => __( 'يُضاف قبل إغلاق </body> في كل صفحة (بدون وسم <script>).', 'kheyata-ossol' ), 'default' => '' ),
				),
			),
		);
	}

	public static function all() {
		if ( self::$values === null ) {
			$saved = get_option( self::OPTION_NAME, array() );
			$defaults = array();
			foreach ( self::get_tabs() as $tab ) {
				foreach ( $tab['fields'] as $key => $field ) {
					$defaults[ $key ] = isset( $field['default'] ) ? $field['default'] : '';
				}
			}
			self::$values = wp_parse_args( $saved, $defaults );
		}
		return self::$values;
	}

	public static function get( $key, $default = '' ) {
		$all = self::all();
		return isset( $all[ $key ] ) && $all[ $key ] !== '' ? $all[ $key ] : $default;
	}

	public static function sanitize( $input ) {
		$clean = array();
		$fields_by_key = array();
		foreach ( self::get_tabs() as $tab ) {
			foreach ( $tab['fields'] as $key => $field ) {
				$fields_by_key[ $key ] = $field;
			}
		}
		foreach ( $fields_by_key as $key => $field ) {
			$type = $field['type'];
			if ( $type === 'checkbox' ) {
				$clean[ $key ] = isset( $input[ $key ] ) ? '1' : '0';
				continue;
			}
			if ( ! isset( $input[ $key ] ) ) {
				$clean[ $key ] = '';
				continue;
			}
			switch ( $type ) {
				case 'color':
					$clean[ $key ] = sanitize_hex_color( $input[ $key ] );
					break;
				case 'number':
					$clean[ $key ] = (string) absint( $input[ $key ] );
					break;
				case 'url':
					$clean[ $key ] = esc_url_raw( trim( $input[ $key ] ) );
					break;
				case 'textarea':
					$clean[ $key ] = sanitize_textarea_field( $input[ $key ] );
					break;
				case 'image':
					$clean[ $key ] = esc_url_raw( $input[ $key ] );
					break;
				case 'code':
					/* لا نستخدم sanitize_textarea_field هنا لأنه يحذف كل ما يشبه وسم HTML —
					   ما يُفسد CSS/JS مشروع (مثل "a > b" أو مقارنات "<"). الحقل مقصور على
					   من يملك صلاحية manage_options أصلاً، فقط نمنع كسر وسم <style>/<script>
					   المُغلِّف عبر إغلاقه مبكرًا، بنفس أسلوب "Additional CSS" في ووردبريس الأصلي. */
					$clean[ $key ] = str_ireplace( array( '</style', '</script' ), array( '<\\/style', '<\\/script' ), (string) $input[ $key ] );
					break;
				default:
					$clean[ $key ] = sanitize_text_field( $input[ $key ] );
			}
		}
		return $clean;
	}

	/* ---------------------------------------------------------
	 * الإخراج (واجهة الإدارة)
	 * --------------------------------------------------------- */
	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) return;
		$tabs = self::get_tabs();
		$active = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : array_key_first( $tabs );
		if ( ! isset( $tabs[ $active ] ) ) $active = array_key_first( $tabs );
		$values = self::all();
		?>
		<div class="wrap ko-options-wrap">
			<div class="ko-options-header">
				<h1><span class="dashicons dashicons-art"></span> <?php esc_html_e( 'خيارات قالب للخياطة أصول', 'kheyata-ossol' ); ?></h1>
				<p><?php esc_html_e( 'لوحة تحكم شاملة على طراز Woodmart لضبط الهيدر والفوتر والألوان وإعدادات المتجر — بدون التعديل في الأكواد.', 'kheyata-ossol' ); ?></p>
			</div>
			<div class="ko-options-body">
				<nav class="ko-options-tabs">
					<?php foreach ( $tabs as $key => $tab ) : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . self::PAGE_SLUG . '&tab=' . $key ) ); ?>" class="ko-tab-link <?php echo $key === $active ? 'is-active' : ''; ?>">
							<span class="dashicons <?php echo esc_attr( $tab['icon'] ); ?>"></span> <?php echo esc_html( $tab['label'] ); ?>
						</a>
					<?php endforeach; ?>
				</nav>
				<form method="post" action="options.php" class="ko-options-form">
					<?php settings_fields( self::OPTION_NAME ); ?>
					<div class="ko-options-panel">
						<h2><?php echo esc_html( $tabs[ $active ]['label'] ); ?></h2>
						<table class="form-table" role="presentation">
							<?php foreach ( $tabs[ $active ]['fields'] as $key => $field ) : self::render_field( $key, $field, $values ); endforeach; ?>
						</table>
						<?php submit_button( __( 'حفظ الإعدادات', 'kheyata-ossol' ) ); ?>
					</div>
					<?php // نُبقي قيم بقية التبويبات كما هي عبر حقول مخفية حتى لا تُفقَد عند الحفظ من تبويب واحد. ?>
					<?php foreach ( $tabs as $tkey => $tab ) : if ( $tkey === $active ) continue; foreach ( $tab['fields'] as $key => $field ) :
						$val = isset( $values[ $key ] ) ? $values[ $key ] : '';
						if ( is_array( $val ) ) continue; ?>
						<input type="hidden" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $val ); ?>">
					<?php endforeach; endforeach; ?>
				</form>
			</div>
		</div>
		<?php
	}

	private static function render_field( $key, $field, $values ) {
		$name  = self::OPTION_NAME . '[' . $key . ']';
		$id    = 'ko-field-' . $key;
		$value = isset( $values[ $key ] ) ? $values[ $key ] : '';
		?>
		<tr>
			<th scope="row"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
			<td>
				<?php switch ( $field['type'] ) :
					case 'checkbox': ?>
						<label class="ko-switch">
							<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( $value, '1' ); ?>>
							<span class="ko-switch-slider"></span>
						</label>
						<?php break;
					case 'textarea': ?>
						<textarea id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="3" class="large-text"><?php echo esc_textarea( $value ); ?></textarea>
						<?php break;
					case 'code': ?>
						<textarea id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="10" class="large-text code" dir="ltr" style="font-family:Consolas,Monaco,monospace; direction:ltr; text-align:left;"><?php echo esc_textarea( $value ); ?></textarea>
						<?php break;
					case 'color': ?>
						<input type="text" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="ko-color-field" data-default-color="<?php echo esc_attr( $field['default'] ); ?>">
						<?php break;
					case 'select': ?>
						<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>">
							<?php foreach ( $field['options'] as $opt_val => $opt_label ) : ?>
								<option value="<?php echo esc_attr( $opt_val ); ?>" <?php selected( $value, $opt_val ); ?>><?php echo esc_html( $opt_label ); ?></option>
							<?php endforeach; ?>
						</select>
						<?php break;
					case 'number': ?>
						<input type="number" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" min="<?php echo esc_attr( $field['min'] ?? 0 ); ?>" max="<?php echo esc_attr( $field['max'] ?? 9999 ); ?>" class="small-text">
						<?php break;
					case 'url': ?>
						<input type="url" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="regular-text" placeholder="https://" dir="ltr">
						<?php break;
					case 'image': ?>
						<div class="ko-image-field">
							<input type="hidden" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_url( $value ); ?>">
							<div class="ko-image-preview" style="<?php echo $value ? '' : 'display:none;'; ?>">
								<img src="<?php echo esc_url( $value ); ?>" alt="">
								<button type="button" class="button ko-image-remove"><?php esc_html_e( 'إزالة', 'kheyata-ossol' ); ?></button>
							</div>
							<button type="button" class="button ko-image-upload" style="<?php echo $value ? 'display:none;' : ''; ?>"><?php esc_html_e( 'اختيار صورة', 'kheyata-ossol' ); ?></button>
						</div>
						<?php break;
					default: ?>
						<input type="text" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
				<?php endswitch;
				if ( ! empty( $field['desc'] ) ) : ?>
					<p class="description"><?php echo esc_html( $field['desc'] ); ?></p>
				<?php endif; ?>
			</td>
		</tr>
		<?php
	}
}
KO_Theme_Options::init();
