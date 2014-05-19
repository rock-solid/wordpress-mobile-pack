<?php

function wptouch_create_directory_if_not_exist( $dir ) {
	if ( !file_exists( $dir ) ) {
		// Try and make the directory
		return @wp_mkdir_p( $dir );
	}

	return true;
}

function wptouch_setup_base_content_dir() {
	global $blog_id;
	global $wptouch_pro;

	$wptouch_upload_dir = wp_upload_dir();

	// Where we want the base content directory to be
	$desirable_dir = '/wptouch-data';

	if ( is_multisite() ) {
		define( 'WPTOUCH_BASE_CONTENT_MS_DIR', WP_CONTENT_DIR . $desirable_dir );
		define( 'WPTOUCH_BASE_CONTENT_MS_URL', WP_CONTENT_URL . $desirable_dir );

		if ( $blog_id ) {
			wptouch_create_directory_if_not_exist( WPTOUCH_BASE_CONTENT_MS_DIR );
			$desirable_dir = $desirable_dir . '/' . $blog_id;
		}
	}

	$undesirable_dir = $wptouch_upload_dir[ 'basedir' ] . '/wptouch-data';
	if ( file_exists( $undesirable_dir ) ) {
		// Need to migrate here
		//define( 'WPTOUCH_BASE_CONTENT_DIR', $wptouch_upload_dir[ 'basedir' ] . '/wptouch-data' );
		//define( 'WPTOUCH_BASE_CONTENT_URL', wptouch_check_url_ssl( $wptouch_upload_dir[ 'baseurl' ] . '/wptouch-data' ) );
		wptouch_create_directory_if_not_exist( WP_CONTENT_DIR . $desirable_dir );

		$migration_paths = array( 'themes', 'icons', 'lang', 'uploads', 'add-ons', 'backups' );
		foreach( $migration_paths as $path ) {
			if ( file_exists( $undesirable_dir . '/' . $path ) ) {
				if ( !file_exists( WP_CONTENT_DIR . $desirable_dir . '/' . $path ) ) {
					// This is a fresh migration, so let's just move it
					if ( !rename( $undesirable_dir . '/' . $path, WP_CONTENT_DIR . $desirable_dir . '/' . $path ) ) {
						define( 'WPTOUCH_MIGRATION_OLD_ISSUE', 1 );
					}
				}
			}
		}
	}

	define( 'WPTOUCH_BASE_CONTENT_DIR', WP_CONTENT_DIR . $desirable_dir );
	define( 'WPTOUCH_BASE_CONTENT_URL', WP_CONTENT_URL . $desirable_dir );
}