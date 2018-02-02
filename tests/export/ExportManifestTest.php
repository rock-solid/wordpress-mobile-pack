<?php

require_once(WMP_PLUGIN_PATH."export/class-export-settings.php");

class ExportManifestTest extends WP_UnitTestCase
{

    /**
     * Calling export_manifest() for Android returns data for default color scheme
     */
    function test_export_manifest_android_returns_data_for_default_color_scheme()
    {
        $_GET['content'] = 'androidmanifest';

        update_option('blogname', 'Test Blog');
        update_option('home', 'http://dummy.appticles.com');

        $export = new WMobilePack_Export_Settings();
        $data = json_decode($export->export_manifest(), true);

        $this->assertEquals('Test Blog', $data['name']);
        $this->assertEquals('http://dummy.appticles.com', $data['start_url']);
        $this->assertEquals('standalone', $data['display']);
		$this->assertEquals('any', $data['orientation']);
		$this->assertEquals('#ffffff', $data['theme_color']);
		$this->assertEquals('#ffffff', $data['background_color']);
    }

    /**
     * Calling export_manifest() for Android returns data for custom color scheme
     */
    function test_export_manifest_android_returns_data_for_custom_color_scheme()
    {
        $_GET['content'] = 'androidmanifest';

		$custom_colors = array (
			'#261587',
			'#000000',
			'#8224e3',
			'#8224e3',
			'#dd3333',
			'#81d742',
			'#8224e3',
			'#eeee22',
			'#1e73be',
			'#e0003b',
            '#8267e3',
            '#9a67e3',
            '#b231ed'
		);

        // Test for new Oblique
		update_option('wmpack_custom_colors', $custom_colors);
		update_option('wmpack_color_scheme', 0);

        update_option('blogname', 'Test Blog');
        update_option('home', 'http://dummy.appticles.com');

        $export = new WMobilePack_Export_Settings();
        $data = json_decode($export->export_manifest(), true);

        $this->assertEquals('Test Blog', $data['name']);
        $this->assertEquals('http://dummy.appticles.com', $data['start_url']);
        $this->assertEquals('standalone', $data['display']);
		$this->assertEquals('any', $data['orientation']);
		$this->assertEquals('#e0003b', $data['theme_color']);
		$this->assertEquals('#e0003b', $data['background_color']);

        // Test for old Oblique
        $custom_colors = array_slice($custom_colors, 0, -3);

        update_option('wmpack_custom_colors', $custom_colors);
        update_option('wmpack_theme', 1);

        $data = json_decode($export->export_manifest(), true);

        $this->assertEquals('Test Blog', $data['name']);
        $this->assertEquals('http://dummy.appticles.com', $data['start_url']);
        $this->assertEquals('standalone', $data['display']);
		$this->assertEquals('any', $data['orientation']);
		$this->assertEquals('#000000', $data['theme_color']);
		$this->assertEquals('#000000', $data['background_color']);

    }

    /**
     * Calling export_manifest() for Firefox returns data
     */
    function test_export_manifest_firefox_returns_data()
    {
        update_option('blogname', 'Test Blog');
        update_option('home', 'http://dummy.appticles.com/blabla');

        $_SERVER['HTTP_HOST'] = 'dummy.appticles.com';

        $export = new WMobilePack_Export_Settings();
        $data = json_decode($export->export_manifest(), true);

        $this->assertEquals('Test Blog', $data['name']);
        $this->assertEquals('/blabla', $data['launch_path']);
        $this->assertEquals('Test Blog', $data['developer']['name']);
    }


    /**
     * Calling export_manifest() for Android / Firefox with icon returns data
     */
    function test_export_manifest_with_icon_returns_data()
    {

        update_option('blogname', 'Test Blog');
        update_option('home', 'http://dummy.appticles.com/blabla');
        update_option(WMobilePack_Options::$prefix.'icon', 'icon_path.jpg');

        $_SERVER['HTTP_HOST'] = 'dummy.appticles.com';

        $export_class = $this->getMockBuilder('WMobilePack_Export_Settings')
            ->setMethods(array('get_uploads_manager'))
            ->getMock();

        // Mock the uploads manager that will check for the file paths
        $uploads_mock = $this->getMockBuilder('Mocked_Uploads')
            ->setMethods(array('get_file_url'))
            ->getMock();

		$sizes = array(48, 96, 144, 196);

		foreach ($sizes as $i => $size) {

			$uploads_mock->expects($this->at($i))
				->method('get_file_url')
				->with(
					$this->equalTo($size. 'icon_path.jpg')
				)
				->will($this->returnValue('http://dummy.appticles.com/'. $size . 'icon_path.jpg'));
		}

		$uploads_mock->expects($this->at(4))
			->method('get_file_url')
			->with(
				$this->equalTo('icon_path.jpg')
			)
			->will($this->returnValue('http://dummy.appticles.com/icon_path.jpg'));

        $export_class->expects($this->exactly(2))
            ->method('get_uploads_manager')
            ->will($this->returnValue($uploads_mock));

        // Check Android manifest
        $_GET['content'] = 'androidmanifest';
        $data = json_decode($export_class->export_manifest(), true);

        $this->assertEquals('Test Blog', $data['name']);
        $this->assertEquals('http://dummy.appticles.com/blabla', $data['start_url']);
        $this->assertEquals('standalone', $data['display']);
        $this->assertEquals(
            array(
                array(
                    'src' => 'http://dummy.appticles.com/48icon_path.jpg',
                    'sizes' => '48x48',
					"type" => "image/png"
                ),
					array(
                    'src' => 'http://dummy.appticles.com/96icon_path.jpg',
                    'sizes' => '96x96',
					"type" => "image/png"
                ),
					array(
                    'src' => 'http://dummy.appticles.com/144icon_path.jpg',
                    'sizes' => '144x144',
					"type" => "image/png"
                ),
					array(
                    'src' => 'http://dummy.appticles.com/196icon_path.jpg',
                    'sizes' => '196x196',
					"type" => "image/png"
                )
            ),
            $data['icons']
        );

        // Check Firefox manifest
        $_GET['content'] = 'mozillamanifest';
        $data = json_decode($export_class->export_manifest(), true);

        $this->assertEquals('Test Blog', $data['name']);
        $this->assertEquals('/blabla', $data['launch_path']);
        $this->assertEquals('Test Blog', $data['developer']['name']);
        $this->assertEquals(array('152' => 'http://dummy.appticles.com/icon_path.jpg'), $data['icons']);

        delete_option(WMobilePack_Options::$prefix.'icon');
    }
}
