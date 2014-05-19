<?php

function wptouch_admin_get_setting_value() {
	global $_primed_setting;

	$settings = wptouch_get_settings( $_primed_setting->domain, false );
	$setting_name = $_primed_setting->name;
	
	if ( isset( $settings->$setting_name ) ) {
		return $settings->$setting_name;
	}
}

function wptouch_admin_the_setting_value() {
	echo wptouch_admin_get_setting_value();
}
