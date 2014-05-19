<?php

global $wptouch_desktop_theme_colors;

function wptouch_get_desktop_theme_colors() {
	global $wptouch_desktop_theme_colors;

	if ( $wptouch_desktop_theme_colors ) {
		return $wptouch_desktop_theme_colors;
	}

	global $wptouch_pro;
	$desktop_colors = array();

	$name = get_option( 'template' );  

	$style_file = $wptouch_pro->load_file( WP_CONTENT_DIR . '/themes/' . $name . '/style.css' );

	$result = preg_match_all( '!color: (.*);!U', $style_file, $matches );
	if ( $result ) {
		foreach( $matches[1] as $color ) {
			if ( !in_array( $color, $desktop_colors ) ) {
				
				if ( stripos( $color, '#' ) !== false ) {
					$desktop_colors[] = $color;					
				}
			}
		}
	}		

	$wptouch_desktop_theme_colors = $desktop_colors;

	return $desktop_colors;
}

require_once( 'text.php' );
