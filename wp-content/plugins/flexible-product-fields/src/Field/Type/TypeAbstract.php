<?php
/**
 * .
 *
 * @package WPDesk\FPF\Free
 */

namespace WPDesk\FPF\Free\Field\Type;

use WPDesk\FPF\Free\Field\Type\TypeInterface;

/**
 * Abstract class of field type.
 */
abstract class TypeAbstract implements TypeInterface {

	/**
	 * Returns value of field type used in HTML.
	 *
	 * @return string Field type.
	 */
	public function get_raw_field_type(): string {
		return $this->get_field_type();
	}

	/**
	 * Returns updated args of field.
	 *
	 * @param array $args Original field args.
	 *
	 * @return array Field args.
	 */
	public function get_field_args( array $args ): array {
		return $args;
	}

	/**
	 * Returns value of field.
	 *
	 * @param string $field_id Field ID.
	 * @param bool   $is_request Whether to use POST or REQUEST values.
	 *
	 * @return mixed Field value.
	 */
	public function get_field_value( string $field_id, bool $is_request = false ) {
		$form_data = ( $is_request ) ? $_REQUEST : $_POST; // phpcs:ignore
		if ( ! isset( $form_data[ $field_id ] ) ) {
			return null;
		}

		$posted_value = wp_unslash( $form_data[ $field_id ] );
		return sanitize_text_field( $posted_value );
	}

	/**
	 * Returns whether field type is available for plugin version.
	 *
	 * @return bool Status if field type is available.
	 */
	public function is_available(): bool {
		return false;
	}

	/**
	 * Returns whether option "Character Limit" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_required(): bool {
		return false;
	}

	/**
	 * Returns whether option "Character Limit" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_max_length(): bool {
		return false;
	}

	/**
	 * Returns whether option "Placeholder" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_placeholder(): bool {
		return false;
	}

	/**
	 * Returns whether option "Value" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_value(): bool {
		return false;
	}

	/**
	 * Returns whether option "CSS Class" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_css_class(): bool {
		return false;
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
		return false;
	}

	/**
	 * Returns whether option "Max value" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_value_max(): bool {
		return false;
	}

	/**
	 * Returns whether option "Step value" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_value_step(): bool {
		return false;
	}

	/**
	 * Returns whether option "Options" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_options(): bool {
		return false;
	}

	/**
	 * Returns whether option "Image label" for options is visible in field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_image_label_in_options(): bool {
		return false;
	}

	/**
	 * Returns whether option "Date format" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_date_format(): bool {
		return false;
	}

	/**
	 * Returns whether option "Days before" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_days_before(): bool {
		return false;
	}

	/**
	 * Returns whether option "Days after" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_days_after(): bool {
		return false;
	}

	/**
	 * Returns whether option "Price" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_price(): bool {
		return false;
	}

	/**
	 * Returns whether information about option "Price" is visible in field settings.
	 *
	 * @return bool Status to show information.
	 */
	public function has_price_info(): bool {
		return false;
	}

	/**
	 * Returns whether option "Price" for options is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_price_in_options(): bool {
		return false;
	}

	/**
	 * Returns whether information about option "Price" for options is visible in field settings.
	 *
	 * @return bool Status to show information.
	 */
	public function has_price_info_in_options(): bool {
		return false;
	}

	/**
	 * Returns whether option "Conditional logic" is available for field settings.
	 *
	 * @return bool Status if settings option is available.
	 */
	public function has_logic(): bool {
		return false;
	}

	/**
	 * Returns whether information about option "Conditional logic" is visible in field settings.
	 *
	 * @return bool Status to show information.
	 */
	public function has_logic_info(): bool {
		return false;
	}
}
