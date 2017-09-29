<?php

define("WMP_VERSION", '3.2');
define('WMP_PLUGIN_NAME', 'WP Mobile Pack');
define('WMP_DOMAIN', 'wordpress-mobile-pack');

define('WMP_PLUGIN_PATH', WP_PLUGIN_DIR . '/'.WMP_DOMAIN.'/');

define('WMP_FEEDBACK_EMAIL','feedback@appticles.com');

define('WMP_NEWS_UPDATES','http://cdn-wpmp.appticles.com/dashboard/news.json');
define('WMP_NEWS_UPDATES_HTTPS','https://d3oqwjghculspf.cloudfront.net/dashboard/news.json');

define('WMP_WHATSNEW_UPDATES','http://cdn-wpmp.appticles.com/dashboard/quick_start/content.json');
define('WMP_WHATSNEW_UPDATES_HTTPS','https://d3oqwjghculspf.cloudfront.net/dashboard/quick_start/content_https.json');

define('WMP_MORE_UPDATES','http://cdn-wpmp.appticles.com/dashboard/more/more4.json');
define('WMP_MORE_UPDATES_HTTPS','https://d3oqwjghculspf.cloudfront.net/dashboard/more/more4.json');
define('WMP_MORE_UPDATES_VERSION', 4);

define('WMP_WAITLIST_PATH','http://gateway.appticles.com/waitlist/api/subscribe');
define('WMP_WAITLIST_PATH_HTTPS','https://gateway.appticles.com/waitlist/api/subscribe');

// define connect with appticles path
define('WMP_APPTICLES_CONNECT','http://api.appticles.com/content1/wpconnect');
define('WMP_APPTICLES_CONNECT_SSL','https://api.appticles.com/content1/wpconnect');

define('WMP_APPTICLES_DISCONNECT','http://api.appticles.com/content1/wpdisconnect');
define('WMP_APPTICLES_DISCONNECT_SSL','https://api.appticles.com/content1/wpdisconnect');

define('WMP_APPTICLES_TRACKING_SSL','https://api.appticles.com/content1/wptracking');

define("WMP_APPTICLES_PREVIEW_DOMAIN", "app.appticles.com");

define("WMP_APPTICLES_PRO_LINK", "https://wpmobilepack.com");

// define the string used for generating comments tokens (can be overwritten for increasing security)
define('WMP_CODE_KEY','asdc%/dfr_A!8792*');

?>
