<?php
/**
 * Multi-select field template.
 *
 * This template can be overridden by copying it to yourtheme/flexible-product-fields/fields/multiselect.php
 *
 * @author  WP Desk
 * @package Flexible Product Fields/Templates
 * @version 1.0.0
 */

?>
<div class="fpf-field fpf-<?php echo esc_attr( $type ); ?>">
	<?php
	$args['return'] = true;
	$output = woocommerce_form_field( $key, $args, '' );
	$output = str_replace(
		'name="' . $key . '"',
		'name="' . $key . '[]"',
		$output
	);
	if ( $value && is_array( $value ) ) {
		foreach ( $value as $value_item ) {
			$output = str_replace(
				'<option value="' . $value_item . '"',
				'<option value="' . $value_item . '" selected',
				$output
			);
		}
	}
	echo $output; // phpcs:ignore
	?>
</div>
