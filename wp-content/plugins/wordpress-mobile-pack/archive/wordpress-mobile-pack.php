<?php
/**
 * Plugin Name:  WordPress Mobile Pack
 * Plugin URI:  http://wordpress.org/extend/plugins/wordpress-mobile-pack/
 * Description: <strong>The WordPress Mobile Pack is a complete toolkit to help mobilize your WordPress site and blog.</strong> It includes a <a href='themes.php?page=wpmp_switcher_admin'>mobile switcher</a>, <a href='themes.php?page=wpmp_theme_widget_admin'>filtered widgets</a>, and content adaptation for mobile device characteristics. Activating this plugin will also install a selection of mobile <a href='themes.php?page=wpmp_theme_theme_admin'>themes</a> by <a href='http://ribot.co.uk'>ribot</a>, a top UK mobile design team, and Forum Nokia. These adapt to different families of devices, such as Nokia and WebKit browsers (including Android, iPhone and Palm). If <a href='options-general.php?page=wpmp_mpexo_admin'>enabled</a>, your site will be listed on <a href='http://www.mpexo.com'>mpexo</a>, a directory of mobile-friendly blogs. Also check out <a href='http://wordpress.org/extend/plugins/wordpress-mobile-pack/' target='_blank'>the documentation</a> and <a href='http://www.wordpress.org/tags/wordpress-mo *	bile-pack' target='_blank'>the forums</a>. If you like the plugin, please rate us on the <a href='http://wordpress.org/extend/plugins/wordpress-mobile-pack/'>WordPress directory</a>. And if you don't, let us know how we can improve it!
 * Version: 2.0
 * Author: James Pearce & friends
 * Author URI: http://www.assembla.com/spaces/wordpress-mobile-pack
 * License: The WordPress Mobile Pack is Licensed under the Apache License, Version 2.0
 */
 
 
global $wmobile_pack;
	
if ( !$wmobile_pack ) {
	// Load main configuration information - sets up directories and constants
	require_once( 'core/config.php' );

	// Load mobile detector
	require_once( 'core/mobile-detect.php' );

	// Load main WMobilePack class
	require_once( 'core/class-wmp.php' );

	// Load admin
	//require_once( 'admin/wmp-admin.php' );

	$wmobile_pack = new WMobilePack();
	$wmobile_pack->init();
}
 
 
 
function wordpress_mobile_pack_install(){
    
	add_action('admin_menu', 'register_ie_option');
	
	global $wmobile_pack;
	// activate plugin
	$wmobile_pack->activate();
	
}
 
 
 function wordpress_mobile_pack_uninstall(){
    
	//Uninstall de plugin
	global $wmobile_pack;
	$wmobile_pack->deactivate();
}
 
 
 
 
 
 

function register_ie_option() {
    add_menu_page('IE Option Page', 'IE Option', 'activate_plugins', 'ie-option', 'ie_option_page', '', 76);
    add_submenu_page('ie-option', 'Import', 'Import', 'activate_plugins', 'ie-import-option', 'ie_import_option_page');
    add_submenu_page('ie-option', 'Export', 'Export', 'activate_plugins', 'ie-export-option', 'ie_export_option_page');
}
 
function ie_option_page() {
    // Our stuff here
}
 
function ie_import_option_page() {
    // Content Import Feature
}
 
function ie_export_option_page() {
    // Content Export Feature
}
//add_action('admin_menu', 'register_ie_option');



register_activation_hook(__FILE__,'wordpress_mobile_pack_install');
register_deactivation_hook( __FILE__, 'wordpress_mobile_pack_uninstall' );
