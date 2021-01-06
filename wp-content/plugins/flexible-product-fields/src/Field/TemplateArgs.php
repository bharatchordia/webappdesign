<?php
/**
 * .
 *
 * @package WPDesk\FPF\Free
 */

namespace WPDesk\FPF\Free\Field;

use WPDesk\FPF\Free\Field\Type\TypeInterface;
use WPDesk\FPF\Free\Field\Type\RadioType;

/**
 * Generates args of fields for fields generation.
 */
class TemplateArgs {

	/**
	 * Returns args for field based on field settings.
	 *
	 * @param array         $settings Field settings.
	 * @param TypeInterface $type_object .
	 * @param array         $field Field settings.
	 * @param object        $product_price Class to handle pricing.
	 * @param \WC_Product   $product Product Class.
	 *
	 * @return array Field args.
	 */
	public function parse_field_args( array $settings, TypeInterface $type_object, array $field, $product_price, \WC_Product $product ): array {
		$args = [
			'type'              => $type_object->get_raw_field_type(),
			'label'             => $field['title'],
			'placeholder'       => '',
			'class'             => [],
			'input_class'       => [
				'fpf-input-field',
			],
			'custom_attributes' => [],
			'fpf_atts'          => [],
		];
		if ( $settings['has_tooltip'] && ( $tooltip = ( $field['tooltip'] ?? '' ) ) ) {
			$args['label'] = sprintf(
				'<span class="fpf-field-tooltip" title="%s">%s <span class="fpf-field-tooltip-icon"></span></span>',
				$tooltip,
				$args['label']
			);
		}
		if ( $settings['has_required'] && isset( $field['required'] ) && ( $field['required'] == '1' ) ) {
			$args['label'] .= sprintf(
				' <abbr class="required" title="%s">*</abbr>',
				__( 'Required field', 'flexible-product-fields' )
			);
			$args['class'][] = 'fpf-required';
		}
		if ( $settings['has_max_length'] && ( $max_length = ( $field['max_length'] ?? '' ) ) ) {
			$args['custom_attributes']['maxlength'] = $max_length;
		}
		if ( $settings['has_placeholder'] && ( $placeholder = ( $field['placeholder'] ?? '' ) ) ) {
			$args['placeholder'] = $placeholder;
		}
		if ( $settings['has_css_class'] && ( $css_class = ( $field['css_class'] ?? '' ) ) ) {
			$args['class'][] = $css_class;
		}
		if ( $settings['has_value_min'] && ( $value_min = ( $field['value_min'] ?? '' ) ) ) {
			$args['custom_attributes']['min'] = $value_min;
		}
		if ( $settings['has_value_max'] && ( $value_max = ( $field['value_max'] ?? '' ) ) ) {
			$args['custom_attributes']['max'] = $value_max;
		}
		if ( $settings['has_value_step'] && ( $value_step = ( $field['value_step'] ?? '' ) ) ) {
			$args['custom_attributes']['step'] = $value_step;
		}
		if ( $settings['has_options'] && ( $options = ( $field['options'] ?? [] ) ) && is_array( $options ) ) {
			$args['options'] = [];
			if ( $settings['has_placeholder'] && ( $placeholder = ( $field['placeholder'] ?? '' ) ) ) {
				$args['placeholder'] = '';
				$args['options'][''] = $placeholder;
			}

			foreach ( $options as $option ) {
				$args['options'][ $option['value'] ] = $option['label'];
			}
		}
		if ( $settings['has_image_label_in_options'] && ( $args['options'] ?? [] ) ) {
			$args['fpf_atts']['media_ids'] = [];
			foreach ( $field['options'] as $option ) {
				$args['fpf_atts']['media_ids'][] = (int) $option['image_id'];
			}
		}
		if ( $settings['has_date_format'] && ( $date_format = ( $field['date_format'] ?? '' ) ) ) {
			$args['custom_attributes']['date_format'] = $date_format;
			$args['custom_attributes']['days_before'] = '';
			$args['custom_attributes']['days_after']  = '';
		}
		if ( $settings['has_days_before'] ) {
			$days_before = ( $field['days_before'] ?? 0 );
			$args['custom_attributes']['days_before'] = ( $days_before != '0' ) ? $days_before : '00';
		}
		if ( $settings['has_days_after'] ) {
			$days_after = ( $field['days_after'] ?? 0 );
			$args['custom_attributes']['days_after'] = ( $days_after != '0' ) ? $days_after : '00';
		}
		if ( $settings['has_price'] && ( $price_value = ( $field['price'] ?? '' ) ) ) {
			$price_type     = $field['price_type'] ?? 'fixed';
			$args['label'] .= sprintf(
				' <span id="%s_price">(%s)</span>',
				$field['id'],
				$this->get_price_for_label( $price_type, $price_value, $product_price, $product )
			);
		}
		if ( $settings['has_price_in_options'] && ( $args['options'] ?? [] ) ) {
			foreach ( $args['options'] as $option_value => $option_label ) {
				$option_data = $this->get_option_data( $field['options'], $option_value );
				if ( ! $option_data
					|| ! ( $price_type = ( $option_data['price_type'] ?? '' ) )
					|| ! ( $price_value = ( $option_data['price'] ?? '' ) ) ) {
					continue;
				}

				if ( $field['type'] === RadioType::FIELD_TYPE ) {
					$args['options'][ $option_value ] .= sprintf(
						' <span id="%s_%s_price">(%s)</span>',
						$field['id'],
						$option_value,
						$this->get_price_for_label( $price_type, $price_value, $product_price, $product )
					);
				} else {
					$args['options'][ $option_value ] .= sprintf(
						' (%s)',
						$this->get_price_for_label( $price_type, $price_value, $product_price, $product )
					);
				}
			}
		}

		return $type_object->get_field_args( $args );
	}

	/**
	 * Returns selected option from options list.
	 *
	 * @param array  $options Field settings.
	 * @param string $option_value Option value.
	 *
	 * @return array|null Data of option, if exists.
	 */
	private function get_option_data( array $options, string $option_value ) {
		foreach ( $options as $option ) {
			if ( $option['value'] == $option_value ) {
				return $option;
			}
		}
		return null;
	}

	/**
	 * Returns price value for field label.
	 *
	 * @param string      $price_type Type of price (fixed, percent).
	 * @param mixed       $price_value Value of price.
	 * @param object      $product_price Class to handle pricing.
	 * @param \WC_Product $product Product Class.
	 *
	 * @return string Formatted price value.
	 */
	private function get_price_for_label( string $price_type, $price_value, $product_price, \WC_Product $product ): string {
		$price_raw     = $product_price->calculate_price( floatval( $price_value ), $price_type, $product );
		$price_display = $product_price->prepare_price_to_display( $product, $price_raw );
		return $product_price->wc_price( $price_display );
	}
}
