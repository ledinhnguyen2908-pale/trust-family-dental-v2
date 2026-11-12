<?php defined('ABSPATH') || exit; get_header(); ?>
<main class="tfd-home">
	<section class="tfd-hero">
		<div class="tfd-shell tfd-hero__grid">
			<div class="tfd-hero__copy">
				<p class="tfd-kicker"><span></span><?php echo esc_html(tfd_copy('hero_eyebrow')); ?></p>
				<h1><?php echo wp_kses_post(tfd_copy('hero_title')); ?></h1>
				<p class="tfd-hero__intro"><?php echo esc_html(tfd_copy('hero_body')); ?></p>
				<div class="tfd-hero__buttons"><a class="tfd-btn" href="<?php echo esc_url(tfd_page_url('appointment')); ?>"><?php echo esc_html(tfd_copy('book')); ?><?php echo tfd_icon('arrow'); ?></a><a class="tfd-text-link" href="<?php echo esc_url(tfd_page_url('locations')); ?>"><?php echo esc_html(tfd_copy('find_location')); ?><?php echo tfd_icon('pin'); ?></a></div>
				<div class="tfd-hero__note"><span class="tfd-mini-mark"><?php echo tfd_icon('heart'); ?></span><span><?php echo esc_html(tfd_copy('hero_note')); ?></span></div>
			</div>
			<div class="tfd-hero__visual">
				<div class="tfd-hero__arch"></div><div class="tfd-hero__orbit"></div><span class="tfd-hero__spark" aria-hidden="true">✳</span>
				<img src="<?php echo esc_url(tfd_asset('images/dental-care.webp')); ?>" width="693" height="1000" alt="<?php echo esc_attr(tfd_copy('care_photo')); ?>" fetchpriority="high" loading="eager">
				<div class="tfd-hero__chip"><?php echo tfd_icon('heart'); ?><span><?php echo wp_kses_post(tfd_copy('hero_chip')); ?></span></div>
				<span class="tfd-hero__vertical" aria-hidden="true"><?php echo esc_html(tfd_copy('visual_tagline')); ?></span>
			</div>
		</div>
	</section>
	<div class="tfd-values"><div class="tfd-shell"><?php foreach(array('one'=>'heart','two'=>'check','three'=>'pin','four'=>'calendar') as $key=>$icon): ?><div><?php echo tfd_icon($icon); ?><span><?php echo esc_html(tfd_copy('feature_'.$key)); ?></span></div><?php endforeach; ?></div></div>
	<section class="tfd-section tfd-services-section"><div class="tfd-shell">
		<div class="tfd-section-heading"><div><p class="tfd-kicker"><?php echo esc_html(tfd_copy('service_eyebrow')); ?></p><h2><?php echo wp_kses_post(tfd_copy('service_title')); ?></h2></div><div><p><?php echo esc_html(tfd_copy('service_intro')); ?></p><a class="tfd-text-link" href="<?php echo esc_url(tfd_page_url('services')); ?>"><?php echo esc_html(tfd_copy('all_services')); ?><?php echo tfd_icon('arrow'); ?></a></div></div>
		<div class="tfd-services-grid"><?php tfd_service_cards(); ?></div>
	</div></section>
	<section class="tfd-section tfd-about-section"><div class="tfd-shell tfd-about-grid">
		<div class="tfd-about-image"><span class="tfd-about-image__circle"></span><img src="<?php echo esc_url(tfd_asset('images/gentle-care.webp')); ?>" width="722" height="1000" loading="lazy" alt="<?php echo esc_attr(tfd_copy('care_photo')); ?>"><span class="tfd-image-caption"><?php echo esc_html(tfd_copy('photo_caption')); ?></span></div>
		<div class="tfd-about-copy"><p class="tfd-kicker"><?php echo esc_html(tfd_copy('about_eyebrow')); ?></p><h2><?php echo wp_kses_post(tfd_copy('about_title')); ?></h2><p><?php echo esc_html(tfd_copy('about_body')); ?></p><ul class="tfd-check-list"><?php foreach(array('one','two','three') as $key): ?><li><?php echo tfd_icon('check'); ?><?php echo esc_html(tfd_copy('about_point_'.$key)); ?></li><?php endforeach; ?></ul><a class="tfd-btn tfd-btn--outline" href="<?php echo esc_url(tfd_page_url('about')); ?>"><?php echo esc_html(tfd_copy('about_link')); ?><?php echo tfd_icon('arrow'); ?></a></div>
	</div></section>
	<section class="tfd-section tfd-locations-section"><div class="tfd-shell">
		<div class="tfd-section-heading"><div><p class="tfd-kicker"><?php echo esc_html(tfd_copy('locations_eyebrow')); ?></p><h2><?php echo wp_kses_post(tfd_copy('locations_title')); ?></h2></div><p><?php echo esc_html(tfd_copy('locations_intro')); ?></p></div>
		<div class="tfd-offices-grid"><?php tfd_location_cards(); ?></div>
	</div></section>
	<section class="tfd-section tfd-journey"><div class="tfd-shell"><p class="tfd-kicker"><?php echo esc_html(tfd_copy('journey_eyebrow')); ?></p><h2><?php echo esc_html(tfd_copy('journey_title')); ?></h2><div class="tfd-steps"><?php foreach(array('one','two','three') as $i=>$key): ?><article><span>0<?php echo (int)$i+1; ?></span><h3><?php echo esc_html(tfd_copy('step_'.$key)); ?></h3><p><?php echo esc_html(tfd_copy('step_'.$key.'_body')); ?></p></article><?php endforeach; ?></div></div></section>
	<section class="tfd-section tfd-faq"><div class="tfd-shell tfd-faq-grid"><div><p class="tfd-kicker">FAQ</p><h2><?php echo esc_html(tfd_copy('faq_title')); ?></h2><a class="tfd-text-link" href="<?php echo esc_url(tfd_page_url('contact')); ?>"><?php echo esc_html(tfd_copy('nav_contact')); ?><?php echo tfd_icon('arrow'); ?></a></div><div><?php foreach(array('one','two','three') as $key): ?><details><summary><?php echo esc_html(tfd_copy('faq_'.$key)); ?><span aria-hidden="true">+</span></summary><p><?php echo esc_html(tfd_copy('faq_'.$key.'_a')); ?></p></details><?php endforeach; ?></div></div></section>
	<?php tfd_cta(); ?>
</main>
<?php get_footer(); ?>
