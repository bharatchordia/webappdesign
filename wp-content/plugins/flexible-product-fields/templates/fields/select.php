<?php
/**
 * Select field template.
 *
 * This template can be overridden by copying it to yourtheme/flexible-product-fields/fields/select.php
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
	$output = str_replace(
		'<option value="" ',
		'<option value="" disabled ',
		$output
	);
	echo $output; // phpcs:ignore
	?>
</div>
