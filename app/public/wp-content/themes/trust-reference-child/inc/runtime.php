<?php
defined('ABSPATH') || exit;
require_once __DIR__.'/copy.php';
require_once __DIR__.'/admin.php';
function tfd_page_url($role){return tfr_url($role);}
function tfd_asset($file){return get_stylesheet_directory_uri().'/assets/'.$file;}
add_filter('tfd_global_hotline',function(){return get_option('tfd_settings',array())['global_hotline']??'';});
add_filter('pll_get_post_types',function($types){$types['location']='location';$types['doctor']='doctor';return $types;});
add_filter('tfd_appointment_form_shortcode',function($value){$forms=get_option('tfr_forms',array());$lang=tfd_language();$role=get_post_meta(get_queried_object_id(),'tfr_role',true);$id=$forms[$role][$lang]??($forms['book'][$lang]??0);return $id?'[contact-form-7 id="'.absint($id).'"]':$value;});
add_action('phpmailer_init',function($mailer){if('local'!==wp_get_environment_type())return;$mailer->isSMTP();$mailer->Host='127.0.0.1';$mailer->Port=10019;$mailer->SMTPAuth=false;$mailer->SMTPAutoTLS=false;});
