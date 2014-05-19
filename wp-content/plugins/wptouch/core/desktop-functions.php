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
		$contents = wptouch_generate_functions_file( $wptouch_pro, $file_name, $template_path, $current_path );

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

function wptouch_generate_functions_file( $wptouch_pro, $file_name, $template_path, $current_path ) {
	$path_info = pathinfo( $file_name );

	$original_name = $file_name;
	$file_name = $path_info['basename'];

	if ( !file_exists( $original_name ) ) {
		$test_name = $current_path . '/' . $file_name;
		if ( !file_exists( $test_name ) ) {
			$test_name = ABSPATH . '/' . $file_name;
			if ( !file_exists( $test_name ) ) {
				$test_name = $current_path . '/' . $original_name;
				if ( !file_exists( $test_name ) ) {
					die( 'Unable to properly load functions.php from the desktop theme, problem with ' . $test_name );
				} else {
					$file_name = $test_name;
				}
			} else {
				$file_name = $test_name;
			}
		} else {
			$file_name = $test_name;
		}
	} else {
		$file_name = $original_name;
	}

	if ( strpos( $file_name, $template_path ) === FALSE ) {
		return;
	}

	$file_contents = trim( $wptouch_pro->load_file( $file_name ) );

	$already_included_list = array();

	// Replace certain files
	$replace_constants = array( 'TEMPLATEPATH', 'STYLESHEETPATH', 'get_template_directory()' );
	foreach( $replace_constants as $to_replace ) {
		$file_contents = str_replace( $to_replace, "'" . $template_path . "'", $file_contents );
	}

	$file_contents = str_replace( ' bloginfo(', ' wptouch_desktop_bloginfo(', $file_contents );
	$file_contents = str_replace( ' get_bloginfo(', ' wptouch_get_desktop_bloginfo(', $file_contents );

	$include_params = array( 'include', 'include_once', 'require', 'require_once', 'locate_template' );
	foreach( $include_params as $include_param ) {
		$reg_ex = '#' . $include_param . ' *\((.*)\);#';
		if ( preg_match_all( $reg_ex, $file_contents, $match ) ) {
			for( $i = 0; $i < count( $match[0] ); $i++ ) {
				$statement_in_code_that_loads_file = $match[0][$i];

				$new_statement = str_replace( $include_param . ' (', $include_param . '(', $statement_in_code_that_loads_file );

				if ( $include_param == 'locate_template' ) {
					$new_statement = str_replace( $include_param . '(', 'wptouch_locate_template(', $new_statement );

					$new_statement = str_replace( ');', ", '" . $template_path . "', '" . $current_path . "');", $new_statement );

					$file_contents = str_replace( $statement_in_code_that_loads_file, $new_statement, $file_contents );
				} else {

					$current_path = dirname( $file_name );
					$new_statement = str_replace( $include_param . '(', 'wptouch_include_functions_file(', $new_statement );

					$new_statement = str_replace( ');', ", '" . $template_path . "', '" . $current_path . "', '" . $include_param . "');", $new_statement );

					$file_contents = str_replace( $statement_in_code_that_loads_file, $new_statement, $file_contents );
				}
			}
		}
	}

	return $file_contents;
}
