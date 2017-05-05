<?php

require_once(WMP_PLUGIN_PATH."export/class-export-settings.php");

class ExportSettingsTest extends WP_UnitTestCase
{

	protected $app_settings = array(
		'theme' => 1,
		'color_scheme' => 1,
		'theme_timestamp' => '',
		'font_headlines' => 1,
		'font_subtitles' => 1,
		'font_paragraphs' => 1,
		'google_analytics_id' => '',
		'display_website_link' => 1,
		'posts_per_page' => 'single',
		'enable_facebook' => 1,
		'enable_twitter' => 1,
		'enable_google' => 1,
		'icon' => '/wp-content/uploads/wordpress-mobile-pack/icon_1482240188.png',
		'logo' => '/wp-content/uploads/wordpress-mobile-pack/logo_1482240179.png',
		'cover' => '/wp-content/uploads/wordpress-mobile-pack/cover_1482241639.png',
		'comments_token' => 'NGJiZmFiZTM1NzUxNDdlODg1ZjkxMzAxZDhlOGRiYThfMTQ4MjQwOTExMQ=='
	);

    protected $expected = array(
		'export' => array(
            'categories' => array(
				'find' => 'wordpress-mobile-pack/export/content.php?content=exportcategories',
				'findOne' => 'wordpress-mobile-pack/export/content.php?content=exportcategory'
			),
            'posts' => array(
				'find' => 'wordpress-mobile-pack/export/content.php?content=exportarticles',
				'findOne' => 'wordpress-mobile-pack/export/content.php?content=exportarticle'
			),
            'pages' => array(
				'find' => 'wordpress-mobile-pack/export/content.php?content=exportpages',
				'findOne' => 'wordpress-mobile-pack/export/content.php?content=exportpage'
			),
            'comments' => array(
				'find' => 'wordpress-mobile-pack/export/content.php?content=exportcomments',
				'insert' => 'wordpress-mobile-pack/export/content.php?content=savecomment'
			)
        ),
		'translate' => array(
			'path' => 'wordpress-mobile-pack/export/content.php?content=apptexts&locale=en_US&format=json',
			'language' => 'fr'
		),
		'socialMedia' => array(
			'facebook' => true,
			'twitter' => true,
			'google' => true,
		),
		'commentsToken' => 'NGJiZmFiZTM1NzUxNDdlODg1ZjkxMzAxZDhlOGRiYThfMTQ4MjQwOTExMQ==',
		'articlesPerCard' => 1,
		'websiteUrl' => '?wmp_theme_mode=desktop',
		'logo' => '/wp-content/uploads/wordpress-mobile-pack/logo_1482240179.png',
		'icon' => '/wp-content/uploads/wordpress-mobile-pack/icon_1482240188.png',
		'defaultCover' => '/wp-content/uploads/wordpress-mobile-pack/cover_1482241639.png',
	);

	function setUp(){
        parent::setUp();

        foreach ($this->expected['export'] as $index => $paths){
			foreach ($paths as $key => $path){
				$this->expected['export'][$index][$key] = plugins_url().'/'.$path;
			}
		}

		$this->expected['translate']['path'] = plugins_url().'/'.$this->expected['translate']['path'];
		$this->expected['websiteUrl'] = home_url().$this->expected['websiteUrl'];
    }

	public function test_export_settings_return_correct_data()
	{

        $export_settings = $this->getMockBuilder('WMobilePack_Export_Settings')
            ->disableOriginalConstructor()
            ->setMethods(array('get_application_manager'))
            ->getMock();


        $settings_mock = $this->getMockBuilder('Mocked_Settings')
            ->setMethods(array('load_app_settings', 'get_language'))
            ->getMock();

        $settings_mock->expects($this->once())
            ->method('load_app_settings')
            ->will($this->returnValue($this->app_settings));

		$settings_mock->expects($this->once())
            ->method('get_language')
            ->will($this->returnValue('fr'));

        $export_settings->expects($this->once())
            ->method('get_application_manager')
            ->will($this->returnValue($settings_mock));

		$this->assertEquals(json_encode($this->expected), $export_settings->export_settings());
	}

}
