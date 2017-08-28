<?php

if (!class_exists('WMobilePack_Premium')) {
    require_once(WMP_PLUGIN_PATH . 'inc/class-wmp-premium.php');
}

if (!class_exists('WMobilePack_Application')) {

    /**
     *
     * WMobilePack_Application
     *
     * Main class for managing frontend apps
     *
     */
    class WMobilePack_Application {


        /**
         * Class constructor
         */
        public function __construct()
        {
            // Load application only if the PRO plugin is not active
            if (!WMobilePack::is_active_plugin('WordPress Mobile Pack PRO'))
                $this->check_load();
        }

        /**
         *
         * Create a theme management object and return it
         *
         * @return object
         *
         */
        protected function get_premium_manager()
        {
            return new WMobilePack_Premium();
        }

        /**
         *
         * Create a cookie management object and return it
         *
         * @return object
         *
         */
        protected function get_cookie_manager()
        {
            return new WMobilePack_Cookie();
        }

        /**
         *
         * Method that checks if we can load the mobile web application theme.
         *
         * The theme is loaded if ALL of the following conditions are met:
         *
         * - the user comes from a supported mobile device and browser
         * - the user has not deactivated the view of the mobile theme by switching to desktop mode
         * - the display mode of the app is set to 'normal' or is set to 'preview' and an admin is logged in
         *
         */
        public function check_load()
        {

            // Set app as visible by default
            $visible_app = true;

            // Check if we have a Premium account
            $premium_manager = $this->get_premium_manager();
            $arr_config_premium = $premium_manager->get_premium_config(false);

            if ($arr_config_premium !== null) {

                // For premium, check if the web app is set as visible
                if ((isset($arr_config_premium->status) && $arr_config_premium->status == 'hidden') ||
                    (isset($arr_config_premium->deactivated) && $arr_config_premium->deactivated == 1)) {

                    $visible_app = false; // setting it to false will skip the detection
                }

            } else {

                // For free, check if the display mode is set to 'normal' or 'preview' and the admin is logged in
                if (!$this->check_display_mode()) {
                    $visible_app = false;
                }
            }

            // Assume the app will not be loaded
            $load_app = false;

            if ($visible_app) {

                // Check if the load app cookie is 1 or the user came from a mobile device
                $cookie_manager = $this->get_cookie_manager();
                $load_app_cookie = $cookie_manager->get_cookie('load_app');

                // If the load_app cookie is not set, verify the device
                if ($load_app_cookie === null) {
                    $load_app = $this->check_device();

                } elseif ($load_app_cookie == 1) {

                    // The cookie was already set for the device, so we can load the app
                    $load_app = true;
                }
            }

            // If we need to add the rel=alternate links in the header
            $show_alternate = true;

            // We have a mobile device and the app is visible, so we can load the app
            if ($load_app) {

                // Check if the user deactivated the app display
                $desktop_mode = $this->check_desktop_mode();

                // Check if we need to display a Premium smart app banner instead of redirect automatically to the app
                $automatic_redirects = $this->check_automatic_redirects($arr_config_premium);

                if ($desktop_mode == false) {

                    // Check if the automatic redirects are enabled
                    if ($automatic_redirects == true) {

                        // We're loading the mobile web app, so we don't need the rel=alternate links
                        $show_alternate = false;
                        $this->load_app();

                    } else {

                        add_action('wp_head', array(&$this, 'show_smart_app_banner_premium'));
                    }

                } else {

                    // The user returned to desktop, so show him a smart app banner
                    if ($arr_config_premium === null) {

                        // Use banner if we are on the FREE version
                        add_action('wp_head', array(&$this, 'show_smart_app_banner'));

                    } elseif ($automatic_redirects == false) {

                        // Or we have a premium app with smart app banner
                        add_action('wp_head', array(&$this, 'show_smart_app_banner_premium'));
                    }

                    // Add hook in footer to show the switch to mobile link
                    add_action('wp_footer', array(&$this, 'show_mobile_link'));
                }
            }

            // Add hook in header (for rel=alternate)
            if ($show_alternate){
                add_action('wp_head', array(&$this, 'show_rel'));
            }
        }

        /**
         *
         * Check if the app display is enabled
         *
         * Returns true if display mode is "normal" (enabled for all mobile users) or
         * if display mode is "preview" and an admin is logged in.
         *
         * @return bool
         *
         */
        protected function check_display_mode()
        {

            $display_mode = WMobilePack_Options::get_setting('display_mode');

            if ($display_mode == 'normal')
                return true;

            elseif ($display_mode == 'preview') {

                if (is_user_logged_in() && current_user_can('create_users'))
                    return true;
            }

            return false;
        }


        /**
         *
         * Call the mobile detection method to verify if we have a supported device
         *
         * @return bool
         *
         */
        protected function check_device(){

            if ( ! class_exists( 'WMobilePack_Detect' ) ) {
                require_once(WMP_PLUGIN_PATH.'frontend/class-detect.php');
            }

            $WMobileDetect = new WMobilePack_Detect();
            return $WMobileDetect->detect_device();
        }


        /**
         * Detect device and return it
         *
         * @return string (phone|tablet)
         */
        protected static function get_device(){

            // Check if it is tablet
            if (!class_exists( 'WMobilePack_Detect' ) ) {
                require_once(WMP_PLUGIN_PATH.'frontend/class-detect.php');
            }

            $detect_manager = new WMobilePack_Detect();
            $is_tablet = $detect_manager->is_tablet();

            // Set device
            return $is_tablet == 0 ? 'phone' : 'tablet';
        }


        /**
         *
         * Check if the user selected to view the desktop mode or we can display the app.
         *
         * The GET/COOKIE "theme_mode" can have two values: 'desktop' or 'mobile'.
         *
         * - Desktop mode can be activated from the app by selecting to return to desktop view.
         * - Mobile mode can be reactivated from the footer of the website.
         *
         * @return bool
         *
         */
        protected function check_desktop_mode()
        {

            $desktop_mode = false;

            $cookie_manager = $this->get_cookie_manager();
            $param_name = WMobilePack_Cookie::$prefix.'theme_mode';

            if (isset($_GET[$param_name]) && is_string($_GET[$param_name])){

                $theme_mode = $_GET[$param_name];

                if ($theme_mode == "desktop" || $theme_mode == "mobile"){
                    $cookie_manager->set_cookie('theme_mode', $theme_mode, 3600*30*24);
                }

                if ($theme_mode == "desktop")
                    $desktop_mode = true;

            } else {

                $theme_mode_cookie = $cookie_manager->get_cookie('theme_mode');

                if ($theme_mode_cookie){
                    if ($theme_mode_cookie == "desktop")
                        $desktop_mode = true;
                }
            }

            return $desktop_mode;
        }


        /**
         * Check if the Premium app has a subdomain and smart app banner that will disable automatic redirects
         *
         * @param $arr_config_premium
         * @return bool
         */
        protected function check_automatic_redirects($arr_config_premium){

            if ($arr_config_premium !== null) {

                if (isset($arr_config_premium->kit_type) && $arr_config_premium->kit_type == 'wpmp') {

                    // Check if we have a valid subdomain linked to the Premium theme
                    if (isset($arr_config_premium->domain_name) && filter_var('http://' . $arr_config_premium->domain_name, FILTER_VALIDATE_URL)) {

                        // Check if the app has an active smart app banner
                        if (isset($arr_config_premium->smart_app_banner) && filter_var('http://' . $arr_config_premium->smart_app_banner, FILTER_VALIDATE_URL)) {
                            return false;
                        }
                    }
                }
            }

            return true;
        }

        /**
         *
         * Method that loads the mobile web application theme.
         *
         * The theme url and theme name from the WP installation are overwritten by the settings below.
         * Set higher than default priority for filters to ensure these are executed after the ones from the free version.
         */
        public function load_app()
        {
            add_filter("stylesheet", array(&$this, "app_theme"), 11);
            add_filter("template", array(&$this, "app_theme"), 11);

            add_filter('theme_root', array( &$this, 'app_theme_root' ), 11);
            add_filter('theme_root_uri', array( &$this, 'app_theme_root' ), 11);
        }


        /**
         * Return the theme name
         */
        public function app_theme()
        {
            $premium_data = get_transient(WMobilePack_Options::$transient_prefix."premium_config_path");

            if (WMobilePack_Options::get_setting('premium_active') == 1 &&
                WMobilePack_Options::get_setting('premium_api_key') != '' &&
                $premium_data !== false && $premium_data !== '')
                return 'premium';
            else
                return 'app'.WMobilePack_Options::get_setting('theme');
        }


        /**
         *
         * Method used to display a rel=alternate link in the header of the desktop theme
         *
         * This method is called from check_load()
         *
         * @todo (Future releases) Don't set tag if a page's parent is deactivated
         */
        public function show_rel()
        {

            $use_external_rels = false;

            if (WMobilePack_Options::get_setting('premium_active') == 1 && WMobilePack_Options::get_setting('premium_api_key') != ''){

                $premium_manager = $this->get_premium_manager();
                $arr_config_premium = $premium_manager->get_premium_config();

                if ($arr_config_premium !== null){
                    if (!isset($arr_config_premium['kit_type']) || $arr_config_premium['kit_type'] != 'wpmp'){
                        $use_external_rels = true;
                    }
                }
            }

            if ($use_external_rels)
                include(WMP_PLUGIN_PATH . 'frontend/sections/show-rel-external.php');
            else
                include(WMP_PLUGIN_PATH.'frontend/sections/show-rel.php');
        }


        /**
         *
         * Method used to include a smart app banner in the header of the desktop theme,
         * when the mobile theme is disabled.
         *
         * This method is called from check_load()
         *
         * @todo (Future releases) Don't set mobile url if a page's parent is deactivated
         *
         */
        public function show_smart_app_banner()
        {
            include(WMP_PLUGIN_PATH.'frontend/sections/smart-app-banner.php');
        }


        /**
         *
         * Method used to include a smart app banner in the header of the desktop theme,
         * when automatic redirects are disabled.
         *
         * This method is called from check_load()
         *
         * @todo (Future releases) Don't set mobile url if a page's parent is deactivated
         *
         */
        public function show_smart_app_banner_premium()
        {
            include(WMP_PLUGIN_PATH.'frontend/sections/smart-app-banner-premium.php');
        }


        /**
         * Return path to the mobile themes folder
         */
        public function app_theme_root()
        {
            return WMP_PLUGIN_PATH . 'frontend/themes';
        }


        /**
         *
         * Method used to display a box on the footer of the theme
         *
         * This method is called from check_load()
         * The box contains a link that sets the cookie and loads the app
         *
         */
        public function show_mobile_link()
        {
            include(WMP_PLUGIN_PATH.'frontend/sections/show-mobile-link.php');
        }


        /**
         * Returns an array with all the application's frontend settings
         *
         * @return array
         */
        public function load_app_settings()
        {

            // load basic settings
            $frontend_options = array(
                'theme',
                'color_scheme',
                'theme_timestamp',
                'font_headlines',
                'font_subtitles',
                'font_paragraphs',
                'google_analytics_id',
                'display_website_link',
                'posts_per_page',
				'enable_facebook',
				'enable_twitter',
				'enable_google',
				'service_worker_installed'
            );

            $settings = array();

            foreach ($frontend_options as $option_name){
                $settings[$option_name] = WMobilePack_Options::get_setting($option_name);

                // backwards compatibility for font settings with versions lower than 2.2
                if (in_array($option_name, array('font_headlines', 'font_subtitles', 'font_paragraphs'))){
                    if (!is_numeric($settings[$option_name])){
                        $settings[$option_name] = 1;
                    }
                }
            }

            // check if custom theme exists and the file size is greater than zero
            if ($settings['theme_timestamp'] != ''){

                $custom_theme_path = WMP_FILES_UPLOADS_DIR.'theme-'.$settings['theme_timestamp'].'.css';

                if (!file_exists($custom_theme_path) || filesize($custom_theme_path) == 0){
                    $settings['theme_timestamp'] = '';
                }
            }

            // theme file doesn't exist, an preset css file will be used instead
            if ($settings['theme_timestamp'] == '' && $settings['font_headlines'] > 3){
                $settings['font_headlines'] = 1;
            }

            // load images
            foreach (array('icon', 'logo', 'cover') as $file_type){

                $file_path = WMobilePack_Options::get_setting($file_type);

                if ($file_path == '' || !file_exists(WMP_FILES_UPLOADS_DIR.$file_path))
                    $settings[$file_type] = '';
                else
                    $settings[$file_type] = WMP_FILES_UPLOADS_URL.$file_path;
            }

            // generate comments token
            if (!class_exists('WMobilePack_Tokens')) {
                require_once(WMP_PLUGIN_PATH . 'inc/class-wmp-tokens.php');
            }

            $settings['comments_token'] = WMobilePack_Tokens::get_token();

			if (!class_exists('WMobilePack_Themes_Config')) {
                require_once(WMP_PLUGIN_PATH . 'inc/class-wmp-themes-config.php');
            }

			$settings['manifest_color'] = WMobilePack_Themes_Config::get_manifest_background($settings['theme'], $settings['color_scheme']);

            return $settings;
        }

		/**
		* Get the language from the locale setting.
		*
		* @param string $locale (eg. 'en_EN')
		* @return string (eg. 'en')
		*/
		public function get_language($locale)
		{
			if (array_key_exists($locale, WMobilePack_Options::$supported_languages)){
				return WMobilePack_Options::$supported_languages[$locale];
			}

			return 'en';
		}


        /**
         * Returns an array with the application's Premium theme settings
         *
         * @param $arr_config_premium
         * @param $device
         * @return array
         */
        protected static function load_app_settings_theme_premium($arr_config_premium, $device){

            $settings = array();

            $theme_options = array(
                'theme',
                'color_scheme',
                'font_headlines',
                'font_subtitles',
                'font_paragraphs',
                'theme_timestamp',
                'custom_fonts'
            );

            foreach ($theme_options as $exact_setting) {

                if (isset($arr_config_premium[$device][$exact_setting])) {
                    $settings[$exact_setting] = $arr_config_premium[$device][$exact_setting];
                } else {

                    if ($exact_setting == 'color_scheme' || $exact_setting == 'font_headlines')
                        $settings[$exact_setting] = 1;
                    else
                        $settings[$exact_setting] = '';
                }
            }

            return $settings;
        }


        /**
         * Returns an array with the application's paths and images settings
         *
         * @param $arr_config_premium
         * @param $device
         * @param $is_secure
         * @return array
         */
        protected static function load_app_settings_paths_images_premium($arr_config_premium, $device, $is_secure){

            $settings = array(
                'cdn_kits' => ($is_secure ? $arr_config_premium['cdn_kits_https'] : $arr_config_premium['cdn_kits']),
                'cdn_apps' => ($is_secure ? $arr_config_premium['cdn_apps_https'] : $arr_config_premium['cdn_apps']),
                'icon' => '',
                'logo' => '',
                'cover' => '',
                'user_cover' => 0,
            );

            // Check if we have to load a custom theme
            if ($arr_config_premium[$device]['theme'] != 0) {

                $settings['kits_path'] = $settings['cdn_kits'];

                if (isset($arr_config_premium['kit_type']) && $arr_config_premium['kit_type'] == 'wpmp'){
                    $settings['kits_path'] .= "/apps";
                }

                $settings['kits_path'] .= "/app".$arr_config_premium[$device]['theme'].'/'.$arr_config_premium['kit_version'].'/';

            } else {
                $settings['kits_path'] = $settings['cdn_apps']."/".$arr_config_premium['shorten_url'].'/';
            }

            // Set icon
            if (isset($arr_config_premium['icon_path']) && $arr_config_premium['icon_path'] != '')
                $settings['icon'] = $settings['cdn_apps']."/".$arr_config_premium['shorten_url'].'/'.$arr_config_premium['icon_path'];

            // Set logo
            if (isset($arr_config_premium['logo_path']) && $arr_config_premium['logo_path'] != '')
                $settings['logo'] = $settings['cdn_apps']."/".$arr_config_premium['shorten_url'].'/'.$arr_config_premium['logo_path'];

            // Set cover settings
            if (isset($arr_config_premium[$device]['cover']) && $arr_config_premium[$device]['cover'] != '') {
                $settings['cover'] = $settings['cdn_apps']."/".$arr_config_premium['shorten_url'].'/'.$arr_config_premium[$device]['cover'];
                $settings['user_cover'] = 1;
            } else {
                $settings['cover'] = $settings['cdn_kits'].'/others/covers/'.$device.'/pattern-'.rand(1,8).'.jpg';
            }

            // Process icons & startup screens timestamps
            if (!isset($arr_config_premium['kit_type']) || $arr_config_premium['kit_type'] != 'wpmp') {

                $settings['icon_timestamp'] = '';
                if (isset($arr_config_premium['icon_path']) && $arr_config_premium['icon_path'] != '') {
                    $str = $arr_config_premium['icon_path'];
                    $settings['icon_timestamp'] = '_' . substr($str, strpos($str, '_') + 1, strpos($str, '.') - strpos($str, '_') - 1);
                }

                $settings['logo_timestamp'] = '';
                if (isset($arr_config_premium['logo_path']) && $arr_config_premium['logo_path'] != '') {
                    $str = $arr_config_premium['logo_path'];
                    $settings['logo_timestamp'] = '_' . substr($str, strpos($str, '_') + 1, strpos($str, '.') - strpos($str, '_') - 1);
                }
            }

            return $settings;
        }


        /**
         * Returns an array with the application's Google DFP and analytics settings
         *
         * @param $arr_config_premium
         * @return array
         */
        protected static function load_app_settings_google_premium($arr_config_premium){

            $settings = array();

            $google_options = array(
                'has_phone_ads',

                'phone_ad_interval',
                'phone_network_code',
                'phone_unit_name',
                'phone_ad_sizes',

                'has_tablet_ads',

                'tablet_ad_interval',
                'tablet_network_code',
                'tablet_unit_name',
                'tablet_ad_sizes',

                'google_internal_id',
                'google_analytics_id',
                'google_tag_manager_id',
                'google_webmasters_code',
            );

            foreach ($google_options as $exact_setting){

                if (isset($arr_config_premium[$exact_setting])){
                    $settings[$exact_setting] = $arr_config_premium[$exact_setting];
                }
            }

            return $settings;
        }



        /**
         *
         * Returns an array with all the application's frontend settings (Premium themes)
         * @todo Remove static from this method
         */
        public static function load_app_settings_premium(){

            $premium_manager = new WMobilePack_Premium();
            $arr_config_premium = $premium_manager->get_premium_config();

            // Get the device type
            $device = self::get_device();

            // Check if we have a secure https connection
            $is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

            // load basic settings
            $settings = array(
                'device' => $device,
                'kit_type' => 'classic',
                'shorten_url' => $arr_config_premium['shorten_url'],
                'title' => $arr_config_premium['title'],
                'locale' => 'en_EN',
                'cover_text' => '',
                'posts_per_page' => 'auto',
                'enable_facebook' => 1,
                'enable_twitter' => 1,
                'enable_google' => 1
            );

            $theme_settings = self::load_app_settings_theme_premium($arr_config_premium, $device);
            $settings = array_merge($settings, $theme_settings);

            $images_paths_settings = self::load_app_settings_paths_images_premium($arr_config_premium, $device, $is_secure);
            $settings = array_merge($settings, $images_paths_settings);

            $google_settings = self::load_app_settings_google_premium($arr_config_premium);
            $settings = array_merge($settings, $google_settings);

            // Check domain name
            if (isset($arr_config_premium['domain_name']) && filter_var('http://'.$arr_config_premium['domain_name'], FILTER_VALIDATE_URL))
                $settings['domain_name'] = $arr_config_premium['domain_name'];

            // Set locale
            if (isset($arr_config_premium['locale']) && $arr_config_premium['locale'] != '')
                $settings['locale'] = $arr_config_premium['locale'];

            // Set website url
            if (isset($arr_config_premium['website_url']) && $arr_config_premium['website_url'] != '')
                $settings['website_url'] = $arr_config_premium['website_url'];

            // Set social media
            foreach (array('facebook', 'twitter', 'google') as $social_network){
                if (isset($arr_config_premium['enable_'.$social_network]) && !$arr_config_premium['enable_'.$social_network])
                    $settings['enable_'.$social_network] = 0;
            }

            if (isset($arr_config_premium['kit_type']) && $arr_config_premium['kit_type'] == 'wpmp') {

                $settings['kit_type'] = 'wpmp';
                $settings['title'] = urldecode($settings['title']);

                // Set posts per page
                if (isset($arr_config_premium[$device]['posts_per_page']) && in_array($arr_config_premium[$device]['posts_per_page'], array('single', 'double'))) {

                    if ($arr_config_premium[$device]['posts_per_page'] == 'single') {
                        $settings['posts_per_page'] = 1;
                    } else {
                        $settings['posts_per_page'] = 2;
                    }
                }

                // Set cover text
                if (isset($arr_config_premium[$device]['cover_text']) && $arr_config_premium[$device]['cover_text'] != ''){

                    // load HTML purifier / formatter
                    if (!class_exists('WMobilePack_Formatter')) {
                        require_once(WMP_PLUGIN_PATH . 'inc/class-wmp-formatter.php');
                    }

                    $purifier = WMobilePack_Formatter::init_purifier();
                    $settings['cover_text'] = $purifier->purify(stripslashes(urldecode($arr_config_premium[$device]['cover_text'])));
                }

                // Generate comments token
                if (!class_exists('WMobilePack_Tokens')) {
                    require_once(WMP_PLUGIN_PATH . 'inc/class-wmp-tokens.php');
                }

                $settings['comments_token'] = WMobilePack_Tokens::get_token();

            } else {

                $settings['webapp'] = $arr_config_premium['webapp'];
                $settings['api_content'] = $is_secure ? $arr_config_premium['api_content_https'] : $arr_config_premium['api_content'];
                $settings['api_social'] = $is_secure ? $arr_config_premium['api_social_https'] : $arr_config_premium['api_social'];

                if (isset($arr_config_premium['api_content_external'])){
                    $settings['api_content_external'] = $arr_config_premium['api_content_external'];
                    $settings['enable_facebook'] = 0;
                    $settings['enable_twitter'] = 0;
                }
            }

            return $settings;

        }


        /**
         * Check if a language file exists in the locales folder
         *
         * @param $locale
         * @return bool|string
         */
        public static function check_language_file($locale)
        {
            $language_file_path = WMP_PLUGIN_PATH.'frontend/locales/'.strip_tags($locale).'.json';

            if (!file_exists($language_file_path)) {
                $language_file_path = WMP_PLUGIN_PATH."frontend/locales/default.json";
            }

            if (file_exists($language_file_path)){
                return $language_file_path;
            }

            return false;
        }
    }
}
