<?php

require_once(WMP_PLUGIN_PATH."export/class-export.php");

class ExportManifestTest extends WP_UnitTestCase
{

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
}