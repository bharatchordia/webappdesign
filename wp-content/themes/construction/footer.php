<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WPCharming
 */

global $wpc_option;
?>

	</div><!-- #content -->

	<div class="clear"></div>

	<footer id="colophon" class="site-footer" role="contentinfo">

		<div class="footer-connect">
			<div class="container">

				<?php if ( $wpc_option['footer_social'] ) { ?>
				<div class="footer-social">
					<?php if ( $wpc_option['social_text'] ) { ?> <label class="font-heading" for=""><?php echo esc_attr($wpc_option['social_text']); ?></label> <?php } ?>
					<?php if ( !empty( $wpc_option['footer_use_social']['twitter']) && $wpc_option['footer_use_social']['twitter'] == 1 && $wpc_option['twitter'] !== '' ) { ?><a target="_blank" href="<?php echo esc_url($wpc_option['twitter']); ?>" title="Twitter"><i class="fa fa-twitter"></i></a> <?php } ?>
					<?php if ( !empty( $wpc_option['footer_use_social']['facebook']) && $wpc_option['footer_use_social']['facebook'] == 1 && $wpc_option['facebook'] !== '' ) { ?><a target="_blank" href="<?php echo esc_url($wpc_option['facebook']); ?>" title="Facebook"><i class="fa fa-facebook"></i></a> <?php } ?>
					<?php if ( !empty( $wpc_option['footer_use_social']['linkedin']) && $wpc_option['footer_use_social']['linkedin'] == 1 && $wpc_option['linkedin'] !== '' ) { ?><a target="_blank" href="<?php echo esc_url($wpc_option['linkedin']); ?>" title="Linkedin"><i class="fa fa-linkedin-square"></i></a> <?php } ?>
					<?php if ( !empty( $wpc_option['footer_use_social']['pinterest']) && $wpc_option['footer_use_social']['pinterest'] == 1 && $wpc_option['pinterest'] !== '' ) { ?><a target="_blank" href="<?php echo esc_url($wpc_option['pinterest']); ?>" title="Pinterest"><i class="fa fa-pinterest"></i></a> <?php } ?>
					<?php if ( !empty( $wpc_option['footer_use_social']['instagram']) && $wpc_option['footer_use_social']['instagram'] == 1 && $wpc_option['instagram'] !== '' ) { ?><a target="_blank" href="<?php echo esc_url($wpc_option['instagram']); ?>" title="Instagram"><i class="fa fa-instagram"></i></a> <?php } ?>
					<?php if ( !empty( $wpc_option['header_use_social']['vk']) && $wpc_option['header_use_social']['vk'] == 1 && $wpc_option['vk'] !== '' ) { ?><a target="_blank" href="<?php echo esc_url($wpc_option['vk']); ?>" title="VK"><i class="fa fa-vk"></i></a> <?php } ?>
					<?php if ( !empty( $wpc_option['header_use_social']['yelp']) && $wpc_option['header_use_social']['yelp'] == 1 && $wpc_option['yelp'] !== '' ) { ?><a target="_blank" href="<?php echo esc_url($wpc_option['yelp']); ?>" title="Yelp"><i class="fa fa-yelp"></i></a> <?php } ?>
					<?php if ( !empty( $wpc_option['header_use_social']['foursquare']) && $wpc_option['header_use_social']['foursquare'] == 1 && $wpc_option['foursquare'] !== '' ) { ?><a target="_blank" href="<?php echo esc_url($wpc_option['foursquare']); ?>" title="Foursquare"><i class="fa fa-foursquare"></i></a> <?php } ?>
					<?php if ( !empty( $wpc_option['footer_use_social']['flickr']) && $wpc_option['footer_use_social']['flickr'] == 1 && $wpc_option['flickr'] !== '' ) { ?><a target="_blank" href="<?php echo esc_url($wpc_option['flickr']); ?>" title="Flickr"><i class="fa fa-flickr"></i></a> <?php } ?>
					<?php if ( !empty( $wpc_option['footer_use_social']['youtube']) && $wpc_option['footer_use_social']['youtube'] == 1 && $wpc_option['youtube'] !== '' ) { ?><a target="_blank" href="<?php echo esc_url($wpc_option['youtube']); ?>" title="Youtube"><i class="fa fa-youtube-play"></i></a> <?php } ?>
					<?php if ( !empty( $wpc_option['footer_use_social']['email']) && $wpc_option['footer_use_social']['email'] == 1 && $wpc_option['email'] !== '' ) { ?><a target="_blank" href="<?php echo wp_kses_post($wpc_option['email']); ?>" title="Email"><i class="fa fa-envelope"></i></a> <?php } ?>
					<?php if ( !empty( $wpc_option['footer_use_social']['rss']) && $wpc_option['footer_use_social']['rss'] == 1 && $wpc_option['rss'] !== '' ) { ?><a target="_blank" href="<?php echo esc_url($wpc_option['rss']); ?>" title="RSS"><i class="fa fa-rss"></i></a> <?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>

		<div class="container">

			<?php if ( $wpc_option['footer_widgets'] ) { ?>
			<div class="footer-widgets-area">
				<?php $footer_columns = $wpc_option['footer_columns']; ?>
				<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) { ?>
					<div class="sidebar-footer footer-columns footer-<?php echo esc_attr($footer_columns) ?>-columns clearfix">
						<?php
						for ( $count = 1; $count <= $footer_columns; $count++ ) {
							?>
							<div id="footer-<?php echo esc_attr($count) ?>" class="footer-<?php echo esc_attr($count) ?> footer-column widget-area" role="complementary">
								<?php dynamic_sidebar('footer-'.$count);?>
							</div>
							<?php
						}
						?>
					</div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
		<div class="site-info-wrapper">
			<div class="container">
				<div class="site-info clearfix">
					<div class="copy_text">
						<?php
						if ( wpcharming_option('footer_copyright') == '' ) {
							printf( __( 'Copyright &copy; %1$s %2$s - Theme by %3$s', 'wpcharming' ), esc_attr(date('Y')), get_bloginfo('name'), '<a href="'. esc_url( __( 'https://wpcharming.com', 'wpcharming' ) ) .'" rel="designer">WPCharming</a>' );
						} else {
							echo wp_kses_post( wpcharming_option('footer_copyright') );
						}
						?>
					</div>
					<div class="footer-menu">
                        <?php 
                        if ( has_nav_menu( 'footer' ) ) {
                            wp_nav_menu( array( 'menu' => 'Footer Right Menu', 'theme_location' => 'footer', 'fallback_cb' => false ) ); 
                        } 
                        ?>
					</div>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php if ( $wpc_option['page_back_totop'] ) { ?>
<div id="btt"><i class="fa fa-angle-double-up"></i></div>
<?php } ?>

<?php wp_footer(); ?>
</body>
</html>
