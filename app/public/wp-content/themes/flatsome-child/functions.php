<?php
/**
 * Trust Family Dental child-theme functions.
 *
 * @package Trust_Family_Dental
 */

defined( 'ABSPATH' ) || exit;
require_once __DIR__ . '/inc/site.php';

/** Load child-theme translations. */
function tfd_child_theme_setup() {
	load_child_theme_textdomain( 'flatsome-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'tfd_child_theme_setup' );

/**
 * Register the location and doctor content types.
 * Vietnamese is the default language; WPML/Polylang Pro translates the bases.
 */
function tfd_register_content_types() {
	register_post_type(
		'location',
		array(
			'labels' => array(
				'name'                  => __( 'Chi nhánh', 'flatsome-child' ),
				'singular_name'         => __( 'Chi nhánh', 'flatsome-child' ),
				'menu_name'             => __( 'Chi nhánh', 'flatsome-child' ),
				'name_admin_bar'        => __( 'Chi nhánh', 'flatsome-child' ),
				'add_new'               => __( 'Thêm chi nhánh', 'flatsome-child' ),
				'add_new_item'          => __( 'Thêm chi nhánh mới', 'flatsome-child' ),
				'edit_item'             => __( 'Sửa chi nhánh', 'flatsome-child' ),
				'new_item'              => __( 'Chi nhánh mới', 'flatsome-child' ),
				'view_item'             => __( 'Xem chi nhánh', 'flatsome-child' ),
				'search_items'          => __( 'Tìm chi nhánh', 'flatsome-child' ),
				'not_found'             => __( 'Không tìm thấy chi nhánh.', 'flatsome-child' ),
				'not_found_in_trash'    => __( 'Không có chi nhánh trong thùng rác.', 'flatsome-child' ),
				'all_items'             => __( 'Tất cả chi nhánh', 'flatsome-child' ),
				'archives'              => __( 'Danh sách chi nhánh', 'flatsome-child' ),
				'featured_image'        => __( 'Ảnh đại diện chi nhánh', 'flatsome-child' ),
				'set_featured_image'    => __( 'Chọn ảnh đại diện', 'flatsome-child' ),
				'remove_featured_image' => __( 'Gỡ ảnh đại diện', 'flatsome-child' ),
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'query_var'          => true,
			'has_archive'        => 'chi-nhanh',
			'rewrite'            => array( 'slug' => 'chi-nhanh', 'with_front' => false ),
			'menu_icon'          => 'dashicons-location-alt',
			'menu_position'      => 20,
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
		),
	);

	register_post_type(
		'doctor',
		array(
			'labels' => array(
				'name'               => __( 'Bác sĩ', 'flatsome-child' ),
				'singular_name'      => __( 'Bác sĩ', 'flatsome-child' ),
				'menu_name'          => __( 'Bác sĩ', 'flatsome-child' ),
				'name_admin_bar'     => __( 'Bác sĩ', 'flatsome-child' ),
				'add_new'            => __( 'Thêm bác sĩ', 'flatsome-child' ),
				'add_new_item'       => __( 'Thêm bác sĩ mới', 'flatsome-child' ),
				'edit_item'          => __( 'Sửa hồ sơ bác sĩ', 'flatsome-child' ),
				'new_item'           => __( 'Bác sĩ mới', 'flatsome-child' ),
				'view_item'          => __( 'Xem hồ sơ bác sĩ', 'flatsome-child' ),
				'search_items'       => __( 'Tìm bác sĩ', 'flatsome-child' ),
				'not_found'          => __( 'Không tìm thấy bác sĩ.', 'flatsome-child' ),
				'all_items'          => __( 'Tất cả bác sĩ', 'flatsome-child' ),
				'archives'           => __( 'Danh sách bác sĩ', 'flatsome-child' ),
				'featured_image'     => __( 'Ảnh chân dung', 'flatsome-child' ),
				'set_featured_image' => __( 'Chọn ảnh chân dung', 'flatsome-child' ),
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'query_var'          => true,
			'has_archive'        => 'bac-si',
			'rewrite'            => array( 'slug' => 'bac-si', 'with_front' => false ),
			'menu_icon'          => 'dashicons-id-alt',
			'menu_position'      => 21,
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
		),
	);
}
add_action( 'init', 'tfd_register_content_types', 5 );

/** Register ACF Pro options and field groups in code. */
function tfd_register_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	tfd_add_acf_group(
		array(
			'key'      => 'group_tfd_location_information',
			'title'    => __( 'Thông Tin Chi Nhánh', 'flatsome-child' ),
			'fields'   => array(
				array(
					'key' => 'field_tfd_location_short_name', 'label' => __( 'Tên hiển thị ngắn', 'flatsome-child' ), 'name' => 'location_short_name', 'type' => 'text',
					'instructions' => __( 'Ví dụ: Denver hoặc Aurora. Tiêu đề bài viết vẫn là tên SEO đầy đủ.', 'flatsome-child' ), 'wpml_cf_preferences' => 2,
				),
				array( 'key' => 'field_tfd_address_tab', 'label' => __( 'Địa chỉ & liên hệ', 'flatsome-child' ), 'type' => 'tab' ),
				array(
					'key' => 'field_tfd_street_address', 'label' => __( 'Địa chỉ đường', 'flatsome-child' ), 'name' => 'street_address', 'type' => 'text', 'required' => 1,
					'instructions' => __( 'Chỉ nhập số nhà và tên đường; không lặp lại thành phố, bang, mã bưu chính.', 'flatsome-child' ), 'wpml_cf_preferences' => 2,
				),
				array(
					'key' => 'field_tfd_address_line_2', 'label' => __( 'Địa chỉ dòng 2', 'flatsome-child' ), 'name' => 'address_line_2', 'type' => 'text',
					'instructions' => __( 'Suite, Unit, tầng… nếu có.', 'flatsome-child' ), 'wpml_cf_preferences' => 2,
				),
				array( 'key' => 'field_tfd_city', 'label' => __( 'Thành phố', 'flatsome-child' ), 'name' => 'city', 'type' => 'text', 'required' => 1, 'wrapper' => array( 'width' => 30 ), 'wpml_cf_preferences' => 1 ),
				array( 'key' => 'field_tfd_state', 'label' => __( 'Bang/Tỉnh', 'flatsome-child' ), 'name' => 'state', 'type' => 'text', 'required' => 1, 'wrapper' => array( 'width' => 25 ), 'wpml_cf_preferences' => 1 ),
				array( 'key' => 'field_tfd_postal_code', 'label' => __( 'Mã bưu chính', 'flatsome-child' ), 'name' => 'postal_code', 'type' => 'text', 'required' => 1, 'wrapper' => array( 'width' => 25 ), 'wpml_cf_preferences' => 1 ),
				array(
					'key' => 'field_tfd_country_code', 'label' => __( 'Quốc gia', 'flatsome-child' ), 'name' => 'country_code', 'type' => 'select',
					'choices' => array( 'US' => 'United States' ), 'default_value' => 'US', 'required' => 1, 'wrapper' => array( 'width' => 20 ), 'wpml_cf_preferences' => 1,
				),
				array(
					'key' => 'field_tfd_location_phone', 'label' => __( 'Điện thoại chi nhánh', 'flatsome-child' ), 'name' => 'location_phone', 'type' => 'text', 'required' => 1,
					'instructions' => __( 'Nhập định dạng hiển thị, ví dụ: 303-935-2353.', 'flatsome-child' ), 'wrapper' => array( 'width' => 33 ), 'wpml_cf_preferences' => 1,
				),
				array( 'key' => 'field_tfd_location_email', 'label' => __( 'Email chi nhánh', 'flatsome-child' ), 'name' => 'location_email', 'type' => 'email', 'wrapper' => array( 'width' => 33 ), 'wpml_cf_preferences' => 1 ),
				array(
					'key' => 'field_tfd_location_global_hotline', 'label' => __( 'Hotline tổng (dự phòng)', 'flatsome-child' ), 'name' => 'global_hotline', 'type' => 'text',
					'instructions' => __( 'Chỉ dùng khi chưa cấu hình hotline ở Cài đặt phòng khám. Nên quản lý hotline tổng tại một nơi để tránh sai NAP.', 'flatsome-child' ), 'wrapper' => array( 'width' => 34 ), 'wpml_cf_preferences' => 1,
				),
				array( 'key' => 'field_tfd_map_tab', 'label' => __( 'Bản đồ & tọa độ', 'flatsome-child' ), 'type' => 'tab' ),
				array(
					'key' => 'field_tfd_google_maps_embed_url', 'label' => __( 'Google Maps Embed URL / iframe', 'flatsome-child' ), 'name' => 'google_maps_embed_url', 'type' => 'textarea', 'rows' => 3, 'new_lines' => '',
					'instructions' => __( 'Dán URL trong thuộc tính src hoặc toàn bộ iframe từ Google Maps. Hệ thống chỉ lưu URL embed hợp lệ.', 'flatsome-child' ), 'wpml_cf_preferences' => 1,
				),
				array(
					'key' => 'field_tfd_google_maps_url', 'label' => __( 'Link Google Maps công khai', 'flatsome-child' ), 'name' => 'google_maps_url', 'type' => 'url',
					'instructions' => __( 'Link dùng cho hasMap trong schema và nút mở ứng dụng bản đồ.', 'flatsome-child' ), 'wpml_cf_preferences' => 1,
				),
				array( 'key' => 'field_tfd_latitude', 'label' => __( 'Vĩ độ (Latitude)', 'flatsome-child' ), 'name' => 'latitude', 'type' => 'number', 'min' => -90, 'max' => 90, 'step' => 0.000001, 'wrapper' => array( 'width' => 50 ), 'wpml_cf_preferences' => 1 ),
				array( 'key' => 'field_tfd_longitude', 'label' => __( 'Kinh độ (Longitude)', 'flatsome-child' ), 'name' => 'longitude', 'type' => 'number', 'min' => -180, 'max' => 180, 'step' => 0.000001, 'wrapper' => array( 'width' => 50 ), 'wpml_cf_preferences' => 1 ),
				array( 'key' => 'field_tfd_hours_tab', 'label' => __( 'Giờ làm việc', 'flatsome-child' ), 'type' => 'tab' ),
				array(
					'key' => 'field_tfd_opening_hours', 'label' => __( 'Lịch làm việc theo ngày', 'flatsome-child' ), 'name' => 'opening_hours', 'type' => 'repeater', 'layout' => 'table', 'min' => 7, 'max' => 7,
					'button_label' => __( 'Thêm ngày', 'flatsome-child' ), 'instructions' => __( 'Giữ đủ 7 ngày. Đánh dấu Đóng cửa thay vì để giờ trống.', 'flatsome-child' ), 'wpml_cf_preferences' => 1,
					'sub_fields' => array(
						array(
							'key' => 'field_tfd_opening_day', 'label' => __( 'Ngày', 'flatsome-child' ), 'name' => 'day', 'type' => 'select', 'required' => 1, 'wrapper' => array( 'width' => 25 ), 'wpml_cf_preferences' => 1,
							'choices' => array(
								'monday' => __( 'Thứ Hai', 'flatsome-child' ), 'tuesday' => __( 'Thứ Ba', 'flatsome-child' ), 'wednesday' => __( 'Thứ Tư', 'flatsome-child' ),
								'thursday' => __( 'Thứ Năm', 'flatsome-child' ), 'friday' => __( 'Thứ Sáu', 'flatsome-child' ), 'saturday' => __( 'Thứ Bảy', 'flatsome-child' ), 'sunday' => __( 'Chủ Nhật', 'flatsome-child' ),
							),
						),
						array( 'key' => 'field_tfd_opening_closed', 'label' => __( 'Đóng cửa', 'flatsome-child' ), 'name' => 'is_closed', 'type' => 'true_false', 'ui' => 1, 'wrapper' => array( 'width' => 15 ), 'wpml_cf_preferences' => 1 ),
						array( 'key' => 'field_tfd_opening_opens', 'label' => __( 'Mở cửa', 'flatsome-child' ), 'name' => 'opens', 'type' => 'time_picker', 'display_format' => 'H:i', 'return_format' => 'H:i', 'wrapper' => array( 'width' => 20 ), 'wpml_cf_preferences' => 1 ),
						array( 'key' => 'field_tfd_opening_closes', 'label' => __( 'Đóng cửa lúc', 'flatsome-child' ), 'name' => 'closes', 'type' => 'time_picker', 'display_format' => 'H:i', 'return_format' => 'H:i', 'wrapper' => array( 'width' => 20 ), 'wpml_cf_preferences' => 1 ),
						array(
							'key' => 'field_tfd_opening_note', 'label' => __( 'Ghi chú', 'flatsome-child' ), 'name' => 'note', 'type' => 'text', 'wrapper' => array( 'width' => 20 ),
							'instructions' => __( 'Ví dụ: Chỉ nhận hẹn trước.', 'flatsome-child' ), 'wpml_cf_preferences' => 2,
						),
					),
				),
				array( 'key' => 'field_tfd_media_staff_tab', 'label' => __( 'Hình ảnh & bác sĩ', 'flatsome-child' ), 'type' => 'tab' ),
				array(
					'key' => 'field_tfd_facility_gallery', 'label' => __( 'Hình ảnh cơ sở vật chất', 'flatsome-child' ), 'name' => 'facility_gallery', 'type' => 'gallery',
					'return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'wpml_cf_preferences' => 1,
				),
				array(
					'key' => 'field_tfd_location_doctors', 'label' => __( 'Bác sĩ phụ trách', 'flatsome-child' ), 'name' => 'location_doctors', 'type' => 'relationship',
					'post_type' => array( 'doctor' ), 'post_status' => array( 'publish' ), 'filters' => array( 'search' ), 'return_format' => 'id',
					'instructions' => __( 'Chọn và sắp xếp các bác sĩ đang làm tại chi nhánh này.', 'flatsome-child' ), 'wpml_cf_preferences' => 1,
				),
			),
			'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'location' ) ) ),
			'position' => 'normal', 'style' => 'default', 'label_placement' => 'top', 'instruction_placement' => 'label', 'active' => true, 'show_in_rest' => 1,
		)
	);

	tfd_add_acf_group(
		array(
			'key' => 'group_tfd_doctor_information', 'title' => __( 'Thông tin chuyên môn', 'flatsome-child' ),
			'fields' => array(
				array( 'key' => 'field_tfd_doctor_specialty', 'label' => __( 'Chuyên khoa', 'flatsome-child' ), 'name' => 'doctor_specialty', 'type' => 'text', 'required' => 1, 'wpml_cf_preferences' => 2 ),
				array( 'key' => 'field_tfd_doctor_credentials', 'label' => __( 'Bằng cấp/chứng chỉ', 'flatsome-child' ), 'name' => 'doctor_credentials', 'type' => 'textarea', 'rows' => 3, 'wpml_cf_preferences' => 2 ),
				array( 'key' => 'field_tfd_doctor_experience', 'label' => __( 'Số năm kinh nghiệm', 'flatsome-child' ), 'name' => 'doctor_experience', 'type' => 'number', 'min' => 0, 'wpml_cf_preferences' => 1 ),
			),
			'location' => array( array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'doctor' ) ) ), 'active' => true, 'show_in_rest' => 1,
		)
	);

	tfd_add_acf_group(
		array(
			'key' => 'group_tfd_site_settings', 'title' => __( 'Cấu hình dùng chung', 'flatsome-child' ),
			'fields' => array(
				array( 'key' => 'field_tfd_global_hotline', 'label' => __( 'Hotline tổng', 'flatsome-child' ), 'name' => 'global_hotline', 'type' => 'text', 'required' => 1, 'instructions' => __( 'Nguồn duy nhất cho Mobile Floating Bar trên toàn website.', 'flatsome-child' ), 'wpml_cf_preferences' => 1 ),
				array( 'key' => 'field_tfd_organization_name', 'label' => __( 'Tên thương hiệu', 'flatsome-child' ), 'name' => 'organization_name', 'type' => 'text', 'default_value' => 'Trust Family Dental', 'wpml_cf_preferences' => 1 ),
				array( 'key' => 'field_tfd_appointment_page', 'label' => __( 'Trang đặt lịch', 'flatsome-child' ), 'name' => 'appointment_page', 'type' => 'post_object', 'post_type' => array( 'page' ), 'return_format' => 'id', 'required' => 1, 'wpml_cf_preferences' => 1 ),
				array( 'key' => 'field_tfd_appointment_form_shortcode', 'label' => __( 'Shortcode form đặt lịch', 'flatsome-child' ), 'name' => 'appointment_form_shortcode', 'type' => 'text', 'instructions' => __( 'Ví dụ: [contact-form-7 id="123" title="Đặt lịch"] hoặc shortcode Fluent Forms.', 'flatsome-child' ), 'wpml_cf_preferences' => 1 ),
			),
			'location' => array( array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'tfd-site-settings' ) ) ), 'active' => true,
		)
	);
}
add_action( 'acf/init', 'tfd_register_acf_fields' );

/** Seed seven opening-hours rows the first time a location is edited. */
function tfd_seed_opening_hours( $value, $post_id, $field ) {
	if ( !is_admin() || 'repeater' !== ($field['type'] ?? '') ) return $value;
	if ( false !== $value && null !== $value && array() !== $value ) {
		return $value;
	}

	$rows = array();
	foreach ( array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ) as $day ) {
		$rows[] = array(
			'field_tfd_opening_day' => $day, 'field_tfd_opening_closed' => 0,
			'field_tfd_opening_opens' => '', 'field_tfd_opening_closes' => '', 'field_tfd_opening_note' => '',
		);
	}
	return $rows;
}
add_filter( 'acf/load_value/name=opening_hours', 'tfd_seed_opening_hours', 10, 3 );

/** Extract and allowlist a Google Maps embed URL from a URL or iframe snippet. */
function tfd_normalize_map_embed_url( $value ) {
	$value = is_string( $value ) ? trim( wp_unslash( $value ) ) : '';
	if ( preg_match( '/<iframe[^>]+src=["\']([^"\']+)["\']/i', $value, $matches ) ) {
		$value = html_entity_decode( $matches[1], ENT_QUOTES, 'UTF-8' );
	}

	$url  = esc_url_raw( $value, array( 'https' ) );
	$host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
	$path = strtolower( (string) wp_parse_url( $url, PHP_URL_PATH ) );
	$is_google_host = 'google.com' === $host || '.google.com' === substr( $host, -11 );

	return $url && $is_google_host && false !== strpos( $path, '/maps' ) ? $url : '';
}

/** Sanitize the ACF map field before it reaches the database. */
function tfd_sanitize_map_embed_field( $value, $post_id, $field ) {
	return tfd_normalize_map_embed_url( $value );
}
add_filter( 'acf/update_value/name=google_maps_embed_url', 'tfd_sanitize_map_embed_field', 10, 3 );

/** Fetch ACF values with a simple post-meta fallback. */
function tfd_get_field( $name, $post_id = false ) {
	if('option' === $post_id) {
		$settings = get_option('tfd_settings',array());
		if(isset($settings[$name])) return $settings[$name];
	}
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $name, $post_id );
		if('facility_gallery' === $name && is_array($value)) return array_values(array_filter($value));
		return $value;
	}
	if ( 'option' === $post_id ) {
		return get_option( 'options_' . $name, '' );
	}
	return get_post_meta( $post_id ?: get_the_ID(), $name, true );
}

/** Return strings registered in WPML String Translation/Polylang. */
function tfd_ui_strings() {
	return array(
		'location_details' => 'Thông tin chi nhánh', 'opening_hours' => 'Giờ mở cửa', 'doctors' => 'Bác sĩ phụ trách',
		'book_here' => 'Đặt lịch tại chi nhánh này', 'call_hotline' => 'Gọi hotline', 'quick_booking' => 'Đặt hẹn nhanh',
		'view_map' => 'Xem bản đồ', 'map' => 'Bản đồ', 'facility_gallery' => 'Hình ảnh cơ sở vật chất', 'select_location' => 'Chọn chi nhánh',
		'our_locations' => 'Hệ thống chi nhánh', 'view_details' => 'Xem chi tiết',
		'our_doctors' => 'Đội ngũ bác sĩ', 'view_profile' => 'Xem hồ sơ', 'experience' => 'Kinh nghiệm', 'years' => 'năm', 'credentials' => 'Bằng cấp & chứng chỉ',
		'closed' => 'Đóng cửa', 'email' => 'Email', 'phone' => 'Điện thoại', 'address' => 'Địa chỉ',
		'no_doctors' => 'Thông tin bác sĩ đang được cập nhật.', 'form_not_configured' => 'Form đặt lịch đang được cấu hình. Vui lòng gọi hotline để được hỗ trợ.',
		'monday' => 'Thứ Hai', 'tuesday' => 'Thứ Ba', 'wednesday' => 'Thứ Tư', 'thursday' => 'Thứ Năm', 'friday' => 'Thứ Sáu', 'saturday' => 'Thứ Bảy', 'sunday' => 'Chủ Nhật',
	);
}

/** Register UI strings with the active multilingual plugin. */
function tfd_register_translation_strings() {
	foreach ( tfd_ui_strings() as $key => $value ) {
		do_action( 'wpml_register_single_string', 'Trust Family Dental', $key, $value );
		if ( is_admin() && function_exists( 'pll_register_string' ) ) {
			pll_register_string( $key, $value, 'Trust Family Dental', false );
		}
	}
}
add_action( 'init', 'tfd_register_translation_strings', 20 );

/** Translate a registered UI string. */
function tfd_tr( $key ) {
	if(isset(tfd_copy_catalogue()[$key])) return tfd_copy($key);
	$strings = tfd_ui_strings();
	$value   = isset( $strings[ $key ] ) ? $strings[ $key ] : $key;
	if ( has_filter( 'wpml_translate_single_string' ) ) {
		return apply_filters( 'wpml_translate_single_string', $value, 'Trust Family Dental', $key );
	}
	if ( function_exists( 'pll__' ) ) {
		return pll__( $value );
	}
	return $value;
}

/** Return structured address parts. */
function tfd_get_location_address_parts( $location_id ) {
	return array_filter(
		array(
			'street' => trim( (string) tfd_get_field( 'street_address', $location_id ) ), 'line_2' => trim( (string) tfd_get_field( 'address_line_2', $location_id ) ),
			'city' => trim( (string) tfd_get_field( 'city', $location_id ) ), 'state' => trim( (string) tfd_get_field( 'state', $location_id ) ),
			'postal_code' => trim( (string) tfd_get_field( 'postal_code', $location_id ) ), 'country' => trim( (string) tfd_get_field( 'country_code', $location_id ) ) ?: 'US',
		),
		'strlen'
	);
}

/** Render the one-line human-readable address. */
function tfd_format_location_address( $location_id ) {
	$parts    = tfd_get_location_address_parts( $location_id );
	$lines    = array_filter( array( $parts['street'] ?? '', $parts['line_2'] ?? '' ) );
	$locality = trim( implode( ', ', array_filter( array( $parts['city'] ?? '', $parts['state'] ?? '' ) ) ) );
	$locality = trim( $locality . ' ' . ( $parts['postal_code'] ?? '' ) );
	if ( $locality ) {
		$lines[] = $locality;
	}
	return implode( ', ', $lines );
}

/** Normalize a human-formatted phone number for a tel: URL. */
function tfd_phone_href( $phone ) {
	$phone = preg_replace( '/[^0-9+]/', '', (string) $phone );
	if(preg_match('/^\d{10}$/',$phone)) $phone='+1'.$phone;
	return preg_replace( '/(?!^)\+/', '', $phone );
}

/** Get the global hotline from one canonical option with a location fallback. */
function tfd_get_global_hotline( $location_id = 0 ) {
	$hotline = trim( (string) tfd_get_field( 'global_hotline', 'option' ) );
	return (string) apply_filters( 'tfd_global_hotline', $hotline, $location_id );
}

/** Translate a configured page ID to the current locale. */
function tfd_translate_post_id( $post_id, $post_type = 'page' ) {
	$post_id = absint( $post_id );
	if ( ! $post_id ) {
		return 0;
	}
	if ( has_filter( 'wpml_object_id' ) ) {
		return (int) apply_filters( 'wpml_object_id', $post_id, $post_type, true );
	}
	if ( function_exists( 'pll_get_post' ) ) {
		return (int) ( pll_get_post( $post_id ) ?: $post_id );
	}
	return $post_id;
}

/** Return the appointment page URL in the active language. */
function tfd_get_appointment_page_url( $location_id = 0 ) {
	$pages = get_option('tfd_pages',array());
	if(!empty($pages['appointment'][tfd_language()])) {
		$url = get_permalink($pages['appointment'][tfd_language()]);
		return $location_id ? add_query_arg('location',get_post_field('post_name',$location_id),$url) : $url;
	}
	$page = tfd_get_field( 'appointment_page', 'option' );
	if ( is_object( $page ) ) {
		$page = $page->ID;
	}
	$page_id = tfd_translate_post_id( (int) $page, 'page' );
	$url     = $page_id ? get_permalink( $page_id ) : '';
	if ( ! $url ) {
		foreach ( array( 'dat-lich', 'appointment' ) as $path ) {
			$candidate = get_page_by_path( $path );
			if ( $candidate ) {
				$url = get_permalink( tfd_translate_post_id( $candidate->ID, 'page' ) );
				break;
			}
		}
	}
	if ( $url && $location_id ) {
		$url = add_query_arg( 'location', get_post_field( 'post_name', $location_id ), $url );
	}
	return (string) apply_filters( 'tfd_appointment_page_url', $url, $location_id );
}

/** Return the trusted form shortcode configured by an administrator. */
function tfd_get_appointment_form_shortcode() {
	return trim( (string) apply_filters( 'tfd_appointment_form_shortcode', tfd_get_field( 'appointment_form_shortcode', 'option' ) ) );
}

/** Query published locations in the current language. */
function tfd_get_published_locations($lang = '') {
	$args = array(
		'post_type' => 'location', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'suppress_filters' => false,
	);
	if ( function_exists( 'pll_current_language' ) ) {
		$args['lang'] = $lang ?: tfd_language();
	}
	return get_posts( $args );
}

/** Resolve a valid location slug from the current page or appointment URL. */
function tfd_current_form_location_slug( $locations = null ) {
	if ( is_singular( 'location' ) ) {
		return get_post_field( 'post_name', get_queried_object_id() );
	}

	if ( empty( $_GET['location'] ) || !is_string($_GET['location']) ) {
		return '';
	}

	$requested = sanitize_title( wp_unslash( $_GET['location'] ) );
	$locations = is_array( $locations ) ? $locations : tfd_get_published_locations();
	foreach ( $locations as $location ) {
		if ( $location->post_name === $requested ) {
			return $requested;
		}
	}

	return '';
}

/** Populate a Contact Form 7 select named "location". Form tag: [select* location]. */
function tfd_cf7_location_dropdown( $tag ) {
	$was_array = is_array($tag);
	if($was_array) $tag=(object)$tag;
	if ( ! is_object( $tag ) || empty( $tag->name ) || 'location' !== $tag->name ) {
		return $was_array ? get_object_vars($tag) : $tag;
	}

	$form = class_exists('WPCF7_ContactForm') ? WPCF7_ContactForm::get_current() : null;
	$locale = $form ? substr($form->locale(),0,2) : tfd_language();
	$locations = tfd_get_published_locations($locale);
	$current_slug = tfd_current_form_location_slug( $locations );
	if ( $current_slug ) {
		usort( $locations, static function ( $a, $b ) use ( $current_slug ) { return ( $b->post_name === $current_slug ) <=> ( $a->post_name === $current_slug ); } );
	} else {
		array_unshift( $locations, null );
		if ( ! in_array( 'first_as_label', (array) $tag->options, true ) ) {
			$tag->options[] = 'first_as_label';
		}
	}

	$tag->raw_values = array();
	$tag->values     = array();
	$tag->labels     = array();
	$tag->pipes = new WPCF7_Pipes(array());
	foreach ( $locations as $location ) {
		if ( ! $location ) {
			$tag->raw_values[] = tfd_tr( 'select_location' ) . '|';
			$tag->values[] = '';
			$tag->labels[] = tfd_tr( 'select_location' );
			continue;
		}
		$slug = $location->post_name;
		$name = str_replace( '|', '-', get_the_title( $location ) );
		$tag->raw_values[] = $name . '|' . $slug;
		$tag->values[] = $slug;
		$tag->labels[] = $name;
	}
	return $was_array ? get_object_vars($tag) : $tag;
}
add_filter( 'wpcf7_form_tag', 'tfd_cf7_location_dropdown', 20 );

/** Populate a Fluent Forms select named "location". */
function tfd_fluent_forms_location_dropdown( $data, $form ) {
	$field_name = $data['attributes']['name'] ?? ( $data['settings']['name'] ?? '' );
	if ( 'location' !== $field_name ) {
		return $data;
	}

	$locations    = tfd_get_published_locations();
	$current_slug = tfd_current_form_location_slug( $locations );
	$options      = array();
	if ( ! $current_slug ) {
		$options[] = array( 'label' => tfd_tr( 'select_location' ), 'value' => '' );
	}
	if ( $current_slug ) {
		usort( $locations, static function ( $a, $b ) use ( $current_slug ) { return ( $b->post_name === $current_slug ) <=> ( $a->post_name === $current_slug ); } );
	}
	foreach ( $locations as $location ) {
		$options[] = array( 'label' => get_the_title( $location ), 'value' => $location->post_name );
	}
	$data['settings']['advanced_options'] = $options;
	if ( $current_slug ) {
		$data['attributes']['value'] = $current_slug;
	}
	return $data;
}
add_filter( 'fluentform/rendering_field_data_select', 'tfd_fluent_forms_location_dropdown', 20, 2 );

/** Convert ACF opening-hours rows to a predictable array. */
function tfd_get_opening_hours( $location_id ) {
	$rows   = tfd_get_field( 'opening_hours', $location_id );
	$result = array();
	if ( ! is_array( $rows ) ) {
		return $result;
	}
	foreach ( $rows as $key => $row ) {
		if(!is_array($row)) continue;
		$day = $row['day'] ?? (is_string($key)?$key:'');
		if ( is_array( $day ) ) {
			$day = $day['value'] ?? '';
		}
		$day = sanitize_key( $day );
		if ( ! in_array( $day, array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ), true ) ) {
			continue;
		}
		if(empty($row['is_closed']) && empty($row['opens']) && empty($row['closes']) && empty($row['note'])) continue;
		$result[] = array(
			'day' => $day, 'is_closed' => ! empty( $row['is_closed'] ),
			'opens' => preg_match( '/^\d{2}:\d{2}/', (string) ( $row['opens'] ?? '' ), $open_match ) ? $open_match[0] : '',
			'closes' => preg_match( '/^\d{2}:\d{2}/', (string) ( $row['closes'] ?? '' ), $close_match ) ? $close_match[0] : '',
			'note' => sanitize_text_field( $row['note'] ?? '' ),
		);
	}
	return $result;
}

/** Format a stored 24-hour value using the site's time format. */
function tfd_format_time( $time ) {
	$date = DateTimeImmutable::createFromFormat( '!H:i', (string) $time, new DateTimeZone( 'UTC' ) );
	return $date ? wp_date( get_option( 'time_format', 'H:i' ), $date->getTimestamp(), new DateTimeZone( 'UTC' ) ) : $time;
}

/** Output a location-specific Dentist/MedicalBusiness JSON-LD entity. */
function tfd_output_location_schema() {
	if ( ! is_singular( 'location' ) || ! apply_filters( 'tfd_enable_location_schema', true ) ) {
		return;
	}

	$location_id = get_queried_object_id();
	$url         = get_permalink( $location_id );
	$address     = tfd_get_location_address_parts( $location_id );
	$phone       = trim( (string) tfd_get_field( 'location_phone', $location_id ) );
	$email       = sanitize_email( tfd_get_field( 'location_email', $location_id ) );
	$latitude    = tfd_get_field( 'latitude', $location_id );
	$longitude   = tfd_get_field( 'longitude', $location_id );
	$map_url     = esc_url_raw( tfd_get_field( 'google_maps_url', $location_id ) );
	$embed_url   = tfd_normalize_map_embed_url( tfd_get_field( 'google_maps_embed_url', $location_id ) );
	$description = get_the_excerpt( $location_id );
	if ( ! $description ) {
		$description = wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $location_id ) ), 40 );
	}

	$schema = array(
		'@context' => 'https://schema.org', '@type' => 'Dentist', '@id' => $url . '#dentist',
		'name' => get_the_title( $location_id ), 'url' => $url, 'mainEntityOfPage' => $url, 'medicalSpecialty' => 'https://schema.org/Dentistry',
		'parentOrganization' => array( '@type' => 'Organization', 'name' => trim( (string) tfd_get_field( 'organization_name', 'option' ) ) ?: get_bloginfo( 'name' ), 'url' => home_url( '/' ) ),
	);
	if ( $description ) { $schema['description'] = $description; }
	if ( $phone ) { $schema['telephone'] = tfd_phone_href($phone); }
	if ( $email ) { $schema['email'] = $email; }

	$postal_address = array_filter(
		array(
			'@type' => 'PostalAddress', 'streetAddress' => trim( implode( ', ', array_filter( array( $address['street'] ?? '', $address['line_2'] ?? '' ) ) ) ),
			'addressLocality' => $address['city'] ?? '', 'addressRegion' => $address['state'] ?? '', 'postalCode' => $address['postal_code'] ?? '', 'addressCountry' => $address['country'] ?? 'US',
		),
		'strlen'
	);
	if ( !empty($postal_address['streetAddress']) && !empty($postal_address['addressLocality']) ) { $schema['address'] = $postal_address; }
	if ( is_numeric( $latitude ) && is_numeric( $longitude ) ) {
		$schema['geo'] = array( '@type' => 'GeoCoordinates', 'latitude' => (float) $latitude, 'longitude' => (float) $longitude );
	}
	if ( $map_url || $embed_url ) { $schema['hasMap'] = $map_url ?: $embed_url; }

	$images = array();
	if ( has_post_thumbnail( $location_id ) ) { $images[] = wp_get_attachment_image_url( get_post_thumbnail_id( $location_id ), 'full' ); }
	$gallery = tfd_get_field( 'facility_gallery', $location_id );
	if ( is_array( $gallery ) ) {
		foreach ( $gallery as $image ) {
			$image_id  = is_array( $image ) ? ( $image['ID'] ?? 0 ) : $image;
			$image_url = wp_get_attachment_image_url( (int) $image_id, 'full' );
			if ( $image_url ) { $images[] = $image_url; }
		}
	}
	$images = array_values( array_unique( array_filter( $images ) ) );
	if ( $images ) { $schema['image'] = $images; }

	$day_urls = array(
		'monday' => 'https://schema.org/Monday', 'tuesday' => 'https://schema.org/Tuesday', 'wednesday' => 'https://schema.org/Wednesday',
		'thursday' => 'https://schema.org/Thursday', 'friday' => 'https://schema.org/Friday', 'saturday' => 'https://schema.org/Saturday', 'sunday' => 'https://schema.org/Sunday',
	);
	$specifications = array();
	foreach ( tfd_get_opening_hours( $location_id ) as $row ) {
		if ( $row['is_closed'] ) {
			$specifications[] = array( '@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $day_urls[ $row['day'] ], 'opens' => '00:00', 'closes' => '00:00' );
			continue;
		}
		if ( $row['opens'] && $row['closes'] ) {
			$specifications[] = array( '@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $day_urls[ $row['day'] ], 'opens' => $row['opens'], 'closes' => $row['closes'] );
		}
	}
	if ( $specifications ) { $schema['openingHoursSpecification'] = $specifications; }

	echo "\n" . '<script type="application/ld+json" id="tfd-location-schema">' . wp_json_encode( $schema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'tfd_output_location_schema', 30 );

/** Load the progressive-enhancement script with defer. */
function tfd_enqueue_frontend_assets() {
	$script_path = get_stylesheet_directory() . '/assets/js/tfd-location.js';
	if ( ! file_exists( $script_path ) ) { return; }
	wp_enqueue_script( 'tfd-location', get_stylesheet_directory_uri() . '/assets/js/tfd-location.js', array(), (string) filemtime( $script_path ), true );
	wp_script_add_data( 'tfd-location', 'strategy', 'defer' );
}
add_action( 'wp_enqueue_scripts', 'tfd_enqueue_frontend_assets', 20 );

/** Determine whether the current page has at least one mobile action. */
function tfd_has_mobile_action_bar() {
	$location_id     = is_singular( 'location' ) ? get_queried_object_id() : 0;
	$hotline         = tfd_get_global_hotline( $location_id );
	$has_local_form  = $location_id && tfd_get_appointment_form_shortcode();
	$appointment_url = $has_local_form ? '#appointment-form' : tfd_get_appointment_page_url( $location_id );

	return (bool) ( $hotline || $appointment_url );
}

/** Add a body class only when the mobile action bar is rendered. */
function tfd_mobile_action_body_class( $classes ) {
	if ( ! is_admin() && tfd_has_mobile_action_bar() ) {
		$classes[] = 'has-tfd-mobile-actions';
	}
	return $classes;
}
add_filter( 'body_class', 'tfd_mobile_action_body_class' );

/** Output the mobile conversion bar on public pages. */
function tfd_render_mobile_action_bar() {
	if ( is_admin() || is_feed() ) { return; }
	$location_id     = is_singular( 'location' ) ? get_queried_object_id() : 0;
	$hotline         = tfd_get_global_hotline( $location_id );
	$has_local_form  = $location_id && tfd_get_appointment_form_shortcode();
	$appointment_url = $has_local_form ? '#appointment-form' : tfd_get_appointment_page_url( $location_id );
	if ( ! tfd_has_mobile_action_bar() ) { return; }
	?>
	<nav class="tfd-mobile-actions" aria-label="<?php echo esc_attr( tfd_tr( 'quick_booking' ) ); ?>">
		<?php if ( $hotline ) : ?>
			<a class="tfd-mobile-actions__item tfd-mobile-actions__item--call" href="tel:<?php echo esc_attr( tfd_phone_href( $hotline ) ); ?>">
				<span class="tfd-mobile-actions__icon" aria-hidden="true">☎</span><span><?php echo esc_html( tfd_tr( 'call_hotline' ) ); ?></span>
			</a>
		<?php endif; ?>
		<?php if ( $appointment_url ) : ?>
			<a class="tfd-mobile-actions__item tfd-mobile-actions__item--book" href="<?php echo esc_url( $appointment_url ); ?>">
				<span class="tfd-mobile-actions__icon" aria-hidden="true">▣</span><span><?php echo esc_html( tfd_tr( 'quick_booking' ) ); ?></span>
			</a>
		<?php endif; ?>
	</nav>
	<?php
}
add_action( 'wp_footer', 'tfd_render_mobile_action_bar', 30 );
