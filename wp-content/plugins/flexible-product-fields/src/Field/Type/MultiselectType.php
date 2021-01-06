<?php
/**
 * .
 *
 * @package WPDesk\FPF\Free
 */

namespace WPDesk\FPF\Free\Field\Type;

use WPDesk\FPF\Free\Field\Type\TypeAbstract;
use WPDesk\FPF\Free\Field\Type\TypeInterface;
use WPDesk\FPF\Free\Field\Type\SelectType;

/**
 * Supports "Multi-select" field type.
 */
class MultiselectType extends TypeAbstract implements TypeInterface {

	const FIELD_TYPE = 'multiselect';

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
		return SelectType::FIELD_TYPE;
	}

	/**
	 * Returns updated args of field.
	 *
	 * @param array $args Original field args.
	 *
	 * @return array Field args.
	 */
	public function get_field_args( array $args ): array {
		$args['custom_attributes']['multiple'] = 'multiple';
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
		$field_id  = str_replace( '[]', '', $field_id );
		if ( ! isset( $form_data[ $field_id ] ) ) {
			return null;
		}

		$posted_values = wp_unslash( $form_data[ $field_id ] );
		$field_values  = [];
		foreach ( $posted_values as $posted_value ) {
			$field_values[] = sanitize_textarea_field( $posted_value );
		}
		return $field_values;
	}

	/**
	 * Returns label of field type.
	 *
	 * @return string Field label.
	 */
	public function get_field_type_label(): string {
		return __( 'Multi-select', 'flexible-product-fields' );
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
