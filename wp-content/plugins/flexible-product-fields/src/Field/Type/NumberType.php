<?php
/**
 * .
 *
 * @package WPDesk\FPF\Free
 */

namespace WPDesk\FPF\Free\Field\Type;

use WPDesk\FPF\Free\Field\Type\TypeAbstract;
use WPDesk\FPF\Free\Field\Type\TypeInterface;

/**
 * Supports "Number" field type.
 */
class NumberType extends TypeAbstract implements TypeInterface {

	const FIELD_TYPE = 'number';

	/**
	 * Returns value of field type.
	 *
	 * @return string Field type.
	 */
	public function get_field_type(): string {
		return self::FIELD_TYPE;
	}

	/**
	 * Returns label of field type.
	 *
	 * @return string Field label.
	 */
	public function get_field_type_label(): string {
		return __( 'Number', 'flexible-product-fields' );
	}

	/**
	 * Returns whether field type is available for plugin version.
	 *
	 * @return bool Status if field type is available.
	 */
	public function is_available(): bool {
		return true;
	}

	/**
	 * Returns whether option "Character Limit" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_required(): bool {
		return true;
	}

	/**
	 * Returns whether option "Placeholder" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_placeholder(): bool {
		return true;
	}

	/**
	 * Returns whether option "CSS Class" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_css_class(): bool {
		return true;
	}

	/**
	 * Returns whether option "Tooltip" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_tooltip(): bool {
		return true;
	}

	/**
	 * Returns whether option "Min value" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_value_min(): bool {
		return true;
	}

	/**
	 * Returns whether option "Max value" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_value_max(): bool {
		return true;
	}

	/**
	 * Returns whether option "Step value" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_value_step(): bool {
		return true;
	}

	/**
	 * Returns whether information about option "Price" is visible in field settings.
	 *
	 * @return bool Status to show information.
	 */
	public function has_price_info(): bool {
		return true;
	}

	/**
	 * Returns whether information about option "Conditional logic" is visible in field settings.
	 *
	 * @return bool Status to show information.
	 */
	public function has_logic_info(): bool {
		return true;
	}
}
