<?php
namespace WPCharming\Widgets;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
use  Elementor\Utils ;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WPC_Element_Page_Children extends Widget_Base {
	public function get_name() {
		return 'wpc-page-children';
	}
	public function get_title() {
		return __( 'Page Children', 'wpcharming' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-wordpress';
	}
	public function get_categories() {
		return [ 'wpc-elements' ];
	}

	public function page_childs(){
		$page_ids = get_all_page_ids();
		$pages = array();
		for ( $i = 0; $i < count($page_ids); $i++ ) {
			$pages[ $page_ids[$i] ] =  get_the_title($page_ids[$i]);
		}

		return $pages;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Page Children', 'wpcharming' ),
			]
		);

		$this->add_control(
			'parrent_page_id',
			[
				'label' => __( 'Select your parent Page', 'wpcharming' ),
				'label_block' => true,
				'description' => __('The builder item will use parrent page ID to get page childen of that page.', 'wpcharming'),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => $this->page_childs()
			]
		);


		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'wpcharming' ),
				'label_block' => true,
				'description' => __('Ascending or descending order', 'wpcharming'),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'desc'  => __( 'DESC', 'wpcharming' ),
					'asc' => __( 'ASC', 'wpcharming' ),
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Orderby', 'wpcharming' ),
				'label_block' => true,
				'description' => __('Sort retrieved posts/pages by parameter', 'wpcharming'),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => __( 'None', 'wpcharming' ),
					'ID' => __( 'ID', 'wpcharming' ),
					'title' => __( 'Title', 'wpcharming' ),
					'name' => __( 'Name', 'wpcharming' ),
					'rand' => __( 'Random', 'wpcharming' ),
					'date' => __( 'Date', 'wpcharming' ),
					'menu_order' => __( 'Page Order', 'wpcharming' ),
				],
			]
		);

		$this->add_control(
			'exclude',
			[
				'label' => __( 'Specify page NOT to retrieve', 'wpcharming' ),
				'description' => __('Use post ids, e.g: 16, 28', 'wpcharming'),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => ''
			]
		);
		$this->add_control(
			'number',
			[
				'label' => __( 'Number of posts', 'wpcharming' ),
				'description' => __('How many post to show?', 'wpcharming'),
				'label_block' => true,
				'type' => Controls_Manager::NUMBER,
				'default' => ''
			]
		);
		$this->add_control(
			'layout',
			[
				'label' => __( 'Display Mode', 'wpcharming' ),
				'label_block' => true,
				'description' => __('The layout your page children being display', 'wpcharming'),
				'type' => Controls_Manager::SELECT,
				'default' => 'carousel',
				'options' => [
					'carousel'  => __( 'Carousel', 'wpcharming' ),
					'grid' => __( 'Grid', 'wpcharming' ),
				],
			]
		);

		$this->add_control(
			'carousel_autoplay',
			[
				'label' => __( 'Carousel Autoplay', 'wpcharming' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Yes', 'wpcharming' ),
				'label_off' => __( 'No', 'wpcharming' ),
				'return_value' => 'yes',
				'condition' => [
					'layout' => 'carousel',
				],
			]
		);
		$this->add_control(
			'carousel_autoplay_speed',
			[
				'label' => __( 'Carousel Autoplay Speed', 'wpcharming' ),
				'description' => __('Carousel Autoplay Speed in millisecond', 'wpcharming'),
				'label_block' => true,
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'condition' => [
					'display_mode' => 'carousel',
				],
			]
		);
		$this->add_control(
			'carousel_speed',
			[
				'label' => __( 'Carousel Speed', 'wpcharming' ),
				'description' => __('Carousel Speed in millisecond', 'wpcharming'),
				'label_block' => true,
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'condition' => [
					'display_mode' => 'carousel',
				],
			]
		);

		$this->add_control(
			'column',
			[
				'label' => __( 'Column', 'wpcharming' ),
				'label_block' => true,
				'description' => __('How many column will be display on a row?', 'wpcharming'),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'2'  => __( '2 Columns', 'wpcharming' ),
					'3' => __( '3 Columns', 'wpcharming' ),
					'4' => __( '4 Columns', 'wpcharming' ),
				],
			]
		);

		$this->add_control(
			'readmore_text',
			[
				'label' => __( 'Read More text', 'wpcharming' ),
				'description' => __('Custom your read more text, e.g. Read More, View Profile ...', 'wpcharming'),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => ''
			]
		);


		$this->end_controls_section();
	}
	protected function render( $instance = [] ) {
		$settings = $this->get_settings();

		$output = '';

		if ( !empty( $settings['parrent_page_id'] ) ) {
			$output .= '
			<div class="child-page-wrapper">';

				$output .= wpcharming_list_child_pages( $settings['parrent_page_id'], $settings['order'], $settings['orderby'], $settings['exclude'], $settings['layout'], $settings['column'], $settings['number'], $settings['readmore_text'], $settings['carousel_autoplay'], $settings['carousel_autoplay_speed'], $settings['carousel_speed'] );

				$output .= '
			</div>';


			echo html_entity_decode( $output );
		}

	}


	protected function _content_template() {}
}
