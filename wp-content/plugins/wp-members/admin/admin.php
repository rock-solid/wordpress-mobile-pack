<?php
/**
 * WP-Members Admin Functions
 *
 * Functions to manage administration.
 * 
 * This file is part of the WP-Members plugin by Chad Butler
 * You can find out more about this plugin at http://rocketgeek.com
 * Copyright (c) 2006-2014  Chad Butler (email : plugins@butlerblog.com)
 * WP-Members(tm) is a trademark of butlerblog.com
 *
 * @package WordPress
 * @subpackage WP-Members
 * @author Chad Butler
 * @copyright 2006-2014
 */


/** File Includes */
include_once( 'dialogs.php' );
//include_once( 'users.php' );        // currently included in the main plugin file
//include_once( 'user-profile.php' ); // currently included in the main plugin file


/** Actions and Filters */
add_action( 'wpmem_admin_do_tab', 'wpmem_admin_do_tab', 10, 2 );
add_action( 'wp_ajax_wpmem_a_field_reorder', 'wpmem_a_do_field_reorder' );
add_action( 'user_new_form', 'wpmem_admin_add_new_user' );
add_filter( 'plugin_action_links', 'wpmem_admin_plugin_links', 10, 2 );


/**
 * Calls the function to reorder fields
 *
 * @since 2.8.0
 *
 * @uses wpmem_a_field_reorder
 */
function wpmem_a_do_field_reorder(){
	include_once( 'tab-fields.php' );
	wpmem_a_field_reorder();
}


/**
 * filter to add link to settings from plugin panel
 *
 * @since 2.4
 *
 * @param  array  $links
 * @param  string $file
 * @static string $wpmem_plugin
 * @return array  $links
 */
function wpmem_admin_plugin_links( $links, $file )
{
	static $wpmem_plugin;
	if( !$wpmem_plugin ) $wpmem_plugin = plugin_basename( 'wp-members/wp-members.php' );
	if( $file == $wpmem_plugin ) {
		$settings_link = '<a href="options-general.php?page=wpmem-settings">' . __( 'Settings' ) . '</a>';
		$links = array_merge( array( $settings_link ), $links );
	}
	return $links;
}


/**
 * Loads the admin javascript and css files
 *
 * @since 2.5.1
 *
 * @uses wp_enqueue_script
 * @uses wp_enqueue_style
 */
function wpmem_load_admin_js()
{
	// queue up admin ajax and styles 
	wp_enqueue_script( 'wpmem-admin-js',  WPMEM_DIR . '/js/admin.js',   '', WPMEM_VERSION ); 
	wp_enqueue_style ( 'wpmem-admin-css', WPMEM_DIR . '/css/admin.css', '', WPMEM_VERSION );
}


/**
 * Creates the captcha tab
 *
 * @since 2.8
 *
 * @param string $tab
 * @return
 */
function wpmem_a_captcha_tab( $tab ) {
	include_once( 'tab-captcha.php' );
	return ( $tab == 'captcha' ) ? wpmem_a_build_captcha_options() : false ;
}


/**
 * Adds the captcha tab
 *
 * @since 2.8
 *
 * @param  array $tabs The array of tabs for the admin panel
 * @return array The updated array of tabs for the admin panel
 */
function wpmem_add_captcha_tab( $tabs ) {
	return array_merge( $tabs, array( 'captcha' => 'Captcha' ) );
}


/**
 * Primary admin function
 *
 * @since 2.1
 *
 * @uses do_action wpmem_admin_do_tab
 */
function wpmem_admin()
{
	$did_update = ( isset( $_POST['wpmem_admin_a'] ) ) ? wpmem_admin_action( $_POST['wpmem_admin_a'] ) : false;

	$wpmem_settings = get_option( 'wpmembers_settings' );
	if( $wpmem_settings[6] ) {
		add_filter( 'wpmem_admin_tabs', 'wpmem_add_captcha_tab' );
		add_action( 'wpmem_admin_do_tab', 'wpmem_a_captcha_tab', 1, 1 );
	} ?>
	
	<div class="wrap">
		<?php screen_icon( 'options-general' ); ?>
		<!--<h2>WP-Members <?php _e('Settings', 'wp-members'); ?></h2>-->
		<?php 
		$tab = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'options';

		wpmem_admin_tabs( $tab );
		
		wpmem_a_do_warnings( $did_update, $wpmem_settings );

		do_action( 'wpmem_admin_do_tab', $tab, $wpmem_settings );
		?>
	</div><!-- .wrap --><?php
	
	return;
}


/**
 * Displays the content for default tabs
 * 
 * @since 2.8
 *
 * @param string $tab The tab that we are on and displaying
 * @param array  $wpmem_settings The array of plugin settings
 */
function wpmem_admin_do_tab( $tab, $wpmem_settings )
{
	switch ( $tab ) {
	
	case 'options' :
		include_once( 'tab-options.php' );
		wpmem_a_build_options( $wpmem_settings );
		break;
	case 'fields' :
		include_once( 'tab-fields.php' );
		wpmem_a_build_fields();
		break;
	case 'dialogs' :
		include_once( 'tab-dialogs.php' );
		wpmem_a_build_dialogs();
		break;
	case 'emails' :
		include_once( 'tab-emails.php' );
		wpmem_a_build_emails( $wpmem_settings );
		break;
	}
}


/**
 * Assemble the tabs for the admin panel
 *
 * @since 2.8
 *
 * @param string $current The tab that we are on
 */
function wpmem_admin_tabs( $current = 'options' ) 
{
    $tabs = array( 
		'options' => 'WP-Members ' . __( 'Options', 'wp-members' ), 
		'fields'  => __( 'Fields', 'wp-members' ), 
		'dialogs' => __( 'Dialogs', 'wp-members' ), 
		'emails'  => __( 'Emails', 'wp-members' ) 
	);
	
	/**
	 * Filter the admin tabs for the plugin settings page.
	 *
	 * @since 2.8.0
	 *
	 * @param array $tabs An array of the tabs to be displayed on the plugin settings page.
	 */
	$tabs = apply_filters( 'wpmem_admin_tabs', $tabs );
	
    $links = array();
    foreach( $tabs as $tab => $name ) {
	
		$class = ( $tab == $current ) ? 'nav-tab nav-tab-active' : 'nav-tab';
		$links[] = '<a class="' . $class . '" href="?page=wpmem-settings&amp;tab=' . $tab . '">' . $name . '</a>';
    
	}
    
	echo '<h2 class="nav-tab-wrapper">';
    foreach( $links as $link )
        echo $link;
    echo '</h2>';
}


/**
 * Handles the various update actions for the default tabs
 *
 * @since 2.8
 *
 * @param string $action The action that is being done
 */
function wpmem_admin_action( $action )
{
	$did_update = ''; // makes sure $did_update is defined
	switch( $action ) {

	case( 'update_settings' ):
		include_once( 'tab-options.php' );
		$did_update = wpmem_update_options();			
		break;

	case( 'update_fields' ):
	case( 'add_field' ): 
	case( 'edit_field' ):
		include_once( 'tab-fields.php' );
		$did_update = wpmem_update_fields( $action );
		break;
	
	case( 'update_dialogs' ):
		include_once( 'tab-dialogs.php' );
		$did_update = wpmem_update_dialogs();
		break;
	
	case( 'update_emails' ):
		include_once( 'tab-emails.php' );
		$did_update = wpmem_update_emails();
		break;
	
	case( 'update_captcha' ):
		include_once( 'tab-captcha.php' );
		$did_update = wpmem_update_captcha();
		break;
	}
	
	return $did_update;
}


/**
 * Adds WP-Members custom fields to the WP Add New User form
 *
 * @since 2.9.1
 */
function wpmem_admin_add_new_user()
{
	include_once( WPMEM_PATH . '/native-registration.php' );
	echo wpmem_do_wp_newuser_form();
	return;
}

/** End of File **/