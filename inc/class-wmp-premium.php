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
         * Method used to read the config js for the premium theme and save it to a transient,
         *
         * The JSON file has the following format:
         *
         * // MANDATORY fields
         * {
         *
         *  'kit_version' : 'v2.4.2',
         *  'cdn_kits' : 'http://cdn-kits.appticles.com',
         *  'cdn_apps': 'http://cdn.appticles.com',
         *
         *  'api_content': 'http://api.appticles.com/content1/',
         *  'api_social' : 'http://api.appticles.com/social',
         *
         *  'webapp' : 'xxxxxxxxxxxxxxxxxxxxxxx',
         *  'title' : 'My app',
         *  'shorten_url' : 'xxxxxx',
         *
         *  'status' => 'visible' / 'hidden',
         *  'theme' : 1,                      // will be removed in future versions
         *
         *  'has_phone_ads' : 0/1,
         *  'has_tablet_ads' : 0/1,
         *
         *  // OPTIONAL fields
         *  'domain_name' : 'myapp.domain.com',
         *  'deactivated' : 1,
         *  'api_content_external': 'http://yourcustomapi.com',
         *
         *  'color_scheme'      : 1,          // will be removed in future versions
         *  'font_headlines'    : 1,          // will be removed in future versions
         *  'font_subtitles'    : 1,          // will be removed in future versions
         *  'font_paragraphs'   : 1,          // will be removed in future versions
         *  'cover_smartphones_path' : '',    // will be removed in future versions
         *  'cover_tablets_path' : '',        // will be removed in future versions
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
         *  // This variable should be removed in the future (it is used by WPMP <= 2.1.5)
         *  'language': 'en',
         *
         *  'locale': 'en_EN',
         *  'google_analytics_id' : 'UA-XXXXXX-1',
         *  'google_internal_id' : 'xxxxx',
         *  'google_webmasters_code' : 'xxxxxx',
         *
         *  // VERSION 2.6.0 (Separate phone and tablet theme settings)
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
         */
        public function set_premium_config(){

            if (WMobilePack_Options::get_setting('premium_api_key') != '') {

                // get config path
                $config_path = WMobilePack_Options::get_setting('premium_config_path');

                if ($config_path != '') { // check if config path is set

                    $json_data = get_transient(WMobilePack_Options::$transient_prefix."premium_config_path");

                    if (!$json_data) {

                        $delete_premium = false;

                        if (filter_var($config_path, FILTER_VALIDATE_URL) !== false) {

                            // get response
                            $json_response = WMobilePack::read_data($config_path);

                            if ($json_response !== false && $json_response != '') {

                                // is valid json
                                $arr_app_settings = json_decode($json_response, true);

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
                                    isset($arr_app_settings['theme']) && is_numeric($arr_app_settings['theme']) &&

                                    isset($arr_app_settings['has_phone_ads']) && is_numeric($arr_app_settings['has_phone_ads']) &&
                                    isset($arr_app_settings['has_tablet_ads']) && is_numeric($arr_app_settings['has_tablet_ads']) &&

                                    // validate optional fields
                                    (!isset($arr_app_settings['domain_name']) || $arr_app_settings['domain_name'] == '' || filter_var('http://'.$arr_app_settings['domain_name'], FILTER_VALIDATE_URL)) &&
                                    (!isset($arr_app_settings['deactivated']) || $arr_app_settings['deactivated'] == 0 || $arr_app_settings['deactivated'] == 1) &&
                                    (!isset($arr_app_settings['api_content_external']) || $arr_app_settings['api_content_external'] == '' || filter_var('http://'.$arr_app_settings['api_content_external'], FILTER_VALIDATE_URL)) &&
                                    (!isset($arr_app_settings['color_scheme']) || $arr_app_settings['color_scheme'] == '' || is_numeric($arr_app_settings['color_scheme'])) &&
                                    (!isset($arr_app_settings['font_headlines']) || $arr_app_settings['font_headlines'] == '' || is_numeric($arr_app_settings['font_headlines'])) &&
                                    (!isset($arr_app_settings['font_subtitles']) || $arr_app_settings['font_subtitles'] == '' || is_numeric($arr_app_settings['font_subtitles'])) &&
                                    (!isset($arr_app_settings['font_paragraphs']) || $arr_app_settings['font_paragraphs'] == '' || is_numeric($arr_app_settings['font_paragraphs'])) &&

                                    (!isset($arr_app_settings['cover_smartphones_path']) || $arr_app_settings['cover_smartphones_path'] == '' || $arr_app_settings['cover_smartphones_path'] == strip_tags($arr_app_settings['cover_smartphones_path'])) &&
                                    (!isset($arr_app_settings['cover_tablets_path']) || $arr_app_settings['cover_tablets_path'] == '' || $arr_app_settings['cover_tablets_path'] == strip_tags($arr_app_settings['cover_tablets_path'])) &&
                                    (!isset($arr_app_settings['logo_path']) || $arr_app_settings['logo_path'] == '' || $arr_app_settings['logo_path'] == strip_tags($arr_app_settings['logo_path'])) &&
                                    (!isset($arr_app_settings['icon_path']) || $arr_app_settings['icon_path'] == '' || $arr_app_settings['icon_path'] == strip_tags($arr_app_settings['icon_path'])) &&

                                    (!isset($arr_app_settings['locale']) || $arr_app_settings['locale'] == '' || ctype_alnum(str_replace('_','', $arr_app_settings['locale']))) &&
                                    (!isset($arr_app_settings['language']) || $arr_app_settings['language'] == '' || ctype_alpha($arr_app_settings['language'])) &&
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
                                    (!isset($arr_app_settings['tablet_ad_sizes']) || $arr_app_settings['tablet_ad_sizes'] == '' || is_array($arr_app_settings['tablet_ad_sizes']))
                                ) {

                                    $valid_phone = false;
                                    $valid_tablet = false;

                                    // validate new theme settings format
                                    if (isset($arr_app_settings['phone']) && is_array($arr_app_settings['phone']) &&
                                        isset($arr_app_settings['tablet']) && is_array($arr_app_settings['tablet'])) {

                                        foreach (array('phone', 'tablet') as $device){

                                            // validate theme settings per device
                                            if ( isset($arr_app_settings[$device]['theme']) && is_numeric($arr_app_settings[$device]['theme']) &&
                                                (!isset($arr_app_settings[$device]['color_scheme']) || $arr_app_settings[$device]['color_scheme'] == '' || is_numeric($arr_app_settings[$device]['color_scheme'])) &&
                                                (!isset($arr_app_settings[$device]['font_headlines']) || $arr_app_settings[$device]['font_headlines'] == '' || is_numeric($arr_app_settings[$device]['font_headlines'])) &&
                                                (!isset($arr_app_settings[$device]['font_subtitles']) || $arr_app_settings[$device]['font_subtitles'] == '' || is_numeric($arr_app_settings[$device]['font_subtitles'])) &&
                                                (!isset($arr_app_settings[$device]['font_paragraphs']) || $arr_app_settings[$device]['font_paragraphs'] == '' || is_numeric($arr_app_settings[$device]['font_paragraphs'])) &&
                                                (!isset($arr_app_settings[$device]['custom_fonts']) || $arr_app_settings[$device]['custom_fonts'] == '' || $arr_app_settings[$device]['custom_fonts'] == strip_tags($arr_app_settings[$device]['custom_fonts'])) &&
                                                (!isset($arr_app_settings[$device]['cover']) || $arr_app_settings[$device]['cover'] == '' || $arr_app_settings[$device]['cover'] == strip_tags($arr_app_settings[$device]['cover']))) {

                                                if ($device == 'phone')
                                                    $valid_phone = true;
                                                else
                                                    $valid_tablet = true;
                                            }
                                        }

                                    } else {

                                        // these will be valid if we have an old config format
                                        if ($arr_app_settings['kit_version'] == 'v2.5.0') {
                                            $valid_phone = true;
                                            $valid_tablet = true;
                                        }
                                    }

                                    if ($valid_phone && $valid_tablet) {
                                        set_transient( WMobilePack_Options::$transient_prefix.'premium_config_path', $json_response, 600 ); // transient expires every 10 minutes
                                        return $json_response;
                                    }
                                }

                            } else
                                $delete_premium = true;

                            if ($delete_premium) { // the dashboards were disconnected

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

    }
}