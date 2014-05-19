<?php

add_filter( 'wptouch_should_init_pro', 'wptouch_check_for_initialization' );

// ManageWP
add_filter( 'mwp_premium_update_notification', 'wptouch_mwp_update_notification' );
add_filter( 'mwp_premium_perform_update', 'wptouch_mwp_perform_update' );

add_filter( 'plugins_loaded', 'wptouch_compat_remove_hooks' );

function wptouch_compat_remove_hooks() {

	// Paginated Comments plugin
	remove_action( 'init', 'Paginated_Comments_init' );
	remove_action( 'admin_menu', 'Paginated_Comments_menu_add' );
	remove_action( 'template_redirect', 'Paginated_Comments_alter_source', 15 );
	remove_action( 'wp_head', 'Paginated_Comments_heads' );
	remove_filter( 'comment_post_redirect', 'Paginated_Comments_redirect_location', 1, 2 );

	// qTranslate
	if ( function_exists( 'qtrans_useCurrentLanguageIfNotFoundShowAvailable' ) ) {
		add_filter( 'wptouch_menu_item_title', 'qtrans_useCurrentLanguageIfNotFoundShowAvailable', 0 );
	}

	// Facebook Like button
	remove_filter( 'the_content', 'Add_Like_Button');

	// Sharebar Plugin
	remove_filter( 'the_content', 'sharebar_auto' );
	remove_action( 'wp_head', 'sharebar_header' );

	// Disqus
	remove_filter( 'comments_number', 'dsq_comments_number' );

	// Classipress
	remove_action( 'admin_enqueue_scripts', 'cp_load_admin_scripts' );
}

function wptouch_check_for_initialization( $should_init ) {
	// Check for Piggy Pro
	if ( function_exists( 'piggy_should_be_shown' ) && piggy_should_be_shown() ) {
		$should_init = false;
	}

	// Check for AJAX requests
	if ( defined( 'XMLRPC_REQUEST' ) || defined( 'APP_REQUEST'  ) ) {
		$should_init = false;
	}

	return $should_init;
}

function wptouch_mwp_update_notification( $premium_updates ) {
	global $wptouch_pro;

	if( !function_exists( 'get_plugin_data' ) ) {
		include_once( ABSPATH.'wp-admin/includes/plugin.php');
	}

	$myplugin = get_plugin_data( WPTOUCH_DIR . '/wptouch-pro-3.php' );
	$myplugin['type'] = 'plugin';

	$latest_info = $wptouch_pro->mwp_get_latest_info();
	if ( $latest_info ) {
		// Check to see if a new version is available
		if ( $latest_info['version'] != WPTOUCH_VERSION ) {
			$myplugin['new_version'] = $latest_info['version'];

			array_push( $premium_updates, $myplugin ) ;

			$wptouch_pro->remove_transient_info();
		}
	}

	return $premium_updates;
}

function wptouch_mwp_perform_update( $update ){
	global $wptouch_pro;

	if( !function_exists( 'get_plugin_data' ) ) {
		include_once( ABSPATH.'wp-admin/includes/plugin.php');
	}

	$my_addon = get_plugin_data(  WPTOUCH_DIR . '/wptouch-pro-3.php' );
	$my_addon[ 'type' ] = 'plugin';

	$latest_info = $wptouch_pro->mwp_get_latest_info();
	if ( $latest_info ) {
		// Check for a new version
		if ( $latest_info['version'] != WPTOUCH_VERSION ) {
			$my_addon['url'] = $latest_info['upgrade_url'];

			array_push( $update, $my_addon );
		}
	}

	return $update;
}
