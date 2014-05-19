<?php

add_filter( 'wptouch_admin_page_render_wptouch-admin-addon-settings', 'wptouch_render_addon_settings', 1 );

function wptouch_render_addon_settings( $page_options ) {
	
	wptouch_add_sub_page( WPTOUCH_PRO_ADMIN_ADDON_OPTIONS, 'addon-settings-general', $page_options );

	$page_options = apply_filters( 'wptouch_addon_options', $page_options );	

	return $page_options;
}