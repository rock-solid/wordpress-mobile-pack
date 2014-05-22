<?php

define("WMP_VERSION", '2.0');
define("WMP_BASE_THEME", dirname(__FILE__) . '/themes/base'); 

define('WMP_PLUGIN_NAME', 'WP Mobile Pack');
define('WMP_DOMAIN', 'wordpress-mobile-pack');
define('WMP_PLUGIN_PATH', WP_PLUGIN_DIR . '/wordpress-mobile-pack/');
define('WMP_LIBS_DIR', WP_PLUGIN_DIR . '/wordpress-mobile-pack/libs/');
define('WMP_LIBS_URI', WP_PLUGIN_URL . '/wordpress-mobile-pack/libs/');
define('WMP_ADMIN_PATH', WP_PLUGIN_DIR . '/wordpress-mobile-pack/admin');

require_once( dirname( __FILE__ ) . '/config-uploads.php' );
if ( !defined( 'WMP_FILES_UPLOADS_DIR' ) && !defined( 'WMP_FILES_UPLOADS_URL' ) ) {
	wmp_set_uploads_dir();
}

define('WMP_FEEDBACK_EMAIL','florentina@webcrumbz.co');
define('WMP_NEWS_UPDATES','http://dev.webcrumbz.co/~flori/api/news_updates.php');
define('WMP_NEWSLETTER_PATH','http://gateway-dev.appticles.com/newsletter/api/subscribe');
define('WMP_WAITLIST_PATH','http://gateway-dev.appticles.com/waitlist/api/subscribe');


define('CODE_KEY','asdc%/dfr_A!8792*');


?>
