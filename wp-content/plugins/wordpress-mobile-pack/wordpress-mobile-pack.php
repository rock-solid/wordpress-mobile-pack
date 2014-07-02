<?php
/**
 * Plugin Name:  WordPress Mobile Pack
 * Plugin URI:  http://wordpress.org/plugins/wordpress-mobile-pack/
 * Description: WordPress Mobile Pack 2.0 has been completely rebuilt from the ground up and repurposed to empower bloggers, publishers and other content creators to go beyond responsiveness and 'appify' the content of their blog.
 * Author: Appticles.com
 * Author URI: http://www.appticles.com/
 * Version: 2.0.1
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

    // Initialize the Wordpress Mobile Pack check logic and rendering
    add_action('plugins_loaded',array( &$wmobile_pack, 'wmp_check_load' ));
        
	if (is_admin()) {
		
		$wmobile_pack->wmp_admin_init();
		
		add_action( 'wp_ajax_wmp_content_save', array( &$wmobile_pack_admin, 'wmp_content_save' ) );
        add_action( 'wp_ajax_wmp_settings_editimages', array( &$wmobile_pack_admin, 'wmp_settings_editimages' ) );
        add_action( 'wp_ajax_wmp_settings_save', array( &$wmobile_pack_admin, 'wmp_settings_save' ) );
		add_action( 'wp_ajax_wmp_send_feedback', array( &$wmobile_pack_admin, 'wmp_send_feedback' ) );
        
	}
    
} 
