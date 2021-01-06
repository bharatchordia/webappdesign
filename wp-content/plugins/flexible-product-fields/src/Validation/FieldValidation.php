<?php
/**
 * .
 *
 * @package WPDesk\FPF\Free
 */

namespace WPDesk\FPF\Free\Validation;

use WPDesk\FPF\Free\Validation\Rule\RuleInterface;
use WPDesk\FPF\Free\Validation\Rule\RequiredRule;
use WPDesk\FPF\Free\Validation\Rule\LengthRule;

/**
 * Initializes integration of validation rules for fields.
 */
class FieldValidation {

	/**
	 * Class objects for validation rules.
	 *
	 * @var RuleInterface[]
	 */
	private $validation_rules = [];

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->validation_rules[] = new RequiredRule();
		$this->validation_rules[] = new LengthRule();
	}

	/**
	 * Checks if the field value is correct.
	 *
	 * @param array $field_data Field settings.
	 * @param array $field_type Config for field data.
	 * @param mixed $value Value of field.
	 *
	 * @return bool|string True if value is correct or error message.
	 */
	public function validate_value( array $field_data, array $field_type, $value ) {
		foreach ( $this->validation_rules as $validation_rule ) {
			if ( ! $validation_rule->validate_value( $field_data, $field_type, $value ) ) {
				return $validation_rule->get_error_message( $field_data );
			}
		}
		return true;
	}
}
