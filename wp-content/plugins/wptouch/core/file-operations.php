<?php

function wptouch_load_file( $file_name ) {
	$contents = '';

	$f = fopen( $file_name, 'rb' );
	if ( $f ) {
		while ( !feof( $f ) ) {
			$new_contents = fread( $f, 8192 );
			$contents = $contents . $new_contents;
		}

		fclose( $f );
	}

	return $contents;
}

function wptouch_copy_file( $src_name, $dst_name ) {
	$src = fopen( $src_name, 'rb' );
	if ( $src ) {
		$dst = fopen( $dst_name, 'w+b' );
		if ( $dst ) {
			while ( !feof( $src ) ) {
				$contents = fread( $src, 8192 );
				fwrite( $dst, $contents );
			}
			fclose( $dst );
		} else {
			WPTOUCH_DEBUG( WPTOUCH_ERROR, 'Unable to open ' . $dst_name . ' for writing' );
		}

		fclose( $src );
	}
}

function wptouch_get_files_in_directory( $directory_name, $extension, $include_dir_name = true ) {
	$files = array();

	$dir = @opendir( $directory_name );

	if ( $dir ) {
		while ( ( $f = readdir( $dir ) ) !== false ) {

			// Skip common files in each directory
			if ( $f == '.' || $f == '..' || $f == '.svn' || $f == '._.DS_Store' || $f == '.DS_Store' ) {
				continue;
			}

			if ( !$extension || strpos( $f, $extension ) !== false ) {
				if ( $include_dir_name ) {
					$files[] = $directory_name . '/' . $f;
				} else {
					$files[] = $f;
				}
			}
		}

		closedir( $dir );
	}

	return $files;
}

function wptouch_remove_directory( $dir_name ) {
	// Check permissions
	if ( current_user_can( 'manage_options' ) ) {
		$dir = @opendir( $dir_name );
		if ( $dir ) {
			while ( $f = readdir( $dir ) ) {
				if ( $f == '.' || $f == '..' ) continue;

				if ( $f == '__MACOSX' ) {
					wptouch_remove_directory( $dir_name . '/' . $f );
				}

				@unlink( $dir_name . '/' . $f );
			}

			closedir( $dir );

			rmdir( $dir_name );
		}
	}
}

function wptouch_recursive_delete( $source_dir ) {
	// Only allow a delete to occur for directories in the main WPtouch data directory
	if ( strpos( $source_dir, '..' ) !== false || strpos( $source_dir, WPTOUCH_BASE_CONTENT_DIR ) === false ) {
		WPTOUCH_DEBUG( WPTOUCH_SECURITY, 'Not deleting directory ' . $source_dir . ' due to possibly security risk' );
		return;
	}

	$src_dir = @opendir( $source_dir );
	if ( $src_dir ) {
		while ( ( $f = readdir( $src_dir ) ) !== false ) {
			if ( $f == '.' || $f == '..' ) {
				continue;
			}

			$cur_file = $source_dir . '/' . $f;
			if ( is_dir( $cur_file ) ) {
				wptouch_recursive_delete( $cur_file );
				@rmdir( $cur_file );
			} else {
				@unlink( $cur_file );
			}
		}

		closedir( $src_dir );

		@rmdir( $source_dir );
	}
}

function wptouch_get_all_recursive_files( $dir, $file_types, $rel_path = '' ) {
	$files = array();

	if ( !is_array( $file_types ) ) {
		$file_types = array( $file_types );
	}

	$d = opendir( $dir );
	if ( $d ) {
		while ( ( $f = readdir( $d ) ) !== false ) {
			if ( $f == '.' || $f == '..' || $f == '.svn' ) continue;

			if ( is_dir( $dir . '/' . $f ) ) {
				$files = array_merge( $files, wptouch_get_all_recursive_files( $dir . '/' . $f, $file_types, $rel_path . '/' . $f ) );
			} else {
				foreach( $file_types as $file_type ) {
					if ( strpos( $f, $file_type ) !== false ) {
						$files[] = $rel_path . '/' . $f;
						break;
					}
				}
			}
		}

		closedir( $d );
	}

	return $files;
}

function wptouch_recursive_copy( $source_dir, $dest_dir ) {
	$src_dir = @opendir( $source_dir );
	if ( $src_dir ) {
		while ( ( $f = readdir( $src_dir ) ) !== false ) {
			if ( $f == '.' || $f == '..' ) {
				continue;
			}

			$cur_file = $source_dir . '/' . $f;
			if ( is_dir( $cur_file ) ) {
				if ( !wp_mkdir_p( $dest_dir . '/' . $f ) ) {
					WPTOUCH_DEBUG( WPTOUCH_WARNING, "Unable to create directory " . $dest_dir . '/' . $f );
				}

				wptouch_recursive_copy( $source_dir . '/' . $f, $dest_dir . '/' . $f );
			} else {
				$dest_file = $dest_dir . '/' . $f;

				$src = @fopen( $cur_file, 'rb' );
				if ( $src ) {
					$dst = fopen( $dest_file, 'w+b' );
					if ( $dst ) {
						while ( !feof( $src ) ) {
							$contents = fread( $src, 8192 );
							fwrite( $dst, $contents );
						}
						fclose( $dst );
					} else {
						WPTOUCH_DEBUG( WPTOUCH_ERROR, 'Unable to open ' . $dest_file . ' for writing' );
					}

					fclose( $src );
				} else {
					WPTOUCH_DEBUG( WPTOUCH_ERROR, 'Unable to open ' . $cur_file . ' for reading' );
				}
			}
		}

		closedir( $src_dir );
	}
}
