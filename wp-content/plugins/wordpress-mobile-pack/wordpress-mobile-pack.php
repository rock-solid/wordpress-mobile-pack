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
 
require_once('core/config.php');
require_once('core/class-wmp.php');
require_once('core/class-admin.php');

if ( class_exists( 'WMobilePack' ) && class_exists( 'WMobilePackAdmin' ) ) {
	
	global $wmobile_pack; 
	$wmobile_pack = new WMobilePack();
    $wmobile_pack_admin = new WMobilePackAdmin();

	// add hooks
	register_activation_hook( __FILE__, array( &$wmobile_pack, 'wmp_install' ) );
	register_deactivation_hook( __FILE__, array( &$wmobile_pack, 'wmp_uninstall' ) );

	if (is_admin()) {
		
		$wmobile_pack->wmp_admin_init();
		
		// Initialize the MobilePress check logic and rendering
		$wmobile_pack->wmp_check_load();
        
        add_action( 'wp_ajax_wmp_content_save', array( &$wmobile_pack_admin, 'wmp_content_save' ) );
        add_action( 'wp_ajax_wmp_settings_editimages', array( &$wmobile_pack_admin, 'wmp_settings_editimages' ) );
		add_action( 'wp_ajax_wmp_send_feedback', array( &$wmobile_pack_admin, 'wmp_send_feedback' ) );
	}
	
	// Initialize the MobilePress check logic and rendering
	//	$wmobile_pack->wmp_check_load();

    
    
} 
