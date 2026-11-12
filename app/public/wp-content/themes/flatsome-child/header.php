<?php defined('ABSPATH') || exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="tfd-skip" href="#main"><?php echo esc_html(tfd_copy('skip')); ?></a>
<div id="wrapper">
	<div class="tfd-top"><div class="tfd-shell"><span><?php echo esc_html(tfd_copy('topline')); ?></span><span class="tfd-top-links"><a href="<?php echo esc_url(tfd_page_url('emergency')); ?>"><?php echo esc_html(tfd_copy('nav_emergency')); ?></a><?php if($hotline=tfd_get_global_hotline()): ?><a href="tel:<?php echo esc_attr(tfd_phone_href($hotline)); ?>"><?php echo esc_html($hotline); ?></a><?php endif; ?></span></div></div>
	<header class="tfd-header">
		<div class="tfd-shell tfd-header__inner">
			<a href="<?php echo esc_url(tfd_home_url()); ?>" class="tfd-brand" aria-label="Trust Family Dental"><img src="<?php echo esc_url(tfd_asset('images/logo.png')); ?>" width="400" height="178" alt="Trust Family Dental"></a>
			<nav class="tfd-nav" aria-label="<?php echo esc_attr(tfd_copy('menu')); ?>" id="tfd-nav">
				<?php foreach(array('services','about','locations','payment','faq') as $role): $url=tfd_page_url($role); ?>
					<a href="<?php echo esc_url($url); ?>" <?php if(('home'===$role&&is_front_page()) || (!is_front_page()&&$url===get_permalink(get_queried_object_id()))) echo 'aria-current="page"'; ?>><?php echo esc_html(tfd_copy('nav_'.$role)); ?></a>
				<?php endforeach; ?>
			</nav>
			<div class="tfd-header__actions">
				<?php if(tfd_is_landing() && ($hotline=tfd_get_global_hotline())): ?><a class="tfd-header-hotline" href="tel:<?php echo esc_attr(tfd_phone_href($hotline)); ?>"><?php echo esc_html($hotline); ?></a><?php endif; ?>
				<nav class="tfd-languages" aria-label="<?php echo esc_attr(tfd_copy('language_label')); ?>"><?php tfd_languages(); ?></nav>
				<a class="tfd-btn tfd-header__book" href="<?php echo esc_url(tfd_page_url('appointment')); ?>"><?php echo esc_html(tfd_copy('book')); ?><?php echo tfd_icon('arrow'); ?></a>
				<button class="tfd-menu-toggle" type="button" aria-controls="tfd-nav" aria-expanded="false" aria-label="<?php echo esc_attr(tfd_copy('menu')); ?>"><?php echo tfd_icon('menu'); ?></button>
			</div>
		</div>
	</header>
	<div id="main" tabindex="-1">
