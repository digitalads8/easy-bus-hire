<?php

$property = es_get_property( get_the_ID() );
$fields = es_property_get_meta_fields();

if ( ! empty( $fields ) ) : ?><ul class="es-listing__meta"><?php
    foreach ( $fields as $field ) :
        if ( ! empty( $field['enabled'] ) && ! empty( $property->{$field['field']} )  ) :  ?>
            <li class="es-listing__meta-<?php echo $field['field']; ?>">
                <?php if ( ! empty( $use_icons ) ) : ?>
                    <?php if ( ! empty( $field['svg'] ) ) : ?>
                        <?php echo $field['svg']; ?>
                    <?php elseif ( ! empty( $field['icon'] ) ) : ?>
                        <img class="es-meta-icon" src="<?php echo $field['icon'] ?>" alt="<?php printf( _x( 'Property %s', 'property meta icon', 'es' ), $field['field'] ); ?>"/>
                    <?php endif; ?>
                <?php endif; ?>
                <span>
                    <?php if ( ! empty( $field['field_description'] ) ) : ?>
                        <b><?php es_the_field( $field['field'] ); ?></b>
                        <span><?php echo $field['field_description']; ?></span>
                    <?php else : ?>
                        <?php es_the_formatted_field( $field['field'] ); ?>
                    <?php endif; ?>
                </span>
            </li>
		<?php endif;
	endforeach;
	?></ul><?php
endif;
