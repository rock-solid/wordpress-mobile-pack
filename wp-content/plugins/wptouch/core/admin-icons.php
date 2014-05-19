<?php

global $wptouch_icon_pack;
global $wptouch_icon_packs_iterator;
global $wptouch_icon;
global $wptouch_icons_iterator;
global $wptouch_admin_menu_items;
global $wptouch_admin_menu_iterator;
global $wptouch_admin_menu_item;

function wptouch_get_remote_icon_packs() {
	$result = wp_remote_get( 'http://wptouch-pro-3.s3.amazonaws.com/icon-sets/readme.txt' );
	$remote_icon_packs = false;

	if ( $result && !is_wp_error( $result ) ) {
		if ( $result[ 'response' ][ 'code' ] == 200 ) {
			$remote_icon_packs = array();

			$items = explode( "\n", $result[ 'body' ] );
			foreach( $items as $item ) {
				$icon_set = new stdClass;

				$icon_set_info = explode( ",", $item );

				$icon_set->download_url = 'http://wptouch-pro-3.s3.amazonaws.com/icon-sets/' . $icon_set_info[0] . '.zip';
				$icon_set->name = $icon_set_info[1];
				$icon_set->dir_base = $icon_set_info[0];
				$icon_set->author = $icon_set_info[2];
				$icon_set->author_url = $icon_set_info[3];
				$icon_set->thumbnail = 'http://wptouch-pro-3.s3.amazonaws.com/icon-sets/thumbnails/' . $icon_set_info[0] . '.png';

				$remote_icon_packs[] = $icon_set;
			}
		}
	}

	return $remote_icon_packs;
}

function wptouch_already_has_icon_pack( $pack_name ) {
	global $wptouch_pro;

	$icon_packs = $wptouch_pro->get_available_icon_packs();
	return isset( $icon_packs[ $pack_name ] );
}

function wptouch_have_icon_packs() {
	global $wptouch_pro;
	global $wptouch_icon_packs_iterator;

	if ( !$wptouch_icon_packs_iterator ) {
		$wptouch_icon_packs = $wptouch_pro->get_available_icon_packs();
		$wptouch_icon_packs_iterator = new WPtouchArrayIterator( $wptouch_icon_packs );
	}

	$has_items = $wptouch_icon_packs_iterator->have_items();

	return $has_items;
}

function wptouch_the_icon_pack() {
	global $wptouch_icon_pack;
	global $wptouch_icon_packs_iterator;

	$wptouch_icon_pack = $wptouch_icon_packs_iterator->the_item();
}

function wptouch_the_icon_pack_name() {
	echo wptouch_get_icon_pack_name();
}

function wptouch_get_icon_pack_name() {
	global $wptouch_icon_pack;

	return apply_filters( 'wptouch_icon_pack_name', $wptouch_icon_pack->name );
}

function wptouch_get_icon_pack_author_url() {
	global $wptouch_icon_pack;

	if ( isset( $wptouch_icon_pack->author_url ) ) {
		return $wptouch_icon_pack->author_url;
	} else {
		return false;
	}
}

function wptouch_the_icon_pack_author_url() {
	$url = wptouch_get_icon_pack_author_url();
	if ( $url ) {
		echo $url;
	}
}

function wptouch_get_icon_pack_author() {
	global $wptouch_icon_pack;

	return $wptouch_icon_pack->author;
}

function wptouch_the_icon_pack_author() {
	echo wptouch_get_icon_pack_author();
}

function wptouch_get_icon_pack_thumbnail() {
	global $wptouch_icon_pack;

	return $wptouch_icon_pack->thumbnail;
}

function wptouch_the_icon_pack_thumbnail() {
	echo wptouch_get_icon_pack_thumbnail();
}

function wptouch_get_icon_pack_dark_bg() {
	global $wptouch_icon_pack;
	return $wptouch_icon_pack->dark_background;
}


function wptouch_the_icon_pack_desc() {
	echo wptouch_get_icon_pack_desc();
}

function wptouch_get_icon_pack_desc() {
	global $wptouch_icon_pack;
	return apply_filters( 'wptouch_icon_pack_desc', $wptouch_icon_pack->description );
}

function wptouch_is_icon_set_enabled() {
	global $wptouch_pro;
	global $wptouch_icon_pack;

	$settings = $wptouch_pro->get_settings();
	if ( isset( $settings->enabled_icon_packs[ $wptouch_icon_pack->name ] ) ) {
		return true;
	} else {
		return false;
	}
}

function wptouch_the_icon_pack_class_name() {
	echo wptouch_get_icon_pack_class_name();
}

function wptouch_get_icon_pack_class_name() {
	global $wptouch_icon_pack;
	return apply_filters( 'wptouch_icon_pack_class_name', $wptouch_icon_pack->class_name );
}

function wptouch_have_icons( $set_name ) {
	global $wptouch_icons_iterator;
	global $wptouch_pro;

	if ( !$wptouch_icons_iterator ) {
		$icons = $wptouch_pro->get_icons_from_packs( $set_name );
		$wptouch_icons_iterator = new WPtouchArrayIterator( $icons );
	}

	$has_items = $wptouch_icons_iterator->have_items();
	if ( !$has_items ) {
		$wptouch_icons_iterator = false;
	}
	return $has_items;
}

function wptouch_the_icon() {
	global $wptouch_icon;
	global $wptouch_icons_iterator;

	$wptouch_icon = $wptouch_icons_iterator->the_item();
	return $wptouch_icon;
}

function wptouch_the_icon_name() {
	echo wptouch_get_icon_name();
}

function wptouch_get_icon_name() {
	global $wptouch_icon;
	return apply_filters( 'wptouch_icon_name', $wptouch_icon->name );
}

function wptouch_the_icon_short_name() {
	echo wptouch_get_icon_short_name();
}

function wptouch_get_icon_short_name() {
	global $wptouch_icon;
	return apply_filters( 'wptouch_icon_short_name', $wptouch_icon->short_name );
}


function wptouch_the_icon_url() {
	echo wptouch_get_icon_url();
}

function wptouch_get_icon_url() {
	global $wptouch_icon;
	return apply_filters( 'wptouch_icon_url', $wptouch_icon->url );
}

function wptouch_the_icon_set() {
	echo wptouch_get_icon_set();
}

function wptouch_get_icon_set() {
	global $wptouch_icon;
	return apply_filters( 'wptouch_icon_set', $wptouch_icon->set );
}


function wptouch_icon_has_image_size_info() {
	global $wptouch_icon;
	return isset( $wptouch_icon->image_size );
}

function wptouch_icon_the_width() {
	echo wptouch_icon_get_width();
}

function wptouch_icon_get_width() {
	global $wptouch_icon;
	return $wptouch_icon->image_size[0];
}

function wptouch_icon_the_height() {
	echo wptouch_icon_get_height();
}

function wptouch_icon_get_height() {
	global $wptouch_icon;
	return $wptouch_icon->image_size[1];
}

function wptouch_the_icon_class_name() {
	echo wptouch_get_icon_class_name();
}

function wptouch_get_icon_class_name() {
	global $wptouch_icon;
	return apply_filters( 'wptouch_icon_class_name', $wptouch_icon->class_name );
}

function wptouch_admin_has_menu_items() {
	global $wptouch_admin_menu_items;
	global $wptouch_admin_menu_iterator;

	wptouch_build_menu_tree( 0, 1, $wptouch_admin_menu_items );

	$wptouch_admin_menu_iterator = new WPtouchArrayIterator( $wptouch_menu_items );

	return $wptouch_admin_menu_iterator->have_items();
}

function wptouch_admin_the_menu_item() {
	global $wptouch_admin_menu_item;
	global $wptouch_admin_menu_iterator;

	if ( $wptouch_admin_menu_iterator ) {
		$wptouch_admin_menu_item = $wptouch_admin_menu_iterator->the_item();
	}
}