<div class="es-wrap">
	<div class="es-head">
		<h1><?php _e( 'Orders', 'es' ); ?></h1>
		<a href="<?php echo admin_url( 'post-new.php?post_type=es_order' ); ?>"
			class="es-btn es-btn--secondary es-btn--icon">
			<span class="es-icon es-icon_plus"></span>
			<?php _e( 'Add new order', 'es' ); ?>
		</a>
		<div class="es-head__logo">
			<?php do_action( 'es_logo' ); ?>
		</div>
	</div>
</div>