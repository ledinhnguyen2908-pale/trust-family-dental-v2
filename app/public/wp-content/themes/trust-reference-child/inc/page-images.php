<?php
/** Client-editable page images powered by ACF. */
defined( 'ABSPATH' ) || exit;

function tfr_page_image_definitions() {
	return array(
		'home' => array(
			array( 'name' => 'tfr_page_image_1', 'label' => 'Ảnh banner trang chủ', 'kind' => 'content' ),
			array( 'name' => 'tfr_page_image_2', 'label' => 'Ảnh phòng điều trị', 'kind' => 'content' ),
		),
		'about' => array(
			array( 'name' => 'tfr_page_image_1', 'label' => 'Ảnh giới thiệu bác sĩ', 'kind' => 'content' ),
			array( 'name' => 'tfr_page_image_2', 'label' => 'Ảnh phòng điều trị', 'kind' => 'content' ),
			array( 'name' => 'tfr_team_image_1', 'label' => 'Ảnh thành viên đội ngũ 1', 'kind' => 'team' ),
			array( 'name' => 'tfr_team_image_2', 'label' => 'Ảnh thành viên đội ngũ 2', 'kind' => 'team' ),
			array( 'name' => 'tfr_team_image_3', 'label' => 'Ảnh thành viên đội ngũ 3', 'kind' => 'team' ),
			array( 'name' => 'tfr_team_image_4', 'label' => 'Ảnh thành viên đội ngũ 4', 'kind' => 'team' ),
		),
		'lp1' => array(
			array( 'name' => 'tfr_page_image_1', 'label' => 'Ảnh banner trang Same-day care', 'kind' => 'content' ),
			array( 'name' => 'tfr_page_image_2', 'label' => 'Ảnh bác sĩ hoặc phòng khám', 'kind' => 'content' ),
		),
		'lp2' => array(
			array( 'name' => 'tfr_page_image_1', 'label' => 'Ảnh banner trang Restorative', 'kind' => 'content' ),
			array( 'name' => 'tfr_page_image_2', 'label' => 'Ảnh phòng điều trị', 'kind' => 'content' ),
		),
		'lp3' => array(
			array( 'name' => 'tfr_page_image_1', 'label' => 'Ảnh banner trang Family', 'kind' => 'content' ),
			array( 'name' => 'tfr_page_image_2', 'label' => 'Ảnh phòng điều trị', 'kind' => 'content' ),
			array( 'name' => 'tfr_team_image_1', 'label' => 'Ảnh thành viên đội ngũ 1', 'kind' => 'team' ),
			array( 'name' => 'tfr_team_image_2', 'label' => 'Ảnh thành viên đội ngũ 2', 'kind' => 'team' ),
			array( 'name' => 'tfr_team_image_3', 'label' => 'Ảnh thành viên đội ngũ 3', 'kind' => 'team' ),
			array( 'name' => 'tfr_team_image_4', 'label' => 'Ảnh thành viên đội ngũ 4', 'kind' => 'team' ),
		),
	);
}

function tfr_register_page_image_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$pages = (array) get_option( 'tfr_pages', array() );
	foreach ( tfr_page_image_definitions() as $role => $slots ) {
		$location = array();
		foreach ( array( 'en', 'es' ) as $lang ) {
			if ( ! empty( $pages[ $role ][ $lang ] ) ) {
				$location[] = array( array( 'param' => 'page', 'operator' => '==', 'value' => (int) $pages[ $role ][ $lang ] ) );
			}
		}
		if ( ! $location ) {
			continue;
		}

		$fields = array();
		foreach ( $slots as $index => $slot ) {
			$key_base = 'field_tfr_' . $role . '_' . sanitize_key( $slot['name'] );
			$fields[] = array(
				'key' => $key_base,
				'label' => $slot['label'],
				'name' => $slot['name'],
				'type' => 'image',
				'instructions' => 'Bấm Add Image hoặc Replace Image. Nên dùng WebP/JPG, rộng tối thiểu 1200px và dung lượng dưới 500KB.',
				'return_format' => 'id',
				'preview_size' => 'medium',
				'library' => 'all',
				'wrapper' => array( 'width' => 50 ),
			);
			$fields[] = array(
				'key' => $key_base . '_alt',
				'label' => 'Mô tả SEO cho ' . $slot['label'],
				'name' => $slot['name'] . '_alt',
				'type' => 'text',
				'instructions' => 'Mô tả ngắn nội dung ảnh. Có thể viết riêng cho English và Español.',
				'maxlength' => 160,
				'wrapper' => array( 'width' => 50 ),
			);
		}

		acf_add_local_field_group( array(
			'key' => 'group_tfr_page_images_' . $role,
			'title' => 'Hình ảnh trang — thay tại đây',
			'fields' => $fields,
			'location' => $location,
			'position' => 'acf_after_title',
			'style' => 'seamless',
			'instruction_placement' => 'label',
			'active' => true,
			'description' => 'Không cần sửa HTML hoặc mở UX Builder. Ảnh được đồng bộ giữa EN/ES; mô tả SEO được giữ riêng theo ngôn ngữ.',
		) );
	}
}
add_action( 'acf/init', 'tfr_register_page_image_fields', 30 );

/** Keep the selected image shared between EN/ES while leaving alt text translatable. */
function tfr_sync_page_image_between_languages( $value, $post_id, $field ) {
	static $syncing = false;
	if ( $syncing || ! is_numeric( $post_id ) || ! str_starts_with( $field['name'] ?? '', 'tfr_' ) || ! str_contains( $field['name'], '_image_' ) ) {
		return $value;
	}
	$role = get_post_meta( (int) $post_id, 'tfr_role', true );
	$pair = (array) ( get_option( 'tfr_pages', array() )[ $role ] ?? array() );
	$other_id = 0;
	foreach ( array( 'en', 'es' ) as $lang ) {
		if ( ! empty( $pair[ $lang ] ) && (int) $pair[ $lang ] !== (int) $post_id ) {
			$other_id = (int) $pair[ $lang ];
			break;
		}
	}
	if ( $other_id ) {
		$syncing = true;
		update_field( $field['key'], $value, $other_id );
		$syncing = false;
	}
	return $value;
}
add_filter( 'acf/update_value/type=image', 'tfr_sync_page_image_between_languages', 20, 3 );

function tfr_page_image_markup( $attachment_id, $alt, $eager = false ) {
	$attachment_id = (int) $attachment_id;
	if ( ! $attachment_id || ! wp_attachment_is_image( $attachment_id ) ) {
		return '';
	}
	if ( '' === trim( (string) $alt ) ) {
		$alt = (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
	}
	$attrs = array(
		'class' => 'attachment-full size-full tfr-editable-image',
		'alt' => trim( wp_strip_all_tags( (string) $alt ) ),
		'decoding' => 'async',
		'loading' => $eager ? 'eager' : 'lazy',
	);
	if ( $eager ) {
		$attrs['fetchpriority'] = 'high';
	}
	return wp_get_attachment_image( $attachment_id, 'full', false, $attrs );
}

function tfr_render_client_page_images( $html ) {
	if ( ! is_singular( 'page' ) || ! in_the_loop() || ! is_main_query() ) {
		return $html;
	}
	$post_id = get_the_ID();
	$role = get_post_meta( $post_id, 'tfr_role', true );
	$definitions = tfr_page_image_definitions()[ $role ] ?? array();
	if ( ! $definitions ) {
		return $html;
	}

	$content_slots = array_values( array_filter( $definitions, static fn( $slot ) => 'content' === $slot['kind'] ) );
	$content_index = 0;
	$html = preg_replace_callback( '/<img\b[^>]*>/i', static function ( $match ) use ( $post_id, $content_slots, &$content_index ) {
		if ( ! isset( $content_slots[ $content_index ] ) ) {
			return $match[0];
		}
		$slot = $content_slots[ $content_index ];
		$image_id = (int) get_post_meta( $post_id, $slot['name'], true );
		$alt = (string) get_post_meta( $post_id, $slot['name'] . '_alt', true );
		$replacement = tfr_page_image_markup( $image_id, $alt, 0 === $content_index );
		$content_index++;
		return $replacement ?: $match[0];
	}, $html );

	$team_slots = array_values( array_filter( $definitions, static fn( $slot ) => 'team' === $slot['kind'] ) );
	$team_index = 0;
	$html = preg_replace_callback( '#<div class="ph"><div>(?:Team photo|Foto del equipo)</div></div>#i', static function ( $match ) use ( $post_id, $team_slots, &$team_index ) {
		if ( ! isset( $team_slots[ $team_index ] ) ) {
			return $match[0];
		}
		$slot = $team_slots[ $team_index++ ];
		$image_id = (int) get_post_meta( $post_id, $slot['name'], true );
		$alt = (string) get_post_meta( $post_id, $slot['name'] . '_alt', true );
		$markup = tfr_page_image_markup( $image_id, $alt, false );
		return $markup ? '<div class="ph tfr-team-photo">' . $markup . '</div>' : $match[0];
	}, $html );

	return $html;
}
add_filter( 'the_content', 'tfr_render_client_page_images', 20 );

