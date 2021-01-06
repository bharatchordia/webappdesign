<?php

    /**
     * Theme Options Config
     */

    if ( ! class_exists( 'WPCharming_Options_Config' ) ) {

        class WPCharming_Options_Config {

            public $args = array();
            public $sections = array();
            public $theme;
            public $ReduxFramework;

            public function __construct() {

                if ( ! class_exists( 'ReduxFramework' ) ) {
                    return;
                }

                // This is needed. Bah WordPress bugs.  ;)
                if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                    $this->initSettings();
                } else {
                    add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
                }

            }

            public function initSettings() {

                // Set the default arguments
                $this->setArguments();

                // Set a few help tabs so you can see how it's done
                $this->setHelpTabs();

                // Create the sections and fields
                $this->setSections();

                if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                    return;
                }

                $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
            }

            public function setHelpTabs() {

                // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-1',
                    'title'   => __( 'Theme Information 1', 'wpcharming' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'wpcharming' )
                );

                $this->args['help_tabs'][] = array(
                    'id'      => 'redux-help-tab-2',
                    'title'   => __( 'Theme Information 2', 'wpcharming' ),
                    'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'wpcharming' )
                );

                // Set the help sidebar
                $this->args['help_sidebar'] = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'wpcharming' );
            }

            /**
             * All the possible arguments for Redux.
             * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
             * */
            public function setArguments() {

                $theme = wp_get_theme(); // For use with some settings. Not necessary.

                $this->args = array(
                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'           => 'wpc_options',
                    // This is where your data is stored in the database and also becomes your global variable name.
                    'display_name'       => $theme->get( 'Name' ),
                    // Name that appears at the top of your panel
                    'display_version'    => false,
                    // Version that appears at the top of your panel
                    'menu_type'          => 'menu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'     => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'         => __( 'Theme Options', 'wpcharming' ),
                    'page_title'         => __( 'Theme Options', 'wpcharming' ),
                    // You will need to generate a Google API key to use this feature.
                    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                    'google_api_key'     => '',
                    // Must be defined to add google fonts to the typography module

                    'async_typography'   => false,
                    // Use a asynchronous font on the front end or font string
                    'admin_bar'          => true,
                    // Show the panel pages on the admin bar
                    'global_variable'    => 'wpc_option',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'           => false,
                    // Show the time the page took to load, etc
                    'customizer'         => false,
                    // Enable basic customizer support

                    // OPTIONAL -> Give you extra features
                    'page_priority'      => null,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'        => 'themes.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'   => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'          => '',
                    // Specify a custom URL to an icon
                    'last_tab'           => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'          => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'          => 'wpcharming_options',
                    // Page slug used to denote the panel
                    'save_defaults'      => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'       => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'       => '',
                    // What to print by the field's title if the value shown is default. Suggested: *
                    'show_import_export' => true,
                    // Shows the Import/Export panel when not used as a field.

                    // CAREFUL -> These options are for advanced use only
                    'transient_time'     => 60 * MINUTE_IN_SECONDS,
                    'output'             => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'         => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    'footer_credit'     => ' ',
                    // Disable the footer credit of Redux. Please leave if you can help it.

                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'           => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                    'system_info'        => false,
                    // REMOVE

                    // HINTS
                    'hints'              => array(
                        'icon'          => 'icon-question-sign',
                        'icon_position' => 'right',
                        'icon_color'    => 'lightgray',
                        'icon_size'     => 'normal',
                        'tip_style'     => array(
                            'color'   => 'light',
                            'shadow'  => true,
                            'rounded' => false,
                            'style'   => '',
                        ),
                        'tip_position'  => array(
                            'my' => 'top left',
                            'at' => 'bottom right',
                        ),
                        'tip_effect'    => array(
                            'show' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'mouseover',
                            ),
                            'hide' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'click mouseleave',
                            ),
                        ),
                    )
                );


                // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
                /*
                $this->args['share_icons'][] = array(
                    'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
                    'title' => 'Visit us on GitHub',
                    'icon'  => 'el-icon-github'
                    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
                    'title' => 'Like us on Facebook',
                    'icon'  => 'el-icon-facebook'
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'http://twitter.com/reduxframework',
                    'title' => 'Follow us on Twitter',
                    'icon'  => 'el-icon-twitter'
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'http://www.linkedin.com/company/redux-framework',
                    'title' => 'Find us on LinkedIn',
                    'icon'  => 'el-icon-linkedin'
                );
                */

                // Panel Intro text -> before the form
                if ( ! isset( $this->args['global_variable'] ) || $this->args['global_variable'] !== false ) {
                    if ( ! empty( $this->args['global_variable'] ) ) {
                        $v = $this->args['global_variable'];
                    } else {
                        $v = str_replace( '-', '_', $this->args['opt_name'] );
                    }
                    //$this->args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'wpcharming' ), $v );
                } else {
                    //$this->args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'wpcharming' );
                }

                // Add content after the form.
                //$this->args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'wpcharming' );
            }

            public function setSections() {


                /*--------------------------------------------------------*/
                /* GENERAL SETTINGS
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'title'  => __( 'General', 'wpcharming' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-cog el-icon-large',
                    'submenu' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields' => array(

                        array(
                            'id'       =>'site_logo',
                            'url'      => false,
                            'type'     => 'media',
                            'title'    => __('Site Logo', 'wpcharming'),
                            'default'  => array( 'url' => get_template_directory_uri() .'/assets/images/logo.png' ),
                            'subtitle' => __('Upload your logo here.', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'site_logo_retina',
                            'url'      => false,
                            'type'     => 'media',
                            'title'    => __('Site Logo Retina', 'wpcharming'),
                            'default'  => '',
                            'subtitle' => __('Upload at exactly 2x the size of your standard logo (optional), the name should include @2x at the end, example <strong>logo@2x.png</strong>', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'site_transparent_logo',
                            'url'      => false,
                            'type'     => 'media',
                            'title'    => __('Transparent Logo', 'wpcharming'),
                            'default'  => array( 'url' => get_template_directory_uri() .'/assets/images/logo_transparent.png' ),
                            'subtitle' => __('Upload your transparent logo here, transparent logo display on a transparent header when applicable.', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'site_transparent_logo_retina',
                            'url'      => false,
                            'type'     => 'media',
                            'title'    => __('Transparent Logo Retina', 'wpcharming'),
                            'default'  => '',
                            'subtitle' => __('Upload at exactly 2x the size of your transparent logo (optional).', 'wpcharming'),
                        ),
                        array(
                            'id'             => 'logo_margin',
                            'type'           => 'spacing',
                            'output'         => array('.site-header .site-branding'),
                            'mode'           => 'margin',
                            'units'          => array('px'),
                            'units_extended' => 'false',
                            'title'          => __('Logo Margin', 'wpcharming'),
                            'subtitle'       => '',
                            'desc'           => __('Set your logo margin in px. ee.g. 20', 'wpcharming'),
                            'default'        => array(
                                'margin-top'     => '0px',
                                'margin-right'   => '0px',
                                'margin-bottom'  => '0px',
                                'margin-left'    => '0px',
                                'units'          => 'px',
                            )

                        ),
                        array(
                            'id'       =>'site_favicon',
                            'url'      => false,
                            'type'     => 'media',
                            'title'    => __('Site Favicon', 'wpcharming'),
                            'default'  => '',
                            'subtitle' => __('Upload a 16px x 16px .png or .gif image that will be your favicon.', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'site_iphone_icon',
                            'url'      => false,
                            'type'     => 'media',
                            'title'    => __('Apple iPhone Icon ', 'wpcharming'),
                            'default'  => '',
                            'subtitle' => __('Custom iPhone icon (57px x 57px).', 'wpcharming'),
                        ),

                        array(
                            'id'       =>'site_iphone_icon_retina',
                            'url'      => false,
                            'type'     => 'media',
                            'title'    => __('Apple iPhone Retina Icon ', 'wpcharming'),
                            'default'  => '',
                            'subtitle' => __('Custom iPhone retina icon (114px x 114px).', 'wpcharming'),
                        ),

                        array(
                            'id'       =>'site_ipad_icon',
                            'url'      => false,
                            'type'     => 'media',
                            'title'    => __('Apple iPad Icon ', 'wpcharming'),
                            'default'  => '',
                            'subtitle' => __('Custom iPad icon (72px x 72px).', 'wpcharming'),
                        ),

                        array(
                            'id'       =>'site_ipad_icon_retina',
                            'url'      => false,
                            'type'     => 'media',
                            'title'    => __('Apple iPad Retina Icon ', 'wpcharming'),
                            'default'  => '',
                            'subtitle' => __('Custom iPad retina icon (144px x 144px).', 'wpcharming'),
                        ),
                        array(
                            'id'       => 'page_comments',
                            'type'     => 'switch',
                            'title'    => __('Enable Page Comments?', 'wpcharming'),
                            'subtitle' => __('Do you want to enable comments on single page?', 'wpcharming'),
                            'default'  => true,
                        ),
                        array(
                            'id'       => 'page_back_totop',
                            'type'     => 'switch',
                            'title'    => __('Enable Back To Top Button?', 'wpcharming'),
                            'subtitle' => __('Do you want to enable back to top button?', 'wpcharming'),
                            'default'  => true,
                        ),
                    )
                );

                /*--------------------------------------------------------*/
                /* LAYOUTS
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'title'  => __( 'Layout', 'wpcharming' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-website el-icon-large',
                    'submenu' => true,
                    'fields' => array(
                        array(
                            'id'       => 'site_boxed',
                            'type'     => 'switch',
                            'title'    => __('Boxed Version?', 'wpcharming'),
                            'subtitle' => __('Do you want to enable boxed layout?', 'wpcharming'),
                            'default'  => false,
                        ),
                        array(
                            'id'       => 'page_layout',
                            'type'     => 'button_set',
                            'title'    => __( 'Page Layout', 'wpcharming' ),
                            'subtitle' => __( 'Default page layout.', 'wpcharming' ),
                            'options'  => array(
                                'left-sidebar'  => 'Left Sidebar',
                                'no-sidebar'    => 'No Sidebar',
                                'right-sidebar' => 'Right Sidebar'
                            ),
                            'default'  => 'right-sidebar'
                        ),
                        array(
                            'id'       => 'archive_layout',
                            'type'     => 'button_set',
                            'title'    => __( 'Archive Layout', 'wpcharming' ),
                            'subtitle' => __( 'Default archive layout ( front page, category, tag, search, author, archive ).', 'wpcharming' ),
                            'options'  => array(
                                'left-sidebar'  => 'Left Sidebar',
                                'no-sidebar'    => 'No Sidebar',
                                'right-sidebar' => 'Right Sidebar'
                            ),
                            'default'  => 'right-sidebar'
                        ),
                        array(
                            'id'       => 'blog_layout',
                            'type'     => 'button_set',
                            'title'    => __( 'Blog Layout', 'wpcharming' ),
                            'subtitle' => __( 'Set your blog layout to display, include blog page and single blog post.', 'wpcharming' ),
                            'options'  => array(
                                'left-sidebar'  => 'Left Sidebar',
                                'no-sidebar'    => 'No Sidebar',
                                'right-sidebar' => 'Right Sidebar'
                            ),
                            'default'  => 'right-sidebar'
                        ),
                        array(
                            'id'       => 'single_shop_layout',
                            'type'     => 'button_set',
                            'title'    => __( 'Single WooCommerce Product Layout', 'wpcharming' ),
                            'subtitle' => __( 'Layout for single product and products archive.', 'wpcharming' ),
                            'options'  => array(
                                'left-sidebar'  => 'Left Sidebar',
                                'no-sidebar'    => 'No Sidebar',
                                'right-sidebar' => 'Right Sidebar'
                            ),
                            'default'  => 'right-sidebar'
                        ),
                    )
                );

                /*--------------------------------------------------------*/
                /* HEADER
                /*--------------------------------------------------------*/

                $this->sections[] = array(
                    'title'  => __( 'Header', 'wpcharming' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-file',
                    'submenu' => true,
                    'fields' => array(

                        array(
                            'id'       => 'header_fixed',
                            'type'     => 'switch',
                            'title'    => __('Enable fixed header on scroll.', 'wpcharming'),
                            'default'  => true,
                        ),

                        array(
                            'id'       => 'header_style',
                            'type'     => 'button_set',
                            'title'    => __( 'Header Style', 'wpcharming' ),
                            'subtitle' => __( 'Select your header style', 'wpcharming' ),
                            'options'  => array(
                                'header-default'  => 'Header 1 - Default',
                                'topbar'   => 'Header with topbar',
                                'centered' => 'Header Centered'
                            ),
                            'default'  => 'header-default'
                        ),

                        array(
                            'id'       => 'header_topbar_info',
                            'type'     => 'info',
                            'style'    => 'warning',
                            'title'    => __('Header Topbar Setup Guide', 'wpcharming'),
                            'desc'     => __('You had selected <strong>Header Topbar</strong> style. In order to display top bar elements please go to Widget page and look for TopBar Left / TopBar Right widget areas.', 'wpcharming'),
                            'required' => array('header_style','=','topbar', ),
                        ),
                        array(
                            'id'       => 'header_centered_info',
                            'type'     => 'info',
                            'style'    => 'warning',
                            'title'    => __('Header Centered Setup Guide', 'wpcharming'),
                            'desc'     => __('You had selected <strong>Header Centered</strong> style. In order to display top bar elements please go to Widget page and look for TopBar Left / TopBar Right widget areas.', 'wpcharming'),
                            'required' => array('header_style','=','centered', ),
                        ),

                        array(
                            'id'       => 'header_social',
                            'type'     => 'switch',
                            'title'    => __('Enable header social icon', 'wpcharming'),
                            'default'  => true,
                            'required' => array('header_style','=','header-default', ),
                        ),
                        array(
                            'id'       => 'header_use_social',
                            'type'     => 'checkbox',
                            'title'    => __('Select Header Social Icon?', 'wpcharming'),
                            'subtitle' => __('Which icon should display? the social icon url will be take from Social Media setting tab.', 'wpcharming'),
                            'required' => array('header_social','=',true, ),
                            'options'  => array(
                                'twitter'    => 'Twitter',
                                'facebook'   => 'Facebook',
                                'linkedin'   => 'Linkedin',
                                'pinterest'  => 'Pinterest',
                                'instagram'  => 'Instagram',
                                'flickr'     => 'Flickr',
                                'youtube'    => 'Youtube',
                                'instagram'  => 'Instagram',
                                'vk'         => 'VK',
                                'yelp'       => 'Yelp',
                                'foursquare' => 'Foursquare',
                                'email'      => 'Email',
                                'rss'        => 'RSS'
                            ),
                        ),

                        array(
                            'id'       => 'extract_1_text',
                            'type'     => 'text',
                            'title'    => __('Header Extract #1 Text', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => "",
                            'default'  => "",
                            'required' => array('header_style','=','header-default', ),
                        ),
                        array(
                            'id'       => 'extract_1_value',
                            'type'     => 'text',
                            'title'    => __('Header Extract #1 Value', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => "",
                            'default'  => "",
                            'required' => array('header_style','=','header-default', ),
                        ),

                        array(
                            'id'       => 'extract_2_text',
                            'type'     => 'text',
                            'title'    => __('Header Extract #2 Text', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => "",
                            'default'  => "",
                            'required' => array('header_style','=','header-default', ),
                        ),
                        array(
                            'id'       => 'extract_2_value',
                            'type'     => 'text',
                            'title'    => __('Header Extract #2 Value', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => "",
                            'default'  => "",
                            'required' => array('header_style','=','header-default', ),
                        ),
                        array(
                            'id'       => 'extras_value_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'title'    => __('Header Extract Value Color', 'wpcharming'),
                            'desc'     => "Change color for extract value elements above, the color will be inherit from primary color by default.",
                            'default'  => '',
                            'output'   => array(
                                'color'             => '#masthead .header-right-wrap .extract-element .phone-text'
                            ),
                            'required' => array('header_style','=','header-default', ),
                        ),


                        array(
                            'id'       => 'topbar_custom_style',
                            'type'     => 'switch',
                            'title'    => __('Custom Topbar Style?.', 'wpcharming'),
                            'default'  => false,
                        ),
                        array(
                            'id'       => 'topbar_background',
                            'type'     => 'background',
                            'compiler' => true,
                            'output'   => array('.site-topbar'),
                            'title'    => __('Topbar Background', 'wpcharming'),
                            'desc'     => '',
                            'required' => array('topbar_custom_style','=',true, ),
                            'default'  => array(
                            ),
                        ),
                        array(
                            'id'       => 'topbar_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'title'    => __('Topbar Text Color', 'wpcharming'),
                            'required' => array('topbar_custom_style','=',true, ),
                            'default'  => '',
                            'output'   => array(
                                'color'             => '.site-topbar'
                            )
                        ),
                        array(
                            'id'       => 'topbar_link_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'title'    => __('Topbar Link Color', 'wpcharming'),
                            'required' => array('topbar_custom_style','=',true, ),
                            'default'  => '',
                            'output'   => array(
                                'color'             => '.site-topbar .widget a'
                            )
                        ),
                        array(
                            'id'       => 'topbar_link_hover_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'title'    => __('Topbar Link Hover Color', 'wpcharming'),
                            'required' => array('topbar_custom_style','=',true, ),
                            'default'  => '',
                            'output'   => array(
                                'color'             => '.site-topbar .widget a:hover'
                            )
                        ),
                        array(
                            'id'       => 'topbar_primary_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'title'    => __('Topbar Primary Color', 'wpcharming'),
                            'required' => array('topbar_custom_style','=',true, ),
                            'default'  => '',
                            'output'   => array(
                                'color'             => '.site-topbar .primary-color'
                            )
                        ),
                        array(
                            'id'       => 'topbar_border_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'title'    => __('Topbar Border Color', 'wpcharming'),
                            'required' => array('topbar_custom_style','=',true, ),
                            'default'  => '',
                            'output'   => array(
                                'border-color'             => '.site-topbar, .site-topbar .topbar-left .topbar-widget,
                                                               .site-topbar .topbar-left .topbar-widget:first-child,.site-topbar .topbar-right .topbar-widget,
                                                               .site-topbar .topbar-right .topbar-widget:first-child,.site-topbar .search-form .search-field'
                            )
                        ),


                        array(
                            'id'       =>'divider_2',
                            'desc'     => '',
                            'required' => array('topbar_custom_style','=',true, ),
                            'type'     => 'divide'
                        ),


                        array(
                            'id'       => 'header_custom_style',
                            'type'     => 'switch',
                            'title'    => __('Custom Header Style?.', 'wpcharming'),
                            'default'  => false,
                        ),
                        array(
                            'id'       => 'header_background',
                            'type'     => 'background',
                            'compiler' => true,
                            'output'   => array('.header-normal .site-header'),
                            'title'    => __('Header Background', 'wpcharming'),
                            'desc'     => '',
                            'required' => array('header_custom_style','=',true, ),
                            'default'  => array(
                            ),
                        ),
                        array(
                            'id'       => 'header_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'title'    => __('Header Color', 'wpcharming'),
                            'required' => array('header_custom_style','=',true, ),
                            'default'  => '',
                            'output'   => array(
                                'color'             => '.header-normal .header-right-wrap, .site-header .header-right-wrap .header-social a i',

                                'border-color'      => '.header-normal .site-header .header-right-wrap .header-social a i'

                            )
                        ),
                        // array(
                        //     'id'       => 'header_transparent_background',
                        //     'type'     => 'background',
                        //     'compiler' => true,
                        //     'output'   => array('.header-transparent .site-header, .header-transparent .site-header.fixed-on'),
                        //     'title'    => __('Transparent Header Background', 'wpcharming'),
                        //     'desc'     => '',
                        //     'required' => array('header_custom_style','=',true, ),
                        //     'default'  => array(
                        //     ),
                        // ),
                        // array(
                        //     'id'       => 'header_transparent_color',
                        //     'type'     => 'color',
                        //     'compiler' => true,
                        //     'title'    => __('Transparent Header Color', 'wpcharming'),
                        //     'output'   => array('.header-transparent .header-right-wrap'),
                        //     'required' => array('header_custom_style','=',true, ),
                        //     'default'  => '#ffffff',
                        // ),

                    )
                );

                /*--------------------------------------------------------*/
                /* PRIMARY MENU
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'title'  => __( 'Primary Menu', 'wpcharming' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-credit-card',
                    'submenu' => true,
                    'fields' => array(
                        array(
                            'id'             =>'primary_menu_typography',
                            'type'           => 'typography',
                            'title'          => __('Primary Menu Typography', 'wpcharming'),
                            'compiler'       =>true,
                            'google'         =>true,
                            'font-backup'    =>false,
                            'text-align'     =>false,
                            'text-transform' =>true,
                            'font-weight'    =>true,
                            'all_styles'     =>false,
                            'font-style'     =>true,
                            'subsets'        =>true,
                            'font-size'      =>true,
                            'line-height'    =>false,
                            'word-spacing'   =>false,
                            'letter-spacing' =>true,
                            'color'          =>true,
                            'preview'        =>true,
                            'output'         => array('.wpc-menu a'),
                            'units'          =>'px',
                            'subtitle'       => __('Custom typography for primary menu.', 'wpcharming'),
                            'default'        => array(
                            )
                        ),
                    )
                );

                /*--------------------------------------------------------*/
                /* PAGE
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'title'  => __( 'Page', 'wpcharming' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-file-new',
                    'submenu' => true,
                    'fields' => array(

                        array(
                            'id'       => 'page_title_bg',
                            'type'     => 'background',
                            'compiler' => true,
                            'output'   => array('.page-title-wrap'),
                            'title'    => __('Page Title Background', 'wpcharming'),
                            'desc'     => 'Apply for page title, note that page title is different with Page Header which will get setting from single page.',
                            'default'  => array(
                                'background-color' => '#f8f9f9',
                            ),
                        ),
                        array(
                            'id'       => 'page_title_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'output'   => array('.page-title-wrap h1'),
                            'title'    => __('Page Title Custom Color', 'wpcharming'),
                            'desc'     => 'By default page title color will inherit from Heading color settings.',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'page_title_contact',
                            'type'     => 'switch',
                            'title'    => __('Page Title Button', 'wpcharming'),
                            'desc'     => 'Enable contact button at the right of page title area.',
                            'default'  => false,
                        ),
                        array(
                            'id'       => 'page_title_button_type',
                            'type'     => 'button_set',
                            'title'    => __( 'Button Type', 'wpcharming' ),
                            'options'  => array(
                                'btn-light'     => 'Light',
                                'btn-primary'   => 'Primary',
                                'btn-secondary' => 'Secondary',
                                'btn-ghost'     => 'Ghost'
                            ),
                            'default'  => 'btn-light',
                            'required' => array('page_title_contact','=',true, )
                        ),
                        array(
                            'id'       => 'page_title_button_text',
                            'type'     => 'text',
                            'title'    => __('Text on the button', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => "",
                            'default'  => "Get In Touch",
                            'required' => array('page_title_contact','=',true, ),
                        ),
                        array(
                            'id'       => 'page_title_button_url',
                            'type'     => 'select',
                            'data'     => 'pages',
                            'title'    => __('Where it should link to?', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => "",
                            'default'  => '',
                            'required' => array('page_title_contact','=',true, ),
                        ),
                        array(
                            'id'       => 'page_contact_button',
                            'type'     => 'select',
                            'data'     => 'pages',
                            'multi'    => true,
                            'title'    => __( 'Display that button on', 'wpcharming' ),
                            'subtitle' => __( '', 'wpcharming' ),
                            'desc'     => __( 'Select the page you want to display contact button at page title area.', 'wpcharming' ),
                            'default'  => array(''),
                            'required' => array('page_title_contact','=',true, ),
                        ),

                    )
                );

                /*--------------------------------------------------------*/
                /* PROJECT
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'title'  => __( 'Project', 'wpcharming' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-briefcase',
                    'submenu' => true,
                    'fields' => array(

                        array(
                            'id'       => 'project_slug_switch',
                            'type'     => 'switch',
                            'title'    => __('Enable custom project slug?', 'wpcharming'),
                            'desc'     => '',
                            'default'  => false,
                        ),
                        array(
                            'id'       => 'custom_project_slug',
                            'type'     => 'text',
                            'title'    => __('Custom Project Slug:', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter your project slug here, after change you will need to re-save the permalink setting again.', 'wpcharming'),
                            'default'  => '',
                            'required' => array('project_slug_switch','=',true, ),
                        ),
                        array(
                            'id'       => 'project_layout',
                            'type'     => 'button_set',
                            'title'    => __( 'Single Project Layout', 'wpcharming' ),
                            'subtitle' => __( 'Default single layout.', 'wpcharming' ),
                            'options'  => array(
                                'left-sidebar'  => 'Left Sidebar',
                                'no-sidebar'    => 'No Sidebar',
                                'right-sidebar' => 'Right Sidebar'
                            ),
                            'default'  => 'no-sidebar'
                        ),


                    )
                );

                /*--------------------------------------------------------*/
                /* STYLING
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'title'  => __( 'Styling', 'wpcharming' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-tint',
                    'submenu' => true,
                    'fields' => array(
                        array(
                            'id'       => 'primary_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'title'    => __('Primary Color Schema', 'wpcharming'),
                            'default'  => '#fab702',
                            'output'   => array(
                                'color'             => 'a, .primary-color, .wpc-menu a:hover, .wpc-menu > li.current-menu-item > a, .wpc-menu > li.current-menu-ancestor > a,
                                                       .entry-footer .post-categories li a:hover, .entry-footer .post-tags li a:hover,
                                                       .heading-404, .grid-item .grid-title a:hover, .widget a:hover, .widget #calendar_wrap a, .widget_recent_comments a,
                                                       #secondary .widget.widget_nav_menu ul li a:hover, #secondary .widget.widget_nav_menu ul li li a:hover, #secondary .widget.widget_nav_menu ul li li li a:hover,
                                                       #secondary .widget.widget_nav_menu ul li.current-menu-item a, .woocommerce ul.products li.product .price, .woocommerce .star-rating,
                                                       .iconbox-wrapper .iconbox-icon .primary, .iconbox-wrapper .iconbox-image .primary, .iconbox-wrapper a:hover,
                                                       .breadcrumbs a:hover, #comments .comment .comment-wrapper .comment-meta .comment-time:hover, #comments .comment .comment-wrapper .comment-meta .comment-reply-link:hover, #comments .comment .comment-wrapper .comment-meta .comment-edit-link:hover,
                                                       .nav-toggle-active i, .header-transparent .header-right-wrap .extract-element .phone-text, .site-header .header-right-wrap .extract-element .phone-text,
                                                       .wpb_wrapper .wpc-projects-light .esg-navigationbutton:hover, .wpb_wrapper .wpc-projects-light .esg-filterbutton:hover,.wpb_wrapper .wpc-projects-light .esg-sortbutton:hover,.wpb_wrapper .wpc-projects-light .esg-sortbutton-order:hover,.wpb_wrapper .wpc-projects-light .esg-cartbutton-order:hover,.wpb_wrapper .wpc-projects-light .esg-filterbutton.selected,
                                                       .wpb_wrapper .wpc-projects-dark .esg-navigationbutton:hover, .wpb_wrapper .wpc-projects-dark .esg-filterbutton:hover, .wpb_wrapper .wpc-projects-dark .esg-sortbutton:hover,.wpb_wrapper .wpc-projects-dark .esg-sortbutton-order:hover,.wpb_wrapper .wpc-projects-dark .esg-cartbutton-order:hover, .wpb_wrapper .wpc-projects-dark .esg-filterbutton.selected',

                                'background-color'  => 'input[type="reset"], input[type="submit"], input[type="submit"], .wpc-menu ul li a:hover,
                                                       .wpc-menu ul li.current-menu-item > a, .loop-pagination a:hover, .loop-pagination span:hover,
                                                       .loop-pagination a.current, .loop-pagination span.current, .footer-social, .tagcloud a:hover, woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt,
                                                       .woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.alt:focus, .woocommerce #respond input#submit.alt:active, .woocommerce a.button.alt:hover, .woocommerce a.button.alt:focus, .woocommerce a.button.alt:active, .woocommerce button.button.alt:hover, .woocommerce button.button.alt:focus, .woocommerce button.button.alt:active, .woocommerce input.button.alt:hover, .woocommerce input.button.alt:focus, .woocommerce input.button.alt:active,
                                                       .woocommerce span.onsale, .entry-content .wpb_content_element .wpb_tour_tabs_wrapper .wpb_tabs_nav li.ui-tabs-active a, .entry-content .wpb_content_element .wpb_accordion_header li.ui-tabs-active a,
                                                       .entry-content .wpb_content_element .wpb_accordion_wrapper .wpb_accordion_header.ui-state-active a,
                                                       .btn, .btn:hover, .btn-primary, .custom-heading .heading-line, .custom-heading .heading-line.primary,
                                                       .wpb_wrapper .eg-wpc_projects-element-1',

                                'border-color'      => 'textarea:focus, input[type="date"]:focus, input[type="datetime"]:focus, input[type="datetime-local"]:focus, input[type="email"]:focus, input[type="month"]:focus, input[type="number"]:focus, input[type="password"]:focus, input[type="search"]:focus, input[type="tel"]:focus, input[type="text"]:focus, input[type="time"]:focus, input[type="url"]:focus, input[type="week"]:focus,
                                                       .entry-content blockquote, .woocommerce ul.products li.product a img:hover, .woocommerce div.product div.images img:hover',

                                'border-left-color' => '#secondary .widget.widget_nav_menu ul li.current-menu-item a:before'

                            )
                        ),

                        array(
                            'id'       => 'secondary_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'title'    => __('Secondary Color Schema', 'wpcharming'),
                            'default'  => '#00aeef',
                            'output'   => array(
                                'color'            => '.secondary-color, .iconbox-wrapper .iconbox-icon .secondary, .iconbox-wrapper .iconbox-image .secondary',

                                'background-color' => '.btn-secondary, .custom-heading .heading-line.secondary'
                            )
                        ),

                        array(
                            'id'       => 'meta_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'title'    => __('Meta Color', 'wpcharming'),
                            'default'  => '#f8f9f9',
                            'output'   => array(
                                'background-color' => '.hentry.sticky, .entry-content blockquote, .entry-meta .sticky-label,
                                .entry-author, #comments .comment .comment-wrapper, .page-title-wrap, .widget_wpc_posts ul li,
                                .inverted-column > .wpb_wrapper, .inverted-row, div.wpcf7-response-output'
                            )
                        ),

                        array(
                            'id'       => 'border_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'title'    => __('Border Color', 'wpcharming'),
                            'default'  => '#e9e9e9',
                            'output'   => array(
                                'border-color' => 'hr, abbr, acronym, dfn, table, table > thead > tr > th, table > tbody > tr > th, table > tfoot > tr > th, table > thead > tr > td, table > tbody > tr > td, table > tfoot > tr > td,
                                fieldset, select, textarea, input[type="date"], input[type="datetime"], input[type="datetime-local"], input[type="email"], input[type="month"], input[type="number"], input[type="password"], input[type="search"], input[type="tel"], input[type="text"], input[type="time"], input[type="url"], input[type="week"],
                                .left-sidebar .content-area, .left-sidebar .sidebar, .right-sidebar .content-area, .right-sidebar .sidebar,
                                .site-header, .wpc-menu.wpc-menu-mobile, .wpc-menu.wpc-menu-mobile li, .blog .hentry, .archive .hentry, .search .hentry,
                                .page-header .page-title, .archive-title, .client-logo img, #comments .comment-list .pingback, .page-title-wrap, .page-header-wrap,
                                .portfolio-prev i, .portfolio-next i, #secondary .widget.widget_nav_menu ul li.current-menu-item a, .icon-button,
                                .woocommerce nav.woocommerce-pagination ul, .woocommerce nav.woocommerce-pagination ul li,woocommerce div.product .woocommerce-tabs ul.tabs:before, .woocommerce #content div.product .woocommerce-tabs ul.tabs:before, .woocommerce-page div.product .woocommerce-tabs ul.tabs:before, .woocommerce-page #content div.product .woocommerce-tabs ul.tabs:before,
                                .woocommerce div.product .woocommerce-tabs ul.tabs li:after, .woocommerce div.product .woocommerce-tabs ul.tabs li:before,
                                .woocommerce table.cart td.actions .coupon .input-text, .woocommerce #content table.cart td.actions .coupon .input-text, .woocommerce-page table.cart td.actions .coupon .input-text, .woocommerce-page #content table.cart td.actions .coupon .input-text,
                                .woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register,.shop-elements i, .testimonial .testimonial-content, .breadcrumbs,
                                .woocommerce-cart .cart-collaterals .cart_totals table td, .woocommerce-cart .cart-collaterals .cart_totals table th,.carousel-prev, .carousel-next,.recent-news-meta,
                                .woocommerce ul.products li.product a img, .woocommerce div.product div.images img'

                                //'border-left-color' => '.woocommerce-cart .cart-collaterals .cart_totals table td, .woocommerce-cart .cart-collaterals .cart_totals table th,.carousel-prev, .carousel-next'
                            )
                        ),

                        array(
                            'id'       => 'body_bg',
                            'type'     => 'background',
                            'compiler' => true,
                            'output'   => array('.site'),
                            'title'    => __('Site Background', 'wpcharming'),
                            'default'  => array(
                                'background-color' => '#ffffff',
                            )

                        ),
                        array(
                            'id'       => 'boxed_bg',
                            'type'     => 'background',
                            'compiler' => true,
                            'output'   => array('.layout-boxed'),
                            'title'    => __('Body Background for boxed layout', 'wpcharming'),
                            'default'  => array(
                                'background-color' => '#333333',
                            )
                        ),

                    )
                );


                /*--------------------------------------------------------*/
                /* TYPOGRAPHY
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'title'      => __('Typography', 'wpcharming'),
                    'header'     => '',
                    'desc'       => '',
                    'icon_class' => 'el-icon-large',
                    'icon'       => 'el-icon-font',
                    'submenu'    => true,
                    'fields'     => array(
                        array(
                            'id'             =>'font_body',
                            'type'           => 'typography',
                            'title'          => __('Body', 'wpcharming'),
                            'compiler'       =>true,
                            'google'         =>true,
                            'font-backup'    =>false,
                            'font-weight'    =>false,
                            'all_styles'     =>true,
                            'font-style'     =>false,
                            'subsets'        =>true,
                            'font-size'      =>true,
                            'line-height'    =>false,
                            'word-spacing'   =>false,
                            'letter-spacing' =>false,
                            'color'          =>true,
                            'preview'        =>true,
                            'output'         => array('body'),
                            'units'          =>'px',
                            'subtitle'       => __('Select custom font for your main body text.', 'wpcharming'),
                            'default'        => array(
                                'color'       =>"#777777",
                                'font-family' =>'PT Sans',
                                'font-size'   =>'14px',
                            )
                        ),
                        array(
                            'id'             =>'font_heading',
                            'type'           => 'typography',
                            'title'          => __('Heading', 'wpcharming'),
                            'compiler'       =>true,
                            'google'         =>true,
                            'font-backup'    =>false,
                            'all_styles'     =>true,
                            'font-weight'    =>true,
                            'font-style'     =>false,
                            'subsets'        =>true,
                            'font-size'      =>false,
                            'line-height'    =>false,
                            'word-spacing'   =>false,
                            'letter-spacing' =>true,
                            'color'          =>true,
                            'preview'        =>true,
                            'output'         => array('h1,h2,h3,h4,h5,h6, .font-heading'),
                            'units'          =>'px',
                            'subtitle'       => __('Select custom font for heading like h1, h2, h3, ...', 'wpcharming'),
                            'default'        => array(
                                'color'       =>"#333333",
                                'font-family' =>'Montserrat',
                            )
                        ),
                    ),
                );

                /*--------------------------------------------------------*/
                /* BLOG SETTINGS
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'title'  => __( 'Blog', 'wpcharming' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-pencil el-icon-pencil',
                    'submenu' => true,
                    'fields' => array(
                        array(
                            'id'       => 'blog_page_title',
                            'type'     => 'switch',
                            'title'    => __('Enable Blog Page Title', 'wpcharming'),
                            'subtitle' => __('Do you want to enable blog page title?', 'wpcharming'),
                            'default'  => true,
                        ),
                        array(
                            'id'       => 'blog_single_page_title',
                            'type'     => 'switch',
                            'title'    => __('Enable Blog Page Title For Single Blog Post', 'wpcharming'),
                            'subtitle' => __('Do you want to enable blog page title on single blog post?', 'wpcharming'),
                            'default'  => true,
                        ),
                        array(
                            'id'       => 'blog_single_breadcrumb',
                            'type'     => 'switch',
                            'title'    => __('Enable Breadcrumb For Single Blog Post', 'wpcharming'),
                            'subtitle' => __('Do you want to enable breadcrumb on single blog post?', 'wpcharming'),
                            'default'  => true,
                        ),

                        array(
                            'id'   =>'divider_1',
                            'desc' => '',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'blog_single_thumb',
                            'type'     => 'switch',
                            'title'    => __('Show Featured Image', 'wpcharming'),
                            'desc'     => __('Show featured image on single blog post?', 'wpcharming'),
                            'default'  => true,
                        ),
                        array(
                            'id'       => 'blog_single_author',
                            'type'     => 'switch',
                            'title'    => __('Show Author Box', 'wpcharming'),
                            'desc'     => __('Show author bio box on single blog post?', 'wpcharming'),
                            'default'  => true,
                        ),
                    )
                );

                /*--------------------------------------------------------*/
                /* FOOTER CONNECT
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'title'  => __( 'Footer Connect', 'wpcharming' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-file-new',
                    'submenu' => true,
                    'fields' => array(
                        // array(
                        //     'id'       => 'footer_newsletter',
                        //     'type'     => 'switch',
                        //     'title'    => __('Enable footer Newsletter section', 'wpcharming'),
                        //     'desc'     => __('This section will display Newsletter form in footer top section.', 'wpcharming'),
                        //     'default'  => false,
                        // ),
                        // array(
                        //     'id'       => 'newsletter_text',
                        //     'type'     => 'text',
                        //     'title'    => __('Newsletter Text', 'wpcharming'),
                        //     'subtitle' => '',
                        //     'desc'     => __('Enter newsletter text before the form.', 'wpcharming'),
                        //     'default'  => 'Newsletter',
                        // ),
                        // array(
                        //     'id'       => 'newsletter_type',
                        //     'type'     => 'button_set',
                        //     'title'    => __( 'Newsletter Type', 'wpcharming' ),
                        //     'subtitle' => __( 'Select your newsletter type.', 'wpcharming' ),
                        //     'options'  => array(
                        //         'feedburner'  => 'FeedBurner',
                        //         'mailchimp'    => 'MailChimp'
                        //     )
                        // ),
                        // array(
                        //     'id'       => 'feedburner_id',
                        //     'type'     => 'text',
                        //     'title'    => __('Feedburner ID', 'wpcharming'),
                        //     'subtitle' => '',
                        //     'desc'     => __('Enter your Feedburner ID.', 'wpcharming'),
                        //     'required' => array('newsletter_type','=','feedburner', )
                        // ),
                        // array(
                        //     'id'       => 'mailchimp_url',
                        //     'type'     => 'text',
                        //     'title'    => __('Mailchimp Action URL', 'wpcharming'),
                        //     'subtitle' => '',
                        //     'desc'     => __('You can get your MailChimp action URL by follow <a target="_blank" href="http://docs.shopify.com/manual/configuration/store-customization/communicating-with-customers/accounts-and-newsletters/where-do-i-get-my-mailchimp-form-action">this guide</a>.', 'wpcharming'),
                        //     'required' => array('newsletter_type','=','mailchimp', )
                        // ),
                        // array(
                        //     'id'   =>'divider_1',
                        //     'desc' => '',
                        //     'type' => 'divide'
                        // ),
                        array(
                            'id'       => 'footer_social',
                            'type'     => 'switch',
                            'title'    => __('Enable footer connect social icon', 'wpcharming'),
                            'default'  => false,
                        ),
                        array(
                            'id'       => 'social_text',
                            'type'     => 'text',
                            'title'    => __('Social Text', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter social text before the social icons.', 'wpcharming'),
                            'default'  => 'Follow us',
                        ),
                        array(
                            'id'       => 'footer_use_social',
                            'type'     => 'checkbox',
                            'title'    => __('Enable Social Icon?', 'wpcharming'),
                            'subtitle' => __('Which icon should display? the social icon url will be take from Social Media setting tab.', 'wpcharming'),
                            'options'  => array(
                                'twitter'    => 'Twitter',
                                'facebook'   => 'Facebook',
                                'linkedin'   => 'Linkedin',
                                'pinterest'  => 'Pinterest',
                                'instagram'  => 'Instagram',
                                'flickr'     => 'Flickr',
                                'youtube'    => 'Youtube',
                                'instagram'  => 'Instagram',
                                'vk'         => 'VK',
                                'yelp'       => 'Yelp',
                                'foursquare' => 'Foursquare',
                                'email'      => 'Email',
                                'rss'        => 'RSS'
                            ),
                        ),

                    )
                );


                /*--------------------------------------------------------*/
                /* FOOTER
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'title'  => __( 'Footer', 'wpcharming' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-photo',
                    'submenu' => true,
                    'fields' => array(
                        array(
                            'id'       => 'footer_widgets',
                            'type'     => 'switch',
                            'title'    => __('Enable footer widgets area.', 'wpcharming'),
                            'default'  => true,
                        ),
                        array(
                            'id'      => 'footer_columns',
                            'type'    => 'button_set',
                            'title'   => __( 'Footer Columns', 'wpcharming' ),
                            'desc'    => __( 'Select the number of columns you would like for your footer widgets area.', 'wpcharming' ),
                            'type'    => 'button_set',
                            'default' => '4',
                            'required' => array('footer_widgets','=',true, ),
                            'options' => array(
                                '1'   => __( '1 Columns', 'wpcharming' ),
                                '2'   => __( '2 Columns', 'wpcharming' ),
                                '3'   => __( '3 Columns', 'wpcharming' ),
                                '4'   => __( '4 Columns', 'wpcharming' ),
                            ),
                        ),
                        array(
                            'id'       =>'footer_copyright',
                            'type'     => 'textarea',
                            'title'    => __('Footer Copyright', 'wpcharming'),
                            'subtitle' => __('Enter the copyright section text.', 'wpcharming'),
                        ),

                        array(
                            'id'       => 'footer_custom_color',
                            'type'     => 'switch',
                            'title'    => __('Custom your footer style?.', 'wpcharming'),
                            'default'  => false,
                        ),
                        array(
                            'id'       => 'footer_bg',
                            'type'     => 'background',
                            'compiler' => true,
                            'output'   => array('.site-footer'),
                            'title'    => __('Footer Background', 'wpcharming'),
                            'required' => array('footer_custom_color','=',true, ),
                            'default'  => array(
                                'background-color' => '#111111',
                            )
                        ),
                        array(
                            'id'       => 'footer_widget_title_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'output'   => array('.site-footer .footer-columns .footer-column .widget .widget-title'),
                            'title'    => __('Footer Widget Title Color', 'wpcharming'),
                            'default'  => '#eeeeee',
                            'required' => array('footer_custom_color','=',true, )
                        ),
                        array(
                            'id'       => 'footer_text_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'output'   => array('.site-footer, .site-footer .widget, .site-footer p'),
                            'title'    => __('Footer Text Color', 'wpcharming'),
                            'default'  => '#999999',
                            'required' => array('footer_custom_color','=',true, )
                        ),
                        array(
                            'id'       => 'footer_link_color',
                            'type'     => 'color',
                            'compiler' => true,
                            'output'   => array('.site-footer a, .site-footer .widget a'),
                            'title'    => __('Footer Link Color', 'wpcharming'),
                            'default'  => '#dddddd',
                            'required' => array('footer_custom_color','=',true, )
                        ),
                        array(
                            'id'       => 'footer_link_color_hover',
                            'type'     => 'color',
                            'compiler' => true,
                            'output'   => array('.site-footer a:hover, .site-footer .widget a:hover'),
                            'title'    => __('Footer Link Color Hover', 'wpcharming'),
                            'default'  => '#ffffff',
                            'required' => array('footer_custom_color','=',true, )
                        ),
                        array(
                            'id'       => 'site_info_bg',
                            'type'     => 'background',
                            'compiler' => true,
                            'output'   => array('.site-info-wrapper'),
                            'title'    => __('Site Info Background', 'wpcharming'),
                            'required' => array('footer_custom_color','=',true, ),
                            'default'  => array(
                            )

                        ),
                    )
                );

                /*--------------------------------------------------------*/
                /* SOCIAL
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'title'  => __( 'Social Media', 'wpcharming' ),
                    'desc'   => 'Enter social url here and then active them in footer or header options. Please add full URLs include "http://".',
                    'icon'   => 'el-icon-address-book',
                    'submenu' => true,
                    'fields' => array(
                        array(
                            'id'       =>'twitter',
                            'type'     => 'text',
                            'title'    => __('Twitter', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter your Twitter URL.', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'facebook',
                            'type'     => 'text',
                            'title'    => __('Facebook', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter your Facebook URL.', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'linkedin',
                            'type'     => 'text',
                            'title'    => __('Linkedin', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter your Linkedin URL.', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'pinterest',
                            'type'     => 'text',
                            'title'    => __('Pinterest', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter your Pinterest URL.', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'instagram',
                            'type'     => 'text',
                            'title'    => __('Instagram', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter your Instagram URL.', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'vk',
                            'type'     => 'text',
                            'title'    => __('VK', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter your VK URL.', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'yelp',
                            'type'     => 'text',
                            'title'    => __('Yelp', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter your Yelp URL.', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'foursquare',
                            'type'     => 'text',
                            'title'    => __('Foursquare', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter your foursquare URL.', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'flickr',
                            'type'     => 'text',
                            'title'    => __('Flickr', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter your Flickr URL.', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'youtube',
                            'type'     => 'text',
                            'title'    => __('Youtube', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter your Youtube URL.', 'wpcharming'),
                        ),

                        array(
                            'id'       =>'email',
                            'type'     => 'text',
                            'title'    => __('Email', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter your Email URL.', 'wpcharming'),
                        ),

                        array(
                            'id'       =>'rss',
                            'type'     => 'text',
                            'title'    => __('RSS', 'wpcharming'),
                            'subtitle' => '',
                            'desc'     => __('Enter your website RSS URL.', 'wpcharming'),
                        ),

                    )
                );

                /*--------------------------------------------------------*/
                /* TRACKING CODE
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'icon'       => 'el-icon-screenshot',
                    'icon_class' => 'el-icon-large',
                    'title'      => __('Custom Codes', 'wpcharming'),
                    'submenu'    => true,
                    'fields'     => array(
                        array(
                            'id'       =>'site_header_tracking',
                            'type'     => 'textarea',
                            'theme'    => 'chrome',
                            'title'    => __('Header Custom Codes', 'wpcharming'),
                            'subtitle' => __('It will apply to wp_head hook.', 'wpcharming'),
                        ),
                        array(
                            'id'       =>'site_footer_tracking',
                            'type'     => 'textarea',
                            'theme'    => 'chrome',
                            'title'    => __('Footer Custom Codes', 'wpcharming'),
                            'subtitle' => __('It will apply to wp_footer hook, recommend for Google Analytic (Remember to include the entire script from google, if you just enter your tracking ID it will not work.) ', 'wpcharming'),
                        ),
                    )
                );

                /*--------------------------------------------------------*/
                /* CUSTOM CSS
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'icon'       => 'el-icon-css',
                    'icon_class' => 'el-icon-large',
                    'title'      => __('Custom CSS', 'wpcharming'),
                    'submenu'    => true,
                    'fields'     => array(
                        array(
                            'id'       => 'site_css',
                            'type'     => 'ace_editor',
                            'title'    => __( 'CSS Code', 'wpcharming' ),
                            'subtitle' => __( 'Paste your custom CSS code here.', 'wpcharming' ),
                            'mode'     => 'css',
                            'theme'    => 'monokai',
                            'desc'     => 'Possible modes can be found at <a href="'. esc_url( 'http://ace.c9.io' ) .'" target="_blank">'. esc_attr( 'http://ace.c9.io' ) .'</a>.',
                            'default'  => ""
                        ),
                    )
                );

                /*--------------------------------------------------------*/
                /* AUTO UPDATE
                /*--------------------------------------------------------*/
                $this->sections[] = array(
                    'icon'       => 'el-icon-random',
                    'icon_class' => 'el-icon-large',
                    'title'      => __('One Click Update', 'wpcharming'),
                    'desc'       => '',
                    'submenu'    => true,
                    'fields'     => array(
                        array(
                            'id'    => 'info_warning',
                            'type'  => 'info',
                            'title' => __('Envato Market Plugin: ', 'wpcharming'),
                            'style' => 'warning',
                            'desc'  => __('You can now use Envato Market plugin to enable auto udpate for all your ThemeForest\'s themes, download it here: <a target="_blank" href="https://github.com/envato/wp-envato-market">Envato Market plugin</a>, or read the <a target="_blank" href="http://www.wpexplorer.com/envato-market-plugin-guide/">detail guide</a> on WPExplorer.', 'wpcharming')
                        )
                    )
                );

                /*--------------------------------------------------------*/
                /*
                /*--------------------------------------------------------*/


            }

        }

        global $reduxConfig;
        $reduxConfig = new WPCharming_Options_Config();

        // Retrieve theme option values
        if ( ! function_exists('wpcharming_option') ) {
            function wpcharming_option($id, $fallback = false, $key = false ) {
                global $wpc_option;
                if ( $fallback == false ) $fallback = '';
                $output = ( isset($wpc_option[$id]) && $wpc_option[$id] !== '' ) ? $wpc_option[$id] : $fallback;
                if ( !empty($wpc_option[$id]) && $key ) {
                    $output = $wpc_option[$id][$key];
                }
                return $output;
            }
        }
    }
