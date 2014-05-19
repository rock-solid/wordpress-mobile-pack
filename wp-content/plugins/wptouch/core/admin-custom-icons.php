<?php

global $wptouch_custom_icon;
global $wptouch_custom_icons;
global $wptouch_custom_icons_iterator;

function wptouch_have_custom_icons() {
	global $wptouch_pro;
	global $wptouch_custom_icons;
	global $wptouch_custom_icons_iterator;

	if ( !$wptouch_custom_icons_iterator ) {
		$icon_packs = $wptouch_pro->get_available_icon_packs();
		if ( isset( $icon_packs[ WPTOUCH_CUSTOM_ICON_SET_NAME ] ) ) {
			$wptouch_custom_icons = $wptouch_pro->get_icons_from_packs( WPTOUCH_CUSTOM_ICON_SET_NAME );
		}

		$wptouch_custom_icons_iterator = new WPtouchArrayIterator( $wptouch_custom_icons );
	}

	return $wptouch_custom_icons_iterator->have_items();
}

function wptouch_the_custom_icon() {
	global $wptouch_custom_icon;
	global $wptouch_custom_icons_iterator;

	if ( $wptouch_custom_icons_iterator ) {
		$wptouch_custom_icon = $wptouch_custom_icons_iterator->the_item();
	}
}

function wptouch_get_custom_icon_image() {
	global $wptouch_custom_icon;
	return $wptouch_custom_icon->url;
}

function wptouch_the_custom_icon_image() {
	echo wptouch_get_custom_icon_image();
}

function wptouch_get_custom_icon_name() {
	global $wptouch_custom_icon;
	return $wptouch_custom_icon->name;
}

function wptouch_the_custom_icon_name() {
	echo wptouch_get_custom_icon_name();
}

function wptouch_get_custom_icon_width() {
	global $wptouch_custom_icon;
	return $wptouch_custom_icon->image_size[0];
}

function wptouch_the_custom_icon_width() {
	echo wptouch_get_custom_icon_width();
}

function wptouch_get_custom_icon_height() {
	global $wptouch_custom_icon;
	return $wptouch_custom_icon->image_size[1];
}

function wptouch_the_custom_icon_height() {
	echo wptouch_get_custom_icon_height();
}

