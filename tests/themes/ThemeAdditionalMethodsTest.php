<?php

require_once(PWA_PLUGIN_PATH.'admin/class-admin-ajax.php');
require_once(PWA_PLUGIN_PATH.'inc/class-wmp-options.php');

/**
 * Testing ajax theme additional methods functionality
 *
 * @group      ajax
 */
class ThemeAdditionalMethodsTest extends WP_UnitTestCase {

	protected $color_vars = array(
		"base-text-color",
        "base-bg-color",
        "article-border-color",
        "extra-text-color",
        "category-color",
        "category-text-color",
        "buttons-bg-color",
        "buttons-color",
        "actions-panel-background",
        "actions-panel-color",
        "actions-panel-border",
        "form-color",
        "cover-text-color"
	);

	protected $allowed_fonts = array(
		'headlines-font' => '',
		'subtitles-font' => '',
		'paragraphs-font' => ''
	);

    /**
     *
     * Calling update fonts with empty data returns false
     *
     */
    function test_update_fonts_without_args_returns_false()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_fonts'
        );
        $method->setAccessible(true);

        $response = $method->invoke($admin_ajax, array(), $this->allowed_fonts);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], false);
        $this->assertEquals($response['updated'], false);
    }


    /**
     *
     * Calling update fonts with values different than default returns true and generates theme
     *
     */
    function test_update_fonts_with_not_default_values_generates_theme()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_fonts'
        );
        $method->setAccessible(true);

        $data = array(
            'wmp_edittheme_fontheadlines' => 2,
            'wmp_edittheme_fontsubtitles' => 3,
            'wmp_edittheme_fontparagraphs' => 4
        );

        $response = $method->invoke($admin_ajax, $data, $this->allowed_fonts);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], true);
        $this->assertEquals($response['updated'], true);
    }

    /**
     *
     * Calling update fonts with default values does not generate theme
     *
     */
    function test_update_fonts_with_default_fonts_does_not_generate_theme()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_fonts'
        );
        $method->setAccessible(true);

        update_option(WMobilePack_Options::$prefix.'font_headlines', 2);
        update_option(WMobilePack_Options::$prefix.'font_subtitles', 3);
        update_option(WMobilePack_Options::$prefix.'font_paragraphs', 4);

        $data = array(
            'wmp_edittheme_fontheadlines' => 1,
            'wmp_edittheme_fontsubtitles' => 1,
            'wmp_edittheme_fontparagraphs' => 1
        );

        $response = $method->invoke($admin_ajax, $data, $this->allowed_fonts);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], false);
        $this->assertEquals($response['updated'], true);

    }

	/**
     *
     * Calling update fonts with unchanged values does not generate theme and returns false
     *
     */
    function test_update_fonts_with_unchanged_values_returns_false()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_fonts'
        );
        $method->setAccessible(true);

        update_option(WMobilePack_Options::$prefix.'font_headlines', 2);
        update_option(WMobilePack_Options::$prefix.'font_subtitles', 3);
        update_option(WMobilePack_Options::$prefix.'font_paragraphs', 4);

        $data = array(
            'wmp_edittheme_fontheadlines' => 2,
            'wmp_edittheme_fontsubtitles' => 3,
            'wmp_edittheme_fontparagraphs' => 4
        );

        $response = $method->invoke($admin_ajax, $data, $this->allowed_fonts);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], true);
        $this->assertEquals($response['updated'], false);

    }

    /**
     *
     * Calling update fonts with a single changed setting generates theme
     *
     */
    function test_update_fonts_with_single_change_generates_theme()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_fonts'
        );
        $method->setAccessible(true);

        update_option(WMobilePack_Options::$prefix.'font_headlines', 2);
        update_option(WMobilePack_Options::$prefix.'font_subtitles', 3);
        update_option(WMobilePack_Options::$prefix.'font_paragraphs', 4);

        $data = array(
            'wmp_edittheme_fontheadlines' => 2,
            'wmp_edittheme_fontsubtitles' => 3,
            'wmp_edittheme_fontparagraphs' => 3
        );

        $response = $method->invoke($admin_ajax, $data, $this->allowed_fonts);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], true);
        $this->assertEquals($response['updated'], true);

    }

	/**
	*
	* Calling update fonts with not allowed font returns false and does not generate theme
	*
	*/
	function test_update_fonts_with_not_allowed_fonts_does_not_generate_theme()
	{

        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_fonts'
        );
        $method->setAccessible(true);

        $data = array(
            'wmp_edittheme_fontheadlineswrong' => 2,
            'wmp_edittheme_fontsubtitleswrong' => 3,
            'wmp_edittheme_fontsparagraphswrong' => 4,
        );

        $response = $method->invoke($admin_ajax, $data, $this->allowed_fonts);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], false);
        $this->assertEquals($response['updated'], false);
	}

    /**
     *
     * Calling update color scheme with empty data returns false
     *
     */
    function test_update_color_scheme_without_args_returns_false()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_color_scheme'
        );
        $method->setAccessible(true);

        $response = $method->invoke($admin_ajax, array());
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], false);
        $this->assertEquals($response['updated'], false);
    }


    /**
     *
     * Calling update color scheme with a custom colors value generates theme
     *
     */
    function test_update_color_scheme_with_not_default_value_generates_theme()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_color_scheme'
        );
        $method->setAccessible(true);

        $data = array(
            'wmp_edittheme_colorscheme' => 2
        );

        $response = $method->invoke($admin_ajax, $data);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], true);
        $this->assertEquals($response['updated'], true);
    }

    /**
     *
     * Calling update color scheme with default value does not generate theme
     *
     */
    function test_update_color_scheme_with_default_value_does_not_generate_theme()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_color_scheme'
        );
        $method->setAccessible(true);

        update_option(WMobilePack_Options::$prefix.'color_scheme', 2);

        $data = array(
            'wmp_edittheme_colorscheme' => 1
        );

        $response = $method->invoke($admin_ajax, $data);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], false);
        $this->assertEquals($response['updated'], true);
    }

    /**
     *
     * Calling update fonts with unchanged value does not generate theme and returns false
     *
     */
    function test_update_color_scheme_with_unchanged_value_returns_false()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_color_scheme'
        );
        $method->setAccessible(true);

        update_option(WMobilePack_Options::$prefix.'color_scheme', 2);

        $data = array(
            'wmp_edittheme_colorscheme' => 2
        );

        $response = $method->invoke($admin_ajax, $data);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], true);
        $this->assertEquals($response['updated'], false);

    }


    /**
     *
     * Calling update colors with empty data returns false
     *
     */
    function test_update_colors_without_args_returns_false()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_colors'
        );
        $method->setAccessible(true);

        $response = $method->invoke($admin_ajax, array(), $this->color_vars);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], false);
        $this->assertEquals($response['error'], true);
    }


    /**
     *
     * Calling update colors with values different than default returns true and generates theme
     *
     */
    function test_update_colors_with_not_default_values_generates_theme()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_colors'
        );
        $method->setAccessible(true);

        update_option(WMobilePack_Options::$prefix.'theme', 2);
        update_option(WMobilePack_Options::$prefix.'custom_colors', array());

        $theme_config = WMobilePack_Themes_Config::get_theme_config();
		$colors = $theme_config['presets'][1];

        $data = array();

        foreach ($colors as $key => $color){
            $data['wmp_edittheme_customcolor'.$key] = $color;
        }

        $response = $method->invoke($admin_ajax, $data, $theme_config['vars']);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], true);
        $this->assertEquals($response['error'], false);

    }


    /**
     *
     * Calling update colors with unchanged values does not generate theme and returns false
     *
     */
    function test_update_colors_with_unchanged_values_returns_false()
    {
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->disableOriginalConstructor()
            ->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_colors'
        );
        $method->setAccessible(true);

        $theme_config = WMobilePack_Themes_Config::get_theme_config();
		$colors = $theme_config['presets'][1];

        update_option(WMobilePack_Options::$prefix.'theme', 2);
        update_option(WMobilePack_Options::$prefix.'custom_colors', $colors);

        $data = array();

        foreach ($colors as $key => $color){
            $data['wmp_edittheme_customcolor'.$key] = $color;
        }

        $response = $method->invoke($admin_ajax, $data, $theme_config['vars']);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], false);
        $this->assertEquals($response['error'], false);

    }

	/**
	*
	* Save colors only if all the colors from the theme have been set
	*
	*/
	function test_colors_are_saved_only_if_all_the_colors_from_the_theme_have_been_set()
	{

		$admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
					->disableOriginalConstructor()
					->getMock();

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Admin_Ajax', 'update_theme_colors'
        );

        $method->setAccessible(true);

		$theme_config = WMobilePack_Themes_Config::get_theme_config();
		$colors = $theme_config['presets'][1];

        update_option(WMobilePack_Options::$prefix.'theme', 2);
        update_option(WMobilePack_Options::$prefix.'custom_colors', $colors);

        $data = array();

        foreach ($colors as $key => $color){
            $data['wmp_edittheme_customcolor'.$key] = $color;
        }
		$theme_config['vars'][] = 'extra-color';
		$response = $method->invoke($admin_ajax, $data, $theme_config['vars']);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], false);
        $this->assertEquals($response['error'], true);
	}
}
