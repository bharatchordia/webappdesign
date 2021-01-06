<?php
/**
 * Radio with images field template.
 *
 * This template can be overridden by copying it to yourtheme/flexible-product-fields/fields/radio-images.php
 *
 * @author  WP Desk
 * @package Flexible Product Fields/Templates
 * @version 1.0.0
 */

?>
<div class="fpf-field fpf-<?php echo esc_attr( $type ); ?>">
	<?php
	$args['return'] = true;
	$output = woocommerce_form_field( $key, $args, $value );
	$index  = 0;
	$output = preg_replace_callback(
		'/(<label(?:(?!>).)+>)((?:(?!<\/label>).)+)(<\/label>)/',
		function( $matches ) use ( &$index, $args ) {
			$index++;
			if ( 1 === $index ) {
				return $matches[0];
			}
			return sprintf(
				'%s %s <span>%s</span> %s',
				$matches[1],
				wp_get_attachment_image( $args['fpf_atts']['media_ids'][ ( $index - 2 ) ] ),
				$matches[2],
				$matches[3]
			);
		},
		$output
	);
	echo $output; // phpcs:ignore
	?>
</div>
