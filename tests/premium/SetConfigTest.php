<?php

require_once(WMP_PLUGIN_PATH . "inc/class-wmp-premium.php");

class SetConfigTest extends WP_UnitTestCase
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
     * Calling set config without an api key returns false
     */
    function test_config_json_without_api_key_returns_false()
    {
        $wmp_premium = new WMobilePack_Premium();
        $this->assertFalse($wmp_premium->set_premium_config());
    }

    /**
     * Calling set config without a config path returns false
     */
    function test_config_json_empty_config_path_returns_false()
    {
        update_option('wmpack_premium_api_key', 'apikey');

        $wmp_premium = new WMobilePack_Premium();
        $this->assertFalse($wmp_premium->set_premium_config());
    }

    /**
     * Calling set config with existing transient data returns transient value
     */
    function test_config_json_existing_transient_returns_transient_value()
    {
        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_config_path', 'http://configpath.appticles.com');
        set_transient('wmp_premium_config_path', 'existing json');

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->setMethods(array('readJSONData'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Premium_Mock->expects($this->never())
            ->method('readJSONData');

        $this->assertEquals('existing json', $WMP_Premium_Mock->set_premium_config());
    }


    /**
     * Calling set config without existing transient data reads data and returns false
     */
    function test_config_json_non_existing_transient_reads_data_returns_false()
    {
        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_config_path', 'http://configpath.appticles.com');

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->setMethods(array('readJSONData'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Premium_Mock->expects($this->once())
            ->method('readJSONData')
            ->with(
                $this->equalTo('http://configpath.appticles.com')
            )
            ->will(
                $this->returnValue('')
            );

        $this->assertFalse($WMP_Premium_Mock->set_premium_config());
    }

    /**
     * Calling set config reads data, validates json and returns false
     */
    function test_config_json_reads_data_validates_json_and_returns_false()
    {
        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_config_path', 'http://configpath.appticles.com');

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->setMethods(array('readJSONData', 'validateJSON', 'validateJSONWPMP'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Premium_Mock->expects($this->once())
            ->method('readJSONData')
            ->with(
                $this->equalTo('http://configpath.appticles.com')
            )
            ->will(
                $this->returnValue('invalid json')
            );

        $WMP_Premium_Mock->expects($this->once())
            ->method('validateJSON')
            ->with(
                $this->equalTo(null)
            )
            ->will(
                $this->returnValue(false)
            );

        $WMP_Premium_Mock->expects($this->never())
            ->method('validateJSONWPMP');

        $this->assertFalse($WMP_Premium_Mock->set_premium_config());
    }

    /**
     * Calling set config reads data, validates json and returns data
     */
    function test_config_json_reads_data_validates_json_and_returns_data()
    {
        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_config_path', 'http://configpath.appticles.com');

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->setMethods(array('readJSONData', 'validateJSON', 'validateJSONWPMP'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Premium_Mock->expects($this->once())
            ->method('readJSONData')
            ->with(
                $this->equalTo('http://configpath.appticles.com')
            )
            ->will(
                $this->returnValue(json_encode(array('kit_version' => 'v2.7.4')))
            );

        $WMP_Premium_Mock->expects($this->once())
            ->method('validateJSON')
            ->with(
                $this->equalTo(array('kit_version' => 'v2.7.4'))
            )
            ->will(
                $this->returnValue(true)
            );

        $WMP_Premium_Mock->expects($this->never())
            ->method('validateJSONWPMP');

        $this->assertEquals(json_encode(array('kit_version' => 'v2.7.4')), $WMP_Premium_Mock->set_premium_config());
    }

    /**
     * Calling set config reads data, validates wpmp json and returns data
     */
    function test_config_json_reads_data_validates_wpmp_json_and_returns_data()
    {
        update_option('wmpack_premium_api_key', 'apikey');
        update_option('wmpack_premium_config_path', 'http://configpath.appticles.com');

        $WMP_Premium_Mock = $this->getMockBuilder('WMobilePack_Premium')
            ->setMethods(array('readJSONData', 'validateJSON', 'validateJSONWPMP'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Premium_Mock->expects($this->once())
            ->method('readJSONData')
            ->with(
                $this->equalTo('http://configpath.appticles.com')
            )
            ->will(
                $this->returnValue(json_encode(array('kit_version' => 'v2.7.4', 'kit_type' => 'wpmp')))
            );

        $WMP_Premium_Mock->expects($this->never())
            ->method('validateJSON');

        $WMP_Premium_Mock->expects($this->once())
            ->method('validateJSONWPMP')
            ->with(
                $this->equalTo(array('kit_version' => 'v2.7.4', 'kit_type' => 'wpmp'))
            )
            ->will(
                $this->returnValue(true)
            );

        $this->assertEquals(json_encode(array('kit_version' => 'v2.7.4', 'kit_type' => 'wpmp')), $WMP_Premium_Mock->set_premium_config());
    }
}

