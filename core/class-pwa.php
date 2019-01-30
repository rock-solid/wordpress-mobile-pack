<?php

if ( ! class_exists( 'PtPwa_Options' ) ) {
    require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH.'inc/class-pt-pwa-options.php');
}

if ( ! class_exists( 'PtPwa_Uploads' ) ) {
    require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH.'inc/class-pt-pwa-uploads.php');
}

if ( ! class_exists( 'PtPwa_Cookie' ) ) {
    require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH.'inc/class-pt-pwa-cookie.php');
}

if ( ! interface_exists( 'PtPwaManager' ) ) {
    require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH.'inc/pt-pwa-interface-manager.php');
}

if ( ! class_exists( 'PtPwaTheme' ) ) {
    require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH.'inc/class-pt-pwa-theme.php');
}

if ( ! class_exists( 'PtPwaThemeManager' ) ) {
    require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH.'inc/class-pt-pwa-theme-manager.php');
}

if ( ! class_exists( 'PtPwaManifest' ) ) {
    require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH.'inc/class-pt-pwa-manifest.php');
}

if ( ! class_exists( 'PtPwaManifestManager' ) ) {
    require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH.'inc/class-pt-pwa-manifest-manager.php');
}

if ( ! class_exists( 'PtPwaIcon' ) ) {
    require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH.'inc/class-pt-pwa-icon.php');
}

if ( ! class_exists( 'PtPwaFileHelper' ) ) {
    require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH.'inc/class-pt-pwa-file-helper.php');
}

if ( ! class_exists( 'JsonSerializer' ) ) {
    require_once ($Pt_Pwa_Config->PWA_PLUGIN_PATH.'libs/json-serializer/JsonSerializer/JsonSerializer.php');
}

if ( ! class_exists( 'PtPwa' ) ) {

    /**
     * PtPwa
     *
     * Main class for the Wordpress Mobile Pack plugin. This class handles:
     *
     * - activation / deactivation of the plugin
     * - setting / getting the plugin's options
     * - loading the admin section, javascript and css files
     * - loading the app in the frontend
     *
     */
    class PtPwa
    {

        /* ----------------------------------*/
        /* Methods							 */
        /* ----------------------------------*/

        /**
         *
         * Construct method that initializes the plugin's options
         *
         */
        public function __construct()
        {
            // create uploads folder and define constants
            if ( !defined( 'PWA_FILES_UPLOADS_DIR' ) && !defined( 'PWA_FILES_UPLOADS_URL' ) ){
                $WMP_Uploads = new PtPwa_Uploads();
                $WMP_Uploads->define_uploads_dir();
            }

            // we only need notices for admins
            if ( is_admin() ) {
                $this->setup_hooks();
            }
        }


        /**
         *
         * The activate() method is called on the activation of the plugin.
         *
         * This method adds to the DB the default settings of the plugin, creates the upload folder and
         * imports settings from the free version.
         *
         */
        public function activate()
        {
            // add settings to database
            PtPwa_Options::save_settings(PtPwa_Options::$options);

            $WMP_Uploads = new PtPwa_Uploads();
            $WMP_Uploads->create_uploads_dir();

            $this->backwards_compatibility();
        }


        /**
         *
         * The deactivate() method is called when the plugin is deactivated.
         * This method removes temporary data (transients and cookies).
         *
         */
        public function deactivate()
        {

            // delete plugin settings
            PtPwa_Options::deactivate();

            // remove the cookies
            $WMP_Cookie = new PtPwa_Cookie();

            $WMP_Cookie->set_cookie("theme_mode", false, -3600);
            $WMP_Cookie->set_cookie("load_app", false, -3600);
        }


        /**
         * Init admin notices hook
         */
        public function setup_hooks(){
            add_action( 'admin_notices', array( $this, 'display_admin_notices' ) );
        }

        /**
         *
         * Show admin notice if license is not active
         *
         */
        public function display_admin_notices(){

            if ( ! current_user_can( 'manage_options' ) ) {
                return;
            }

            if (version_compare(PHP_VERSION, '5.6') < 0) {
                $Pt_Pwa_Config = new Pt_Pwa_Config();
                echo '<div class="error"><p><b>Warning!</b> The ' . $Pt_Pwa_Config->PWA_PLUGIN_NAME . ' plugin requires at least PHP 5.6.0!</p></div>';
                return;
            }

			// display notice to reupload icon
			//$this->display_icon_reupload_notice();

        }

		/**
		 *
		 * Display icon reupload notice if icon is uploaded and manifest icon sizes are missing.
		 *
		 */
		 public function display_icon_reupload_notice(){

			$icon_filename = PtPwa_Options::get_setting('icon');

			if ($icon_filename == '') {
				echo '<div class="notice notice-warning is-dismissible"><p>Publishers Toolbox PWA: Upload an <a href="' . get_admin_url() . 'admin.php?page=wmp-options-theme-settings"/>App Icon</a> to take advantage of the Add To Home Screen functionality!</p></div>';

			} elseif ($icon_filename != '' && file_exists(PWA_FILES_UPLOADS_DIR . $icon_filename)) {
				foreach (PtPwa_Uploads::$manifest_sizes as $manifest_size) {
					if (!file_exists(PWA_FILES_UPLOADS_DIR . $manifest_size . $icon_filename)) {
						echo '<div class="notice notice-warning is-dismissible"><p>Publishers Toolbox PWA comes with Add To Home Screen functionality which requires you to reupload your <a href="' . get_admin_url() . 'admin.php?page=wmp-options-theme-settings"/>App Icon</a>!</p></div>';
						return;
					}
				}
			}
		}

        /**
         *
         * Transform settings to fit the new plugin structure
         *
         */
        public function backwards_compatibility(){

            if ( ! class_exists( 'PtPwa_Themes_Config' )) {
                include('../inc/class-pt-pwa-themes-config.php');
            }

            if (class_exists('PtPwa_Themes_Config')){

                foreach (array('headlines', 'subtitles', 'paragraphs') as $font_type) {

                    $font_option = PtPwa_Options::get_setting('font_'.$font_type);

                    if (!is_numeric($font_option)){
                        $new_font_option = array_search($font_option, PtPwa_Themes_Config::$allowed_fonts) + 1;
                        PtPwa_Options::update_settings('font_'.$font_type, $new_font_option);
                    }
                }
			}
			
			// switch from Obliq v1 to v2
			$theme = PtPwa_Options::get_setting('theme');

			if ($theme == 1) {
				$this->reset_theme_settings();
				PtPwa_Options::update_settings('theme', 2);
			}

			// delete premium options
			delete_option(PtPwa_Options::$prefix . 'premium_api_key');
			delete_option(PtPwa_Options::$prefix . 'premium_config_path');
			delete_option(PtPwa_Options::$prefix . 'premium_active');
		}
		

		/**
		 * Reset theme settings (for migrating from Obliq v1 to Obliq v2)
		 */
		protected function reset_theme_settings(){

			// reset color schemes and fonts
			PtPwa_Options::update_settings('color_scheme', 1);
			PtPwa_Options::update_settings('custom_colors', array());
			PtPwa_Options::update_settings('font_headlines', 1);
			PtPwa_Options::update_settings('font_subtitles', 1);
			PtPwa_Options::update_settings('font_paragraphs', 1);
			PtPwa_Options::update_settings('font_size', 1);

			// remove compiled css file (if it exists)
			$theme_timestamp = PtPwa_Options::get_setting('theme_timestamp');

			if ($theme_timestamp != ''){

				$file_path = PWA_FILES_UPLOADS_DIR.'theme-'.$theme_timestamp.'.css';

				if (file_exists($file_path)) {
					unlink($file_path);
				}

				PtPwa_Options::update_settings('theme_timestamp', '');
			}
		}

        /**
         *
         * Method used to check if a specific plugin is installed and active,
         * returns true if the plugin is installed and false otherwise.
         *
         * @param $plugin_name - the name of the plugin
         *
         * @return bool
         */
        public static function is_active_plugin($plugin_name)
        {

            $active_plugin = false; // by default, the search plugin does not exist

            // if the plugin name is empty return false
            if ($plugin_name != '') {

                // if function doesn't exist, load plugin.php
                if (!function_exists('get_plugins')) {
                    require_once ABSPATH . 'wp-admin/includes/plugin.php';
                }

                // get active plugins from the DB
                $apl = get_option('active_plugins');

                // get list withh all the installed plugins
                $plugins = get_plugins();

                foreach ($apl as $p){
                    if (isset($plugins[$p])){
                        // check if the active plugin is the searched plugin
                        if ($plugins[$p]['Name'] == $plugin_name)
                            $active_plugin = true;
                    }
                }
            }

            return $active_plugin; //return the active plugin variable
        }


        /**
         *
         * Static method used to request the content of different pages using curl or fopen
         * This method returns false if both curl and fopen are dissabled and an empty string ig the json could not be read
         *
         */
        public static function read_data($json_url) {

            // check if curl is enabled
            if (extension_loaded('curl')) {

                $send_curl = curl_init($json_url);

                // set curl options
                curl_setopt($send_curl, CURLOPT_URL, $json_url);
                curl_setopt($send_curl, CURLOPT_HEADER, false);
                curl_setopt($send_curl, CURLOPT_CONNECTTIMEOUT, 2);
                curl_setopt($send_curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($send_curl, CURLOPT_HTTPHEADER,array('Accept: application/json', "Content-type: application/json"));
                curl_setopt($send_curl, CURLOPT_FAILONERROR, FALSE);
                curl_setopt($send_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($send_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
                $json_response = curl_exec($send_curl);

                // get request status
                $status = curl_getinfo($send_curl, CURLINFO_HTTP_CODE);
                curl_close($send_curl);

                // return json if success
                if ($status == 200)
                    return $json_response;

            } elseif (ini_get( 'allow_url_fopen' )) { // check if allow_url_fopen is enabled

                // open file
                $json_file = fopen( $json_url, 'rb' );

                if($json_file) {

                    $json_response = '';

                    // read contents of file
                    while (!feof($json_file)) {
                        $json_response .= fgets($json_file);
                    }
                }

                // return json response
                if($json_response)
                    return $json_response;

            } else
                // both curl and fopen are disabled
                return false;

            // by default return an empty string
            return '';

        }
    }
}