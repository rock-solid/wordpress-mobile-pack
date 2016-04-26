<?php

require_once(WMP_PLUGIN_PATH."export/class-export.php");

class ExportSettingsTest extends WP_UnitTestCase
{

    function tearDown(){

        // disable connection with the API key
        $arrData = array(
            'premium_api_key' => '',
            'premium_active'  => 0,
            'premium_config_path'  => '',
            'icon' => '',
            'logo' => '',
            'cover' => '',
            'google_analytics_id' => ''
        );

        // save options
        WMobilePack_Options::update_settings($arrData);

        parent::tearDown();
    }

    /**
     * Calling export_settings() without an api key returns error
     */
    function test_export_settings_without_api_key_returns_error()
    {
        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_settings(), json_encode(array('error' => 'Missing post data (API Key) or mismatch.', 'status' => 0)));
    }

    /**
     * Calling export_settings() with an api key that doesn't match option returns error
     */
    function test_export_settings_with_invalid_api_key_returns_error()
    {
        $_POST['apiKey'] = 'dummyapikey@@#';

        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_settings(), json_encode(array('error' => 'Missing post data (API Key) or mismatch.', 'status' => 0)));
    }

    /**
     * Calling export_settings() when premium_active is not zero returns error
     */
    function test_export_settings_premium_active_returns_error()
    {
        $_POST['apiKey'] = 'dummyapikey';

        update_option('wmpack_premium_api_key', $_POST['apiKey']);
        update_option('wmpack_premium_active', 1);

        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_settings(), json_encode(array('error' => 'Premium plugin is not active.', 'status' => 0)));
    }

    /**
     * Calling export_settings() when premium_active is zero returns data
     */
    function test_export_settings_premium_inactive_returns_data()
    {
        $_POST['apiKey'] = 'dummyapikey';

        update_option('wmpack_premium_api_key', $_POST['apiKey']);
        update_option('wmpack_premium_active', 0);

        $export = new WMobilePack_Export();

        $data = json_decode($export->export_settings(), true);

        $this->assertEquals('', $data['icon']);
        $this->assertEquals('', $data['logo']);
        $this->assertEquals('', $data['cover']);
        $this->assertEquals('', $data['google_analytics_id']);
        $this->assertEquals(1, $data['status']);
    }

    /**
     * Calling export_settings() with images returns data
     */
    function test_export_settings_with_images_returns_data()
    {

        $_POST['apiKey'] = 'dummyapikey';

        update_option('wmpack_premium_api_key', $_POST['apiKey']);
        update_option('wmpack_premium_active', 0);

        update_option(WMobilePack_Options::$prefix.'icon', 'icon_path.jpg');
        update_option(WMobilePack_Options::$prefix.'logo', 'logo_path.jpg');
        update_option(WMobilePack_Options::$prefix.'cover', 'cover_path.jpg');
        update_option(WMobilePack_Options::$prefix.'google_analytics_id', 'UA-1234567-1');

        $_SERVER['HTTP_HOST'] = 'dummy.appticles.com';

        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_uploads_manager'))
            ->getMock();

        // Mock the uploads manager that will check for the file paths
        $uploads_mock = $this->getMockBuilder('Mocked_Uploads')
            ->setMethods(array('get_file_url'))
            ->getMock();

        $uploads_mock->expects($this->exactly(3))
            ->method('get_file_url')
            ->withConsecutive(
                $this->equalTo('logo_path.jpg'),
                $this->equalTo('icon_path.jpg'),
                $this->equalTo('cover_path.jpg')
            )
            ->will(
                $this->returnCallback(
                    function($parameter) {
                        return 'http://dummy.appticles.com/' . $parameter;
                    }
                )
            );

        $export_class->expects($this->once())
            ->method('get_uploads_manager')
            ->will($this->returnValue($uploads_mock));

        // Call method
        $data = json_decode($export_class->export_settings(), true);

        $this->assertEquals('http://dummy.appticles.com/icon_path.jpg', $data['icon']);
        $this->assertEquals('http://dummy.appticles.com/logo_path.jpg', $data['logo']);
        $this->assertEquals('http://dummy.appticles.com/cover_path.jpg', $data['cover']);
        $this->assertEquals('UA-1234567-1', $data['google_analytics_id']);
        $this->assertEquals(1, $data['status']);
    }
}