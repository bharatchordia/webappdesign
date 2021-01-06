<?php
/*------------------------------------------------------*/
/* CLIENT TESTIMONIAL CAROUSEL
/*------------------------------------------------------*/
vc_map( array(
	"name"                      => __("Testimonial Carousel", "js_composer"),
	"base"                      => 'wpc_testimonial_carousel',
	"category"                  => __('WPC Elements', 'js_composer'),
	"description"               => '',
	"save_always" 				=> true,
	"params"                    => array(
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Testimonial Style', 'js_composer' ),
			'param_name'  => 'style',
			'description' => __( 'Choose your testimonial style', 'js_composer' ),
			'default'	  => 'default',
			'value'       => array(
				__("Default, on a white background color", "js_composer") => "default",
				__("Inverted, on a gray background color", "js_composer") => "inverted"
			)
		),


		array(
			"type"        => "exploded_textarea",
			"holder"      => "",
			"heading"     => __("Name for each Testimonial", 'js_composer'),
			"param_name"  => "names",
			"value"       => __("Mark Geragos,Evan Chesler,James M. Beck", 'js_composer'),
			"description" => __("Enter name for each testimonial here. Divide each with linebreaks (Enter).", 'js_composer')
		),
		array(
			"type"        => "attach_images",
			"heading"     => __("Testimonial Avatar", "js_composer"),
			"param_name"  => "images",
			"value"       => "",
			"description" => __("Select images from media library.", "js_composer")
		),
		array(
			"type"        => "textarea_html",
			"holder"      => "div",
			"heading"     => __("Testimonial Content", "js_composer"),
			"param_name"  => "content",
			"value"       => __("Dummy text is text that is used in the publishing industry or by web designers to occupy the space which will later be filled with ‘real’ content. This is required when, for example, the final text is not yet available. \n\n Dummy text is text that is used in the publishing industry or by web designers to occupy the space which will later be filled with ‘real’ content. This is required when, for example, the final text is not yet available. \n\n Dummy text is text that is used in the publishing industry or by web designers to occupy the space which will later be filled with ‘real’ content. This is required when, for example, the final text is not yet available.", "js_composer"),
			"description" => __("Enter content for each testimonial here. Divide each with linebreaks (Enter).", "js_composer")
		),

		array(
			"type"        => "checkbox",
			"heading"     => __("Carousel Autoplay","js_composer"),
			"value"       => array( __("Yes.","js_composer") => "yes" ),
			"param_name"  => "carousel_autoplay",
			'description'    => __( 'Autoplay testimonial carousel, Note: Carousel layout only display when you have more than one testimonial', 'js_composer' ),
		),

		array(
			"type"			 => "textfield",
			"class"			 => "",
			"heading"		 => __("Carousel Autoplay Speed","js_composer"),
			"param_name"	 => "carousel_autoplay_speed",
			"value"			 => "3000",
			'description'    => __( 'Carousel Autoplay Speed in millisecond', 'js_composer' ),
		),
		array(
			"type"			=> "textfield",
			"class"			=> "",
			"heading"		=> __("Carousel Speed","js_composer"),
			"param_name"	=> "carousel_speed",
			'description'    => __( 'Carousel Speed in millisecond', 'js_composer' ),
			"value"			=> "300",
		),
	),
) );
function wpc_shortcode_testimonial_testimonial($atts, $content = null) {
	$atts = vc_map_get_attributes( 'wpc_testimonial_carousel', $atts );
	extract( $atts );

	$output = null;
	$style_class = null;
	if ( $style == 'inverted' ) $style_class = ' inverted';
	$slick_rtl = 'false';
	if ( is_rtl() ){
		$slick_rtl = 'true';
	}

	$testimonial_avatars = explode(',', $images);
	$testimonial_names = explode( ',', $names );
	$testimonial_contents = preg_split("/\r\n|\n|\r/", wpb_js_remove_wpautop($content));
	$i = -1;

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

	$output .= '
	<div class="testimonial_carousel_wrapper">';

		$carousel_class = '';
		if ( is_array( $testimonial_contents ) && count( $testimonial_contents ) > 1  ) {
			$carousel_class = 'testimonial_carousel_'.uniqid();
			$output .= '
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

		$output .= '
		<div class="'. $carousel_class .'">';

			foreach ( $testimonial_contents as $key => $value ) {
				$i++;
				if ( ! isset( $testimonial_contents[$i] ) ) {
					$testimonial_content = '';
				} else {
					$testimonial_content = $testimonial_contents[$i];
				}
				if ( ! isset( $testimonial_avatars[$i] ) ) $testimonial_avatars[$i] = '';
				if ( ! isset( $testimonial_names[$i] ) ) $testimonial_names[$i] = '';


				$output .= '
				<div class="testimonial testimonial-item'. $style_class .'">';

					$output .= '
					<div class="testimonial-content">';

						$output .= '
						'. $testimonial_content .'';

					$output .= '
					</div>';

					$output .= '
					<div class="testimonial-header clearfix">';

						if ( $testimonial_avatars[$i] != '' ) {
						$output .= '
						<div class="testimonial-avatar"><img src="'. wp_get_attachment_url($testimonial_avatars[$i]) .'" alt="'. esc_attr($testimonial_names[$i]) .'"></div>';
						}

						$output .= '
						<div class="testimonial-name font-heading">'. $testimonial_names[$i] .'</div>';

					$output .= '
					</div>';

				$output .= '
				</div>';


			}

		$output .= '
		</div>';

	$output .= '
	</div>';


	return $output;
}
add_shortcode('wpc_testimonial_carousel', 'wpc_shortcode_testimonial_testimonial');


/*------------------------------------------------------*/
/* CLIENT TESTIMONIALS
/*------------------------------------------------------*/
vc_map( array(
	"name"                      => __("Client Testimonial", "js_composer"),
	"base"                      => 'wpc_testimonial',
	"category"                  => __('WPC Elements', 'js_composer'),
	"description"               => '',
	"save_always" 				=> true,
	"params"                    => array(
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Testimonial Style', 'js_composer' ),
			'param_name'  => 'style',
			'description' => __( 'Choose your testimonial style', 'js_composer' ),
			'default'	  => 'default',
			'value'       => array(
				__("Default, on a white background color", "js_composer") => "default",
				__("Inverted, on a gray background color", "js_composer") => "inverted"
			)
		),
		array(
			"type"			=> "textfield",
			"class"			=> "",
			"heading"		=> __("Client Name","js_composer"),
			"param_name"	=> "name",
			"value"			=> ""
		),
		array(
			"type"        => "attach_image",
			"class"       => "",
			"heading"     => __("Client Avatar","js_composer"),
			"param_name"  => "avatar",
			"value"       => "",
			"description" => "Client image, the size should be smaller than 200 x 200px"
		),
		array(
			'type'        => 'textarea',
			'heading'     => __( 'Testimonial Content', 'js_composer' ),
			'param_name'  => 'testimonial_content',
			'value'       => ''
		),
	),
) );
function wpc_shortcode_testimonial($atts, $content = null) {
	// extract(shortcode_atts(array(
	// 	'style'               => '',
	// 	'name'                => '',
	// 	'avatar'              => '',
	// 	'testimonial_content' => ''
	// ), $atts));
	$atts = vc_map_get_attributes( 'wpc_testimonial', $atts );
	extract( $atts );

	$output = null;
	$style_class = null;

	if ( $style == 'inverted' ) $style_class = ' inverted';

	$output .= '
	<div class="testimonial'. $style_class .'">';

		$output .= '
		<div class="testimonial-content">';

			if ( $testimonial_content ) {
			$output .= '
			<p>'. wp_kses_post($testimonial_content) .'</p>';
			}

		$output .= '
		</div>';

		$output .= '
		<div class="testimonial-header clearfix">';

			if ( $avatar != '' ) {
				$output .= '
				<div class="testimonial-avatar"><img src="'. wp_get_attachment_url($avatar) .'" alt="'. esc_attr($name) .'"></div>';
			}

			$output .= '
			<div class="testimonial-name font-heading">'. esc_attr($name) .'</div>';

		$output .= '
		</div>';

	$output .= '
	</div>';

	return $output;
}
add_shortcode('wpc_testimonial', 'wpc_shortcode_testimonial');


/*------------------------------------------------------*/
/* Featured Box
/*------------------------------------------------------*/
vc_map( array(
	"name"                      => __("Featured Box", "js_composer"),
	"base"                      => 'wpc_featured_box',
	"category"                  => __('WPC Elements', 'js_composer'),
	"description"               => '',
	"save_always" 				=> true,
	"params"                    => array(
		array(
			"type"        => "attach_image",
			"class"       => "",
			"heading"     => __("Featured Box Image","js_composer"),
			"param_name"  => "image",
			"value"       => "",
			"description" => "It will display at the top of featured box."
		),
		array(
			"type"        => "textfield",
			"class"       => "",
			"heading"     => __("Video URL","js_composer"),
			"param_name"  => "video_url",
			"value"       => "",
			"description" => "Open a video lightbox when user click to featured image."
		),
		array(
			"type"			=> "textarea",
			"class"			=> "",
			"heading"		=> __("Featured Box Title","js_composer"),
			"param_name"	=> "title",
			"value"			=> ""
		),
		array(
			"type"			=> "textarea",
			"class"			=> "",
			"heading"		=> __("Featured Box Description","js_composer"),
			"param_name"	=> "desc",
			"value"			=> ""
		),
		array(
			'type'        => 'vc_link',
			'heading'     => __( 'URL (Link)', 'js_composer' ),
			'param_name'  => 'link',
			'description' => __( 'Featured Box permalink.', 'js_composer' ),
			//"dependency"  => Array('element' => "link", 'value' => array('custom'))
		),
		array(
			"type"			=> "textfield",
			"class"			=> "",
			"heading"		=> __("Read More Text","js_composer"),
			"param_name"	=> "more_text",
			"value"			=> ""
		),
		array(
			"type"       => "colorpicker",
			"class"      => "",
			"heading"    => __("Custom BG Color","js_composer"),
			"param_name" => "bg_color",
			"value"      => "",
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'js_composer' ),
			'param_name'  => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' )
		)
	),
) );

function wpc_shortcode_featured_box($atts, $content = null) {
	// extract(shortcode_atts(array(
	// 	'image'     => '',
	// 	'video_url' => '',
	// 	'title'     => '',
	// 	'desc'      => '',
	// 	'link'      => '',
	// 	'more_text' => '',
	// 	'bg_color'  => '',
	// 	'el_class'  => ''
	// ), $atts));
	$atts = vc_map_get_attributes( 'wpc_featured_box', $atts );
	extract( $atts );

	$href = null;
	if ( $link !== '' ) { $href = vc_build_link($link); }
	$a_href = $href['url'];
	$a_title = $href['title'];
	$a_target = $href['target'];

	$custom_bg = null;
	if ( $bg_color ) $custom_bg = ' style="background:'. $bg_color .'"';

	$output = null;

	$output .= '
	<div class="wpb_content_element featured-box ' . esc_attr($el_class) .'" '. $custom_bg .'>';

		if ( $image ) {
			$imgurl = wp_get_attachment_image_src( $image, 'medium-thumb');
			$output .= '
			<div class="featured-box-thumb">';

				if ( $video_url ) {
					$output .= '
					<a class="popup-video" href="'. esc_url($video_url) .'">
						<img src="'. $imgurl[0] .'">
						<span class="video_icon"><i class="fa fa-play"></i></span>
					</a>';
				} else {
					if( ! empty( $href['url'] ) ) {
						$output .= '<a title="'. $a_title .'" target="'. $a_target .'" href="'. $href['url'] .'"><img src="'. $imgurl[0] .'"></a>';
					} else {
						$output .= '<img src="'. $imgurl[0] .'">';
					}
				}

			$output .= '
			</div>';
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

				if ( $more_text && $href['url'] !== '' ) {
					$output .= '
					<div class="featured-box-button">
						<a title="'. $a_title .'" target="'. $a_target .'" href="'. $href['url'] .'" class="">'. esc_attr($more_text) .'</a>
					</div>';
				}
			$output .= '
			</div>';
		}

	$output .= '
	</div>';


	return $output;
}
add_shortcode('wpc_featured_box', 'wpc_shortcode_featured_box');

/*------------------------------------------------------*/
/* CUSTOM HEADING
/*------------------------------------------------------*/
vc_map( array(
	"name"                      => __("Custom Heading", "js_composer"),
	"base"                      => 'wpc_custom_heading',
	"icon"                      => "",
	"show_settings_on_create"   => true,
	"category"                  => __('WPC Elements', 'js_composer'),
	//"description"               => __('Restaurant menu heading', 'js_composer'),
	"save_always" 				=> true,
	"params"                    => array(
		array(
			'type'        => 'textarea',
			'holder'      => 'h2',
			'heading'     => __( 'Heading', 'js_composer' ),
			'param_name'  => 'heading',
			'admin_label' => true,
			'value'       => '',
			'description' => __('Custom heading, allow simple HTML code.', 'js_composer')
		),
		array(
			"type"       => "colorpicker",
			"class"      => "",
			"heading"    => __("Heading Color","js_composer"),
			"param_name" => "heading_color",
			"value"      => ""
		),
		array(
			"type"        => "checkbox",
			"class"       => "",
			"heading"     => __("Display a colored line below heading?","js_composer"),
			"value"       => array( __("Yes.","js_composer") => "yes" ),
			"param_name"  => "colored_line",
			"description" => ""
		),

		array(
			'type'               => 'dropdown',
			'heading'            => __( 'Custom Line Color', 'js_composer' ),
			'param_name'         => 'line_color',
			'description'        => __( 'Heading custom line color.', 'js_composer' ),
			'value'              => array(
				__("Primary Color", "js_composer")   => "primary",
				__("Secondary Color", "js_composer") => "secondary",
				__("Custom Color", "js_composer") => "custom",
			)
		),
		array(
			"type"       => "colorpicker",
			"class"      => "",
			"heading"    => __("Custom Line Color","js_composer"),
			"param_name" => "line_custom_color",
			"value"      => "",
			"dependency" => Array('element' => "line_color", 'value' => array('custom'))
		),

		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Heading Position', 'js_composer' ),
			'param_name'  => 'position',
			'value'       => array(
				__("Left", "js_composer")   => "left",
				__("Center", "js_composer") => "center",
				__("Right", "js_composer")  => "right"
			)
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Heading Size', 'js_composer' ),
			'param_name'  => 'size',
			'value'       => array(
				__("Large", "js_composer")   => "large",
				__("Medium", "js_composer") => "medium",
				__("Small", "js_composer")  => "small"
			)
		),
		array(
			"type"			=> "textfield",
			"class"			=> "",
			"heading"		=> __("Custom Margin Top","js_composer"),
			"param_name"	=> "margin_top",
			"value"			=> "",
			"description" 	=> "Don't include \"px\" in your string. e.g \"50\"",
		),
		array(
			"type"			=> "textfield",
			"class"			=> "",
			"heading"		=> __("Custom Margin Bottom","js_composer"),
			"param_name"	=> "margin_bottom",
			"value"			=> "",
			"description" 	=> "Don't include \"px\" in your string. e.g \"50\"",
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'js_composer' ),
			'param_name'  => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' )
		)
	),
) );
function wpc_shortcode_custom_heading($atts, $content = null) {
	// extract(shortcode_atts(array(
	// 	'heading'           => '',
	// 	'heading_color'     => '',
	// 	'colored_line'      => '',
	// 	'line_color'        => '',
	// 	'line_custom_color' => '',
	// 	'position'          => '',
	// 	'size'				=> '',
	// 	'margin_top'        => '',
	// 	'margin_bottom'     => '',
	// 	'el_class'          => ''
	// ), $atts));
	$atts = vc_map_get_attributes( 'wpc_custom_heading', $atts );
	extract( $atts );

	$heading_style_color = '';
	if ( $heading_color ) {
		$heading_style_color = ' style="color: '. $heading_color .';"';
	}

	$extract_class = '';
	if ( $el_class ) $extract_class = $el_class;

	$position_class = '';
	if ( $position == 'right' ) $position_class = ' text-right';
	if ( $position == 'center' ) $position_class = ' text-center';

	$heading_size = '';
	if ( $size     == 'medium' ) $heading_size = ' heading-medium';
	if ( $size == 'small' ) $heading_size = ' heading-small';

	// Custom Style
	$custom_styles = array();
		if ( $margin_top ) {
			$custom_styles[] = 'margin-top: ' . intval($margin_top) . 'px;';
		}
		if ( $margin_bottom ) {
			$custom_styles[] = 'margin-bottom: ' . intval($margin_bottom) . 'px;';
		}
	$custom_styles = implode('', $custom_styles);
	if ( $custom_styles ) {
		$custom_styles = wp_kses( $custom_styles, array() );
		$custom_styles = ' style="' . esc_attr($custom_styles) . '"';
	}

	$line_class = '';
	$line_color_custom = '';
	if ( $line_color == 'primary' ) {
		$line_class = 'primary';
	}
	if ( $line_color == 'secondary' ) {
		$line_class = 'secondary';
	}
	if ( $line_color == 'custom' ) {
		$line_class = '';
	}

	if ( $line_custom_color && $line_color == 'custom' ) $line_color_custom = 'style="background-color: '. $line_custom_color .'"';

	$output = null;

	$output .= '
	<div class="custom-heading wpb_content_element '. $extract_class . $heading_size . $position_class .'"'. $custom_styles .'>';

		if ( $heading ) $output .= '
		<h2 class="heading-title" '. $heading_style_color .'>'. wp_kses_post($heading) .'</h2>';

		if ( $colored_line ) $output .= '
		<span class="heading-line '. $line_class .'"'. $line_color_custom .'></span>';

	$output .= '
	</div>';

	return $output;
}
add_shortcode('wpc_custom_heading', 'wpc_shortcode_custom_heading');
