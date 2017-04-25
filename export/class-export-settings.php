<?php 

if ( ! class_exists( 'WMobilePack_Export_settings' ) ) {
    
    /**
     * Class WMobilePack_Export_settings
     *
     * Contains methods for exporting settings, manifest and language files
     */
     class WMobilePack_Export_settings
     {

        /**
         *
         * Load app texts for the current locale.
         *
         * The JSON files with translations for each language are located in frontend/locales.
         *
         * @param $locale
         * @param $response_type = javascript | list
         * @return bool|mixed
         *
         */
        public function load_language($locale, $response_type = 'javascript')
        {

            if (!class_exists('WMobilePack_Application'))
                require_once(WMP_PLUGIN_PATH.'frontend/class-application.php');

            $language_file = WMobilePack_Application::check_language_file($locale);

            if ($language_file !== false) {

                $appTexts = file_get_contents($language_file);
                $appTextsJson = json_decode($appTexts, true);

                if ($appTextsJson && !empty($appTextsJson) && array_key_exists('APP_TEXTS', $appTextsJson)) {

                    if ($response_type == 'javascript')
                        return 'var APP_TEXTS = ' . json_encode($appTextsJson['APP_TEXTS']);
                    elseif ($response_type == 'json')
                        return json_encode($appTextsJson['APP_TEXTS']);
                    else
                        return $appTextsJson;
                }
            }

            return false;
        }

        /**
         *
         * Export manifest files for Android or Mozilla.
         *
         * The method receives a single GET param:
         *
         * - content = 'androidmanifest' or 'mozillamanifest'
         */
        public function export_manifest()
        {

            // set blog name
            $blog_name = get_bloginfo("name");

			// init response depending on the manifest type
            if (isset($_GET['content']) && $_GET['content'] == 'androidmanifest') {

				$arr_manifest = array(
                    'name' => $blog_name,
                    'start_url' => home_url(),
                    'display' => 'standalone',
					'orientation' => 'any'
                );

				if (!class_exists('WMobilePack_Themes_Config')) {
					require_once(WMP_PLUGIN_PATH . 'inc/class-wmp-themes-config.php');
				}

				$background_color = WMobilePack_Themes_Config::get_manifest_background();

				if ($background_color !== false){
					$arr_manifest['theme_color'] = $background_color;
					$arr_manifest['background_color'] = $background_color;
				}

            } else {

                // remove domain name from the launch path
                $launch_path = home_url();
                $launch_path = str_replace('http://' . $_SERVER['HTTP_HOST'], '', $launch_path);
                $launch_path = str_replace('https://' . $_SERVER['HTTP_HOST'], '', $launch_path);

                $arr_manifest = array(
                    'name' => $blog_name,
                    'launch_path' => $launch_path,
                    'developer' => array(
                        "name" => $blog_name
                    )
                );
            }

            // load icon from the local settings and folder
            $icon_path = WMobilePack_Options::get_setting('icon');

            if ($icon_path != '') {

                $WMP_Uploads = $this->get_uploads_manager();
                $icon_path = $WMP_Uploads->get_file_url($icon_path);
            }

            // set icon depending on the manifest file type
            if ($icon_path != '') {

                if ($_GET['content'] == 'androidmanifest') {

                    $arr_manifest['icons'] = array(
                        array(
                            "src" => $icon_path,
                            "sizes" => "192x192"
                        )
                    );

                } else {
                    $arr_manifest['icons'] = array(
                        '152' => $icon_path,
                    );
                }
            }

            return json_encode($arr_manifest);

        }

        /**
         * Export manifest files for Android or Mozilla (Premium settings).
         *
         * The method receives a single GET param:
         *
         * - content = 'androidmanifest' or 'mozillamanifest'
         *
         * @return string
         */
        public function export_manifest_premium(){

            if (WMobilePack_Options::get_setting('premium_active') == 1 && WMobilePack_Options::get_setting('premium_api_key') != ''){

                if (!class_exists('WMobilePack_Premium'))
                    require_once(WMP_PLUGIN_PATH.'inc/class-wmp-premium.php');

                $premium_manager = new WMobilePack_Premium();
                $arr_config_premium = $premium_manager->get_premium_config();

                if ($arr_config_premium !== null){

                    if (!isset($arr_config_premium['domain_name']) || $arr_config_premium['domain_name'] == '') {

                        $blog_name = $arr_config_premium['title'];

                        if (isset($arr_config_premium['kit_type']) && $arr_config_premium['kit_type'] == 'wpmp') {
                            $blog_name = urldecode($blog_name);
                        }

                        // init response depending on the manifest type
                        if (isset($_GET['content']) && $_GET['content'] == 'androidmanifest') {

                            $arr_manifest = array(
                                'name' => $blog_name,
                                'start_url' => home_url(),
                                'display' => 'standalone'
                            );

                        } else {

                            // remove domain name from the launch path
                            $launch_path = home_url();
                            $launch_path = str_replace('http://' . $_SERVER['HTTP_HOST'], '', $launch_path);
                            $launch_path = str_replace('https://' . $_SERVER['HTTP_HOST'], '', $launch_path);

                            $arr_manifest = array(
                                'name' => $blog_name,
                                'launch_path' => $launch_path,
                                'developer' => array(
                                    "name" => $blog_name
                                )
                            );
                        }

                        // load icon path
                        $icon_path = false;

                        if (isset($arr_config_premium['icon_path']) && $arr_config_premium['icon_path'] != '') {

                            // Check if we have a secure https connection
                            $is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

                            $cdn_apps = $is_secure ? $arr_config_premium['cdn_apps_https'] : $arr_config_premium['cdn_apps'];
                            $icon_path = $cdn_apps . "/" . $arr_config_premium['shorten_url'] . '/' . $arr_config_premium['icon_path'];
                        }

                        // set icon depending on the manifest file type
                        if ($icon_path != false) {

                            if ($_GET['content'] == 'androidmanifest') {

                                $arr_manifest['icons'] = array(
                                    array(
                                        "src" => $icon_path,
                                        "sizes" => "192x192"
                                    )
                                );

                            } else {
                                $arr_manifest['icons'] = array(
                                    '152' => $icon_path,
                                );
                            }
                        }

                        return json_encode($arr_manifest);
                    }
                }
            }
        }

     }
}