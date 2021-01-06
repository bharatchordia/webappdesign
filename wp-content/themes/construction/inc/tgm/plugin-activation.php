<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.4.0
 * @author     Thomas Griffin <thomasgriffinmedia.com>
 * @author     Gary Jones <gamajo.com>
 * @copyright  Copyright (c) 2014, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        array(
            'name'              => 'WPBakery Visual Composer',
            'slug'              => 'js_composer', 
            'source'            => get_template_directory() . '/inc/plugins/js_composer.zip',
            'required'          => true,
            'force_activation'  => true,
            'external_url'      => 'http://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=WPCharming',
        ),

        array(
            'name'               => 'Redux Framework',
            'slug'               => 'redux-framework', 
            'required'           => true,
            'force_activation'   => true,
        ),
        
        array(
            'name'               => 'Slider Revolution',
            'slug'               => 'revslider', 
            'source'             => get_template_directory() . '/inc/plugins/revslider.zip',
            'required'           => false,
            'force_activation'   => false,
            'force_deactivation' => false,
            'external_url'       => 'http://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380?ref=WPCharming',
        ),

        array(
            'name'               => 'Essential Grid',
            'slug'               => 'essential-grid', 
            'source'             => get_template_directory() . '/inc/plugins/essential-grid.zip',
            'required'           => false,
            'force_activation'   => false,
            'force_deactivation' => false,
            'external_url'       => 'http://codecanyon.net/item/essential-grid-wordpress-plugin/7563340?ref=WPCharming',
        ),

        array(
            'name'               => 'One Click Demo Import',
            'slug'               => 'one-click-demo-import', 
            'required'           => false,
            'force_activation'   => false,
            'force_deactivation' => false,
        ),

        array(
            'name'               => 'Portfolio Post Type',
            'slug'               => 'portfolio-post-type', 
            'required'           => true,
            'force_activation'   => true,
            'force_deactivation' => false,
        ),

        array(
            'name'              => 'Breadcrumb NavXT',
            'slug'              => 'breadcrumb-navxt', 
            'required'          => false,
            'force_activation'  => false,
        ),

        array(
            'name'              => 'WooCommerce',
            'slug'              => 'woocommerce', 
            'required'          => false,
            'force_activation'  => false,
        ),
        array(
            'name'              => 'WooSidebars',
            'slug'              => 'woosidebars', 
            'required'          => false,
            'force_activation'  => false,
        ),
        array(
            'name'              => 'Contact Form 7',
            'slug'              => 'contact-form-7', 
            'required'          => false,
            'force_activation'  => false,
        ),
	    array(
		    'name'              => 'EasyMega Mega Menu',
		    'slug'              => 'easymega',
		    'required'          => false,
		    'force_activation'  => false,
	    ),

        // This is an example of how to include a plugin from a private repo in your theme.
        /*
        array(
            'name'               => 'TGM New Media Plugin', // The plugin name.
            'slug'               => 'tgm-new-media-plugin', // The plugin slug (typically the folder name).
            'source'             => 'https://s3.amazonaws.com/tgm/tgm-new-media-plugin.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'external_url'       => 'https://github.com/thomasgriffin/New-Media-Image-Uploader', // If set, overrides default API URL and points to an external URL.
        ),
        */

        // This is an example of how to include a plugin from the WordPress Plugin Repository.

    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
    );

    tgmpa( $plugins, $config );

}