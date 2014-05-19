<?php

global $_wptouch_list_options_iterator;
global $_wptouch_list_option;

function wptouch_admin_has_list_options() {
	global $_wptouch_list_options_iterator;
	global $_primed_setting;
		
	if ( !$_wptouch_list_options_iterator ) {
		$_wptouch_list_options_iterator = new WPtouchArrayIterator( $_primed_setting->extra );
	}
	
	$has_items = $_wptouch_list_options_iterator->have_items();
	if ( !$has_items ) {
		$_wptouch_list_options_iterator = false;
	}
	
	return $has_items;
}

function wptouch_admin_the_list_option() {
	global $_wptouch_list_options_iterator;
	global $_wptouch_list_option;	
	
	$_wptouch_list_option = $_wptouch_list_options_iterator->the_item();
}

function wptouch_admin_get_list_option_key() {
	global $_wptouch_list_options_iterator;	
	return $_wptouch_list_options_iterator->the_key();
}

function wptouch_admin_the_list_option_key() {
	echo wptouch_admin_get_list_option_key();
}

function wptouch_admin_get_list_option_desc() {
	global $_wptouch_list_option;
	return $_wptouch_list_option;
}

function wptouch_admin_the_list_option_desc() {
	echo wptouch_admin_get_list_option_desc();
}

function wptouch_admin_is_list_option_selected() {
	global $_primed_setting;

	$settings = wptouch_get_settings( $_primed_setting->domain, false );
	$setting_name = $_primed_setting->name;
	
	return ( isset( $settings->$setting_name ) && ( wptouch_admin_get_list_option_key() == $settings->$setting_name ) );	
}
