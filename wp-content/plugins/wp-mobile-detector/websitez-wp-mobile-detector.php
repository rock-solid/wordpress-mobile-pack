<?php
/*
 Plugin Name: WP Mobile Detector Mobile Plugin
 Plugin URI: http://www.websitez.com/
 Description: The WP Mobile Detector wordpress mobile plugin automatically detects if the visitor is using a standard mobile phone or a smart phone and loads a compatible wordpress mobile theme for each. This mobile plugin is one of the first to differentiate between a standard mobile phone and a smart phone. With advanced mobile statistics, image resizing, automatically formatted content, and detection of 5,000+ mobile phones, the WP Mobile Detector gives your mobile visitors the experience they desire.

 Version: 1.8
 Author: Websitez.com
 Author URI: http://websitez.com
*/

/*
Get the necessary files
*/
require(dirname(__FILE__) . '/functions.php');

global $table_prefix;
//Putting this in for the future
global $websitez_free_version;
$websitez_free_version = true;

global $websitez_mobile_device;
$websitez_mobile_device = array();

/*
Define Globals
*/
define('WEBSITEZ_COOKIE_NAME', 'websitez_mobile_detector_v1-8');
define('WEBSITEZ_PLUGIN_NAME', 'WP Mobile Detector');
define('WEBSITEZ_PLUGIN_AUTHORIZATION', 'wp_mobile_detector_token');
define('WEBSITEZ_PLUGIN_DIR', dirname(__FILE__));
define('WEBSITEZ_PLUGIN_WEB_DIR', plugin_dir_url(__FILE__));
define('WEBSITEZ_BASIC_THEME', 'websitez_basic_theme');
define('WEBSITEZ_ADVANCED_THEME', 'websitez_advanced_theme');
define('WEBSITEZ_INSTALL_BASIC_THEME', 'bluesteel-mobile');
define('WEBSITEZ_INSTALL_ADVANCED_THEME', 'bluesteel-mobile');
define('WEBSITEZ_DEFAULT_THEME', 'twentyten');
define('WEBSITEZ_ADVANCED_MAX_IMAGE_WIDTH', '250');
define('WEBSITEZ_STATS_TABLE', $table_prefix.'websitez_stats');
define('WEBSITEZ_RECORD_STATS_NAME', 'websitez_record_stats');
define('WEBSITEZ_RECORD_STATS', "true");
define('WEBSITEZ_SHOW_ATTRIBUTION_NAME', 'websitez_show_attribution');
define('WEBSITEZ_SHOW_ATTRIBUTION', "false");
define('WEBSITEZ_USE_PREINSTALLED_THEMES', "true");
define('WEBSITEZ_USE_PREINSTALLED_THEMES_NAME', "websitez_preinstalled_themes");
define('WEBSITEZ_BASIC_URL_REDIRECT', 'websitez_basic_url_redirect');
define('WEBSITEZ_ADVANCED_URL_REDIRECT', 'websitez_advanced_url_redirect');

define('WEBSITEZ_SHOW_DASHBOARD_WIDGET', "true");
define('WEBSITEZ_SHOW_DASHBOARD_WIDGET_NAME', "websitez_show_dashboard_widget");

define('WEBSITEZ_SHOW_MOBILE_TO_TABLETS', "true");
define('WEBSITEZ_SHOW_MOBILE_TO_TABLETS_NAME', "websitez_show_mobile_to_tablets");

/* ADs off by default */
define('WEBSITEZ_SHOW_MOBILE_ADS', "false");
define('WEBSITEZ_SHOW_MOBILE_ADS_NAME', "websitez_show_mobile_ads");
define('WEBSITEZ_MONETIZATION_MESSAGE', "websitez_monetization_message");

//Does this plugin come with pre-installed templates?
global $websitez_preinstalled_templates;
$websitez_preinstalled_templates = get_option(WEBSITEZ_USE_PREINSTALLED_THEMES_NAME);

// Install plugin
if(function_exists('register_activation_hook')) {
	register_activation_hook( __FILE__, 'websitez_install' );
}

if(is_admin()) {
	require(dirname(__FILE__) . '/admin/admin-page.php');
	require(dirname(__FILE__) . '/admin/themes.php');
	add_action('admin_menu', 'websitez_configuration_menu');
	//Check to make sure plugin is installed properly
	add_action('init', 'websitez_checkInstalled');
	add_action('init', 'websitez_authorization');
	//add_action('init', 'websitez_check_monetization');
	add_action('wp_dashboard_setup', 'websitez_dashboard_setup' );
}

/*
Lets get this party started.
*/
if(websitez_check_and_act_mobile()){
	if($websitez_preinstalled_templates == "true"){
		require(dirname(__FILE__) . '/default-widgets.php');
		if(!is_admin()){
			add_filter('theme_root', 'websitez_setThemeFolder');
			add_filter('theme_root_uri', 'websitez_setThemeFolderFront');
			add_filter('stylesheet', 'websitez_getTheme');
			add_filter('template', 'websitez_getTheme');
			//If the user creates a dynamic sidebar, make sure to add the proper styling
			add_filter('dynamic_sidebar_params', 'websitez_reclamation_sidebar_params');
			add_action('widgets_init', 'websitez_unregister_default_wp_widgets', 1);
			add_action('send_headers', 'websitez_send_headers');
			add_filter('get_the_generator_xhtml', 'websitez_wordpress_generator');
			add_filter('get_the_generator_html', 'websitez_wordpress_generator');
		}
	}else{
		add_filter('stylesheet', 'websitez_getTheme');
		add_filter('template', 'websitez_getTheme');
		add_action('send_headers', 'websitez_send_headers');
		add_filter('get_the_generator_xhtml', 'websitez_wordpress_generator');
		add_filter('get_the_generator_html', 'websitez_wordpress_generator');
	}
}
?>