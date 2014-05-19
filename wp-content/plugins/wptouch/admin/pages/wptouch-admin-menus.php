<?php

define( 'WPTOUCH_ADMIN_MENU_ICONS_OPTIONS', __( 'Theme Menus', 'wptouch-pro' ) );
define( 'WPTOUCH_ADMIN_MENU_ICONS_MENUS', __( 'Menu Setup', 'wptouch-pro' ) );
define( 'WPTOUCH_ADMIN_MENU_MANAGE_ICON_SETS', __( 'Icon Upload & Sets', 'wptouch-pro' ) );

add_filter( 'wptouch_admin_page_render_wptouch-admin-menus', 'wptouch_render_menu_page' );

function wptouch_add_custom_menus( $menu_array ) {
	$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
	foreach( $menus as $key => $menu ) {
		$menu_array[ $menu->term_id ] = $menu->name;
	}

	return $menu_array;
}

function wptouch_get_custom_menu_list( $include_wp_pages = true, $include_none = false ) {
	$custom_menu = array();

	if ( $include_wp_pages ) {
		$custom_menu[ 'wp' ] = __( 'WordPress Pages', 'wptouch-pro' );
	}

	$custom_menu = wptouch_add_custom_menus( $custom_menu );

	if ( $include_none ) {
		$custom_menu[ 'none' ] = __( 'None', 'wptouch-pro' );
	}

	return $custom_menu;
}

function wptouch_render_menu_page( $page_options ) {
	wptouch_add_sub_page( WPTOUCH_ADMIN_MENU_ICONS_OPTIONS, 'menu-icons-options', $page_options );
	wptouch_add_sub_page( WPTOUCH_ADMIN_MENU_ICONS_MENUS, 'menu-icons-menus', $page_options );	
	wptouch_add_sub_page( WPTOUCH_ADMIN_MENU_MANAGE_ICON_SETS, 'menu-icons-manage-icon-sets', $page_options );		

	global $wptouch_pro;
	$theme_menus = $wptouch_pro->theme_menus;
	if ( count( $theme_menus ) ) {
		foreach( $theme_menus as $menu ) {
			wptouch_add_page_section(
				WPTOUCH_ADMIN_MENU_ICONS_OPTIONS,
				$menu->friendly_name,
				'setup-menu-icons-' . $menu->setting_name,
				array(
					wptouch_add_setting( 
						'list', 
						$menu->setting_name, 
						$menu->description, 
						$menu->tooltip, 
						WPTOUCH_SETTING_BASIC, 
						'3.0', 
						wptouch_get_custom_menu_list( true, $menu->can_be_disabled )
					)		
				),
				$page_options,
				$menu->settings_domain
			);
		}
	}
	
	wptouch_add_page_section(
		WPTOUCH_ADMIN_MENU_ICONS_OPTIONS,
		__( 'Menu Options', 'wptouch-pro' ),
		'setup-menu-parent-items',
		array(
			wptouch_add_setting( 
				'checkbox', 
				'enable_parent_items', 
				__( 'Enable parent items as links', 'wptouch-pro' ), 
				__( 'If disabled, parent menu items will only toggle child items.', 'wptouch-pro' ), 
				WPTOUCH_SETTING_BASIC, 
				'3.0.2'
			),																		
			wptouch_add_setting( 
				'checkbox', 
				'enable_menu_icons', 
				__( 'Use menu icons', 'wptouch-pro' ), 
				'', 
				WPTOUCH_SETTING_ADVANCED, 
				'3.0'
			)
		),
		$page_options
	);		
	
	wptouch_add_page_section(
		WPTOUCH_ADMIN_MENU_ICONS_MENUS,
		'',
		'admin_menu_icons_menus',
		array(
			wptouch_add_setting(
				'custom',
				'icon_menu_area'
			)
		),
		$page_options
	);	
	
	wptouch_add_page_section(
		WPTOUCH_ADMIN_MENU_MANAGE_ICON_SETS,
		__( 'Icon Upload', 'wptouch-pro' ),
		'admin_menu_icon_upload',
		array(
			wptouch_add_setting(
				'custom',
				'custom_icon_upload'
			)
		),
		$page_options
	);	

	wptouch_add_page_section(
		WPTOUCH_ADMIN_MENU_MANAGE_ICON_SETS,
		__( 'Uploaded Icons', 'wptouch-pro' ),
		'uploaded-icons',
		array(
			wptouch_add_setting(
				'custom',
				'custom_icon_management'
			)
		),
		$page_options
	);	

	wptouch_add_page_section(
		WPTOUCH_ADMIN_MENU_MANAGE_ICON_SETS,
		__( 'Icon Sets', 'wptouch-pro' ),
		'admin_menu_icon_sets',
		array(
			wptouch_add_setting(
				'custom',
				'installed_icon_sets'
			)
		),
		$page_options
	);				
	
	return $page_options;
}