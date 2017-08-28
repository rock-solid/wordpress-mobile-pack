<?php

require_once(WMP_PLUGIN_PATH.'admin/class-admin-ajax.php');
require_once(WMP_PLUGIN_PATH.'inc/class-wmp-options.php');

/**
 * Testing ajax theme additional methods functionality
 *
 */
class AppAdditionalMethodsTest extends WP_UnitTestCase {


    /**
     *
     * Calling remove_image without an option returns false
     *
     */
    function test_remove_image_no_options_returns_false()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->setMethods(array('get_uploads_manager'))
            ->getMock();

        $admin_ajax->expects($this->never())
            ->method('get_uploads_manager');

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'remove_image'
        );
        $method->setAccessible(true);

        $response = $method->invoke($admin_ajax, 'icon');
        $this->assertFalse($response);
    }


    /**
     *
     * Calling remove_image with valid data removes files and returns true
     *
     */
    function test_remove_image_valid_data_returns_true()
    {

        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->setMethods(array('get_uploads_manager'))
            ->getMock();

        // Create a stub for the SomeClass class.
        $uploads_mock = $this->getMockBuilder('Mocked_Uploads')
            ->setMethods(array('remove_uploaded_file'))
            ->getMock();

		$sizes = array(48, 96, 144, 196);

		foreach ($sizes as $i => $size) {
			$uploads_mock->expects($this->at($i))
				->method('remove_uploaded_file')
				->with($this->equalTo( $size . 'icon_path.jpg'))
				->will($this->returnValue(true));
		}

		$uploads_mock->expects($this->at(4))
			->method('remove_uploaded_file')
			->with($this->equalTo('icon_path.jpg'))
			->will($this->returnValue(true));

        $admin_ajax->expects($this->once())
            ->method('get_uploads_manager')
            ->will($this->returnValue($uploads_mock));

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'remove_image'
        );
        $method->setAccessible(true);

        update_option(WMobilePack_Options::$prefix.'icon', 'icon_path.jpg');

        $response = $method->invoke($admin_ajax, 'icon');
        $this->assertTrue($response);

        delete_option(WMobilePack_Options::$prefix.'icon');
    }

    /**
     *
     * Calling remove_image_category without an options array returns false
     *
     */
    function test_remove_image_category_no_options_returns_false()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->setMethods(array('get_uploads_manager'))
            ->getMock();

        $admin_ajax->expects($this->never())
            ->method('get_uploads_manager');

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'remove_image_category'
        );
        $method->setAccessible(true);

        $response = $method->invoke($admin_ajax, 1);
        $this->assertFalse($response);
    }

    /**
     *
     * Calling remove_image_category without a category in the options array returns false
     *
     */
    function test_remove_image_category_no_category_returns_false()
    {

        update_option(WMobilePack_Options::$prefix.'categories_details', array());

        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->setMethods(array('get_uploads_manager'))
            ->getMock();

        $admin_ajax->expects($this->never())
            ->method('get_uploads_manager');

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'remove_image_category'
        );
        $method->setAccessible(true);

        $response = $method->invoke($admin_ajax, 1);
        $this->assertFalse($response);

        delete_option(WMobilePack_Options::$prefix.'categories_details');
    }

    /**
     *
     * Calling remove_image_category without an icon path in the options array returns false
     *
     */
    function test_remove_image_category_no_icon_returns_false()
    {

        update_option(WMobilePack_Options::$prefix.'categories_details', array(1 => array()));

        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->setMethods(array('get_uploads_manager'))
            ->getMock();

        $admin_ajax->expects($this->never())
            ->method('get_uploads_manager');

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'remove_image_category'
        );
        $method->setAccessible(true);

        $response = $method->invoke($admin_ajax, 1);
        $this->assertFalse($response);

        delete_option(WMobilePack_Options::$prefix.'categories_details');
    }

    /**
     *
     * Calling remove_image_category with valid data removes files and returns true
     *
     */
    function test_remove_image_category_valid_data_returns_true()
    {

        update_option(WMobilePack_Options::$prefix.'categories_details', array(1 => array('icon' => 'icon_path.jpg')));

        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->setMethods(array('get_uploads_manager'))
            ->getMock();

        // Create a stub for the SomeClass class.
        $uploads_mock = $this->getMockBuilder('Mocked_Uploads')
            ->setMethods(array('remove_uploaded_file'))
            ->getMock();

        $uploads_mock->expects($this->once())
            ->method('remove_uploaded_file')
            ->with($this->equalTo('icon_path.jpg'))
            ->will($this->returnValue(true));

        $admin_ajax->expects($this->once())
            ->method('get_uploads_manager')
            ->will($this->returnValue($uploads_mock));

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'remove_image_category'
        );
        $method->setAccessible(true);

        $response = $method->invoke($admin_ajax, 1);
        $this->assertTrue($response);

        delete_option(WMobilePack_Options::$prefix.'categories_details');
    }
}
