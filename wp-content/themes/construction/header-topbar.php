<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package WPCharming
 */
global $wpc_option;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'wpcharming' ); ?></a>
	
	<div id="topbar" class="site-topbar">
		<div class="container">
			<div class="topbar-inner clearfix">
				<div class="topbar-left topbar widget-area clearfix">
					<?php dynamic_sidebar('topbar-left'); ?>
				</div>
				<div class="topbar-right topbar widget-area clearfix">
					<?php dynamic_sidebar('topbar-right'); ?>
				</div>
			</div>
		</div>
	</div> <!-- /#topbar -->

	<header id="masthead" class="site-header <?php if ( wpcharming_option('header_fixed') ) echo 'fixed-on' ?>" role="banner">
		<div class="header-wrap">
			<div class="container">
				<div class="site-branding">
					<?php if ( wpcharming_option('site_logo', false, 'url') !== '' ) { ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
						<img src="<?php echo wpcharming_logo_render(); ?>" alt="<?php get_bloginfo( 'name' ) ?>" />
					</a>
					<?php } else { ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
					<?php } ?>
				</div><!-- /.site-branding -->

				<div class="header-right-wrap clearfix">
					<nav id="site-navigation" class="main-navigation" role="navigation">
						<div id="nav-toggle"><i class="fa fa-bars"></i></div>
						<ul class="wpc-menu">	
					   	   <?php wp_nav_menu( array('theme_location' => 'primary', 'container' => '', 'items_wrap' => '%3$s' ) ); ?>
					    </ul>
					</nav><!-- #site-navigation -->
				</div>
			</div>
			
		</div>
	</header><!-- #masthead -->

	<div id="content" class="site-content">