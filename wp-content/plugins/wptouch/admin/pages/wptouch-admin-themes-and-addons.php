<?php

define( 'WPTOUCH_PRO_ADMIN_THEMES', __( 'Themes', 'wptouch-pro' ) );
define( 'WPTOUCH_PRO_ADMIN_ADDONS', __( 'Extensions', 'wptouch-pro' ) );

add_filter( 'wptouch_admin_page_render_wptouch-admin-themes-and-addons', 'wptouch_render_themes', 1 );
add_filter( 'wptouch_admin_page_render_wptouch-admin-themes-and-addons', 'wptouch_render_addons', 1 );

function wptouch_render_themes( $page_options ) {
	require_once( WPTOUCH_DIR . '/core/admin-themes.php' );
	
	wptouch_add_sub_page( WPTOUCH_PRO_ADMIN_THEMES, 'setup-themes-browser', $page_options );

	wptouch_add_page_section(
		WPTOUCH_PRO_ADMIN_THEMES,
		'',
		'handle-themes',
		array(
			wptouch_add_setting( 
				'custom', 
				'theme-browser', 
				'', 
				'', 
				WPTOUCH_SETTING_BASIC, 
				'3.1'
			)
		),
		$page_options
	);	

	return $page_options;
}

function wptouch_render_addons( $page_options ) {
	require_once( WPTOUCH_DIR . '/core/admin-extensions.php' );
	
	wptouch_add_sub_page( WPTOUCH_PRO_ADMIN_ADDONS, 'setup-addons-browser', $page_options );

	wptouch_add_page_section(
		WPTOUCH_PRO_ADMIN_ADDONS,
		'',
		'handle-addons',
		array(
			wptouch_add_setting( 
				'custom', 
				'extension-browser', 
				'', 
				'', 
				WPTOUCH_SETTING_BASIC, 
				'3.1'
			)
		),
		$page_options
	);		

	return $page_options;
}