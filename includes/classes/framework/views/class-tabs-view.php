<?php

/**
 * Class Es_Tabs_View.
 */
class Es_Tabs_View extends Es_Framework_View {

	/**
	 * Es_Tabs_View constructor.
	 *
	 * @param $args
	 */
	public function __construct( $args ) {

		parent::__construct( $args );

		$default = array(
			'layout'   => 'vertical',
			'template' => Es_Framework::get_path() . 'templates' . DS . 'views' . DS . 'tabs.php',
			'nav_title' => '',
			'after_content_tabs' => '',
			'after_nav' => '',
			'ul_class' => '',
            'show_logo' => true,
            'before_content_tabs' => '',
            'use_data_attr_tab_id' => false,
		);

		$default['wrapper_nav_class'] = 'es-tabs__nav';
		$default['wrapper_tabs_class'] = 'es-tabs__wrapper';
		$default['wrapper_tab_class'] = 'es-tabs__content';
		$default['wrapper_notifications_class'] = 'es-notifications__wrapper';
		$default['wrapper_notifications_critical_class'] = 'es-notifications_critical__wrapper';
		$default['tab_link_wrapper'] = '<a class="es-tabs__nav-link" href="#{id}">{label}</a>';

		$this->_args = es_parse_args( $this->_args, $default );

		if ( empty( $this->_args['wrapper_class'] ) ) {
			$this->_args['wrapper_class'] = sprintf( "es-tabs es-tabs--%s", $this->_args['layout'] );
		}
	}

	/**
	 * Render tabs nav.
	 *
	 * @return void
	 */
	public function render_nav() {
		$config = $this->get_args(); ?>
		<ul class='<?php echo $config['ul_class']; ?>'>
			<?php foreach ( $config['tabs'] as $id => $item ) : ?>
                <li <?php echo ! empty( $item['li_attributes'] ) ? $item['li_attributes'] : ''; ?>>
					<?php if ( ! empty( $item['link_html'] ) ) : ?>
						<?php echo $item['link_html']; ?>
					<?php else : ?>
                        <a class="es-tabs__nav-link" data-tab="<?php echo '#' . $id; ?>" href="<?php echo $config['use_data_attr_tab_id'] ? '' : '#' . $id; ?>"><?php echo $item['label']; ?></a>
					<?php endif; ?>
                </li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	/**
	 * Render tabs notification.
	 *
	 * @return void
	 */
	public function render_notification() {
		global $post;
		$config = $this->get_args();

		if ( empty( $post ) ) {
			return false;
		}
		
		if ($post->post_author != 0 ) {
			if ( in_array( 'agent', get_userdata( $post->post_author )->roles ) && es_user_has_active_subscription( $post->post_author ) && current_user_can( 'manage_options' ) && $post->post_type == 'properties' ) : ?>
				<?php $subscription = es_get_user_subscription( $post->post_author ); ?>
					<?php if ( ! es_user_can_publish_listings (  $post->post_author  ) ) : ?>   
						<div class="<?php echo $config['wrapper_notifications_critical_class']; ?>">
							
							<img src="<?php echo es_public_img_path( 'icon-notification-alert-red.svg' ); ?>" alt="Notification"/>

							<span><?php _e('This author has reached their listings limit by subscription.', 'es'); ?></span>

						</div>
					<?php else : ?>
						<div class="<?php echo $config['wrapper_notifications_class']; ?>">

							<img src="<?php echo es_public_img_path( 'icon-notification-alert.svg' ); ?>" alt="Notification"/>
							
							<span><?php echo __(sprintf( 'Notice: The author of this post has a limit on posting. Currently, he has %s/%s posts and %s/%s posts marked as Featured.',
								$subscription->published_listings_count,
								$subscription->basic_listings_count + $subscription->featured_listings_count,
								$subscription->published_featured_listings_count,
								$subscription->featured_listings_count
							), 'es'); ?></span>

						</div>
					<?php endif; ?>
			<?php endif;
		}
	}

	/**
	 * Render tabs UI element.
	 */
	public function render() {

		/**
		 * @var $this Es_Tabs_View
		 */
		$config = $this->get_args(); ?>

		<div class="js-es-tabs <?php echo $config['wrapper_class']; ?>">
			<div class="js-es-tabs__nav <?php echo $config['wrapper_nav_class']; ?>">
				<div class="es-tabs__nav-inner">
					<?php if ( ! empty( $config['nav_title'] ) ) : ?>
						<h1><?php echo $config['nav_title']; ?></h1>
					<?php endif;

					$this->render_nav();

					echo $config['after_nav']; ?>
				</div>

				<?php if ( function_exists( 'pll_the_languages' ) && !is_admin() && isset( $_GET['property_id'] )) :
					$languages = pll_the_languages( array (
						'dropdown' => 0,
						'show_names' => 0,
						'hide_if_empty' => 0,
						'raw' => 1,
					) );

					if ( isset( $_GET['property_id'] ) ) {
						$property_id = intval( $_GET['property_id'] );
					} elseif ( ! empty ( $_GET['property_translation_id'] ) ) {
						$property_id = intval( $_GET['property_translation_id'] );
					}
                
					if ( isset( $_GET['property_translation_id'] ) && isset( $_GET['property_lang'] ) ) {
						$current_language = $_GET['property_lang'];
					} else {
						$current_language = pll_current_language();
					}

					if ( ! empty( $property_id ) ) {
						$post_translations = pll_get_post_translations( $property_id );
					}
					
					$page_id = ests( 'profile_page_id' );	

                if ( ! empty( $languages ) ) : ?>          
					<div class="es-translation-management__select-wrapper">
						<div class="es-translation-management__select-title"> <?php echo __( 'Choose listings language', 'es' ); ?></div>
						<div class="es-translation-management__select-subtitle"><?php echo __( 'Select a language to change the property listings' , 'es'); ?></div>
						<div class="es-translation-management__select">
							<div class="es-translation-management__select-trigger">
								<span class="es-translation-management__select-arrow">
										<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M12.8619 4.86194L13.8047 5.80475L8 11.6095L2.19527 5.80475L3.13808 4.86194L8 9.72387L12.8619 4.86194Z" fill="#263238"/>
										</svg>
								</span>
								<?php foreach ( $languages as $language ) : ?>
									<?php if ( $language['slug'] == $current_language ) : ?>
										<div class="es-translation-management__option" data-url="<?php echo esc_attr( $language['url'] ); ?>" data-lang="<?php echo esc_attr( $language['slug'] ); ?>'">
											<img src="<?php echo esc_attr( $language['flag'] ); ?>" alt="<?php echo esc_attr( $language['name'] ); ?> flag">
											<span><?php echo esc_html( $language['name'] ); ?></span>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
										
								<div class="es-translation-management__options">
									<?php foreach ( $languages as $language ) : ?>
										<?php if ( $language['slug'] != $current_language ) : 
											if ( $language['slug'] != pll_default_language() ) {
												$link_property = get_permalink ( pll_get_post( $page_id, $language['slug'] ) );
											} else {
												$link_property =  get_permalink ( pll_get_post($page_id, pll_default_language() ) );
											} ?>

											<?php if ( ! empty ( $post_translations[$language['slug']] ) ) : ?>
												<?php 
												$translate_code = $post_translations[$language['slug']]; 
												$link_property = add_query_arg( array(
													'screen' => 'edit-property',
													'property_id' => $property_id,
													'property_lang' => $language['slug'],
													'tab' => 'my-listings'
												), $link_property );
												?>
												<a target="_blank" href="<?php echo $link_property; ?>" class="es-translation-management__option" data-url="<?php echo esc_attr( $language['url'] ); ?>" data-lang="<?php echo esc_attr( $language['slug'] ); ?>">
												<img src="<?php echo esc_attr( $language['flag'] ) ?>" alt="<?php echo esc_attr( $language['name'] ) ?>' flag">
												<span><?php echo esc_html( $language['name'] ); ?></span></a>

											<?php elseif ( ! empty ( pll_get_post( $page_id, $language['slug'] ) ) ) :

												$link_property = add_query_arg( array(
													'screen' => 'add-new-property',
													'property_id' => $property_id,
													'property_lang' => $language['slug'],
													'tab' => 'my-listings'
												), $link_property );

												if (!empty ($property_id)) {
													$link_property = add_query_arg( 'property_translation_id', $property_id, $link_property  ); 
												} ?>    

												<a target="_blank" href="<?php echo $link_property; ?>" class="es-translation-management__option" data-url="<?php echo esc_attr( $language['url'] ); ?>" data-lang="<?php echo esc_attr( $language['slug'] ); ?>">
												<img src="<?php echo esc_attr( $language['flag'] ) ?>" alt="<?php echo esc_attr( $language['name'] ) ?>' flag">
												<span><?php echo esc_html( $language['name'] ); ?></span></a>
											<?php endif; ?>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; endif; ?>
			</div>
			<div class="js-es-tabs__wrapper <?php echo $config['wrapper_tabs_class']; ?>">
				<?php if ( $config['show_logo'] ) do_action( 'es_logo' ); ?>
				<?php if ( $this->render_notification() ) $this->render_notification(); ?>
					<?php if ( ! empty( $config['before_content_tabs'] ) ) : ?>
						<?php echo $config['before_content_tabs']; ?>
					<?php endif; ?>
					<?php foreach ( $config['tabs'] as $id => $item ) : ?>
						<div id="<?php echo $id; ?>" class="js-es-tabs__content es-hidden <?php echo $config['wrapper_tab_class']; ?>">
							<?php echo ! empty( $item['before'] ) ? $item['before'] : '';

							if ( ! empty( $item['template'] ) && file_exists( $item['template'] ) ) {
								include $item['template'];
							} else if ( ! empty( $item['action'] ) ) {
								do_action( $item['action'], $item, $id, $config );
							}

							echo ! empty( $item['after'] ) ? $item['after'] : ''; ?>
						</div>
					<?php endforeach; ?>

				<?php echo $config['after_content_tabs']; ?>
			</div>
		</div>
	<?php }
}
