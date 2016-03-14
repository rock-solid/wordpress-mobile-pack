<?php

require_once(WMP_PLUGIN_PATH.'frontend/class-application.php');


class AppSettingsPremiumTest extends WP_UnitTestCase
{

    function tearDown(){

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
     *
     * Mock the application class and disable original constructor
     *
     * @return mixed
     */
    protected function mockApplication(){

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->disableOriginalConstructor()
            ->getMock();

        return $WMP_Application_Mock;
    }

    /**
     *
     * Calling the load_app_settings_theme_premium() method returns array
     *
     */
    public function test_settings_theme_default(){

        $WMP_Application_Mock = $this->mockApplication();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'load_app_settings_theme_premium'
        );
        $method->setAccessible(true);

        $arr_config_premium = array(
            'phone' => array(
                'theme' => 2
            )
        );

        $result = $method->invoke($WMP_Application_Mock, $arr_config_premium, 'phone');

        $this->assertEquals(2, $result['theme']);
        $this->assertEquals(1, $result['color_scheme']);
        $this->assertEquals(1, $result['font_headlines']);
        $this->assertEquals('', $result['font_subtitles']);
        $this->assertEquals('', $result['font_paragraphs']);
        $this->assertEquals('', $result['theme_timestamp']);
        $this->assertEquals('', $result['custom_fonts']);
    }

    /**
     *
     * Calling the load_app_settings_theme_premium() method returns array
     *
     */
    public function test_settings_theme_custom(){

        $WMP_Application_Mock = $this->mockApplication();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'load_app_settings_theme_premium'
        );
        $method->setAccessible(true);

        $arr_config_premium = array(
            'tablet' => array(
                'theme' => 2,
                'color_scheme' => 0,
                'font_headlines' => 9,
                'font_subtitles' => 8,
                'font_paragraphs' => 7,
                'theme_timestamp' => '123456',
                'custom_fonts' => '1,2'
            )
        );

        $result = $method->invoke($WMP_Application_Mock, $arr_config_premium, 'tablet');

        $this->assertEquals(2, $result['theme']);
        $this->assertEquals(0, $result['color_scheme']);
        $this->assertEquals(9, $result['font_headlines']);
        $this->assertEquals(8, $result['font_subtitles']);
        $this->assertEquals(7, $result['font_paragraphs']);
        $this->assertEquals('123456', $result['theme_timestamp']);
        $this->assertEquals('1,2', $result['custom_fonts']);
    }


    /**
     *
     * Calling the load_app_settings_theme_premium() method returns array
     *
     */
    public function test_settings_paths_images(){

        $WMP_Application_Mock = $this->mockApplication();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'load_app_settings_paths_images_premium'
        );
        $method->setAccessible(true);

        $arr_config_premium = array(
            'shorten_url' => 'abcdef',
            'kit_version' => 'v2.7.4',
            'cdn_kits' => 'http://cdn-kits.appticles.com',
            'cdn_kits_https' => 'https://cdn-kits.appticles.com',
            'cdn_apps' => 'http://cdn.appticles.com',
            'cdn_apps_https' => 'https://cdn.appticles.com',
            'phone' => array(
                'theme' => 1
            )
        );

        $result = $method->invoke($WMP_Application_Mock, $arr_config_premium, 'phone', false);

        $this->assertEquals('http://cdn-kits.appticles.com', $result['cdn_kits']);
        $this->assertEquals('http://cdn.appticles.com', $result['cdn_apps']);
        $this->assertEquals('', $result['icon']);
        $this->assertEquals('', $result['logo']);
        $this->assertTrue(strpos($result['cover'], 'http://cdn-kits.appticles.com/others/covers/phone/pattern-') !== false);
        $this->assertEquals(0, $result['user_cover']);
        $this->assertEquals('http://cdn-kits.appticles.com/app1/v2.7.4/', $result['kits_path']);
        $this->assertEquals('', $result['icon_timestamp']);
        $this->assertEquals('', $result['logo_timestamp']);
    }

    /**
     *
     * Calling the load_app_settings_theme_premium() method with classic settings returns array
     *
     */
    public function test_settings_paths_images_classic(){

        $WMP_Application_Mock = $this->mockApplication();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'load_app_settings_paths_images_premium'
        );
        $method->setAccessible(true);

        $arr_config_premium = array(
            'shorten_url' => 'abcdef',
            'kit_version' => 'v2.7.4',
            'cdn_kits' => 'http://cdn-kits.appticles.com',
            'cdn_kits_https' => 'https://cdn-kits.appticles.com',
            'cdn_apps' => 'http://cdn.appticles.com',
            'cdn_apps_https' => 'https://cdn.appticles.com',
            'phone' => array(
                'theme' => 0,
                'cover' => 'resources/smartphones_cover_123458.png'
            ),
            'icon_path' => 'resources/icon_123456.png',
            'logo_path' => 'resources/logo_123457.png',
        );

        $result = $method->invoke($WMP_Application_Mock, $arr_config_premium, 'phone', false);

        $this->assertEquals('http://cdn-kits.appticles.com', $result['cdn_kits']);
        $this->assertEquals('http://cdn.appticles.com', $result['cdn_apps']);
        $this->assertEquals('http://cdn.appticles.com/abcdef/resources/icon_123456.png', $result['icon']);
        $this->assertEquals('http://cdn.appticles.com/abcdef/resources/logo_123457.png', $result['logo']);
        $this->assertEquals('http://cdn.appticles.com/abcdef/resources/smartphones_cover_123458.png', $result['cover']);
        $this->assertEquals(1, $result['user_cover']);
        $this->assertEquals('http://cdn.appticles.com/abcdef/', $result['kits_path']);
        $this->assertEquals('_123456', $result['icon_timestamp']);
        $this->assertEquals('_123457', $result['logo_timestamp']);
    }

    /**
     *
     * Calling the load_app_settings_theme_premium() method with wpmp settings returns array
     *
     */
    public function test_settings_paths_images_wpmp(){

        $WMP_Application_Mock = $this->mockApplication();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'load_app_settings_paths_images_premium'
        );
        $method->setAccessible(true);

        $arr_config_premium = array(
            'shorten_url' => 'abcdef',
            'kit_type' => 'wpmp',
            'kit_version' => 'v1.0.0',
            'cdn_kits' => 'http://cdn-kits.appticles.com',
            'cdn_kits_https' => 'https://cdn-kits.appticles.com',
            'cdn_apps' => 'http://cdn.appticles.com',
            'cdn_apps_https' => 'https://cdn.appticles.com',
            'phone' => array(
                'theme' => 1,
                'cover' => 'resources/smartphones_cover_123458.png'
            ),
            'icon_path' => 'resources/icon_123456.png',
            'logo_path' => 'resources/logo_123457.png',
        );

        $result = $method->invoke($WMP_Application_Mock, $arr_config_premium, 'phone', true);

        $this->assertEquals('https://cdn-kits.appticles.com', $result['cdn_kits']);
        $this->assertEquals('https://cdn.appticles.com', $result['cdn_apps']);
        $this->assertEquals('https://cdn.appticles.com/abcdef/resources/icon_123456.png', $result['icon']);
        $this->assertEquals('https://cdn.appticles.com/abcdef/resources/logo_123457.png', $result['logo']);
        $this->assertEquals('https://cdn.appticles.com/abcdef/resources/smartphones_cover_123458.png', $result['cover']);
        $this->assertEquals(1, $result['user_cover']);
        $this->assertEquals('https://cdn-kits.appticles.com/apps/app1/v1.0.0/', $result['kits_path']);
        $this->assertFalse(array_key_exists('icon_timestamp', $result));
        $this->assertFalse(array_key_exists('logo_timestamp', $result));
    }



    /**
     *
     * Calling the load_app_settings_google_premium() method returns array
     *
     */
    public function test_settings_google_ads(){

        $WMP_Application_Mock = $this->mockApplication();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'load_app_settings_google_premium'
        );
        $method->setAccessible(true);

        $arr_config_premium = array(
            'has_phone_ads' => 1,
            'has_tablet_ads' => 1,

            'phone_network_code' => '103234324',
            'phone_unit_name' => 'random',
            'phone_ad_interval' => 30,
            'phone_ad_sizes' => array(
                array(170, 170),
                array(336, 280),
                array(300, 250),
                array(250, 250)
            ),

            'tablet_network_code' => '103224325',
            'tablet_unit_name' => 'rand',
            'tablet_ad_interval' => 10,
            'tablet_ad_sizes' => array(
                array(700, 800),
                array(336, 280),
                array(300, 250),
                array(300, 600)
            )
        );

        $result = $method->invoke($WMP_Application_Mock, $arr_config_premium);

        $this->assertEquals(1, $result['has_phone_ads']);
        $this->assertEquals(1, $result['has_tablet_ads']);

        $this->assertEquals('103234324', $result['phone_network_code']);
        $this->assertEquals('random', $result['phone_unit_name']);
        $this->assertEquals(30, $result['phone_ad_interval']);
        $this->assertEquals($arr_config_premium['phone_ad_sizes'], $result['phone_ad_sizes']);

        $this->assertEquals('103224325', $result['tablet_network_code']);
        $this->assertEquals('rand', $result['tablet_unit_name']);
        $this->assertEquals(10, $result['tablet_ad_interval']);
        $this->assertEquals($arr_config_premium['tablet_ad_sizes'], $result['tablet_ad_sizes']);

        $this->assertFalse(array_key_exists('google_internal_id', $result));
        $this->assertFalse(array_key_exists('google_analytics_id', $result));
        $this->assertFalse(array_key_exists('google_webmasters_code', $result));
    }

    /**
     *
     * Calling the load_app_settings_google_premium() method returns array
     *
     */
    public function test_settings_google_others(){

        $WMP_Application_Mock = $this->mockApplication();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'load_app_settings_google_premium'
        );
        $method->setAccessible(true);

        $arr_config_premium = array(
            'has_phone_ads' => 0,
            'has_tablet_ads' => 0,

            'google_internal_id' => 1200,
            'google_analytics_id' => 'U-1234567-1',
            'google_webmasters_code' => 'webmasterscode',
        );

        $result = $method->invoke($WMP_Application_Mock, $arr_config_premium);

        $this->assertEquals(0, $result['has_phone_ads']);
        $this->assertEquals(0, $result['has_tablet_ads']);

        $this->assertEquals(1200, $result['google_internal_id']);
        $this->assertEquals('U-1234567-1', $result['google_analytics_id']);
        $this->assertEquals('webmasterscode', $result['google_webmasters_code']);

        $this->assertFalse(array_key_exists('phone_network_code', $result));
        $this->assertFalse(array_key_exists('phone_unit_name', $result));
        $this->assertFalse(array_key_exists('phone_ad_interval', $result));
        $this->assertFalse(array_key_exists('phone_ad_sizes', $result));

        $this->assertFalse(array_key_exists('tablet_network_code', $result));
        $this->assertFalse(array_key_exists('tablet_unit_name', $result));
        $this->assertFalse(array_key_exists('tablet_ad_interval', $result));
        $this->assertFalse(array_key_exists('tablet_ad_sizes', $result));
    }


    /**
     *
     * Calling the load_app_settings_premium() method returns array
     *
     */
    public function test_settings_premium_wpmp(){

        $premium_config = array(
            'kit_version' => 'v1.0.0',
            'kit_type' => 'wpmp',

            'cdn_kits' => 'http://cdn-kits.appticles.com',
            'cdn_apps' => 'http://cdn.appticles.com',
            'cdn_kits_https' => 'https://d2drn63u22mxd7.cloudfront.net',
            'cdn_apps_https' => 'https://d1wltzw0mxj130.cloudfront.net',

            'webapp' => 'webappid',
            'title' => 'My%20new\'%20app',
            'shorten_url' => 'abcdef',
            'status' => 'visible',

            'domain_name' => 'app.mydomain.com',
            'website_url' => 'http://www.mydomain.com?v=x',

            'locale' => 'it_IT',

            'enable_facebook' => 1,
            'enable_twitter' => 0,
            'enable_google' => 1,

            'icon_path' => 'resources/icon_1457128845.jpg',
            'google_internal_id' => 1200,
            'google_analytics_id' => 'U-1234567-1',

            'phone' => array(
                'theme' => '5',
                'cover_text' => 'Cover%20new%20app',
                'posts_per_page' => 'single'
            ),

            'has_phone_ads' => 0,
            'has_tablet_ads' => 0
        );

        $_SERVER['SERVER_PORT'] = 80;

        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_active', 1);
        update_option('wmpack_premium_config_path', "http://configdummy.appticles.com");
        set_transient('wmp_premium_config_path', json_encode($premium_config));

        $WMP_Application_Mock = $this->mockApplication();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'load_app_settings_premium'
        );
        $method->setAccessible(true);

        $result = $method->invoke($WMP_Application_Mock);

        $this->assertEquals('phone', $result['device']);
        $this->assertEquals('wpmp', $result['kit_type']);
        $this->assertEquals('abcdef', $result['shorten_url']);
        $this->assertEquals('My new\' app', $result['title']);
        $this->assertEquals('it_IT', $result['locale']);
        $this->assertEquals('Cover new app', $result['cover_text']);
        $this->assertEquals(1, $result['posts_per_page']);
        $this->assertEquals(1, $result['enable_facebook']);
        $this->assertEquals(0, $result['enable_twitter']);
        $this->assertEquals(1, $result['enable_google']);
        $this->assertEquals('app.mydomain.com', $result['domain_name']);
        $this->assertEquals('http://www.mydomain.com?v=x', $result['website_url']);

        $this->assertEquals('http://cdn-kits.appticles.com', $result['cdn_kits']);
        $this->assertEquals('http://cdn.appticles.com', $result['cdn_apps']);
        $this->assertEquals('http://cdn.appticles.com/abcdef/resources/icon_1457128845.jpg', $result['icon']);
        $this->assertEquals('', $result['logo']);
        $this->assertTrue(strpos($result['cover'], 'http://cdn-kits.appticles.com/others/covers/phone/pattern-') !== false);
        $this->assertEquals(0, $result['user_cover']);
        $this->assertEquals('http://cdn-kits.appticles.com/apps/app5/v1.0.0/', $result['kits_path']);
        $this->assertFalse(array_key_exists('icon_timestamp', $result));
        $this->assertFalse(array_key_exists('logo_timestamp', $result));

        $this->assertEquals(0, $result['has_phone_ads']);
        $this->assertEquals(0, $result['has_tablet_ads']);

        $this->assertEquals(1200, $result['google_internal_id']);
        $this->assertEquals('U-1234567-1', $result['google_analytics_id']);
        $this->assertFalse(array_key_exists('google_webmasters_code', $result));
        $this->assertTrue(array_key_exists('comments_token', $result));


        $premium_config['phone']['posts_per_page'] = 'double';
        set_transient('wmp_premium_config_path', json_encode($premium_config));

        $result = $method->invoke($WMP_Application_Mock);
        $this->assertEquals(2, $result['posts_per_page']);
    }


    /**
     *
     * Calling the load_app_settings_premium() method returns array
     */
    public function test_settings_premium_classic(){

        $premium_config = array(
            'kit_version' => 'v2.7.4',

            'cdn_kits' => 'http://cdn-kits.appticles.com',
            'cdn_apps' => 'http://cdn.appticles.com',
            'cdn_kits_https' => 'https://d2drn63u22mxd7.cloudfront.net',
            'cdn_apps_https' => 'https://d1wltzw0mxj130.cloudfront.net',

            'api_content' => 'http://api.appticles.com/content1',
            'api_content_https' => 'https://api.appticles.com/content1',
            'api_social' => 'http://api.appticles.com/social1',
            'api_social_https' => 'https://api.appticles.com/social1',

            'webapp' => 'webappid',
            'title' => 'My%20new\'%20app',
            'shorten_url' => 'abcdef',
            'status' => 'visible',

            'locale' => 'it_IT',

            'api_content_external' => 'http://customapi.appticles.com',

            'logo_path' => 'resources/logo_1457128845.jpg',
            'google_internal_id' => 1200,

            'phone' => array(
                'theme' => '3'
            ),

            'has_phone_ads' => 0,
            'has_tablet_ads' => 0
        );

        $_SERVER['SERVER_PORT'] = 443;

        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_active', 1);
        update_option('wmpack_premium_config_path', "http://configdummy.appticles.com");
        set_transient('wmp_premium_config_path', json_encode($premium_config));

        $WMP_Application_Mock = $this->mockApplication();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'load_app_settings_premium'
        );
        $method->setAccessible(true);

        $result = $method->invoke($WMP_Application_Mock);

        $this->assertEquals('webappid', $result['webapp']);
        $this->assertEquals('phone', $result['device']);
        $this->assertEquals('classic', $result['kit_type']);
        $this->assertEquals('abcdef', $result['shorten_url']);
        $this->assertEquals('My%20new\'%20app', $result['title']);
        $this->assertEquals('it_IT', $result['locale']);

        $this->assertEquals(0, $result['enable_facebook']);
        $this->assertEquals(0, $result['enable_twitter']);
        $this->assertEquals('', $result['cover_text']);
        $this->assertEquals('auto', $result['posts_per_page']);

        $this->assertEquals('https://d2drn63u22mxd7.cloudfront.net', $result['cdn_kits']);
        $this->assertEquals('https://d1wltzw0mxj130.cloudfront.net', $result['cdn_apps']);
        $this->assertEquals('https://d1wltzw0mxj130.cloudfront.net/abcdef/resources/logo_1457128845.jpg', $result['logo']);
        $this->assertEquals('', $result['icon']);
        $this->assertTrue(strpos($result['cover'], 'https://d2drn63u22mxd7.cloudfront.net/others/covers/phone/pattern-') !== false);
        $this->assertEquals(0, $result['user_cover']);
        $this->assertEquals('https://d2drn63u22mxd7.cloudfront.net/app3/v2.7.4/', $result['kits_path']);
        $this->assertEquals('', $result['icon_timestamp']);
        $this->assertEquals('_1457128845', $result['logo_timestamp']);

        $this->assertEquals('https://api.appticles.com/content1', $result['api_content']);
        $this->assertEquals('https://api.appticles.com/social1', $result['api_social']);
        $this->assertEquals('http://customapi.appticles.com', $result['api_content_external']);

        $this->assertEquals(0, $result['has_phone_ads']);
        $this->assertEquals(0, $result['has_tablet_ads']);

        $this->assertEquals(1200, $result['google_internal_id']);
        $this->assertFalse(array_key_exists('website_url', $result));
        $this->assertFalse(array_key_exists('domain_name', $result));
        $this->assertFalse(array_key_exists('google_analytics_id', $result));
        $this->assertFalse(array_key_exists('google_webmasters_code', $result));
        $this->assertFalse(array_key_exists('comments_token', $result));

        // set port back
        $_SERVER['SERVER_PORT'] = 80;
    }
}