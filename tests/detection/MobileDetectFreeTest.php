<?php

if (!class_exists('MobileDetectFreeTest')) {

    class MobileDetectFreeTest extends WP_UnitTestCase
    {

        public static $smartphoneUserAgents = array(

            // iPhone 5 (Safari)
            'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3',

            // iPhone 6 (Safari)
            'Mozilla/6.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/8.0 Mobile/10A5376e Safari/8536.25',

            // iPhone 6 plus (Safari)
            'Mozilla/5.0 (iPhone; CPU iPhone OS 10_10_1 like Mac OS X) AppleWebKit/600.14 (KHTML, like Gecko) Version/8.0 Mobile/12A365 Safari/600.14',

            // Android (native, HTC)
            'Mozilla/5.0 (Linux; Android 5.0.2; HTC One Build/LRX22G) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/33.0.0.0 Mobile Safari/537.36',

            // Android (Chrome)
            'Mozilla/5.0 (Linux; Android 5.0.2; HTC One Build/LRX22G) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.96 Mobile Safari/537.36',

            // Android (Firefox)
            'Mozilla/5.0 (Android; Mobile; rv:37.0) Gecko/37.0 Firefox/37.0',

            // Windows Phone 8
            'Mozilla/5.0 (Windows Phone 8.1; ARM; Trident/7.0; Touch; rv:11.0; IEMobile/11.0; NOKIA; 909; Vodafone) like Gecko',

            // Firefox OS
            'Mozilla/5.0 (Mobile; rv:32.0) Gecko/32.0 Firefox/32.0'
        );


        public static $tabletsUserAgents = array(

            // iPad (iOS8, Safari)
            'Mozilla/5.0 (iPad; CPU OS 8_2 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12D508 Safari/600.1.4',

            // iPad (iOS8, Chrome)
            'Mozilla/5.0 (iPad; CPU OS 8_2 like Mac OS X; en-us) AppleWebKit/536.26 (KHTML, like Gecko) CriOS/23.0.1271.100 Mobile/12D508 Safari/8536.25',

            // iPad (iOS7)
            'Mozilla/5.0 (iPad; CPU OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53',

            // Android (Chrome)
            'Mozilla/5.0 (Linux; Android 5.0.2; Nexus 7 Build/LRX22G) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.96 Safari/537.36',

            // Android (Firefox)
            'Mozilla/5.0 (Android; Tablet; rv:37.0) Gecko/37.0 Firefox/37.0',

            // Windows tablet
            'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; Touch; rv:11.0) like Gecko',
        );


        public static $desktopUserAgents = array(

            // Chrome
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36',

            // Firefox
            'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0',

            // Internet Explorer
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)'
        );


        public static $bbUserAgents = array(

            // BB Q10
            'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.10+ (KHTML, like Gecko) Version/10.1.0.1429 Mobile Safari/537.10+',

            // BB Z10
            'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.35+ (KHTML, like Gecko) Version/10.3.1.2243 Mobile Safari/537.35+'
        );


        /**
         * @runInSeparateProcess
         */
        function test_smartphones()
        {

            foreach (self::$smartphoneUserAgents as $user_agent) {

                $_SERVER['HTTP_USER_AGENT'] = $user_agent;

                require_once(WMP_PLUGIN_PATH . 'core/mobile-detect.php');
                $WMobileDetect = new WPMobileDetect;

                $load_app = $WMobileDetect->wmp_detect_device();

                $this->assertEquals(true, $load_app);
            }
        }


        /**
         * @runInSeparateProcess
         */
        function test_tablets()
        {

            foreach (self::$tabletsUserAgents as $user_agent) {

                $_SERVER['HTTP_USER_AGENT'] = $user_agent;

                require_once(WMP_PLUGIN_PATH . 'core/mobile-detect.php');
                $WMobileDetect = new WPMobileDetect;

                $load_app = $WMobileDetect->wmp_detect_device();

                $this->assertEquals(false, $load_app);
            }
        }

        /**
         * @runInSeparateProcess
         */
        function test_desktops()
        {

            foreach (self::$desktopUserAgents as $user_agent) {

                $_SERVER['HTTP_USER_AGENT'] = $user_agent;

                require_once(WMP_PLUGIN_PATH . 'core/mobile-detect.php');
                $WMobileDetect = new WPMobileDetect;

                $load_app = $WMobileDetect->wmp_detect_device();

                $this->assertEquals(false, $load_app);
            }
        }

        /**
         * @runInSeparateProcess
         */
        function test_otherdevices()
        {

            foreach (self::$bbUserAgents as $user_agent) {

                $_SERVER['HTTP_USER_AGENT'] = $user_agent;

                require_once(WMP_PLUGIN_PATH . 'core/mobile-detect.php');
                $WMobileDetect = new WPMobileDetect;

                $load_app = $WMobileDetect->wmp_detect_device();

                $this->assertEquals(false, $load_app);
            }
        }
    }
}