<?php
/** Template Name: Trust Dental — Concept v2 */
defined('ABSPATH') || exit;
get_header();
$role=tfd_concept_role();
$visual=is_front_page() || tfd_is_landing();
?>
<main class="tfd-v2-page">
<?php while(have_posts()): the_post(); ?>
<section class="tfd-v2-hero"><div class="tfd-shell <?php echo $visual?'tfd-v2-hero-grid':''; ?>">
 <div><?php if(!is_front_page()): ?><nav class="tfd-v2-breadcrumb" aria-label="Breadcrumb"><a href="<?php echo esc_url(tfd_home_url()); ?>"><?php echo esc_html(tfd_copy('nav_home')); ?></a><span aria-hidden="true"> / </span><span><?php the_title(); ?></span></nav><?php else: ?><p class="tfd-kicker"><?php echo esc_html(tfd_copy('hero_eyebrow')); ?></p><?php endif; ?>
 <h1><?php echo esc_html(get_post_meta(get_the_ID(),'tfd_concept_heading',true) ?: get_the_title()); ?></h1>
 <p class="tfd-v2-lead"><?php echo esc_html(get_the_excerpt()); ?></p>
 <?php if(!in_array($role,array('appointment','faq'),true)) tfd_concept_actions(tfd_is_landing(),is_front_page()); ?>
 </div>
 <?php if($visual): ?><figure class="tfd-v2-hero-image"><?php if(has_post_thumbnail()): the_post_thumbnail('large',array('fetchpriority'=>'high','loading'=>'eager')); else: ?><img src="<?php echo esc_url(tfd_asset('images/gentle-care.webp')); ?>" width="722" height="1000" alt="<?php echo esc_attr(tfd_copy('care_photo')); ?>" fetchpriority="high" loading="eager"><?php endif; ?></figure><?php endif; ?>
</div></section>
<div class="tfd-v2-content"><?php the_content(); ?></div>
<?php endwhile; ?>
<?php if('appointment'!==$role): ?><section class="tfd-v2-closing"><div class="tfd-shell"><h2><?php echo esc_html(tfd_copy('signature')); ?></h2><?php tfd_concept_actions(tfd_is_landing()); ?></div></section><?php endif; ?>
</main>
<?php get_footer(); ?>
