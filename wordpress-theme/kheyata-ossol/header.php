<!DOCTYPE html>
<html lang="ar" dir="rtl" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$logo = ko_opt( 'site_logo', '' );
if ( ! $logo && has_custom_logo() ) {
	$logo_id  = get_theme_mod( 'custom_logo' );
	$logo_src = wp_get_attachment_image_src( $logo_id, 'full' );
	$logo     = $logo_src ? $logo_src[0] : '';
}
if ( ! $logo ) $logo = KO_THEME_URI . '/assets/images/logo.webp';

$cart_count  = class_exists( 'WooCommerce' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$account_url = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
$cart_url    = class_exists( 'WooCommerce' ) ? wc_get_cart_url() : home_url( '/' );
?>

<header class="site-header">
	<div class="container header-inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand">
			<img src="<?php echo esc_url( $logo ); ?>" alt="<?php bloginfo( 'name' ); ?>">
			<span class="brand-text">
				<strong><?php bloginfo( 'name' ); ?></strong>
				<span><?php bloginfo( 'description' ); ?></span>
			</span>
		</a>

		<nav class="main-nav">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'items_wrap'     => '<ul>%3$s</ul>',
				'fallback_cb'    => 'ko_default_primary_menu',
			) );
			?>
		</nav>

		<div class="header-actions">
			<?php if ( ko_opt( 'header_cta_text', '' ) ) : ?>
				<a href="<?php echo esc_url( ko_opt( 'header_cta_link', '#' ) ); ?>" class="btn btn-accent btn-sm ko-header-cta"><?php echo esc_html( ko_opt( 'header_cta_text', '' ) ); ?></a>
			<?php endif; ?>

			<?php if ( ko_opt( 'show_account_icon', '1' ) === '1' ) : ?>
				<a href="<?php echo esc_url( $account_url ); ?>" class="icon-btn" data-auth-account-link title="<?php echo is_user_logged_in() ? esc_attr__( 'حسابي', 'kheyata-ossol' ) : esc_attr__( 'تسجيل الدخول', 'kheyata-ossol' ); ?>">
					<i class="fa-solid fa-user"></i>
				</a>
			<?php endif; ?>

			<?php if ( ko_opt( 'show_cart_icon', '1' ) === '1' ) : ?>
				<a href="<?php echo esc_url( $cart_url ); ?>" class="icon-btn" id="mini-cart-toggle" data-mini-cart="<?php echo ko_opt( 'mini_cart_drawer', '1' ) === '1' ? '1' : '0'; ?>">
					<i class="fa-solid fa-cart-shopping"></i>
					<span class="cart-badge" style="<?php echo $cart_count > 0 ? '' : 'display:none;'; ?>"><?php echo esc_html( $cart_count ); ?></span>
				</a>
			<?php endif; ?>

			<button class="nav-toggle" aria-label="القائمة"><i class="fa-solid fa-bars"></i></button>
		</div>
	</div>

	<div class="mobile-nav">
		<ul>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'mobile',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'fallback_cb'    => 'ko_default_mobile_menu',
			) );
			?>
			<li><a href="<?php echo esc_url( $account_url ); ?>" data-auth-account-link><?php esc_html_e( 'حسابي', 'kheyata-ossol' ); ?> <i class="fa-solid fa-user"></i></a></li>
		</ul>
	</div>
</header>
