<?php
/*
Plugin Name: WP-Members
Plugin URI:  http://rocketgeek.com
Description: WP access restriction and user registration.  For more information on plugin features, refer to <a href="http://rocketgeek.com/plugins/wp-members/users-guide/">the online Users Guide</a>. A <a href="http://rocketgeek.com/plugins/wp-members/quick-start-guide/">Quick Start Guide</a> is also available. WP-Members(tm) is a trademark of butlerblog.com.
Version:     2.9.6
Author:      Chad Butler
Author URI:  http://butlerblog.com/
License:     GPLv2
*/


/*  
	Copyright (c) 2006-2014  Chad Butler

	The name WP-Members(tm) is a trademark of butlerblog.com

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

	You may also view the license here:
	http://www.gnu.org/licenses/gpl.html
*/


/*
	A NOTE ABOUT LICENSE:

	While this plugin is freely available and open-source under the GPL2
	license, that does not mean it is "public domain." You are free to modify
	and redistribute as long as you comply with the license. Any derivative 
	work MUST be GPL licensed and available as open source.  You also MUST give 
	proper attribution to the original author, copyright holder, and trademark
	owner.  This means you cannot change two lines of code and claim copyright 
	of the entire work as your own.  The GPL2 license requires that if you
	modify this code, you must clearly indicate what section(s) you have
	modified and you may only claim copyright of your modifications and not
	the body of work.  If you are unsure or have questions about how a 
	derivative work you are developing complies with the license, copyright, 
	trademark, or if you do not understand the difference between
	open source and public domain, contact the original author at:
	http://rocketgeek.com/contact/.


	INSTALLATION PROCEDURE:
	
	For complete installation and usage instructions,
	visit http://rocketgeek.com
*/


/** initial constants **/
define( 'WPMEM_VERSION', '2.9.6' );
define( 'WPMEM_DEBUG', false );
define( 'WPMEM_DIR',  plugin_dir_url ( __FILE__ ) );
define( 'WPMEM_PATH', plugin_dir_path( __FILE__ ) );

/** initialize the plugin **/
add_action( 'after_setup_theme', 'wpmem_init', 10 );

/** install the pluign **/
register_activation_hook( __FILE__, 'wpmem_install' );


/**
 * Initialize WP-Members.
 *
 * The initialization function contains much of what was previously just
 * loaded in the main plugin file. It has been moved into this function
 * in order to allow action hooks for loading the plugin and initializing
 * its features and options.
 *
 * @since 2.9.0
 */
function wpmem_init()
{
	/**
	 * Action for pre-initialization of plugin options.
	 *
	 * @since 2.9.0
	 */
	do_action( 'wpmem_pre_init' );
	
	/**
	 * start with any potential translation
	 */
	load_plugin_textdomain( 'wp-members', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );


	/**
	 * load options
	 */
	$wpmem_settings = get_option( 'wpmembers_settings' );

	/**
	 * Filter the options before they are loaded into constants.
	 *
	 * @since 2.9.0
	 *
	 * @param array $wpmem_settings An array of the WP-Members settings.
	 */
	$wpmem_settings = apply_filters( 'wpmem_settings', $wpmem_settings );

	/**
	 * define constants based on option settings
	 */
	( ! defined( 'WPMEM_BLOCK_POSTS'  ) ) ? define( 'WPMEM_BLOCK_POSTS',  $wpmem_settings[1]  ) : '';
	( ! defined( 'WPMEM_BLOCK_PAGES'  ) ) ? define( 'WPMEM_BLOCK_PAGES',  $wpmem_settings[2]  ) : '';
	( ! defined( 'WPMEM_SHOW_EXCERPT' ) ) ? define( 'WPMEM_SHOW_EXCERPT', $wpmem_settings[3]  ) : '';
	( ! defined( 'WPMEM_NOTIFY_ADMIN' ) ) ? define( 'WPMEM_NOTIFY_ADMIN', $wpmem_settings[4]  ) : '';
	( ! defined( 'WPMEM_MOD_REG'      ) ) ? define( 'WPMEM_MOD_REG',      $wpmem_settings[5]  ) : '';
	( ! defined( 'WPMEM_CAPTCHA'      ) ) ? define( 'WPMEM_CAPTCHA',      $wpmem_settings[6]  ) : '';
	( ! defined( 'WPMEM_NO_REG'       ) ) ? define( 'WPMEM_NO_REG',       $wpmem_settings[7]  ) : '';
	( ! defined( 'WPMEM_USE_EXP'      ) ) ? define( 'WPMEM_USE_EXP',      $wpmem_settings[9]  ) : '';
	( ! defined( 'WPMEM_USE_TRL'      ) ) ? define( 'WPMEM_USE_TRL',      $wpmem_settings[10] ) : '';
	( ! defined( 'WPMEM_IGNORE_WARN'  ) ) ? define( 'WPMEM_IGNORE_WARN',  $wpmem_settings[11] ) : '';

	( ! defined( 'WPMEM_MSURL'  ) ) ? define( 'WPMEM_MSURL',  get_option( 'wpmembers_msurl', null ) ) : '';
	( ! defined( 'WPMEM_REGURL' ) ) ? define( 'WPMEM_REGURL', get_option( 'wpmembers_regurl',null ) ) : '';

	/**
	 * define the stylesheet
	 */
	$wpmem_style = get_option( 'wpmembers_cssurl', null );
	$wpmem_style = ( ! $wpmem_style ) ? get_option( 'wpmembers_style', null ) : $wpmem_style;
	define( 'WPMEM_CSSURL', $wpmem_style );


	/**
	 * Filter the location and name of the pluggable file.
	 *
	 * @since 2.9.0
	 *
	 * @param string The path to wp-members-pluggable.php.
	 */
	$wpmem_pluggable = apply_filters( 'wpmem_plugins_file', WP_PLUGIN_DIR . '/wp-members-pluggable.php' );
	
	/**
	 * preload any custom functions, if available
	 */
	if( file_exists( $wpmem_pluggable ) ) {
		include( $wpmem_pluggable );
	}


	/**
	 * preload the expiration module, if available
	 */
	$exp_module = ( in_array( 'wp-members-expiration/module.php', get_option( 'active_plugins' ) ) ) ? true : false;
	define( 'WPMEM_EXP_MODULE', $exp_module ); 


	include_once( 'wp-members-core.php' );

	add_action( 'init', 'wpmem' );                           // runs before headers are sent
	add_action( 'widgets_init', 'widget_wpmemwidget_init' ); // initializes the widget
	add_action( 'wp_head', 'wpmem_head' );                   // anything added to header
	add_action( 'admin_init', 'wpmem_chk_admin' );           // check user role to load correct dashboard
	add_action( 'admin_menu', 'wpmem_admin_options' );       // adds admin menu
	add_action( 'user_register', 'wpmem_wp_reg_finalize' );  // handles wp native registration
	add_action( 'login_enqueue_scripts', 'wpmem_wplogin_stylesheet' ); // styles the native registration

	add_filter( 'allow_password_reset', 'wpmem_no_reset' );  // no password reset for non-activated users
	add_filter( 'the_content', 'wpmem_securify', 1, 1 );     // securifies the_content
	add_filter( 'register_form', 'wpmem_wp_register_form' ); // adds fields to the default wp registration
	add_filter( 'registration_errors', 'wpmem_wp_reg_validate', 10, 3 ); // native registration validation


	/**
	 * add the wp-members shortcodes
	 */
	add_shortcode( 'wp-members',       'wpmem_shortcode' );
	add_shortcode( 'wpmem_field',      'wpmem_shortcode' );
	add_shortcode( 'wpmem_logged_in',  'wpmem_shortcode' );
	add_shortcode( 'wpmem_logged_out', 'wpmem_shortcode' );
	add_shortcode( 'wpmem_logout',     'wpmem_shortcode' );


	/**
	 * load the stylesheet if using the new forms
	 */
	add_action( 'wp_print_styles', 'wpmem_enqueue_style' );


	/**
	 * if registration is moderated, check for activation (blocks backend login by non-activated users)
	 */
	if( WPMEM_MOD_REG == 1 ) { 
		add_filter( 'authenticate', 'wpmem_check_activated', 99, 3 ); 
	}
	
	/**
	 * Action at the end of initialization of plugin options.
	 *
	 * @since 2.9.0
	 */
	do_action( 'wpmem_after_init' );
}


/**
 * Scripts for admin panels.
 *
 * Determines which scripts to load and actions to use based on the 
 * current users capabilities.
 *
 * @since 2.5.2
 */
function wpmem_chk_admin()
{
	/**
	 * Action for initialization of admin options.
	 *
	 * @since 2.9.0
	 */
	do_action( 'wpmem_pre_admin_init' );
	
	// if user has a role that can edit users, load the admin functions
	if( current_user_can( 'edit_users' ) ) { 
		require_once( 'admin/admin.php' );
		require_once( 'admin/users.php' );
		include_once( 'admin/user-profile.php' );
	} else {
		// user profile actions for non-admins
		require_once( WPMEM_PATH . 'users.php' );
		add_action( 'show_user_profile', 'wpmem_user_profile'   );
		add_action( 'edit_user_profile', 'wpmem_user_profile'   );
		add_action( 'profile_update',    'wpmem_profile_update' );
	}
	
	// if user has a role that can edit posts, add the block/unblock meta boxes and custom post/page columns
	if( current_user_can( 'edit_posts' ) ) {
		include_once( 'admin/post.php' );
		add_action( 'add_meta_boxes', 'wpmem_block_meta_add' );  
		add_action( 'save_post', 'wpmem_block_meta_save' );
		add_filter( 'manage_posts_columns', 'wpmem_post_columns' );  
		add_action( 'manage_posts_custom_column', 'wpmem_post_columns_content', 10, 2 );
		add_filter( 'manage_pages_columns', 'wpmem_page_columns' );
		add_action( 'manage_pages_custom_column', 'wpmem_page_columns_content', 10, 2 );
	}
	
	/**
	 * Action at the end of admin options.
	 *
	 * @since 2.9.0
	 */
	do_action( 'wpmem_after_admin_init' );
}


/**
 * Adds the plugin options page and JavaScript.
 *
 * @since 2.5.2
 */
function wpmem_admin_options() {
	$plugin_page = add_options_page ( 'WP-Members', 'WP-Members', 'manage_options', 'wpmem-settings', 'wpmem_admin'    );
	add_action( 'load-'.$plugin_page, 'wpmem_load_admin_js' ); // enqueues javascript for admin
}


/**
 * Install the plugin options.
 *
 * @since 2.5.2
 */
function wpmem_install() {
	require_once( 'wp-members-install.php' );
	if( is_multisite() ) {
		// if it is multisite, install options for each blog
		global $wpdb;
		$blogs = $wpdb->get_results("
			SELECT blog_id
			FROM {$wpdb->blogs}
			WHERE site_id = '{$wpdb->siteid}'
			AND spam = '0'
			AND deleted = '0'
			AND archived = '0'
		");
		$original_blog_id = get_current_blog_id();   
		foreach ( $blogs as $blog_id ) {
			switch_to_blog( $blog_id->blog_id );
			wpmem_do_install();
		}   
		switch_to_blog( $original_blog_id );
	} else {
		// normal single install
		wpmem_do_install();
	}
}


add_action( 'wpmu_new_blog', 'wpmem_mu_new_site', 10, 6 );
/**
 * Install default plugin options for a newly added blog in multisite.
 *
 * @since 2.9.3
 *
 * @param $blog_id
 * @param $user_id
 * @param $domain
 * @param $path
 * @param $site_id
 * @param $meta
 */
function wpmem_mu_new_site( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
	require_once( 'wp-members-install.php' );
	switch_to_blog( $blog_id );
	wpmem_do_install();
	restore_current_blog();
}


/** End of File **/