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
 * Supports "Heading" field type.
 */
class HeadingType extends TypeAbstract implements TypeInterface {

	const FIELD_TYPE = 'heading';

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
		return __( 'Heading', 'flexible-product-fields' );
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
	 * Returns whether information about option "Conditional logic" is visible in field settings.
	 *
	 * @return bool Status to show information.
	 */
	public function has_logic_info(): bool {
		return true;
	}
}
