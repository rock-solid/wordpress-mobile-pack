<?php

require_once(WMP_PLUGIN_PATH.'inc/class-wmp-options.php');
require_once(WMP_PLUGIN_PATH.'frontend/class-detect.php');

if (!class_exists('MobileDetectPremiumTest')) {

    /**
     * Class MobileDetectPremiumTest
     */
    class MobileDetectPremiumTest extends WP_UnitTestCase
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

            // Windows Phone 8
            'Mozilla/5.0 (Windows Phone 8.1; ARM; Trident/7.0; Touch; rv:11.0; IEMobile/11.0; NOKIA; 909; Vodafone) like Gecko'

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
        );


        public static $desktopUserAgents = array(

            // Chrome
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36',

            // Firefox
            'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0',

            // Internet Explorer
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)'
        );


        public static $otherUserAgents = array(

            // BB Q10
            'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.10+ (KHTML, like Gecko) Version/10.1.0.1429 Mobile Safari/537.10+',

            // BB Z10
            'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.35+ (KHTML, like Gecko) Version/10.3.1.2243 Mobile Safari/537.35+',

			// Firefox OS
            'Mozilla/5.0 (Mobile; rv:32.0) Gecko/32.0 Firefox/32.0',

			// Android smartphone (Firefox)
            'Mozilla/5.0 (Android; Mobile; rv:37.0) Gecko/37.0 Firefox/37.0',

			// Android tablet (Firefox)
            'Mozilla/5.0 (Android; Tablet; rv:37.0) Gecko/37.0 Firefox/37.0',

            // Windows tablet
            'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; Touch; rv:11.0) like Gecko'
        );



        function setUp()
        {

            parent::setUp();

            update_option(WMobilePack_Options::$prefix.'premium_api_key', 'apikeytest');
            update_option(WMobilePack_Options::$prefix.'premium_active', 1);
            update_option(WMobilePack_Options::$prefix.'premium_config_path', "http://configpath.appticles.com");

            $theme_settings = array(
                'phone' => array(
                    'theme' => 1
                )
            );

            set_transient('wmp_premium_config_path', json_encode($theme_settings));
        }


        function tearDown(){

            unset($_SERVER['HTTP_HOST']);

            // disable connection with the API key
            $arrData = array(
                'premium_api_key' => '',
                'premium_active'  => 0,
                'premium_config_path'  => ''
            );

            // save options
            WMobilePack_Options::update_settings($arrData);

            delete_transient('wmp_premium_config_path');

            parent::tearDown();
        }


        /**
         * Smartphones should be allowed
         */
        function test_smartphones()
        {

            $WMobileDetect = $this->getMockBuilder('WMobilePack_Detect')
                ->setMethods(array('set_load_app_cookie'))
                ->getMock();

            $WMobileDetect->expects($this->exactly(count(self::$smartphoneUserAgents)))
                ->method('set_load_app_cookie')
                ->with($this->equalTo(true))
                ->will($this->returnValue(true));

            foreach (self::$smartphoneUserAgents as $user_agent) {

                $_SERVER['HTTP_USER_AGENT'] = $user_agent;
                $load_app = $WMobileDetect->detect_device();

                $this->assertEquals(true, $load_app);
            }
        }

        /**
         * Tablets should be allowed is the config json contains a tablet theme
         */
        function test_tablets_are_allowed()
        {

            $tablet_theme_settings = array(
                'tablet' => array(
                    'theme' => 1
                )
            );

            set_transient('wmp_premium_config_path', json_encode($tablet_theme_settings));

            $WMobileDetect = $this->getMockBuilder('WMobilePack_Detect')
                ->setMethods(array('set_load_app_cookie'))
                ->getMock();

            $WMobileDetect->expects($this->exactly(count(self::$tabletsUserAgents)))
                ->method('set_load_app_cookie')
                ->with($this->equalTo(true))
                ->will($this->returnValue(true));

            foreach (self::$tabletsUserAgents as $user_agent) {

                $_SERVER['HTTP_USER_AGENT'] = $user_agent;
                $load_app = $WMobileDetect->detect_device();

                $this->assertEquals(true, $load_app);
            }
        }

        /**
         * Tablets should NOT be allowed is the config json doesn't contain a tablet theme
         */
        function test_tablets_are_not_allowed()
        {

            $WMobileDetect = $this->getMockBuilder('WMobilePack_Detect')
                ->setMethods(array('set_load_app_cookie'))
                ->getMock();

            $WMobileDetect->expects($this->exactly(count(self::$tabletsUserAgents)))
                ->method('set_load_app_cookie')
                ->with($this->equalTo(false))
                ->will($this->returnValue(true));

            foreach (self::$tabletsUserAgents as $user_agent) {

                $_SERVER['HTTP_USER_AGENT'] = $user_agent;
                $load_app = $WMobileDetect->detect_device();

                $this->assertEquals(false, $load_app);
            }
        }

        /**
         * Desktop devices should not be allowed
         */
        function test_desktops()
        {

            $WMobileDetect = $this->getMockBuilder('WMobilePack_Detect')
                ->setMethods(array('set_load_app_cookie'))
                ->getMock();

            $WMobileDetect->expects($this->exactly(count(self::$desktopUserAgents)))
                ->method('set_load_app_cookie')
                ->with($this->equalTo(false))
                ->will($this->returnValue(true));

            // return;
            foreach (self::$desktopUserAgents as $user_agent) {

                $_SERVER['HTTP_USER_AGENT'] = $user_agent;
                $load_app = $WMobileDetect->detect_device();

                $this->assertEquals(false, $load_app);
            }
        }

        /**
         * BlackBerry devices should not be allowed
         */
        function test_otherdevices()
        {

            $WMobileDetect = $this->getMockBuilder('WMobilePack_Detect')
                ->setMethods(array('set_load_app_cookie'))
                ->getMock();

            $WMobileDetect->expects($this->exactly(count(self::$otherUserAgents)))
                ->method('set_load_app_cookie')
                ->with($this->equalTo(false))
                ->will($this->returnValue(true));

            foreach (self::$otherUserAgents as $user_agent) {

                $_SERVER['HTTP_USER_AGENT'] = $user_agent;
                $load_app = $WMobileDetect->detect_device();

                $this->assertEquals(false, $load_app);
            }
        }
    }
}
