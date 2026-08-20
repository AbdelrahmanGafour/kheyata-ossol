<?php
/**
 * مثبّت الإضافات المطلوبة: صفحة إدارية تسمح بتثبيت وتفعيل الإضافات الأربع المطلوبة
 * للقالب (WooCommerce, Contact Form 7, Yoast SEO, Secure Custom Fields) بضغطة واحدة
 * لكل إضافة، عبر مُثبِّت ووردبريس الأصلي (لا مكتبة خارجية/لا TGMPA) — بنفس الفكرة التي
 * تستخدمها القوالب الاحترافية (مثل Woodmart) لطلب الإضافات المطلوبة عند تفعيل القالب.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function ko_required_plugins_list() {
	return array(
		'woocommerce'          => array( 'name' => 'WooCommerce', 'file' => 'woocommerce/woocommerce.php' ),
		'contact-form-7'       => array( 'name' => 'Contact Form 7', 'file' => 'contact-form-7/wp-contact-form-7.php' ),
		'wordpress-seo'        => array( 'name' => 'Yoast SEO', 'file' => 'wordpress-seo/wp-seo.php' ),
		'secure-custom-fields' => array( 'name' => 'Secure Custom Fields', 'file' => 'secure-custom-fields/secure-custom-fields.php' ),
	);
}

/** الحالة الحالية لكل إضافة مطلوبة: not_installed / inactive / active. */
function ko_get_plugin_status( $slug, $file ) {
	if ( ! function_exists( 'get_plugins' ) ) require_once ABSPATH . 'wp-admin/includes/plugin.php';
	$all = get_plugins();
	if ( ! isset( $all[ $file ] ) ) return 'not_installed';
	return is_plugin_active( $file ) ? 'active' : 'inactive';
}

function ko_missing_required_plugins() {
	$missing = array();
	foreach ( ko_required_plugins_list() as $slug => $data ) {
		if ( ko_get_plugin_status( $slug, $data['file'] ) !== 'active' ) $missing[ $slug ] = $data;
	}
	return $missing;
}

/* -------- صفحة إدارية مخصصة: خيارات القالب ← الإضافات المطلوبة -------- */
add_action( 'admin_menu', function () {
	add_submenu_page(
		KO_Theme_Options::PAGE_SLUG,
		__( 'الإضافات المطلوبة', 'kheyata-ossol' ),
		__( 'الإضافات المطلوبة', 'kheyata-ossol' ),
		'install_plugins',
		'ko-required-plugins',
		'ko_render_required_plugins_page'
	);
} );

function ko_render_required_plugins_page() {
	if ( ! current_user_can( 'install_plugins' ) ) return;
	?>
	<div class="wrap ko-options-wrap">
		<div class="ko-options-header">
			<h1><span class="dashicons dashicons-admin-plugins"></span> <?php esc_html_e( 'الإضافات المطلوبة لقالب للخياطة أصول', 'kheyata-ossol' ); ?></h1>
			<p><?php esc_html_e( 'هذه الإضافات الأربع ضرورية لعمل المتجر وكل ميزاته (المنتجات، النماذج، السيو، والمحتوى الديناميكي). ثبّت وفعّل كلاً منها بضغطة واحدة.', 'kheyata-ossol' ); ?></p>
		</div>
		<table class="widefat striped" style="max-width:900px;">
			<thead><tr><th><?php esc_html_e( 'الإضافة', 'kheyata-ossol' ); ?></th><th><?php esc_html_e( 'الحالة', 'kheyata-ossol' ); ?></th><th><?php esc_html_e( 'إجراء', 'kheyata-ossol' ); ?></th></tr></thead>
			<tbody>
			<?php foreach ( ko_required_plugins_list() as $slug => $data ) :
				$status = ko_get_plugin_status( $slug, $data['file'] ); ?>
				<tr>
					<td><strong><?php echo esc_html( $data['name'] ); ?></strong></td>
					<td>
						<?php if ( $status === 'active' ) : ?>
							<span style="color:#2e7d32;">✔ <?php esc_html_e( 'مفعّلة', 'kheyata-ossol' ); ?></span>
						<?php elseif ( $status === 'inactive' ) : ?>
							<span style="color:#b26a00;">● <?php esc_html_e( 'مثبّتة وغير مفعّلة', 'kheyata-ossol' ); ?></span>
						<?php else : ?>
							<span style="color:#c0392b;">○ <?php esc_html_e( 'غير مثبّتة', 'kheyata-ossol' ); ?></span>
						<?php endif; ?>
					</td>
					<td>
						<?php if ( $status === 'active' ) : ?>
							—
						<?php elseif ( $status === 'inactive' ) : ?>
							<a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . $data['file'] ), 'activate-plugin_' . $data['file'] ) ); ?>"><?php esc_html_e( 'تفعيل', 'kheyata-ossol' ); ?></a>
						<?php else : ?>
							<a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $slug ), 'install-plugin_' . $slug ) ); ?>"><?php esc_html_e( 'تثبيت الآن', 'kheyata-ossol' ); ?></a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php if ( empty( ko_missing_required_plugins() ) ) : ?>
			<p class="mt-3" style="margin-top:16px;">
				<span class="dashicons dashicons-yes-alt" style="color:#2e7d32;"></span>
				<?php esc_html_e( 'كل الإضافات المطلوبة مفعّلة. يمكنك الآن استيراد المحتوى التجريبي من: خيارات القالب ← استيراد محتوى تجريبي.', 'kheyata-ossol' ); ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=ko-demo-import' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'استيراد المحتوى التجريبي', 'kheyata-ossol' ); ?></a>
			</p>
		<?php endif; ?>
	</div>
	<?php
}

/* -------- تنبيه مختصر أعلى لوحة التحكم إن كانت إضافات مطلوبة لا تزال ناقصة -------- */
add_action( 'admin_notices', function () {
	$missing = ko_missing_required_plugins();
	if ( empty( $missing ) ) return;
	if ( ! current_user_can( 'install_plugins' ) ) return;
	$screen = get_current_screen();
	if ( $screen && strpos( $screen->id, 'ko-required-plugins' ) !== false ) return; // لا تكرر التنبيه داخل نفس صفحة التثبيت.
	?>
	<div class="notice notice-warning">
		<p>
			<strong><?php esc_html_e( 'قالب للخياطة أصول:', 'kheyata-ossol' ); ?></strong>
			<?php printf(
				/* translators: %s: comma separated plugin names */
				esc_html__( 'إضافات مطلوبة غير مفعّلة بعد: %s.', 'kheyata-ossol' ),
				'<strong>' . esc_html( implode( '، ', wp_list_pluck( $missing, 'name' ) ) ) . '</strong>'
			); ?>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=ko-required-plugins' ) ); ?>"><?php esc_html_e( 'تثبيت الآن', 'kheyata-ossol' ); ?></a>
		</p>
	</div>
	<?php
} );
