<?php
defined('ABSPATH') || exit;
function tfr_role(){return get_post_meta(get_queried_object_id(),'tfr_role',true);}
function tfr_landing(){return in_array(tfr_role(),array('lp1','lp2','lp3'),true);}
function tfr_office_booking_link($office){
 $url=add_query_arg('location',$office->post_name,tfr_url('book'));
 $role=tfr_role();
 $location=preg_replace('/-(en|es)$/','',$office->post_name);
 if(tfr_landing() && in_array($location,array('aurora','denver'),true)){
  return ' href="#'.esc_attr($role).'-form" data-form-office="'.esc_attr($location).'"';
 }
 return ' href="'.esc_url($url).'"';
}
// The concept's page selector belongs to the local review interface.
function tfr_preview_navigation(){
 if(tfr_landing() || 'local'!==wp_get_environment_type())return;
 $groups=array('Landing pages'=>array('lp1'=>"Can't wait",'lp2'=>'Restorative','lp3'=>'Family'));
 echo '<aside class="reference-selector" aria-label="Concept page selector"><div class="reference-selector-inner">';
 foreach($groups as $label=>$pages){
  echo '<nav class="reference-selector-group" aria-label="'.esc_attr($label).'"><span class="reference-selector-label">'.esc_html($label).'</span><div class="reference-selector-tabs">';
  foreach($pages as $role=>$title)printf('<a href="%s"%s>%s</a>',esc_url(tfr_url($role)),tfr_role()===$role?' aria-current="page"':'',esc_html($title));
  echo '</div></nav>';
 }
 echo '</div></aside>';
}
function tfr_reference_slugs(){return array('services'=>'services','about'=>'about','locations'=>'locations','payment'=>'payment-insurance','faq'=>'faq','book'=>'booking','lp1'=>'cant-wait','lp2'=>'restorative','lp3'=>'family','privacy'=>'privacy','nondiscrimination'=>'nondiscrimination','privacy-practices'=>'privacy-practices','accessibility'=>'accessibility');}
function tfr_english_office_slugs(){
 return array('denver-en'=>'denver','aurora-en'=>'aurora');
}
add_action('init',function(){
 foreach(tfr_english_office_slugs() as $slug=>$city){
  add_rewrite_rule('^locations/'.$city.'/?$','index.php?location='.$slug.'&lang=en','top');
 }
},30);
add_filter('post_type_link',function($url,$post){
 $city=tfr_english_office_slugs()[$post->post_name]??'';
 return $post->post_type==='location' && $post->post_status==='publish' && $city
  ? trailingslashit(get_option('home')).'locations/'.$city.'/' : $url;
},100,2);
add_action('template_redirect',function(){
 if(!is_singular('location'))return;
 $post=get_queried_object();
 if(!isset(tfr_english_office_slugs()[$post->post_name]))return;
 $request=wp_parse_url($_SERVER['REQUEST_URI']??'/');
 $legacy=wp_parse_url(trailingslashit(get_option('home')).'chi-nhanh/'.$post->post_name,PHP_URL_PATH);
 if(untrailingslashit($request['path']??'')!==$legacy)return;
 $target=get_permalink($post);
 if(!empty($request['query']))$target.='?'.$request['query'];
 wp_safe_redirect($target,301);
 exit;
},1);
add_filter('wp_robots',function($robots){
 if(tfr_landing() && !empty($robots['noindex'])){
  unset($robots['nofollow']);
  $robots['follow']=true;
 }
 return $robots;
},99);
remove_action('wp_head','print_emoji_detection_script',7);
remove_action('wp_enqueue_scripts','wp_enqueue_emoji_styles');
remove_action('wp_print_styles','print_emoji_styles');
add_filter('kirki_enqueue_google_fonts',function($fonts){
 unset($fonts['Lato'],$fonts['Dancing Script']);
 return $fonts;
},99);
// Native translated posts remain distinct. Explicit WordPress routes keep the reference's /es/ URLs
// without requiring duplicated database slugs or a paid plugin's shared-slug feature.
add_action('init',function(){
 $pages=get_option('tfr_pages',array());
 foreach(tfr_reference_slugs() as $role=>$slug)if(!empty($pages[$role]['es']))add_rewrite_rule('^es/'.preg_quote($slug,'/').'/?$','index.php?page_id='.(int)$pages[$role]['es'].'&lang=es','top');
},30);
add_filter('page_link',function($url,$id){
 if(!function_exists('pll_get_post_language') || pll_get_post_language($id)!=='es')return $url;
 $slug=tfr_reference_slugs()[get_post_meta($id,'tfr_role',true)]??'';
 return $slug?trailingslashit(get_option('home')).'es/'.$slug.'/':$url;
},100,2);
function tfr_text($text,$lang=null){
 $lang=$lang?:tfd_language(); if($lang!=='es')return $text;
 static $es; if(null===$es)$es=json_decode(file_get_contents(__DIR__.'/es.json'),true);
 $extra=array('Illustrative overview. Use Get directions for the actual map.'=>'Vista ilustrativa. Usa Cómo llegar para ver el mapa real.','Your office'=>'Tu clínica','Closed'=>'Cerrado');
 return $extra[$text]??$es[$text]??$text;
}
function tfr_url($role){
 $role=array('appointment'=>'book','emergency'=>'lp1','restorative'=>'lp2','family'=>'lp3')[$role]??$role;
 $id=get_option('tfr_pages',array())[$role][tfd_language()]??0;
 return $id && get_post_status($id)==='publish'?get_permalink($id):(function_exists('pll_home_url')?pll_home_url():home_url('/'));
}
function tfr_tooth(){return '<svg width="28" height="28" viewBox="0 0 30 30" aria-hidden="true"><path d="M8 4c-3 0-5 3-5 7 0 5 2 8 3 12 .6 2.6 1.4 4 2.6 4 1.6 0 1.8-3 2.4-6 .4-1.8 1-2.6 1.9-2.6h.2c.9 0 1.5.8 1.9 2.6.6 3 .8 6 2.4 6 1.2 0 2-1.4 2.6-4 1-4 3-7 3-12 0-4-2-7-5-7-2.4 0-3.6 1.2-5 1.2S10.4 4 8 4z" fill="none" stroke="var(--accent)" stroke-width="2"/></svg>';}
function tfr_call($label,$class=''){printf('<a class="%s" href="tel:%s">%s</a>',esc_attr($class),esc_attr(tfd_phone_href(tfd_get_global_hotline())),esc_html($label));}
function tfr_languages(){
 echo '<span class="langsw" aria-label="Languages">';
 $first=true; foreach((array)pll_the_languages(array('raw'=>1,'hide_if_no_translation'=>1)) as $lang){
  if(!in_array($lang['slug'],array('en','es'),true))continue;
  if(!$first)echo '<span aria-hidden="true">|</span>'; $first=false;
  printf('<a href="%s" lang="%s" hreflang="%s" %s>%s</a>',esc_url($lang['url']),esc_attr($lang['slug']),esc_attr($lang['slug']),$lang['current_lang']?'class="on" aria-current="page"':'',esc_html(strtoupper($lang['slug'])));
 } echo '</span>';
}
function tfr_nav(){foreach(array('services'=>'Services','about'=>'About','locations'=>'Locations','payment'=>'Payment & Insurance','faq'=>'FAQ') as $role=>$label)printf('<a href="%s" %s>%s</a>',esc_url(tfr_url($role)),tfr_role()===$role?'class="on" aria-current="page"':'',esc_html(tfr_text($label)));}
add_filter('template_include',function(){return get_stylesheet_directory().'/index.php';},100);
add_filter('document_title_parts',function($parts){if(is_front_page())$parts['tagline']=tfr_text('A beautiful smile begins with trust.');return $parts;});
add_action('wp',function(){
 // The supplied HTML owns its layout. Do not append Flatsome's off-canvas menu or typography.
 remove_action('wp_head','flatsome_custom_css',100);
 remove_action('wp_head','flatsome_viewport_meta',1);
 remove_action('wp_footer','flatsome_mobile_menu',7);
 remove_action('wp_footer','flatsome_body_overlay');
 if(tfr_role())remove_filter('the_content','wpautop');
});
add_action('wp_enqueue_scripts',function(){
 foreach(wp_styles()->queue as $handle)if(!in_array($handle,array('admin-bar','dashicons','contact-form-7'),true))wp_dequeue_style($handle);
 wp_enqueue_style('tfr-reference',get_stylesheet_directory_uri().'/assets/site.css',array(),filemtime(__DIR__.'/../assets/site.css'));
 wp_enqueue_style('tfr-design',get_stylesheet_directory_uri().'/assets/design.css',array('tfr-reference'),filemtime(__DIR__.'/../assets/design.css'));
 wp_enqueue_style('tfr-polish',get_stylesheet_directory_uri().'/assets/polish.css',array('tfr-design'),filemtime(__DIR__.'/../assets/polish.css'));
 wp_enqueue_script('tfr-reference',get_stylesheet_directory_uri().'/assets/site.js',array(),filemtime(__DIR__.'/../assets/site.js'),true);
 wp_script_add_data('tfr-reference','strategy','defer');
},999);
add_action('template_redirect',function(){
 $path=wp_parse_url($_SERVER['REQUEST_URI']??'/',PHP_URL_PATH);
 $legacy=array('same-day-dental-care'=>'lp1','tooth-replacement'=>'lp2','family-dentist'=>'lp3');
 $requested=trim((string)$path,'/');
 $language='en';
 if(str_starts_with($requested,'es/')){
  $language='es';
  $requested=substr($requested,3);
  $legacy+=array('cant-wait-es'=>'lp1','restorative-es'=>'lp2','family-es'=>'lp3');
 }
 $role=$legacy[$requested]??'';
 $legacy_target=get_option('tfr_pages',array())[$role][$language]??0;
 if($legacy_target && get_post_status($legacy_target)==='publish'){
  $url=get_permalink($legacy_target);
  if(untrailingslashit((string)$path)!==untrailingslashit(wp_parse_url($url,PHP_URL_PATH))){
   $query=wp_parse_url($_SERVER['REQUEST_URI']??'/',PHP_URL_QUERY);
   nocache_headers();
   wp_safe_redirect($query?$url.'?'.$query:$url,301);
   exit;
  }
 }
},-10);
add_action('template_redirect',function(){
 if(is_post_type_archive('location')){wp_safe_redirect(tfr_url('locations'),301);exit;}
 if(is_post_type_archive('doctor')){wp_safe_redirect(tfr_url('about'),301);exit;}
 $path=wp_parse_url($_SERVER['REQUEST_URI']??'/',PHP_URL_PATH);
 $target=get_option('tfr_redirects',array())[$path]??0;
 if($target && 'publish'===get_post_status($target) && $path!==wp_parse_url(get_permalink($target),PHP_URL_PATH)){wp_safe_redirect(get_permalink($target),301);exit;}
});
add_filter('the_content',function($html){
 $html=preg_replace_callback('/(?:\{\{|%7B%7B)tfr(?::|%3A)([a-z0-9-]+)(?:\}\}|%7D%7D)/i',function($m){return esc_url(tfr_url($m[1]));},$html);
 return str_ireplace(array('{{tfr-hotline}}','%7B%7Btfr-hotline%7D%7D'),esc_attr(tfd_phone_href(tfd_get_global_hotline())),$html);
},8);
add_action('wp_head',function(){
 if(is_singular() && !defined('WPSEO_VERSION') && !defined('RANK_MATH_VERSION'))printf('<meta name="description" content="%s">'."\n",esc_attr(wp_strip_all_tags(get_the_excerpt(get_queried_object_id()))));
},5);

add_shortcode('tfr_span',function($atts,$content=''){
 return '<span>'.esc_html(html_entity_decode(wp_strip_all_tags((string)$content),ENT_QUOTES|ENT_HTML5,'UTF-8')).'</span>';
});
function tfr_landing_office_hours($office){
 $hours=array_column(tfd_get_opening_hours($office->ID),null,'day');
 $days=array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
 foreach($days as $day){
  $row=$hours[$day]??null;
  if(!$row || (!$row['is_closed'] && (!$row['opens'] || !$row['closes'])))return '';
 }
 $html='<table class="table"><caption>'.esc_html(tfd_tr('opening_hours')).'</caption><tbody>';
 foreach($days as $day){
  $row=$hours[$day];
  $value=$row['is_closed']?tfr_text('Closed'):tfd_format_time($row['opens']).' – '.tfd_format_time($row['closes']);
  $html.='<tr><th scope="row">'.esc_html(tfd_tr($day)).'</th><td>'.esc_html($value).'</td></tr>';
 }
 return $html.'</tbody></table>';
}
add_shortcode('tfr_locations',function($atts){
 $mode=shortcode_atts(array('mode'=>'compact'),$atts)['mode'];$offices=tfd_get_published_locations();
 // Match the reference order while remaining a dynamic CMS query.
 usort($offices,function($a,$b){return strcmp(tfd_get_field('city',$b->ID),tfd_get_field('city',$a->ID));});
 $full=!in_array($mode,array('compact','mini'),true);ob_start();
 if($full): ?><div class="locwrap"><div class="map"><svg viewBox="0 0 760 420" role="img" aria-label="<?php echo esc_attr(tfr_text('Locations')); ?>"><rect width="760" height="420" fill="#f7f7f4"/><path d="M70 0v420M260 0v420M480 0v420M680 0v420M0 90h760M0 200h760M0 320h760" fill="none" stroke="#e2e2db" stroke-width="12"/><path d="M30 10 410 410M10 340 700 60" stroke="#d3d7ca" stroke-width="8"/><?php foreach($offices as $i=>$office): $x=220+($i%2)*330;$y=170+floor($i/2)*70+($i%2)*65; ?><a href="<?php echo esc_url(get_permalink($office)); ?>"><circle cx="<?php echo (int)$x; ?>" cy="<?php echo (int)$y; ?>" r="12" fill="#1f5c3d"/><text x="<?php echo (int)$x+20; ?>" y="<?php echo (int)$y+7; ?>" font-size="22" font-weight="700" fill="#141414"><?php echo esc_html(tfd_get_field('city',$office->ID)); ?></text></a><?php endforeach; ?></svg><p class="small"><?php echo esc_html(tfr_text('Illustrative overview. Use Get directions for the actual map.')); ?></p></div><div class="loclist"><?php else: ?><div class="loc-compact" <?php if($mode==='mini')echo 'style="grid-template-columns:1fr"'; ?>><?php endif; ?>
 <?php foreach($offices as $office): ?><div><h3><a href="<?php echo esc_url(get_permalink($office)); ?>"><?php echo esc_html(tfd_get_field('city',$office->ID)); ?></a></h3><p><?php echo esc_html(tfd_get_field('street_address',$office->ID).' '.tfd_get_field('address_line_2',$office->ID)); ?><br><?php echo esc_html(tfd_get_field('city',$office->ID).', '.tfd_get_field('state',$office->ID).' '.tfd_get_field('postal_code',$office->ID)); ?></p><?php $schedule=tfr_landing()?tfr_landing_office_hours($office):''; if($schedule){echo $schedule;}else{ ?><p style="margin-top:6px"><?php echo esc_html(tfr_text('Call to confirm opening hours.')); ?></p><?php } ?><div class="acts"><a class="link" href="<?php echo esc_url(tfd_get_field('google_maps_url',$office->ID)); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html(tfr_text('Get directions')); ?></a><?php tfr_call(tfr_text('Call now'),'link'); ?><a class="link"<?php echo tfr_office_booking_link($office); ?>><?php echo esc_html(tfr_text('Book now')); ?></a>
 <?php if(tfr_landing()):
  $review_links=array('Aurora'=>'https://maps.app.goo.gl/z6nTH5WfjGDbkH5QA','Denver'=>'https://maps.app.goo.gl/uHfVT3kkZRVkDZvF9');
  $review_url=$review_links[tfd_get_field('city',$office->ID)]??'';
  if($review_url): ?>
   <a class="link" href="<?php echo esc_url($review_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html(tfd_language()==='es'?'Ver reseñas en Google':'Read Google reviews'); ?></a>
  <?php endif;
 endif; ?>
 </div></div><?php endforeach; ?>
 </div><?php if($full)echo '</div>';return ob_get_clean();
});
add_shortcode('tfr_testimonials',function($atts){
 if(tfr_landing() || 'local'!==wp_get_environment_type())return ''; // Never present concept examples as genuine reviews.
 $group=shortcode_atts(array('group'=>'home'),$atts)['group'];
 $sets=json_decode(file_get_contents(__DIR__.'/samples.json'),true);$rows=$sets[$group]??array();
 $out='<p class="sample-label">'.esc_html(tfr_text('Layout samples — not real patient reviews.')).'</p><div class="rv">';
 foreach($rows as $row)$out.='<div><div class="stars" aria-hidden="true">★★★★★</div><q>'.esc_html(tfr_text($row['text'])).'</q><div class="who">'.esc_html($row['name']).'<span>'.esc_html(tfr_text($row['service'])).'</span></div></div>';
 return $out.'</div>';
});
function tfr_location(){
 $id=get_the_ID(); ?>
 <section class="page-hero"><div class="wrap"><p class="eyebrow"><a href="<?php echo esc_url(tfr_url('locations')); ?>"><?php echo esc_html(tfr_text('Locations')); ?></a></p><h1><?php the_title(); ?></h1><p class="lead"><?php echo esc_html(tfd_format_location_address($id)); ?></p></div></section>
 <section class="sec"><div class="wrap location-grid"><div><?php the_content(); ?><h2 class="h2"><?php echo esc_html(tfr_text('Book at this office')); ?></h2><?php echo do_shortcode(tfd_get_appointment_form_shortcode()); ?></div><aside class="location-panel"><h2 class="h2"><?php echo esc_html(tfr_text('Your office')); ?></h2><address><?php echo esc_html(tfd_format_location_address($id)); ?></address><p><a href="tel:<?php echo esc_attr(tfd_phone_href(tfd_get_field('location_phone',$id))); ?>"><?php echo esc_html(tfd_get_field('location_phone',$id)); ?></a></p><h3><?php echo esc_html(tfr_text('Hours')); ?></h3><?php $hours=tfd_get_opening_hours($id); if(!$hours)echo '<p>'.esc_html(tfr_text('Call to confirm opening hours.')).'</p>';else foreach($hours as $day)echo '<p>'.esc_html(tfd_tr($day['day']).' '.($day['is_closed']?tfr_text('Closed'):$day['opens'].' – '.$day['closes'])).'</p>'; ?><div class="loc-map-placeholder" data-map-url="<?php echo esc_url(tfd_normalize_map_embed_url(tfd_get_field('google_maps_embed_url',$id))); ?>"><button class="btn" type="button" data-load-map><?php echo esc_html(tfr_text('View map')); ?></button></div></aside></div></section>
 <?php
}
add_filter('wpcf7_form_tag',function($tag){
 $form=WPCF7_ContactForm::get_current();
 $id=$form?(int)$form->id():0;
 if(!in_array($id,array(121,123,125,127,129,131),true))return $tag;
 $spanish=in_array($id,array(123,127,131),true);
 $was_array=is_array($tag);
 $tag=(object)$tag;
 if($tag->basetype==='acceptance' && $tag->name==='consent'){
  $tag->content=$spanish
   ? 'Acepto recibir llamadas y mensajes de texto de Trust Family Dental sobre mi solicitud de cita. La frecuencia de los mensajes varía. Pueden aplicarse tarifas de mensajes y datos. Responda STOP para dejar de recibir mensajes o HELP para obtener ayuda. El consentimiento no es una condición del servicio. <a href="{{tfr:privacy}}">Política de privacidad</a>'
   : 'I agree to receive calls and text messages from Trust Family Dental about my appointment request. Msg frequency varies. Msg &amp; data rates may apply. Reply STOP to opt out, HELP for help. Consent is not a condition of service. <a href="{{tfr:privacy}}">Privacy policy</a>';
 }
 if($tag->basetype==='select' && $tag->name==='location'){
  $offices=array('aurora-es'=>'aurora','denver-es'=>'denver');
  $tag->values=array_map(static fn($value)=>$offices[$value]??$value,$tag->values);
  $tag->raw_values=array_map(static fn($label,$value)=>$label.'|'.$value,$tag->labels,$tag->values);
 }
 if($tag->basetype==='select' && $tag->name==='payment'){
  $labels=$spanish
   ? array('medicaid_chp'=>'Medicaid / CHP+','private_insurance'=>'Seguro privado','monthly_payments'=>'Pagos mensuales','no_insurance'=>'Sin seguro','not_sure'=>'No estoy seguro')
   : array('medicaid_chp'=>'Medicaid / CHP+','private_insurance'=>'Private insurance','monthly_payments'=>'Monthly payments','no_insurance'=>'No insurance','not_sure'=>'Not sure');
  $codes=array_flip($labels)+array(
   'Medicaid / Health First Colorado'=>'medicaid_chp',
   'CHP+'=>'medicaid_chp',
   'Insurance'=>'private_insurance',
   'Seguro'=>'private_insurance',
  );
  $tag->values=array_map(static fn($label)=>$codes[$label]??$label,$tag->labels);
  foreach($labels as $code=>$label){
   if(in_array($code,$tag->values,true))continue;
   $tag->values[]=$code;
   $tag->labels[]=$label;
  }
  $tag->raw_values=$tag->values;
  $tag->pipes=new WPCF7_Pipes(array());
 }
 if(in_array($id,array(125,127),true) && $tag->basetype==='select' && $tag->name==='interest'){
  $new_options=$spanish?array('Implante dental','Tratamiento de conducto'):array('Dental implant','Root canal');
  foreach(array('values','labels','raw_values') as $property){
   $tag->$property=array_values(array_unique(array_merge($new_options,$tag->$property)));
  }
 }
 return $was_array?get_object_vars($tag):$tag;
},25);
add_filter('wpcf7_form_elements',function($html){
 $html=str_replace('{{tfr:privacy}}',esc_url(tfr_url('privacy')),$html);
 if(!tfr_landing() && 'local'===wp_get_environment_type())$html='<p class="preview-note">'.esc_html(tfr_text('Local preview — no real appointments.')).'</p>'.$html;
 return '<div class="form">'.$html.'</div>';
});
