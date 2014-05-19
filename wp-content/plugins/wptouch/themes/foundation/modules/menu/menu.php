<?php

add_action( 'foundation_module_init_mobile', 'foundation_menu_init' );
add_filter( 'wptouch_page_menu_walker', 'foundation_get_page_walker', 10, 2 );
add_filter( 'foundation_inline_style', 'foundation_menu_inline_style' );

function foundation_menu_inline_style( $style_data ) {
	require_once( WPTOUCH_DIR . '/core/file-operations.php' );
	$dir = foundation_get_base_module_dir();

	return $style_data . wptouch_load_file( $dir . '/menu/menu.css' );
}

function foundation_get_page_walker( $walker, $menu_name ) {
	global $wptouch_pro;

	// Don't do anything for WordPress menus
	if ( is_array( $wptouch_pro->theme_menus ) && count( $wptouch_pro->theme_menus ) ) {
		foreach( $wptouch_pro->theme_menus as $menu_info ) {
			$settings = wptouch_get_settings( $menu_info->settings_domain );
			$setting_value = $menu_info->setting_name;

			$menu_to_show = $settings->$setting_value;

			if ( $menu_name == $menu_to_show ) {
				// This is the menu that is showing
				switch ( $menu_info->menu_type ) {
					case 'dropdown':
						// This is already taken care of by the walkel
						break;
					default:
						$walker = apply_filters( 'wptouch_unhandled_page_walker', $walker, $menu_info->menu_type );
						break;
				}
			}
		}
	}

	return $walker;
//	$wptouch_pro->theme_menus[] = $menu;
}

function foundation_menu_get_style_deps() {
	$style_deps = array();

	if ( defined( 'WPTOUCH_MODULE_RESET_INSTALLED' ) ) {
		$style_deps[] = 'foundation_reset';
	}

	return $style_deps;
}

function foundation_menu_init() {
	/*
	wp_enqueue_style(
		'foundation_menu',
		foundation_get_base_module_url() . '/menu/menu.css',
		foundation_menu_get_style_deps(),
		FOUNDATION_VERSION
	);
	*/

	wp_enqueue_script(
		'foundation_menu',
		foundation_get_base_module_url() . '/menu/menu.js',
		array( 'jquery' ),
		FOUNDATION_VERSION,
		true
	);
}