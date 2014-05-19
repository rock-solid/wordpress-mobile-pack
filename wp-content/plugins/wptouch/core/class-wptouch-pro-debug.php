<?php

global $wptouch_pro_debug;

define( 'WPTOUCH_ERROR', 1 );
define( 'WPTOUCH_SECURITY', 2 );
define( 'WPTOUCH_WARNING', 3 );
define( 'WPTOUCH_INFO', 4 );
define( 'WPTOUCH_VERBOSE', 5 );
define( 'WPTOUCH_ALL', 6 );

function wptouch_debug_get_filename() {
	$settings = wptouch_get_settings();

	return date( 'Ymd' ) . '-' . $settings->debug_log_salt . '.txt';
}

class WPtouchProDebug {
	var $debug_file;
	var $enabled;
	var $log_level;

	function WPtouchProDebug() {
		$this->debug_file = false;
		$this->enabled = false;
	}

	function enable() {
		$this->enabled = true;

		// Create the debug file
		if ( !$this->debug_file ) {
			$this->debug_file = fopen( WPTOUCH_DEBUG_DIRECTORY . '/' . wptouch_debug_get_filename(), 'a+t' );
		}
	}

	function disable() {
		$this->enabled = false;

		// Close the debug file
		if ( $this->debug_file ) {
			fclose( $this->debug_file );
			$this->debug_file = false;
		}
	}

	function set_log_level( $level ) {
		$this->log_level = $level;
	}

	function add_to_log( $level, $msg ) {
		if ( $this->enabled && $level <= $this->log_level ) {
			$message = sprintf( date( 'g:i:sa', time() + ( get_option( 'gmt_offset' )*3600 ) ) ) . ' - ' . microtime( true ) . ' : ';

			switch( $level ) {
				case WPTOUCH_ERROR:
					$message .= '[error]';
					break;
				case WPTOUCH_SECURITY:
					$message .= '[security]';
					break;
				case WPTOUCH_WARNING:
					$message .= '[warning]';
					break;
				case WPTOUCH_INFO:
					$message .= '[info]';
					break;
				case WPTOUCH_VERBOSE:
					$message .= '[verbose]';
					break;
			}

			$message .= '[Process: ' . getmypid() . ']';

			// Lock the debug file for writing so multiple PHP processes don't mangle it
			if ( flock( $this->debug_file, LOCK_EX ) ) {
				fwrite( $this->debug_file, $message . ': ' . $msg . "\n" );
				flock( $this->debug_file, LOCK_UN );
			}
		}
	}
}

$wptouch_pro_debug = new WPtouchProDebug;

function WPTOUCH_DEBUG( $level, $msg ) {
	global $wptouch_pro_debug;

	$wptouch_pro_debug->add_to_log( $level, $msg );
}

function wptouch_debug_enable( $enable_or_disable ) {
	global $wptouch_pro_debug;

	if ( $enable_or_disable ) {
		$wptouch_pro_debug->enable();
	} else {
		$wptouch_pro_debug->disable();
	}
}

function wptouch_debug_set_log_level( $level ) {
	global $wptouch_pro_debug;

	$wptouch_pro_debug->set_log_level( $level );
}
