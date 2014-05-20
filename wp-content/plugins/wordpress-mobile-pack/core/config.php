<?php

define("WMP_VERSION", '2.0');
define("WMP_BASE_THEME", dirname(__FILE__) . '/themes/base'); 

define('WMP_DOMAIN', 'wordpress-mobile-pack');
define('WMP_PLUGIN_PATH', WP_PLUGIN_DIR . '/wordpress-mobile-pack/');
define('WMP_LIBS_DIR', WP_PLUGIN_DIR . '/wordpress-mobile-pack/libs/');
define('WMP_LIBS_URI', WP_PLUGIN_URL . '/wordpress-mobile-pack/libs/');
define('WMP_ADMIN_PATH', WP_PLUGIN_DIR . '/wordpress-mobile-pack/admin');

require_once( dirname( __FILE__ ) . '/config-uploads.php' );
if ( !defined( 'WMP_FILES_UPLOADS_DIR' ) && !defined( 'WMP_FILES_UPLOADS_URL' ) ) {
	wmp_set_uploads_dir();
}

?>
