<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPCharming
 */
global $wpc_option;

wpcharming_get_header() ?>

		<?php
		// Blog Page Title
		if ( !is_front_page() && is_home() && get_option('page_for_posts') ) {

			$page_breadcrumb = get_post_meta( get_option('page_for_posts'), '_wpc_hide_breadcrumb', true );
			$hide_page_title = get_post_meta( get_option('page_for_posts'), '_wpc_hide_page_title', true );

			if ( $hide_page_title !== 'on' ) {
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
			wpcharming_get_page_header(get_option('page_for_posts'));
			if ( $page_breadcrumb !== 'on' ) wpcharming_breadcrumb();

		} else {

			?>
			<div class="page-title-wrap-none"></div>
			<?php
			wpcharming_breadcrumb();

		}
		?>

		<?php //wpcharming_breadcrumb(); ?>

		<div id="content-wrap" class="container <?php echo wpcharming_get_layout_class(); ?>">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
							/* Include the Post-Format-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'content');
						?>

					<?php endwhile; ?>

					<?php wpcharming_paging_nav(); ?>

				<?php else : ?>

					<?php get_template_part( 'content', 'none' ); ?>

				<?php endif; ?>

				</main><!-- #main -->
			</div><!-- #primary -->

			<?php echo wpcharming_frontpage_sidebar(); ?>

		</div> <!-- /#content-wrap -->

<?php get_footer(); ?>
