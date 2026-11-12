<?php
defined('ABSPATH') || exit;
require_once __DIR__.'/core.php';
require_once __DIR__.'/inc/reference.php';
require_once __DIR__.'/inc/page-images.php';
remove_action('wp_enqueue_scripts','tfd_enqueue_frontend_assets',20);
remove_action('wp_footer','tfd_render_mobile_action_bar',30);
remove_filter('body_class','tfd_mobile_action_body_class');

// One-time repair for the Can't wait language pair and legacy landing-page redirects.
add_action('init', function () {
    if (!function_exists('pll_set_post_language') || get_option('tfr_landing_urls_fixed_20261110')) return;
    wp_update_post(array('ID' => 124, 'post_name' => 'cant-wait-es-temp'));
    pll_set_post_language(122, 'en');
    pll_set_post_language(124, 'es');
    wp_update_post(array('ID' => 122, 'post_name' => 'cant-wait'));
    wp_update_post(array('ID' => 124, 'post_name' => 'cant-wait-es'));
    $redirects = (array) get_option('tfr_redirects', array());
    $redirects['/same-day-dental-care/'] = 122;
    $redirects['/tooth-replacement/'] = 126;
    $redirects['/family-dentist/'] = 130;
    update_option('tfr_redirects', $redirects, false);
    update_option('tfr_landing_urls_fixed_20261110', 1, false);
    flush_rewrite_rules(false);
}, 99);
