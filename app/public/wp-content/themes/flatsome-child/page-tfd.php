<?php
/**
 * Template Name: Trust Dental — Trang nội dung
 */
defined('ABSPATH') || exit;
get_header();
$role = $tfd_role ?? get_post_meta(get_queried_object_id(),'tfd_page_role',true);
if(!$role && is_page() && tfd_is_service_page(get_queried_object_id())) $role='service';
$titles=array('services'=>'services_title','locations'=>'locations_title','doctors'=>'doctors_title','contact'=>'contact_title','appointment'=>'booking_title','about'=>'about_title');
?>
<main class="tfd-inner-page">
<?php if(in_array($role,array('appointment','about','doctors'),true)): ?>
	<section class="tfd-inner-hero"><div class="tfd-shell tfd-inner-hero__split"><div><p class="tfd-kicker"><?php echo esc_html(tfd_copy('appointment'===$role?'booking_eyebrow':'about_eyebrow')); ?></p><h1><?php echo wp_kses_post(tfd_copy($titles[$role])); ?></h1><p><?php echo esc_html(tfd_copy('appointment'===$role?'booking_intro':('doctors'===$role?'doctors_intro':'about_body'))); ?></p></div><div class="tfd-inner-hero__image"><img src="<?php echo esc_url(tfd_asset('images/gentle-care.webp')); ?>" width="722" height="1000" alt="<?php echo esc_attr(tfd_copy('care_photo')); ?>" fetchpriority="high"></div></div></section>
<?php else: ?>
	<section class="tfd-page-heading"><div class="tfd-shell"><p class="tfd-kicker">TRUST FAMILY DENTAL</p><h1><?php echo isset($titles[$role])?wp_kses_post(tfd_copy($titles[$role])):esc_html(get_the_title()); ?></h1><?php if(in_array($role,array('services','locations','contact'),true)): ?><p><?php echo esc_html(tfd_copy(array('services'=>'service_intro','locations'=>'locations_intro','contact'=>'contact_intro')[$role])); ?></p><?php endif; ?></div></section>
<?php endif; ?>

<?php if('services'===$role): ?>
	<section class="tfd-section"><div class="tfd-shell"><div class="tfd-services-grid tfd-services-grid--full"><?php tfd_service_cards(); ?></div></div></section>
<?php elseif('locations'===$role || 'contact'===$role): ?>
	<section class="tfd-section"><div class="tfd-shell"><div class="tfd-offices-grid"><?php tfd_location_cards(); ?></div></div></section>
<?php elseif('appointment'===$role): ?>
	<section class="tfd-section"><div class="tfd-shell tfd-booking-layout">
		<aside><p class="tfd-kicker"><?php echo esc_html(tfd_copy('nav_locations')); ?></p><h2><?php echo esc_html(tfd_copy('find_location')); ?></h2><?php foreach(tfd_get_published_locations() as $location): ?><div class="tfd-booking-office"><h3><?php echo esc_html(tfd_get_field('city',$location->ID)); ?></h3><p><?php echo esc_html(tfd_format_location_address($location->ID)); ?></p><a href="tel:<?php echo esc_attr(tfd_phone_href(tfd_get_field('location_phone',$location->ID))); ?>"><?php echo tfd_icon('phone'); ?><?php echo esc_html(tfd_get_field('location_phone',$location->ID)); ?></a></div><?php endforeach; ?></aside>
		<div class="tfd-booking-panel" id="appointment-form" data-current-location="<?php echo esc_attr(tfd_current_form_location_slug()); ?>"><h2><?php echo esc_html(tfd_copy('request_visit')); ?></h2><p><?php echo esc_html(tfd_copy('booking_note')); ?></p><?php $form=tfd_get_appointment_form_shortcode(); if($form && shortcode_exists('contact-form-7')) echo do_shortcode($form); else echo '<p>'.esc_html(tfd_copy('form_unavailable')).'</p>'; ?></div>
	</div></section>
<?php elseif('doctors'===$role): ?>
	<section class="tfd-section"><div class="tfd-shell"><div class="tfd-doctor-archive__grid">
	<?php $doctors=get_posts(array('post_type'=>'doctor','post_status'=>'publish','numberposts'=>-1,'suppress_filters'=>false)); foreach($doctors as $doctor): ?>
		<article class="tfd-profile-card"><?php echo get_the_post_thumbnail($doctor->ID,'large',array('loading'=>'lazy')); ?><div class="tfd-profile-card__body"><h2><a href="<?php echo esc_url(get_permalink($doctor)); ?>"><?php echo esc_html(get_the_title($doctor)); ?></a></h2><p><?php echo esc_html(tfd_get_field('doctor_specialty',$doctor->ID)); ?></p><a class="tfd-text-link" href="<?php echo esc_url(get_permalink($doctor)); ?>"><?php echo esc_html(tfd_copy('view_profile')); ?> →</a></div></article>
	<?php endforeach; ?></div>
	<?php if(!$doctors): ?><div class="tfd-team-note"><?php echo tfd_icon('heart'); ?><h2><?php echo esc_html(tfd_copy('team_contact')); ?></h2><p><?php echo esc_html(tfd_copy('team_contact_body')); ?></p><a class="tfd-btn" href="<?php echo esc_url(tfd_page_url('contact')); ?>"><?php echo esc_html(tfd_copy('nav_contact')); ?><?php echo tfd_icon('arrow'); ?></a></div><?php endif; ?>
	</div></section>
<?php elseif('about'===$role): ?>
	<section class="tfd-section"><div class="tfd-shell tfd-about-values"><?php foreach(array('one','two','three') as $i=>$key): ?><article><span class="tfd-kicker">0<?php echo (int)$i+1; ?></span><h2><?php echo esc_html(tfd_copy('about_point_'.$key)); ?></h2><p><?php echo esc_html(tfd_copy('step_'.$key.'_body')); ?></p></article><?php endforeach; ?></div></section>
<?php else: ?>
	<section class="tfd-section"><div class="tfd-shell tfd-article-layout"><article class="tfd-prose"><?php while(have_posts()): the_post(); the_content(); endwhile; ?></article><?php if('service'===$role): ?><aside class="tfd-service-aside"><h2><?php echo esc_html(tfd_copy('service_next')); ?></h2><p><?php echo esc_html(tfd_copy('service_next_body')); ?></p><a class="tfd-btn" href="<?php echo esc_url(tfd_page_url('appointment')); ?>"><?php echo esc_html(tfd_copy('book')); ?><?php echo tfd_icon('arrow'); ?></a><a class="tfd-text-link" href="<?php echo esc_url(tfd_page_url('services')); ?>">← <?php echo esc_html(tfd_copy('back_services')); ?></a></aside><?php endif; ?></div></section>
<?php endif; ?>
<?php if('appointment'!==$role && 'privacy'!==$role) tfd_cta(); ?>
</main>
<?php get_footer(); ?>
