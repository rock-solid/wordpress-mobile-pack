<?php

if ( ! class_exists( 'WMobilePack_Admin' ) ) {

    /**
     *
     * WMobilePack_Admin class for managing the admin area for the Wordpress Mobile Pack plugin
     *
     */
    class WMobilePack_Admin
    {

        /**
         *
         * Method used to render the main admin page
         *
         */
        public function whatsnew() {

            WMobilePack_Options::update_settings('whats_new_updated', 0);

            include(WMP_PLUGIN_PATH.'admin/pages/whats-new.php');
        }

		/**
         *
         * Method used to render the themes selection page from the admin area
         *
         */
        public function themes() {

            include(WMP_PLUGIN_PATH.'admin/pages/themes.php');
        }

        /**
         *
         * Method used to render the theme settings page from the admin area
         *
         */
        public function theme_settings() {

            include(WMP_PLUGIN_PATH.'admin/pages/theme-settings.php');
        }


        /**
         * Build tree hierarchy for the pages array
         *
         * @param $all_pages
         * @return array
         */
        protected function build_pages_tree($all_pages){

            $nodes_pages = array();

            foreach ($all_pages as $p) {

                $nodes_pages[$p->ID] = array(
                    'id' => $p->ID,
                    'parent_id' => intval($p->post_parent),
                    'obj' => clone $p
                );
            }

            $pages_tree = array(0 => array());

            foreach ($nodes_pages as $n) {

                $pid = $n['parent_id'];
                $id = $n['id'];

                if (!isset($pages_tree[$pid]))
                    $pages_tree[$pid] = array('child' => array());

                if (isset($pages_tree[$id]))
                    $child = &$pages_tree[$id]['child'];
                else
                    $child = array();

                $pages_tree[$id] = $n;
                $pages_tree[$id]['child'] = &$child;
                unset($pages_tree[$id]['parent_id']);
                unset($child);

                $pages_tree[$pid]['child'][] = &$pages_tree[$id];
            }

            if (!empty($pages_tree) && !empty($pages_tree[0])) {
                $nodes_pages = $pages_tree[0]['child'];
                unset($pages_tree);
                return $nodes_pages;
            }

            return array();

        }


        /**
         *
         * Method used to render the content selection page from the admin area
         *
         */
        public function content() {

            $all_pages = get_pages(array('sort_column' => 'menu_order,post_title'));
            $pages = $this->build_pages_tree($all_pages);

            include(WMP_PLUGIN_PATH.'admin/pages/content.php');
        }


        /**
         *
         * Method used to render the settings page from the admin area
         *
         */
        public function settings() {

            include(WMP_PLUGIN_PATH.'admin/pages/settings.php');
        }

		/**
         *
         * Method used to render the PRO page from the admin area
         *
         */
        public function pro(){

            include(WMP_PLUGIN_PATH.'admin/pages/pro.php');
        }


        
        /**
         *
         * Method used to render a form with a category's details
         *
         */
        public function category_content() {

            if (isset($_GET) && is_array($_GET) && !empty($_GET)){

                if (isset($_GET['id'])) {

                    if (is_numeric($_GET['id'])) {

                        // get category
                        $category = get_category($_GET['id']);

                        if ($category != null) {

                            // load view
                            include(WMP_PLUGIN_PATH.'admin/pages/category-details.php');
                        }
                    }
                }
            }
        }


        /**
         *
         * Method used to render a form with a page's details
         *
         */
        public function page_content() {

            if (isset($_GET) && is_array($_GET) && !empty($_GET)){

                if (isset($_GET['id'])) {

                    if (is_numeric($_GET['id'])) {

                        // get page
                        $page = get_page($_GET['id']);

                        if ($page != null) {

                            if (!class_exists('WMobilePack_Formatter')) {
                                require_once(WMP_PLUGIN_PATH . 'inc/class-wmp-formatter.php');
                            }

                            $purifier = WMobilePack_Formatter::init_purifier();

                            // first check if the admin edited the content for this page
                            if (get_option(WMobilePack_Options::$prefix.'page_' .$page->ID) === false)
                                $content = apply_filters("the_content", $page->post_content);
                            else
                                $content = apply_filters("the_content", get_option( WMobilePack_Options::$prefix.'page_' .$page->ID  ));

                            $content = $purifier->purify(stripslashes($content));

                            // load view
                            include(WMP_PLUGIN_PATH.'admin/pages/page-details.php');
                        }
                    }
                }
            }
        }


        /**
         *
         * Static method used to request the content for the What's New page.
         * The method returns an array containing the latest content or an empty array by default.
         *
         */
        public static function whatsnew_updates() {

            $json_data = get_transient(WMobilePack_Options::$transient_prefix."whats_new_updates");

            // the transient is not set or expired
            if (!$json_data) {

                // check if we have a https connection
                $is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

                // jSON URL which should be requested
                $json_url = ($is_secure ? WMP_WHATSNEW_UPDATES_HTTPS : WMP_WHATSNEW_UPDATES);

                // get response
                $json_response = WMobilePack::read_data($json_url);

                if ($json_response !== false && $json_response != '') {

                    // Store this data in a transient
                    set_transient( WMobilePack_Options::$transient_prefix.'whats_new_updates', $json_response, 3600*24*2 );

                    // get response
                    $response = json_decode($json_response, true);

                    if (isset($response["content"]) && is_array($response["content"]) && !empty($response["content"])){

                        if (isset($response['content']['last_updated']) && is_numeric($response['content']['last_updated'])){

                            $last_updated = intval($response['content']['last_updated']);
                            $option_last_updated = intval(WMobilePack_Options::get_setting('whats_new_last_updated'));

                            if ($last_updated > $option_last_updated){

                                WMobilePack_Options::update_settings('whats_new_last_updated', $last_updated);
                                WMobilePack_Options::update_settings('whats_new_updated', 1);
                            }
                        }

                        // check if a new version of the PRO plugin was released
                        if (get_bloginfo('version') >= 4.2 && !WMobilePack::is_active_plugin('WordPress Mobile Pack PRO')){

                            if (isset($response['content']['pro_release'])) {

                                if (isset($response['content']['pro_release']['text']) &&
                                    isset($response['content']['pro_release']['last_updated']) && is_numeric($response['content']['pro_release']['last_updated'])) {

                                    // check if there was an update since the last time we had a release
                                    $last_updated = intval($response['content']['pro_release']['last_updated']);
                                    $option_last_updated = intval(WMobilePack_Options::get_setting('upgrade_notice_last_updated'));

                                    if ($last_updated > $option_last_updated) {

                                        // memorize the release timestamp and enable notice
                                        WMobilePack_Options::update_settings('upgrade_notice_last_updated', $last_updated);
                                        WMobilePack_Options::update_settings('upgrade_notice_updated', 1);
                                    }
                                }
                            }
                        }

                        // return response
                        return $response["content"];
                    }

                } elseif ($json_response == false) {

                    // Store this data in a transient
                    set_transient(WMobilePack_Options::$transient_prefix.'whats_new_updates', 'warning', 3600*24*2 );

                    // return message
                    return 'warning';
                }

            } else {

                if ($json_data == 'warning')
                    return $json_data;

                // get response
                $response = json_decode($json_data, true);

                if (isset($response["content"]) && is_array($response["content"]) && !empty($response["content"]))
                    return $response["content"];
            }

            // by default return empty array
            return array();
        }


        /**
         * Static method used to request the news and updates from an endpoint on a different domain.
         *
         * The method returns an array containing the latest news and updates or an empty array by default.
         *
         */
        public static function news_updates() {

            $json_data =  get_transient(WMobilePack_Options::$transient_prefix."news_updates");

            if (!$json_data) {

                // check if we have a https connection
                $is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

                // JSON URL that should be requested
                $json_url = ($is_secure ? WMP_NEWS_UPDATES_HTTPS : WMP_NEWS_UPDATES);

                // get response
                $json_response = WMobilePack::read_data($json_url);

                if ($json_response !== false && $json_response != ''){

                    // Store this data in a transient
                    set_transient(WMobilePack_Options::$transient_prefix."news_updates", $json_response, 3600*24*2);

                    // get response
                    $response = json_decode($json_response, true);

                    if ( (isset($response["news"]) && is_array($response["news"]) && !empty($response["news"])) ||
                        (isset($response["whitepaper"]) && is_array($response["whitepaper"]) && !empty($response["whitepaper"])) ) {

                        return $response;
                    }
                }

            } else {

                // get response
                $response = json_decode($json_data, true);

                if ( (isset($response["news"]) && is_array($response["news"]) && !empty($response["news"])) ||
                    (isset($response["whitepaper"]) && is_array($response["whitepaper"]) && !empty($response["whitepaper"])) ) {

                    return $response;
                }
            }

            // by default return empty array
            return array();
        }


        /**
         * Static method used to request the more from an endpoint on a different domain.
         *
         * The method returns an array containing the upgrade information or an empty array by default.
         *
         */
        public static function more_updates() {

            $json_data =  get_transient(WMobilePack_Options::$transient_prefix.'more_updates');

			if ($json_data){

				if ($json_data == 'warning') {
                    return $json_data;
				}

                // get response
                $response = json_decode($json_data, true);

                if (isset($response["content"]) && is_array($response["content"]) && !empty($response["content"])) {

					if (isset($response['content']['version']) && $response['content']['version'] == WMP_MORE_UPDATES_VERSION) {
                    	return $response["content"];
					}
				}
			}

			// check if we have a https connection
			$is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

			// JSON URL that should be requested
			$json_url = ($is_secure ? WMP_MORE_UPDATES_HTTPS : WMP_MORE_UPDATES);

			// get response
			$json_response = WMobilePack::read_data($json_url);

			if ($json_response !== false && $json_response != '') {

				// Store this data in a transient
				set_transient(WMobilePack_Options::$transient_prefix.'more_updates', $json_response, 3600*24*2);

				// get response
				$response = json_decode($json_response, true);

				if (isset($response["content"]) && is_array($response["content"]) && !empty($response["content"])){

					// return response
					return $response["content"];
				}

			} elseif ($json_response == false) {

				// Store this data in a transient
				set_transient(WMobilePack_Options::$transient_prefix.'more_updates', 'warning', 3600*24*2 );

				// return message
				return 'warning';
			}

            // by default return empty array
            return array();
        }

        /**
         * Get the link to the WP Mobile Pack PRO purchase page
         *
         * @return string
         */
        public static function upgrade_pro_link(){

            // Get premium link from the more json
            $upgrade_content = self::more_updates();

            if  (is_array($upgrade_content) && !empty($upgrade_content)){

                if (array_key_exists('premium', $upgrade_content)) {

                    if (isset($upgrade_content['premium']['packages']['upgrade_link']) &&
						filter_var($upgrade_content['premium']['packages']['upgrade_link'], FILTER_VALIDATE_URL)) {

						return $upgrade_content['premium']['packages']['upgrade_link'];
                    }

					return false;
                }
            }

            return WMP_APPTICLES_PRO_LINK;
        }

		/**
         * Get array with the PRO themes.
         *
         * @return string
         */
		public static function upgrade_pro_themes($upgrade_content = false){

			$themes = array();

			if ($upgrade_content === false)
				$upgrade_content = self::more_updates();

			if  (is_array($upgrade_content) && !empty($upgrade_content)){

				if (array_key_exists('premium', $upgrade_content) && array_key_exists('themes', $upgrade_content['premium'])) {

					if (array_key_exists('list', $upgrade_content['premium']['themes']) && is_array($upgrade_content['premium']['themes']['list'])) {

						foreach ($upgrade_content['premium']['themes']['list'] as $theme){

							if (isset($theme['title']) &&
								isset($theme['icon']) && filter_var($theme['icon'], FILTER_VALIDATE_URL) &&
								(!isset($theme['demo']['link']) || filter_var($theme['demo']['link'], FILTER_VALIDATE_URL)) &&
								(!isset($theme['details']['link']) || filter_var($theme['details']['link'], FILTER_VALIDATE_URL))
							){
								$themes[] = $theme;
							}
						}
					}
				}
			}

			return $themes;
		}
    }
}
