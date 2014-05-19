<?php


// All available built-in WPtouch Pro menu items go here
define( 'WPTOUCH_PRO_ADMIN_TOUCHBOARD', 'wptouch-admin-touchboard' );
define( 'WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS', 'wptouch-admin-general-settings' );
define( 'WPTOUCH_PRO_ADMIN_MENUS', 'wptouch-admin-menus' );
define( 'WPTOUCH_PRO_ADMIN_MENUS_DISABLED', 'wptouch-admin-menus-disabled' );
define( 'WPTOUCH_PRO_ADMIN_WARNINGS', 'wptouch-admin-warnings' );
define( 'WPTOUCH_PRO_ADMIN_LICENSE', 'wptouch-admin-license' );
define( 'WPTOUCH_PRO_ADMIN_UPGRADE', 'wptouch-admin-upgrade' );

define( 'WPTOUCH_PRO_ADMIN_THEMES_AND_ADDONS', 'wptouch-admin-themes-and-addons' );
define( 'WPTOUCH_PRO_ADMIN_THEME_OPTIONS', 'wptouch-admin-theme-settings' );
define( 'WPTOUCH_PRO_ADMIN_ADDON_OPTIONS', 'wptouch-admin-addon-settings' );
define( 'WPTOUCH_PRO_ADMIN_ADDON_OPTIONS_GENERAL', 'wptouch-admin-addon-settings' );


function wptouch_admin_create_menu( $id, $friendly_name, $menu_type = WPTOUCH_PRO_ADMIN_SETTINGS_PAGE, $display_name = false ) {
	$menu = new stdClass;

	$menu->slug = $id;
	$menu->friendly_name = $friendly_name;
	$menu->menu_type = $menu_type;
	$menu->display_name = $display_name;

	return $menu;
}

function wptouch_admin_get_predefined_menus( $network_only = false ) {
	$available_menus = array();

	if ( $network_only ) {
		$available_menus = array(
			WPTOUCH_PRO_ADMIN_TOUCHBOARD => wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_TOUCHBOARD, __( 'What\'s New', 'wptouch-pro' ) )
		);
	} else {
		$available_menus = array(
			WPTOUCH_PRO_ADMIN_TOUCHBOARD => wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_TOUCHBOARD, __( 'What\'s New', 'wptouch-pro' ) ),
			WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS => wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS, __( 'Core Settings', 'wptouch-pro' ) ),
			WPTOUCH_PRO_ADMIN_THEMES_AND_ADDONS => wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_THEMES_AND_ADDONS, __( 'Themes & Extensions', 'wptouch-pro' ) ),
			WPTOUCH_PRO_ADMIN_THEME_OPTIONS => wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_THEME_OPTIONS, __( 'Theme Settings', 'wptouch-pro' ), WPTOUCH_PRO_ADMIN_SETTINGS_PAGE, sprintf( __( '%s Settings', 'wptouch-pro' ), wptouch_get_bloginfo( 'active_theme_friendly_name' ) ) ),
		);

		$settings = wptouch_get_settings();
		if ( isset( $settings->active_addons ) && is_array( $settings->active_addons ) && count( $settings->active_addons ) ) {
			$available_menus[ WPTOUCH_PRO_ADMIN_ADDON_OPTIONS ] = wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_ADDON_OPTIONS, __( 'Extension Settings', 'wptouch-pro' ) );
		}

		if ( wptouch_get_registered_theme_count() ) {
			// Need to see if a theme has a menu available
			$available_menus[ WPTOUCH_PRO_ADMIN_MENUS ] = wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_MENUS, __( 'Menus', 'wptouch-pro' ) );
		} else {
			$available_menus[ WPTOUCH_PRO_ADMIN_MENUS_DISABLED ] = wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_MENUS_DISABLED, __( 'Menus', 'wptouch-pro' ) );
		}
	}

	// Check multisite
	if ( !defined( 'WPTOUCH_IS_FREE' ) && ( wptouch_can_show_license_menu() || defined( 'WPTOUCH_FORCE_SHOW_LICENSE_PANEL' ) ) ) {
		$available_menus[ WPTOUCH_PRO_ADMIN_LICENSE ] = wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_LICENSE, __( 'License', 'wptouch-pro' ), WPTOUCH_PRO_ADMIN_CUSTOM_PAGE );
	}

	// Check multisite
	if ( defined( 'WPTOUCH_IS_FREE' ) ) {
		$available_menus[ WPTOUCH_PRO_ADMIN_UPGRADE ] = wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_UPGRADE, __( 'Upgrade to Pro', 'wptouch-pro' ), WPTOUCH_PRO_ADMIN_CUSTOM_PAGE );
	}

	return apply_filters( 'wptouch_available_menus', $available_menus );
}

function wptouch_admin_get_root_slug() {
	$menu = wptouch_admin_get_predefined_menus();

	return $menu[ WPTOUCH_PRO_ADMIN_TOUCHBOARD ]->slug;
}

