<?php
namespace LWS\WOOREWARDS\Ui;

// don't call the file directly
if( !defined( 'ABSPATH' ) ) exit();

/** Create the backend menu and settings pages. */
class Admin
{
	public function __construct()
	{
		/** @param array, the fields settings array. @param Pool */
		\add_filter('lws_woorewards_admin_pool_general_settings', array($this, 'getPoolGeneralSettings'), 10, 2);

		lws_register_pages($this->managePages());
		\add_action('admin_enqueue_scripts', array($this , 'scripts'));

		// replace usual notice by a badge teaser
		if( !defined('LWS_WOOREWARDS_ACTIVATED') || !LWS_WOOREWARDS_ACTIVATED )
			\add_filter('pre_set_transient_settings_errors', array($this, 'noticeSettingsSaved'));

		$this->checkCouponsEnabled();
	}

	protected function checkCouponsEnabled()
	{
		if( defined('DOING_AJAX') && DOING_AJAX )
			return;

		if( function_exists('\wc_coupons_enabled') && !\wc_coupons_enabled() && !\get_option('lws_woorewards_ignore_woocommerce_disable_coupons') )
		{
			$ignore = false;
			if( isset($_GET['lws_wr_wc_coupons_enable']) && in_array($_GET['lws_wr_wc_coupons_enable'], array('yes', 'ignore')) )
			{
				if( $_GET['lws_wr_wc_coupons_enable'] == 'yes' )
				{
					\update_option('woocommerce_enable_coupons', 'yes');
					\update_option('lws_woorewards_ignore_woocommerce_disable_coupons', '');
				}
				else
					\update_option('lws_woorewards_ignore_woocommerce_disable_coupons', 'yes');
				$ignore = true;
			}

			if( !$ignore )
			{
				$message = array(
					__("WooCommerce Coupons are disabled. Several WooRewards features will be broken without Coupons.", 'woorewards-lite'),
					__("You can check your WooCommerce General Settings and look for : <b>Enable coupons</b>.", 'woorewards-lite'),
					sprintf(
						__('%1$s or %2$s this warning at your own risks.', 'woorewards-lite'),
						sprintf(
							"<a href='%s' class='button primary'>%s</a>",
							\esc_attr(\add_query_arg('lws_wr_wc_coupons_enable', 'yes')),
							__("Click here to resolve the problem immediately", 'woorewards-lite')
						),
						sprintf(
							"<a href='%s' class=''>%s</a>",
							\esc_attr(\add_query_arg('lws_wr_wc_coupons_enable', 'ignore')),
							__("ignore", 'woorewards-lite')
						)
					),
				);
				\lws_admin_add_notice_once('woocommerce_enable_coupons', implode('<br/>', $message), array('level'=>'error'));
			}
		}
	}

	public function scripts($hook)
	{
		// Force the menu icon with lws-icons font
		\wp_enqueue_style('wr-menu-icon', LWS_WOOREWARDS_CSS . '/menu-icon.css', array(), LWS_WOOREWARDS_VERSION);

		\wp_register_script('lws_wre_system_selector', LWS_WOOREWARDS_JS . '/poolsettings.js', array('lws-base64'), LWS_WOOREWARDS_VERSION, true);
		\wp_register_style('lws_wre_system_selector', LWS_WOOREWARDS_CSS . '/poolsettings.css', array(), LWS_WOOREWARDS_VERSION);

		if( false !== ($ppos = strpos($hook, LWS_WOOREWARDS_PAGE)) )
		{
			$page = substr($hook, $ppos);
			$tab = isset($_GET['tab']) ? $_GET['tab'] : '';

			if( !defined('LWS_WOOREWARDS_ACTIVATED') || !LWS_WOOREWARDS_ACTIVATED )
			{
				// let badge teaser replace the notice
				\wp_enqueue_style('lws-wre-notice', LWS_WOOREWARDS_CSS . '/notice.css', array(), LWS_WOOREWARDS_VERSION);
			}

			if( ($page == LWS_WOOREWARDS_PAGE || false !== strpos($page, 'customers')) && (empty($tab) || strpos($tab, 'wr_customers') !== false) )
			{
				// labels displayed in points history
				$labels = array(
					'hist' => __("Points History", 'woorewards-lite'),
					'desc' => __("Description", 'woorewards-lite'),
					'date' => __("Date", 'woorewards-lite'),
					'points' => __("Points", 'woorewards-lite'),
					'total' => __("Total", 'woorewards-lite'),
				);
				// enqueue editlist column folding script
				foreach( ($deps = array('jquery', 'lws-tools')) as $dep )
					\wp_enqueue_script($dep);

				\wp_register_script('lws-wre-userspoints', LWS_WOOREWARDS_JS . '/userspoints.js', $deps, LWS_WOOREWARDS_VERSION, true);
				\wp_localize_script('lws-wre-userspoints', 'lws_wr_userspoints_labels', $labels);
				\wp_enqueue_script('lws-wre-userspoints');
				\wp_enqueue_style('lws-wre-userspoints', LWS_WOOREWARDS_CSS . '/userspoints.css', array(), LWS_WOOREWARDS_VERSION);

				\do_action('lws_adminpanel_enqueue_lac_scripts', array('select'));
				\do_action('lws_woorewards_ui_userspoints_enqueue_scripts', $hook, $tab);
			}
			else if( false !== strpos($page, 'loyalty') )
			{
				\do_action('lws_adminpanel_enqueue_lac_scripts', array('select'));
				\wp_enqueue_script('lws-checkbox');
				\wp_enqueue_script('lws-switch');
			}
			else if( false !== strpos($page, 'appearance') )
			{
				\wp_enqueue_style('lws_wr_pointsoncart_hard', LWS_WOOREWARDS_CSS.'/pointsoncart.css', array(), LWS_WOOREWARDS_VERSION);
			}

			\wp_enqueue_script('lws-wre-coupon-edit', LWS_WOOREWARDS_JS . '/couponedit.js', array('jquery'), LWS_WOOREWARDS_VERSION, true);
		}
	}

	/** Push an achievement teaser instead our usual notice at setting save. */
	public function noticeSettingsSaved($value)
	{
		if( !empty($value) && isset($_POST['option_page']) && false !== strpos($_POST['option_page'], LWS_WOOREWARDS_PAGE) )
		{
			$val = \current($value);
			if( isset($val['type']) && $val['type'] == 'updated' && isset($val['code']) && $val['code'] == 'settings_updated' )
			{
				$teasers = array(
					__("Add fun and achievements for your customers with the <a>Pro Version</a>", 'woorewards-lite'),
					__("Try the <a>Pro Version</a> for free for 30 days", 'woorewards-lite'),
					__("The <a>Pro Version</a> adds Events and Levelling systems. Try <a>it</a>", 'woorewards-lite')
				);
				\LWS_WooRewards::achievement(array(
					'title'   => __("Your settings have been saved.", 'woorewards-lite'),
					'message' => str_replace(
						'<a>',
						"<a href='https://plugins.longwatchstudio.com/product/woorewards/' target='_blank'>",
						$teasers[rand(0, count($teasers)-1)]
					)
				));
			}
		}
		return $value;
	}

	protected function managePages()
	{
		$pages = array();
		$pages['wr_resume'] = $this->getResumePage();
		$pages['wr_customers'] = $this->getCustomerPage();
		if( false === ($pages['wr_loyalty'] = \apply_filters('lws_woorewards_ui_loyalty_tab_get', false)) )
		{
			$pages['wr_loyalty'] = $this->getLoyaltyPage();
		}
		if( defined('LWS_WIZARD_SUMMONER') )
		{
			$pages['wr_wizard'] = $this->getWizardPage();
		}
		$pages['wr_features'] = $this->getFeaturesPage();
		$pages['wr_appearance'] = $this->getAppearancePage();
		$pages['wr_system'] = $this->getSystemPage();

		return $pages;
	}

	protected function getResumePage()
	{
		$resumePage = array(
			'title'	    => __("WooRewards", 'woorewards-lite'),
			'id'	      => LWS_WOOREWARDS_PAGE,
			'rights'    => 'manage_rewards',
			'dashicons' => '',
			'index'     => 57,
			'resume'    => true,
			'tabs'	    => array(
				'wr_customers' => array(
					'title'  => __("Customers", 'woorewards-lite'),
					'id'     => 'resume_customers',
				)
			)
		);
		return $resumePage;
	}

	protected function getCustomerPage()
	{
		require_once LWS_WOOREWARDS_INCLUDES . '/ui/editlists/userspoints.php';
		require_once LWS_WOOREWARDS_INCLUDES . '/ui/editlists/userspointsbulkaction.php';
		$editlist = \lws_editlist(
			'userspoints',
			'user_id',
			new \LWS\WOOREWARDS\Ui\Editlists\UsersPoints(),
			\LWS\Adminpanel\EditList::FIX,
			\apply_filters('lws_woorewards_ui_userspoints_filters', array(
				'user_search' => new \LWS\Adminpanel\EditList\FilterSimpleField('usersearch', __('Search...', 'woorewards-lite')),
				'points_add'  => new \LWS\WOOREWARDS\Ui\Editlists\UsersPointsBulkAction('points_add')
			))
		);

		$description = array(
			__("The customers page lets you track your customers loyalty activity and perform some actions :", 'woorewards-lite'),
			__("See customers points and history", 'woorewards-lite'),
			__("See customers owned coupons", 'woorewards-lite'),
			__("Add/Subtract Points", 'woorewards-lite'),
			__("Add/Remove Rewards (Pro Version Only)", 'woorewards-lite'),
			__("Add/Remove Badges (Pro Version Only)", 'woorewards-lite'),
			__("Filter by points, activity or inactivity periods (Pro Version Only)", 'woorewards-lite'),
		);
		$cusPage = array(
			'title'    => __("Customers", 'woorewards-lite'),
			'id'       => LWS_WOOREWARDS_PAGE . '.customers',
			'rights'   => 'manage_rewards',
			'color' => '#A8CE38',
			'image'		=> LWS_WOOREWARDS_IMG . '/r-customers.png',
			'description' => "<p>" . $description[0] . "</p><ul>" .
			"<li><span>" . $description[1] . "</span></li><li><span>" . $description[2] . "</span></li><li><span>" . $description[3] . "</span></li>" .
			"<li><span>" . $description[4] . "</span></li><li><span>" . $description[5] . "</span></li><li><span>" . $description[6] . "</span></li></ul>",
			'tabs'     => array(
				'wr_customers' => array(
					'title'    => __("Customers", 'woorewards-lite'),
					'id'       => 'wr_customers',
					'groups'   => array(
						'customers_points' => array(
							'title'		=> __("Points Management", 'woorewards-lite'),
							'icon'		=> 'lws-icon-users',
							'color'		=> '#00768b',
							'text'		=> __("Here you can see and manage your customers reward points", 'woorewards-lite')
								. "<br/>" . __("You can view the points <b>history</b> by clicking the points total in the table", 'woorewards-lite'),
							'extra'    => array('doclink' => 'https://plugins.longwatchstudio.com/docs/woorewards/customers-management/'),
							'editlist' => $editlist,
						)
					)
				)
			)
		);
		return $cusPage;
	}

	protected function getLoyaltyPage()
	{
		$description = array(
			__("Setup how customers will earn points and what rewards they can get.", 'woorewards-lite'),
			__("In WooRewards Lite, there's only 3 ways to earn points (20+ in WooRewards Pro) :", 'woorewards-lite'),
			array(
				array(__("Place an order :", 'woorewards-lite'),
					__("Earn points when placing an order, independently of the order's amount. ", 'woorewards-lite'),
				),
				array(__("Spend Money :", 'woorewards-lite'),
					__("Earn points when placing an order, depending on the order's amount.", 'woorewards-lite'),
				),
				array(__("Review a product :", 'woorewards-lite'),
					__("Earn points by reviewing a product on your website.", 'woorewards-lite'),
				),
			),
			__("In WooRewards Lite, you can choose between 2 rewards (10+ in WooRewards Pro) :", 'woorewards-lite'),
			array(
				array(__("Coupon :", 'woorewards-lite'),
					__("Points are converted into WooCommerce coupons that customers can use on their cart.", 'woorewards-lite'),
				),
				array(__("Points on Cart :", 'woorewards-lite'),
					__("Set the value of a point and customers will use their points on the cart to get immediate discounts.", 'woorewards-lite'),
				),
			)
		);

		$loyaltyPage = array(
			'title'    => __("Points and Rewards", 'woorewards-lite'),
			'rights'   => 'manage_rewards',
			'id'       => LWS_WOOREWARDS_PAGE . '.loyalty',
			'color' => '#526981',
			'image'		=> LWS_WOOREWARDS_IMG . '/r-loyalty-systems.png',
			'description' => "<p>" . $description[0] . " " . $description[1] . "</p><ul>" .
			"<li><span><strong>" . $description[2][0][0] . "</strong> " . $description[2][0][1] . "</span></li>" .
			"<li><span><strong>" . $description[2][1][0] . "</strong> " . $description[2][1][1] . "</span></li>" .
			"<li><span><strong>" . $description[2][2][0] . "</strong> " . $description[2][2][1] . "</span></li>" .
			"</ul><p>" . $description[3] . "</p><ul>" .
			"<li><span><strong>" . $description[4][0][0] . "</strong> " . $description[4][0][1] . "</span></li>" .
			"<li><span><strong>" . $description[4][1][0] . "</strong> " . $description[4][1][1] . "</span></li>" .
			"</ul>",
			'tabs' => array(
				'wr_loyalty' => array(
					'title'    => __("Points and Rewards", 'woorewards-lite'),
					'id'       => 'wr_loyalty',
					'groups'   => $this->getLoyaltyGroups()
				)
			)
		);
		return $loyaltyPage;
	}

	protected function getWizardPage()
	{
		$description = array(
			__("Wizards are made to help you get started or create some specific loyalty systems more easily.", 'woorewards-lite'),
			__("Select a wizard, follow the setup steps and everything will be generated automatically according to your preferences.", 'woorewards-lite'),
			__("Here are the wizards available :", 'woorewards-lite'),
			__("Standard System :", 'woorewards-lite'),
			__("Set up a standard system to let customers win coupons", 'woorewards-lite'),
			__("Leveling System :", 'woorewards-lite'),
			__("This wizard will help you create a bronze/silver/gold system (Pro Version Only)", 'woorewards-lite'),
			__("Special Events :", 'woorewards-lite'),
			__("Loyalty systems for special occasions like Christmas (Pro Version Only)", 'woorewards-lite'),
			__("Double Points :", 'woorewards-lite'),
			__("Create an event where customers can earn twice the points (Pro Version Only)", 'woorewards-lite'),
			__("Sponsorship :", 'woorewards-lite'),
			__("Sponsors and sponsored are rewarded in this loyalty system (Pro Version Only)", 'woorewards-lite'),
			__("Birthday or Anniversary ", 'woorewards-lite'),
			__("Send a special gift on customers birthday or registration anniversary (Pro Version Only)", 'woorewards-lite'),
		);
		$wizardPage = array(
			'title'			=> __("Wizard", 'woorewards-lite'),
			'subtitle'		=> __("Wizard", 'woorewards-lite'),
			'id'			=> LWS_WIZARD_SUMMONER.LWS_WOOREWARDS_PAGE,
			'rights'		=> 'manage_rewards',
			'color'			=> '#00B7EB',
			'image'			=> LWS_WOOREWARDS_IMG . '/r-wizard.png',
			'description'	=> "<p>" . $description[0] . " " . $description[1] . " " . $description[2] . "</p>"
			."<ul>"
			."<li><span><strong>" . $description[3] . "</strong> " . $description[4] . "</span></li>"
			."<li><span><strong>" . $description[5] . "</strong> " . $description[6] . "</span></li>"
			."<li><span><strong>" . $description[7] . "</strong> " . $description[8] . "</span></li>"
			."<li><span><strong>" . $description[9] . "</strong> " . $description[10] . "</span></li>"
			."<li><span><strong>" . $description[11] . "</strong> " . $description[12] . "</span></li>"
			."<li><span><strong>" . $description[13] . "</strong> " . $description[14] . "</span></li>"
			."</ul>",
		);
		return $wizardPage;
	}

	protected function getOrderStatusList()
	{
		if (\function_exists('\wc_get_order_statuses'))
		{
			$orderStatus = array();
			foreach( \wc_get_order_statuses() as $value => $label )
			{
				if (substr($value, 0, 3) == 'wc-')
					$value = substr($value, 3);
				$orderStatus[] = array('value' => $value, 'label' => $label);
			}
			return $orderStatus;
		}
		else
		{
			return array(
				array('value' => 'pending'   , 'label' => __("Pending payment", 'woorewards-lite')),
				array('value' => 'processing', 'label' => __("Processing", 'woorewards-lite')),
				array('value' => 'on-hold'   , 'label' => __("On hold", 'woorewards-lite')),
				array('value' => 'completed' , 'label' => __("Completed", 'woorewards-lite')),
				array('value' => 'cancelled' , 'label' => __("Cancelled", 'woorewards-lite')),
				array('value' => 'refunded'  , 'label' => __("Refunded", 'woorewards-lite')),
				array('value' => 'failed'    , 'label' => __("Failed", 'woorewards-lite')),
			);
		}
	}

	protected function getFeaturesPage()
	{
		$description = array(
			__("Activate and set up different WooRewards features in this section : ", 'woorewards-lite'),
			__("WooCommerce :", 'woorewards-lite'),
			__("WooCommerce related rules and additional loyalty information on woocommerce pages", 'woorewards-lite'),
			__("Sponsorship :", 'woorewards-lite'),
			__("Set up the sponsorship options (Pro Version Only)", 'woorewards-lite'),
			__("Badges and Achievements :", 'woorewards-lite'),
			__("Create and manage user badges, achievements and badges rarity (Pro Version Only)", 'woorewards-lite'),
			__("API :", 'woorewards-lite'),
			__("Set up the API to connect WooRewards with a third party app (Pro Version Only)", 'woorewards-lite'),
		);

		$featuresPage = array(
			'title'    => __("Features", 'woorewards-lite'),
			'subtitle' => __("Features", 'woorewards-lite'),
			'id'       => LWS_WOOREWARDS_PAGE . '.settings',
			'rights'   => 'manage_rewards',
			'color'			=> '#7AC943',
			'image'			=> LWS_WOOREWARDS_IMG . '/r-features.png',
			'description'	=> "<p>" . $description[0] . "</p>"
			. "<ul>"
			. "<li><span><strong>" . $description[1] . "</strong> " . $description[2] . "</span></li>"
			. "<li><span><strong>" . $description[3] . "</strong> " . $description[4] . "</span></li>"
			. "<li><span><strong>" . $description[5] . "</strong> " . $description[6] . "</span></li>"
			. "</ul>",
			'tabs'     => array(
				'wc_settings' => array(
					'id'	=> 'wc_settings',
					'title'	=>  __("WooCommerce", 'woorewards-lite'),
					'icon'	=> 'lws-icon-cart-2',
					'groups' => array(
						'settings' => array(
							'id'     => 'settings',
							'icon'	 => 'lws-icon-settings-gear',
							'title'  => __("General settings", 'woorewards-lite'),
							'text'   => __("Check the options below according to your needs. If you want to exclude shipping fees from points calculation, it can be done inside your loyalty systems.", 'woorewards-lite'),
							'extra'    => array('doclink' => 'https://plugins.longwatchstudio.com/docs/woorewards/other-settings/general-settings/'),
							'fields' => array(
								'inc_taxes' => array(
									'id'    => 'lws_woorewards_order_amount_includes_taxes',
									'title' => __("Includes taxes", 'woorewards-lite'),
									'type'  => 'box',
									'extra' => array(
										'class' => 'lws_checkbox',
										'help'=>__("If checked, taxes will be included in the points earned when spending money", 'woorewards-lite'),
									)
								),
								'order_state' => array(
									'id'    => 'lws_woorewards_points_distribution_status',
									'title' => __("Order statuses for points", 'woorewards-lite'),
									'type'  => 'lacchecklist',
									'extra' => array(
										'source' => $this->getOrderStatusList(),
										'help' => __("Default state to get points is the processing order status.<br/>If you want to use other statuses instead (recommanded), select them here", 'woorewards-lite'),
									)
								)
							)
						),
					)
				)
			)
		);
		return $featuresPage;
	}

	protected function getAppearancePage()
	{
		$description = array(
			__("Set the appearance of everything your customers will see on your website regarding loyalty systems : ", 'woorewards-lite'),
			__("Widgets :", 'woorewards-lite'),
			__("Setup the widgets/shortcodes options and appearance", 'woorewards-lite'),
			__("Emails :", 'woorewards-lite'),
			__("Customize emails sent to your customers", 'woorewards-lite'),
			__("Popup :", 'woorewards-lite'),
			__("Setup the popup that your customers see when they unlock a reward (Pro Version Only)", 'woorewards-lite'),
		);
		$appearancePage = array(
			'title'    => __("Appearance", 'woorewards-lite'),
			'subtitle' => __("Appearance", 'woorewards-lite'),
			'id'       => LWS_WOOREWARDS_PAGE . '.appearance',
			'rights'   => 'manage_rewards',
			'color'			=> '#4CBB41',
			'image'			=> LWS_WOOREWARDS_IMG . '/r-appearance.png',
			'description'	=> "<p>" . $description[0] . "</p>"
			. "<ul>"
			. "<li><span><strong>" . $description[1] . "</strong> " . $description[2] . "</span></li>"
			. "<li><span><strong>" . $description[3] . "</strong> " . $description[4] . "</span></li>"
			. "<li><span><strong>" . $description[5] . "</strong> " . $description[6] . "</span></li>"
			. "</ul>",
			'tabs'     => array(
				'sty_widgets' => $this->getWidgetsTab(),
				'shortcodes' => $this->getShortcodesTab(),
				'sty_mails' => array(
					'id'     => 'sty_mails',
					'title'  => __("Emails", 'woorewards-lite'),
					'icon'	 => 'lws-icon-letter',
					'groups' => \lws_mail_settings(\apply_filters('lws_woorewards_mails', array()))
				),
			)
		);
		return $appearancePage;
	}

	protected function getSystemPage()
	{
		$description = array(
			__("Manage your license and different system related options :", 'woorewards-lite'),
			__("Data Management :", 'woorewards-lite'),
			__("Manage WooRewards data in the database, process past orders", 'woorewards-lite'),
			__("License :", 'woorewards-lite'),
			__("Manage your license and subscription", 'woorewards-lite'),
		);
		$systemPage = array(
			'title'    		=> __("System", 'woorewards-lite'),
			'subtitle' 		=> __("System", 'woorewards-lite'),
			'id'       		=> LWS_WOOREWARDS_PAGE . '.system',
			'rights'   		=> 'manage_rewards',
			'color'			=> '#7958A5',
			'image'			=> LWS_WOOREWARDS_IMG . '/r-system.png',
			'description'	=> "<p>" . $description[0] . "</p>"
			. "<ul>"
			. "<li><span><strong>" . $description[1] . "</strong> " . $description[2] . "</span></li>"
			. "<li><span><strong>" . $description[3] . "</strong> " . $description[4] . "</span></li>"
			. "</ul>",
			'tabs'			=> array(
				'data_management' => array(
					'id'     => 'data_management',
					'title'  => __("Data Management", 'woorewards-lite'),
					'icon'   => 'lws-icon-components',
					'groups' => array(
						'wc_old_orders' => array(
							'id' => 'wc_old_orders',
							'icon'	 => 'lws-icon-repeat',
							'title' => __("Give Points for Past orders", 'woorewards-lite'),
							'text' => __("If you want to give points for orders that pre-existed your loyalty system, you can do it here", 'woorewards-lite')
								. '<br/>' . __("This operation can take several minutes. Depending on you server configuration and date range, you should run this operation several times on short dates ranges.", 'woorewards-lite'),
							'fields' => array(
								'date_min' => array(
									'id'    => 'date_min',
									'title' => __("Start date", 'woorewards-lite'),
									'type'  => 'input',
									'extra' => array(
										'type'     => 'date',
										'gizmo'    => true,
										'class'    => 'lws-ignore-confirm',
										'default'  => \date_create()->sub(new \DateInterval('P1M'))->format('Y-m-d'),
									),
								),
								'date_max' => array(
									'id' => 'date_max',
									'title' => __("End date", 'woorewards-lite'),
									'type'  => 'input',
									'extra' => array(
										'type'     => 'date',
										'gizmo'    => true,
										'class'    => 'lws-ignore-confirm',
										'default'  => \date('Y-m-d'),
									),
								),
								'trigger_orders' => array(
									'id' => 'trigger_orders',
									'title' => __("Launch the procedure", 'woorewards-lite'),
									'type' => 'button',
									'extra' => array(
										'callback' => array($this, 'forceOldOrdersTrigger')
									),
								),
							)
						),
						'delete' => array(
							'id'    => 'delete',
							'title' => __("Delete all data", 'woorewards-lite'),
							'icon'  => 'lws-icon-delete-forever',
							'text'  => __("Remove all loyalty systems, user points and all WooRewards relative data.", 'woorewards-lite')
							. '<br/>' . __("Use it with care since this action is <b>not undoable</b>.", 'woorewards-lite'),
							'fields' => array(
								'trigger_delete' => array(
									'id' => 'trigger_delete_all_woorewards',
									'title' => __("Delete All Data", 'woorewards-lite'),
									'type' => 'button',
									'extra' => array(
										'callback' => array($this, 'deleteAllData')
									),
								),
							)
						),
					)
				),
			)
		);
		//error_log(print_r($systemPage,true));
		return $systemPage;
	}

	protected function getWidgetsTab()
	{
		return array(
			'id'     => 'sty_widgets',
			'title'  => __("Widgets", 'woorewards-lite'),
			'icon'   => 'lws-icon-components',
			'groups' => array(
				'showpoints' => array(
					'id' => 'showpoints',
					'icon' => 'lws-icon-components',
					'title' => __("Display Points Widget", 'woorewards-lite'),
					'extra'    => array('doclink' => 'https://plugins.longwatchstudio.com/docs/woorewards/widgets-and-shortcodes/display-points/'),
					'text' => __("In this Widget, customers can see their Reward Points available.", 'woorewards-lite'),
					'fields' => array(
						'spunconnected' => array(
							'id' => 'lws_wooreward_showpoints_nouser',
							'title' => __("Text displayed if user not connected", 'woorewards-lite'),
							'type' => 'text',
							'extra' => array(
								'size' => '50',
								'placeholder' => __("Please log in if you want to see your loyalty points", 'woorewards-lite'),
							)
						),
						'showpoints' => array(
							'id' => 'lws_woorewards_displaypoints_template',
							'type' => 'stygen',
							'extra' => array(
								'purpose' => 'filter',
								'template' => 'wr_display_points',
								'html'=> false,
								'css'=>LWS_WOOREWARDS_CSS.'/templates/displaypoints.css',
								'help' => __("Here you can customize the look and displayed text of the shortcode/widget", 'woorewards-lite'),
								'subids' => array(
									'lws_woorewards_displaypoints_title' => "WooRewards Show Points - title", // no translation on purpose
									'lws_woorewards_button_more_details' => "WooRewards Show Points - details", // no translation on purpose
								)
							)
						),
					)
				),
				'pointsoncart' => array(
					'id'     => 'pointsoncart',
					'icon'	 => 'lws-icon-coins',
					'title'  => __("Points On Cart", 'woorewards-lite'),
					'text'	 => __("Select where the Points on Cart tool will be displayed and how it will look", 'woorewards-lite'),
					'fields' => array(
						'cartdisplay' => array(
							'id'    => 'lws_woorewards_points_to_cart_pos',
							'title' => __("Cart Display", 'woorewards-lite'),
							'type'  => 'lacselect',
							'extra' => array(
								'mode'     => 'select',
								'notnull'  => true,
								'maxwidth' => '400px',
								'source'   => array(
									array('value' => 'not_displayed',    'label' => __("Not displayed at all", 'woorewards-lite')),
									array('value' => 'after_products',   'label' => __("Between products and totals", 'woorewards-lite')),
									array('value' => 'cart_collaterals', 'label' => __("Aside from cart totals", 'woorewards-lite')),
								),
								'default'  => 'not_displayed',
								'help'     => __("The following options are used to decide where and how the Points on Cart tool will be displayed in the cart page", 'woorewards-lite'),
							)
						),
						'cartreload' => array(
							'id'    => 'lws_woorewards_points_to_cart_reload',
							'title' => __("Reload cart page after amount modification", 'woorewards-lite'),
							'type'  => 'box',
							'extra' => array(
								'class'    => 'lws_checkbox',
								'tooltips' => __("By default, changing the amount will provoke a javascript (ajax) update. Check this box if the default behavior doesn't work.", 'woorewards-lite'),
							)
						),
						'checkoutdisplay' => array(
							'id'    => 'lws_woorewards_points_to_checkout_pos',
							'title' => __("Checkout Display", 'woorewards-lite'),
							'type'  => 'lacselect',
							'extra' => array(
								'mode'     => 'select',
								'notnull'  => true,
								'maxwidth' => '400px',
								'source'   => array(
									array('value' => 'not_displayed',   'label' => __("Not displayed at all", 'woorewards-lite')),
									array('value' => 'top_page',        'label' => __("Top of the page", 'woorewards-lite')),
									array('value' => 'before_customer', 'label' => __("Before customer details", 'woorewards-lite')),
									array('value' => 'before_review',   'label' => __("Before order review", 'woorewards-lite')),
								),
								'default'  => 'not_displayed',
								'help'     => __("The following options are used to decide where and how the Points on Cart tool will be displayed in the checkout page", 'woorewards-lite'),
							)
						),
						'checkoutreload' => array(
							'id'    => 'lws_woorewards_points_to_checkout_reload',
							'title' => __("Reload checkout page after amount modification", 'woorewards-lite'),
							'type'  => 'box',
							'extra' => array(
								'class'    => 'lws_checkbox',
								'tooltips' => __("By default, changing the amount will provoke a javascript (ajax) update. Check this box if the default behavior doesn't work.", 'woorewards-lite'),
							)
						),
						'pointsoncartheader' => array(
							'id' => 'lws_wooreward_points_cart_header',
							'title' => __("Tool Header", 'woorewards-lite'),
							'type' => 'text',
							'extra' => array(
								'placeholder' => __('Loyalty points discount', 'woorewards-lite'),
								'size' => '30',
							)
						),
						array(
							'id' => 'lws_woorewards_points_to_cart_style',
							'type' => 'stygen',
							'extra' => array(
								'purpose'  => 'filter',
								'template' => 'lws_woorewards_points_to_cart',
								'html'     => false,
								'css'      => LWS_WOOREWARDS_CSS . '/templates/pointsoncart.css',
								'help'     => __("Use the styling tool to change the tool's frontend appearance", 'woorewards-lite'),
								'subids'   => array(
									'lws_woorewards_points_to_cart_action_use'     => "WooRewards - Points On Cart Action - Use",
									'lws_woorewards_points_to_cart_action_update'  => "WooRewards - Points On Cart Action - Update",
									'lws_woorewards_points_to_cart_action_max'     => "WooRewards - Points On Cart Action - Max",
								),
							)
						)
					)
				),
			)
		);
	}

	protected function getShortcodesTab()
	{
		return array(
			'id'     => 'shortcodes',
			'title'  => __("Shortcodes", 'woorewards-lite'),
			'icon'	=> 'lws-icon-shortcode',
			'groups' => array(
				'shortcodes' => array(
					'id'	=> 'shortcodes',
					'title'	=> __('Shortcodes', 'woorewards-lite'),
					'icon'	=> 'lws-icon-shortcode',
					'text'	=> __("In this section, you will find various shortcodes you can use on your website.", 'woorewards-lite'),
					'fields' => array(
						'simplepoints'    => array(
							'id' => 'lws_woorewards_sc_simple_points',
							'title' => __("Simple Points Display", 'woorewards-lite'),
							'type' => 'shortcode',
							'extra' => array(
								'shortcode' => '[wr_simple_points]',
								'description' =>  __("This simple shortcode is used to display the user's points with no decoration.", 'woorewards-lite') . "<br/>" .
								__("This is very convenient if you want to display points within a phrase for example.", 'woorewards-lite'),
							)
						),
						'showpoints'    => array(
							'id' => 'lws_woorewards_sc_show_points',
							'title' => __("Display Points", 'woorewards-lite'),
							'type' => 'shortcode',
							'extra' => array(
								'shortcode' => '[wr_show_points title="your title"]',
								'description' =>  __("This shortcode shows to customers the points they have on a loyalty system.", 'woorewards-lite'),
								'options'   => array(
									array(
										'option' => 'title',
										'desc' => __("The text displayed before the points.", 'woorewards-lite'),
									),
									array(
										'option' => 'show_currency',
										'desc' => __("(Optional) If set, the number of points displayed will show the points currency.", 'woorewards-lite'),
									),
								),
							)
						),
						'history'    => array(
							'id' => 'lws_woorewards_sc_history',
							'title' => __("Points History", 'woorewards-lite'),
							'type' => 'shortcode',
							'extra' => array(
								'shortcode' => "[wr_show_history count='15']",
								'description' =>  __("This shortcode displays a user's points history.", 'woorewards-lite'),
								'options'   => array(
									array(
										'option' => 'count',
										'desc' => __("(Optional) The number of rows displayed. Default is 15.", 'woorewards-lite'),
									),
								),
							)
						),
						'pointsoncart' => array(
							'id' => 'wr_points_on_cart',
							'title' => __("Points on Cart Tool", 'woorewards-lite'),
							'type' => 'shortcode',
							'extra' => array(
								'shortcode' => '[wr_points_on_cart]',
								'description' =>  __("This shortcode is used to display the Points on Cart tool.", 'woorewards-lite') . "<br/>" .
								__("You can customize its appearance in the Widgets Tab.", 'woorewards-lite'),
							)
						),
						'maxwpointsoncart' => array(
							'id' => 'wr_max_points_on_cart',
							'title' => __("Maximum Point Amount on Cart", 'woorewards-lite'),
							'type' => 'shortcode',
							'extra' => array(
								'shortcode' => '[wr_max_points_on_cart raw=""]',
								'description' =>  __("This shortcode will show the maximum quantity of Point that could be used on this cart.", 'woorewards-lite'),
								'options'   => array(
									array(
										'option' => 'raw',
										'desc' => __("(Optional) If set, the amount will be a simple text. Otherwise, it will be presented inside a stylable element", 'woorewards-lite'),
									),
								),
							)
						),
					),
				),
			)
		);
	}

	/** Tease about pro version.
	 * Display standand pool settings. */
	protected function getLoyaltyGroups()
	{
		$groups = array();

		if( !\LWS_WooRewards::isWC() && (!defined('LWS_WOOREWARDS_ACTIVATED') || !LWS_WOOREWARDS_ACTIVATED) ) {
			$groups['information'] = array(
				'id'    => 'information',
				'title' => __("Information", 'woorewards-lite'),
				'text'  => __(
					"WooRewards Standard uses WooCommerce <i>orders</i> and <i>coupons</i>.
							<br/>You should install <a href='https://wordpress.org/plugins/woocommerce/' target='_blank'>WooCommerce</a> to have them active.
							<br/>Or <a href='https://plugins.longwatchstudio.com/product/woorewards/' target='_blank'>upgrade <b>WooRewards</b> to the <b>Pro</b> version</a>
							and enjoy new ways to earn points (social media, sponsoring... with or without WooCommerce) and a lot of new reward types !",
					LWS_WOOREWARDS_DOMAIN
				)
			);
		}

		// load the default pool
		$pools = \LWS\WOOREWARDS\Collections\Pools::instanciate()->load(array(
			'meta_query'  => array(
				array(
					'key'     => 'wre_pool_prefab',
					'value'   => 'yes',
					'compare' => 'LIKE'
				),
				array(
					'key'     => 'wre_pool_type',
					'value'   => \LWS\WOOREWARDS\Core\Pool::T_STANDARD,
					'compare' => 'LIKE'
				)
			)
		));

		if( $pools->count() <= 0 )
		{
			if( !(defined('LWS_WOOREWARDS_ACTIVATED') && LWS_WOOREWARDS_ACTIVATED) )
			{
				if( defined('LWS_WIZARD_SUMMONER') && LWS_WIZARD_SUMMONER )
				{
					$url = \esc_attr(\add_query_arg(array('page'=>LWS_WIZARD_SUMMONER.LWS_WOOREWARDS_PAGE), admin_url('admin.php')));
					$attr = " href='{$url}' class='lws-adm-btn'";
					$text = sprintf(
						__("It seems the prefab pool does not exists. Please run the <a%s>wizard</a> to create one.", 'woorewards-lite'),
						$attr
					);
					\lws_admin_add_notice_once('lws-wre-pool-nothing-loaded', sprintf(
						__("No loyalty system available. Please run the <a%s>wizard</a> to install default one.", 'woorewards-lite'),
						$attr
					), array('level'=>'info', 'dismissible'=>true));
				}
				else
				{
					$text = __("It seems the prefab pool does not exists. Try to re-activate this plugin. If the problem persists, contact your administrator.", 'woorewards-lite');
					\lws_admin_add_notice_once('lws-wre-pool-nothing-loaded', __("No loyalty system available. Try to re-activate the WooRewards plugin to install default ones.", 'woorewards-lite'), array('level'=>'info', 'dismissible'=>true));
				}

				error_log("Warning: No pools loaded.");
			}
			$groups['failure'] = array(
				'id'    => 'failure',
				'title' => __("Loading failure", 'woorewards-lite'),
				'text'  => $text,
			);
		}
		else
		{
			$prefix = 'lws-wr-pool-option-';
			// let dedicated class create options
			$pool = $pools->get(0);
			$groups = array_merge($groups, array(
				'general'    => array(
					'id'       => 'general',
					'image'		=> LWS_WOOREWARDS_IMG.'/ls-settings.png',
					'color'		=> '#7958a5',
					'title'    => __("General Settings", 'woorewards-lite'),
					'fields'   => \apply_filters('lws_woorewards_admin_pool_general_settings', array(), $pool),
					'text'     => __("Before activating your loyalty program, make sure you've read the documentation. You will find links to the documentation on the top right of each group.", 'woorewards-lite'),
					'extra'    => array('doclink' => 'https://plugins.longwatchstudio.com/docs/woorewards/how-does-it-work/loyalty-systems/'),
				),
				'earning'=> array(
					'id'		=> 'earning',
					'class'		=> 'half',
					'title'    	=> __("Points", 'woorewards-lite'),
					'image'		=> LWS_WOOREWARDS_IMG.'/ls-earning.png',
					'color'		=> '#38bebe',
					'text'     	=> __("Here you can manage how your customers earn loyalty points", 'woorewards-lite'),
					'extra'    => array('doclink' => 'https://plugins.longwatchstudio.com/docs/woorewards/earning-methods/'),
					'editlist' 	=> \lws_editlist(
						'EventList',
						\LWS\WOOREWARDS\Ui\Editlists\EventList::ROW_ID,
						new \LWS\WOOREWARDS\Ui\Editlists\EventList($pool),
						\LWS\Adminpanel\EditList::MOD
					)->setPageDisplay(false)->setCssClass('eventlist')->setRepeatHead(false)
				),
				'spending'   => array(
					'id'       => 'spending',
					'class'		=> 'half',
					'title'    => __("Rewards", 'woorewards-lite'),
					'image'		=> LWS_WOOREWARDS_IMG.'/ls-gift.png',
					'color'		=> '#526981',
					'text'     => __("Here you can manage the rewards for your customers. Rewards can either be automatically generated WooCommerce Coupons or points usable on cart for immediate discounts", 'woorewards-lite'),
					'extra'    => array('doclink' => 'https://plugins.longwatchstudio.com/docs/woorewards/rewards/'),
					'fields' => array(
						'mode' => array(
							'id'    => $prefix.'direct_reward_mode',
							'type'  => 'box',
							'title' => __("Reward Type", 'woorewards-lite'),
							'extra' => array(
								'id'      => 'direct_reward_mode',
								'class'   => 'lws_switch',
								'value'   => $pool->getOption('direct_reward_mode'),
								'data'    => array(
									'left' => __("WooCommerce Coupon", 'woorewards-lite'),
									'right' => __("Points on Cart", 'woorewards-lite'),
									'colorleft' => '#425981',
									'colorright' => '#5279b1',
								),
							)
						),
						array(
							'id'    => $prefix.'direct_reward_point_rate',
							'type'  => 'text',
							'title' => sprintf(__("Point Value in %s", 'woorewards-lite'),\LWS_WooRewards::isWC() ? \get_woocommerce_currency_symbol() : '?'),
							'extra' => array(
								'value'   => $pool->getOption('direct_reward_point_rate'),
								'help' => __("Each point spent on the cart will decrease the order total of that value", 'woorewards-lite')
							),
							'require' => array('selector' => '#direct_reward_mode', 'value' => 'on'),
						),
						array(
							'id'    => 'rewards',
							'type'  => 'editlist',
							'title' => __("Coupon", 'woorewards-lite'),
							'extra' => array(
								'editlist' => \lws_editlist(
									'UnlockableList',
									\LWS\WOOREWARDS\Ui\Editlists\UnlockableList::ROW_ID,
									new \LWS\WOOREWARDS\Ui\Editlists\UnlockableList($pool),
									\LWS\Adminpanel\EditList::MOD
								)->setPageDisplay(false)->setCssClass('unlockablelist')->setRepeatHead(false),
							),
							'require' => array('selector' => '#direct_reward_mode', 'value' => ''),
						),
					)
				)
			));
		}
		if( (!defined('LWS_WOOREWARDS_ACTIVATED') || !LWS_WOOREWARDS_ACTIVATED) )
		{
			if( isset($groups['earning']) )
			{
				$groups['earning']['fields']= array(
					'tutorial' => array(
						'id' => 'lws_woorewards_tutorial_earning_vfree',
						'type' => 'help',
						'extra' => array(
							'help' => __("Watch our <a target='_blank' href='https://youtu.be/yl9WyGOGd_I'>youtube tutorial</a> to help you set up your loyalty program", 'woorewards-lite'),
							'type' => 'youtube'
						)
					),
				);
			}
		}

		return $groups;
	}

	/** For pool option in admin page:
	 * *	be sure field id starts with 'lws-wr-pool-option-' and Pool->setOption accept the id string rest as valid option name.
	 * *	be sure the page contains a <input> named 'pool' with relevant pool id.
	 * *	since field cannot read value in wp get_option, be sure to set the relevant value in extra array.
	 *
	 *	@param fields an array as required by 'fields' entry in admin group.
	 * 	@param $pool a Pool instance. */
	public function getPoolGeneralSettings($fields, \LWS\WOOREWARDS\Core\Pool $pool)
	{
		$poolOptionPrefix = 'lws-wr-pool-option-';

		$fields['pool'] = array(
			'id'    => 'lws-wr-pool-option',
			'type'  => 'hidden',
			'extra' => array(
				'value' => $pool->getId(),
				'id'    => 'lws_wr_pool_id',
			)
		);

		$fields['enabled'] = array(
			'id'    => $poolOptionPrefix.'enabled', /// id starts with 'lws-wr-pool-option-', 'enabled' is accepted as Pool option
			'type'  => 'box',
			'title' => 'Status',
			'extra' => array(
				'class'   => 'lws_switch lws-force-confirm',
				'checked' => $pool->getOption('enabled'), /// set field value here
				'data'    => array(
					'default' => _x("Off", "pool enabled switch", 'woorewards-lite'),
					'checked' => _x("On", "pool enabled switch", 'woorewards-lite')
				)
			)
		);

		$fields['title'] = array(
			'id'    => $poolOptionPrefix.'title',
			'type'  => 'text',
			'title' => _x("Title", "Pool title", 'woorewards-lite'),
			'extra' => array(
				'required' => true,
				'value'    => $pool->getOption('title')
			)
		);

		return $fields;
	}

	/** Simulate the order status change for order in date range */
	function forceOldOrdersTrigger($btnId, $data=array())
	{
		if( $btnId != 'trigger_orders' ) return false;
		if( !isset($data['date_min']) || !($d1 = \date_create($data['date_min'])) ) return __("Dates are required", 'woorewards-lite');
		if( !isset($data['date_max']) || !($d2 = \date_create($data['date_max'])) ) return __("Dates are required", 'woorewards-lite');
		if( $d2 < $d1 )
		{
			$tmp = $d2;
			$d2 = $d1;
			$d1 = $tmp;
		}
		$d1 = $d1->format('Y-m-d');
		$d2 = $d2->format('Y-m-d');

		$status = array_unique(\apply_filters('lws_woorewards_order_events', array('processing', 'completed')));
		$status = array_map(function($s){return 'wc-'.$s;}, $status);
		$status = implode("','", array_map('\esc_sql', $status));

		$shopKind = \apply_filters('lws_woorewards_order_backward_apply_shop_kind', array('shop_order'));
		$shopKind = implode("','", array_map('\esc_sql', $shopKind));

		global $wpdb;
		$sql = <<<EOT
			SELECT p.ID
			FROM {$wpdb->posts} as p
			WHERE p.post_type IN ('{$shopKind}')
			AND p.post_status IN ('{$status}')
			AND DATE(p.post_date) BETWEEN DATE('{$d1}') AND DATE('{$d2}')
			GROUP BY p.ID
EOT;
		$orderIds = $wpdb->get_col($sql);
		if( false === $orderIds ) return false;

		$count = 0;
		foreach( $orderIds as $orderId )
		{
			if( $order = \wc_get_order($orderId) )
			{
				$hook = 'lws_woorewards_pool_on_order_done';
//				$hook = 'woocommerce_order_status_' . $order->get_status('edit'); // we test process unicity, but not sure any third party do it correctly
				\do_action($hook, $orderId, $order);
				++$count;
			}
		}

		return sprintf(__("<b>%s</b> order(s) processed.", 'woorewards-lite'), $count);
	}

	function deleteAllData($btnId, $data=array())
	{
		if( $btnId != 'trigger_delete_all_woorewards' ) return false;
		$wpInstalling = \wp_installing();
		\wp_installing(true); // should force no cache
		\do_action('lws_woorewards_before_delete_all', $data);
		error_log("[WooRewards] Delete everything");

		\delete_option('lws_woorewards_version');
		\delete_option('lws_woorewards_pointstack_timeout_delete');

		global $wpdb;
		foreach( array('lws-wre-pool', 'lws-wre-event', 'lws-wre-unlockable') as $post_type )
		{
			foreach( $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type='{$post_type}'") as $post_id )
				\wp_delete_post($post_id, true);
		}

		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}lws_wr_historic");
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}lws_wr_achieved_log");

		// user meta
		$wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE meta_key='lws_wre_unlocked_id'");
		$wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE meta_key='lws_wre_pending_achievement'");
		$wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE 'lws_wre_points_%'"); /// @see \LWS\WOOREWARDS\Abstracts\IPointStack::MetaPrefix

		// post meta
		$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE 'lws_woorewards_%'");
		$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key IN ('reward_origin','reward_origin_id')");

		// mails
		$prefix = 'lws_mail_'.'woorewards'.'_attribute_';
		\delete_option($prefix.'headerpic');
		\delete_option($prefix.'footer');
		foreach( array('wr_new_reward') as $template )
		{
			\delete_option('lws_mail_subject_'.$template);
			\delete_option('lws_mail_preheader_'.$template);
			\delete_option('lws_mail_title_'.$template);
			\delete_option('lws_mail_header_'.$template);
			\delete_option('lws_mail_template_'.$template);
			\delete_option('lws_mail_bcc_admin_'.$template);
		}

		// clean options
		$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'lws_woorewards_%'");
		$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'rflush_lws_woorewards_%'");

		\do_action('lws_woorewards_after_delete_all', $data);
		\wp_installing($wpInstalling);
		return __("You can now create new Loyalty System for your customers or uninstall WooRewards.", 'woorewards-lite');
	}
}
