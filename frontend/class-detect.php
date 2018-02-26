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
         * Check if we have tablet themes support
         *
         * @return int
         */
        protected function is_allowed_tablets(){
			return WMobilePack_Options::get_setting('enable_tablets') == 1;
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

            if (!class_exists('WMP_Mobile_Detect')) {
                require_once (WMP_PLUGIN_PATH.'libs/Mobile-Detect-2.8.25/Mobile_Detect.php');
			}

            $detect = new WMP_Mobile_Detect();

            if ($detect->isMobile() || $detect->isTablet())
                $is_supported_device = 1;

            if ($detect->isTablet()) {
                $is_tablet = 1;
			}

            if ($detect->is('iOS') || $detect->is('AndroidOS')) {
                $is_supported_os = 1;
            }

            if ($detect->is('WebKit')) {
                $is_supported_browser = 1;
			}

            // set load app variable
            $load_app = false;

            if ($is_supported_device && $is_supported_os && $is_supported_browser) {

                if ($is_tablet == 0 || $this->is_allowed_tablets()){
                    $load_app = true;
                }

            }

            // set load app cookie
            $this->set_load_app_cookie(intval($load_app));

            return $load_app;
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
