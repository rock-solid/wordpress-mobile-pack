<?php

class MobileDetectFreeTest extends WP_UnitTestCase
{
    function test_init()
    {
        $demo_plugin = $this->getMockBuilder('WMobilePack')
            ->setMethods(array('wmp_set_premium_config'))
            ->getMock();

        $demo_plugin->expects($this->once())
            ->method('wmp_set_premium_config');

        $demo_plugin->wmp_check_load();
    }
}