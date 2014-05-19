<?php

function wptouch_decode_encoded_setting( $encoded_setting ) {
	$decoded_setting = new stdClass;

	if ( preg_match( '#^wptouch__(.*)__(.*)#', $encoded_setting, $match ) ) {
		$decoded_setting->domain = $match[1];
		$decoded_setting->name = $match[2];
	}

	return $decoded_setting;
}

global $_primed_setting;

function wptouch_admin_prime_setting_for_display( $setting ) {
	global $_primed_setting;

	$_primed_setting = $setting;
}

function wptouch_admin_get_setting_name() {
	global $_primed_setting;

	if ( is_object( $_primed_setting ) ) {
		return $_primed_setting->name;
	}
}

function wptouch_admin_the_setting_name() {
	echo wptouch_admin_get_setting_name();
}

function wptouch_admin_get_setting_level() {
	global $_primed_setting;

	if ( is_object( $_primed_setting ) ) {
		return $_primed_setting->level;
	}
}

function wptouch_admin_get_setting_desc() {
	global $_primed_setting;

	if ( is_object( $_primed_setting ) ) {
		return $_primed_setting->desc;
	}
}

function wptouch_admin_the_setting_desc() {
	echo wptouch_admin_get_setting_desc();
}

function wptouch_admin_setting_has_tooltip() {
	global $_primed_setting;

	if ( is_object( $_primed_setting ) ) {
		return strlen( $_primed_setting->tooltip );
	}
}

function wptouch_admin_get_setting_tooltip() {
	global $_primed_setting;

	if ( is_object( $_primed_setting ) ) {
		return $_primed_setting->tooltip;
	}
}

function wptouch_admin_the_setting_tooltip() {
	echo htmlspecialchars( wptouch_admin_get_setting_tooltip() );
}

function wptouch_admin_get_manual_encoded_setting_name( $domain, $name ) {
	return 'wptouch__' . $domain . '__' . $name;
}

function wptouch_admin_get_encoded_setting_name() {
	global $_primed_setting;

	if ( is_object( $_primed_setting ) ) {
		return 'wptouch__' . $_primed_setting->domain . '__' . $_primed_setting->name;
	}
}

function wptouch_admin_the_encoded_setting_name() {
	echo wptouch_admin_get_encoded_setting_name();
}

function wptouch_admin_get_split_version( $ver ) {
	$new_ver = explode( '.', $ver );

	$float_ver = $new_ver[0]*1000;
	if ( isset( $new_ver[1] ) ) {
		$float_ver += $new_ver[1];
	}

	if ( isset( $new_ver[2] ) ) {
		$float_ver += $new_ver[2] / 1000;
	}

	return $float_ver;
}

function wptouch_admin_is_setting_new() {
	global $_primed_setting;

	$current_version = wptouch_admin_get_split_version(
		apply_filters( 'wptouch_setting_version_compare', WPTOUCH_VERSION, $_primed_setting->domain )
	);

	$setting_added_in_version = wptouch_admin_get_split_version( $_primed_setting->version );

	return ( $setting_added_in_version == $current_version && $current_version > 1000 );
}
