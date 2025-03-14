<?php

/**
 * Class Es_Subscriptions_Orders_Archive_Page.
 */
class Es_Subscriptions_Orders_Archive_Page  extends Es_Entities_Archive_Page {

	/**
	 * Initialize requests archive page.
	 *
	 * @return void
	 */
	public static function init() {
		parent::init();

		add_filter( 'views_edit-es_order', array( __CLASS__, 'render_header' ) );
	}

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
	 * @param array $columns
	 *
	 * @return array|mixed
	 */
	public static function add_table_columns( $columns ) {
		unset( $columns['title'], $columns['date'], $columns['author'], $columns['cb'] );

		$columns['user_id'] = __( 'User', 'es' );
		$columns['listings_count'] = __( 'Listings count', 'es' );
		$columns['agencies_count'] = __( 'Agencies count', 'es' );
		$columns['payment_status'] = __( 'Payment status', 'es' );
		$columns['status'] = __( 'Subscription status', 'es' );
		$columns['start_time'] = __( 'Start time', 'es' );
		$columns['amount'] = __( 'Amount', 'es' );
		$columns['plan'] = __( 'Plan', 'es' );

		// Add date column on new position with new label.
		$columns = es_push_array_pos( array( 'actions' => '<span class="es-icon es-icon_settings"></span>' ), $columns, 100 );

		return $columns;
	}

	/**
	 * Render table column value.
	 *
	 * @param $column
	 * @param $post_id
	 */
	public static function add_table_columns_values( $column, $post_id ) {
		parent::add_table_columns_values( $column, $post_id );
		$order = es_get_order( $post_id );

		switch ( $column ) {
			case 'plan':
				if ( $order->is_subscription && ! empty ( $order->plan['ID'] ) ) {
					$plan = es_get_subscription_plan( $order->plan['ID'] );
					if ( ! empty( $plan->get_wp_entity() ) && ! empty (	$plan )  ) {
						$link = add_query_arg( array( 'tab' => 'plan-form', 'plan_id' => $order->plan['ID'] ), admin_url( 'admin.php?page=es_subscriptions' ) );
						echo sprintf( '<b>%s</b> (%s) <a href="%s" target="_blank"><span class="es-icon es-icon_pencil"></span></a><br>', $order->plan['name'], $order->get_period_label(), $link );
					} else {
						echo sprintf( '<b>%s</b> (%s)', $order->plan['name'], $order->get_period_label() );
					}
				} else {
					echo __( 'One time payment', 'es' );
				}
				break;
			case 'listings_count':
				printf( __( 'Basic: %s', 'es' ), '<b>' . (int) $order->basic_listings_count . '</b>' );
				echo "<br>";
				printf( __( 'Featured: %s', 'es' ), '<b>' . (int) $order->featured_listings_count . '</b>' );
				break;
			case 'agencies_count':
				printf( __( 'Agencies: %s', 'es' ), '<b>' . (int) $order->agencies_count . '</b>' );
				break;
			case 'start_time':
				echo date( ests( 'date_format' ) . ' ' . ests( 'time_format' ) . ':' . 'i:s', $order->{$column} );

				break;

			case 'amount':
				if ( stristr( $order->payment_method, 'paypal' ) ) : ?>
                    <img src="<?php echo es_public_img_path( 'paypal.svg' ); ?>" alt="PayPal logo"/><br>
				<?php endif;
				echo $order->amount ? es_format_value( $order->amount, 'price' ) : null;
				break;

			case 'status':
				echo $order->get_status();
				break;

			case 'payment_status':
				echo $order->get_payment_status();
				break;

			case 'user_id':
				$user = es_get_user_entity( $order->user_id );
				$name = $user->get_full_name() ? $user->get_full_name() : $user->get_email();
				$edit_link = $user instanceof Es_Agent_User ? get_edit_post_link( $user->post_id ) :  get_edit_user_link( $order->user_id );
				echo "<a href='{$edit_link}' target='_blank'>{$name}</a>";
				break;
			default:
				echo $order->{$column};
		}

		if ( 'actions' == $column ) :
			$post = get_post( $post_id );
			$title            = _draft_or_post_title();
			$post_type_object = get_post_type_object( $post->post_type );
			$can_edit_post    = current_user_can( 'edit_post', $post->ID ); ?>

            <div class="es-actions">
                <a href='#' class='es-more js-es-more'><span class='es-icon es-icon_more'></a>
                <div class="es-actions__dropdown">
                    <ul>
						<?php if ( is_post_type_viewable( $post_type_object ) ) {
							if ( in_array( $post->post_status, array( 'pending', 'draft', 'future' ) ) ) {
								if ( $can_edit_post ) {
									$preview_link    = get_preview_post_link( $post );
									printf(
										'<li><a href="%s" target="_blank" rel="bookmark" aria-label="%s">%s</a></li>',
										esc_url( $preview_link ),
										/* translators: %s: Post title. */
										esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;' ), $title ) ),
										__( 'Preview' )
									);
								}
							} elseif ( 'trash' != $post->post_status ) {
								printf(
									'<li><a href="%s" target="_blank" rel="bookmark" aria-label="%s">%s</a></li>',
									get_permalink( $post_id ),
									/* translators: %s: Post title. */
									esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $title ) ),
									__( 'View' )
								);
							}
						} ?>
						<?php if ( $can_edit_post ) : ?>
                            <li>
								<?php printf( '<a href="%s" aria-label="%s">%s</a>',
									get_edit_post_link( $post_id ),
									esc_attr( sprintf( __( 'Edit %s order', 'es' ), $title ) ),
									__( 'Edit order', 'es' )
								); ?>
                            </li>
						<?php endif; ?>

	                    <?php if ( current_user_can( 'delete_post', $post_id ) ) : ?>
	                    <?php endif; ?>
                    </ul>
                </div>
            </div>

		<?php endif;
	}

	/**
	 * Properties list query filter.
	 *
	 * @param $query WP_Query
	 */
	public static function filter_query( $query ) {
		if ( ! $query->is_main_query() ) {
			return;
		}

		$meta_query = array();

		if ( ! empty( $_GET['entities_filter'] ) ) {
			$filter = es_clean( $_GET['entities_filter'] );

            if ( ! empty( $filter['plan_id'] ) ) {
                if ( $filter['plan_id'] == 'one_time_payment' ) {
	                $meta_query[] = array(
		                'key' => 'es_order_is_subscription',
		                'value' => 1,
                        'compare' => '!='
	                );
                } else {
	                $meta_query[] = array(
		                'key' => 'es_order_product_id',
		                'value' => $filter['plan_id']
	                );
                }
            }

            if ( ! empty( $filter['status'] ) ) {
	            $meta_query[] = array(
		            'key' => 'es_order_status',
		            'value' => $filter['status']
	            );
            }

            if ( ! empty( $filter['start_date_from'] ) ) {
	            $meta_query[] = array(
		            'key' => 'es_order_start_time',
		            'value' => strtotime( $filter['start_date_from'] ),
                    'compare' => '>='
	            );
            }

			if ( ! empty( $filter['start_date_to'] ) ) {
				$meta_query[] = array(
					'key' => 'es_order_start_time',
					'value' => strtotime( $filter['start_date_to'] ),
					'compare' => '<='
				);
			}

			if ( ! empty( $filter['s'] ) ) {
				$meta_query['keywords'] = array(
					'key' => 'es_order_keywords',
					'value' => $filter['s'],
					'compare' => 'LIKE'
				);
			}
		}

        if ( $meta_query ) {
            $query->set( 'meta_query', $meta_query );
        }
	}

	/**
	 * @param $views
	 *
	 * @return mixed
	 */
	public static function render_header( $views ) {
		es_load_template( 'admin/subscriptions-orders/header.php' );

		$f = es_framework_instance();
		$f->load_assets();

		$entity = es_get_entity( static::get_entity_name() );
		if ( $entity::count() ) {
			es_load_template( 'admin/subscriptions-orders/filter.php' );
		} else {
			es_load_template( 'admin/partials/empty-archive.php', array(
				'entity_name' => static::get_entity_name(),
				'post_type' => static::get_post_type_name(),
				'can_add_new' => false
			) );
		}

		return $views;
	}
}

Es_Subscriptions_Orders_Archive_Page::init();
