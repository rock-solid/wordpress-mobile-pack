<?php

require_once(WMP_PLUGIN_PATH."export/class-export.php");

class SaveCommentAllowedHostsTest extends WP_UnitTestCase
{

    function setUp(){
        parent::setUp();

        $_SERVER['HTTP_HOST'] = 'dummy.appticles.com';
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
     * Mock the export class
     *
     * @return mixed
     */
    function mock_export(){

        $WMP_Export_Mock = $this->getMockBuilder('WMobilePack_Export')
            ->disableOriginalConstructor()
            ->getMock();

        return $WMP_Export_Mock;
    }

    /**
     * Calling get_comments_allowed_hosts() with free version returns HTTP_HOST
     */
    function test_allowed_hosts_free_returns_http_host()
    {

        $WMP_Export_Mock = $this->mock_export();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Export', 'get_comments_allowed_hosts'
        );
        $method->setAccessible(true);

        $webapp_id = false;
        $result = $method->invokeArgs($WMP_Export_Mock, array(&$webapp_id));

        $this->assertEquals(array('dummy.appticles.com'), $result);
        $this->assertEquals(false, $webapp_id);
    }

    /**
     * Calling get_comments_allowed_hosts() with premium version returns array
     */
    function test_allowed_hosts_premium_returns_array()
    {

        $WMP_Export_Mock = $this->mock_export();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Export', 'get_comments_allowed_hosts'
        );
        $method->setAccessible(true);

        // Set Premium options
        update_option('wmpack_premium_active', 1);
        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_config_path', 'http://configpath.appticles.com');
        set_transient('wmp_premium_config_path', json_encode(array('webapp' => 'webappid', 'shorten_url' => 'abcdef')));

        $webapp_id = false;
        $result = $method->invokeArgs($WMP_Export_Mock, array(&$webapp_id));

        $this->assertEquals(array('dummy.appticles.com', 'app.appticles.com/abcdef'), $result);
        $this->assertEquals('webappid', $webapp_id);
    }

    /**
     * Calling get_comments_allowed_hosts() with premium version and subdomain returns array
     */
    function test_allowed_hosts_premium_subdomain_returns_array()
    {

        $WMP_Export_Mock = $this->mock_export();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Export', 'get_comments_allowed_hosts'
        );
        $method->setAccessible(true);

        // Set Premium options
        update_option('wmpack_premium_active', 1);
        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_config_path', 'http://configpath.appticles.com');

        $arr_config = array(
            'webapp' => 'webappid',
            'shorten_url' => 'abcdef',
            'domain_name' => 'app.customdomainname'
        );

        set_transient('wmp_premium_config_path', json_encode($arr_config));

        $webapp_id = false;
        $result = $method->invokeArgs($WMP_Export_Mock, array(&$webapp_id));

        $this->assertEquals(array('dummy.appticles.com', 'app.appticles.com/abcdef', 'app.customdomainname'), $result);
        $this->assertEquals('webappid', $webapp_id);
    }
}