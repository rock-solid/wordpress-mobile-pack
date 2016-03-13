<?php

if (!class_exists('WMobilePack_Premium')) {

    /**
     *
     * Manage Premium settings
     *
     */
    class WMobilePack_Premium
    {

        /**
         * Method used to read the config js for the premium theme and save it to a transient
         */
        public function set_premium_config(){

            if (WMobilePack_Options::get_setting('premium_api_key') != '') {

                // get config path
                $config_path = WMobilePack_Options::get_setting('premium_config_path');

                if ($config_path != '') { // check if config path is set

                    $json_data = get_transient(WMobilePack_Options::$transient_prefix."premium_config_path");

                    if (!$json_data) {

                        if (filter_var($config_path, FILTER_VALIDATE_URL) !== false) {

                            // get response
                            $json_response = $this->readJSONData($config_path);

                            if ($json_response !== false && $json_response != '') {

                                // is valid json
                                $arr_app_settings = json_decode($json_response, true);

                                if (isset($arr_app_settings['kit_type']) && ctype_alnum($arr_app_settings['kit_type']) && $arr_app_settings['kit_type'] == 'wpmp'){
                                    $valid_json = $this->validateJSONWPMP($arr_app_settings);
                                } else {
                                    $valid_json = $this->validateJSON($arr_app_settings);
                                }

                                if ($valid_json){

                                    set_transient( WMobilePack_Options::$transient_prefix.'premium_config_path', $json_response, 600 ); // transient expires every 10 minutes
                                    return $json_response;
                                }

                            } else {

                                // the json doesn't exist, so the dashboards were disconnected

                                $arr_data = array(
                                    'premium_api_key' => '',
                                    'premium_active'  => 0,
                                    'premium_config_path' => ''
                                );

                                // save options
                                WMobilePack_Options::update_settings($arr_data);

                            }
                        }
                    }

                    return $json_data;
                }
            }

            return false;
        }


        /**
         * Get array with premium settings
         *
         * @param bool $as_array
         * @return mixed|null
         */
        public function get_premium_config($as_array = true){

            $json_config_premium = $this->set_premium_config();

            if ($json_config_premium !== false) {
                return json_decode($json_config_premium, $as_array);
            }

            return null;
        }


        /**
         * Get Premium kit type
         *
         * @return string (wpmp|classic)
         */
        public function get_kit_type(){

            $arr_config_premium = $this->get_premium_config();

            if ($arr_config_premium !== null && isset($arr_config_premium['kit_type']) && $arr_config_premium['kit_type'] == 'wpmp'){
                return 'wpmp';
            }

            return 'classic';
        }


        /**
         * Wrapper for reading the JSON data
         *
         * @param $config_path
         * @return bool|mixed|string
         */
        protected function readJSONData($config_path){
            return WMobilePack::read_data($config_path);
        }


        /**
         * Validate JSON config array
         *
         * The JSON file has the following format:
         *
         * // MANDATORY fields
         * {
         *
         *  'kit_version' : 'v2.4.2',
         *
         *  'cdn_kits' : 'http://cdn-kits.appticles.com',
         *  'cdn_kits_https': 'https://d2drn63u22mxd7.cloudfront.net',
         *  'cdn_apps': 'http://cdn.appticles.com',
         *  'cdn_apps_https': 'https://d1wltzw0mxj130.cloudfront.net',
         *
         *  'api_content': 'http://api.appticles.com/content1/',
         *  'api_content_https': 'https://api.appticles.com/content1/',
         *  'api_social' : 'http://api.appticles.com/social',
         *  'api_social_https' : 'http://api.appticles.com/social',
         *
         *  'webapp' : 'xxxxxxxxxxxxxxxxxxxxxxx',
         *  'title' : 'My app',
         *  'shorten_url' : 'xxxxxx',
         *  'status' => 'visible' / 'hidden',
         *
         *  'has_phone_ads' : 0/1,
         *  'has_tablet_ads' : 0/1,
         *
         *  // OPTIONAL fields
         *  'domain_name' : 'myapp.domain.com',
         *  'website_url' : 'http://mywebsiteurl.com',
         *  'deactivated' : 1,
         *  'api_content_external': 'http://yourcustomapi.com',
         *  'locale': 'en_EN',
         *
         *  'logo_path' : '',
         *  'icon_path' : '',
         *
         *  'phone_network_code' : '',
         *  'phone_unit_name' : '',
         *  'phone_ad_interval' : 30,
         *  'phone_ad_sizes' : [[250,250],[300,300],...],
         *
         *  'tablet_network_code' : '',
         *  'tablet_unit_name' : '',
         *  'tablet_ad_interval' : 30,
         *  'tablet_ad_sizes' : [[250,250],[300,300],...],
         *
         *  'enable_facebook': 1,
         *  'enable_twitter': 1,
         *
         *  'google_analytics_id' : 'UA-XXXXXX-1',
         *  'google_internal_id' : 'xxxxx',
         *  'google_webmasters_code' : 'xxxxxx',
         *
         *  'timestamp' : 'numeric timestamp',
         *
         * 'phone' : {
         *  'theme'             : 1, // 0 means a custom theme
         *  'color_scheme'      : 1,
         *  'font_headlines'    : 1,
         *  'font_subtitles'    : 1,
         *  'font_paragraphs'   : 1,
         *  'cover'             : '',
         *  'theme_timestamp'   : '',
         *  'custom_fonts''     : ''
         * }
         *
         * 'tablet' : {
         *  'theme'             : 1, // 0 means a custom theme
         *  'color_scheme'      : 1,
         *  'font_headlines'    : 1,
         *  'font_subtitles'    : 1,
         *  'font_paragraphs'   : 1,
         *  'cover'             : '',
         *  'theme_timestamp'   : '',
         *  'custom_fonts''     : ''
         * }
         *
         * }
         *
         * @param $arr_app_settings
         * @return bool
         *
         */
        protected function validateJSON($arr_app_settings){

            if (isset($arr_app_settings['kit_version']) && ctype_alnum(str_replace('.', '', $arr_app_settings['kit_version'])) &&
                isset($arr_app_settings['cdn_kits']) && filter_var($arr_app_settings['cdn_kits'], FILTER_VALIDATE_URL) &&
                isset($arr_app_settings['cdn_kits_https']) && filter_var($arr_app_settings['cdn_kits_https'], FILTER_VALIDATE_URL) &&
                isset($arr_app_settings['cdn_apps']) && filter_var($arr_app_settings['cdn_apps'], FILTER_VALIDATE_URL) &&
                isset($arr_app_settings['cdn_apps_https']) && filter_var($arr_app_settings['cdn_apps_https'], FILTER_VALIDATE_URL) &&
                isset($arr_app_settings['api_content']) && filter_var($arr_app_settings['api_content'], FILTER_VALIDATE_URL) &&
                isset($arr_app_settings['api_content_https']) && filter_var($arr_app_settings['api_content_https'], FILTER_VALIDATE_URL) &&
                isset($arr_app_settings['api_social']) && filter_var($arr_app_settings['api_social'], FILTER_VALIDATE_URL) &&
                isset($arr_app_settings['api_social_https']) && filter_var($arr_app_settings['api_social_https'], FILTER_VALIDATE_URL) &&

                isset($arr_app_settings['webapp']) && ctype_alnum($arr_app_settings['webapp']) &&
                isset($arr_app_settings['shorten_url']) && ctype_alnum($arr_app_settings['shorten_url']) &&
                isset($arr_app_settings['title']) && $arr_app_settings['title'] == strip_tags($arr_app_settings['title']) &&
                isset($arr_app_settings['status']) && in_array($arr_app_settings['status'], array('visible', 'hidden')) &&

                isset($arr_app_settings['has_phone_ads']) && is_numeric($arr_app_settings['has_phone_ads']) &&
                isset($arr_app_settings['has_tablet_ads']) && is_numeric($arr_app_settings['has_tablet_ads']) &&

                // validate optional fields
                (!isset($arr_app_settings['domain_name']) || $arr_app_settings['domain_name'] == '' || filter_var('http://'.$arr_app_settings['domain_name'], FILTER_VALIDATE_URL)) &&
                (!isset($arr_app_settings['website_url']) || $arr_app_settings['website_url'] == '' || filter_var($arr_app_settings['website_url'], FILTER_VALIDATE_URL)) &&
                (!isset($arr_app_settings['deactivated']) || $arr_app_settings['deactivated'] == 0 || $arr_app_settings['deactivated'] == 1) &&
                (!isset($arr_app_settings['api_content_external']) || $arr_app_settings['api_content_external'] == '' || filter_var('http://'.$arr_app_settings['api_content_external'], FILTER_VALIDATE_URL)) &&

                (!isset($arr_app_settings['logo_path']) || $arr_app_settings['logo_path'] == '' || $arr_app_settings['logo_path'] == strip_tags($arr_app_settings['logo_path'])) &&
                (!isset($arr_app_settings['icon_path']) || $arr_app_settings['icon_path'] == '' || $arr_app_settings['icon_path'] == strip_tags($arr_app_settings['icon_path'])) &&

                (!isset($arr_app_settings['locale']) || $arr_app_settings['locale'] == '' || ctype_alnum(str_replace('_','', $arr_app_settings['locale']))) &&
                (!isset($arr_app_settings['google_analytics_id']) || $arr_app_settings['google_analytics_id'] == '' || ctype_alnum(str_replace('-','', $arr_app_settings['google_analytics_id']))) &&
                (!isset($arr_app_settings['google_internal_id']) || $arr_app_settings['google_internal_id'] == '' || is_numeric($arr_app_settings['google_internal_id'])) &&
                (!isset($arr_app_settings['google_webmasters_code']) || $arr_app_settings['google_webmasters_code'] == '' || $arr_app_settings['google_webmasters_code'] == strip_tags($arr_app_settings['google_webmasters_code'])) &&

                (!isset($arr_app_settings['enable_facebook']) || $arr_app_settings['enable_facebook'] == '' || is_numeric($arr_app_settings['enable_facebook'])) &&
                (!isset($arr_app_settings['enable_twitter']) || $arr_app_settings['enable_twitter'] == '' || is_numeric($arr_app_settings['enable_twitter'])) &&

                (!isset($arr_app_settings['phone_network_code']) || $arr_app_settings['phone_network_code'] == '' || is_numeric($arr_app_settings['phone_network_code'])) &&
                (!isset($arr_app_settings['phone_unit_name']) || $arr_app_settings['phone_unit_name'] == '' || $arr_app_settings['phone_unit_name'] == strip_tags($arr_app_settings['phone_unit_name'])) &&
                (!isset($arr_app_settings['phone_ad_interval']) || $arr_app_settings['phone_ad_interval'] == '' || is_numeric($arr_app_settings['phone_ad_interval'])) &&
                (!isset($arr_app_settings['phone_ad_sizes']) || $arr_app_settings['phone_ad_sizes'] == '' || is_array($arr_app_settings['phone_ad_sizes'])) &&

                (!isset($arr_app_settings['tablet_network_code']) || $arr_app_settings['tablet_network_code'] == '' || is_numeric($arr_app_settings['tablet_network_code'])) &&
                (!isset($arr_app_settings['tablet_unit_name']) || $arr_app_settings['tablet_unit_name'] == '' || $arr_app_settings['tablet_unit_name'] == strip_tags($arr_app_settings['tablet_unit_name'])) &&
                (!isset($arr_app_settings['tablet_ad_interval']) || $arr_app_settings['tablet_ad_interval'] == '' || is_numeric($arr_app_settings['tablet_ad_interval'])) &&
                (!isset($arr_app_settings['tablet_ad_sizes']) || $arr_app_settings['tablet_ad_sizes'] == '' || is_array($arr_app_settings['tablet_ad_sizes'])) &&

                (!isset($arr_app_settings['timestamp']) || is_numeric($arr_app_settings['timestamp']))
            ) {

                $valid_phone = false;
                $valid_tablet = false;

                // validate new theme settings format
                if (isset($arr_app_settings['phone']) && isset($arr_app_settings['tablet'])) {

                    $valid_phone = $this->validateThemeSettings($arr_app_settings['phone']);
                    $valid_tablet = $this->validateThemeSettings($arr_app_settings['tablet']);

                }

                if ($valid_phone && $valid_tablet) {
                    return true;
                }
            }

            return false;
        }

        /**
         * The JSON file has the following format:
         *
         * // MANDATORY fields
         * {
         *
         *  'kit_version' : 'v1.0.0',
         *  'kit_type' : 'wpmp',
         *  'cdn_kits' : 'http://cdn-kits.appticles.com',
         *  'cdn_kits_https' : 'https://d2drn63u22mxd7.cloudfront.net',
         *  'cdn_apps': 'http://cdn.appticles.com'
         *  'cdn_apps_https': 'https://d1wltzw0mxj130.cloudfront.net'
         *
         *  'webapp' : 'xxxxxxxxxxxxxxxxxxxxxxx',
         *  'title' : 'My app',
         *  'shorten_url' : 'xxxxxx',
         *  'status' => 'visible' / 'hidden',
         *
         *  'has_phone_ads' : 0/1,
         *  'has_tablet_ads' : 0/1,
         *
         *  // OPTIONAL fields
         *  'domain_name' : 'myapp.domain.com',
         *  'website_url' : 'http://mywebsiteurl.com',
         *  'deactivated' : 1,
         *  'logo_path' : '',
         *  'icon_path' : '',
         *  'locale': 'en_EN',
         *
         *  'phone_network_code' : '',
         *  'phone_unit_name' : '',
         *  'phone_ad_interval' : 30,
         *  'phone_ad_sizes' : [[250,250],[300,300],...],
         *
         *  'tablet_network_code' : '',
         *  'tablet_unit_name' : '',
         *  'tablet_ad_interval' : 30,
         *  'tablet_ad_sizes' : [[250,250],[300,300],...],
         *
         *  'enable_facebook': 1,
         *  'enable_twitter': 1,
         *  'enable_google': 1,
         *
         *  'google_analytics_id' : 'UA-XXXXXX-1',
         *  'google_internal_id' : 'xxxxx',
         *  'google_webmasters_code' : 'xxxxxx',
         *
         *  'timestamp' : 'numeric timestamp',
         *
         * 'phone' : {
         *  'theme'             : 1, // 0 means a custom theme
         *  'color_scheme'      : 1,
         *  'font_headlines'    : 1,
         *  'font_subtitles'    : 1,
         *  'font_paragraphs'   : 1,
         *  'cover'             : '',
         *  'theme_timestamp'   : '',
         *  'custom_fonts''     : '',
         *  'posts_per_page''   : 'auto|single|double',
         *  'cover_text''       : '<strong>encoded html text</strong>'
         * }
         *
         * 'tablet' : {
         *  'theme'             : 1, // 0 means a custom theme
         *  'color_scheme'      : 1,
         *  'font_headlines'    : 1,
         *  'font_subtitles'    : 1,
         *  'font_paragraphs'   : 1,
         *  'cover'             : '',
         *  'theme_timestamp'   : '',
         *  'custom_fonts''     : '',
         *  'posts_per_page''   : 'auto|single|double',
         *  'cover_text''       : '<strong>encoded html text</strong>'
         * }
         *
         * }
         *
         * @param $arr_app_settings
         * @return bool
         */
        protected function validateJSONWPMP($arr_app_settings){

            if (isset($arr_app_settings['kit_version']) && ctype_alnum(str_replace('.', '', $arr_app_settings['kit_version'])) &&
                isset($arr_app_settings['kit_type']) && $arr_app_settings['kit_type'] == 'wpmp' &&
                isset($arr_app_settings['cdn_kits']) && filter_var($arr_app_settings['cdn_kits'], FILTER_VALIDATE_URL) &&
                isset($arr_app_settings['cdn_kits_https']) && filter_var($arr_app_settings['cdn_kits_https'], FILTER_VALIDATE_URL) &&
                isset($arr_app_settings['cdn_apps']) && filter_var($arr_app_settings['cdn_apps'], FILTER_VALIDATE_URL) &&
                isset($arr_app_settings['cdn_apps_https']) && filter_var($arr_app_settings['cdn_apps_https'], FILTER_VALIDATE_URL) &&

                isset($arr_app_settings['webapp']) && ctype_alnum($arr_app_settings['webapp']) &&
                isset($arr_app_settings['shorten_url']) && ctype_alnum($arr_app_settings['shorten_url']) &&
                isset($arr_app_settings['title']) && $arr_app_settings['title'] == strip_tags($arr_app_settings['title']) &&
                isset($arr_app_settings['status']) && in_array($arr_app_settings['status'], array('visible', 'hidden')) &&

                isset($arr_app_settings['has_phone_ads']) && is_numeric($arr_app_settings['has_phone_ads']) &&
                isset($arr_app_settings['has_tablet_ads']) && is_numeric($arr_app_settings['has_tablet_ads']) &&

                // validate optional fields
                (!isset($arr_app_settings['domain_name']) || $arr_app_settings['domain_name'] == '' || filter_var('http://'.$arr_app_settings['domain_name'], FILTER_VALIDATE_URL)) &&
                (!isset($arr_app_settings['website_url']) || $arr_app_settings['website_url'] == '' || filter_var($arr_app_settings['website_url'], FILTER_VALIDATE_URL)) &&
                (!isset($arr_app_settings['deactivated']) || $arr_app_settings['deactivated'] == 0 || $arr_app_settings['deactivated'] == 1) &&
                (!isset($arr_app_settings['logo_path']) || $arr_app_settings['logo_path'] == '' || $arr_app_settings['logo_path'] == strip_tags($arr_app_settings['logo_path'])) &&
                (!isset($arr_app_settings['icon_path']) || $arr_app_settings['icon_path'] == '' || $arr_app_settings['icon_path'] == strip_tags($arr_app_settings['icon_path'])) &&
                (!isset($arr_app_settings['locale']) || $arr_app_settings['locale'] == '' || ctype_alnum(str_replace('_','', $arr_app_settings['locale']))) &&

                (!isset($arr_app_settings['google_analytics_id']) || $arr_app_settings['google_analytics_id'] == '' || ctype_alnum(str_replace('-','', $arr_app_settings['google_analytics_id']))) &&
                (!isset($arr_app_settings['google_internal_id']) || $arr_app_settings['google_internal_id'] == '' || is_numeric($arr_app_settings['google_internal_id'])) &&
                (!isset($arr_app_settings['google_webmasters_code']) || $arr_app_settings['google_webmasters_code'] == '' || $arr_app_settings['google_webmasters_code'] == strip_tags($arr_app_settings['google_webmasters_code'])) &&

                (!isset($arr_app_settings['enable_facebook']) || $arr_app_settings['enable_facebook'] == '' || is_numeric($arr_app_settings['enable_facebook'])) &&
                (!isset($arr_app_settings['enable_twitter']) || $arr_app_settings['enable_twitter'] == '' || is_numeric($arr_app_settings['enable_twitter'])) &&
                (!isset($arr_app_settings['enable_google']) || $arr_app_settings['enable_google'] == '' || is_numeric($arr_app_settings['enable_google'])) &&

                (!isset($arr_app_settings['phone_network_code']) || $arr_app_settings['phone_network_code'] == '' || is_numeric($arr_app_settings['phone_network_code'])) &&
                (!isset($arr_app_settings['phone_unit_name']) || $arr_app_settings['phone_unit_name'] == '' || $arr_app_settings['phone_unit_name'] == strip_tags($arr_app_settings['phone_unit_name'])) &&
                (!isset($arr_app_settings['phone_ad_interval']) || $arr_app_settings['phone_ad_interval'] == '' || is_numeric($arr_app_settings['phone_ad_interval'])) &&
                (!isset($arr_app_settings['phone_ad_sizes']) || $arr_app_settings['phone_ad_sizes'] == '' || is_array($arr_app_settings['phone_ad_sizes'])) &&

                (!isset($arr_app_settings['tablet_network_code']) || $arr_app_settings['tablet_network_code'] == '' || is_numeric($arr_app_settings['tablet_network_code'])) &&
                (!isset($arr_app_settings['tablet_unit_name']) || $arr_app_settings['tablet_unit_name'] == '' || $arr_app_settings['tablet_unit_name'] == strip_tags($arr_app_settings['tablet_unit_name'])) &&
                (!isset($arr_app_settings['tablet_ad_interval']) || $arr_app_settings['tablet_ad_interval'] == '' || is_numeric($arr_app_settings['tablet_ad_interval'])) &&
                (!isset($arr_app_settings['tablet_ad_sizes']) || $arr_app_settings['tablet_ad_sizes'] == '' || is_array($arr_app_settings['tablet_ad_sizes'])) &&

                (!isset($arr_app_settings['timestamp']) || is_numeric($arr_app_settings['timestamp']))
            ) {

                $valid_phone = false;
                $valid_tablet = false;

                // validate phone theme settings
                if (isset($arr_app_settings['phone'])){

                    if ($this->validateThemeSettings($arr_app_settings['phone']))
                        $valid_phone = true;
                }

                // validate tablet theme settings
                if (isset($arr_app_settings['tablet'])){

                    if ($this->validateThemeSettings($arr_app_settings['tablet']))
                        $valid_tablet = true;

                } else {
                    // tablet theme settings are not mandatory
                    $valid_tablet = true;
                }

                if ($valid_phone && $valid_tablet) {
                    return true;
                }
            }

            return false;
        }


        /**
         * Validate array with theme settings
         *
         * @param $arr_theme_settings
         * @return bool
         *
         */
        protected function validateThemeSettings($arr_theme_settings){

            // validate theme settings per device
            if (is_array($arr_theme_settings)) {

                if (isset($arr_theme_settings['theme']) && is_numeric($arr_theme_settings['theme']) &&
                    (!isset($arr_theme_settings['color_scheme']) || $arr_theme_settings['color_scheme'] == '' || is_numeric($arr_theme_settings['color_scheme'])) &&
                    (!isset($arr_theme_settings['font_headlines']) || $arr_theme_settings['font_headlines'] == '' || is_numeric($arr_theme_settings['font_headlines'])) &&
                    (!isset($arr_theme_settings['font_subtitles']) || $arr_theme_settings['font_subtitles'] == '' || is_numeric($arr_theme_settings['font_subtitles'])) &&
                    (!isset($arr_theme_settings['font_paragraphs']) || $arr_theme_settings['font_paragraphs'] == '' || is_numeric($arr_theme_settings['font_paragraphs'])) &&
                    (!isset($arr_theme_settings['custom_fonts']) || $arr_theme_settings['custom_fonts'] == '' || $arr_theme_settings['custom_fonts'] == strip_tags($arr_theme_settings['custom_fonts'])) &&
                    (!isset($arr_theme_settings['cover']) || $arr_theme_settings['cover'] == '' || $arr_theme_settings['cover'] == strip_tags($arr_theme_settings['cover'])) &&
                    (!isset($arr_theme_settings['theme_timestamp']) || $arr_theme_settings['theme_timestamp'] == '' || is_numeric($arr_theme_settings['theme_timestamp'])) &&
                    (!isset($arr_theme_settings['posts_per_page']) || in_array($arr_theme_settings['posts_per_page'], array('auto', 'single', 'double')))
                ) {
                    return true;
                }
            }

            return false;
        }
    }
}