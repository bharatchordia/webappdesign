<?php
namespace WPCharming\Widgets;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
use  Elementor\Utils ;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WPC_Element_Featured_Box extends Widget_Base {
	public function get_name() {
		return 'wpc-featured-box';
	}
	public function get_title() {
		return __( 'Featured Box', 'wpcharming' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'fa fa-cube';
	}
	public function get_categories() {
		return [ 'wpc-elements' ];
	}
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Featured Box', 'wpcharming' ),
			]
		);

		$this->add_control(
			'featured_image',
			[
				'label' => __( 'Featured Box Image', 'wpcharming' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src()
				]
			]
		);

		$this->add_control(
			'featured_video_link',
			[
				'label' => __( 'Video URL', 'wpcharming' ),
				'type' => Controls_Manager::URL,
				'default' => [
					'url' => 'http://',
					'is_external' => ''
				],
				'show_external' => false
			]
		);

		$this->add_control(
			'featured_title',
			[
				'label' => __( 'Featured Box Title', 'wpcharming' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => ''
			]
		);

		$this->add_control(
			'featured_description',
			[
				'label' => __( 'Featured Box Description', 'wpcharming' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => ''
			]
		);

		$this->add_control(
			'featured_url',
			[
				'label' => __( 'URL (Link)', 'wpcharming' ),
				'type' => Controls_Manager::URL,
				'default' => [
					'url' => 'http://',
					'is_external' => ''
				],
				'show_external' => false
			]
		);

		$this->add_control(
			'more_text',
			[
				'label' => __( 'Read More Text', 'wpcharming' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' =>  __( 'Read more', 'wpcharming' )
			]
		);
		$this->end_controls_section();
	}
	protected function render( $instance = [] ) {
		$settings = $this->get_settings();
		$image = $settings['featured_image'];
		$imgurl = wp_get_attachment_image_src(  $image['id'], 'medium-thumb' );
		$video_url = $settings['featured_video_link'];
		$featured_url = $settings['featured_url'];
		$title =  $settings['featured_title'];
		$desc =  $settings['featured_description'];
		$more_text = $settings['more_text'];

		$output = '';

		$output .= '<div class="wpb_content_element featured-box" >';

		if ( $image ) {

			$output .= '
				<div class="featured-box-thumb">';

			if ( !empty( $video_url['url'] ) ) {
				$output .= '
					<a class="popup-video" href="'. esc_url($video_url['url']) .'">
						<img src="'. $imgurl[0] .'" alt="'. wp_kses_post($title) .'">
						<span class="video_icon"><i class="fa fa-play"></i></span>
					</a>';
			} else {
				if( ! empty( $featured_url ) ) {
					$output .= '<a title="'. $title .'" target="" href="'. $featured_url['url'] .'"><img src="'. $imgurl[0] .'"></a>';
				} else {
					$output .= '<img src="'. $imgurl[0] .'" alt="'. wp_kses_post($title) .'" >';
				}
			}

			$output .= '</div>';
		}

		if ( $title || $desc || $more_text ) {
			$output .= '
			<div class="featured-box-content">';

			if ( $title ) {
				$output .= '<h4>'. wp_kses_post($title) .'</h4>';
			}

			if ( $desc ) {
				$output .= '
					<div class="featured-box-desc">';

				$output .= '<p>'. wp_kses_post($desc) .'</p>';

				$output .= '
					</div>';
			}

			if ( $more_text && $featured_url['url'] !== '' ) {
				$output .= '
					<div class="featured-box-button">
						<a title="'. $title .'" target="" href="'. $featured_url['url'] .'" class="">'. esc_attr($more_text) .'</a>
					</div>';
			}
			$output .= '
			</div>';
		}

		$output .= '</div>';

		echo html_entity_decode($output);

	}


	protected function _content_template() {}
}
