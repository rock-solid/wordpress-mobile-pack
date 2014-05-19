<?php
if ( ! class_exists( 'Mobilepress_core' ) ) {
	/**
	 * The core MobilePress class where the magic happens
	 *
	 * @package MobilePress
	 * @since 1.0
	 */
	class Mobilepress_core {

		/**
		 * Creates the MobilePress menus and creates an admin object which creates the menu content
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		public function mopr_admin() {
			require_once( MOPR_PATH . 'classes/admin.php' );

			$Mobilepress_admin = new Mobilepress_admin;

			// Add MobilePress Menu
			add_menu_page( 'MobilePress', 'MobilePress', 10, 'mobilepress', '', WP_PLUGIN_URL . '/mobilepress/views/images/icon.png' );
				add_submenu_page( 'mobilepress', 'MobilePress Settings', 'Settings', 10, 'mobilepress', array( &$Mobilepress_admin, 'mopr_admin_settings' ) );
				add_submenu_page( 'mobilepress', 'MobilePress Themes', 'Themes', 10, 'mobilepress-themes', array( &$Mobilepress_admin, 'mopr_admin_themes') );
		}

		/**
		 * Loads the install class and setups the plugin including database creation
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		public function mopr_load_activation() {
			require_once( MOPR_PATH . 'classes/setup.php' );
			$mobilepress_setup = new Mobilepress_setup;
			$mobilepress_setup->mopr_install();
		}

		/**
		 * Calls the method to create admin menus and to set up the admin area
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		public function mopr_load_admin() {
			add_action( 'admin_menu', array( &$this, 'mopr_admin' ) );
		}

		/**
		 * Deactivates the plugin
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		public function mopr_load_deactivation() {
			// Shutdown the plugin (nothing here yet)
		}

		/**
		 * Does the checks and decides whether to render a mobile or normal website
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		public function mopr_load_site() {
			if ( ( isset( $_GET['killsession'] ) ) || ( $_SESSION['MOPR_FORCE_MOBILE'] == TRUE && ! mopr_get_option( 'force_mobile', 1 ) ) ) {
				session_unset();
				session_destroy();
				$_SESSION['MOPR_MOBILE_ACTIVE']		= '';
				$_SESSION['MOPR_MOBILE_BROWSER']	= '';
				$_SESSION['MOPR_MOBILE_THEME']		= '';
			}

			// Plugin preference is set to render entire site in mobile.
			if ( mopr_get_option( 'force_mobile', 1 ) ) {
				$_SESSION['MOPR_FORCE_MOBILE']		= TRUE;
				$_SESSION['MOPR_MOBILE_ACTIVE']		= TRUE;
				$_SESSION['MOPR_MOBILE_BROWSER']	= 'mobile';
				$_SESSION['MOPR_MOBILE_THEME']		= mopr_get_option( 'mobile_theme', 1 );
			}

			// Check if mobile sesison var exists
			// Also, check if ?mobile or ?nomobile is set. If so, establish the session var so that subsequent page calls will render in the desired mode.
			if ( ( ! isset( $_SESSION['MOPR_MOBILE_ACTIVE'] ) || ( trim( $_SESSION['MOPR_MOBILE_ACTIVE'] ) == '') ) || ( isset( $_GET['mobile'] ) ) || ( isset( $_GET['nomobile'] ) ) ) {
				require_once( MOPR_PATH . 'classes/check.php' );
				$mobilepress_check = new Mobilepress_check;
				$mobilepress_check->mopr_detect_device();
			}

			if ( $_SESSION['MOPR_MOBILE_ACTIVE'] === TRUE ) {
				// Double check session var for theme, fall back on default if any problems
				if ( ! isset( $_SESSION['MOPR_MOBILE_THEME'] ) || ( trim( $_SESSION['MOPR_MOBILE_THEME'] ) == '') ) {
					$_SESSION['MOPR_MOBILE_THEME'] = mopr_get_option( 'mobile_theme', 1 );
				}

				require_once( MOPR_PATH . 'classes/render.php' );
				$Mobilepress_render = new Mobilepress_render();
				$Mobilepress_render->mopr_render_theme();
			}
		}

		/**
		 * Uninstalls the plugin
		 *
		 * @package MobilePress
		 * @since 1.2
		 */
		public function mopr_load_uninstall() {
			require_once( MOPR_PATH . 'classes/setup.php' );
			$mobilepress_setup = new Mobilepress_setup;
			$mobilepress_setup->mopr_uninstall();
		}

	}
}