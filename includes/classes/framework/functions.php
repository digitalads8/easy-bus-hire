<?php

if ( ! function_exists( 'es_parse_args' ) ) {

	/**
	 * Recursive function for parse array args.
	 *
	 * @param $args
	 * @param $defaults
	 *
	 * @return array
	 */
	function es_parse_args( &$args, $defaults ) {
		$args     = (array) $args;
		$defaults = (array) $defaults;
		$result   = $defaults;

		foreach ( $args as $k => &$v ) {
			if ( is_array( $v ) && isset( $result[ $k ] ) ) {
				$result[ $k ] = es_parse_args( $v, $result[ $k ] );
			} else {
				$result[ $k ] = $v;
			}
		}

		return $result;
	}
}

/**
 * Return icons list.
 *
 * @return mixed|void
 */
function esf_get_icons_list() {
	return apply_filters( 'esf_get_icons_list', array(
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_air-cond'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_balcony'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_bellhop'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_carbon-monoxide-detector'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_dishwasher'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_dryer'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_fire-alarm'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_fireplace'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_garbage-disposal'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_garden'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_heating'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_iron'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_jacuzzi'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_microwave'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_oven'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_pool'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_refrigerator'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_smoke-detector'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_terrace'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_trash-compactor'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_tv'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_wifi'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_pets'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_phone'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_printer'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_creditcard'></span>",
		),
		array(
			'type' => 'es-icon',
			'icon' => "<span class='es-icon es-icon_monitor'></span>",
		),
	) );
}

/**
 * @param $icons
 *
 * @return array
 */
function es_alter_icons_list( $icons ) {
	$attachments = get_posts( array(
		'post_type' => 'attachment',
		'post_status' => 'private',
		'posts_per_page' => -1,
		'meta_key' => 'esf_file_type',
		'meta_value' => 'icon-uploader',
		'post_mime_type' => 'image',
	) );

	if ( ! empty( $attachments ) ) {
		foreach ( $attachments as $attachment ) {
			$icons[ $attachment->post_name ] = array(
				'type' => 'es-custom-icon',
				'icon' => sprintf( "<span class='es-custom-icon es-custom-icon_%s' style='background-image: url(%s);'></span>", $attachment->post_name, wp_get_attachment_image_url( $attachment->ID, 'thumbnail' ) ),
			);
		}
	}

	return $icons;
}
add_filter( 'esf_get_icons_list', 'es_alter_icons_list' );
