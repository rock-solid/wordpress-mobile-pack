<?php
/**
 * Plugin Name:  WordPress Mobile Pack
 * Plugin URI:  http://wordpress.org/plugins/wordpress-mobile-pack/
 * Description: With WordPress Mobile Pack,  you can easily build your own stylish, cross-platform mobile web application out of your existing content.
 * Version: 2.0
 * Copyright (c) 2009 – 2014 James Pearce, mTLD Top Level Domain Limited, ribot, Forum Nokia, Appticles.com
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

    // Initialize the MobilePress check logic and rendering
    $wmobile_pack->wmp_check_load();
        
	if (is_admin()) {
		
		//setcookie("wmp_theme_mode", 'desktop', time()+3600*30*24,'/');
		//setcookie("wmp_load_app", 1, time()+3600*30*24,'/');
		
		$wmobile_pack->wmp_admin_init();
		
		add_action( 'wp_ajax_wmp_content_save', array( &$wmobile_pack_admin, 'wmp_content_save' ) );
        add_action( 'wp_ajax_wmp_settings_editimages', array( &$wmobile_pack_admin, 'wmp_settings_editimages' ) );
        add_action( 'wp_ajax_wmp_settings_save', array( &$wmobile_pack_admin, 'wmp_settings_save' ) );
		add_action( 'wp_ajax_wmp_send_feedback', array( &$wmobile_pack_admin, 'wmp_send_feedback' ) );
        
	}
    
} 
