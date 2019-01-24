<?php

require_once(PWA_PLUGIN_PATH.'admin/class-admin-ajax.php');
require_once(PWA_PLUGIN_PATH.'inc/class-wmp-options.php');

/**
 * Testing ajax theme settings functionality
 *
 * @group      ajax
 *
 */
class ThemeSettingsTest extends WP_Ajax_UnitTestCase
{

    function setUp()
    {
        parent::setUp();

        // Become an administrator
        $this->_setRole( 'administrator' );
        update_option('wmpack_theme', 2);

    }

    /**
     *
     * Mock a theme manager object
     */

    /**
     * Mock a theme manager object
     *
     * @param $compiled_response = Value returned by the compile_css_file method
     * @param $remove_css_response = Value returned by the remove_css_file method
     *
     * @return object
     */
    function mock_theme_manager($compiled_response = null, $remove_css_response = null)
    {
        $theme_manager = $this->getMockBuilder('WMobilePack_Themes_Compiler')
            ->setMethods(array('compile_css_file', 'remove_css_file'))
            ->disableOriginalConstructor()
            ->getMock();

        if ($compiled_response === null){
            $theme_manager->expects($this->never())
                ->method('compile_css_file');

        } else {
            $theme_manager->expects($this->once())
                ->method('compile_css_file')
                ->will($this->returnValue($compiled_response));
        }

        if ($remove_css_response === null){

            $theme_manager->expects($this->never())
                ->method('remove_css_file');

        } else {
            $theme_manager->expects($this->once())
                ->method('remove_css_file')
                ->will($this->returnValue($remove_css_response));
        }

        return $theme_manager;
    }

    /**
     *
     * Calling update theme with empty data returns status zero
     *
     */
    function test_settings_without_args_returns_status_zero()
    {

        // Add hook for the ajax method
        $wmobile_pack_admin_ajax = new WMobilePack_Admin_Ajax();
        add_action('wp_ajax_wmp_theme_settings', array( &$wmobile_pack_admin_ajax, 'theme_settings' ) );

        // Make the request
        try {
            $this->_handleAjax( 'wmp_theme_settings' );
        } catch ( WPAjaxDieContinueException $e ) {
            unset( $e );
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(0, $response['status']);
        $this->assertEquals(array(), $response['messages']);
    }

    /**
     *
     * Calling update theme with invalid color scheme returns status zero
     *
     */
    function test_settings_with_invalid_color_scheme_returns_status_zero()
    {

        // Add hook for the ajax method
        $wmobile_pack_admin_ajax = new WMobilePack_Admin_Ajax();
        add_action('wp_ajax_wmp_theme_settings', array( &$wmobile_pack_admin_ajax, 'theme_settings' ) );

        $_POST['wmp_edittheme_colorscheme'] = "invalidcolorscheme";
        $_POST['wmp_edittheme_fontheadlines'] = "1";
        $_POST['wmp_edittheme_fontsubtitles'] = "1";
        $_POST['wmp_edittheme_fontparagraphs'] = "1";

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(0, $response['status']);
		$this->assertEquals('Please select all colors before saving the custom color scheme!', $response['messages'][0]);

    }

    /**
     *
     * Calling update theme with invalid font headlines returns status zero
     *
     */
    function test_settings_with_invalid_font_headlines_returns_status_zero()
    {

        // Add hook for the ajax method
        $wmobile_pack_admin_ajax = new WMobilePack_Admin_Ajax();
        add_action('wp_ajax_wmp_theme_settings', array( &$wmobile_pack_admin_ajax, 'theme_settings' ) );

        $_POST['wmp_edittheme_colorscheme'] = "1";
        $_POST['wmp_edittheme_fontheadlines'] = "invalidfont";
        $_POST['wmp_edittheme_fontsubtitles'] = "1";
        $_POST['wmp_edittheme_fontparagraphs'] = "1";

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(0, $response['status']);
        $this->assertEquals(array(), $response['messages']);
    }

    /**
     *
     * Calling update theme with invalid font subtitles returns status zero
     * @todo Remove this test if the font_subtitles option is removed.
     */
    // function test_settings_with_invalid_font_subtitles_returns_status_zero()
    // {

    //     // Add hook for the ajax method
    //     $wmobile_pack_admin_ajax = new WMobilePack_Admin_Ajax();
    //     add_action('wp_ajax_wmp_theme_settings', array( &$wmobile_pack_admin_ajax, 'theme_settings' ) );

    //     $_POST['wmp_edittheme_colorscheme'] = "1";
    //     $_POST['wmp_edittheme_fontheadlines'] = "1";
    //     $_POST['wmp_edittheme_fontsubtitles'] = "invalidfont";
    //     $_POST['wmp_edittheme_fontparagraphs'] = "1";

    //     // Make the request
    //     try {
    //         $this->_handleAjax('wmp_theme_settings');
    //     } catch (WPAjaxDieContinueException $e) {
    //         unset($e);
    //     }

    //     $response = json_decode($this->_last_response, true);

    //     $this->assertInternalType('array', $response);
    //     $this->assertArrayHasKey('status', $response);
    //     $this->assertArrayHasKey('messages', $response);
    //     $this->assertEquals(0, $response['status']);
    //     $this->assertEquals(array(), $response['messages']);
    // }

    /**
     *
     * Calling update theme with invalid font paragraphs returns status zero
     *
     */
    function test_settings_with_invalid_font_paragraphs_returns_status_zero()
    {

        // Add hook for the ajax method
        $wmobile_pack_admin_ajax = new WMobilePack_Admin_Ajax();
        add_action('wp_ajax_wmp_theme_settings', array( &$wmobile_pack_admin_ajax, 'theme_settings' ) );

        $_POST['wmp_edittheme_colorscheme'] = "1";
        $_POST['wmp_edittheme_fontheadlines'] = "1";
        $_POST['wmp_edittheme_fontsubtitles'] = "1";
        $_POST['wmp_edittheme_fontparagraphs'] = "100";

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(0, $response['status']);
        $this->assertEquals(array(), $response['messages']);
    }

    /**
     *
     * Calling update theme with invalid colors and color scheme = 0 returns error message
     *
     */
    function test_settings_with_invalid_colors_returns_error_message()
    {

        // Mock admin ajax class
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->setMethods(array('update_theme_colors'))
            ->getMock();

        $admin_ajax->expects($this->once())
            ->method('update_theme_colors')
            ->will($this->returnValue(array('scss' => false, 'error' => true)));

        // Add hook for the ajax method
        add_action('wp_ajax_wmp_theme_settings', array( &$admin_ajax, 'theme_settings' ) );

        $_POST['wmp_edittheme_colorscheme'] = "0";
        $_POST['wmp_edittheme_fontheadlines'] = "1";
        $_POST['wmp_edittheme_fontsubtitles'] = "1";
        $_POST['wmp_edittheme_fontparagraphs'] = "1";

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(0, $response['status']);
        $this->assertEquals(array("Please select all colors before saving the custom color scheme!"), $response['messages']);
    }

    /**
     *
     * Calling update theme with valid colors tries to compile the theme but fails
     *
     */
    function test_settings_with_valid_colors_tries_to_compile_theme_but_fails()
    {

        // Mock admin ajax class
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->setMethods(array('update_theme_colors', 'get_theme_manager'))
            ->getMock();

        $admin_ajax->expects($this->once())
            ->method('update_theme_colors')
            ->will($this->returnValue(array('scss' => true, 'error' => false)));

        // Mock theme manager class
        $theme_manager = $this->mock_theme_manager(
            array('compiled' => false, 'error' => 'Error compiling theme'),
            null
        );

        $admin_ajax->expects($this->once())
            ->method('get_theme_manager')
            ->will($this->returnValue($theme_manager));

        // Add hook for the ajax method
        add_action('wp_ajax_wmp_theme_settings', array( &$admin_ajax, 'theme_settings' ) );

        $_POST['wmp_edittheme_colorscheme'] = "0";
        $_POST['wmp_edittheme_fontheadlines'] = "1";
        $_POST['wmp_edittheme_fontsubtitles'] = "1";
        $_POST['wmp_edittheme_fontparagraphs'] = "1";

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(0, $response['status']);
        $this->assertEquals(array("Error compiling theme"), $response['messages']);
    }


    /**
     *
     * Calling update theme with valid colors compiles the theme
     *
     */
    function test_settings_with_valid_colors_compiles_theme()
    {

        // Mock admin ajax class
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->setMethods(array('update_theme_colors', 'get_theme_manager'))
            ->getMock();

        $admin_ajax->expects($this->once())
            ->method('update_theme_colors')
            ->will($this->returnValue(array('scss' => true, 'error' => false)));

        // Mock theme manager class
        $theme_manager = $this->mock_theme_manager(
            array('compiled' => true, 'error' => false),
            null
        );

        $admin_ajax->expects($this->once())
            ->method('get_theme_manager')
            ->will($this->returnValue($theme_manager));

        // Add hook for the ajax method
        add_action('wp_ajax_wmp_theme_settings', array( &$admin_ajax, 'theme_settings' ) );

        $_POST['wmp_edittheme_colorscheme'] = "0";
        $_POST['wmp_edittheme_fontheadlines'] = "1";
        $_POST['wmp_edittheme_fontsubtitles'] = "1";
        $_POST['wmp_edittheme_fontparagraphs'] = "1";

        // Assume we don't have a previous theme that must be deleted
        update_option(WMobilePack_Options::$prefix.'theme_timestamp', '');

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(1, $response['status']);
        $this->assertEquals(array(), $response['messages']);
    }

    /**
     *
     * Calling update theme with valid colors compiles the theme and removes previous css file
     *
     */
    function test_settings_with_valid_colors_compiles_theme_and_removes_old_css()
    {

        // Mock admin ajax class
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->setMethods(array('update_theme_colors', 'get_theme_manager'))
            ->getMock();

        $admin_ajax->expects($this->once())
            ->method('update_theme_colors')
            ->will($this->returnValue(array('scss' => true, 'error' => false)));

        // Mock theme manager class
        $theme_manager = $this->mock_theme_manager(
            array('compiled' => true, 'error' => false),
            '123456'
        );

        $admin_ajax->expects($this->once())
            ->method('get_theme_manager')
            ->will($this->returnValue($theme_manager));

        // Add hook for the ajax method
        add_action('wp_ajax_wmp_theme_settings', array( &$admin_ajax, 'theme_settings' ) );

        $_POST['wmp_edittheme_colorscheme'] = "0";
        $_POST['wmp_edittheme_fontheadlines'] = "1";
        $_POST['wmp_edittheme_fontsubtitles'] = "1";
        $_POST['wmp_edittheme_fontparagraphs'] = "1";

        // Assume we have a previous theme that must be deleted
        update_option(WMobilePack_Options::$prefix.'theme_timestamp', '123456');

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(1, $response['status']);
        $this->assertEquals(array(), $response['messages']);
    }


    /**
     *
     * Calling update theme with default values for fonts and color scheme removes previous theme
     *
     */
    function test_settings_with_default_values_removes_custom_css()
    {

        // Mock admin ajax class
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->setMethods(array('update_theme_colors', 'remove_custom_theme', 'get_theme_manager'))
            ->getMock();

        $admin_ajax->expects($this->never())
            ->method('update_theme_colors');

        $admin_ajax->expects($this->once())
            ->method('get_theme_manager');

        $admin_ajax->expects($this->once())
            ->method('remove_custom_theme');

        // Add hook for the ajax method
        add_action('wp_ajax_wmp_theme_settings', array( &$admin_ajax, 'theme_settings' ) );

        $_POST['wmp_edittheme_colorscheme'] = "1";
        $_POST['wmp_edittheme_fontheadlines'] = "1";
        $_POST['wmp_edittheme_fontsubtitles'] = "1";
        $_POST['wmp_edittheme_fontparagraphs'] = "1";

        // Assume we have a previous theme that must be deleted
        update_option(WMobilePack_Options::$prefix.'theme_timestamp', '123456');

        // Assume we had another color scheme
        update_option(WMobilePack_Options::$prefix.'color_scheme', '2');

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(1, $response['status']);
        $this->assertEquals(array(), $response['messages']);
	}

	/**
     *
     * Calling update theme with a color scheme that is not the default one compiles the theme and removes previous css file
     *
     */
    function test_settings_with_not_default_color_scheme_compiles_theme_and_removes_old_css()
    {

        // Mock admin ajax class
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->setMethods(array('update_theme_color_scheme', 'get_theme_manager'))
            ->getMock();

        $admin_ajax->expects($this->once())
            ->method('update_theme_color_scheme')
            ->will($this->returnValue(array('scss' => true, 'updated' => true)));

        // Mock theme manager class
        $theme_manager = $this->mock_theme_manager(
            array('compiled' => true, 'error' => false),
            '123456'
        );

        $admin_ajax->expects($this->once())
            ->method('get_theme_manager')
            ->will($this->returnValue($theme_manager));

        // Add hook for the ajax method
        add_action('wp_ajax_wmp_theme_settings', array( &$admin_ajax, 'theme_settings' ) );

        $_POST['wmp_edittheme_colorscheme'] = "2";
        $_POST['wmp_edittheme_fontheadlines'] = "1";
        $_POST['wmp_edittheme_fontsubtitles'] = "1";
        $_POST['wmp_edittheme_fontparagraphs'] = "1";

        // Assume we have a previous theme that must be deleted
        update_option(WMobilePack_Options::$prefix.'theme_timestamp', '123456');

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(1, $response['status']);
        $this->assertEquals(array(), $response['messages']);
    }

    /**
     *
     * Calling update theme with a font that is not the default one compiles the theme and removes previous css file
     *
     */
    function test_settings_with_not_default_font_compiles_theme_and_removes_old_css()
    {

        // Mock admin ajax class
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->setMethods(array('update_theme_fonts', 'get_theme_manager'))
            ->getMock();

        $admin_ajax->expects($this->once())
            ->method('update_theme_fonts')
            ->will($this->returnValue(array('scss' => true, 'updated' => true)));

        // Mock theme manager class
        $theme_manager = $this->mock_theme_manager(
            array('compiled' => true, 'error' => false),
            '123456'
        );

        $admin_ajax->expects($this->once())
            ->method('get_theme_manager')
            ->will($this->returnValue($theme_manager));

        // Add hook for the ajax method
        add_action('wp_ajax_wmp_theme_settings', array( &$admin_ajax, 'theme_settings' ) );

        $_POST['wmp_edittheme_colorscheme'] = "1";
        $_POST['wmp_edittheme_fontheadlines'] = "1";
        $_POST['wmp_edittheme_fontsubtitles'] = "3";
        $_POST['wmp_edittheme_fontparagraphs'] = "1";

        // Assume we have a previous theme that must be deleted
        update_option(WMobilePack_Options::$prefix.'theme_timestamp', '123456');

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(1, $response['status']);
        $this->assertEquals(array(), $response['messages']);
    }

    /**
     *
     * Calling update theme with unchanged settings returns error message
     *
     */
    function test_settings_with_unchanged_settings_returns_error()
    {

        // Mock admin ajax class
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->setMethods(array('get_theme_manager'))
            ->getMock();

        $admin_ajax->expects($this->once())
            ->method('get_theme_manager');

        // Add hook for the ajax method
        add_action('wp_ajax_wmp_theme_settings', array( &$admin_ajax, 'theme_settings' ) );

        // Set previous options
        update_option(WMobilePack_Options::$prefix.'color_scheme', '2');
        update_option(WMobilePack_Options::$prefix.'font_headlines', '3');
        update_option(WMobilePack_Options::$prefix.'font_subtitles', '4');
        update_option(WMobilePack_Options::$prefix.'font_paragraphs', '5');
        update_option(WMobilePack_Options::$prefix.'theme_timestamp', '123456');

        $_POST['wmp_edittheme_colorscheme'] = "2";
        $_POST['wmp_edittheme_fontheadlines'] = "3";
        $_POST['wmp_edittheme_fontsubtitles'] = "4";
        $_POST['wmp_edittheme_fontparagraphs'] = "5";


        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(0, $response['status']);
        $this->assertEquals(array('Your application\'s settings have not changed!'), $response['messages']);
    }


    /**
     *
     * Calling update theme with a default font recompiles the SCSS for a non-default color scheme
     *
     */
    function test_settings_with_default_font_compiles_theme_for_non_default_color_scheme()
    {

        // Mock admin ajax class
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->setMethods(array('get_theme_manager'))
            ->getMock();

        // Mock theme manager class
        $theme_manager = $this->mock_theme_manager(
            array('compiled' => true, 'error' => false),
            '123456'
        );

        $admin_ajax->expects($this->once())
            ->method('get_theme_manager')
            ->will($this->returnValue($theme_manager));

        // Add hook for the ajax method
        add_action('wp_ajax_wmp_theme_settings', array( &$admin_ajax, 'theme_settings' ) );

        // Revert to default font variables, but keep a non-default value for the color scheme
        $_POST['wmp_edittheme_colorscheme'] = "2";
        $_POST['wmp_edittheme_fontheadlines'] = "1";
        $_POST['wmp_edittheme_fontsubtitles'] = "1";
        $_POST['wmp_edittheme_fontparagraphs'] = "1";

        // Assume that the color scheme has not changed
        update_option(WMobilePack_Options::$prefix.'color_scheme', '2');

        // Assume that the headlines font was set to a custom value
        update_option(WMobilePack_Options::$prefix.'font_headlines', '2');

        // The other font variables have default value
        update_option(WMobilePack_Options::$prefix.'font_subtitles', '1');
        update_option(WMobilePack_Options::$prefix.'font_paragraphs', '1');

        // Assume we have a previous theme
        update_option(WMobilePack_Options::$prefix.'theme_timestamp', '123456');

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(1, $response['status']);
        $this->assertEquals(array(), $response['messages']);
    }


    /**
     *
     * Calling update theme with a default color scheme recompiles the SCSS for a non-default font
     *
     */
    function test_settings_with_default_color_scheme_compiles_theme_for_non_default_font()
    {

        // Mock admin ajax class
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->setMethods(array('get_theme_manager'))
            ->getMock();

        // Mock theme manager class
        $theme_manager = $this->mock_theme_manager(
            array('compiled' => true, 'error' => false),
            '123456'
        );

        $admin_ajax->expects($this->once())
            ->method('get_theme_manager')
            ->will($this->returnValue($theme_manager));

        // Add hook for the ajax method
        add_action('wp_ajax_wmp_theme_settings', array( &$admin_ajax, 'theme_settings' ) );

        // Change font headlines, keep other settings unchanged
        $_POST['wmp_edittheme_colorscheme'] = "1";
        $_POST['wmp_edittheme_fontheadlines'] = "2";
        $_POST['wmp_edittheme_fontsubtitles'] = "1";
        $_POST['wmp_edittheme_fontparagraphs'] = "1";

        // Assume that the color scheme has not changed
        update_option(WMobilePack_Options::$prefix.'color_scheme', '2');

        // Assume that the headlines font was set to a custom value
        update_option(WMobilePack_Options::$prefix.'font_headlines', '2');

        // The other font variables have default value
        update_option(WMobilePack_Options::$prefix.'font_subtitles', '1');
        update_option(WMobilePack_Options::$prefix.'font_paragraphs', '1');

        // Assume we have a previous theme
        update_option(WMobilePack_Options::$prefix.'theme_timestamp', '123456');

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(1, $response['status']);
        $this->assertEquals(array(), $response['messages']);
    }


    /**
     *
     * Calling update theme with a default color scheme recompiles the SCSS for a non-default font size
     *
     */
    function test_settings_with_default_color_scheme_compiles_theme_for_non_default_font_size()
    {

        // Mock admin ajax class
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->setMethods(array('get_theme_manager'))
            ->getMock();

        // Mock theme manager class
        $theme_manager = $this->mock_theme_manager(
            array('compiled' => true, 'error' => false),
            '123456'
        );

        $admin_ajax->expects($this->once())
            ->method('get_theme_manager')
            ->will($this->returnValue($theme_manager));

        // Add hook for the ajax method
        add_action('wp_ajax_wmp_theme_settings', array( &$admin_ajax, 'theme_settings' ) );

        // Revert to default variables, but keep a non-default value for the font headlines
        $_POST['wmp_edittheme_colorscheme'] = "1";
        $_POST['wmp_edittheme_fontheadlines'] = "2";
        $_POST['wmp_edittheme_fontsubtitles'] = "1";
        $_POST['wmp_edittheme_fontparagraphs'] = "1";

        // Assume that the color scheme has changed
        update_option(WMobilePack_Options::$prefix.'color_scheme', '2');

        // The other font variables have custom (unchanged) values
        update_option(WMobilePack_Options::$prefix.'font_headlines', '1');
        update_option(WMobilePack_Options::$prefix.'font_subtitles', '1');
        update_option(WMobilePack_Options::$prefix.'font_paragraphs', '1');

        // Assume we have a previous theme
        update_option(WMobilePack_Options::$prefix.'theme_timestamp', '123456');

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(1, $response['status']);
        $this->assertEquals(array(), $response['messages']);
    }


    /**
     *
     * Calling update theme without a valid theme manager returns error
     * Obs.: The theme manager will not be loaded if the PHP version is lower than 5.3.
     *
     */
    function test_settings_with_nonexistent_theme_manager_returns_error()
    {

        // Mock admin ajax class
        $admin_ajax = $this->getMockBuilder('WMobilePack_Admin_Ajax')
            ->setMethods(array('get_theme_manager'))
            ->getMock();

        $admin_ajax->expects($this->once())
            ->method('get_theme_manager')
            ->will($this->returnValue(false));

        // Add hook for the ajax method
        add_action('wp_ajax_wmp_theme_settings', array( &$admin_ajax, 'theme_settings' ) );

        // Set previous options
        update_option(WMobilePack_Options::$prefix.'color_scheme', '2');
        update_option(WMobilePack_Options::$prefix.'font_headlines', '3');
        update_option(WMobilePack_Options::$prefix.'font_subtitles', '4');
        update_option(WMobilePack_Options::$prefix.'font_paragraphs', '5');
        update_option(WMobilePack_Options::$prefix.'theme_timestamp', '123456');

        $_POST['wmp_edittheme_colorscheme'] = "2";
        $_POST['wmp_edittheme_fontheadlines'] = "3";
        $_POST['wmp_edittheme_fontsubtitles'] = "4";
        $_POST['wmp_edittheme_fontparagraphs'] = "5";

        // Make the request
        try {
            $this->_handleAjax('wmp_theme_settings');
        } catch (WPAjaxDieContinueException $e) {
            unset($e);
        }

        $response = json_decode($this->_last_response, true);

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('messages', $response);
        $this->assertEquals(0, $response['status']);
        $this->assertEquals(array('Unable to load theme compiler. Please check your PHP version, should be at least 5.3.'), $response['messages']);
    }
}
