<?php

if ( ! class_exists( 'WMobilePack_Export_Settings' ) ) {

    /**
     * Class WMobilePack_Export_Settings
     *
     * Contains methods for exporting settings, manifest and language files
     */
     class WMobilePack_Export_Settings
     {

        /**
         *
         * Create an uploads management object and return it
         *
         * @return object
         *
         */
        protected function get_uploads_manager()
        {
            return new WMobilePack_Uploads();
        }

		/**
         *
         * Create a manager Application object and return it
         *
         * @return object
         *
         */
        protected function get_application_manager()
        {
			if (!class_exists('WMobilePack_Application')){
				require_once(WMP_PLUGIN_PATH.'frontend/class-application.php');
			}

            return new WMobilePack_Application();
        }

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
					'short_name' => $blog_name,
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

            if ($icon_path != '' && $_GET['content'] == 'androidmanifest') {

				$base_path = $icon_path;
				$arr_manifest['icons'] = array();
				$WMP_Uploads = $this->get_uploads_manager();

				foreach (WMobilePack_Uploads::$manifest_sizes as $manifest_size) {

					$icon_path = $WMP_Uploads->get_file_url($manifest_size . $base_path);

					if ($icon_path != '') {

						$arr_manifest['icons'][] = array(
							"src" => $icon_path,
							"sizes" => $manifest_size . 'x' . $manifest_size,
							"type" => "image/png"
						);
					}

				}
			} elseif ($icon_path != '') {
				$WMP_Uploads = $this->get_uploads_manager();

				$icon_path = $WMP_Uploads->get_file_url($icon_path);

				if ($icon_path != '') {

					$arr_manifest['icons'] = array(
						'152' => $icon_path,
					);
				}
			}


            return json_encode($arr_manifest);

        }

        /**
         *
         * Export settings file.
		 *
         */
		public function export_settings()
		{

            $app = $this->get_application_manager();
            $app_settings = $app->load_app_settings();

			$frontend_path = plugins_url()."/".WMP_DOMAIN."/frontend/";
            $export_path = plugins_url()."/".WMP_DOMAIN."/export/";

            $settings = array();

            $settings['export'] = array(

                'categories' => array(
                    'find' => $export_path . 'content.php?content=exportcategories',
                    'findOne' => $export_path . 'content.php?content=exportcategory'
                ),

                'posts' => array(
                    'find' => $export_path . 'content.php?content=exportarticles',
                    'findOne' => $export_path . 'content.php?content=exportarticle'
                ),

                'pages' => array(
                    'find' => $export_path . 'content.php?content=exportpages',
                    'findOne' => $export_path . 'content.php?content=exportpage'
                ),

                'comments' => array(
                    'find' => $export_path . 'content.php?content=exportcomments',
                    'insert' => $export_path . 'content.php?content=savecomment'
                )
            );

            $settings['translate'] = array(
                'path' => $export_path.'content.php?content=apptexts&locale='.get_locale().'&format=json',
                'language' => $app->get_language(get_locale())
            );

            $settings['socialMedia'] = array(
                'facebook' => (bool)$app_settings['enable_facebook'],
                'twitter' => (bool)$app_settings['enable_twitter'],
                'google' => (bool)$app_settings['enable_google']
            );

            $settings['commentsToken'] = $app_settings['comments_token'];

            if ($app_settings['posts_per_page'] == 'single') {
                $settings['articlesPerCard'] = 1;

            } elseif ($app_settings['posts_per_page'] == 'double') {
                $settings['articlesPerCard'] = 2;

            } else {
                $settings['articlesPerCard'] = 'auto';
            }

            if ($app_settings['display_website_link']) {
                $spliter = parse_url(home_url(), PHP_URL_QUERY) ? '&' : '?';
                $settings['websiteUrl'] = home_url() . $spliter . WMobilePack_Cookie::$prefix . 'theme_mode=desktop';
            }

            $settings['logo'] = $app_settings['logo'];
            $settings['icon'] = $app_settings['icon'];
            $settings['defaultCover'] = $app_settings['cover'] != '' ? $app_settings['cover'] : $frontend_path."images/pattern-".rand(1, 6).".jpg";

            return json_encode($settings);

		}

     }
}
