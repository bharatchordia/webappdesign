<?php
/**
 * .
 *
 * @package WPDesk\FPF\Free
 */

namespace WPDesk\FPF\Free\Field\Type;

use WPDesk\FPF\Free\Field\Type\TypeAbstract;
use WPDesk\FPF\Free\Field\Type\TypeInterface;
use WPDesk\FPF\Free\Field\Type\RadioType;

/**
 * Supports "Radio with images" field type.
 */
class RadioImagesType extends TypeAbstract implements TypeInterface {

	const FIELD_TYPE = 'radio-images';

	/**
	 * Returns value of field type.
	 *
	 * @return string Field type.
	 */
	public function get_field_type(): string {
		return self::FIELD_TYPE;
	}

	/**
	 * Returns value of field type used in HTML.
	 *
	 * @return string Field type.
	 */
	public function get_raw_field_type(): string {
		return RadioType::FIELD_TYPE;
	}

	/**
	 * Returns label of field type.
	 *
	 * @return string Field label.
	 */
	public function get_field_type_label(): string {
		return __( 'Radio with images', 'flexible-product-fields' );
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
	 * Returns whether option "Options" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_options(): bool {
		return true;
	}

	/**
	 * Returns whether option "Image label" for options is visible in field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_image_label_in_options(): bool {
		return true;
	}

	/**
	 * Returns whether information about option "Price" for options is visible in field settings.
	 *
	 * @return bool Status to show information.
	 */
	public function has_price_info_in_options(): bool {
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
