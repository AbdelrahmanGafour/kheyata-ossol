<?php
/**
 * حقول Secure Custom Fields — تجعل كل محتوى الصفحة الرئيسية وصفحة "من نحن" قابلاً للتحرير
 * من لوحة تحكم ووردبريس بدل أن يكون مكتوبًا مباشرة داخل القوالب. تُسجَّل محليًا عبر PHP
 * (Local Field Groups) حتى يكون القالب متكاملًا بدون الحاجة لاستيراد JSON يدويًا.
 */

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

add_action( 'acf/init', function () {

	/* =======================================================
	 * محتوى الصفحة الرئيسية
	 * ======================================================= */
	acf_add_local_field_group( array(
		'key'      => 'group_ko_home',
		'title'    => 'محتوى الصفحة الرئيسية',
		'location' => array( array( array( 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ) ) ),
		'fields'   => array(

			array( 'key' => 'field_ko_home_tab_hero', 'label' => 'قسم الهيرو', 'type' => 'tab' ),
			array( 'key' => 'field_ko_hero_badge', 'label' => 'نص الشارة العلوية', 'name' => 'hero_badge_text', 'type' => 'text', 'default_value' => 'خبرة أكثر من 5 سنوات في عالم الخياطة' ),
			array( 'key' => 'field_ko_hero_heading', 'label' => 'العنوان الرئيسي', 'name' => 'hero_heading', 'type' => 'text', 'default_value' => 'للخياطة أصول.. وللأصول أهلها' ),
			array( 'key' => 'field_ko_hero_lead', 'label' => 'الفقرة التعريفية', 'name' => 'hero_lead', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'المنصة الأولى المتكاملة في عالم الخياطة: متجر لأدوات وماكينات الخياطة الأصلية والنادرة، أكاديمية تعليم احترافي من الصفر حتى إدارة المشاريع، ودعم فني حقيقي بعد البيع.' ),
			array( 'key' => 'field_ko_hero_video', 'label' => 'فيديو الخلفية (MP4)', 'name' => 'hero_video', 'type' => 'file', 'mime_types' => 'mp4', 'return_format' => 'url' ),
			array( 'key' => 'field_ko_hero_poster', 'label' => 'صورة بديلة (Poster) قبل تشغيل الفيديو', 'name' => 'hero_poster', 'type' => 'image', 'return_format' => 'url' ),
			array( 'key' => 'field_ko_hero_btn1_text', 'label' => 'نص الزر الأول', 'name' => 'hero_btn1_text', 'type' => 'text', 'default_value' => 'تسوّق الآن' ),
			array( 'key' => 'field_ko_hero_btn1_link', 'label' => 'رابط الزر الأول', 'name' => 'hero_btn1_link', 'type' => 'text', 'default_value' => '/store/' ),
			array( 'key' => 'field_ko_hero_btn2_text', 'label' => 'نص الزر الثاني', 'name' => 'hero_btn2_text', 'type' => 'text', 'default_value' => 'تعرف علينا' ),
			array( 'key' => 'field_ko_hero_btn2_link', 'label' => 'رابط الزر الثاني', 'name' => 'hero_btn2_link', 'type' => 'text', 'default_value' => '/about/' ),
			array(
				'key' => 'field_ko_hero_stats', 'label' => 'شريط الإحصائيات', 'name' => 'hero_stats', 'type' => 'repeater',
				'layout' => 'table', 'min' => 4, 'max' => 4,
				'sub_fields' => array(
					array( 'key' => 'field_ko_hs_number', 'label' => 'الرقم', 'name' => 'number', 'type' => 'number' ),
					array( 'key' => 'field_ko_hs_suffix', 'label' => 'إضافة بعد الرقم (مثال: +)', 'name' => 'suffix', 'type' => 'text' ),
					array( 'key' => 'field_ko_hs_label', 'label' => 'الوصف', 'name' => 'label', 'type' => 'text' ),
				),
				'default_value' => array(
					array( 'number' => 5, 'suffix' => '+', 'label' => 'سنوات خبرة' ),
					array( 'number' => 1200, 'suffix' => '+', 'label' => 'عميل راضٍ' ),
					array( 'number' => 15, 'suffix' => '+', 'label' => 'منتج أصلي' ),
					array( 'number' => 27, 'suffix' => '', 'label' => 'محافظة توصيل' ),
				),
			),

			array( 'key' => 'field_ko_home_tab_categories', 'label' => 'قسم التصنيفات', 'type' => 'tab' ),
			array( 'key' => 'field_ko_categories_eyebrow', 'label' => 'الشارة العلوية', 'name' => 'categories_eyebrow', 'type' => 'text', 'default_value' => 'التصنيفات' ),
			array( 'key' => 'field_ko_categories_heading', 'label' => 'العنوان', 'name' => 'categories_heading', 'type' => 'text', 'default_value' => 'تسوّق حسب احتياجك' ),

			array( 'key' => 'field_ko_home_tab_newest', 'label' => 'قسم أحدث المنتجات', 'type' => 'tab' ),
			array( 'key' => 'field_ko_newest_eyebrow', 'label' => 'الشارة العلوية', 'name' => 'newest_eyebrow', 'type' => 'text', 'default_value' => 'وصل حديثًا' ),
			array( 'key' => 'field_ko_newest_heading', 'label' => 'العنوان', 'name' => 'newest_heading', 'type' => 'text', 'default_value' => 'أحدث المنتجات' ),
			array( 'key' => 'field_ko_newest_text', 'label' => 'النص التعريفي', 'name' => 'newest_text', 'type' => 'text', 'default_value' => 'آخر ما أضفناه لمتجرنا من أدوات وماكينات أصلية، تصفّحها أولًا بأول.' ),

			array( 'key' => 'field_ko_home_tab_services', 'label' => 'قسم خدماتنا', 'type' => 'tab' ),
			array( 'key' => 'field_ko_services_eyebrow', 'label' => 'الشارة العلوية', 'name' => 'services_eyebrow', 'type' => 'text', 'default_value' => 'خدماتنا' ),
			array( 'key' => 'field_ko_services_heading', 'label' => 'العنوان', 'name' => 'services_heading', 'type' => 'text', 'default_value' => 'ثلاث ركائز في منصة واحدة' ),
			array( 'key' => 'field_ko_services_text', 'label' => 'النص التعريفي', 'name' => 'services_text', 'type' => 'text', 'default_value' => 'لا نبيع أدوات فقط، بل نبني معك مسارًا متكاملًا للاحتراف في عالم الخياطة.' ),
			array(
				'key' => 'field_ko_services', 'label' => 'بطاقات الخدمات', 'name' => 'services', 'type' => 'repeater', 'layout' => 'block', 'min' => 1, 'max' => 6,
				'sub_fields' => array(
					array( 'key' => 'field_ko_srv_icon', 'label' => 'أيقونة (Font Awesome class)', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'fa-solid fa-store' ),
					array( 'key' => 'field_ko_srv_title', 'label' => 'العنوان', 'name' => 'title', 'type' => 'text' ),
					array( 'key' => 'field_ko_srv_text', 'label' => 'الوصف', 'name' => 'text', 'type' => 'textarea', 'rows' => 2 ),
					array( 'key' => 'field_ko_srv_link_text', 'label' => 'نص الرابط', 'name' => 'link_text', 'type' => 'text' ),
					array( 'key' => 'field_ko_srv_link_url', 'label' => 'رابط', 'name' => 'link_url', 'type' => 'text' ),
				),
				'default_value' => array(
					array( 'icon' => 'fa-solid fa-store', 'title' => 'متجر النوادر', 'text' => 'أدوات وماكينات خياطة أصلية ونادرة يصعب إيجادها، بجودة تصدير وأسعار منافسة.', 'link_text' => 'تصفح المتجر', 'link_url' => '/store/' ),
					array( 'icon' => 'fa-solid fa-graduation-cap', 'title' => 'أكاديمية الأصول', 'text' => 'تعليم احترافي للخياطة من الأساسيات وحتى إدارة المشاريع، بإشراف مدربين متخصصين.', 'link_text' => 'اعرف أكثر', 'link_url' => '/contact/' ),
					array( 'icon' => 'fa-solid fa-screwdriver-wrench', 'title' => 'دعم فني وضمان', 'text' => 'ضمان حقيقي بعد البيع وتوفر قطع غيار أصلية، مع فريق فني جاهز لمساعدتك دائمًا.', 'link_text' => 'تواصل معنا', 'link_url' => '/contact/' ),
				),
			),

			array( 'key' => 'field_ko_home_tab_featured', 'label' => 'قسم منتجات مميزة', 'type' => 'tab' ),
			array( 'key' => 'field_ko_featured_eyebrow', 'label' => 'الشارة العلوية', 'name' => 'featured_eyebrow', 'type' => 'text', 'default_value' => 'الأكثر طلبًا' ),
			array( 'key' => 'field_ko_featured_heading', 'label' => 'العنوان', 'name' => 'featured_heading', 'type' => 'text', 'default_value' => 'منتجات مميزة' ),
			array( 'key' => 'field_ko_featured_text', 'label' => 'النص التعريفي', 'name' => 'featured_text', 'type' => 'text', 'default_value' => 'مختارات من أفضل الأدوات والماكينات الأصلية، بضمان الجودة وسرعة التوصيل.' ),

			array( 'key' => 'field_ko_home_tab_steps', 'label' => 'قسم خطوات الطلب', 'type' => 'tab' ),
			array( 'key' => 'field_ko_steps_eyebrow', 'label' => 'الشارة العلوية', 'name' => 'steps_eyebrow', 'type' => 'text', 'default_value' => 'كيف تطلب؟' ),
			array( 'key' => 'field_ko_steps_heading', 'label' => 'العنوان', 'name' => 'steps_heading', 'type' => 'text', 'default_value' => 'رحلتك معنا في 4 خطوات بسيطة' ),
			array(
				'key' => 'field_ko_steps', 'label' => 'الخطوات', 'name' => 'steps', 'type' => 'repeater', 'layout' => 'table', 'min' => 1, 'max' => 6,
				'sub_fields' => array(
					array( 'key' => 'field_ko_step_title', 'label' => 'العنوان', 'name' => 'title', 'type' => 'text' ),
					array( 'key' => 'field_ko_step_text', 'label' => 'الوصف', 'name' => 'text', 'type' => 'text' ),
				),
				'default_value' => array(
					array( 'title' => 'تصفّح المنتجات', 'text' => 'اختر من مجموعة واسعة من الأدوات والماكينات الأصلية حسب احتياجك.' ),
					array( 'title' => 'أضف إلى السلة', 'text' => 'أضف منتجاتك المفضلة إلى السلة وراجع طلبك بسهولة تامة.' ),
					array( 'title' => 'أدخل بياناتك', 'text' => 'أكمل بيانات الشحن واختر الدفع عند الاستلام في خطوات سريعة.' ),
					array( 'title' => 'استلم طلبك', 'text' => 'يصلك طلبك مُغلّفًا بعناية خلال 2-4 أيام عمل إلى باب منزلك.' ),
				),
			),

			array( 'key' => 'field_ko_home_tab_cta', 'label' => 'قسم CTA الأكاديمية', 'type' => 'tab' ),
			array( 'key' => 'field_ko_cta_badge', 'label' => 'نص الشارة', 'name' => 'cta_badge_text', 'type' => 'text', 'default_value' => 'أكاديمية الأصول' ),
			array( 'key' => 'field_ko_cta_heading', 'label' => 'العنوان', 'name' => 'cta_heading', 'type' => 'text', 'default_value' => 'احترف الخياطة خطوة بخطوة' ),
			array( 'key' => 'field_ko_cta_text', 'label' => 'الوصف', 'name' => 'cta_text', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'من أول غرزة حتى إدارة مشروعك الخاص، برامج تدريبية متكاملة بإشراف خبراء لهم أكثر من 5 سنوات في المجال.' ),
			array( 'key' => 'field_ko_cta_btn_text', 'label' => 'نص الزر', 'name' => 'cta_button_text', 'type' => 'text', 'default_value' => 'اعرف تفاصيل الالتحاق' ),
			array( 'key' => 'field_ko_cta_btn_link', 'label' => 'رابط الزر', 'name' => 'cta_button_link', 'type' => 'text', 'default_value' => '/contact/' ),

			array( 'key' => 'field_ko_home_tab_testimonials', 'label' => 'قسم آراء العملاء', 'type' => 'tab' ),
			array( 'key' => 'field_ko_testi_eyebrow', 'label' => 'الشارة العلوية', 'name' => 'testimonials_eyebrow', 'type' => 'text', 'default_value' => 'آراء العملاء' ),
			array( 'key' => 'field_ko_testi_heading', 'label' => 'العنوان', 'name' => 'testimonials_heading', 'type' => 'text', 'default_value' => 'ثقة أكثر من 1200 عميل' ),
			array(
				'key' => 'field_ko_testimonials', 'label' => 'الآراء', 'name' => 'testimonials', 'type' => 'repeater', 'layout' => 'block', 'min' => 1, 'max' => 9,
				'sub_fields' => array(
					array( 'key' => 'field_ko_te_text', 'label' => 'نص الرأي', 'name' => 'text', 'type' => 'textarea', 'rows' => 3 ),
					array( 'key' => 'field_ko_te_image', 'label' => 'صورة العميل', 'name' => 'image', 'type' => 'image', 'return_format' => 'url' ),
					array( 'key' => 'field_ko_te_name', 'label' => 'الاسم', 'name' => 'name', 'type' => 'text' ),
					array( 'key' => 'field_ko_te_role', 'label' => 'الصفة', 'name' => 'role', 'type' => 'text' ),
				),
			),
		),
	) );

	/* =======================================================
	 * محتوى صفحة "من نحن"
	 * ======================================================= */
	acf_add_local_field_group( array(
		'key'      => 'group_ko_about',
		'title'    => 'محتوى صفحة من نحن',
		'location' => array( array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-about.php' ) ) ),
		'fields'   => array(

			array( 'key' => 'field_ko_about_tab_story', 'label' => 'القصة', 'type' => 'tab' ),
			array( 'key' => 'field_ko_story_eyebrow', 'label' => 'الشارة العلوية', 'name' => 'story_eyebrow', 'type' => 'text', 'default_value' => 'قصتنا' ),
			array( 'key' => 'field_ko_story_heading', 'label' => 'العنوان', 'name' => 'story_heading', 'type' => 'text', 'default_value' => 'للخياطة أصول.. وللأصول أهلها' ),
			array( 'key' => 'field_ko_story_text_1', 'label' => 'الفقرة الأولى', 'name' => 'story_text_1', 'type' => 'textarea', 'rows' => 3 ),
			array( 'key' => 'field_ko_story_text_2', 'label' => 'الفقرة الثانية', 'name' => 'story_text_2', 'type' => 'textarea', 'rows' => 3 ),
			array( 'key' => 'field_ko_story_image_1', 'label' => 'الصورة الأولى', 'name' => 'story_image_1', 'type' => 'image', 'return_format' => 'url' ),
			array( 'key' => 'field_ko_story_image_2', 'label' => 'الصورة الثانية', 'name' => 'story_image_2', 'type' => 'image', 'return_format' => 'url' ),

			array( 'key' => 'field_ko_about_tab_why', 'label' => 'لماذا تختارنا', 'type' => 'tab' ),
			array( 'key' => 'field_ko_why_eyebrow', 'label' => 'الشارة العلوية', 'name' => 'why_choose_eyebrow', 'type' => 'text', 'default_value' => 'لماذا تختارنا' ),
			array( 'key' => 'field_ko_why_heading', 'label' => 'العنوان', 'name' => 'why_choose_heading', 'type' => 'text', 'default_value' => 'خبرة حقيقية تصنع فرقًا' ),
			array( 'key' => 'field_ko_why_text', 'label' => 'النص التعريفي', 'name' => 'why_choose_text', 'type' => 'text', 'default_value' => 'نجمع بين جودة الأدوات وصدق التعامل، لنكون الوجهة الأولى لكل خيّاط ومصمم أزياء في مصر.' ),
			array(
				'key' => 'field_ko_why_items', 'label' => 'بطاقات "لماذا تختارنا"', 'name' => 'why_choose_items', 'type' => 'repeater', 'layout' => 'block', 'min' => 1, 'max' => 8,
				'sub_fields' => array(
					array( 'key' => 'field_ko_wi_icon', 'label' => 'أيقونة (Font Awesome class)', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'fa-solid fa-medal' ),
					array( 'key' => 'field_ko_wi_title', 'label' => 'العنوان', 'name' => 'title', 'type' => 'text' ),
					array( 'key' => 'field_ko_wi_text', 'label' => 'الوصف', 'name' => 'text', 'type' => 'textarea', 'rows' => 2 ),
				),
				'default_value' => array(
					array( 'icon' => 'fa-solid fa-medal', 'title' => 'أدوات أصلية ومتينة', 'text' => 'ماكينات وأدوات مضمونة المصدر تدوم طويلًا مع الاستخدام اليومي المكثف.' ),
					array( 'icon' => 'fa-solid fa-headset', 'title' => 'دعم فني حقيقي', 'text' => 'ضمان بعد البيع وتوفر قطع الغيار مع فريق دعم يرد على استفساراتك بسرعة.' ),
					array( 'icon' => 'fa-solid fa-box', 'title' => 'تغليف مدرّع', 'text' => 'تغليف مقاوم للصدمات يحافظ على منتجك من باب المصنع حتى باب بيتك.' ),
					array( 'icon' => 'fa-solid fa-truck-fast', 'title' => 'توصيل سريع وآمن', 'text' => 'شحن لكل محافظات مصر خلال 2-4 أيام عمل، مع الدفع عند الاستلام.' ),
				),
			),

			array( 'key' => 'field_ko_about_tab_pillars', 'label' => 'ركائزنا', 'type' => 'tab' ),
			array( 'key' => 'field_ko_pillars_eyebrow', 'label' => 'الشارة العلوية', 'name' => 'pillars_eyebrow', 'type' => 'text', 'default_value' => 'رسالتنا وركائزنا' ),
			array( 'key' => 'field_ko_pillars_heading', 'label' => 'العنوان', 'name' => 'pillars_heading', 'type' => 'text', 'default_value' => 'ثلاث ركائز تصنع الفرق' ),
			array(
				'key' => 'field_ko_pillars', 'label' => 'الركائز', 'name' => 'pillars', 'type' => 'repeater', 'layout' => 'block', 'min' => 1, 'max' => 6,
				'sub_fields' => array(
					array( 'key' => 'field_ko_pi_icon', 'label' => 'أيقونة', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'fa-solid fa-store' ),
					array( 'key' => 'field_ko_pi_title', 'label' => 'العنوان', 'name' => 'title', 'type' => 'text' ),
					array( 'key' => 'field_ko_pi_text', 'label' => 'الوصف', 'name' => 'text', 'type' => 'textarea', 'rows' => 2 ),
				),
				'default_value' => array(
					array( 'icon' => 'fa-solid fa-store', 'title' => 'متجر النوادر', 'text' => 'ماكينات وأدوات خياطة أصلية ونادرة يصعب إيجادها في مكان واحد، بأسعار منافسة وجودة موثوقة.' ),
					array( 'icon' => 'fa-solid fa-graduation-cap', 'title' => 'أكاديمية الأصول', 'text' => 'برامج تدريبية تبدأ من الأساسيات وتصل إلى إدارة المشاريع الاحترافية، بإشراف مدربين متخصصين.' ),
					array( 'icon' => 'fa-solid fa-screwdriver-wrench', 'title' => 'دعم فني وضمان', 'text' => 'ضمان حقيقي بعد البيع، وتوفر قطع الغيار، وفريق دعم فني يرد بسرعة وصدق على كل استفسار.' ),
				),
			),

			array( 'key' => 'field_ko_about_tab_stats', 'label' => 'الإحصائيات', 'type' => 'tab' ),
			array(
				'key' => 'field_ko_about_stats', 'label' => 'بطاقة الإحصائيات', 'name' => 'about_stats', 'type' => 'repeater', 'layout' => 'table', 'min' => 4, 'max' => 4,
				'sub_fields' => array(
					array( 'key' => 'field_ko_ast_icon', 'label' => 'أيقونة', 'name' => 'icon', 'type' => 'text' ),
					array( 'key' => 'field_ko_ast_number', 'label' => 'الرقم', 'name' => 'number', 'type' => 'number' ),
					array( 'key' => 'field_ko_ast_suffix', 'label' => 'إضافة', 'name' => 'suffix', 'type' => 'text' ),
					array( 'key' => 'field_ko_ast_label', 'label' => 'الوصف', 'name' => 'label', 'type' => 'text' ),
				),
				'default_value' => array(
					array( 'icon' => 'fa-solid fa-award', 'number' => 5, 'suffix' => '+', 'label' => 'سنوات خبرة' ),
					array( 'icon' => 'fa-solid fa-face-smile', 'number' => 1200, 'suffix' => '+', 'label' => 'عميل راضٍ' ),
					array( 'icon' => 'fa-solid fa-box-open', 'number' => 15, 'suffix' => '+', 'label' => 'منتج أصلي' ),
					array( 'icon' => 'fa-solid fa-truck-fast', 'number' => 27, 'suffix' => '', 'label' => 'محافظة توصيل' ),
				),
			),

			array( 'key' => 'field_ko_about_tab_quote', 'label' => 'الاقتباس والختام', 'type' => 'tab' ),
			array( 'key' => 'field_ko_quote_text', 'label' => 'نص الاقتباس', 'name' => 'quote_heading', 'type' => 'text', 'default_value' => '"للخياطة أصول.. وللأصول أهلها"' ),
			array( 'key' => 'field_ko_quote_sub', 'label' => 'النص الفرعي', 'name' => 'quote_text', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'هذه ليست مجرد شعار، بل فلسفة عمل: كل غرزة لها أصول، وكل أداة لها معيار، وكل عميل يستحق تعاملًا صادقًا يليق بحرفة الخياطة العريقة.' ),
			array( 'key' => 'field_ko_final_cta_heading', 'label' => 'عنوان CTA الختامي', 'name' => 'final_cta_heading', 'type' => 'text', 'default_value' => 'ابدأ رحلتك معنا اليوم' ),
			array( 'key' => 'field_ko_final_cta_text', 'label' => 'نص CTA الختامي', 'name' => 'final_cta_text', 'type' => 'text', 'default_value' => 'تصفّح متجرنا أو تواصل معنا لمعرفة تفاصيل الالتحاق بأكاديمية الأصول.' ),
		),
	) );
	/* =======================================================
	 * محتوى صفحة "تواصل معنا" (الأسئلة الشائعة + نبذة بطاقة التواصل)
	 * ======================================================= */
	acf_add_local_field_group( array(
		'key'      => 'group_ko_contact',
		'title'    => 'محتوى صفحة تواصل معنا',
		'location' => array( array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-contact.php' ) ) ),
		'fields'   => array(
			array( 'key' => 'field_ko_contact_intro', 'label' => 'نبذة بطاقة بيانات التواصل', 'name' => 'contact_intro', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'فريقنا جاهز للرد على استفساراتك بخصوص المنتجات أو الطلبات أو الالتحاق بأكاديمية الأصول.' ),
			array( 'key' => 'field_ko_faq_eyebrow', 'label' => 'شارة قسم الأسئلة الشائعة', 'name' => 'faq_eyebrow', 'type' => 'text', 'default_value' => 'الأسئلة الشائعة' ),
			array( 'key' => 'field_ko_faq_heading', 'label' => 'عنوان قسم الأسئلة الشائعة', 'name' => 'faq_heading', 'type' => 'text', 'default_value' => 'عندك سؤال؟ يمكن نكون جاوبنا عليه' ),
			array(
				'key' => 'field_ko_faq_items', 'label' => 'الأسئلة والأجوبة', 'name' => 'faq_items', 'type' => 'repeater', 'layout' => 'block', 'min' => 1, 'max' => 12,
				'sub_fields' => array(
					array( 'key' => 'field_ko_faq_q', 'label' => 'السؤال', 'name' => 'question', 'type' => 'text' ),
					array( 'key' => 'field_ko_faq_a', 'label' => 'الجواب', 'name' => 'answer', 'type' => 'textarea', 'rows' => 2 ),
				),
				'default_value' => array(
					array( 'question' => 'كم تستغرق مدة التوصيل؟', 'answer' => 'يصل طلبك خلال 2-4 أيام عمل لجميع محافظات مصر، مع تغليف مقاوم للصدمات للحفاظ على المنتج.' ),
					array( 'question' => 'هل يوجد ضمان على الماكينات؟', 'answer' => 'نعم، جميع منتجاتنا الأصلية مضمونة بعد البيع مع توفر قطع الغيار ودعم فني حقيقي عند الحاجة.' ),
					array( 'question' => 'هل يمكن الدفع عند الاستلام؟', 'answer' => 'بالتأكيد، نوفر خاصية الدفع عند الاستلام نقدًا لجميع الطلبات في جميع محافظات مصر.' ),
					array( 'question' => 'كيف ألتحق بأكاديمية الأصول؟', 'answer' => 'تواصل معنا عبر الهاتف أو واتساب أو نموذج التواصل، وسيقوم فريقنا بشرح البرامج التدريبية المتاحة ومواعيدها.' ),
				),
			),
		),
	) );

	/* =======================================================
	 * أيقونة تصنيف المنتج (تظهر في كاروسيل "تسوّق حسب احتياجك")
	 * ======================================================= */
	acf_add_local_field_group( array(
		'key'      => 'group_ko_category_icon',
		'title'    => 'أيقونة التصنيف',
		'location' => array( array( array( 'param' => 'taxonomy', 'operator' => '==', 'value' => 'product_cat' ) ) ),
		'fields'   => array(
			array(
				'key' => 'field_ko_category_icon', 'label' => 'أيقونة Font Awesome', 'name' => 'ko_category_icon', 'type' => 'text',
				'placeholder' => 'fa-solid fa-scissors',
				'instructions' => 'اسم كلاس الأيقونة من Font Awesome، مثال: fa-solid fa-gear',
			),
		),
	) );
} );

/**
 * صفحة القالب "من نحن" — تُسجَّل هنا حتى تظهر في قائمة "قالب الصفحة" بمحرر ووردبريس
 * ويُستخدمها SCF كشرط ظهور (location rule) أعلاه.
 */
add_filter( 'theme_page_templates', function ( $templates ) {
	$templates['page-about.php']   = 'صفحة من نحن';
	$templates['page-contact.php'] = 'صفحة تواصل معنا';
	return $templates;
} );
