<?php $target_blank = ! empty( $target_blank ) ? $target_blank : '';





if ( empty( $ignore_wrapper ) ) : ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php endif; ?>
    <div class="js-es-listing es-listing es-listing--<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>">
        <?php es_load_template( 'front/property/content-archive-image.php', array(
                'target_blank' => $target_blank,
                'wishlist_confirm' => ! empty( $wishlist_confirm ) ? $wishlist_confirm : null,
                'show_compare' => isset( $show_compare ) ? $show_compare : true,
        ) ); 
		
		// Get the related post ID from meta field
        $logo_post_id = get_post_meta(get_the_ID(), 'es_property_agency_id', true);		

        if (!empty($logo_post_id)) {
    $thumb_id = get_post_thumbnail_id($logo_post_id); // Get image ID
    $thumb_url = wp_get_attachment_image_url($thumb_id, 'full'); // Get image URL

    if (!empty($thumb_url)) {
		$thumb_url = '<a href="' . esc_url(get_permalink($logo_post_id)) . '"><img src="' . esc_url($thumb_url) . '" style="max-height:30px; width:auto;margin-right: 10px;" class="single-operator-logo" alt="Agency Logo"></a>';
    } else {
        $thumb_url ="";
    }
} else {
    $thumb_url ="";
}	
		
// Get the raw serialized meta value
$contact_info = get_post_meta( $logo_post_id, 'es_agency_contacts', true);

// Unserialize the data
$contacts = maybe_unserialize($contact_info);

// Check if data is an array and contains a phone number
$phone_number = '';
if (is_array($contacts) && isset($contacts[0]['phone']['tel'])) {
    $phone_number = esc_html($contacts[0]['phone']['tel']); // Get phone number
}
		
$email = get_post_meta( $logo_post_id, 'es_agency_email', true);
$org_phone_number="";
		
		
// Display the phone number
if (!empty($phone_number)) {
	$org_phone_number=$phone_number;
    $phone_number = '<a class="es-btn--request-info es-btn es-btn--primary js-es-scroll-to" href="tel:' . esc_attr($phone_number) . '">Call</a>';
} else {
    $phone_number ='';
}
?>
		
		
		
        <div class="es-listing__content">
            <div class="es-listing__content__inner">
                <div class="es-listing__content__left">
					<div style="display:inline-block;"><div class="single-operator-logo" style="display: inline-block;vertical-align: middle;"><?php echo $thumb_url ?></div><?php es_the_title( '<h3 class="es-listing__title" style="display: inline-block;vertical-align: middle;">
                        <a href="' . es_get_the_permalink() . '" ' . $target_blank . '>', '</a></h3>' ); ?></div>
                    <div class='es-badges es-listing--hide-on-list'>
                        <?php es_the_price();
                        es_the_field( 'price_note', '<span class="es-badge es-badge--normal">', '</span>' ); ?></div>
                    <?php es_the_address( '<div class="es-address es-listing--hide-on-grid">', '</div>' );
                    if ( get_the_excerpt() && ests( 'is_listing_description_enabled' ) ) : ?>
                        <p class="es-excerpt es-listing--hide-on-grid"><?php the_excerpt(); ?></p>
                    <?php endif;
                    do_action( 'es_property_meta', array( 'use_icons' => true ) );
                    es_the_address( '<div class="es-address es-listing--hide-on-list">', '</div>' ); ?>
                </div>
                <div class="es-listing__content__right es-listing--hide-on-grid">
                    <div class="es-property__control es-listing--hide-on-grid">
                        <?php do_action( 'es_property_control', array(
                            'show_sharing' => false,
                            'is_full' => false,
                            'icon_size' => 'big',
                            'context' => 'property-content',
                            'show_compare' => isset( $show_compare ) ? $show_compare : true,
                        ) ); ?>
                    </div>
                    <?php es_the_price(); ?>
                    <?php es_the_field( 'price_note', '<span class="es-badge es-badge--normal">', '</span>' ); ?>
                </div>
            </div>
            <div class="es-listing__footer">
                <?php es_load_template( 'front/property/partials/property-terms.php' ); ?>
				<style>.es-listing__terms.right-align{right: 15px;position: absolute;}.es-listing__terms.right-align li:after{width:0px;height:0px;}.es-listing__terms.right-align a{line-height: 36px;}</style>
				<!--<ul class="es-listing__terms right-align">
					<li><?php echo $phone_number; ?></li>
					<li><a id="quote-form-<?php the_ID(); ?>" href="#" class="es-btn--request-info es-btn es-btn--primary js-es-scroll-to quote-form-button">Quote</a></li>
				</ul>-->
				
				<ul class="es-listing__terms right-align" style="
    flex-direction: column;
">
<div style="
    margin-bottom: 10px;
    flex-direction: row;
    vertical-align: middle;
    display: flex;
    justify-content: center;
    align-items: center;
    align-content: center;
"><div style="
    display: flex;
    font-size: 12px;
    font-weight: 600;
    flex-direction: row;
    align-content: center;
    justify-content: center;
    align-items: center;
">Select For Bulk Quote</div><div style="
    display: flex;
    align-content: center;
    justify-content: center;
    align-items: center;
"><input agency-email="<?php echo $email; ?>" agency-phone="<?php echo $org_phone_number; ?>" agency-name="<?php echo get_the_title( $logo_post_id ); ?>" agency-bus-name="<?php echo get_the_title(); ?>" agency-id="<?php echo $logo_post_id; ?>" agency-bus-id="<?php echo the_ID(); ?>" class="bulk-quote-checkbox" id="checkbox-id-<?php the_ID(); ?>" type="checkbox" style="
    margin-left: 10px;
    width: 18px;
    height: 18px;
    border: 2px solid #4CAF50;
    border-radius: 4px;
    display: inline-block;
    position: relative;
    cursor: pointer;
"></div></div>	<div>				<li><?php echo $phone_number; ?></li>
					<li><a id="quote-form-<?php the_ID(); ?>" href="#" class="es-btn--request-info es-btn es-btn--primary js-es-scroll-to quote-form-button">Quote</a></li></div>
				</ul>
				
				
            </div>

            <?php do_action( 'es_after_property_content', get_the_ID() ); ?>
        </div>
    </div>
<?php if ( empty( $ignore_wrapper ) ) : ?>
    </div>
<?php endif;
