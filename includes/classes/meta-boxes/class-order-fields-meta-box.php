<?php


/**
 * Class Es_Order_Fields_Meta_Box
 */
class Es_Order_Fields_Meta_Box extends Es_Entity_Fields_Meta_Box {



	/**
	 * @var string
	 */
	public static $render_field_callback = 'es_order_field_render';

	/**
	 * @return string
	 */
	public static function get_entity_name() {
		return 'order';
	}


	/**
	 * @return string
	 */
	public static function get_post_type_name() {
		return 'es_order';
	}


	/**
	 * @return string|void
	 */
	public static function get_metabox_title() {
		return __( 'Order information', 'es' );
	}
}

Es_Order_Fields_Meta_Box::init();