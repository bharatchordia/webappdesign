<?php
/**
 * .
 *
 * @package WPDesk\FPF\Free
 */

namespace WPDesk\FPF\Free\Integration;

/**
 * .
 */
interface IntegratorInterface {

	/**
	 * Returns version of integration script.
	 *
	 * @example Use method to integration with plugin.
	 *
	 * @return string Integration script version.
	 */
	public function get_version(): string;

	/**
	 * Returns version of plugin core (do not use this method for plugin integration).
	 *
	 * @example Use method to create plugin dependent on this plugin.
	 *
	 * @return string Plugin core version.
	 */
	public function get_version_dev(): string;
}
