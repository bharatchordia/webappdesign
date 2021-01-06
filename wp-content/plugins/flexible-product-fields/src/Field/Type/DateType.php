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
 * Supports "Date" field type.
 */
class DateType extends TypeAbstract implements TypeInterface {

	const FIELD_TYPE = 'fpfdate';

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
		return __( 'Date', 'flexible-product-fields' );
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
	 * Returns whether option "Date format" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_date_format(): bool {
		return true;
	}

	/**
	 * Returns whether option "Days before" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_days_before(): bool {
		return true;
	}

	/**
	 * Returns whether option "Days after" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_days_after(): bool {
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
