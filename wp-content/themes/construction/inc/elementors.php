<?php
namespace WPCharming;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WPC_Elementors {
	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		$this->add_actions();
	}
	private function add_actions() {
		add_action( 'elementor/init', array( $this, 'add_elementor_category' ) );
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );
	}
	public function add_elementor_category()
	{
		\Elementor\Plugin::instance()->elements_manager->add_category( 'wpc-elements', array(
			'title' => __( 'WPCharming Elements', 'wpcharming' ),
		), 1 );
	}
	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
	}
	private function includes()
	{
		// Theme Elements
		require_once __DIR__ . '/elementors/featured-box.php';
		require_once __DIR__ . '/elementors/page-children.php';
		require_once __DIR__ . '/elementors/recent-news.php';
		require_once __DIR__ . '/elementors/testimonial.php';
	}
	private function register_widget() {
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WPCharming\Widgets\WPC_Element_Featured_Box() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WPCharming\Widgets\WPC_Element_Page_Children() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WPCharming\Widgets\WPC_Element_Recent_news() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WPCharming\Widgets\WPC_Element_Testimonial_Carousel() );
	}
}
new WPC_Elementors();