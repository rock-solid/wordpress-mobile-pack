<?php

global $wptouch_themes;
global $wptouch_cur_theme;

global $wptouch_theme_item;
global $wptouch_theme_iterator;

function wptouch_rewind_themes() {
	global $wptouch_themes;
	$wptouch_themes = false;
}

function wptouch_has_themes( $include_cloud = false ) {
	global $wptouch_pro;
	global $wptouch_theme_iterator;

	if ( !$wptouch_theme_iterator ) {
		$wptouch_themes = $wptouch_pro->get_available_themes( $include_cloud );
		$wptouch_theme_iterator = new WPtouchArrayIterator( $wptouch_themes );
	}

	return $wptouch_theme_iterator->have_items();
}

function wptouch_is_theme_in_cloud() {
	global $wptouch_cur_theme;

	return ( isset( $wptouch_cur_theme->location ) && ( $wptouch_cur_theme->location == 'cloud' ) );
}

function wptouch_theme_type() {
	global $wptouch_cur_theme;

	if ( isset( $wptouch_cur_theme->theme_type ) ) {
		return $wptouch_cur_theme->theme_type;
	} else {
		return false;
	}
}

function wptouch_theme_info_url() {
	global $wptouch_cur_theme;

	if ( isset( $wptouch_cur_theme->info_url ) ) {
		return $wptouch_cur_theme->info_url . '?utm_source=' . WPTOUCH_UTM_SOURCE . '&utm_campaign=theme-browser&utm_source=web';
	} else {
		return false;
	}
}

function wptouch_cloud_theme_update_available() {
	global $wptouch_cur_theme;

	return ( !wptouch_is_theme_in_cloud() && isset( $wptouch_cur_theme->upgrade_available ) && $wptouch_cur_theme->upgrade_available );
}

function wptouch_cloud_theme_get_update_version() {
	global $wptouch_cur_theme;

	return $wptouch_cur_theme->cloud_version;
}

function wptouch_the_theme() {
	global $wptouch_theme_iterator;
	global $wptouch_cur_theme;

	$wptouch_cur_theme = $wptouch_theme_iterator->the_item();

	return apply_filters( 'wptouch_theme', $wptouch_cur_theme );
}

function wptouch_the_theme_classes( $extra_classes = array() ) {
	echo implode( ' ', wptouch_get_theme_classes( $extra_classes ) ) ;
}

function wptouch_get_theme_classes( $extra_classes = array() ) {

	$classes = $extra_classes;

	if ( wptouch_is_theme_active() ) {
		$classes[] = 'active';
	}

	if ( wptouch_is_theme_in_cloud() ) {
		$classes[] = 'cloud';
	}

	if ( wptouch_is_theme_custom() ) {
		$classes[] = 'custom';
	}

	if ( wptouch_has_theme_tags() ) {
		$tags = wptouch_get_theme_tags();
		foreach( $tags as $tag ) {
			$classes[] = $tag;
		}
	}

	$classes[] = 'name-' . wptouch_convert_to_class_name( wptouch_get_theme_title() );

	return $classes;
}

function wptouch_has_theme_tags() {
	global $wptouch_cur_theme;

	return ( isset( $wptouch_cur_theme->tags ) && count( $wptouch_cur_theme->tags ) );
}

function wptouch_get_theme_tags() {
	global $wptouch_cur_theme;

	return apply_filters( 'wptouch_theme_tags', $wptouch_cur_theme->tags );
}

function wptouch_is_theme_active() {
	global $wptouch_pro;
	global $wptouch_cur_theme;

	$settings = $wptouch_pro->get_settings();

	$current_theme_location = $settings->current_theme_location . '/' . $settings->current_theme_name;

	return ( $wptouch_cur_theme->location == $current_theme_location );
}

function wptouch_active_theme_has_settings() {
	$menu = apply_filters( 'wptouch_theme_menu', array() );
	return count( $menu );
}

function wptouch_is_theme_custom() {
	global $wptouch_cur_theme;
	return ( $wptouch_cur_theme->custom_theme );
}

function wptouch_is_theme_child() {
	global $wptouch_cur_theme;
	return ( isset( $wptouch_cur_theme->parent_theme ) && strlen( $wptouch_cur_theme->parent_theme ) );
}

function wptouch_the_theme_version() {
	echo wptouch_get_theme_version();
}

function wptouch_get_theme_parent() {
	global $wptouch_cur_theme;
	return $wptouch_cur_theme->parent_theme;
}

function wptouch_get_theme_version() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return apply_filters( 'wptouch_theme_version', $wptouch_cur_theme->version );
	}

	return false;
}

function wptouch_the_theme_title() {
	echo wptouch_get_theme_title();
}

function wptouch_get_theme_title() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return apply_filters( 'wptouch_theme_title', $wptouch_cur_theme->name );
	}

	return false;
}

function wptouch_the_theme_location() {
	echo wptouch_get_theme_location();
}

function wptouch_get_theme_location() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return apply_filters( 'wptouch_theme_location', $wptouch_cur_theme->location );
	}

	return false;
}

function wptouch_get_theme_url() {
	echo WP_CONTENT_URL . wptouch_get_theme_location();
}

function wptouch_the_theme_url() {
	echo wptouch_get_theme_url();
}

function wptouch_the_theme_features() {
	echo implode( wptouch_get_theme_features(), ', ' );
}

function wptouch_get_theme_features() {
	global $wptouch_cur_theme;
	return apply_filters( 'wptouch_theme_features', $wptouch_cur_theme->features );
}

function wptouch_theme_has_features() {
	global $wptouch_cur_theme;
	return $wptouch_cur_theme->features;
}

function wptouch_the_theme_author() {
	echo wptouch_get_theme_author();
}

function wptouch_get_theme_author() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return apply_filters( 'wptouch_theme_author', $wptouch_cur_theme->author );
	}

	return false;
}

function wptouch_the_theme_description() {
	echo wptouch_get_theme_description();
}

function wptouch_get_theme_description() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return apply_filters( 'wptouch_theme_description', $wptouch_cur_theme->description );
	}

	return false;
}

function wptouch_the_theme_screenshot() {
	echo wptouch_get_theme_screenshot();
}

function wptouch_get_theme_screenshot() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return apply_filters( 'wptouch_theme_screenshot', $wptouch_cur_theme->screenshot );
	}

	return false;
}


function wptouch_the_theme_base() {
	echo wptouch_get_theme_base();
}

function wptouch_get_theme_base() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return apply_filters( 'wptouch_theme_base', $wptouch_cur_theme->base );
	}

	return false;
}

function wptouch_the_theme_download_url() {
	echo wptouch_get_theme_download_url();
}

function wptouch_get_theme_download_url() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return $wptouch_cur_theme->download_url;
	}

	return false;
}

function wptouch_get_theme_buy_url() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme && isset( $wptouch_cur_theme->buy_url ) ) {
		return $wptouch_cur_theme->buy_url;
	}

	return false;
}

function wptouch_the_theme_buy_url() {
	echo wptouch_get_theme_buy_url();
}

function wtouch_the_theme_activate_link_url() {
	echo wptouch_get_theme_activate_link_url();
}

function wptouch_get_theme_activate_link_url() {
	return add_query_arg( array(
		'admin_command' => 'activate_theme',
		'theme_name' => urlencode( wptouch_get_theme_title() ),
		'theme_location' => urlencode( wptouch_get_theme_location() ),
		'admin_menu_nonce' => wptouch_admin_menu_get_nonce()
	) );
}

function wtouch_the_theme_copy_link_url() {
	echo wptouch_get_theme_copy_link_url();
}

function wptouch_get_theme_copy_link_url() {
	return add_query_arg( array(
		'admin_command' => 'copy_theme',
		'theme_name' => urlencode( wptouch_get_theme_title() ),
		'theme_location' => urlencode( wptouch_get_theme_location() ),
		'admin_menu_nonce' => wptouch_admin_menu_get_nonce()
	) );
}

function wptouch_the_theme_delete_link_url() {
	echo wptouch_get_theme_delete_link_url();
}

function wptouch_get_theme_delete_link_url() {
	return add_query_arg( array(
		'admin_command' => 'delete_theme',
		'theme_name' => urlencode( wptouch_get_theme_title() ),
		'theme_location' => urlencode( wptouch_get_theme_location() ),
		'admin_menu_nonce' => wptouch_admin_menu_get_nonce()
	) );
}

global $wptouch_theme_previews;
global $wptouch_theme_preview_item;
global $wptouch_theme_preview_iterator;

function wptouch_get_theme_preview_images() {
	require_once( WPTOUCH_DIR . '/core/file-operations.php' );

	return wptouch_get_files_in_directory( WP_CONTENT_DIR . wptouch_get_theme_location() . '/preview', '.jpg', false );
}

function wptouch_has_theme_preview_images() {
	global $wptouch_theme_preview_iterator;
	global $wptouch_theme_previews;

	if ( !$wptouch_theme_preview_iterator ) {
		$wptouch_theme_previews = wptouch_get_theme_preview_images();
		$wptouch_theme_preview_iterator = new WPtouchArrayIterator( $wptouch_theme_previews );
	}

	return $wptouch_theme_preview_iterator->have_items();
}

function wptouch_the_theme_preview_image() {
	global $wptouch_theme_preview_iterator;
	global $wptouch_theme_preview_item;

	$wptouch_theme_preview_item = $wptouch_theme_preview_iterator->the_item();

	return apply_filters( 'wptouch_theme_preview_image', $wptouch_theme_preview_item );
}

function wptouch_get_theme_preview_image_num() {
	global $wptouch_theme_preview_iterator;

	return $wptouch_theme_preview_iterator->current_position();
}

function wptouch_is_first_theme_preview_image() {
	return ( wptouch_get_theme_preview_image_num() == 1 );
}

function wptouch_get_theme_preview_url() {
	global $wptouch_theme_preview_item;

	return wptouch_get_theme_url() . '/preview/' . $wptouch_theme_preview_item;
}

function wptouch_the_theme_preview_url() {
	echo wptouch_get_theme_preview_url();
}

function wptouch_reset_theme_preview() {
	global $wptouch_theme_preview_iterator;

	$wptouch_theme_preview_iterator = false;
}

