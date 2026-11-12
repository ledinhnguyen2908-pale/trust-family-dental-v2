<?php defined('ABSPATH') || exit; $landing=tfr_landing(); ?>
<!doctype html><html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class('reference-site'); ?>><?php wp_body_open(); ?><a class="skip-link" href="#main"><?php echo esc_html(tfr_text('Skip to content')); ?></a>
<div class="site-frame">
<div class="care-topbar"><div class="wrap care-topbar-inner">
<?php if(!$landing): ?><div class="care-availability"><span class="care-hours"><?php echo esc_html(tfr_text('Open 7 days a week')); ?></span><span class="care-coverage"><?php echo esc_html(tfr_text('Medicaid & most insurance accepted')); ?></span></div><?php endif; ?>
<?php tfr_preview_navigation(); ?>
<?php if(!$landing): ?><div class="care-contact"><a class="care-phone" href="tel:<?php echo esc_attr(tfd_phone_href(tfd_get_global_hotline())); ?>"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.8 2.1z"/></svg><?php echo esc_html(tfd_get_global_hotline()); ?></a><?php tfr_languages(); ?></div><?php endif; ?>
</div></div>
<header class="hdr <?php echo $landing?'lp-hdr':''; ?>"><div class="wrap"><a class="logo" href="<?php echo esc_url(tfr_url('home')); ?>"><?php echo tfr_tooth(); ?>Trust Family Dental</a>
<?php if($landing): ?><span class="lp-right"><?php tfr_languages(); ?><?php tfr_call(tfd_get_global_hotline(),'tel'); ?></span>
<?php else: ?><nav class="nav" aria-label="<?php echo esc_attr(tfr_text('Main navigation')); ?>"><?php tfr_nav(); ?></nav><a class="btn btn-primary desk" href="<?php echo esc_url(tfr_url('book')); ?>"><?php echo esc_html(tfr_text('Book now')); ?></a><button class="burger" aria-label="<?php echo esc_attr(tfr_text('Open menu')); ?>" aria-controls="mobile-nav" aria-expanded="false">≡</button><?php endif; ?>
</div><?php if(!$landing): ?><nav class="mnav" id="mobile-nav" aria-label="<?php echo esc_attr(tfr_text('Mobile navigation')); ?>"><?php tfr_nav(); ?><a href="<?php echo esc_url(tfr_url('book')); ?>"><?php echo esc_html(tfr_text('Book now')); ?></a></nav><?php endif; ?></header>
