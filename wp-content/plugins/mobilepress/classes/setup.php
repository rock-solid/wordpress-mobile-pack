<?php
if ( ! class_exists('Mobilepress_setup') ) {
	/**
	 * Class that deals with installing, updating and uninstalling the MobilePress plugin
	 *
	 * @package MobilePress
	 * @since 1.0
	 */
	class Mobilepress_setup {

		/**
		 * Inserts default values into the MobilePress database
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		private function mopr_add_defaults() {
			global $wpdb;

			$sql = "INSERT INTO " . MOPR_TABLE . " (
						option_name,
						option_value,
						option_value_2
					)
					VALUES
						('version', '" . MOPR_VERSION . "', ''),
						('front_page', '', ''),
						('page_posts', '5', ''),
						('show_categories', '', ''),
						('show_pages', '', ''),
						('show_tags', '', ''),
						('show_thumbnails', '', ''),
						('comments', 'posts', ''),
						('custom_themes', '/mobilepress/themes', ''),
						('mobile_theme', 'default', 'Default'),
						('mobile_theme_root', '/plugins/mobilepress/themes', ''),
						('force_mobile', '0', '')
					";

			$wpdb->query( $sql );
		}

		/**
		 * Drops the MobilePress settings table
		 *
		 * @package MobilePress
		 * @since 1.2
		 */
		private function mopr_drop_table() {
			global $wpdb;

			$sql	= "
						DROP TABLE IF EXISTS " . MOPR_TABLE . "
					";

			$wpdb->query( $sql );
		}


		/**
		 * Start the installation process
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		public function mopr_install() {
			// If MobilePress table exists then try upgrade, otherwise create it and add defaults
			if ( mopr_check_table_exists() )
			{
				$this->mopr_drop_table();
				$this->mopr_setup_table();
				$this->mopr_add_defaults();
			}
			else
			{
				$this->mopr_setup_table();
				$this->mopr_add_defaults();
			}
		}

		/**
		 * Creates the MobilePress settings table
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		private function mopr_setup_table() {
			$sql	= "
						CREATE TABLE " . MOPR_TABLE . " (
							id mediumint(9) NOT NULL AUTO_INCREMENT,
							option_name VARCHAR(100) NOT NULL,
							option_value VARCHAR(100) NOT NULL,
							option_value_2 VARCHAR(100) NOT NULL,
						UNIQUE KEY id (id))
						ENGINE = MYISAM
						CHARACTER SET utf8
						COLLATE utf8_unicode_ci;
					";

			// Require upgrade.php from the CORE
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		/**
		 * Start the uninstall process
		 *
		 * @package MobilePress
		 * @since 1.2
		 */
		public function mopr_uninstall() {
			$this->mopr_drop_table();
		}

		/**
		 * Upgrades the plugin database if it is outdated
		 *
		 * @package MobilePress
		 * @since 1.0.2
		 */
		private function mopr_upgrade() {
			global $wpdb;

			// No upgrade for 1.2
		}
	}
}