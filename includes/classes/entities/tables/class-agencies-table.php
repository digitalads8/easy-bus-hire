<?php

/**
 * Class Es_Agencies_Table
 */
class Es_Agencies_Table extends Es_Entities_Table {

	/**
	 * @return array|mixed|void
	 */
	public static function get_table_columns_fields() {
		$fields = array(
			'_manage-checkbox',
			'ID',
			'post_title',
			'Active',
			'Properties Qty',
			'rating',
			'reviews_count',
			'post_status',
			'_manage-buttons'
		);

		return apply_filters( sprintf( 'es_%s_get_table_columns_fields', static::get_entity_type() ), $fields );
	}

	/**
	 * @return string
	 */
	public static function get_entity_type() {
		return 'agency';
	}
}
