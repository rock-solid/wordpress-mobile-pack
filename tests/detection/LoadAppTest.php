<?php

require_once(WMP_PLUGIN_PATH.'frontend/class-application.php');
require_once(WMP_PLUGIN_PATH.'inc/class-wmp-cookie.php');
require_once(WMP_PLUGIN_PATH.'inc/class-wmp-options.php');


/**
 * Class LoadAppTest
 * @todo Revise unit tests to separate check_automatic_redirects() method from check_load() method
 *
 */
class LoadAppTest extends WP_UnitTestCase
{


    /**
     * Create a mock builder for the cookie manager
     *
     * @param $cookie_name = The cookie name that is used to call the setter
     * @param $cookie_value_get = The value returned by the getter
     * @param bool $call_set_cookie = If the setter is called
     * @param $cookie_value_set = The value expected by the setter
     *
     * @return mixed
     */
    public function mock_cookie_manager($cookie_name, $cookie_value_get, $call_set_cookie = false, $cookie_value_set = false)
    {
        $cookie_manager = $this->getMockBuilder('WMobilePack_Cookie')
            ->disableOriginalConstructor()
            ->getMock();

        $cookie_manager->expects($this->any())
            ->method('get_cookie')
            ->will($this->returnValue($cookie_value_get));

        if ($call_set_cookie) {

            $cookie_manager->expects($this->once())
                ->method('set_cookie')
                ->with(
                    $this->equalTo($cookie_name),
                    $this->equalTo($cookie_value_set === false ? $cookie_value_get: $cookie_value_set)
                )
                ->will($this->returnValue(null));

        } else {

            $cookie_manager->expects($this->never())
                ->method('set_cookie')
                ->will($this->returnValue(null));
        }

        return $cookie_manager;
    }


    /**
     * Create a mock builder for the premium manager
     *
     * @param null $premium_config
     * @return mixed
     */
    function mock_premium_manager($premium_config = null){

        $premium_manager = $this->getMockBuilder('WMobilePack_Premium')
            ->disableOriginalConstructor()
            ->getMock();

        $premium_manager->expects($this->any())
            ->method('get_premium_config')
            ->will($this->returnValue($premium_config));

        return $premium_manager;
    }


    /**
     *
     * Call the check_desktop_mode() method with a invalid get param
     *
     */
    function test_desktop_mode_invalid_param()
    {

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('get_cookie_manager'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->any())
            ->method('get_cookie_manager')
            ->will(
                $this->returnValue(
                    $this->mock_cookie_manager('theme_mode', null, false)
                )
            );

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'check_desktop_mode'
        );
        $method->setAccessible(true);

        $_GET[WMobilePack_Cookie::$prefix.'theme_mode'] = 'invalid_value';

        $result = $method->invoke($WMP_Application_Mock);
        $this->assertEquals($result, false);
    }


    /**
     *
     * Call the check_desktop_mode() method with a valid get param = desktop and check if the cookie is set
     *
     */
    function test_desktop_mode_set_cookie_desktop()
    {

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('get_cookie_manager'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->any())
            ->method('get_cookie_manager')
            ->will(
                $this->returnValue(
                    $this->mock_cookie_manager('theme_mode', 'desktop', true)
                )
            );

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'check_desktop_mode'
        );
        $method->setAccessible(true);

        $_GET[WMobilePack_Cookie::$prefix.'theme_mode'] = 'desktop';

        $result = $method->invoke($WMP_Application_Mock);
        $this->assertEquals($result, true);
    }


    /**
     *
     * Call the check_desktop_mode() method with a valid get param = mobile and check if the cookie is set
     *
     */
    function test_desktop_mode_set_cookie_mobile()
    {

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('get_cookie_manager'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->any())
            ->method('get_cookie_manager')
            ->will(
                $this->returnValue(
                    $this->mock_cookie_manager('theme_mode', 'mobile', true)
                )
            );

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'check_desktop_mode'
        );
        $method->setAccessible(true);

        $_GET[WMobilePack_Cookie::$prefix.'theme_mode'] = 'mobile';

        $result = $method->invoke($WMP_Application_Mock);
        $this->assertEquals($result, false);
    }

    /**
     *
     * Call the check_desktop_mode() method without get params and assume the cookie = desktop
     *
     */
    function test_desktop_mode_get_cookie_desktop()
    {

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('get_cookie_manager'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->any())
            ->method('get_cookie_manager')
            ->will(
                $this->returnValue(
                    $this->mock_cookie_manager('theme_mode', 'desktop', false)
                )
            );

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'check_desktop_mode'
        );

        $method->setAccessible(true);

        $result = $method->invoke($WMP_Application_Mock);
        $this->assertEquals($result, true);
    }

    /**
     *
     * Call the check_desktop_mode() method without get params and assume the cookie = mobile
     *
     */
    function test_desktop_mode_get_cookie_mobile()
    {

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('get_cookie_manager'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->any())
            ->method('get_cookie_manager')
            ->will(
                $this->returnValue(
                    $this->mock_cookie_manager('theme_mode', 'mobile', false)
                )
            );

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Application', 'check_desktop_mode'
        );

        $method->setAccessible(true);

        $result = $method->invoke($WMP_Application_Mock);
        $this->assertEquals($result, false);
    }

    /**
     *
     * Call the check_load() method with a deactivated app (Settings > Display mode) doesn't load the app
     *
     */
    function test_load_app_deactivated_display_mode_no_load()
    {

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('check_display_mode'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->once())
            ->method('check_display_mode')
            ->will($this->returnValue(false));

        $WMP_Application_Mock->expects($this->never())
            ->method('load_app');

        $WMP_Application_Mock->check_load();
    }


    /**
     *
     * Call the check_load() method with an active app (Settings > Display mode) and set cookie loads the app if
     * the user has not reverted to the desktop theme.
     *
     */
    function test_load_app_active_display_mode_and_cookie_loads_app()
    {

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('check_display_mode', 'check_desktop_mode', 'get_cookie_manager', 'load_app'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->once())
            ->method('check_display_mode')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->once())
            ->method('check_desktop_mode')
            ->will($this->returnValue(false));

        $WMP_Application_Mock->expects($this->any())
            ->method('get_cookie_manager')
            ->will(
                $this->returnValue(
                    $this->mock_cookie_manager('load_app', 1)
                )
            );

        $WMP_Application_Mock->expects($this->once())
            ->method('load_app');

        $WMP_Application_Mock->check_load();
    }

    /**
     *
     * Call the check_load() method with an active app (Settings > Display mode) and set cookie doesn't load the app
     * if the user has reverted to the desktop theme.
     *
     */
    function test_load_app_active_display_mode_and_cookie_does_not_load()
    {

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('check_display_mode', 'check_desktop_mode', 'get_cookie_manager', 'load_app'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->once())
            ->method('check_display_mode')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->once())
            ->method('check_desktop_mode')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->any())
            ->method('get_cookie_manager')
            ->will(
                $this->returnValue(
                    $this->mock_cookie_manager('load_app', 1)
                )
            );

        $WMP_Application_Mock->expects($this->never())
            ->method('load_app');

        $WMP_Application_Mock->check_load();
    }

    /**
     *
     * Call the check_load() method with an active app (Settings > Display mode) and non-existing cookie will
     * load the app if the user hasn't reverted to the desktop theme.
     *
     */
    function test_load_app_active_display_mode_without_cookie_loads_app()
    {

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('check_display_mode', 'check_desktop_mode', 'get_cookie_manager', 'check_device', 'load_app'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->once())
            ->method('check_display_mode')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->once())
            ->method('check_desktop_mode')
            ->will($this->returnValue(false));

        $WMP_Application_Mock->expects($this->once())
            ->method('check_device')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->any())
            ->method('get_cookie_manager')
            ->will(
                $this->returnValue(
                    $this->mock_cookie_manager('load_app', null)
                )
            );

        $WMP_Application_Mock->expects($this->once())
            ->method('load_app');

        $WMP_Application_Mock->check_load();
    }


    /**
     *
     * Call the check_load() method with an active app (Settings > Display mode) and non-existing cookie will
     * not load the app if the user has reverted to the desktop theme.
     *
     */
    function test_load_app_active_display_mode_without_cookie_does_not_load_app()
    {

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('check_display_mode', 'check_desktop_mode', 'get_cookie_manager', 'check_device', 'load_app'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->once())
            ->method('check_display_mode')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->once())
            ->method('check_desktop_mode')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->once())
            ->method('check_device')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->any())
            ->method('get_cookie_manager')
            ->will(
                $this->returnValue(
                    $this->mock_cookie_manager('load_app', null)
                )
            );

        $WMP_Application_Mock->expects($this->never())
            ->method('load_app');

        $WMP_Application_Mock->check_load();
    }


    /**
     *
     * Call the check_load() method with an active app (Settings > Display mode) and non-existing cookie will
     * not load the app if the device is not supported.
     *
     */
    function test_load_app_active_display_mode_without_cookie_unsupported_device_does_not_load_app()
    {

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('check_display_mode', 'check_desktop_mode', 'get_cookie_manager', 'check_device', 'load_app'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->once())
            ->method('check_display_mode')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->never())
            ->method('check_desktop_mode')
            ->will($this->returnValue(false));

        $WMP_Application_Mock->expects($this->once())
            ->method('check_device')
            ->will($this->returnValue(false));

        $WMP_Application_Mock->expects($this->any())
            ->method('get_cookie_manager')
            ->will(
                $this->returnValue(
                    $this->mock_cookie_manager('load_app', null)
                )
            );

        $WMP_Application_Mock->expects($this->never())
            ->method('load_app');

        $WMP_Application_Mock->check_load();
    }


    /**
     * Calling the check_load() method with a Premium hidden app does not load the app
     */
    function test_load_app_premium_hidden_app_does_not_load_app()
    {

        $premium_config = array(
            'status' => 'hidden'
        );

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('check_display_mode', 'check_desktop_mode', 'get_cookie_manager', 'get_premium_manager', 'check_device', 'load_app'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->once())
            ->method('get_premium_manager')
            ->will(
                $this->returnValue(
                    $this->mock_premium_manager(json_decode(json_encode($premium_config)))
                )
            );

        $WMP_Application_Mock->expects($this->never())
            ->method('get_cookie_manager')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->never())
            ->method('check_desktop_mode')
            ->will($this->returnValue(false));

        $WMP_Application_Mock->expects($this->never())
            ->method('load_app');

        $WMP_Application_Mock->check_load();
    }

    /**
     * Calling the check_load() method with a Premium deactivated app does not load the app
     */
    function test_load_app_premium_deactivated_app_does_not_load_app()
    {

        $premium_config = array(
            'deactivated' => 1
        );

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('check_display_mode', 'check_desktop_mode', 'get_cookie_manager', 'get_premium_manager', 'check_device', 'load_app'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->once())
            ->method('get_premium_manager')
            ->will(
                $this->returnValue(
                    $this->mock_premium_manager(json_decode(json_encode($premium_config)))
                )
            );

        $WMP_Application_Mock->expects($this->never())
            ->method('get_cookie_manager')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->never())
            ->method('check_desktop_mode')
            ->will($this->returnValue(false));

        $WMP_Application_Mock->expects($this->never())
            ->method('load_app');

        $WMP_Application_Mock->check_load();
    }


    /**
     * Calling the check_load() method with a Premium app with subdomain and smart app banner does not load the app
     */
    function test_load_app_premium_with_subdomain_and_smart_app_banner_does_not_load_app()
    {

        $premium_config = array(
            'domain_name' => 'dummy.appticles.com',
            'smart_app_banner' => 'gateway.apticles.com/redirect-abcdef.js',
            'kit_type' => 'wpmp'
        );

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('check_display_mode', 'check_desktop_mode', 'get_cookie_manager', 'get_premium_manager', 'check_device', 'load_app'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->once())
            ->method('get_premium_manager')
            ->will(
                $this->returnValue(
                    $this->mock_premium_manager(json_decode(json_encode($premium_config)))
                )
            );

        $WMP_Application_Mock->expects($this->never())
            ->method('check_display_mode')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->once())
            ->method('check_desktop_mode')
            ->will($this->returnValue(false));

        $WMP_Application_Mock->expects($this->once())
            ->method('check_device')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->any())
            ->method('get_cookie_manager')
            ->will(
                $this->returnValue(
                    $this->mock_cookie_manager('load_app', null)
                )
            );

        $WMP_Application_Mock->expects($this->never())
            ->method('load_app');

        $WMP_Application_Mock->check_load();
    }

    /**
     * Calling the check_load() method with a Premium app without subdomain loads the app
     */
    function test_load_app_premium_without_subdomain_loads_app()
    {

        $premium_config = array(
            'smart_app_banner' => 'gateway.apticles.com/redirect-abcdef.js',
            'kit_type' => 'wpmp'
        );

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('check_display_mode', 'check_desktop_mode', 'get_cookie_manager', 'get_premium_manager', 'check_device', 'load_app'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->once())
            ->method('get_premium_manager')
            ->will(
                $this->returnValue(
                    $this->mock_premium_manager(json_decode(json_encode($premium_config)))
                )
            );

        $WMP_Application_Mock->expects($this->never())
            ->method('check_display_mode')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->once())
            ->method('check_desktop_mode')
            ->will($this->returnValue(false));

        $WMP_Application_Mock->expects($this->once())
            ->method('check_device')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->any())
            ->method('get_cookie_manager')
            ->will(
                $this->returnValue(
                    $this->mock_cookie_manager('load_app', null)
                )
            );

        $WMP_Application_Mock->expects($this->once())
            ->method('load_app');

        $WMP_Application_Mock->check_load();
    }


    /**
     * Calling the check_load() method with a Premium app with subdomain, but without smart app banner loads the app
     */
    function test_load_app_premium_without_smart_app_banner_loads_app()
    {

        $premium_config = array(
            'domain_name' => 'dummy.appticles.com',
            'kit_type' => 'wpmp'
        );

        $WMP_Application_Mock = $this->getMockBuilder('WMobilePack_Application')
            ->setMethods(array('check_display_mode', 'check_desktop_mode', 'get_cookie_manager', 'get_premium_manager', 'check_device', 'load_app'))
            ->disableOriginalConstructor()
            ->getMock();

        $WMP_Application_Mock->expects($this->once())
            ->method('get_premium_manager')
            ->will(
                $this->returnValue(
                    $this->mock_premium_manager(json_decode(json_encode($premium_config)))
                )
            );

        $WMP_Application_Mock->expects($this->never())
            ->method('check_display_mode')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->once())
            ->method('check_desktop_mode')
            ->will($this->returnValue(false));

        $WMP_Application_Mock->expects($this->once())
            ->method('check_device')
            ->will($this->returnValue(true));

        $WMP_Application_Mock->expects($this->any())
            ->method('get_cookie_manager')
            ->will(
                $this->returnValue(
                    $this->mock_cookie_manager('load_app', null)
                )
            );

        $WMP_Application_Mock->expects($this->once())
            ->method('load_app');

        $WMP_Application_Mock->check_load();
    }
}