<?php
defined('ABSPATH') || exit;

/** Stable portable group storage in both ACF Free and Pro. */
function tfd_add_acf_group($group) {
	if(!function_exists('acf_add_local_field_group')) return;
	if('group_tfd_site_settings'===$group['key']) return; // One settings screen, not two.
	{
		foreach($group['fields'] as &$field) {
			if('opening_hours'===($field['name']??'')) {
				$field['type']='group';
				$field['layout']='block';
				$field['sub_fields']=array();
				foreach(array('monday','tuesday','wednesday','thursday','friday','saturday','sunday') as $day) {
					$field['sub_fields'][]=array('key'=>'field_tfd_hours_'.$day,'name'=>$day,'label'=>tfd_tr($day),'type'=>'group','layout'=>'table','sub_fields'=>array(
						array('key'=>'field_tfd_'.$day.'_closed','name'=>'is_closed','label'=>'Đóng cửa','type'=>'true_false','ui'=>1),
						array('key'=>'field_tfd_'.$day.'_opens','name'=>'opens','label'=>'Mở cửa','type'=>'time_picker','display_format'=>'H:i','return_format'=>'H:i'),
						array('key'=>'field_tfd_'.$day.'_closes','name'=>'closes','label'=>'Đóng cửa lúc','type'=>'time_picker','display_format'=>'H:i','return_format'=>'H:i'),
						array('key'=>'field_tfd_'.$day.'_note','name'=>'note','label'=>'Ghi chú','type'=>'text'),
					));
				}
			}
			if('facility_gallery'===($field['name']??'')) {
				$field['type']='group';
				$field['sub_fields']=array();
				for($i=1;$i<=6;$i++) $field['sub_fields'][]=array('key'=>'field_tfd_gallery_'.$i,'name'=>'image_'.$i,'label'=>'Ảnh '.$i,'type'=>'image','return_format'=>'id','wrapper'=>array('width'=>33));
			}
			if('global_hotline'===($field['name']??'') && 'group_tfd_location_information'===$group['key']) {
				$field['type']='message'; unset($field['name']);
				$field['message']='Hotline tổng được quản lý tại Giao diện → Cài đặt Trust Dental.';
			}
		}
		unset($field);
	}
	acf_add_local_field_group($group);
}

add_action('admin_menu',function(){
	add_theme_page('Cài đặt Trust Dental','Cài đặt Trust Dental','manage_options','tfd-settings','tfd_settings_screen');
});

add_action('admin_init',function(){
	register_setting('tfd_settings','tfd_settings',array('type'=>'array','sanitize_callback'=>function($input){
		return array('global_hotline'=>sanitize_text_field($input['global_hotline']??''),'organization_name'=>sanitize_text_field($input['organization_name']??'Trust Family Dental'));
	}));
});
function tfd_settings_screen() {
	if(!current_user_can('manage_options')) return;
	$settings=get_option('tfd_settings',array());
	?>
	<div class="wrap"><h1>Cài đặt Trust Family Dental</h1>
	<p>Hotline dưới đây là số duy nhất dùng cho nút gọi tổng trên mọi ngôn ngữ. Các số của Denver và Aurora được quản lý riêng trong Chi nhánh.</p>
	<form method="post" action="options.php"><?php settings_fields('tfd_settings'); ?><table class="form-table">
	<tr><th><label for="tfd-org">Tên thương hiệu</label></th><td><input id="tfd-org" class="regular-text" name="tfd_settings[organization_name]" value="<?php echo esc_attr($settings['organization_name']??'Trust Family Dental'); ?>"></td></tr>
	<tr><th><label for="tfd-hotline">Hotline tổng</label></th><td><input id="tfd-hotline" class="regular-text" type="tel" name="tfd_settings[global_hotline]" value="<?php echo esc_attr($settings['global_hotline']??''); ?>"><p class="description">Nhập số đã được phòng khám xác nhận, bao gồm mã quốc gia (+1).</p></td></tr>
	</table><?php submit_button(); ?></form>
	<h2>Quản lý nội dung</h2><p>Chi nhánh → Thêm mới: nhập NAP, giờ làm việc, ảnh và chọn bác sĩ. Trang mới tự xuất hiện trong website và form. Tạo bản dịch bằng nút + của Polylang.</p>
	<p>Trang → sửa các trang Dịch vụ để đổi nội dung. Ngôn ngữ → Bản dịch chuỗi → Trust Family Dental để chỉnh nội dung giao diện.</p>
	</div>
	<?php
}

/** Synchronize only factual metadata in Polylang Free. Pro/ACFML provide their own field integration. */
add_filter('pll_copy_post_metas',function($metas,$sync,$from,$to){
	if(defined('POLYLANG_PRO') || !in_array(get_post_type($from),array('location','doctor'),true)) return $metas;
	foreach(get_post_meta($from) as $name=>$values) {
		if(preg_match('/^_?(city|state|postal_code|country_code|location_phone|location_email|latitude|longitude|google_maps_.*|opening_hours.*|facility_gallery.*|doctor_experience)$/',$name) && !str_ends_with($name,'_note')) $metas[]=$name;
	}
	return array_unique($metas);
},10,4);
