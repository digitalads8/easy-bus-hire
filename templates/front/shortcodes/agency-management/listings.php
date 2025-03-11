<?php

if ( get_query_var( 'paged' ) ) {
	$page_num = get_query_var( 'paged' );
} elseif ( get_query_var( 'page' ) ) {
	$page_num = get_query_var( 'page' );
} else {
	$page_num = 1;
}

$search_agencies = es_get_shortcode_instance( 'es_my_agencies' );
$query_args = $search_agencies->get_query_args();
$query_args['post_status'] = 'any';
$query_args['paged'] = $page_num;

if ( ! current_user_can( 'edit_others_agencies' ) ) {
	$query_args['author'] = get_current_user_id();
}

$instance = es_get_entities_table_instance( 'agency', array(
	'query_args' => $query_args,
) );

$query_args = $instance->get_wp_query_args();

$query = new WP_Query( $query_args );
$flashes = es_get_flash_instance( 'prop-management' );

if ( $query->have_posts() || es_get( 'search_context' ) == 'pm' ) : ?>
    <div class="es-wrap">
        <div class="es-agency-management es-agency-management--list content-font">
            <?php 
            include es_locate_template( 'front/shortcodes/agency-management/filter.php' );
            include es_locate_template( 'front/shortcodes/agency-management/bulk-actions.php' );
            $flashes->render_messages();
            $instance->render(); ?>
        </div>
    </div>
<?php else : ?>
    <p class="es-subtitle"><?php _e( 'You don’t have any agency yet', 'es' ); ?></p>
	<?php if ( ( $url = es_get_add_new_agency_url() ) && ests( 'is_frontend_management_enabled' ) ) : ?>
        <p><?php _e( 'Fill the form to add new agency now.', 'es' ); ?></p>
        <a href="<?php echo add_query_arg( 'screen', 'add-new-agency' ); ?>" class="es-btn es-btn--secondary">
            <span class="es-icon es-icon_plus"></span>
            <?php _e( 'Add new agency', 'es' ); ?>
        </a>
    <?php endif; ?>
<?php endif;
