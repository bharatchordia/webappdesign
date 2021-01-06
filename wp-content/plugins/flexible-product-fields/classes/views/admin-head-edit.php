<script type="text/javascript">
    (function($) {
        $(document).ready(function(){
            var wrapper = $('<div class="inspire-settings"></div>');
            wrapper.append( $('<div class="inspire-main-content"></div>') );
            $("#posts-filter, .subsubsub").wrapAll( wrapper );

            var fpf_message_box = '<div class="stuffbox">';
            fpf_message_box += '<h3 class="hndle"><?php _e( 'Get Flexible Product Fields PRO', 'flexible-product-fields' ); ?></h3>';
            fpf_message_box += '<div class="inside">';
            fpf_message_box += '<ul>';
            fpf_message_box += '<li><span class="dashicons dashicons-yes"></span><?php _e( 'Conditional logic for fields', 'flexible-product-fields' ); ?></li>';
            fpf_message_box += '<li><span class="dashicons dashicons-yes"></span><?php _e( 'Add price to fields', 'flexible-product-fields' ); ?></li>';
            fpf_message_box += '<li><span class="dashicons dashicons-yes"></span><?php _e( 'New field: Date', 'flexible-product-fields' ); ?></li>';
            fpf_message_box += '<li><span class="dashicons dashicons-yes"></span><?php _e( 'New field: Heading', 'flexible-product-fields' ); ?></li>';
            fpf_message_box += '<li><span class="dashicons dashicons-yes"></span><?php _e( 'Assign field groups to all products', 'flexible-product-fields' ); ?></li>';
            fpf_message_box += '<li><span class="dashicons dashicons-yes"></span><?php _e( 'Assign field groups to categories', 'flexible-product-fields' ); ?></li>';
            fpf_message_box += '</ul>';
            fpf_message_box += '<p><a class="button button-primary" target="blank" href="<?php echo esc_url( apply_filters( 'flexible_product_fields/short_url', '#', 'fpf-settings-widget-upgrade-button' ) ); ?>"><?php _e( 'Upgrade now →', 'flexible-product-fields' ); ?></a></p>';
            fpf_message_box += '</div>';
            fpf_message_box += '</div>';


            var fpf_message_box_rate = '';
			<?php if ( $show_rate_it_metabox ) : ?>
                fpf_message_box_rate += '<div class="stuffbox">';
                fpf_message_box_rate += '<h3 class="hndle"><?php _e( 'Enjoying the free version? Rate it!', 'flexible-product-fields' ); ?></h3>';
                fpf_message_box_rate += '<div class="inside inside-rate">';
                <?php include( 'rate-it-content.php' ) ?>
                fpf_message_box_rate += '</div>';
                fpf_message_box_rate += '</div>';
            <?php endif; ?>

            var fpf_message_box2 = '<div class="stuffbox">';
            fpf_message_box2 += '<h3 class="hndle"><?php _e( 'Documentation', 'flexible-product-fields' ); ?></h3>';
            fpf_message_box2 += '<div class="inside">';
            <?php include( 'documentation-content.php' ) ?>
            fpf_message_box2 += '</div>';
            fpf_message_box2 += '</div>';

            $(".inspire-settings").append( $('<div class="inspire-sidebar metabox-holder">'+ fpf_message_box_rate + fpf_message_box2 + '</div>') );
            <?php if ( !is_flexible_products_fields_pro_active() ) : ?>
                $(".inspire-sidebar").prepend( $(fpf_message_box) );
	        <?php endif; ?>
        });

    })(jQuery);

</script>

<style type="text/css">
	#posts-filter p.search-box { display:none; }
</style>
