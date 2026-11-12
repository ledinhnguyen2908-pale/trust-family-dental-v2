<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/copy.php';
require_once __DIR__ . '/admin.php';
require_once __DIR__ . '/concept.php';

function tfd_asset($file) {
	return get_stylesheet_directory_uri() . '/assets/' . ltrim($file,'/');
}

function tfd_home_url() {
	return function_exists('pll_home_url') ? pll_home_url(tfd_language()) : home_url('/');
}

function tfd_page_url($role) {
	$mapped = get_option('tfd_pages',array())[$role][tfd_language()] ?? 0;
	if($mapped && 'publish' === get_post_status($mapped)) return get_permalink($mapped);
	if('locations'===$role) return get_post_type_archive_link('location');
	if('doctors'===$role) return get_post_type_archive_link('doctor');
	$pages = get_option('tfd_pages',array());
	$id = $pages[$role][tfd_language()] ?? 0;
	return $id && 'publish' === get_post_status($id) ? get_permalink($id) : tfd_home_url();
}

function tfd_icon($name='tooth') {
	$paths = array(
		'arrow'=>'<path d="M5 12h14m-6-6 6 6-6 6"/>',
		'pin'=>'<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>',
		'phone'=>'<path d="m7 3 3 5-3 3c1 3 3 5 6 6l3-3 5 3c-1 4-3 5-6 4C9 19 5 15 3 9 2 6 3 4 7 3Z"/>',
		'calendar'=>'<rect x="3" y="5" width="18" height="16" rx="3"/><path d="M7 2v6m10-6v6M3 11h18m-14 4h3m4 0h3"/>',
		'heart'=>'<path d="M20 5c-3-3-7-1-8 1-1-2-5-4-8-1-4 4 1 10 8 15 7-5 12-11 8-15Z"/>',
		'check'=>'<path d="m5 12 4 4L19 6"/>',
		'tooth'=>'<path d="M12 5C7 1 2 5 5 12c1 3 1 9 3 9s2-7 4-7 2 7 4 7 2-6 3-9c3-7-2-11-7-7Z"/><path d="m9 4 5 3"/>',
		'implant'=>'<path d="M7 9c-3-6 1-8 5-5 4-3 8-1 5 5H7Zm2 3h6m-6 4h6m-5 4h4M9 9l1 13m5-13-1 13"/>',
		'crown'=>'<path d="m3 6 3 13h12l3-13-6 5-3-8-3 8-6-5Zm3 16h12"/>',
		'bridge'=>'<path d="M2 13c1-6 5-6 7 0h6c2-6 6-6 7 0M3 13v6m5-6v6m8-6v6m5-6v6M8 15h8"/>',
		'root'=>'<path d="M5 11C1 2 8 1 12 5c4-4 11-3 7 6l-3 10-4-8-4 8-3-10Z"/><path d="M12 5v8"/>',
		'mail'=>'<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 6 9 7 9-7"/>',
		'globe'=>'<circle cx="12" cy="12" r="9"/><ellipse cx="12" cy="12" rx="4" ry="9"/><path d="M3 12h18"/>',
		'menu'=>'<path d="M4 6h16M4 12h16M4 18h16"/>',
	);
	return '<svg class="tfd-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.($paths[$name] ?? $paths['tooth']).'</svg>';
}

function tfd_services() {
	$pages = get_option('tfd_pages',array());
	$parent = $pages['services'][tfd_language()] ?? 0;
	return $parent ? get_posts(array('post_type'=>'page','post_parent'=>$parent,'post_status'=>'publish','numberposts'=>-1,'orderby'=>'menu_order','order'=>'ASC','suppress_filters'=>false)) : array();
}

function tfd_service_cards() {
	foreach(tfd_services() as $post) {
		$icon = get_post_meta($post->ID,'tfd_service_icon',true) ?: 'tooth';
		?>
		<a class="tfd-service" href="<?php echo esc_url(get_permalink($post)); ?>">
			<span class="tfd-service__icon"><?php echo tfd_icon($icon); ?></span>
			<h3><?php echo esc_html(get_the_title($post)); ?></h3>
			<p><?php echo esc_html(get_the_excerpt($post)); ?></p>
			<span class="tfd-text-link"><?php echo esc_html(tfd_copy('learn_more')); ?> <?php echo tfd_icon('arrow'); ?></span>
		</a>
		<?php
	}
}

function tfd_location_cards() {
	foreach(tfd_get_published_locations() as $index=>$location) {
		$city = tfd_get_field('city',$location->ID);
		$phone = tfd_get_field('location_phone',$location->ID);
		?>
		<article class="tfd-office">
			<div class="tfd-office__top"><span class="tfd-office__number"><?php echo esc_html(str_pad($index+1,2,'0',STR_PAD_LEFT)); ?></span><?php echo tfd_icon('pin'); ?><span><?php echo esc_html(tfd_get_field('state',$location->ID)); ?></span></div>
			<h3><?php echo esc_html($city ?: get_the_title($location)); ?></h3>
			<p class="tfd-office__address"><?php echo esc_html(tfd_format_location_address($location->ID)); ?></p>
			<?php if($phone): ?><a class="tfd-office__phone" href="tel:<?php echo esc_attr(tfd_phone_href($phone)); ?>"><?php echo tfd_icon('phone'); ?><?php echo esc_html($phone); ?></a><?php endif; ?>
			<a class="tfd-office__detail" href="<?php echo esc_url(get_permalink($location)); ?>"><?php echo esc_html(tfd_copy('location_detail')); ?><?php echo tfd_icon('arrow'); ?></a>
			<?php if($map=tfd_get_field('google_maps_url',$location->ID)): ?><a class="tfd-text-link" href="<?php echo esc_url($map); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html(tfd_copy('directions')); ?> ↗</a><?php endif; ?>
		</article>
		<?php
	}
}

function tfd_cta() { ?>
	<section class="tfd-cta"><div class="tfd-shell tfd-cta__inner">
		<div><span class="tfd-kicker">TRUST FAMILY DENTAL</span><h2><?php echo wp_kses_post(tfd_copy('cta_title')); ?></h2><p><?php echo esc_html(tfd_copy('cta_body')); ?></p></div>
		<a class="tfd-btn tfd-btn--cream" href="<?php echo esc_url(tfd_page_url('appointment')); ?>"><?php echo esc_html(tfd_copy('book')); ?><?php echo tfd_icon('arrow'); ?></a>
	</div></section>
<?php }

function tfd_languages() {
	if(function_exists('pll_the_languages')) {
		$languages = pll_the_languages(array('raw'=>1,'hide_if_empty'=>0,'hide_if_no_translation'=>1));
		foreach((array)$languages as $lang) {
			printf('<a href="%s" lang="%s" hreflang="%s" %s>%s</a>',esc_url($lang['url']),esc_attr($lang['slug']),esc_attr($lang['slug']),$lang['current_lang']?'aria-current="true" class="is-current"':'',esc_html(strtoupper($lang['slug'])));
		}
	} else {
		$languages = apply_filters('wpml_active_languages',null,array('skip_missing'=>1));
		foreach((array)$languages as $lang) {
			printf('<a href="%s" lang="%s" %s>%s</a>',esc_url($lang['url']),esc_attr($lang['language_code']),$lang['active']?'aria-current="true" class="is-current"':'',esc_html(strtoupper($lang['language_code'])));
		}
	}
}

add_action('wp_enqueue_scripts', function() {
	wp_enqueue_style('tfd-design',tfd_asset('css/design.css'),array('flatsome-main','flatsome-style'),filemtime(get_stylesheet_directory().'/assets/css/design.css'));
	wp_enqueue_script('tfd-site',tfd_asset('js/site.js'),array(),filemtime(get_stylesheet_directory().'/assets/js/site.js'),true);
	wp_script_add_data('tfd-site','strategy','defer');
},30);

add_filter('body_class',function($classes){ $classes[]='tfd-site'; return $classes; });
function tfd_is_service_page($id) {
	$pages=get_option('tfd_pages',array());
	return in_array((int)wp_get_post_parent_id($id),array_map('intval',array_values($pages['services']??array())),true);
}
add_filter('template_include',function($template){
	if(is_page() && tfd_is_service_page(get_queried_object_id())) return get_stylesheet_directory().'/page-tfd.php';
	return $template;
},30);
add_filter('document_title_parts',function($parts){
	if(is_front_page()) $parts['tagline']=tfd_copy('topline');
	if(is_post_type_archive('location')) $parts['title']=tfd_copy('our_locations');
	if(is_post_type_archive('doctor')) $parts['title']=tfd_copy('our_doctors');
	return $parts;
});
add_action('wp_enqueue_scripts',function(){
	$pages=get_option('tfd_pages',array());
	if(is_singular('location') || is_page(array_values($pages['appointment']??array()))) return;
	// Other pages may contain a manually added CF7 shortcode.
	if(is_singular() && (has_shortcode(get_post_field('post_content',get_queried_object_id()),'contact-form-7') || has_shortcode(get_post_field('post_content',get_queried_object_id()),'tfd_booking'))) return;
	wp_dequeue_script('contact-form-7');
	wp_dequeue_script('swv');
	wp_dequeue_style('contact-form-7');
},40);
add_filter('pll_get_post_types',function($types){$types['location']='location'; $types['doctor']='doctor'; return $types;});
add_filter('tfd_appointment_form_shortcode',function($shortcode){
	$forms=get_option('tfd_forms',array());
	$id=$forms[tfd_language()] ?? 0;
	return $id && shortcode_exists('contact-form-7') ? '[contact-form-7 id="'.absint($id).'"]' : $shortcode;
});

// Resolve privacy links at render time so translated forms survive a domain migration.
add_filter('wpcf7_form_elements',function($html){
	$html=preg_replace_callback('~href="[^"]*(?:/privacy/|/quyen-rieng-tu/|/privacidad/)"~',function(){return 'href="'.esc_url(tfd_page_url('privacy')).'"';},$html);
	if('local'===wp_get_environment_type()) $html='<p class="tfd-local-note">'.esc_html(tfd_copy('local_notice')).'</p>'.$html;
	return $html;
});
add_filter('gettext_contact-form-7',function($translated,$original){
	if('Undefined value was submitted through this field.'!==$original) return $translated;
	$form=WPCF7_ContactForm::get_current();
	$lang=$form?substr($form->locale(),0,2):tfd_language();
	return array('vi'=>'Vui lòng chọn một chi nhánh trong danh sách.','en'=>'Please select a location from the list.','es'=>'Elige una clínica de la lista.')[$lang]??$translated;
},10,2);

/** Use actual published content only, with a self-canonical fallback when no SEO plugin is active. */
add_action('wp_head',function(){
	if(defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION')) return;
	if(is_front_page() && !get_post_meta(get_queried_object_id(),'_tfd_concept_v2',true)) $description=wp_strip_all_tags(tfd_copy('hero_body'));
	elseif(is_singular()) $description=wp_strip_all_tags(get_the_excerpt(get_queried_object_id()));
	else $description='';
	if($description) printf('<meta name="description" content="%s">'."\n",esc_attr($description));
	if(is_post_type_archive()) printf('<link rel="canonical" href="%s">'."\n",esc_url(get_post_type_archive_link(get_query_var('post_type'))));
},5);

/** A single clinic telephone source; only an explicitly configured global hotline is used. */
add_filter('tfd_global_hotline',function(){ $settings=get_option('tfd_settings',array()); return $settings['global_hotline'] ?? ''; });

add_action('phpmailer_init',function($mailer){
	if('local'!==wp_get_environment_type()) return;
	$mailer->isSMTP();
	$mailer->Host='127.0.0.1';
	$mailer->Port=10019;
	$mailer->SMTPAuth=false;
	$mailer->SMTPAutoTLS=false;
});

/** Register all design strings in the native translation system. */
add_action('init',function(){
	if(!is_admin()) return;
	foreach(tfd_copy_catalogue() as $key=>$values){
		if(function_exists('pll_register_string')) pll_register_string($key,$values[0],'Trust Family Dental',true);
		do_action('wpml_register_single_string','Trust Family Dental',$key,$values[0]);
	}
},30);
