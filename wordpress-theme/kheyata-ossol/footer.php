<?php
/**
 * الفوتر الموحّد. النوع (كامل/مبسّط) يُقرأ افتراضيًا من تبويب "الفوتر" بخيارات القالب،
 * ويمكن تجاوزه لكل صفحة عبر: $ko_footer_variant = 'minimal'; قبل استدعاء get_footer().
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$variant = isset( $ko_footer_variant ) ? $ko_footer_variant : ko_opt( 'footer_columns', 'full' );

$logo = ko_opt( 'site_logo', '' );
if ( ! $logo ) $logo = KO_THEME_URI . '/assets/images/logo.webp';

$copyright = str_replace(
	array( '[year]', '[site_name]' ),
	array( date( 'Y' ), get_bloginfo( 'name' ) ),
	ko_opt( 'footer_copyright', '© [year] [site_name]. جميع الحقوق محفوظة.' )
);
?>

<footer class="site-footer">
	<?php if ( $variant === 'full' ) : ?>
		<div class="container">
			<div class="footer-top">
				<div class="footer-col">
					<div class="footer-brand">
						<img src="<?php echo esc_url( $logo ); ?>" alt="<?php bloginfo( 'name' ); ?>">
						<strong><?php bloginfo( 'name' ); ?></strong>
					</div>
					<p><?php echo esc_html( ko_opt( 'footer_about_text', '' ) ); ?></p>
					<div class="social-row">
						<?php if ( ko_opt( 'social_facebook' ) ) : ?><a href="<?php echo esc_url( ko_opt( 'social_facebook' ) ); ?>" target="_blank" rel="noopener" aria-label="فيسبوك"><i class="fa-brands fa-facebook-f"></i></a><?php endif; ?>
						<?php if ( ko_opt( 'social_instagram' ) ) : ?><a href="<?php echo esc_url( ko_opt( 'social_instagram' ) ); ?>" target="_blank" rel="noopener" aria-label="إنستجرام"><i class="fa-brands fa-instagram"></i></a><?php endif; ?>
						<?php if ( ko_opt( 'social_tiktok' ) ) : ?><a href="<?php echo esc_url( ko_opt( 'social_tiktok' ) ); ?>" target="_blank" rel="noopener" aria-label="تيك توك"><i class="fa-brands fa-tiktok"></i></a><?php endif; ?>
						<?php if ( ko_opt( 'contact_whatsapp' ) ) : ?><a href="<?php echo ko_whatsapp_link(); ?>" target="_blank" rel="noopener" aria-label="واتساب"><i class="fa-brands fa-whatsapp"></i></a><?php endif; ?>
					</div>
				</div>

				<?php if ( is_active_sidebar( 'footer-col-2' ) ) : dynamic_sidebar( 'footer-col-2' ); else : ?>
					<div class="footer-col">
						<h4><?php esc_html_e( 'روابط سريعة', 'kheyata-ossol' ); ?></h4>
						<ul>
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a></li>
							<li><a href="<?php echo class_exists( 'WooCommerce' ) ? esc_url( wc_get_page_permalink( 'shop' ) ) : '#'; ?>">المتجر</a></li>
							<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">من نحن</a></li>
							<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">تواصل معنا</a></li>
						</ul>
					</div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'footer-col-3' ) ) : dynamic_sidebar( 'footer-col-3' ); else : ?>
					<div class="footer-col">
						<h4><?php esc_html_e( 'روابط مساعدة', 'kheyata-ossol' ); ?></h4>
						<ul>
							<?php if ( class_exists( 'WooCommerce' ) ) : ?>
								<li><a href="<?php echo esc_url( wc_get_cart_url() ); ?>">سلة المشتريات</a></li>
								<li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">حسابي</a></li>
							<?php endif; ?>
							<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">الأسئلة الشائعة</a></li>
						</ul>
					</div>
				<?php endif; ?>

				<div class="footer-col">
					<h4><?php esc_html_e( 'تواصل معنا', 'kheyata-ossol' ); ?></h4>
					<ul class="footer-contact">
						<?php if ( ko_opt( 'contact_phone' ) ) : ?><li><i class="fa-solid fa-phone"></i><a href="tel:<?php echo esc_attr( ko_opt( 'contact_phone' ) ); ?>" dir="ltr"><?php echo esc_html( ko_opt( 'contact_phone' ) ); ?></a></li><?php endif; ?>
						<?php if ( ko_opt( 'contact_whatsapp' ) ) : ?><li><i class="fa-brands fa-whatsapp"></i><a href="<?php echo ko_whatsapp_link(); ?>" target="_blank" rel="noopener" dir="ltr"><?php echo esc_html( ko_opt( 'contact_whatsapp' ) ); ?></a></li><?php endif; ?>
						<?php if ( ko_opt( 'contact_email' ) ) : ?><li><i class="fa-solid fa-envelope"></i><a href="mailto:<?php echo esc_attr( ko_opt( 'contact_email' ) ); ?>"><?php echo esc_html( ko_opt( 'contact_email' ) ); ?></a></li><?php endif; ?>
						<?php if ( ko_opt( 'contact_service_area' ) ) : ?><li><i class="fa-solid fa-location-dot"></i><span><?php echo esc_html( ko_opt( 'contact_service_area' ) ); ?></span></li><?php endif; ?>
					</ul>
				</div>
			</div>

			<div class="footer-bottom">
				<span><?php echo wp_kses_post( $copyright ); ?></span>
				<span><?php esc_html_e( 'صُنع بعناية لعشاق الخياطة', 'kheyata-ossol' ); ?></span>
			</div>
		</div>

		<?php if ( ko_opt( 'whatsapp_float_button', '1' ) === '1' && ko_opt( 'contact_whatsapp' ) ) : ?>
			<a href="<?php echo ko_whatsapp_link(); ?>" class="whatsapp-float" target="_blank" rel="noopener" aria-label="تواصل عبر واتساب"><i class="fa-brands fa-whatsapp"></i></a>
		<?php endif; ?>

	<?php else : /* footer مبسّط */ ?>
		<div class="container">
			<div class="footer-bottom" style="border-top:none; padding-top:0;">
				<span><?php echo wp_kses_post( $copyright ); ?></span>
			</div>
		</div>
	<?php endif; ?>
</footer>

<?php if ( class_exists( 'WooCommerce' ) && ko_opt( 'mini_cart_drawer', '1' ) === '1' ) : ?>
<div class="mini-cart-backdrop" id="mini-cart-backdrop"></div>
<aside class="mini-cart-drawer" id="mini-cart-drawer" aria-label="<?php esc_attr_e( 'سلة المشتريات', 'kheyata-ossol' ); ?>">
	<div class="mini-cart-head">
		<h4><i class="fa-solid fa-cart-shopping"></i> <?php esc_html_e( 'سلة المشتريات', 'kheyata-ossol' ); ?></h4>
		<button type="button" class="mini-cart-close" id="mini-cart-close" aria-label="<?php esc_attr_e( 'إغلاق', 'kheyata-ossol' ); ?>"><i class="fa-solid fa-xmark"></i></button>
	</div>
	<div class="mini-cart-body" id="mini-cart-body"><?php echo ko_mini_cart_content_html(); // phpcs:ignore ?></div>
</aside>
<?php endif; ?>

<?php if ( class_exists( 'WooCommerce' ) && ko_opt( 'show_quick_view', '1' ) === '1' ) : ?>
<div class="quick-view-backdrop" id="quick-view-backdrop"></div>
<div class="quick-view-modal" id="quick-view-modal" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'معاينة سريعة للمنتج', 'kheyata-ossol' ); ?>">
	<button type="button" class="quick-view-close" id="quick-view-close" aria-label="<?php esc_attr_e( 'إغلاق', 'kheyata-ossol' ); ?>"><i class="fa-solid fa-xmark"></i></button>
	<div class="quick-view-body" id="quick-view-body"></div>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
