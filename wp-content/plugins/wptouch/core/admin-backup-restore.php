<?php

function wptouch_prune_backup_files( $amount = 30 ) {
	require_once( WPTOUCH_DIR . '/core/file-operations.php' );

	$all_files = wptouch_get_files_in_directory( WPTOUCH_BACKUP_DIRECTORY, '.txt' );
	if ( is_array( $all_files ) && count( $all_files ) ) {
		$file_data = array();

		foreach( $all_files as $backup_file ) {
			$file_data[ filemtime( $backup_file ) ] = $backup_file;
		}

		// Sort by file modification time in desc. order
		krsort( $file_data );

		// Determine the files we want to delete
		$files_to_delete = array_diff_key( $file_data, array_slice( $file_data, 0, $amount, true ) );

		foreach( $files_to_delete as $ftime => $file_name ) {
			unlink( $file_name );
		}
	}
}

function wptouch_backup_settings() {
	global $wptouch_pro;

	$backup_domains = $wptouch_pro->get_active_setting_domains();

	if ( is_array( $backup_domains ) && count( $backup_domains ) ) {
		$settings_to_save = array();

		foreach( $backup_domains as $domain ) {
			$settings_notused = wptouch_get_settings( $domain );
			$settings = $wptouch_pro->settings_objects[ $domain ];

			if ( isset( $settings->domain) ) {
				unset( $settings->domain );
			}

			$settings_to_save[ $domain ] = apply_filters( 'wptouch_backup_settings', $settings, $domain );
		}

		ksort( $settings_to_save );

		$backup_string = base64_encode( gzcompress( serialize( $settings_to_save ), 9 ) );

		$backup_base_name = 'wptouch-backup-' . date( 'Ymd-His') . '.txt';
		$backup_file_name = WPTOUCH_BACKUP_DIRECTORY . '/' . $backup_base_name;
		$backup_file = fopen( $backup_file_name, 'w+t' );
		if ( $backup_file ) {
			fwrite( $backup_file, $backup_string );
			fclose( $backup_file );
		}
	}

	wptouch_prune_backup_files();

	return $backup_base_name;
}

function wptouch_restore_settings( $encoded_string ) {
	global $wptouch_pro;

	$encoded_settings = base64_decode( $encoded_string );
	$settings = unserialize( gzuncompress( $encoded_settings ) );

	$wptouch_pro->erase_all_settings();
	if ( is_array( $settings ) ) {
		foreach( $settings as $domain => $settings_object ) {
			$settings_object->domain = $domain;
			$settings_object->save();
		}
	}
}
