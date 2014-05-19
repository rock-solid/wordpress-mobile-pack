<?php

function wptouch_include_functions_file( $file_name, $template_path, $current_path, $load_type ) {
	global $wptouch_pro;

	// Figure out real name of the source file
	$source_file = $file_name;

	if ( !file_exists( $source_file ) ) {
		$source_file = $current_path . '/' . $file_name;
		if ( !file_exists( $source_file ) ) {
			$source_file = $template_path . '/' . $file_name;
			if ( !file_exists( $source_file ) ) {
				echo 'Unable to load desktop functions file';
				die;
			}
		}
	}

	// Determine name of cached file
	$file_info = pathinfo( $source_file );
	$cached_file = $file_info['dirname'] . '/.' . $file_info['basename'] . '.wptouch';

	// Basic caching for generating new functions files
	$generate_new_cached_file = true;
	if ( file_exists( $cached_file ) ) {
		$cached_file_mod_time = filemtime( $cached_file );
		$time_since_last_update = time() - $cached_file_mod_time;

		// Only update once an hour
		if ( $time_since_last_update < WPTOUCH_PRO_DESKTOP_FCN_CACHE_TIME ) {
			$generate_new_cached_file = false;
		}
	}

	// Only generate cached file when it's stale or unavailable
	if ( $generate_new_cached_file ) {
		$contents = $wptouch_pro->include_functions_file( $file_name, $template_path, $current_path );

		$f = fopen( $cached_file, 'wt+' );
		if ( $f ) {
			fwrite( $f, $contents );
			fclose( $f );
		}
	}

	// Load cached file
	switch( $load_type ) {
		case 'include':
			include( $cached_file );
			break;
		case 'include_once';
			include_once( $cached_file );
			break;
		case 'require';
			require( $cached_file );
			break;
		case 'require_once';
			require_once( $cached_file );
			break;
		default:
			break;
	}
}

function wptouch_get_desktop_bloginfo( $param ) {
	switch( $param ) {
		case 'stylesheet_directory':
		case 'template_url':
		case 'template_directory':
			return WP_CONTENT_URL . '/themes/' . get_option( 'template' );
		default:
			return get_bloginfo( $param );
	}
}

function wptouch_desktop_bloginfo( $param ) {
	echo wptouch_get_desktop_bloginfo( $param );
}
