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

                if ($arr_config_premium !== null && array_key_exists('tablet', $arr_config_premium)){
                    return 1;
				}

            } elseif (WMobilePack_Options::get_setting('enable_tablets') == 1) {
				return 1;
			}

            return 0;
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

                if ($is_tablet == 0 || $is_allowed_tablets == 1){
                    $load_app = true;
                }

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

            if (!class_exists('WMP_Mobile_Detect')) {
                require_once (WMP_PLUGIN_PATH.'libs/Mobile-Detect-2.8.25/Mobile_Detect.php');
			}

            $detect = new WMP_Mobile_Detect();
			return $detect->isTablet();
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
