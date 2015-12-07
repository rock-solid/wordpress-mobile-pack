<?php

require_once(WMP_PLUGIN_PATH."export/class-export.php");

class ExportSettingsTest extends WP_UnitTestCase
{

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

        $this->assertArrayHasKey('icon', $data);
        $this->assertArrayHasKey('logo', $data);
        $this->assertArrayHasKey('cover', $data);
        $this->assertArrayHasKey('google_analytics_id', $data);
        $this->assertEquals(1, $data['status']);
    }
}