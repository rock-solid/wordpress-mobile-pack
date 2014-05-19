<?php
if ( ! class_exists( 'Mobilepress_admin' ) ) {
	/**
	 * MobilePress class for creating the admin area
	 *
	 * @package MobilePress
	 * @since 1.0
	 */
	class Mobilepress_admin {

		/**
		 * Constructor which runs a few checks
		 *
		 * @package MobilePress
		 * @since 1.2
		 */
		public function __construct() {
			if ( MOPR_DBVERSION < MOPR_VERSION ) {
				require_once( MOPR_PATH . 'classes/setup.php' );
				$mobilepress_setup = new Mobilepress_setup;
				$mobilepress_setup->mopr_install();
			}
		}

		/**
		 * Renders the themes page
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		public function mopr_admin_themes() {
			global $wpdb;

			if ( isset( $_GET['action'] ) == 'activate' && $_GET['theme_type'] == 'mobile' )  {
				$theme_type = $_GET['theme_type'];

				$wpdb->update(	MOPR_TABLE,
								array(
									'option_value'		=> $_GET['template'],
									'option_value_2'	=> $_GET['theme']
								),
								array( 'option_name' => $theme_type .'_theme' )
				);

				$theme_root = explode( 'wp-content', $_GET['theme_root'] );
				$theme_root = $theme_root[1];

				$wpdb->update(	MOPR_TABLE,
								array(
									'option_value'	=> $theme_root
								),
								array( 'option_name' => $theme_type .'_theme_root' )
				);
			}

			$mobile_theme			= mopr_get_option( 'mobile_theme', 2 );

			$themes					= $this->mopr_select_themes();
			$theme_names			= array_keys( $themes );

			$data['mobile_theme']	= $mobile_theme;
			$data['themes']			= $themes;
			$data['theme_names']	= $theme_names;

			mopr_load_view( 'admin_themes', $data );
		}

		/**
		 * Renders the MobilePress settings page
		 *
		 * @package MobilePress
		 * @since 1.2
		 */
		public function mopr_admin_settings() {
			global $wpdb;

			if ( isset( $_POST['save'] ) ) {

				$mopr_settings	= array(
										'front_page'		=> $_POST['mopr_front_page'],
										'page_posts'		=> $_POST['mopr_page_posts'],
										'show_categories'	=> $_POST['mopr_show_categories'],
										'show_pages'		=> $_POST['mopr_show_pages'],
										'show_tags'			=> $_POST['mopr_show_tags'],
										'show_thumbnails'	=> $_POST['mopr_show_thumbnails'],
										'comments'			=> $_POST['mopr_comments'],
										'force_mobile'		=> $_POST['mopr_force_mobile'],
										'custom_themes'		=> $_POST['mopr_custom_themes']
				);

				foreach( $mopr_settings as $mopr_setting => $mopr_value ) {
					$wpdb->update(	MOPR_TABLE,
									array( 'option_value' => $mopr_value ),
									array( 'option_name' => $mopr_setting )
					);
				}
			}

			$data['mopr_front_page']		= mopr_get_option( 'front_page', 1 );
			$data['mopr_page_posts']		= mopr_get_option( 'page_posts', 1 );
			$data['mopr_show_categories']	= mopr_get_option( 'show_categories', 1 );
			$data['mopr_show_pages']		= mopr_get_option( 'show_pages', 1 );
			$data['mopr_show_tags']			= mopr_get_option( 'show_tags', 1 );
			$data['mopr_show_thumbnails']	= mopr_get_option( 'show_thumbnails', 1 );
			$data['mopr_comments']			= mopr_get_option( 'comments', 1 );
			$data['mopr_force_mobile']		= mopr_get_option( 'force_mobile', 1 );
			$data['mopr_custom_themes']		= mopr_get_option( 'custom_themes', 1 );

			mopr_load_view( 'admin_settings', $data );
		}

		/**
		 * Function to check if themes exist and if default theme exists, if so, returns the themes
		 *
		 * @package MobilePress
		 * @since 1.1.1
		 */
		private function mopr_select_themes() {
			$default_themes	= $this->mopr_get_themes( MOPR_ROOT_PATH . 'themes' );
			$custom_themes	= $this->mopr_get_themes( WP_CONTENT_DIR . mopr_get_option( 'custom_themes', 1 ) );

			if ( is_array( $default_themes ) && is_array( $custom_themes ) ) {
				// Merge themes
				$themes = array_merge( $default_themes, $custom_themes );
				ksort( $themes );
			} else if ( is_array( $default_themes ) ) {
				$themes = $default_themes;
				ksort( $themes );
			} else if ( is_array( $custom_themes ) ) {
				$themes = $custom_themes;
				ksort( $themes );
			} else {
				$themes = array();
			}

			if ( empty( $themes ) ) {
				mopr_display_notice( '<p>Please upload a theme to your MobilePress themes directory!</p>' );
				return false;
			} else {
				if ( empty( $themes['Default']['Title'] ) ) {
					mopr_display_notice( '<p>You need to upload the default theme!</p>' );
					return false;
				} else {
					return $themes;
				}
			}
		}

		/**
		 * Core WP function for getting themes (with a few modifications) - located at: wp-includes/theme.php
		 *
		 * @package MobilePress
		 * @since 1.0
		 */
		private function mopr_get_themes( $directory ) {
			$themes = array();
			$theme_loc = $theme_root = $directory;

			// Files in wp-content/themes directory and one subdir down
			$themes_dir = @ opendir($theme_root);
			if ( !$themes_dir )
				return false;

			while ( ($theme_dir = readdir($themes_dir)) !== false ) {
				if ( is_dir($theme_root . '/' . $theme_dir) && is_readable($theme_root . '/' . $theme_dir) ) {
					if ( $theme_dir{0} == '.' || $theme_dir == '..' || $theme_dir == 'CVS' )
						continue;
					$stylish_dir = @ opendir($theme_root . '/' . $theme_dir);
					$found_stylesheet = false;
					while ( ($theme_file = readdir($stylish_dir)) !== false ) {
						if ( $theme_file == 'style.css' ) {
							$theme_files[] = $theme_dir . '/' . $theme_file;
							$found_stylesheet = true;
							break;
						}
					}
					@closedir($stylish_dir);
					if ( !$found_stylesheet ) { // look for themes in that dir
						$subdir = "$theme_root/$theme_dir";
						$subdir_name = $theme_dir;
						$theme_subdir = @ opendir( $subdir );
						while ( ($theme_dir = readdir($theme_subdir)) !== false ) {
							if ( is_dir( $subdir . '/' . $theme_dir) && is_readable($subdir . '/' . $theme_dir) ) {
								if ( $theme_dir{0} == '.' || $theme_dir == '..' || $theme_dir == 'CVS' )
									continue;
								$stylish_dir = @ opendir($subdir . '/' . $theme_dir);
								$found_stylesheet = false;
								while ( ($theme_file = readdir($stylish_dir)) !== false ) {
									if ( $theme_file == 'style.css' ) {
										$theme_files[] = $subdir_name . '/' . $theme_dir . '/' . $theme_file;
										$found_stylesheet = true;
										break;
									}
								}
								@closedir($stylish_dir);
							}
						}
						@closedir($theme_subdir);
						$wp_broken_themes[$theme_dir] = array('Name' => $theme_dir, 'Title' => $theme_dir, 'Description' => __('Stylesheet is missing.'));
					}
				}
			}
			if ( is_dir( $theme_dir ) )
				@closedir( $theme_dir );

			if ( !$themes_dir || !$theme_files )
				return $themes;

			sort($theme_files);

			foreach ( (array) $theme_files as $theme_file ) {
				if ( !is_readable("$theme_root/$theme_file") ) {
					$wp_broken_themes[$theme_file] = array('Name' => $theme_file, 'Title' => $theme_file, 'Description' => __('File not readable.'));
					continue;
				}

				$theme_data = get_theme_data("$theme_root/$theme_file");

				$name        = $theme_data['Name'];
				$title       = $theme_data['Title'];
				$description = wptexturize($theme_data['Description']);
				$version     = $theme_data['Version'];
				$author      = $theme_data['Author'];
				$template    = $theme_data['Template'];
				$stylesheet  = dirname($theme_file);

				$screenshot = false;
				foreach ( array('png', 'gif', 'jpg', 'jpeg') as $ext ) {
					if (file_exists("$theme_root/$stylesheet/screenshot.$ext")) {
						$screenshot = "screenshot.$ext";
						break;
					}
				}

				if ( empty($name) ) {
					$name = dirname($theme_file);
					$title = $name;
				}

				if ( empty($template) ) {
					if ( file_exists(dirname("$theme_root/$theme_file/index.php")) )
						$template = dirname($theme_file);
					else
						continue;
				}

				$template = trim($template);

				if ( !file_exists("$theme_root/$template/index.php") ) {
					$parent_dir = dirname(dirname($theme_file));
					if ( file_exists("$theme_root/$parent_dir/$template/index.php") ) {
						$template = "$parent_dir/$template";
					} else {
						$wp_broken_themes[$name] = array('Name' => $name, 'Title' => $title, 'Description' => __('Template is missing.'));
						continue;
					}
				}

				$stylesheet_files = array();
				$stylesheet_dir = @ dir("$theme_root/$stylesheet");
				if ( $stylesheet_dir ) {
					while ( ($file = $stylesheet_dir->read()) !== false ) {
						if ( !preg_match('|^\.+$|', $file) && preg_match('|\.css$|', $file) )
							$stylesheet_files[] = "$theme_loc/$stylesheet/$file";
					}
				}

				$template_files = array();
				$template_dir = @ dir("$theme_root/$template");
				if ( $template_dir ) {
					while(($file = $template_dir->read()) !== false) {
						if ( !preg_match('|^\.+$|', $file) && preg_match('|\.php$|', $file) )
							$template_files[] = "$theme_loc/$template/$file";
					}
				}

				$template_dir = dirname($template_files[0]);
				$stylesheet_dir = dirname($stylesheet_files[0]);

				if ( empty($template_dir) )
					$template_dir = '/';
				if ( empty($stylesheet_dir) )
					$stylesheet_dir = '/';

				// Check for theme name collision.  This occurs if a theme is copied to
				// a new theme directory and the theme header is not updated.  Whichever
				// theme is first keeps the name.  Subsequent themes get a suffix applied.
				// The Default always trump their pretenders.
				if ( isset($themes[$name]) ) {
					if ( ('Default' == $name) &&
							 ('default' == $stylesheet) ) {
						// If another theme has claimed to be one of our default themes, move
						// them aside.
						$suffix = $themes[$name]['Stylesheet'];
						$new_name = "$name/$suffix";
						$themes[$new_name] = $themes[$name];
						$themes[$new_name]['Name'] = $new_name;
					} else {
						$name = "$name/$stylesheet";
					}
				}

				$themes[$name] = array('Name' => $name, 'Title' => $title, 'Description' => $description, 'Author' => $author, 'Version' => $version, 'Template' => $template, 'Stylesheet' => $stylesheet, 'Template Files' => $template_files, 'Stylesheet Files' => $stylesheet_files, 'Template Dir' => $template_dir, 'Stylesheet Dir' => $stylesheet_dir, 'Status' => $theme_data['Status'], 'Screenshot' => $screenshot, 'Tags' => $theme_data['Tags'], 'Theme Root' => $theme_root, 'Theme Root URI' => str_replace( WP_CONTENT_DIR, content_url(), $theme_root ) );
			}

			// Resolve theme dependencies.
			$theme_names = array_keys($themes);

			foreach ( (array) $theme_names as $theme_name ) {
				$themes[$theme_name]['Parent Theme'] = '';
				if ( $themes[$theme_name]['Stylesheet'] != $themes[$theme_name]['Template'] ) {
					foreach ( (array) $theme_names as $parent_theme_name ) {
						if ( ($themes[$parent_theme_name]['Stylesheet'] == $themes[$parent_theme_name]['Template']) && ($themes[$parent_theme_name]['Template'] == $themes[$theme_name]['Template']) ) {
							$themes[$theme_name]['Parent Theme'] = $themes[$parent_theme_name]['Name'];
							break;
						}
					}
				}
			}

			return $themes;
		}
	}

}