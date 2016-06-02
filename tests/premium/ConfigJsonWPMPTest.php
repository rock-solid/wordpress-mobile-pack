<?php

require_once(WMP_PLUGIN_PATH . "inc/class-wmp-premium.php");

class ConfigJsonWPMPTest extends WP_UnitTestCase
{

    protected $app_settings_wpmp = array(

        'timestamp' => '1457548968',

        'kit_version' => 'v1.0.0',
        'kit_type' => 'wpmp',

        'cdn_kits' => 'http://cdn-kits.appticles.com',
        'cdn_apps' => 'http://cdn.appticles.com',
        'cdn_kits_https' => 'https://d2drn63u22mxd7.cloudfront.net',
        'cdn_apps_https' => 'https://d1wltzw0mxj130.cloudfront.net',

        'webapp' => 'webappid',
        'title' => 'My%20new\'%20app%20title%20%22%C3%A9%22',
        'shorten_url' => 'uhnh96',
        'status' => 'visible',

        'domain_name' => 'app.mydomain.com',
        'website_url' => 'http://www.mydomain.com?v=x',
        'smart_app_banner' => 'gateway.appticles.com/redirect-uhnh96.js',

        'locale' => 'it_IT',

        'enable_facebook' => 1,
        'enable_twitter' => 0,
        'enable_google' => 1,

        'icon_path' => 'resources/icon_1457128845.jpg',
        'google_internal_id' => '17',
        'google_analytics_id' => '',

        'phone' => array(
            'theme' => '5',
            'theme_timestamp' => '1457291706',
            'color_scheme' => 1,
            'font_headlines' => 4,
            'font_subtitles' => 1,
            'font_paragraphs' => 1,
            'cover' => 'resources/smartphone_cover_1457460576.jpg',
            'posts_per_page' => 'double',
            'cover_text' => '%3Cp%3E%C2%A0sdfsdfsdf%20sd%20sd%3C%2Fp%3E'
        ),

        'has_phone_ads' => 1,
        'has_tablet_ads' => 0,

        'phone_network_code' => '103234324',
        'phone_unit_name' => 'random',
        'phone_ad_interval' => 30,
        'phone_ad_sizes' => array(
            array(170, 170),
            array(336, 280),
            array(300, 250),
            array(250, 250)
        )
    );


    /**
     * Calling validateThemeSettings() with an invalid theme returns false
     */
    function test_validate_theme_settings_invalid_theme_returns_false(){

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Premium', 'validateThemeSettings'
        );

        $method->setAccessible(true);

        $result = $method->invoke($WMP_Premium_Mock, array('theme' => 'invalid theme id'));
        $this->assertFalse($result);
    }


    /**
     * Calling validateThemeSettings() with a valid theme returns true
     */
    function test_validate_theme_settings_valid_theme_returns_true(){

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Premium', 'validateThemeSettings'
        );

        $method->setAccessible(true);

        $result = $method->invoke($WMP_Premium_Mock, $this->app_settings_wpmp['phone']);
        $this->assertTrue($result);
    }


    /**
     * Calling validateJSONWPMP() with an invalid webapp id returns false
     */
    function test_validate_config_json_invalid_webapp_id_returns_false()
    {

        $this->app_settings_wpmp['webapp'] = "invalid webapp id";

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->setMethods(array('validateThemeSettings'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Premium_Mock->expects($this->never())
            ->method('validateThemeSettings');

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Premium', 'validateJSONWPMP'
        );
        $method->setAccessible(true);

        $result = $method->invoke($WMP_Premium_Mock, $this->app_settings_wpmp);
        $this->assertFalse($result);
    }


    /**
     * Calling validateJSONWPMP() with invalid phone theme settings returns false
     */
    function test_validate_config_json_invalid_theme_returns_false()
    {

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->setMethods(array('validateThemeSettings'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Premium_Mock->expects($this->once())
            ->method('validateThemeSettings')
            ->with(
                $this->equalTo(
                    $this->app_settings_wpmp['phone']
                )
            )
            ->will(
                $this->returnValue(false)
            );

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Premium', 'validateJSONWPMP'
        );
        $method->setAccessible(true);

        $result = $method->invoke($WMP_Premium_Mock, $this->app_settings_wpmp);
        $this->assertFalse($result);
    }


    /**
     * Calling validateJSONWPMP() with a valid json with phone theme settings returns true
     */
    function test_validate_config_json_phone_theme_returns_true()
    {

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->setMethods(array('validateThemeSettings'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Premium_Mock->expects($this->once())
            ->method('validateThemeSettings')
            ->with(
                $this->equalTo(
                    $this->app_settings_wpmp['phone']
                )
            )
            ->will(
                $this->returnValue(true)
            );

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Premium', 'validateJSONWPMP'
        );
        $method->setAccessible(true);

        $result = $method->invoke($WMP_Premium_Mock, $this->app_settings_wpmp);
        $this->assertTrue($result);
    }


    /**
     * Calling validateJSONWPMP() with a valid json with phone & tablet theme settings returns true
     */
    function test_validate_config_json_phone_tablet_theme_returns_true()
    {

        $this->app_settings_wpmp['tablet'] = array(
            'theme' => '1',
            'theme_timestamp' => '1457277706',
            'color_scheme' => 0,
            'font_headlines' => 1,
            'font_subtitles' => 2,
            'font_paragraphs' => 3,
            'cover' => 'resources/tablet_cover_1457460576.jpg',
            'posts_per_page' => 'single',
            'cover_text' => '%3Cp%3E%C2%A0sdfsdfsdf%20sd%20sd%3C%2Fp%3E'
        );

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->setMethods(array('validateThemeSettings'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Premium_Mock->expects($this->exactly(2))
            ->method('validateThemeSettings')
            ->withConsecutive(
                $this->equalTo(
                    $this->app_settings_wpmp['phone']
                ),
                $this->equalTo(
                    $this->app_settings_wpmp['tablet']
                )
            )
            ->will(
                $this->returnValue(true)
            );

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Premium', 'validateJSONWPMP'
        );
        $method->setAccessible(true);

        $result = $method->invoke($WMP_Premium_Mock, $this->app_settings_wpmp);
        $this->assertTrue($result);
    }
}