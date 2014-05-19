<?php
/**
 * @package Test Touch
 */
/*
Plugin Name:Test Touch
Plugin URI: http://www.wmobile_pack.com
Description: Present your Wordpress site in a beautiful theme optimized for touch-based smartphones
Version: 1.0.4
Author: Test Touch
Author URI: http://www.wmobile_pack.com/mobile
License: GPLv2 or later
*/

define("TESTTOUCH_VERSION", '1.0.4');
define("TESTTOUCH_BASE_THEME", dirname(__FILE__) . '/themes/base');

require_once('admin/admin.php');
require_once('includes/defaults.php');


if ( ! class_exists( 'TestTouch' ) ) :

	require_once('core/tt-class.php');
	global $wmobile_pack; 
	$wmobile_pack = new TestTouch();

endif; // if ( ! class_exists( 'TestTouch' ) ) :



function wmp_admin_menu() {
	global $wmobile_pack; 
	
	add_menu_page( 'WP Mobile Pack', 'WP Mobile Pack', 'manage_options', 'wmp-option', 'wmp_admin_main', $wmobile_pack->wmp_plugin_admin_uri() . '/images/wmobile_pack_icon.png', 61 );
	add_submenu_page('wmp-option', "What's New", "What's New", 'manage_options', 'wmp-option');
	add_submenu_page('wmp-option', "Look & Feel", "Look & Feel", 'manage_options', 'wmp-option');
	add_submenu_page('wmp-option', "Content", "Content", 'manage_options', 'wmp-option');
	add_submenu_page('wmp-option', "Settings", "Settings", 'manage_options', 'wmp-option');
	add_submenu_page('wmp-option', "Upgrade", "Upgrade", 'manage_options', 'wmp-option');
}

function wmp_get_option( $option_name, $option_key = false ) {
	
	global $wmp_default_options;

	if ( get_option( 'wmobile_pack_' . $option_name ) == '' )
		$wmp_option = $wmp_default_options[$option_name];
	else
		$wmp_option = get_option( 'wmobile_pack_' . $option_name );
		
	if( $option_key )
		return $wmp_option[$option_key];
	else
		return $wmp_option;
}

function wmp_add_option( $option_name, $option_value ) {
	
	global $wmp_default_options;
	
	if ( array_key_exists( $option_name , $wmp_default_options ) )
		return add_option( 'wmobile_pack_' . $option_name, $option_value );
}

function wmp_update_option( $option_name, $option_value ) {
	
	global $wmp_default_options;
	
	if ( array_key_exists( $option_name , $wmp_default_options ) )
		return update_option( 'wmobile_pack_' . $option_name, $option_value );
}

function wmp_delete_option( $option_name ) {
	
	global $wmp_default_options;
	
	if ( array_key_exists( $option_name , $wmp_default_options ) )
		return delete_option( 'wmobile_pack_' . $option_name );
}


register_activation_hook( __FILE__, array( &$wmobile_pack, 'wmp_install' ) );
add_action( 'admin_menu',  'wmp_admin_menu' );
add_action( 'admin_init', array( &$wmobile_pack, 'wmp_admin_init' ) );