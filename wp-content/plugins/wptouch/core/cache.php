<?php

function wptouch_should_mobile_cache_page() {
	global $wptouch_pro;

	return $wptouch_pro->is_mobile_device;
}

function wptouch_cache_get_key() {
	$cache_key = 'wptouch_pro';

	// Add the active device class
	$cache_key = $cache_key . '_device_class_' . $wptouch_pro->$active_device_class;

	// Add the value of the user's cookie
	if ( isset( $_COOKIE[ WPTOUCH_COOKIE ] ) ) {
		$cache_key = $cache_key . '_cookie_' . $_COOKIE[ WPTOUCH_COOKIE ];
	}

	return md5( $cache_key );
}

function wptouch_cache_get_mobile_user_agents() {
	global $wptouch_pro;

	$user_agents = $wptouch_pro->get_supported_user_agents();
}