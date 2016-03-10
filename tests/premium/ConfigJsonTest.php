<?php

require_once(WMP_PLUGIN_PATH . "inc/class-wmp-premium.php");

class ConfigJsonTest extends WP_UnitTestCase
{

    protected $app_settings = array(

        'timestamp' => '1457548968',

        'kit_version' => 'v2.7.4',

        'cdn_kits' => 'http://cdn-kits.appticles.com',
        'cdn_apps' => 'http://cdn.appticles.com',
        'cdn_kits_https' => 'https://d2drn63u22mxd7.cloudfront.net',
        'cdn_apps_https' => 'https://d1wltzw0mxj130.cloudfront.net',

        'api_content' => 'http://api.appticles.com/content1/',
        'api_social' => 'http://api.appticles.com/social1/',
        'api_content_https' => 'https://api.appticles.com/content1/',
        'api_social_https' => 'https://api.appticles.com/social1/',

        'webapp' => 'webappid',
        'title' => 'My app',
        'shorten_url' => 'uhnh96',
        'status' => 'visible',

        'domain_name' => 'app.mydomain.com',
        'website_url' => 'http://www.mydomain.com',

        'locale' => 'it_IT',

        'enable_facebook' => 1,
        'enable_twitter' => 0,

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
            'cover' => 'resources/smartphone_cover_1457460576.jpg'
        ),

        'tablet' => array(
            'theme' => 1,
            'color_scheme' => 1
        ),

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

        'tablet_network_code' => '103224324',
        'tablet_unit_name' => 'rand',
        'tablet_ad_interval' => 10,
        'tablet_ad_sizes' => array(
            array(700, 800),
            array(336, 280),
            array(300, 250),
            array(300, 600)
        )
    );



    /**
     * Calling validateJSON() with an invalid webapp id returns false
     */
    function test_validate_config_json_invalid_webapp_id_returns_false()
    {

        $this->app_settings['webapp'] = "invalid webapp id";

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->setMethods(array('validateThemeSettings'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Premium_Mock->expects($this->never())
            ->method('validateThemeSettings');

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Premium', 'validateJSON'
        );
        $method->setAccessible(true);

        $result = $method->invoke($WMP_Premium_Mock, $this->app_settings);
        $this->assertFalse($result);
    }


    /**
     * Calling validateJSON() with invalid phone theme settings returns false
     */
    function test_validate_config_json_invalid_theme_returns_false()
    {

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->setMethods(array('validateThemeSettings'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Premium_Mock->expects($this->exactly(2))
            ->method('validateThemeSettings')
            ->withConsecutive(
                $this->equalTo(
                    $this->app_settings['phone']
                ),
                $this->equalTo(
                    $this->app_settings['tablet']
                )
            )
            ->will(
                $this->returnValue(false)
            );

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Premium', 'validateJSON'
        );
        $method->setAccessible(true);

        $result = $method->invoke($WMP_Premium_Mock, $this->app_settings);
        $this->assertFalse($result);
    }


    /**
     * Calling validateJSON() with a valid json with phone & tablet theme settings returns true
     */
    function test_validate_config_json_phone_tablet_theme_returns_true()
    {

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->setMethods(array('validateThemeSettings'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Premium_Mock->expects($this->exactly(2))
            ->method('validateThemeSettings')
            ->withConsecutive(
                $this->equalTo(
                    $this->app_settings['phone']
                ),
                $this->equalTo(
                    $this->app_settings['tablet']
                )
            )
            ->will(
                $this->returnValue(true)
            );

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Premium', 'validateJSON'
        );
        $method->setAccessible(true);

        $result = $method->invoke($WMP_Premium_Mock, $this->app_settings);
        $this->assertTrue($result);
    }


    /**
     * Calling validateJSON() with an old json format with unified theme settings returns false
     */
    function test_validate_config_json_old_json_format_returns_false()
    {

        unset($this->app_settings['phone']);
        unset($this->app_settings['tablet']);

        $this->app_settings['kit_version'] = 'v2.5.0';

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->setMethods(array('validateThemeSettings'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Premium_Mock->expects($this->never())
            ->method('validateThemeSettings');

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Premium', 'validateJSON'
        );
        $method->setAccessible(true);

        $result = $method->invoke($WMP_Premium_Mock, $this->app_settings);
        $this->assertFalse($result);
    }
}