<?php

if (!class_exists('WMobilePack_Detect')) {

    /**
     *
     * WMobilePack_Detect
     *
     * Main class for detecting the user's device and browser.
     *
     */
    class WMobilePack_Detect {


        /* ----------------------------------*/
        /* Methods							 */
        /* ----------------------------------*/

        /**
         *
         * Create a premium management object and return it
         *
         * @return object
         *
         */
        protected function get_premium_manager()
        {
            // attempt to load the settings json
            if (!class_exists('WMobilePack_Premium')) {
                require_once(WMP_PLUGIN_PATH . 'inc/class-wmp-premium.php');
            }

            return new WMobilePack_Premium();
        }


        /**
         * Check if we have tablet themes support
         *
         * @return int
         */
        protected function is_allowed_tablets(){

            if (WMobilePack_Options::get_setting('premium_active') == 1 && WMobilePack_Options::get_setting('premium_api_key') != ''){

                $premium_manager = $this->get_premium_manager();
                $arr_config_premium = $premium_manager->get_premium_config();

                if ($arr_config_premium !== null && array_key_exists('tablet', $arr_config_premium))
                    return 1;
            }

            return 0;
        }


        /**
         * Detect IE tablet
         *
         * @return bool
         */
        protected function is_IE_tablet(){

            // Check user agent only for not detected devices (should exclude Windows phones)
            if (isset($_SERVER['HTTP_USER_AGENT'])){

                // Check if user agent is IE v>10 ex: Trident/6.0 or Trident/7.0 (for IE11) with Touch
                preg_match("@Trident/([0-9]{1,}[\.0-9]{0,}); Touch@", $_SERVER['HTTP_USER_AGENT'], $matches);

                // if IE version is equal or more than 10
                if (isset($matches[1]) && $matches[1] >= 6)
                    return true;
            }

            return false;
        }



        /**
         *
         * Check the browser's user agent and return true if the device is a supported smartphone
         *
         */
        public function detect_device()
        {

            $is_supported_device = 0;
            $is_supported_os = 0;
            $is_supported_browser = 0;
            $is_tablet = 0;

            $is_allowed_tablets = $this->is_allowed_tablets();

            if (!class_exists('WMP_Mobile_Detect'))
                require_once (WMP_PLUGIN_PATH.'libs/Mobile-Detect-2.8.12/Mobile_Detect.php');

            $detect = new WMP_Mobile_Detect();

            if ($detect->isMobile() || $detect->isTablet())
                $is_supported_device = 1;

            if ($detect->isTablet())
                $is_tablet = 1;

            if ($detect->is('iOS') || $detect->is('AndroidOS') || $detect->is('WindowsPhoneOS') ||  $detect->is('WindowsMobileOS')) {
                $is_supported_os = 1;
            } else {

                // Assume we have FirefoxOS, but this part should be replaced with a proper detection
                if ($detect->isMobile() && $detect->is('Firefox') && stripos(strtolower($_SERVER['HTTP_USER_AGENT']), 'android') === false)
                    $is_supported_os = 1;
            }

            if ($detect->is('WebKit') || $detect->is('Firefox') || ($detect->is('IE') && intval($detect->version('IE')) >= 10))
                $is_supported_browser = 1;

            // Assume the device is not Windows 8 / IE
            $is_IE_tablet = false;

            // Check user agent only for not detected devices (should exclude Windows phones)
            if ($is_supported_device == 0){
                $is_IE_tablet = $this->is_IE_tablet();
            }

            // set load app variable
            $load_app = false;

            if ($is_supported_device && $is_supported_os && $is_supported_browser) {

                if ($is_tablet == 0 || $is_allowed_tablets == 1){
                    $load_app = true;
                }

            } elseif ($is_IE_tablet && $is_allowed_tablets == 1) {

                $load_app = true;
            }

            // set load app cookie
            $this->set_load_app_cookie(intval($load_app));

            return $load_app;
        }


        /**
         *
         * Check the browser's user agent and return true if the device is a supported tablet
         *
         * This method is used by the index file from the Premium theme.
         *
         */
        public function is_tablet() {

            $is_tablet = false;
            $is_IE_tablet = false;

            if (!class_exists('WMP_Mobile_Detect'))
                require_once (WMP_PLUGIN_PATH.'libs/Mobile-Detect-2.8.12/Mobile_Detect.php');

            $detect = new WMP_Mobile_Detect();

            if ($detect->isTablet())
                $is_tablet = true;

            // Check user agent only for not detected devices (should exclude Windows phones)
            if (!$detect->is('WindowsPhoneOS')  && !$detect->is('WindowsMobileOS')){
                $is_IE_tablet = $this->is_IE_tablet();
            }

            return ($is_tablet || $is_IE_tablet);
        }


        /**
         *
         * Set the set_load_app_cookie
         * The cookie is set in a separate method to allow mocking for unit testing.
         *
         * @param $value
         */
        protected function set_load_app_cookie($value)
        {
            $WMobilePackCookie = new WMobilePack_Cookie();
            $WMobilePackCookie->set_cookie('load_app', $value);
        }
    }
}