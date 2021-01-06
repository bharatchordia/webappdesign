<?php
/**
 * Number field template.
 *
 * This template can be overridden by copying it to yourtheme/flexible-product-fields/fields/number.php
 *
 * @author  WP Desk
 * @package Flexible Product Fields/Templates
 * @version 1.0.0
 */

?>
<div class="fpf-field fpf-<?php echo esc_attr( $type ); ?>">
	<?php woocommerce_form_field( $key, $args, $value ); ?>
</div>
