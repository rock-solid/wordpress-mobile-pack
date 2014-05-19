<?php
/*
Plugin Name: MobilePress
Plugin URI: http://mobilepress.co.za
Description: Turn your WordPress blog into a mobile website/blog.
Version: 1.2.2
Author: Tyler Reed
Author URI: http://tylerreed.com
License: GPL2

/* Copyright 2008 - 2012  Tyler Reed  (email : mobilepress@tylerreed.com)

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
*/

global $wpdb;

// Load the helpers
require_once( dirname( __FILE__ ) . '/helpers/functions.php' );

// Load the config
require_once( dirname( __FILE__ ) . '/config/config.php' );

// Load the core class
require_once( dirname( __FILE__ ) . '/classes/core.php' );

if ( class_exists( 'Mobilepress_core' ) ) {
	// New MobilePress object
	$mobilepress_core = new Mobilepress_core;

	// Setup the installer on activation of the plugin
	register_activation_hook( __FILE__, array( &$mobilepress_core, 'mopr_load_activation' ) );

	// Shut down the plugin on deactivation
	register_deactivation_hook( __FILE__, array( &$mobilepress_core, 'mopr_load_deactivation' ) );

	// Setup the uninstaller if the plugin needs to be uninstalled
	register_uninstall_hook( __FILE__, array( &$mobilepress_core, 'mopr_load_uninstall' ));

	// Setup admin panel only if we are inside the admin area, otherwise run the normal render code
	if ( is_admin() )
	{
		// Setup the admin area
		$mobilepress_core->mopr_load_admin();
	} else {
		require_once( dirname( __FILE__ ) . '/helpers/api.php' );
		// Start a session if not started already
		if ( ! session_id() ) {
			@session_start();
		}

		// Initialize the MobilePress check logic and rendering
		$mobilepress_core->mopr_load_site();
	}
}

?>