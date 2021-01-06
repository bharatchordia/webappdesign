<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package WPCharming
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function wpcharming_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'wpcharming_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function wpcharming_body_classes( $classes ) {

	global $woocommerce;
	global $post;

	if ( is_page_template( 'template-fullwidth.php' ) || is_404() ) {
		$classes[] = 'page-fullwidth';
	}

	// WooCommerce
	if ( $woocommerce ) {
		$woo_layout  = get_post_meta( wc_get_page_id('shop'), '_wpc_page_layout', true );
		if ( $woo_layout == 'right-sidebar' || $woo_layout == 'left-sidebar' ) {
			$classes[] = 'shop-has-sidebar';
		}
	}

	// Boxed Layout
	if ( wpcharming_option('site_boxed') || (isset($_REQUEST['boxed_layout']) && $_REQUEST['boxed_layout'] = 'enable' ) ) {
		$classes[] = 'layout-boxed';
	}

	// Header Style
	if ( wpcharming_option('header_style') || wpcharming_option('header_style') !== '' ) {

		if ( isset( $_REQUEST['header-demo'] ) ) {
			$classes[] = 'header-'.$_REQUEST['header-demo'];
		} else {
			$classes[] = 'header-'.wpcharming_option('header_style');
		}

	} else {
		$classes[] = 'header-default';
	}

	// Fixed Header
	if ( wpcharming_option('header_fixed') ) {
		$classes[] = 'header-fixed-on';
	}

	// Transparent Header
	$post_types = get_post_types( '', 'names' );
	$header_style = wpcharming_option('header_style');
	$transparent_header_meta = null;
	if ( $woocommerce && is_woocommerce() ) {
		$transparent_header_meta = get_post_meta( wc_get_page_id('shop'), '_wpc_transparent_header', true );
	} else {
		foreach ( $post_types as $post_type ) {
			if ( is_singular($post_type) ) {
				global $post;
				$transparent_header_meta = get_post_meta( $post->ID, '_wpc_transparent_header', true );
			}
		}
	}

	if ( !is_front_page() && is_home() && get_option('page_for_posts') ) {
		$transparent_header_meta = get_post_meta( get_option('page_for_posts'), '_wpc_transparent_header', true );
	}

	if ( $transparent_header_meta == 'on' && $header_style == 'header-default' ) {

		if ( isset( $_REQUEST['header-demo'] ) ) {
			$classes[] = 'header-normal';
		} else {
			$classes[] = 'header-transparent';
		}

	} else {
		$classes[] = 'header-normal';
	}

	if ( get_post_meta( get_option('page_for_posts'), '_wpc_transparent_header', true ) == 'on' && is_singular('post' ) ) {
		$classes[] = 'header-transparent';
	}

	return $classes;
}
add_filter( 'body_class', 'wpcharming_body_classes' );

/**
 * Adds custom classes to main content.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function wpcharming_get_layout_class ( ) {
	global $post;
	global $woocommerce;
	$classes               = 'right-sidebar';
	$page_layout_admin     = wpcharming_option('page_layout');
	$archive_layout_admin  = wpcharming_option('archive_layout');
	$blog_layout_admin     = wpcharming_option('blog_layout');
	$single_shop_layout    = wpcharming_option('single_shop_layout');
	$single_project_layout = wpcharming_option('project_layout');

	$post_type = get_post_type($post);

	// Pages
	if ( is_page() ){
		$page_meta = get_post_meta( $post->ID, '_wpc_page_layout', true );

		if ( $page_meta == 'sidebar-default' || $page_meta == '' ) {
			$classes = $page_layout_admin;
		} else {
			$classes = $page_meta;
		}
	}

	// Single Post
	if ( is_singular('post') ) {
		if ( $blog_layout_admin ) {
			$classes = $blog_layout_admin;
		} else {
			$classes = 'right-sidebar';
		}
	}

	// Single Project
	if ( is_singular('portfolio') ) {
		$portfolio_layout_meta = get_post_meta( $post->ID, '_wpc_page_layout', true );

		if ( $portfolio_layout_meta == '' || $portfolio_layout_meta == 'sidebar-default' ) {
			if ( $single_project_layout == 'left-sidebar' || $single_project_layout == 'right-sidebar' ) {
				$classes = $single_project_layout;
			} else {
				$classes = 'no-sidebar';
			}
		} else {
			if ( $portfolio_layout_meta == 'right-sidebar' || $portfolio_layout_meta == 'left-sidebar' ) {
				$classes = $portfolio_layout_meta;
			}
		}
	}

	// Archive
	if ( (is_archive() || is_author()) & !is_front_page() ) {
		if ( $archive_layout_admin !== '' ){
			$classes = $archive_layout_admin;
		} else {
			$classes = 'right-sidebar';
		}

	}

	// Search
	if ( is_search() ) {
		if ( $archive_layout_admin !== '' ){
			$classes = $archive_layout_admin;
		} else {
			$classes = 'right-sidebar';
		}

	}

	// Blog Page
	if ( !is_front_page() && is_home() ) {
		if ( $blog_layout_admin ) {
			$classes = $blog_layout_admin;
		} else {
			$classes = 'right-sidebar';
		}
	}

	// WooCommerce
	if ( $woocommerce ) {
		$shop_layout_meta = get_post_meta( wc_get_page_id('shop'), '_wpc_page_layout', true );
		if ( $woocommerce && is_shop() || $woocommerce && is_product_category() || $woocommerce && is_product_tag() ) {
			if ( $shop_layout_meta ) {
				$classes = $shop_layout_meta;
			} else {
				$classes = 'no-sidebar';
			}
		}
		if ( $woocommerce && is_product() ) {
			if ( $single_shop_layout ) {
				$classes = $single_shop_layout;
			} else {
				$classes = 'no-sidebar';
			}
		}
	}

	return $classes;
}


/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function wpcharming_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'wpcharming_setup_author' );

/**
 * Output the status of widets for footer column.
 *
 */
function wpcharming_sidebar_desc( $sidebar_id ) {

	$desc           = '';
	$column         = str_replace( 'footer-', '', $sidebar_id );
	$footer_columns = wpcharming_option('footer_columns');

	if ( $column > $footer_columns ) {
		$desc = __( 'This widget area is currently disabled. You can enable it Theme Options - Footer section.', 'wpcharming' );
	}

	return esc_html( $desc );
}

/**
 * Output the status of widets for topbar.
 *
 */
function wpcharming_topbar_desc( $sidebar_id ) {

	$desc           = '';
	$header_style = wpcharming_option('header_style');

	if ( $header_style == '' || $header_style == 'header-default' ) {
		$desc = __( 'This widget area is currently disabled because you are using default header ( Theme Option > Header ) and it only available for Header Topbar or Header Centered.', 'wpcharming' );
	} else {
		$desc = '';
	}

	return esc_html( $desc );
}

/**
 * Change post type labels and arguments for Portfolio Post Type plugin.
 *
 * @param array $args Existing arguments.
 *
 * @return array Amended arguments.
 */
function wpcharming_change_portfolio_labels( array $args ) {
	global $wpc_option;

	$custom_slug = 'project';
	if ( $wpc_option['custom_project_slug'] != '' ) {
		$custom_slug = trim( $wpc_option['custom_project_slug'] );
	}

    $labels = array(
        'name'               => __( 'Projects', 'wpcharming' ),
        'singular_name'      => __( 'Project', 'wpcharming' ),
        'add_new'            => __( 'Add New Item', 'wpcharming' ),
        'add_new_item'       => __( 'Add New Project', 'wpcharming' ),
        'edit_item'          => __( 'Edit Project', 'wpcharming' ),
        'new_item'           => __( 'Add New Project', 'wpcharming' ),
        'view_item'          => __( 'View Item', 'wpcharming' ),
        'search_items'       => __( 'Search Projects', 'wpcharming' ),
        'not_found'          => __( 'No projects found', 'wpcharming' ),
        'not_found_in_trash' => __( 'No projects found in trash', 'wpcharming' ),
    );
    $args['labels'] = $labels;

    // Update project single permalink format, and archive slug as well.
    $args['rewrite']     = array( 'slug' => $custom_slug );
    $args['has_archive'] = false;
    // Don't forget to visit Settings->Permalinks after changing these to flush the rewrite rules.

    return $args;
}
add_filter( 'portfolioposttype_args', 'wpcharming_change_portfolio_labels' );

/**
 * Browser detection body_class() output
 */
function wpcharming_browser_body_class($classes) {
        global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
        if($is_lynx) $classes[] = 'lynx';
        elseif($is_gecko) $classes[] = 'gecko';
        elseif($is_opera) $classes[] = 'opera';
        elseif($is_NS4) $classes[] = 'ns4';
        elseif($is_safari) $classes[] = 'safari';
        elseif($is_chrome) $classes[] = 'chrome';
        elseif($is_IE) {
                $classes[] = 'ie';
                if(preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version))
                $classes[] = 'ie'.$browser_version[1];
        } else $classes[] = 'unknown';
        if($is_iphone) $classes[] = 'iphone';
        if ( stristr( $_SERVER['HTTP_USER_AGENT'],"mac") ) {
                 $classes[] = 'osx';
           } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"linux") ) {
                 $classes[] = 'linux';
           } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"windows") ) {
                 $classes[] = 'windows';
           }
        return $classes;
}
add_filter('body_class','wpcharming_browser_body_class');


function wpcharming_ocdi_import_files() {
	return array(
		array(
			'import_file_name'             => 'Demo Data Import',
			'local_import_file'            => trailingslashit( get_template_directory() ) . 'assets/dummy-data/demo-content.xml',
			'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'assets/dummy-data/widgets.wie',
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . 'assets/dummy-data/theme_options.json',
                    'option_name' => 'wpc_options',
                ),
            )
		)
	);
}
add_filter( 'pt-ocdi/import_files', 'wpcharming_ocdi_import_files' );

function wpcharming_ocdi_after_import_setup() {
    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Primary', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'footer'  => $footer_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Company News' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
}
add_action( 'pt-ocdi/after_import', 'wpcharming_ocdi_after_import_setup' );