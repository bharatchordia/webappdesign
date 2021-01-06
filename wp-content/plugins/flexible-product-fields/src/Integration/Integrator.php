<?php
/**
 * .
 *
 * @package WPDesk\FPF\Free
 */

namespace WPDesk\FPF\Free\Integration;

use WPDesk\FPF\Free\Integration\IntegratorInterface;

/**
 * .
 */
class Integrator implements IntegratorInterface {

	/**
	 * Version of integration script.
	 *
	 * @var string
	 */
	private $version = FLEXIBLE_PRODUCT_FIELDS_VERSION;

	/**
	 * Version of plugin core (for compatibility with dependent plugins).
	 *
	 * @var string
	 */
	private $version_dev = FLEXIBLE_PRODUCT_FIELDS_VERSION_VERSION_DEV;

	/**
	 * Returns version of integration script.
	 *
	 * @example Use method to integration with plugin.
	 *
	 * @return string Integration script version.
	 */
	public function get_version(): string {
		$version_dev_major = explode( '.', $this->version_dev )[0];
		$version_dev_minor = explode( '.', $this->version_dev )[1];
		$version_major     = explode( '.', $this->version )[0];
		$version_minor     = explode( '.', $this->version )[1];
		$version_patch     = explode( '.', $this->version )[2];

		return sprintf(
			'%d.%d.%d',
			( ( $version_dev_major * 1000 ) + $version_dev_minor ),
			( ( $version_major * 1000 ) + $version_minor ),
			$version_patch
		);
	}

	/**
	 * Returns version of plugin core (do not use this method for plugin integration).
	 *
	 * @example Use method to create plugin dependent on this plugin.
	 *
	 * @return string Plugin core version.
	 */
	public function get_version_dev(): string {
		$version_dev_major = explode( '.', $this->version_dev )[0];
		$version_dev_minor = explode( '.', $this->version_dev )[1];
		$version_major     = explode( '.', $this->version )[0];
		$version_minor     = explode( '.', $this->version )[1];

		return sprintf(
			'%d.%d.%d',
			$version_dev_major,
			$version_dev_minor,
			( ( $version_major * 1000 ) + $version_minor )
		);
	}

	/**
	 * Returns list of available field sections.
	 *
	 * @return SectionInterface[] List of objects with section data.
	 */
	public function get_available_field_sections(): array {
		return ( new Sections( $this->field_sections ) )->get_available_field_sections();
	}

	/**
	 * Returns list of available fields.
	 *
	 * @param string $group_key Optionally key of field group.
	 *
	 * @return FieldInterface[] List of objects with field data.
	 */
	public function get_available_fields( string $group_key = '' ): array {
		return ( new Fields( $this->field_groups ) )->get_available_fields( $group_key );
	}

	/**
	 * Returns value of order field.
	 *
	 * @param string $field_key Field key.
	 * @param int    $order_id  ID of WC_Order.
	 *
	 * @return mixed Value of field, or null if not exists.
	 */
	public function get_field_value( string $field_key, int $order_id ) {
		return ( new Value() )->get_field_value( $field_key, $order_id );
	}
}
