<?php

require_once( ABSPATH . '/wp-admin/includes/class-wp-upgrader.php' );
require_once( ABSPATH . '/wp-admin/includes/file.php' );

class WPtouchAddonThemeInstaller {
	var $had_error;
	var $error_text;
	var $use_pclzip;
	var $use_curl;
	var $use_ftp;

	function __construct() {
		$this->had_error = false;
		$this->error_text = false;

		$this->force_use_pclzip = false;
		$this->force_use_curl = false;
	}

	function error_text() {
		return $this->error_text;
	}

	private function supports_curl_download() {
		return extension_loaded( 'curl' );
	}

	private function supports_url_download() {
		return ini_get( 'allow_url_fopen' );
	}

	private function supports_zip() {
		return extension_loaded( 'zip' );
	}

	private function supports_pclzip() {
		return file_exists( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
	}

	private function supports_ftp() {
		return function_exists( 'ftp_connect' );
	}

	private function add_error( $desc ) {
		$this->had_error = true;
		$this->error_text = $desc;
	}

	private function download_method() {
		if ( $this->supports_curl_download() && ( $this->force_use_curl || !$this->supports_url_download() ) ) {
			return 'CURL';
		} else return 'DIRECT';
	}

	private function can_download_directly() {
		return $this->supports_url_download() || $this->supports_curl_download();
	}

	private function can_install_directly( $path ) {
		$destination_dir = WPTOUCH_BASE_CONTENT_DIR . '/' . $path;

		return is_writable( $destination_dir );
	}

	private function can_unzip() {
		return $this->supports_zip() || $this->supports_pclzip();
	}

	private function unzip_file( $file_name, $destination ) {
		if ( class_exists( 'ZipArchive' ) && !$this->force_use_pclzip ) {
			$zip = new ZipArchive;
			$zip->open( $file_name );
			$zip->extractTo( $destination );
			$zip->close();

			return true;
		} else {
			require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );

			$zip = new PclZip( $file_name );
			$result = $zip->extract( PCLZIP_OPT_PATH, $destination );
		}
	}

	private function get_writable_temp_directory( $alternate_path ) {
		$temp_dir = sys_get_temp_dir();
		if ( is_writable( $temp_dir ) ) {
			return $temp_dir;
		}

		$temp_dir = '/tmp';
		if ( is_writable( $temp_dir ) ) {
			return $temp_dir;
		}

		return $alternate_path;
	}

	private function download_file( $package, $alternate_path ) {
		$method = $this->download_method();

		if ( $method == 'CURL' ) {
			$temp_dir = $this->get_writable_temp_directory( $alternate_path );
			$temp_name = tempnam( $temp_dir, 'wptouch-' );

			$curl = curl_init( $package );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
			$data = curl_exec( $curl );
			curl_close( $curl );

			$f = fopen( $temp_name, 'wb' );
			if ( $f ) {
				fwrite( $f, $data );
				fclose( $f );

				return $temp_name;
			}
		} else if ( $method == 'DIRECT' ) {
			$source_file = fopen( $package, 'rb' );
			if ( $source_file ) {
				$temp_dir = $this->get_writable_temp_directory( $alternate_path );
				$temp_name = tempnam( $temp_dir, 'wptouch-' );

				$dest_file = fopen( $temp_name, 'wb' );
				if ( $dest_file ) {
					while( !feof( $source_file ) ) {
						$data = fread( $source_file, 8192 );
						if ( $data ) {
							fwrite( $dest_file, $data );
						}
					}

					fclose( $dest_file );
					fclose( $source_file );

					return $temp_name;
				}

				fclose( $source_file );
			}
		}

		return false;
	}

	function requires_ftp() {
		return !$this->can_install_directly();
	}

	function can_perform_install( $path ) {
		return $this->can_download_directly() && $this->can_install_directly( $path ) && $this->can_unzip();
	}

	function had_error() {
		return $this->had_error;
	}

	function install( $name, $package, $path ) {
		if ( !$this->can_download_directly() ) {
			$this->add_error( __( "No server support for directly downloading new Cloud packages.", "wptouch-pro" ) );
			return false;
		}

		if ( !$this->can_install_directly( $path ) ) {
			$this->add_error( sprintf( __( "Unable to write to directory %s. Try relaxing permissions to allow writing to this location.", "wptouch-pro" ), WPTOUCH_BASE_CONTENT_DIR . '/'. $path ) );
			return false;
		}

		if ( !$this->can_unzip() ) {
			$this->add_error( __( "No server support for unzipping files.", "wptouch-pro" ) );
			return false;
		}

		$file_name = $this->download_file( $package, WPTOUCH_BASE_CONTENT_DIR . '/' . $path );
		if ( $file_name ) {
			$this->unzip_file( $file_name, WPTOUCH_BASE_CONTENT_DIR . '/' . $path );

			@unlink( $file_name );
		} else {
			$this->add_error( __( "Unable to download the Cloud package.", "wptouch-pro" ) );
			return false;
		}
	}
};