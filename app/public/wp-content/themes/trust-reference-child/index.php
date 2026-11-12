<?php
defined('ABSPATH') || exit;
get_header();
if(have_posts()): while(have_posts()): the_post(); ?>
<main id="main"><?php if(is_singular('location')){tfr_location();}elseif(is_singular('doctor')){echo '<section class="page-hero"><div class="wrap"><h1>'.esc_html(get_the_title()).'</h1></div></section><section class="sec"><div class="wrap">';the_content();echo '</div></section>';}else{the_content();} ?></main>
<?php endwhile; else: ?>
<main id="main"><section class="page-hero"><div class="wrap"><h1><?php echo esc_html(tfr_text('Page not found')); ?></h1><a class="btn" href="<?php echo esc_url(tfr_url('home')); ?>"><?php echo esc_html(tfr_text('Home')); ?></a></div></section></main>
<?php endif; get_footer(); ?>
