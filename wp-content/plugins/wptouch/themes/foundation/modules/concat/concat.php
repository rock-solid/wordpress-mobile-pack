<?php

add_action( 'wptouch_body_bottom', 'foundation_setup_concat' );

function foundation_setup_concat() {
	// Added check to make sure cache directory is writable before attempting to write concat
	if ( is_writable( WPTOUCH_BASE_CONTENT_DIR . '/cache' ) ) {
		ob_start( 'foundation_do_concat' );	
	}
}

function foundation_do_concat( $contents ) {
	$expression = "#" . preg_quote( "<script type='text/javascript' src='" . WPTOUCH_URL ) . "(.*)" . preg_quote( "'></script>" ) . "#i";
	$result = preg_match_all( $expression, $contents, $matches );
	if ( $result ) {
		$actual_files = array();

		$new_contents = '';

		// Actual files
		foreach( $matches[1] as $file_name ) {
			if ( strpos( $file_name, '?' ) !== false ) {
				$temp_file = explode( '?', $file_name );
				$file_name = $temp_file[0];
			} 

			$actual_files[] = WPTOUCH_DIR . $file_name;
		}

		// Compute hash
		$hash_string = '';
		foreach( $actual_files as $file ) {
			if ( file_exists( $file ) ) {
				$hash_string = $hash_string . $file . filemtime( $file );	
			} else {
				$hash_string = $hash_string . $file;
			}
		}

		$cache_file_suffix = '/cache/' . 'wptouch-' . sha1( $hash_string ) . '.js';
		$cache_file_name = WPTOUCH_BASE_CONTENT_DIR . $cache_file_suffix;

		if ( !file_exists( $cache_file_name ) ) {
			$cache_file = fopen( $cache_file_name, 'w+t' );
			if ( $cache_file ) {
				global $wptouch_pro;

				foreach( $actual_files as $actual_file ) {
					$file_contents = $wptouch_pro->load_file( $actual_file );
					fwrite( $cache_file, $file_contents . "\n" );
				}
				
				fclose( $cache_file );
			}
		}

		for ( $i = 0; $i < count( $matches[0] ) - 1; $i++ ) {
			$contents = str_replace( $matches[0][$i], '', $contents );
		}

		$contents = str_replace( $matches[0][ count( $matches[0] ) - 1 ], "<script type='text/javascript' src='" . WPTOUCH_BASE_CONTENT_URL . $cache_file_suffix . "'></script>", $contents );
	}

	return $contents;
}