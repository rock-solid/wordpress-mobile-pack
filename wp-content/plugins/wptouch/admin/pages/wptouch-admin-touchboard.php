<?php

define( 'WPTOUCH_ADMIN_MENU_TOUCHBOARD', __( 'What\'s New', 'wptouch-pro' ) );

add_filter( 'wptouch_admin_page_render_wptouch-admin-touchboard', 'wptouch_render_menu_touchboard' );

function wptouch_render_menu_touchboard( $page_options ) {
	wptouch_add_sub_page( WPTOUCH_ADMIN_MENU_TOUCHBOARD, 'menu-touchboard', $page_options );

	wptouch_add_page_section(
		WPTOUCH_ADMIN_MENU_TOUCHBOARD,
		'',
		'admin_menu_touchboard_area',
		array(
			wptouch_add_setting(
				'custom',
				'touchboard'
			)
		),
		$page_options
	);	

	return $page_options;
}