<?php
/** Concept v2: native WordPress pages, reusable dynamic sections, no client-side translations. */
defined('ABSPATH') || exit;

function tfd_concept_role() { return get_post_meta(get_queried_object_id(),'tfd_page_role',true); }
function tfd_is_landing() { return is_page() && in_array(tfd_concept_role(),array('emergency','restorative','family'),true); }
function tfd_concept_reason_label() {
 $labels=array('emergency'=>array('Cần hỗ trợ sớm','Prompt assistance','Atención pronta'),'restorative'=>array('Tư vấn phục hồi răng','Restoration consultation','Consulta de restauración'),'family'=>array('Thăm khám gia đình / trẻ em','Family / child’s visit','Visita familiar / infantil'));
 return $labels[tfd_concept_role()][array('vi'=>0,'en'=>1,'es'=>2)[tfd_language()]]??'';
}

add_action('wp_enqueue_scripts',function(){
 wp_enqueue_style('tfd-concept',tfd_asset('css/concept.css'),array('tfd-design'),filemtime(__DIR__.'/../assets/css/concept.css'));
 wp_enqueue_script('tfd-concept',tfd_asset('js/concept.js'),array('tfd-site'),filemtime(__DIR__.'/../assets/js/concept.js'),true);
 wp_script_add_data('tfd-concept','strategy','defer');
},35);
add_filter('template_include',function($template){
 return is_page() && get_post_meta(get_queried_object_id(),'_tfd_concept_v2',true) ? get_stylesheet_directory().'/page-concept.php' : $template;
},50);
add_filter('body_class',function($classes){ $classes[]='tfd-concept'; if(tfd_is_landing()) $classes[]='tfd-landing'; return $classes; });
add_action('template_redirect',function(){
 if(!is_post_type_archive('location')) return;
 $id=get_option('tfd_pages',array())['locations'][tfd_language()]??0;
 if($id && 'publish'===get_post_status($id)){ wp_safe_redirect(get_permalink($id),301); exit; }
});

function tfd_concept_actions($local=false,$urgent=false) {
 $url=$local?'#appointment-form':tfd_page_url('appointment');
 echo '<div class="tfd-v2-actions"><a class="tfd-btn" href="'.esc_url($url).'">'.esc_html(tfd_copy('book')).'</a>';
 if($urgent) echo '<a class="tfd-btn tfd-btn--outline" href="'.esc_url(tfd_page_url('emergency')).'">'.esc_html(tfd_copy('urgent_link')).'</a>';
 elseif($hotline=tfd_get_global_hotline()) echo '<a class="tfd-btn tfd-btn--outline" href="tel:'.esc_attr(tfd_phone_href($hotline)).'">'.esc_html(tfd_tr('call_hotline')).'</a>';
 echo '</div>';
}

add_shortcode('tfd_reasons',function(){
 $routes=array('pain'=>'emergency','child'=>'emergency','repair'=>'restorative','clean'=>'family','other'=>'appointment');
 $html='<div class="tfd-reasons">';
 foreach($routes as $reason=>$role) $html.='<a href="'.esc_url(tfd_page_url($role)).'" data-reason-link><span>'.esc_html(tfd_copy('reason_'.$reason)).'</span>'.tfd_icon('arrow').'</a>';
 return $html.'</div>';
});
add_shortcode('tfd_offices',function(){ ob_start(); echo '<div class="tfd-offices-grid">'; tfd_location_cards(); echo '</div>'; return ob_get_clean(); });
add_shortcode('tfd_payment_strip',function(){ return '<div class="tfd-payment-strip"><div class="tfd-shell"><strong>'.esc_html(tfd_copy('nav_payment')).'</strong><span>'.esc_html(tfd_copy('payment_prompt')).'</span><a class="tfd-text-link" href="'.esc_url(tfd_page_url('payment')).'">'.esc_html(tfd_copy('learn_more')).' →</a></div></div>'; });
add_shortcode('tfd_route_link',function($atts,$content=''){
 $a=shortcode_atts(array('role'=>'appointment','style'=>'outline'),$atts);
 $allowed=array('home','services','about','locations','payment','faq','appointment','contact','privacy','doctors','emergency','restorative','family');
 if(!in_array($a['role'],$allowed,true)) return '';
 return '<a class="tfd-btn'.('outline'===$a['style']?' tfd-btn--outline':'').'" href="'.esc_url(tfd_page_url($a['role'])).'">'.esc_html($content ?: tfd_copy('book')).'</a>';
});
add_shortcode('tfd_service_rows',function(){
 $html='<div class="tfd-service-rows">';
 foreach(tfd_services() as $service) $html.='<article><h3><a href="'.esc_url(get_permalink($service)).'">'.esc_html(get_the_title($service)).'</a></h3><p>'.esc_html(get_the_excerpt($service)).'</p><a class="tfd-text-link" href="'.esc_url(get_permalink($service)).'">'.esc_html(tfd_copy('learn_more')).' →</a></article>';
 return $html.'</div>';
});
add_shortcode('tfd_team',function(){
 $posts=get_posts(array('post_type'=>'doctor','post_status'=>'publish','numberposts'=>-1,'suppress_filters'=>false));
 if(!$posts) return '<p>'.esc_html(tfd_copy('team_pending')).'</p><p><a class="tfd-text-link" href="'.esc_url(tfd_page_url('contact')).'">'.esc_html(tfd_copy('nav_contact')).' →</a></p>';
 $html='<div class="tfd-v2-team">';
 foreach($posts as $post) $html.='<article>'.get_the_post_thumbnail($post->ID,'medium',array('loading'=>'lazy')).'<h3><a href="'.esc_url(get_permalink($post)).'">'.esc_html(get_the_title($post)).'</a></h3><p>'.esc_html(tfd_get_field('doctor_specialty',$post->ID)).'</p></article>';
 return $html.'</div>';
});
add_shortcode('tfd_booking',function(){
 ob_start(); ?>
 <div class="tfd-v2-booking" id="appointment-form" data-booking-reason="<?php echo esc_attr(tfd_concept_reason_label()); ?>" data-current-location="<?php echo esc_attr(tfd_current_form_location_slug()); ?>">
  <div class="tfd-booking-panel"><h2><?php echo esc_html(tfd_copy('request_visit')); ?></h2><p><?php echo esc_html(tfd_copy('booking_note')); ?></p><?php $form=tfd_get_appointment_form_shortcode(); echo $form?do_shortcode($form):'<p>'.esc_html(tfd_copy('form_unavailable')).'</p>'; ?></div>
  <aside><p class="tfd-kicker"><?php echo esc_html(tfd_copy('next_steps')); ?></p><div class="tfd-v2-next"><?php foreach(array('one','two','three') as $i=>$key): ?><article><span><?php echo (int)$i+1; ?></span><div><h3><?php echo esc_html(tfd_copy('step_'.$key)); ?></h3><p><?php echo esc_html(tfd_copy('step_'.$key.'_body')); ?></p></div></article><?php endforeach; ?></div><h3><?php echo esc_html(tfd_copy('find_location')); ?></h3><?php foreach(tfd_get_published_locations() as $office): ?><p><a href="<?php echo esc_url(get_permalink($office)); ?>"><?php echo esc_html(tfd_get_field('city',$office->ID)); ?> →</a><br><?php echo esc_html(tfd_format_location_address($office->ID)); ?></p><?php endforeach; ?></aside>
 </div>
 <?php return ob_get_clean();
});

// Testimonials are opt-in editorial records, never seeded from the concept's examples.
add_action('init',function(){
 add_post_type_support('page','excerpt');
 register_post_type('tfd_testimonial',array('labels'=>array('name'=>'Đánh giá đã duyệt','singular_name'=>'Đánh giá'),'public'=>false,'show_ui'=>true,'show_in_rest'=>true,'supports'=>array('title','editor','revisions'),'menu_icon'=>'dashicons-format-quote'));
});
add_filter('pll_get_post_types',function($types){$types['tfd_testimonial']='tfd_testimonial'; return $types;});
add_action('acf/init',function(){
 if(!function_exists('acf_add_local_field_group')) return;
 acf_add_local_field_group(array('key'=>'group_tfd_concept_heading','title'=>'Concept v2 — Tiêu đề đầu trang','fields'=>array(array('key'=>'field_tfd_concept_heading','name'=>'tfd_concept_heading','label'=>'Tiêu đề H1','type'=>'text','instructions'=>'Nhập tiêu đề theo ngôn ngữ của trang. Mô tả ngắn sửa trong mục Tóm tắt; nội dung sửa trong trình soạn thảo.')),'location'=>array(array(array('param'=>'page_template','operator'=>'==','value'=>'page-concept.php')))));
 acf_add_local_field_group(array('key'=>'group_tfd_review_consent','title'=>'Xác nhận quyền sử dụng','fields'=>array(array('key'=>'field_tfd_review_consent','name'=>'tfd_review_consent','label'=>'Đánh giá thật, đã có đồng ý cho phép công bố','type'=>'true_false','instructions'=>'Chỉ bật sau khi phòng khám kiểm chứng và lưu giấy đồng ý. Không nhập đánh giá mẫu trong file concept.','default_value'=>0)),'location'=>array(array(array('param'=>'post_type','operator'=>'==','value'=>'tfd_testimonial')))));
});
add_shortcode('tfd_reviews',function(){
 $posts=get_posts(array('post_type'=>'tfd_testimonial','post_status'=>'publish','numberposts'=>6,'meta_key'=>'tfd_review_consent','meta_value'=>'1','suppress_filters'=>false));
 if(!$posts) return '';
 $html='<section class="tfd-v2-section"><div class="tfd-shell"><h2>'.esc_html(tfd_copy('approved_reviews')).'</h2><div class="tfd-v2-columns">';
 foreach($posts as $post) $html.='<blockquote>'.wp_kses_post(wpautop($post->post_content)).'<cite>'.esc_html(get_the_title($post)).'</cite></blockquote>';
 return $html.'</div></div></section>';
});
