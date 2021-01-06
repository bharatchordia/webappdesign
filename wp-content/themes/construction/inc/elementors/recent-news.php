<?php
namespace WPCharming\Widgets;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
use  Elementor\Utils ;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WPC_Element_Recent_news extends Widget_Base {
	public function get_name() {
		return 'wpc-recent-new';
	}
	public function get_title() {
		return __( 'Recent News', 'wpcharming' );
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
				'label' => esc_html__( 'Recent News', 'wpcharming' ),
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
		$readmore_text = $settings['readmore_text'];
		$column = $settings['column'];
		$carousel_autoplay = $settings['carousel_autoplay'];
		$carousel_autoplay_speed = $settings['carousel_autoplay_speed'];
		$carousel_speed = $settings['carousel_speed'];
		$number  = $settings['number'];
		$layout = $settings['layout'];
		$order = $settings['order'];
		$orderby = $settings['orderby'];

		if ( $readmore_text == '' ) {
			$readmore_text = __('Read More', 'wpcharming');
		}

		$col_class = $thumbnail = '';
		if ( $column == 2 ) {
			$col_class = "grid-sm-6";
		} elseif ( $column == 3 ){
			$col_class = "grid-sm-6 grid-md-4";
		} elseif ( $column == 4 ) {
			$col_class = "grid-sm-6 grid-md-3";
		} else {
			$col_class = "grid-sm-6 grid-md-4";
		}

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

		$count  = 0;
		$args = array(
			'posts_per_page' => $number,
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'order'          => $order,
			'orderby'        => $orderby
		);
		$recent_posts = new \WP_Query( $args );

		$output = null;

		$output .= '
	<div class="child-page-wrapper recent-news-wrapper">';

		$carousel_class = '';
		if ( $layout == 'carousel' ) {
			$carousel_class = 'recent-news-carousel-'.uniqid();
			$output .= '
					<script type="text/javascript">
						jQuery(document).ready(function(){
							"use strict";
							jQuery(".'. $carousel_class .'").slick({
								rtl: '. $slick_rtl .',
								slidesToShow: '. $column .',
								autoplay: '. $carousel_autoplay .',
		                        autoplaySpeed: '. $carousel_autoplay_speed .' ,
		                        speed: '. $carousel_speed .',
								slidesToScroll: 1,
								draggable: false,
								prevArrow: "<span class=\'carousel-prev\'><i class=\'fa fa-angle-left\'></i></span>",
		        				nextArrow: "<span class=\'carousel-next\'><i class=\'fa fa-angle-right\'></i></span>",
		        				responsive: [{
								    breakpoint: 1024,
								    settings: {
								    slidesToShow: '. $column .'
								    }
								},
								{
								    breakpoint: 600,
								    settings: {
								    slidesToShow: 2
								    }
								},
								{
								    breakpoint: 480,
								    settings: {
								    slidesToShow: 1
								    }
								}]
							});
						});
					</script>';
		}

		if ( $recent_posts->have_posts() ) :

			$output .= '
				<div class="grid-wrapper grid-'.$column.'-columns grid-row '. $carousel_class .'">';

			while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); $count++;

				$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
				$time_string = sprintf( $time_string,
					esc_attr( get_the_date( 'c' ) ),
					esc_html( get_the_date() ),
					esc_attr( get_the_modified_date( 'c' ) ),
					esc_html( get_the_modified_date() )
				);

				$num_comments = get_comments_number(); // get_comments_number returns only a numeric value

				if ( comments_open() ) {
					if ( $num_comments == 0 ) {
						$comments = __('No Comments', 'wpcharming');
					} elseif ( $num_comments > 1 ) {
						$comments = $num_comments . __(' Comments', 'wpcharming');
					} else {
						$comments = __('1 Comment', 'wpcharming');
					}
					$write_comments = '<a href="' . get_comments_link() .'">'. $comments.'</a>';
				} else {
					$write_comments =  __('Comments off.', 'wpcharming');
				}

				$output .= '
					<div class="grid-item '. $col_class .'">';

				if( has_post_thumbnail() ) {
					$output .= '
						<div class="grid-thumbnail">
							<a href="'. get_the_permalink() .'" title="'. get_the_title() .'">'. get_the_post_thumbnail( get_the_ID(), 'medium-thumb') .'</a>
						</div>';
				}

				$output .= '
						<h4 class="grid-title"><a href="'. get_the_permalink() .'" rel="bookmark">'. get_the_title() .'</a></h4>

						<div class="recent-news-meta">
							<span class="post-date"><i class="fa fa-file-text-o"></i> '. $time_string .'</span>
							<span class="comments-link"><i class="fa fa-comments-o"></i> '. $write_comments .'</span>
						</div>

						<p>'. get_the_excerpt() .'</p>

						<a class="btn btn-light btn-small" href="'. get_the_permalink() .'" title="'. get_the_title() .'">'. esc_attr($readmore_text) .'</a>

					</div>
					';
				if ( $layout == 'grid' ) {
					if ( $count % $column == 0 ) $output .= '
						<div class="clear"></div>';
				}

			endwhile;

			$output .= '
				</div>';

		else:
			$output .= __( 'Sorry, there is no child pages under your selected page.', 'wpcharming' );
		endif;

		wp_reset_postdata();

		$output .= '
	</div>';

		echo html_entity_decode($output);

	}


	protected function _content_template() {}
}
