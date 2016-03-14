<?php

require_once(WMP_PLUGIN_PATH."export/class-export.php");

class ExportManifestTest extends WP_UnitTestCase
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
     * Calling export_manifest() for Android returns data
     */
    function test_export_manifest_android_returns_data()
    {
        $_GET['content'] = 'androidmanifest';

        update_option('blogname', 'Test Blog');
        update_option('home', 'http://dummy.appticles.com');

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_manifest(), true);

        $this->assertEquals('Test Blog', $data['name']);
        $this->assertEquals('http://dummy.appticles.com', $data['start_url']);
        $this->assertEquals('standalone', $data['display']);
    }

    /**
     * Calling export_manifest() for Firefox returns data
     */
    function test_export_manifest_firefox_returns_data()
    {
        update_option('blogname', 'Test Blog');
        update_option('home', 'http://dummy.appticles.com/blabla');

        $_SERVER['HTTP_HOST'] = 'dummy.appticles.com';

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_manifest(), true);

        $this->assertEquals('Test Blog', $data['name']);
        $this->assertEquals('/blabla', $data['launch_path']);
        $this->assertEquals('Test Blog', $data['developer']['name']);
    }

    /**
     * Calling export_manifest_premium() for Android without Premium settings does nothing
     */
    function test_export_manifest_premium_android_returns_nothing()
    {
        $_GET['content'] = 'androidmanifest';
        $_GET['premium'] = 1;

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_manifest_premium(), true);

        $this->assertEquals(null, $data);
    }

    /**
     * Calling export_manifest_premium() for Android with subdomain does nothing
     */
    function test_export_manifest_premium_android_subdomain_returns_nothing()
    {
        $_GET['content'] = 'androidmanifest';
        $_GET['premium'] = 1;

        $premium_config = array(
            'kit_type' => 'wpmp',
            'title' => 'My%20new\'%20app',
            'domain_name' => 'app.dummyappticles.com'
        );

        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_active', 1);
        update_option('wmpack_premium_config_path', "http://configdummy.appticles.com");
        set_transient('wmp_premium_config_path', json_encode($premium_config));

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_manifest_premium(), true);

        $this->assertEquals(null, $data);
    }

    /**
     * Calling export_manifest_premium() for Android returns data
     */
    function test_export_manifest_premium_android_returns_data()
    {
        $_GET['content'] = 'androidmanifest';
        $_GET['premium'] = 1;

        $premium_config = array(
            'kit_type' => 'wpmp',
            'title' => 'My%20new\'%20app'
        );

        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_active', 1);
        update_option('wmpack_premium_config_path', "http://configdummy.appticles.com");
        set_transient('wmp_premium_config_path', json_encode($premium_config));

        update_option('home', 'http://dummy.appticles.com/blabla');

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_manifest_premium(), true);

        $this->assertEquals('My new\' app', $data['name']);
        $this->assertEquals('http://dummy.appticles.com/blabla', $data['start_url']);
        $this->assertEquals('standalone', $data['display']);
        $this->assertFalse(array_key_exists('icons', $data));
    }


    /**
     * Calling export_manifest_premium() for Android with icon returns data
     */
    function test_export_manifest_premium_android_with_icon_returns_data()
    {
        $_GET['content'] = 'androidmanifest';
        $_GET['premium'] = 1;

        $premium_config = array(
            'kit_type' => 'wpmp',
            'shorten_url' => 'abcdef',
            'title' => 'My%20new\'%20app',
            'icon_path' => 'resources/icon.png',
            'cdn_apps' => 'http://cdn.appticles.com',
            'cdn_apps_https' => 'https://cdn.appticles.com'
        );

        $_SERVER['SERVER_PORT'] = 80;

        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_active', 1);
        update_option('wmpack_premium_config_path', "http://configdummy.appticles.com");
        set_transient('wmp_premium_config_path', json_encode($premium_config));

        update_option('home', 'http://dummy.appticles.com/blabla');

        $export = new WMobilePack_Export();

        $data = json_decode($export->export_manifest_premium(), true);

        $this->assertEquals('My new\' app', $data['name']);
        $this->assertEquals('http://dummy.appticles.com/blabla', $data['start_url']);
        $this->assertEquals('standalone', $data['display']);
        $this->assertEquals(array("src" => 'http://cdn.appticles.com/abcdef/resources/icon.png', "sizes" => "192x192"), $data['icons'][0]);
    }


    /**
     * Calling export_manifest_premium() for Android with icon and classic kit returns data
     */
    function test_export_manifest_premium_android_with_icon_classic_returns_data()
    {
        $_GET['content'] = 'androidmanifest';
        $_GET['premium'] = 1;

        $premium_config = array(
            'shorten_url' => 'abcdef',
            'title' => 'My%20new\'%20app',
            'icon_path' => 'resources/icon.png',
            'cdn_apps' => 'http://cdn.appticles.com',
            'cdn_apps_https' => 'https://cdn.appticles.com'
        );

        $_SERVER['SERVER_PORT'] = 443;

        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_active', 1);
        update_option('wmpack_premium_config_path', "http://configdummy.appticles.com");
        set_transient('wmp_premium_config_path', json_encode($premium_config));

        update_option('home', 'http://dummy.appticles.com/blabla');

        $export = new WMobilePack_Export();

        $data = json_decode($export->export_manifest_premium(), true);

        $this->assertEquals('My%20new\'%20app', $data['name']);
        $this->assertEquals('https://dummy.appticles.com/blabla', $data['start_url']);
        $this->assertEquals('standalone', $data['display']);
        $this->assertEquals(array("src" => 'https://cdn.appticles.com/abcdef/resources/icon.png', "sizes" => "192x192"), $data['icons'][0]);

        // set port back
        $_SERVER['SERVER_PORT'] = 80;
    }


    /**
     * Calling export_manifest_premium() for Firefox returns data
     */
    function test_export_manifest_premium_firefox_returns_data()
    {
        $_GET['content'] = 'mozillamanifest';
        $_GET['premium'] = 1;

        $premium_config = array(
            'kit_type' => 'wpmp',
            'shorten_url' => 'abcdef',
            'title' => 'My%20new\'%20app',
            'icon_path' => 'resources/icon.png',
            'cdn_apps' => 'http://cdn.appticles.com',
            'cdn_apps_https' => 'https://cdn.appticles.com'
        );

        $_SERVER['HTTP_HOST'] = 'dummy.appticles.com';

        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_active', 1);
        update_option('wmpack_premium_config_path', "http://configdummy.appticles.com");
        set_transient('wmp_premium_config_path', json_encode($premium_config));

        update_option('home', 'http://dummy.appticles.com/blabla');

        $export = new WMobilePack_Export();

        $data = json_decode($export->export_manifest_premium(), true);

        $this->assertEquals('My new\' app', $data['name']);
        $this->assertEquals('/blabla', $data['launch_path']);
        $this->assertEquals('My new\' app', $data['developer']['name']);
        $this->assertEquals(array('152' => 'http://cdn.appticles.com/abcdef/resources/icon.png'), $data['icons']);
    }


}