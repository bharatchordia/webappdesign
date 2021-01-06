<?php
namespace WPCharming\Widgets;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WPC_Element_Testimonial_Carousel extends Widget_Base {
	public function get_name() {
		return 'wpc-testimonial-carousel';
	}
	public function get_title() {
		return __( 'Testimonial Carousel', 'wpcharming' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-wordpress';
	}
	public function get_categories() {
		return [ 'wpc-elements' ];
	}


	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Testimonial Carousel', 'wpcharming' ),
			]
		);

		$this->add_control(
			'style',
			[
				'label' => __( 'Testimonial Style', 'wpcharming' ),
				'label_block' => true,
				'description' => __('Choose your testimonial style', 'wpcharming'),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'  => __( 'Default, on a white background color', 'wpcharming' ),
					'inverted' => __( 'Inverted, on a gray background color', 'wpcharming' ),
				],
			]
		);

		$this->add_control(
			'list',
			[
				'label' => __( 'Testimonial Item', 'wpcharming' ),
				'type' => Controls_Manager::REPEATER,
				'default' => '',
				'fields' => [
					[
						'name' => 'testimonial_name',
						'label' => __( 'Name', 'wpcharming' ),
						'type' => Controls_Manager::TEXT,
						'default' => '',
						'label_block' => true,
					],
					[
						'name' => 'avatar',
						'label' => __( 'Avatar', 'wpcharming' ),
						'type' => Controls_Manager::MEDIA,
						'default' => '',
						'label_block' => true,
					],
					[
						'name' => 'content',
						'label' => __( 'Content', 'wpcharming' ),
						'type' => Controls_Manager::WYSIWYG,
						'default' => '',
						'show_label' => true,
					],
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
				'return_value' => 'yes'
			]
		);
		$this->add_control(
			'carousel_autoplay_speed',
			[
				'label' => __( 'Carousel Autoplay Speed', 'wpcharming' ),
				'description' => __('Carousel Autoplay Speed in millisecond', 'wpcharming'),
				'label_block' => true,
				'type' => Controls_Manager::NUMBER,
				'default' => ''
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
			]
		);




		$this->end_controls_section();
	}
	protected function render( $instance = [] ) {
		$settings = $this->get_settings();

		$style = $settings['style'];
		$carousel_autoplay = $settings['carousel_autoplay'];
		$carousel_autoplay_speed = $settings['carousel_autoplay_speed'];
		$carousel_speed = $settings['carousel_speed'];
		$testimonials = $settings['list'];

		$style_class =  ( $style == 'inverted' )  ? ' inverted' : '';
		$slick_rtl = 'false';
		if ( is_rtl() ){
			$slick_rtl = 'true';
		}

		if ( $carousel_autoplay == 'yes' ) {
			$carousel_autoplay = 'true';
		} else {
			$carousel_autoplay = 'false';
		}

		if ( $carousel_autoplay_speed == '' ) {
			$carousel_autoplay_speed = '3000';
		}

		if ( $carousel_speed == '' ) {
			$carousel_speed = '300';
		}

		$carousel_class = 'testimonial_carousel_'.uniqid();

		if ( count( $testimonials ) > 1 ) {
			$carousel_class = 'testimonial_carousel_'.uniqid();
			echo '
			<script type="text/javascript">
				jQuery(document).ready(function(){
					"use strict";
					jQuery(".'. $carousel_class .'").slick({
						rtl: '. $slick_rtl .',
						autoplay: '. $carousel_autoplay .' ,
						autoplaySpeed: '. $carousel_autoplay_speed .' ,
						speed: '. $carousel_speed .' ,
						slidesToShow: 1,
						slidesToScroll: 1,
						draggable: false,
						prevArrow: "<span class=\'carousel-prev\'><i class=\'fa fa-angle-left\'></i></span>",
        				nextArrow: "<span class=\'carousel-next\'><i class=\'fa fa-angle-right\'></i></span>",
					});
				});
			</script>';
		}

		if ( $testimonials ) {
			?>

			<div class="testimonial_carousel_wrapper">
				<div class="<?php echo esc_attr( $carousel_class ) ?>">
					<?php
					foreach ( $testimonials as $testimonial ) {
						$image = $testimonial['avatar'];
						$avatar = wp_get_attachment_image_src(  $image['id'], 'medium' );
						?>
						<div class="testimonial testimonial-item <?php echo esc_attr( $style_class ); ?>">
							<div class="testimonial-content">
								<?php echo  wp_kses_post($testimonial['content']); ?>
							</div>
							<div class="testimonial-header clearfix">
								<div class="testimonial-avatar"><img src="<?php echo esc_url( $avatar[0] ) ?>" alt="<?php echo esc_attr($testimonial['testimonial_name']); ?>"></div>
								<div class="testimonial-name font-heading">
									<?php echo  esc_attr($testimonial['testimonial_name']) ; ?>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>

			<?php
		}

	}


	protected function _content_template() {}
}
