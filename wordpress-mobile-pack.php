<?php
/**
 * Plugin Name:  WordPress Mobile Pack
 * Plugin URI:  http://wordpress.org/plugins/wordpress-mobile-pack/
 * Description: WordPress Mobile Pack 3.0+ is a mobile plugin that helps you transform your WordPress-based content (posts/articles, categories, pages) into a progressive web application. It comes with multiple mobile app themes and extensions that you can purchase individually or as a bundle.
 * Author: WPMobilePack.com
 * Author URI: https://wpmobilepack.com/
 * Version: 3.3
 * Copyright (c) 2009 - 2018 James Pearce, mTLD Top Level Domain Limited, ribot, Forum Nokia, Appticles.com
 * License: The WordPress Mobile Pack is Licensed under the Apache License, Version 2.0
 * Text Domain: wordpress-mobile-pack
 */

require_once('core/config.php');
require_once('core/class-wmp.php');

/**
 * Used to load the required files on the plugins_loaded hook, instead of immediately.
 */
function wmobilepack_frontend_init() {

    require_once('frontend/class-application.php');
    new WMobilePack_Application();
}

function wmobilepack_admin_init() {

    require_once('admin/class-admin-init.php');
    new WMobilePack_Admin_Init();
}

if (class_exists( 'WMobilePack' ) && class_exists( 'WMobilePack' )) {

    global $wmobile_pack;
    $wmobile_pack = new WMobilePack();

    // Add hooks for activating & deactivating the plugin
    register_activation_hook(__FILE__, array(&$wmobile_pack, 'activate'));
    register_deactivation_hook(__FILE__, array(&$wmobile_pack, 'deactivate'));

    // Initialize the Wordpress Mobile Pack check logic and rendering
    if (is_admin()) {

        if (defined('DOING_AJAX') && DOING_AJAX) {

            require_once(WMP_PLUGIN_PATH . 'admin/class-admin-ajax.php');

            $wmobile_pack_ajax = new WMobilePack_Admin_Ajax();

			add_action('wp_ajax_wmp_theme_switch', array( &$wmobile_pack_ajax, 'theme_switch' ) );
            add_action('wp_ajax_wmp_theme_settings', array(&$wmobile_pack_ajax, 'theme_settings'));
			add_action('wp_ajax_wmp_theme_editimages', array(&$wmobile_pack_ajax, 'theme_editimages'));

            add_action('wp_ajax_wmp_content_status', array(&$wmobile_pack_ajax, 'content_status'));
            add_action('wp_ajax_wmp_content_pagedetails', array(&$wmobile_pack_ajax, 'content_pagedetails'));
            add_action('wp_ajax_wmp_content_order', array(&$wmobile_pack_ajax, 'content_order'));

            add_action('wp_ajax_wmp_settings_save', array(&$wmobile_pack_ajax, 'settings_save'));
            add_action('wp_ajax_wmp_settings_app', array(&$wmobile_pack_ajax, 'settings_app'));

            add_action('wp_ajax_wmp_join_waitlist', array(&$wmobile_pack_ajax, 'settings_waitlist'));
            add_action('wp_ajax_wmp_send_feedback', array(&$wmobile_pack_ajax, 'send_feedback'));

        } else {
            add_action('plugins_loaded', 'wmobilepack_admin_init');
        }

    } else {
        add_action('plugins_loaded', 'wmobilepack_frontend_init');
    }

}
