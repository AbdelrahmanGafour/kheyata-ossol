<?php
/**
 * Template Name: صفحة تواصل معنا
 * بيانات التواصل تُقرأ من خيارات القالب (تبويب "التواصل")، والنموذج من Contact Form 7،
 * والأسئلة الشائعة من حقول Secure Custom Fields.
 */
get_header();
$pid = get_the_ID();
?>

<main>

	<div class="page-header">
		<div class="container">
			<h1><?php the_title(); ?></h1>
			<?php ko_render_breadcrumb(); ?>
		</div>
	</div>

	<section>
		<div class="container contact-layout">
			<div class="contact-info-card" data-reveal>
				<h3>بيانات التواصل</h3>
				<p class="mb-3"><?php echo esc_html( ko_field( 'contact_intro', $pid, '' ) ); ?></p>

				<?php if ( ko_opt( 'contact_phone' ) ) : ?>
				<div class="contact-info-item">
					<i class="fa-solid fa-phone"></i>
					<div><strong>اتصل بنا</strong><a class="value" href="tel:<?php echo esc_attr( ko_opt( 'contact_phone' ) ); ?>"><?php echo esc_html( ko_opt( 'contact_phone' ) ); ?></a></div>
				</div>
				<?php endif; ?>

				<?php if ( ko_opt( 'contact_whatsapp' ) ) : ?>
				<div class="contact-info-item">
					<i class="fa-brands fa-whatsapp"></i>
					<div><strong>واتساب</strong><a class="value" href="<?php echo ko_whatsapp_link(); ?>" target="_blank" rel="noopener"><?php echo esc_html( ko_opt( 'contact_whatsapp' ) ); ?></a></div>
				</div>
				<?php endif; ?>

				<?php if ( ko_opt( 'contact_email' ) ) : ?>
				<div class="contact-info-item">
					<i class="fa-solid fa-envelope"></i>
					<div><strong>البريد الإلكتروني</strong><a class="value" href="mailto:<?php echo esc_attr( ko_opt( 'contact_email' ) ); ?>"><?php echo esc_html( ko_opt( 'contact_email' ) ); ?></a></div>
				</div>
				<?php endif; ?>

				<?php if ( ko_opt( 'contact_service_area' ) ) : ?>
				<div class="contact-info-item" style="border-bottom:none;">
					<i class="fa-solid fa-location-dot"></i>
					<div><strong>نطاق الخدمة</strong><span class="value" style="direction:rtl;"><?php echo esc_html( ko_opt( 'contact_service_area' ) ); ?></span></div>
				</div>
				<?php endif; ?>

				<div class="social-row mt-3">
					<?php if ( ko_opt( 'social_facebook' ) ) : ?><a href="<?php echo esc_url( ko_opt( 'social_facebook' ) ); ?>" target="_blank" rel="noopener" aria-label="فيسبوك"><i class="fa-brands fa-facebook-f"></i></a><?php endif; ?>
					<?php if ( ko_opt( 'social_instagram' ) ) : ?><a href="<?php echo esc_url( ko_opt( 'social_instagram' ) ); ?>" target="_blank" rel="noopener" aria-label="إنستجرام"><i class="fa-brands fa-instagram"></i></a><?php endif; ?>
					<?php if ( ko_opt( 'social_tiktok' ) ) : ?><a href="<?php echo esc_url( ko_opt( 'social_tiktok' ) ); ?>" target="_blank" rel="noopener" aria-label="تيك توك"><i class="fa-brands fa-tiktok"></i></a><?php endif; ?>
					<?php if ( ko_opt( 'contact_whatsapp' ) ) : ?><a href="<?php echo ko_whatsapp_link(); ?>" target="_blank" rel="noopener" aria-label="واتساب"><i class="fa-brands fa-whatsapp"></i></a><?php endif; ?>
				</div>
			</div>

			<div class="form-card" data-reveal>
				<h3 class="mb-3">أرسل لنا رسالة</h3>
				<?php ko_render_contact_form(); ?>
			</div>
		</div>
	</section>

	<?php $faqs = ko_field( 'faq_items', $pid, array() ); if ( $faqs ) : ?>
	<section class="section-alt">
		<div class="container" style="max-width: 820px;">
			<div class="section-head" data-reveal>
				<span class="section-eyebrow"><?php echo esc_html( ko_field( 'faq_eyebrow', $pid, 'الأسئلة الشائعة' ) ); ?></span>
				<h2><?php echo esc_html( ko_field( 'faq_heading', $pid, 'عندك سؤال؟ يمكن نكون جاوبنا عليه' ) ); ?></h2>
			</div>
			<div id="faq-list">
				<?php foreach ( $faqs as $faq ) : ?>
					<div class="faq-item" data-reveal>
						<div class="faq-question"><?php echo esc_html( $faq['question'] ); ?> <i class="fa-solid fa-chevron-down"></i></div>
						<div class="faq-answer"><p><?php echo esc_html( $faq['answer'] ); ?></p></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

</main>

<style>
	.contact-layout { display: grid; grid-template-columns: 1fr; gap: 32px; }
	@media (min-width: 900px) { .contact-layout { grid-template-columns: 1fr 1fr; } }
	.contact-info-card { background: var(--color-primary); color: #fff; border-radius: var(--radius-lg); padding: 36px; height: 100%; }
	.contact-info-card h3 { color: #fff; }
	.contact-info-card p { color: rgba(255,255,255,.8); }
	.contact-info-item { display: flex; align-items: center; gap: 16px; padding: 16px 0; border-bottom: 1px solid rgba(255,255,255,.15); }
	.contact-info-item i { width: 46px; height: 46px; border-radius: 50%; background: rgba(255,255,255,.12); display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--color-accent-light); flex-shrink: 0; }
	.contact-info-item strong { display: block; font-size: 14px; }
	.contact-info-item .value { color: rgba(255,255,255,.85); font-size: 14px; direction: ltr; display: inline-block; overflow-wrap: anywhere; }
	.contact-info-item a.value:hover { color: var(--color-accent-light); }
	.form-card { background: var(--color-white); border-radius: var(--radius-lg); box-shadow: var(--shadow-soft); padding: 36px; }
	@media (max-width: 340px) { .contact-info-card, .form-card { padding: 24px 20px; } }
	.faq-item { background: var(--color-white); border-radius: var(--radius-md); box-shadow: var(--shadow-soft); margin-bottom: 14px; overflow: hidden; }
	.faq-question { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; cursor: pointer; font-family: var(--font-heading); font-weight: 600; }
	.faq-question i { transition: transform .3s ease; color: var(--color-accent-dark); }
	.faq-item.is-open .faq-question i { transform: rotate(180deg); }
	.faq-answer { max-height: 0; overflow: hidden; transition: max-height .3s ease; }
	.faq-answer p { padding: 0 24px 20px; }
</style>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.faq-question').forEach(function (q) {
			q.addEventListener('click', function () {
				var item = q.closest('.faq-item');
				var answer = item.querySelector('.faq-answer');
				var isOpen = item.classList.contains('is-open');
				document.querySelectorAll('.faq-item.is-open').forEach(function (open) {
					open.classList.remove('is-open');
					open.querySelector('.faq-answer').style.maxHeight = null;
				});
				if (!isOpen) {
					item.classList.add('is-open');
					answer.style.maxHeight = answer.scrollHeight + 'px';
				}
			});
		});
	});
</script>

<?php get_footer(); ?>
