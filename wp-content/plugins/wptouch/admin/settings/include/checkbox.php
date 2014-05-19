<?php

function wptouch_admin_is_setting_checked() {
	global $_primed_setting;
	
	$settings = wptouch_get_settings( $_primed_setting->domain );
	
	if ( $settings ) {
		$name = $_primed_setting->name;
		if ( isset( $settings->$name ) ) {
			return ( $settings->$name == 1 );
		}
	} 
		
	return false;
}