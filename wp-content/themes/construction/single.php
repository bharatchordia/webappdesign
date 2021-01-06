<?php
/**
 * The template for displaying all single posts.
 *
 * @package WPCharming
 */
global $wpc_option;

wpcharming_get_header() ?>
	
	<?php
	// Blog Page Title
	if ( $wpc_option['blog_single_page_title'] && get_option('page_for_posts') ) {

		$enable_page_header = get_post_meta( get_option('page_for_posts'), '_wpc_enable_page_header', true );

		if ( $enable_page_header  == 'on') {
			wpcharming_get_page_header(get_option('page_for_posts'));
		} else {
			?>
			<div class="page-title-wrap">
				<div class="container">
					<h1 class="page-entry-title">
						<?php echo get_the_title( get_option('page_for_posts') ); ?>
					</h1>
				</div>
			</div>
			<?php
		}
	}
	?>
	
	<?php if ( $wpc_option['blog_single_breadcrumb'] ) wpcharming_breadcrumb(); ?>

	<div id="content-wrap" class="container <?php echo wpcharming_get_layout_class(); ?>">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'single' ); ?>

					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					?>

				<?php endwhile; // end of the loop. ?>

			</main><!-- #main -->
		</div><!-- #primary -->
		<?php echo wpcharming_get_sidebar(); ?>
	</div> <!-- /#content-wrap -->
<?php get_footer(); ?>
