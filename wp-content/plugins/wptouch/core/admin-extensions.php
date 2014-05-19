<?php

global $wptouch_addons;
global $wptouch_cur_addon;

global $wptouch_addon_item;
global $wptouch_addon_iterator;

function wptouch_rewind_addons() {
	global $wptouch_addons;
	$wptouch_addons = false;
}

function wptouch_has_addons( $include_cloud = false ) {
	global $wptouch_pro;
	global $wptouch_addon_iterator;

	if ( !$wptouch_addon_iterator ) {
		$wptouch_addons = $wptouch_pro->get_available_addons( $include_cloud );
		$wptouch_addon_iterator = new WPtouchArrayIterator( $wptouch_addons );
	}

	return $wptouch_addon_iterator->have_items();
}

function wptouch_the_addon() {
	global $wptouch_addon_iterator;
	global $wptouch_cur_addon;

	$wptouch_cur_addon = $wptouch_addon_iterator->the_item();

	return apply_filters( 'wptouch_addon', $wptouch_cur_addon );
}

function wptouch_the_addon_classes( $extra_classes = array() ) {
	echo implode( ' ', wptouch_get_addon_classes( $extra_classes ) ) ;
}

function wptouch_get_addon_classes( $extra_classes = array() ) {

	$classes = $extra_classes;

	if ( wptouch_is_addon_active() ) {
		$classes[] = 'active';
	}

	if ( wptouch_is_addon_in_cloud() ) {
		$classes[] = 'cloud';
	}

	if ( wptouch_has_addon_tags() ) {
		$tags = wptouch_get_addon_tags();
		foreach( $tags as $tag ) {
			$classes[] = $tag;
		}
	}

	$classes[] = 'name-' . wptouch_convert_to_class_name( wptouch_get_addon_title() );

	return $classes;
}

function wptouch_has_addon_tags() {
	global $wptouch_cur_addon;

	return ( isset( $wptouch_cur_addon->tags ) && count( $wptouch_cur_addon->tags ) );
}

function wptouch_get_addon_tags() {
	global $wptouch_cur_addon;

	return apply_filters( 'wptouch_addon_tags', $wptouch_cur_addon->tags );
}

function wptouch_is_addon_active() {
	global $wptouch_pro;
	global $wptouch_cur_addon;

	$settings = $wptouch_pro->get_settings();

	return isset( $settings->active_addons[ $wptouch_cur_addon->name ] );
}

function wptouch_the_addon_version() {
	echo wptouch_get_addon_version();
}

function wptouch_get_addon_version() {
	global $wptouch_cur_addon;
	if ( $wptouch_cur_addon ) {
		return apply_filters( 'wptouch_addon_version', $wptouch_cur_addon->version );
	}

	return false;
}

function wptouch_addon_info_url() {
	global $wptouch_cur_addon;
	if ( isset( $wptouch_cur_addon->info_url ) ) {
		return $wptouch_cur_addon->info_url . '?utm_source=' . WPTOUCH_UTM_SOURCE . '&utm_campaign=extension-browser&utm_source=web';
	} else {
		return false;
	}
}

function wptouch_the_addon_title() {
	echo wptouch_get_addon_title();
}

function wptouch_get_addon_title() {
	global $wptouch_cur_addon;
	if ( $wptouch_cur_addon ) {
		return apply_filters( 'wptouch_addon_title', $wptouch_cur_addon->name );
	}

	return false;
}

function wptouch_the_addon_base() {
	echo wptouch_get_addon_base();
}

function wptouch_get_addon_base() {
	global $wptouch_cur_addon;
	if ( $wptouch_cur_addon ) {
		return apply_filters( 'wptouch_addon_base', $wptouch_cur_addon->base );
	}

	return false;
}

function wptouch_the_addon_download_url() {
	echo wptouch_get_addon_download_url();
}

function wptouch_get_addon_download_url() {
	global $wptouch_cur_addon;
	if ( $wptouch_cur_addon ) {
		return $wptouch_cur_addon->download_url;
	}

	return false;
}

function wptouch_get_addon_buy_url() {
	global $wptouch_cur_addon;
	if ( $wptouch_cur_addon && isset( $wptouch_cur_addon->buy_url ) ) {
		return $wptouch_cur_addon->buy_url;
	}

	return false;
}

function wptouch_the_addon_buy_url() {
	echo wptouch_get_addon_buy_url();
}

function wptouch_the_addon_location() {
	echo wptouch_get_addon_location();
}

function wptouch_get_addon_location() {
	global $wptouch_cur_addon;
	if ( $wptouch_cur_addon ) {
		return apply_filters( 'wptouch_addon_location', $wptouch_cur_addon->location );
	}

	return false;
}

function wptouch_get_addon_url() {
	echo WP_CONTENT_URL . wptouch_get_addon_location();
}

function wptouch_the_addon_url() {
	echo wptouch_get_addon_url();
}

function wptouch_the_addon_features() {
	echo implode( wptouch_get_addon_features(), ', ' );
}

function wptouch_get_addon_features() {
	global $wptouch_cur_addon;
	return apply_filters( 'wptouch_addon_features', $wptouch_cur_addon->features );
}

function wptouch_addon_has_features() {
	global $wptouch_cur_addon;
	return $wptouch_cur_addon->features;
}

function wptouch_the_addon_author() {
	echo wptouch_get_addon_author();
}

function wptouch_get_addon_author() {
	global $wptouch_cur_addon;
	if ( $wptouch_cur_addon ) {
		return apply_filters( 'wptouch_addon_author', $wptouch_cur_addon->author );
	}

	return false;
}

function wptouch_the_addon_description() {
	echo wptouch_get_addon_description();
}

function wptouch_get_addon_description() {
	global $wptouch_cur_addon;
	if ( $wptouch_cur_addon ) {
		return apply_filters( 'wptouch_addon_description', $wptouch_cur_addon->description );
	}

	return false;
}

function wptouch_cloud_addon_update_available() {
	global $wptouch_cur_addon;

	return ( !wptouch_is_addon_in_cloud() && isset( $wptouch_cur_addon->upgrade_available ) && $wptouch_cur_addon->upgrade_available );
}


function wptouch_cloud_addon_get_update_version() {
	global $wptouch_cur_addon;

	return $wptouch_cur_addon->cloud_version;
}


function wptouch_is_addon_in_cloud() {
	global $wptouch_cur_addon;

	return ( isset( $wptouch_cur_addon->location ) && ( $wptouch_cur_addon->location == 'cloud' ) );
}

function wptouch_the_addon_screenshot() {
	echo wptouch_get_addon_screenshot();
}

function wptouch_get_addon_screenshot() {
	global $wptouch_cur_addon;
	if ( $wptouch_cur_addon ) {
		return apply_filters( 'wptouch_addon_screenshot', $wptouch_cur_addon->screenshot );
	}

	return false;
}

function wptouch_the_addon_activate_link_url() {
	echo wptouch_get_addon_activate_link_url();
}

function wptouch_get_addon_activate_link_url() {
	return add_query_arg( array(
		'admin_command' => 'activate_addon',
		'addon_name' => urlencode( wptouch_get_addon_title() ),
		'addon_location' => urlencode( wptouch_get_addon_location() ),
		'admin_menu_nonce' => wptouch_admin_menu_get_nonce()
	), admin_url( 'admin.php?page=wptouch-admin-themes-and-addons') );
}

function wptouch_the_addon_deactivate_link_url() {
	echo wptouch_get_addon_deactivate_link_url();
}

function wptouch_get_addon_deactivate_link_url() {
	return add_query_arg( array(
		'admin_command' => 'deactivate_addon',
		'addon_name' => urlencode( wptouch_get_addon_title() ),
		'addon_location' => urlencode( wptouch_get_addon_location() ),
		'admin_menu_nonce' => wptouch_admin_menu_get_nonce()
	), admin_url( 'admin.php?page=wptouch-admin-themes-and-addons' ) );
}

global $wptouch_addon_previews;
global $wptouch_addon_preview_item;
global $wptouch_addon_preview_iterator;

function wptouch_get_addon_preview_images() {
	require_once( WPTOUCH_DIR . '/core/file-operations.php' );

	return wptouch_get_files_in_directory( WP_CONTENT_DIR . wptouch_get_addon_location() . '/preview', '.jpg', false );
}

function wptouch_has_addon_preview_images() {
	global $wptouch_addon_preview_iterator;
	global $wptouch_addon_previews;

	if ( !$wptouch_addon_preview_iterator ) {
		$wptouch_addon_previews = wptouch_get_addon_preview_images();
		$wptouch_addon_preview_iterator = new WPtouchArrayIterator( $wptouch_addon_previews );
	}

	return $wptouch_addon_preview_iterator->have_items();
}

function wptouch_the_addon_preview_image() {
	global $wptouch_addon_preview_iterator;
	global $wptouch_addon_preview_item;

	$wptouch_addon_preview_item = $wptouch_addon_preview_iterator->the_item();

	return apply_filters( 'wptouch_addon_preview_image', $wptouch_addon_preview_item );
}

function wptouch_get_addon_preview_image_num() {
	global $wptouch_addon_preview_iterator;

	return $wptouch_addon_preview_iterator->current_position();
}

function wptouch_is_first_addon_preview_image() {
	return ( wptouch_get_addon_preview_image_num() == 1 );
}

function wptouch_get_addon_preview_url() {
	global $wptouch_addon_preview_item;

	return wptouch_get_addon_url() . '/preview/' . $wptouch_addon_preview_item;
}


function wptouch_the_addon_preview_url() {
	echo wptouch_get_addon_preview_url();
}

function wptouch_reset_addon_preview() {
	global $wptouch_addon_preview_iterator;

	$wptouch_addon_preview_iterator = false;
}

