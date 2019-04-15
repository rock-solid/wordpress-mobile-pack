<?php if (!class_exists('PtPwa_Detect')) {

    /**
     *
     * PtPwa_Detect
     *
     * Main class for detecting the user's device and browser.
     *
     */
    class PtPwa_Detect {

        /* ----------------------------------*/
        /* Methods							 */
        /* ----------------------------------*/

        /**
         * Check if we have tablet themes support
         *
         * @return int
         */
        protected function is_allowed_tablets() {
            return false;
        }

        /**
         *
         * Check the browser's user agent and return true if the device is a supported smartphone
         *
         */
        public function detect_device() {

            $is_supported_device = 0;
            $is_supported_os = 0;
            $is_supported_browser = 0;
            $is_tablet = 0;

            if (!class_exists('WMP_Mobile_Detect')) {
                $Pt_Pwa_Config = new Pt_Pwa_Config();
                require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'libs/Mobile-Detect-2.8.25/Mobile_Detect.php');
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

                if ($is_tablet == 0 || $this->is_allowed_tablets()) {
                    $load_app = true;
                }
            }

            return $load_app;
        }
    }
}

