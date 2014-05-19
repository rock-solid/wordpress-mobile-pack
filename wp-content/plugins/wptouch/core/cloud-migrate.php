<?php

function wptouch_migration_is_theme_broken() {
	$settings = wptouch_get_settings();

	if ( !defined( 'WPTOUCH_IS_FREE' ) ) {
		$settings->current_theme_location = str_replace( 'plugins/wptouch', 'plugins/wptouch-pro-3', $settings->current_theme_location );
	}

	$broken = ( !file_exists( WP_CONTENT_DIR . $settings->current_theme_location . '/'. $settings->current_theme_name ) );
	return $broken;
}

function wptouch_migration_check_for_broken_extensions() {
	$settings = wptouch_get_settings();
	if ( isset( $settings->active_addons) ) {
		$update_extensions = false;
		$new_extensions = $settings->active_addons;
		foreach( $settings->active_addons as $name => $addon_info ) {
			if ( !file_exists( WP_CONTENT_DIR . '/' . $addon_info->location . '/' . $addon_info->addon_name ) ) {
				$update_extensions = true;
				unset( $new_extensions[ $name ] );
			}
		}

		if ( $update_extensions ) {
			$settings->active_addons = $new_extensions;
			$settings->save();
		}
	}
}

function wptouch_can_repair_active_theme() {
	global $wptouch_pro;

	$settings = wptouch_get_settings();

	// Broken theme
	$theme_dirs = $wptouch_pro->get_theme_directories();

	// Try to find it
	$theme_fixed = false;
	foreach( $theme_dirs as $theme_dir ) {
		if ( file_exists( $theme_dir[0]  . '/' . $settings->current_theme_name ) ) {
			// Theme was found here, so we need to repair it
			$wptouch_pro->repair_active_theme( $theme_dir[0], $settings->current_theme_friendly_name );
			$theme_fixed = true;
			break;
		}
	}

	return $theme_fixed;
}

function wptouch_repair_active_theme_from_cloud( &$error_condition ) {
	global $wptouch_pro;
	$result = true;
	$error_condition = false;

	$settings = wptouch_get_settings();

	$wptouch_pro->setup_bncapi();

	// We need to download the theme and then repair it
	$themes = $wptouch_pro->get_available_themes( true );
	if ( isset( $themes[ $settings->current_theme_friendly_name ] ) ) {
		require_once( WPTOUCH_DIR . '/core/addon-theme-installer.php' );

		$theme_to_install = $themes[ $settings->current_theme_friendly_name ];

		$addon_installer = new WPtouchAddonThemeInstaller;
		$result = $addon_installer->install( $theme_to_install->base, $theme_to_install->download_url, 'themes' );
		if ( $result ) {
			$wptouch_pro->repair_active_theme( WPTOUCH_BASE_CONTENT_DIR . '/themes', $settings->current_theme_friendly_name );
		} 
	}	

	return $result;
}
