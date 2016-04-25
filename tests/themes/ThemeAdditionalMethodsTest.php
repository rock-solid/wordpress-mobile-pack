<?php

require_once(WMP_PLUGIN_PATH.'admin/class-admin-ajax.php');
require_once(WMP_PLUGIN_PATH.'inc/class-wmp-options.php');

/**
 * Testing ajax theme additional methods functionality
 *
 * @group      ajax
 */
class ThemeAdditionalMethodsTest extends WP_UnitTestCase {


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

        $response = $method->invoke($admin_ajax, array());
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
            'wmp_edittheme_fontheadlines' => 1,
            'wmp_edittheme_fontsubtitles' => 2,
            'wmp_edittheme_fontparagraphs' => 3
        );

        $response = $method->invoke($admin_ajax, $data);
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
            'wmp_edittheme_fontheadlines' => 3,
            'wmp_edittheme_fontsubtitles' => 3,
            'wmp_edittheme_fontparagraphs' => 3
        );

        $response = $method->invoke($admin_ajax, $data);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], false);
        $this->assertEquals($response['updated'], true);

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

        $response = $method->invoke($admin_ajax, $data);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], true);
        $this->assertEquals($response['updated'], true);

    }

    /**
     *
     * Calling update fonts with unchanged values doesn't update options, but marks the theme as recompiled
     *
     */
    function test_update_fonts_with_unchanged_custom_values_generates_theme()
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

        $response = $method->invoke($admin_ajax, $data);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], true);
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
            'wmp_edittheme_colorscheme' => 0
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
            'wmp_edittheme_colorscheme' => 3
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
        $this->assertEquals($response['scss'], false);
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

        $response = $method->invoke($admin_ajax, array());
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

        update_option(WMobilePack_Options::$prefix.'theme', 1);
        update_option(WMobilePack_Options::$prefix.'custom_colors', array());

        $colors = WMobilePack_Themes_Config::$color_schemes[1]['presets'][1];

        $data = array();

        foreach ($colors as $key => $color){
            $data['wmp_edittheme_customcolor'.$key] = $color;
        }

        $response = $method->invoke($admin_ajax, $data);
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

        $colors = WMobilePack_Themes_Config::$color_schemes[1]['presets'][1];

        update_option(WMobilePack_Options::$prefix.'theme', 1);
        update_option(WMobilePack_Options::$prefix.'custom_colors', $colors);

        $data = array();

        foreach ($colors as $key => $color){
            $data['wmp_edittheme_customcolor'.$key] = $color;
        }

        $response = $method->invoke($admin_ajax, $data);
        $this->assertInternalType('array', $response);
        $this->assertEquals($response['scss'], false);
        $this->assertEquals($response['error'], false);

    }
}