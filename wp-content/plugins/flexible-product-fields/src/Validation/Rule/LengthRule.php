<?php
/**
 * .
 *
 * @package WPDesk\FPF\Free
 */

namespace WPDesk\FPF\Free\Validation\Rule;

use WPDesk\FPF\Free\Validation\Rule\RuleAbstract;
use WPDesk\FPF\Free\Validation\Rule\RuleInterface;

/**
 * Supports "Max length" validation rule for fields.
 */
class LengthRule extends RuleAbstract implements RuleInterface {

	/**
	 * Checks if the field value is correct.
	 *
	 * @param array $field_data Field settings.
	 * @param array $field_type Config for field data.
	 * @param mixed $value Value of field.
	 *
	 * @return bool Status of field value.
	 */
	public function validate_value( array $field_data, array $field_type, $value ): bool {
		if ( ! $field_type['has_max_length'] || ! $field_data['max_length'] ) {
			return true;
		}

		return ( strlen( $value ) <= intval( $field_data['max_length'] ) );
	}

	/**
	 * Returns error message for validation rule.
	 *
	 * @param array $field_data Field settings.
	 *
	 * @return string Error message.
	 */
	public function get_error_message( array $field_data ): string {
		return sprintf(
			/* translators: %1$s: field label, %2$s: limit of chars */
			__( 'Exceeded maximum number of characters for the %1$s field. (max: %2$s)', 'flexible-product-fields' ),
			$field_data['title'],
			$field_data['max_length']
		);
	}
}
