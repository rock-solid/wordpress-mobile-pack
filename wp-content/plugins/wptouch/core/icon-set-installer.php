<?php

require_once( ABSPATH . '/wp-admin/includes/class-wp-upgrader.php' );

class WPtouchIconSetSkin extends WP_Upgrader_Skin {
	function header() {}
	function footer() {}
	function error( $errors ) {}
	function feedback( $string ) {}
}

class WPtouchIconSetInstaller extends WP_Upgrader {
	function __construct() {
		$skin = new WPtouchIconSetSkin;
		parent::__construct( $skin );
	}

	function install( $name, $package ) {
		$options = array(
			'package' => $package,
			'destination' => WPTOUCH_BASE_CONTENT_DIR . '/icons/' . $name,
			'clear_destination' => true,
			'clear_working' => true
		);

		$this->run( $options );
	}
}