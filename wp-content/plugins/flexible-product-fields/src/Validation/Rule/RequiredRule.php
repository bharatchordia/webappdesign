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
 * Supports "Required" validation rule for fields.
 */
class RequiredRule extends RuleAbstract implements RuleInterface {

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
		if ( ! $field_data['required'] ) {
			return true;
		}

		return ( ( $value !== null ) && ! empty( $value ) );
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
			/* translators: %s: field label */
			__( '<span><strong>%s</strong> is required field.</span>', 'flexible-product-fields' ),
			$field_data['title']
		);
	}
}
