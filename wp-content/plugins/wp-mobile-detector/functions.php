<?php
add_filter('plugin_action_links', 'websitez_settings_link', 10, 2 );

function websitez_settings_link( $links, $file ) {
 	if( $file == 'wp-mobile-detector/websitez-wp-mobile-detector.php' && function_exists( "admin_url" ) ) {
		$settings_link = '<a href="' . admin_url( 'admin.php?page=websitez_config' ) . '">' . __('Settings') . '</a>';
		array_push( $links, $settings_link ); // after other links
	}
	return $links;
}

add_action('websitez_manage_stats_prune', 'websitez_manage_stats_prune_do');

function websitez_manage_stats(){
	if( !wp_next_scheduled( 'websitez_manage_stats_prune' ) ):
  	wp_schedule_event( time(), 'daily', 'websitez_manage_stats_prune' );
  endif;
}

function websitez_manage_stats_prune_do(){
	global $wpdb;
	$table_name = WEBSITEZ_STATS_TABLE;
	//Delete stats more than 30 days old.
	//Placing a limit of 50000 to prevent systems with a huge stats table from crashing.
	$delete = $wpdb->query("DELETE FROM $table_name WHERE created_at < '".date("Y-m-d 00:00:00", strtotime("-1 month"))."' LIMIT 50000");
}

function websitez_dashboard_setup(){
	$websitez_show_dashboard_widget = get_option(WEBSITEZ_SHOW_DASHBOARD_WIDGET_NAME);
	if($websitez_show_dashboard_widget == "true"):
		wp_add_dashboard_widget('websitez_dashboard_widget', 'WP Mobile Detector Updates', 'websitez_dashboard_widget_function');
		global $wp_meta_boxes;
		
		// Get the regular dashboard widgets array 
		// (which has our new widget already but at the end)
	
		$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
		
		// Backup and delete our new dashbaord widget from the end of the array
	
		$example_widget_backup = array('websitez_dashboard_widget' => $normal_dashboard['websitez_dashboard_widget']);
		unset($normal_dashboard['websitez_dashboard_widget']);
	
		// Merge the two arrays together so our widget is at the beginning
	
		$sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);
	
		// Save the sorted array back into the original metaboxes 
	
		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	endif;
}

function websitez_dashboard_widget_function(){
	$data = websitez_remote_request("http://websitez.com/api/websitez-wp-mobile-detector/dashboard-feed.php","");
	echo $data;
}

function websitez_get_mobile_device(){
	global $websitez_mobile_device;
	return $websitez_mobile_device;
}
function websitez_set_mobile_device($mobile_device){
	global $websitez_mobile_device;
	$websitez_mobile_device = $mobile_device;
}
/*
Validate the mobile theme
*/
function validate_current_mobile_theme($template_name = null, $template_path = null) {
	// Don't validate during an install/upgrade.
	if ( defined('WP_INSTALLING') || !apply_filters( 'validate_current_theme', true ) )
		return true;

	if ( $template_name != WP_DEFAULT_THEME && !file_exists($template_path . '/index.php') ) {
		switch_theme( WEBSITEZ_INSTALL_ADVANCED_THEME, WEBSITEZ_INSTALL_ADVANCED_THEME );
		return false;
	}

	if ( $template_name != WP_DEFAULT_THEME && !file_exists($template_path . '/style.css') ) {
		switch_theme( WEBSITEZ_INSTALL_ADVANCED_THEME, WEBSITEZ_INSTALL_ADVANCED_THEME );
		return false;
	}

	return true;
}

/*
Insert proper meta tags for caching and attribution
*/
function websitez_wordpress_generator($generator) {
	$headers = "\n";
	$headers .= '<meta http-equiv="Cache-Control" content="max-age=200" />';
	$headers .= "\n";
	$headers .= '<meta name="generator" content="WordPress ' . get_bloginfo( 'version' ) . ' - Mobile Detection by '.WEBSITEZ_PLUGIN_NAME.'" />';
	$headers .= "\n";
  return $headers;
}
/*
Send header to let them requester know that it was mobilized
*/
function websitez_send_headers($wp) {
  @header("X-Mobilized-By: ".WEBSITEZ_PLUGIN_NAME);
}

/*
This will style a dynamic sidebar if one is created by the website owner
*/
function websitez_reclamation_sidebar_params($params){
	$params[0]['before_widget'] = '<div class="wrapper"><div class="ui-body ui-body-f"><div data-role="collapsible" data-theme="a">';
	$params[0]['before_title'] = '<h3>';
	if($params[0]['widget_name'] == "Calendar" || $params[0]['widget_name'] == "Text" || $params[0]['widget_name'] == "Tag Cloud"){
		$params[0]['after_title'] = '</h3><p>';
		$params[0]['after_widget'] = '</p></div></div></div>';
	}else{
		$params[0]['after_title'] = '</h3><ul data-role="listview" data-inset="true" data-theme="c">';
		$params[0]['after_widget'] = '</ul></div></div></div>';
	}
	
	return $params;
} 
/*
This is called on activation of the plugin
*/
function websitez_install(){
	global $wpdb, $websitez_free_version, $websitez_preinstalled_templates, $table_prefix;
	
	/*Always ping on installation
	$domain = $_SERVER['HTTP_HOST'];
	$tmp = explode(".",$domain);
	$count = count($tmp);
	if($count > 2){
		$token = $tmp[($count-2)].".".$tmp[($count-1)];
	}else{
		$token = $domain;
	}
	$authorization = unserialize(file_get_contents("http://mobile.websitez.com/authorize.php?token=".rawurlencode($token)));*/
	
	/*
	Insert the proper values into the db
	*/
	if($websitez_free_version == true || $authorization['status'] == "1"){
		/*
		Setup the stats table to record mobile visits
		*/
		$table_name = WEBSITEZ_STATS_TABLE;
		if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
			$sql = "CREATE TABLE " . $table_name . " (
				id int(11) NOT NULL AUTO_INCREMENT,
				data text NOT NULL,
				device_type int(2) NOT NULL,
				created_at DATETIME NOT NULL,
				UNIQUE KEY id (id),
				PRIMARY KEY(created_at)
				);";
		
		  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		  dbDelta($sql);
		}
		
		//Keeping the URL redirect code, but waiting to implement it pending research. TODO
		/*if(!get_option(WEBSITEZ_BASIC_URL_REDIRECT))
			add_option(WEBSITEZ_BASIC_URL_REDIRECT, '', '', 'yes');
		
		if(!get_option(WEBSITEZ_ADVANCED_URL_REDIRECT))
			add_option(WEBSITEZ_ADVANCED_URL_REDIRECT, '', '', 'yes');*/
		
		if(!get_option(WEBSITEZ_SHOW_MOBILE_ADS_NAME))
			add_option(WEBSITEZ_SHOW_MOBILE_ADS_NAME, WEBSITEZ_SHOW_MOBILE_ADS, '', 'yes');
		
		if(!get_option(WEBSITEZ_SHOW_MOBILE_TO_TABLETS_NAME))
			add_option(WEBSITEZ_SHOW_MOBILE_TO_TABLETS_NAME, WEBSITEZ_SHOW_MOBILE_TO_TABLETS, '', 'yes');
		
		if(!get_option(WEBSITEZ_SHOW_DASHBOARD_WIDGET_NAME))
			add_option(WEBSITEZ_SHOW_DASHBOARD_WIDGET_NAME, WEBSITEZ_SHOW_DASHBOARD_WIDGET, '', 'yes');
		
		if(!get_option(WEBSITEZ_RECORD_STATS_NAME))
			add_option(WEBSITEZ_RECORD_STATS_NAME, WEBSITEZ_RECORD_STATS, '', 'yes');
		
		if(!get_option(WEBSITEZ_USE_PREINSTALLED_THEMES_NAME))
			add_option(WEBSITEZ_USE_PREINSTALLED_THEMES_NAME, WEBSITEZ_USE_PREINSTALLED_THEMES, '', 'yes');
		
		if(!get_option(WEBSITEZ_SHOW_ATTRIBUTION_NAME))
			add_option(WEBSITEZ_SHOW_ATTRIBUTION_NAME, WEBSITEZ_SHOW_ATTRIBUTION, '', 'yes');
			
		if(!get_option(WEBSITEZ_BASIC_THEME)){
			if(WEBSITEZ_USE_PREINSTALLED_THEMES == "true")
				add_option(WEBSITEZ_BASIC_THEME, WEBSITEZ_INSTALL_BASIC_THEME, '', 'yes');
			else
				add_option(WEBSITEZ_BASIC_THEME, WEBSITEZ_DEFAULT_THEME, '', 'yes');
		}
		if(!get_option(WEBSITEZ_ADVANCED_THEME)){
			if(WEBSITEZ_USE_PREINSTALLED_THEMES == "true")
				add_option(WEBSITEZ_ADVANCED_THEME, WEBSITEZ_INSTALL_ADVANCED_THEME, '', 'yes');
			else
				add_option(WEBSITEZ_ADVANCED_THEME, WEBSITEZ_DEFAULT_THEME, '', 'yes');
		}
	}
}

/*
Remove all traces of the plugin
This is not currently in use, but may be implemented TODO
*/
function websitez_uninstall(){
	global $wpdb;
	if(get_option(WEBSITEZ_BASIC_THEME))
		delete_option(WEBSITEZ_BASIC_THEME);
	if(get_option(WEBSITEZ_ADVANCED_THEME))
		delete_option(WEBSITEZ_ADVANCED_THEME);
	if(get_option(WEBSITEZ_RECORD_STATS_NAME))
		delete_option(WEBSITEZ_RECORD_STATS_NAME);
	
	$table_name = WEBSITEZ_STATS_TABLE;//TODO
	if($wpdb->get_var("show tables like '$table_name'") == $table_name) {
		$sql = "DROP TABLE ".$table_name;
		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		dbDelta($sql);
	}
}

/*
Start the buffer to filter the raw contents of a page
*/
function websitez_basic_buffer(){
	//Don't filter Dashboard pages and the feed
	if (is_feed() || is_admin()){
		return;
	}

	ob_start("websitez_filter_basic_page");
}

/*
Start the buffer to filter the raw contents of a page
*/
function websitez_advanced_buffer(){
	//Don't filter Dashboard pages and the feed
	if (is_feed() || is_admin()){
		return;
	}
	
	ob_start("websitez_filter_advanced_page");
}

/*
Filter content for an advanced mobile device
*/
function websitez_filter_advanced_page($html){
	if (class_exists('DOMDocument')) {
		try{
			//Resize the images on the page
			$dom = new DOMDocument();
			$dom->loadHTML($html);
			
			// grab all the on the page and make sure they are the right size
			$xpath = new DOMXPath($dom);
			$imgs = $xpath->evaluate("/html/body//img");
			
			for ($i = 0; $i < $imgs->length; $i++) {
				$img = $imgs->item($i);
				$src = trim($img->getAttribute('src'));
				$img->removeAttribute('width');
				$img->removeAttribute('height');
				//Use dynamic image resizer link
				if(strlen($src) > 0){
					$max_width = WEBSITEZ_ADVANCED_MAX_IMAGE_WIDTH;
					list($width, $height) = getimagesize($src);
					$blog_url = get_bloginfo('siteurl');
					if($width > $max_width){
						if(stripos($src,$blog_url) !== false):
							$arr = explode("/",$src);
							if(count($arr) > 4):
								unset($arr[0]);
								unset($arr[1]);
								unset($arr[2]);
								$src = "/".implode("/",$arr);
							endif;
						endif;
						$tmp = parse_url($src);
						if(strlen($tmp['host']) > 0):
							$path = $tmp['scheme']."://".$tmp['host'].$tmp['path'];
						else:
							$path = $tmp['path'];
						endif;
						$resize = plugin_dir_url(__FILE__)."/timthumb.php?src=".urlencode($path)."&w=".$max_width;
						$img->setAttribute('src', $resize);
					}
				}
			}
			
			$stuff = $dom->saveHTML();
		}catch(Exception $e){
			$stuff = $html;
		}
	}else{
		$stuff = $html;
	}
	
	return $stuff;
}

/*
Filter content for a basic mobile device
*/
function websitez_filter_basic_page($html){
	global $websitez_preinstalled_templates;
	
	if (class_exists('DOMDocument')) {
		//Resize the images on the page
		$dom = new DOMDocument();
		$dom->loadHTML($html);
	
		// grab all the on the page and make sure they are the right size
		$xpath = new DOMXPath($dom);
		$divs = $xpath->evaluate("/html/body//div");
	
		for ($i = 0; $i < $divs->length; $i++) {
			$div = $divs->item($i);
			$div->removeAttribute('data-role');
			$div->removeAttribute('data-theme');
			$div->removeAttribute('style');
			$div->removeAttribute('data-icon');
			$div->removeAttribute('data-iconpos');
			$div->removeAttribute('onclick');
			$div->removeAttribute('data-state');
		}
	
		$links = $xpath->evaluate("/html/body//a");
	
		for ($i = 0; $i < $links->length; $i++) {
			$link = $links->item($i);
			$link->removeAttribute('data-inline');
			$link->removeAttribute('data-role');
			$link->removeAttribute('data-theme');
			$link->removeAttribute('style');
			$link->removeAttribute('data-icon');
			$link->removeAttribute('data-iconpos');
			$link->removeAttribute('onclick');
		}
	
		$uls = $xpath->evaluate("/html/body//ul");
	
		for ($i = 0; $i < $uls->length; $i++) {
			$ul = $uls->item($i);
			$ul->removeAttribute('data-inline');
			$ul->removeAttribute('data-role');
			$ul->removeAttribute('data-theme');
			$ul->removeAttribute('data-inset');
			$ul->removeAttribute('style');
			$ul->removeAttribute('data-icon');
			$ul->removeAttribute('data-iconpos');
			$ul->removeAttribute('onclick');
		}
	
		$htmls = $xpath->evaluate("/html");
	
		for ($i = 0; $i < $htmls->length; $i++) {
			$h = $htmls->item($i);
			$h->removeAttribute('dir');
			$h->removeAttribute('lang');
		}
	
		$text = $dom->saveHTML();
	}else{
		$text = $html;
	}
	
	$text = preg_replace(
	  array(
	  	// Remove invisible content
	  	'@<meta[^>]*?>@siu',
	  	'@<link[^>]*?>@siu',
	  	'@<form[^>]*?>.*?</form>@siu',
	    '@<style[^>]*?>.*?</style>@siu',
	    '@<script[^>]*?.*?</script>@siu',
	    '@<object[^>]*?.*?</object>@siu',
	    '@<embed[^>]*?.*?</embed>@siu',
	    '@<applet[^>]*?.*?</applet>@siu',
	    '@<noframes[^>]*?.*?</noframes>@siu',
			'@<iframe[^>]*?.*?</iframe>@siu',
	    '@<noscript[^>]*?.*?</noscript>@siu',
	    '@<noembed[^>]*?.*?</noembed>@siu',
			// Remove visible content
			'@<img[^>]*?>@siu',
	  	// Add line breaks before and after blocks
	    '@</?((address)|(blockquote)|(center)|(del))@iu',
	    '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
	    '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
	    '@</?((table)|(th)|(td)|(caption))@iu',
	    '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
	    '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
	    '@</?((frameset)|(frame)|(iframe))@iu',
	  ),
	  array(
	    ' ',' ',' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
	    "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
	    "\n\$0", "\n\$0",
	  ),
	  $text );
  //If it is a websitez template, run the basic device stylesheet
  if($websitez_preinstalled_templates == "true")
  	$text = str_replace("</head>","<link rel='stylesheet' href='".get_bloginfo('stylesheet_directory')."/basic-device.css' />\n</head>\n",$text);
	
	$text = preg_replace('/\s\s+/', '', $text);
  $text = preg_replace('/<!--(.*?)-->/', '', $text);
  $text = preg_replace('/\n/', '', $text);
		
	return $text;
}

/*
When in the admin area, this will alert the admin if the plugin is not installed properly
*/
function websitez_checkInstalled(){
	global $wpdb,$table_prefix,$websitez_free_version;
	if(isset($_GET['websitez-plugin-notice'])):
		update_option('WEBSITEZ_OTHER_PLUGINS_CHECK', 'false');
	endif;
	$table = $table_prefix."options";
	if(!get_option(WEBSITEZ_BASIC_THEME) || !get_option(WEBSITEZ_ADVANCED_THEME)){
		if($websitez_free_version == true){
			add_action('admin_notices', create_function( '', "echo '<div class=\"error\"><p>".WEBSITEZ_PLUGIN_NAME." was unable to install correctly. Please try deactivating and then activating this plugin again.</p><p><strong>If you still have trouble, please contact support@websitez.com</strong></p></div>';" ) );
		}else{
			add_action('admin_notices', create_function( '', "echo '<div class=\"error\"><p>".WEBSITEZ_PLUGIN_NAME." was unable to install correctly. This domain is not authorized to use this plugin.</p><p><strong>Please contact support@websitez.com</strong></p></div>';" ) );
		}
	}
	$plugin_notice = false;
	$plugins = get_option('active_plugins');
	foreach($plugins as $plugin):
		if(stripos($plugin,"w3-total-cache") !== false):
			$plugin_notice = true;
		endif;
	endforeach;
	if(get_option('WEBSITEZ_OTHER_PLUGINS_CHECK') == 'false'):
		$plugin_notice = false;
	endif;
	if($plugin_notice):
		add_action('admin_notices', create_function( '', "echo '<div class=\"error\"><p>There are plugins installed that require slight modifications to work with the <strong>".WEBSITEZ_PLUGIN_NAME."</strong> plugin. Please read this short blog post that will help you resolve these issues quickly: <a href=\"http://websitez.com/resolving-plugin-conflicts-with-wp-mobile-detector/\" target=\"_blank\">http://websitez.com/resolving-plugin-conflicts-with-wp-mobile-detector/</a></p><p><a href=\"?websitez-plugin-notice=hide\">Hide This Notice</a></p></div>';" ) );
	endif;
	$cache = WEBSITEZ_PLUGIN_DIR.'/cache/';
	$permissions = substr(sprintf('%o', fileperms($cache)), -4);
	if($permissions != "0777"):
		add_action('admin_notices', create_function( '', "echo '<div class=\"error\"><p>Please set the permissions on this folder (".$cache.") to be 777 to allow the <strong>WP Mobile Detector</strong> to work properly.</p><p>Execute the following command via SSH:<br><strong>chmod 777 ".$cache."</strong></p></div>';" ) );
	endif;
}

function websitez_check_monetization(){
	global $wpdb,$table_prefix,$websitez_free_version;
	$table = $table_prefix."options";
	if($_GET['page'] == "websitez_config" || $_GET['page'] == "websitez_stats" || $_GET['page'] == "websitez_themes" || $_GET['page'] == "websitez_monetization"):
		$monetization = get_option(WEBSITEZ_SHOW_MOBILE_ADS_NAME);
		if($monetization == "false"):
			$time = strtotime("+3 months", strtotime(get_option(WEBSITEZ_MONETIZATION_MESSAGE)));
			$date = date("Y-m-d H:i:s", $time);
			$current = date("Y-m-d H:i:s");
			if($current >= $date):
				add_action('admin_notices', create_function( '', "echo '<div class=\"error\"><p><strong><a href=\"admin.php?page=websitez_monetization\">".WEBSITEZ_PLUGIN_NAME." Monetization is disabled!</a></strong> You can <a href=\"admin.php?page=websitez_monetization&monetization=true\">enable monetization</a> or <a href=\"admin.php?page=websitez_monetization&hide=true\">hide</a> this message.</p></div>';" ) );
			endif;
		endif;
	endif;
}

/*
Check to make sure authorization token is set.
*/
function websitez_authorization(){
	$token = get_option(WEBSITEZ_PLUGIN_AUTHORIZATION);
	if(!$token):
		$response = unserialize(websitez_remote_request("http://stats.websitez.com/get_token.php","host=".$_SERVER['HTTP_HOST']."&email=".get_option('admin_email')."&source=wp-mobile-detector"));
		if($response && $response['status'] == "1" && strlen($response['token']) > 0):
			update_option(WEBSITEZ_PLUGIN_AUTHORIZATION,$response['token']);
		endif;
	endif;
}

function websitez_set_mobile_ads_buffer_append($html){
	if(class_exists('DOMDocument')):
		try{
			$domain_token = get_option(WEBSITEZ_PLUGIN_AUTHORIZATION);
			$dom = new DOMDocument();
			$xpath = new DOMXPath($dom);
			$dom->loadHTML($html);
			$body = $dom->getElementsByTagName('body')->item(0);

	  	/* ad */
	  	//http_build_query($_SERVER)
	  	$ad_html = websitez_remote_request("http://adserver.websitez.com/php/ad.php?token=".$domain_token,http_build_query($_SERVER));
	  	if(strlen($ad_html) > 11):
				$div_a = $dom->createCDATASection($ad_html);
	  		$body->appendChild($div_a);
	  	endif;
	  	
			$html = $dom->saveHTML();  
		}catch (Exception $e){  
			//Do nothing for now.  
		}
	endif;
	
	return $html;
}

/*
Ad mobile ads to the top and bottom of the page
*/
function websitez_set_mobile_ads_buffer(){
	//Don't filter Dashboard pages and the feed
	if (is_feed() || is_admin()){
		return;
	}

	ob_start("websitez_set_mobile_ads_buffer_append");
}

/*
Change where it looks for themes on the frond end
*/
function websitez_setThemeFolderFront(){
	return plugin_dir_url(__FILE__).'/themes';
}

/*
Change where it looks for themes
*/
function websitez_setThemeFolder(){
	return WEBSITEZ_PLUGIN_DIR.'/themes';
}

/*
The theme set here is used if it is a mobile device
*/
function websitez_setTheme($theme){
	$GLOBALS['websitez_template_name'] = $theme;
}

/*
The theme retrieved here is used if it is a mobile device
*/
function websitez_getTheme(){
	return $GLOBALS['websitez_template_name'];
}

/*
Lets get this party started
*/
function websitez_check_and_act_mobile(){
	global $table_prefix, $wpdb, $websitez_free_version, $websitez_preinstalled_templates;
	$mobile_device = websitez_detect_mobile_device();
	//Set the detection
	websitez_set_mobile_device($mobile_device);
	
	//Is it a mobile device?
	if($mobile_device['status'] == "true"){
		//Remove old stat records
		add_action('init', 'websitez_manage_stats', 10, 0);
		
		//Record a mobile visit only on the regular site and if it is enabled
		$websitez_record_stats = get_option(WEBSITEZ_RECORD_STATS_NAME);
		$websitez_preinstalled_templates = get_option(WEBSITEZ_USE_PREINSTALLED_THEMES_NAME);
		if($websitez_record_stats == "true" && !is_feed() && !is_admin()){
			$insert = $wpdb->insert(WEBSITEZ_STATS_TABLE, array( 'data' => serialize($_SERVER), 'device_type' => $mobile_device['type'], 'created_at' => date("Y-m-d H:i:s") ) );
		}
		$websitez_show_mobile_ads = get_option(WEBSITEZ_SHOW_MOBILE_ADS_NAME);
		if($websitez_show_mobile_ads != "false"):
			add_action('wp', 'websitez_set_mobile_ads_buffer', 10, 0);
		endif;

		if($mobile_device['type'] == "2"){ //Standard device
			$option = get_option(WEBSITEZ_BASIC_THEME);
			if(($websitez_preinstalled_templates == "false" && is_dir(ABSPATH.'/wp-content/themes/'.$option)) || ($websitez_preinstalled_templates == "true" && is_dir(WEBSITEZ_PLUGIN_DIR.'/themes/'.$option)) && strlen($option) > 0) {
				//This logic switches the theme and modifies the head/footer to give the user the ability to switch back to the full site
				websitez_setTheme($option);
				//This will remove all scripts, stylesheets, and advanced HTML from the page
				add_action('wp', 'websitez_basic_buffer', 10, 0);
				add_filter("the_content", "websitez_filterContentStandard");
				add_action('wp_footer', 'websitez_web_footer');
				add_action('wp_head', 'websitez_web_head');
				return true;
			}
		}else if($mobile_device['type'] == "1"){ //Smart device
			$option = get_option(WEBSITEZ_ADVANCED_THEME);
			if(($websitez_preinstalled_templates == "false" && is_dir(ABSPATH.'/wp-content/themes/'.$option)) || ($websitez_preinstalled_templates == "true" && is_dir(WEBSITEZ_PLUGIN_DIR.'/themes/'.$option)) && strlen($option) > 0) {
				//This logic switches the theme and modifies the head/footer to give the user the ability to switch back to the full site
				websitez_setTheme($option);
				add_action('wp', 'websitez_advanced_buffer', 10, 0);
				add_filter("the_content", "websitez_filterContentAdvanced");
				add_action('wp_footer', 'websitez_web_footer');
				add_action('wp_head', 'websitez_web_head');
				return true;
			}
		}else if($mobile_device['type'] == "0" && isset($_COOKIE['websitez_mobile_detector_fullsite'])){
			//If this is true, it is a mobile user, but they elected to view the full site.
			//We should give them the option to switch back
			add_action('wp_footer', 'websitez_web_footer_mobile');
			add_action('wp_head', 'websitez_web_head_mobile');
			//We want to return false so that the currently installed template is shown
			return false;
		}
	}else{
		//If it is the free version, add attribution
		if(get_option(WEBSITEZ_SHOW_ATTRIBUTION_NAME) == "true"){
			add_action('wp_footer', 'websitez_web_footer_standard');
		}
		//This means it is not a mobile device
	}
	return false;
}

function websitez_filterContentStandard($content){
	//Remove all images
	$content = preg_replace("/<img[^>]+\>/i", "", $content);
	return $content;
}

function websitez_filterContentAdvanced($content){
	//For now, do not filter anything, possibly filter HTML5 tags such as canvas
	return $content;
}

function websitez_web_footer_standard(){
	echo "<div class='websitez-footer' style='font-size: 1em; text-align: center; padding: 4px 0px;'>\n<p>".websitez_kpr()."</p>\n</div>\n";
}

/*
Attribution and ability to switch between mobile and full site
*/
function websitez_web_footer(){
	global $websitez_free_version;
	if($websitez_free_version == true){
		echo "<div class='websitez-footer'>\n
	<p>".websitez_kpr()."&nbsp;&nbsp;&nbsp;<a href='' onClick='websitez_setFullSite()' rel='nofollow'>View Full Site</a></p>\n
	</div>\n";
	}else{
		echo "<div class='websitez-footer'>\n
	<p><a href='' onClick='websitez_setFullSite()' rel='nofollow'>View Full Site</a></p>\n
	</div>\n";
	}
}

/*
Attribution and ability to switch between mobile and full site
*/
function websitez_web_footer_mobile(){
	global $websitez_free_version;
	if($websitez_free_version == true){
		echo "<div class='websitez-footer-mobile'>\n
	<p>".websitez_kpr()."&nbsp;&nbsp;&nbsp;<a href='' onClick='websitez_setMobileSite()' rel='nofollow'>View Mobile Site</a></p>\n
	</div>\n";
	}else{
		echo "<div class='websitez-footer-mobile'>\n
	<p><a href='' onClick='websitez_setMobileSite()' rel='nofollow'>View Mobile Site</a></p>\n
	</div>\n";
	}
}

/*
Attribution and ability to switch between mobile and full site
*/
function websitez_web_head(){
	echo "<script type='text/javascript'>\n
	function websitez_setFullSite(){\n
		c_name = 'websitez_mobile_detector_fullsite';\n
		value = '1';\n
		expiredays = '1';\n
		var exdate=new Date();\n
		exdate.setDate(exdate.getDate()+expiredays);\n
		document.cookie=c_name+ '=' +escape(value)+((expiredays==null) ? '' : ';expires='+exdate.toUTCString());\n
		window.location.reload();\n
	}\n
	</script>\n";
}

/*
Attribution and ability to switch between mobile and full site
*/
function websitez_web_head_mobile(){
	$cookie_name = WEBSITEZ_COOKIE_NAME;
	echo "<script type='text/javascript'>\n
	function websitez_setMobileSite(){\n
		websitez_setCookie('$cookie_name','',-1);
		websitez_setCookie('websitez_mobile_detector_fullsite','',-1);
		window.location.reload();\n
	}\n
	function websitez_setCookie(c_name,value,expiredays){\n
		var exdate=new Date();\n
		exdate.setDate(exdate.getDate()+expiredays);\n
		document.cookie=c_name+ '=' +escape(value)+((expiredays==null) ? '' : ';expires='+exdate.toUTCString());\n
	}\n
	</script>\n";
}

/*
Returns an array of information about the device
*/
function websitez_detect_mobile_device(){
	global $wpdb;
	//Checks to see if this has already been detected as a device.
	$check = websitez_check_previous_detection();
	if($check){
		return $check;
	}
		
	//Innocent until proven guilty
	$mobile_browser = false;
	//Speaks for itself
  $user_agent = $_SERVER['HTTP_USER_AGENT'];
	//This can also be used to detect a mobile device
  $accept = $_SERVER['HTTP_ACCEPT'];
	//Type of phone
	$mobile_browser_type = "0"; //0 - PC, 1 - Smart Phone, 2- Standard Phone
	
	$show_mobile_to_tablets = get_option(WEBSITEZ_SHOW_MOBILE_TO_TABLETS_NAME);

	switch(true){
		case (preg_match('/ipad/i',$user_agent)||preg_match('/kindle/i',$user_agent)||preg_match('/nook/i',$user_agent)); //Tablets
			if($show_mobile_to_tablets == 'true'):
				$mobile_browser = true;
				$mobile_browser_type = "1"; //Smart Phone
			else:
				$mobile_browser = false;
				$mobile_browser_type = "0"; //Smart Phone
			endif;
    break;
		/*
		Start off with smart phones or smart devices
		*/
		case (preg_match('/ipad/i',$user_agent)); //Tablets
      $mobile_browser = true;
			$mobile_browser_type = "1"; //Smart Phone
    break;
		
		case (preg_match('/ipod/i',$user_agent)||preg_match('/iphone/i',$user_agent)); //iPhone or iPod
      $mobile_browser = true;
			$mobile_browser_type = "1"; //Smart Phone
    break;

		case (preg_match('/android/i',$user_agent)); //Android
      $mobile_browser = true;
			$mobile_browser_type = "1"; //Smart Phone
    break;

		case (preg_match('/opera mini/i',$user_agent)); //Opera Mini
      $mobile_browser = true;
			$mobile_browser_type = "1"; //Smart Phone
    break;

		case (preg_match('/blackberry/i',$user_agent)); //Blackberry
      $mobile_browser = true;
			$mobile_browser_type = "1"; //Smart Phone
    break;

		case (preg_match('/(series60|series 60)/i',$user_agent)); //Symbian OS
      $mobile_browser = true;
			$mobile_browser_type = "1"; //Smart Phone
    break;

		case (preg_match('/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i',$user_agent)); //Palm OS
      $mobile_browser = true;
			$mobile_browser_type = "1"; //Smart Phone
    break;

		case (preg_match('/(iris|3g_t|windows ce|opera mobi|iemobile)/i',$user_agent)); //Windows OS
      $mobile_browser = true;
			$mobile_browser_type = "1"; //Smart Phone
    break;

		case (preg_match('/(maemo|tablet|qt embedded|com2)/i',$user_agent)); //Nokia Tablet
      $mobile_browser = true;
			$mobile_browser_type = "1"; //Smart Device
    break;

		/*
		Now look for standard phones & mobile devices
		*/
		case (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo|vnd.rim|wml|nitro|nintendo|wii|xbox|archos|openweb|mini|docomo)/i',$user_agent)); //Mix of standard phones
      $mobile_browser = true;
			$mobile_browser_type = "2"; //Standard Phone
    break;

		case ((strpos($accept,'text/vnd.wap.wml')>0)||(strpos($accept,'application/vnd.wap.xhtml+xml')>0)); //Any falling through the cracks
      $mobile_browser = true;
			$mobile_browser_type = "2"; //Standard Phone
    break;

		case (isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE'])); //Any falling through the cracks
      $mobile_browser = true;
			$mobile_browser_type = "2"; //Standard Phone
    break;

		case (in_array(strtolower(substr($user_agent,0,4)),array('1207'=>'1207','3gso'=>'3gso','4thp'=>'4thp','501i'=>'501i','502i'=>'502i','503i'=>'503i','504i'=>'504i','505i'=>'505i','506i'=>'506i','6310'=>'6310','6590'=>'6590','770s'=>'770s','802s'=>'802s','a wa'=>'a wa','acer'=>'acer','acs-'=>'acs-','airn'=>'airn','alav'=>'alav','asus'=>'asus','attw'=>'attw','au-m'=>'au-m','aur '=>'aur ','aus '=>'aus ','abac'=>'abac','acoo'=>'acoo','aiko'=>'aiko','alco'=>'alco','alca'=>'alca','amoi'=>'amoi','anex'=>'anex','anny'=>'anny','anyw'=>'anyw','aptu'=>'aptu','arch'=>'arch','argo'=>'argo','bell'=>'bell','bird'=>'bird','bw-n'=>'bw-n','bw-u'=>'bw-u','beck'=>'beck','benq'=>'benq','bilb'=>'bilb','blac'=>'blac','c55/'=>'c55/','cdm-'=>'cdm-','chtm'=>'chtm','capi'=>'capi','cond'=>'cond','craw'=>'craw','dall'=>'dall','dbte'=>'dbte','dc-s'=>'dc-s','dica'=>'dica','ds-d'=>'ds-d','ds12'=>'ds12','dait'=>'dait','devi'=>'devi','dmob'=>'dmob','doco'=>'doco','dopo'=>'dopo','el49'=>'el49','erk0'=>'erk0','esl8'=>'esl8','ez40'=>'ez40','ez60'=>'ez60','ez70'=>'ez70','ezos'=>'ezos','ezze'=>'ezze','elai'=>'elai','emul'=>'emul','eric'=>'eric','ezwa'=>'ezwa','fake'=>'fake','fly-'=>'fly-','fly_'=>'fly_','g-mo'=>'g-mo','g1 u'=>'g1 u','g560'=>'g560','gf-5'=>'gf-5','grun'=>'grun','gene'=>'gene','go.w'=>'go.w','good'=>'good','grad'=>'grad','hcit'=>'hcit','hd-m'=>'hd-m','hd-p'=>'hd-p','hd-t'=>'hd-t','hei-'=>'hei-','hp i'=>'hp i','hpip'=>'hpip','hs-c'=>'hs-c','htc '=>'htc ','htc-'=>'htc-','htca'=>'htca','htcg'=>'htcg','htcp'=>'htcp','htcs'=>'htcs','htct'=>'htct','htc_'=>'htc_','haie'=>'haie','hita'=>'hita','huaw'=>'huaw','hutc'=>'hutc','i-20'=>'i-20','i-go'=>'i-go','i-ma'=>'i-ma','i230'=>'i230','iac'=>'iac','iac-'=>'iac-','iac/'=>'iac/','ig01'=>'ig01','im1k'=>'im1k','inno'=>'inno','iris'=>'iris','jata'=>'jata','java'=>'java','kddi'=>'kddi','kgt'=>'kgt','kgt/'=>'kgt/','kpt '=>'kpt ','kwc-'=>'kwc-','klon'=>'klon','lexi'=>'lexi','lg g'=>'lg g','lg-a'=>'lg-a','lg-b'=>'lg-b','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-f'=>'lg-f','lg-g'=>'lg-g','lg-k'=>'lg-k','lg-l'=>'lg-l','lg-m'=>'lg-m','lg-o'=>'lg-o','lg-p'=>'lg-p','lg-s'=>'lg-s','lg-t'=>'lg-t','lg-u'=>'lg-u','lg-w'=>'lg-w','lg/k'=>'lg/k','lg/l'=>'lg/l','lg/u'=>'lg/u','lg50'=>'lg50','lg54'=>'lg54','lge-'=>'lge-','lge/'=>'lge/','lynx'=>'lynx','leno'=>'leno','m1-w'=>'m1-w','m3ga'=>'m3ga','m50/'=>'m50/','maui'=>'maui','mc01'=>'mc01','mc21'=>'mc21','mcca'=>'mcca','medi'=>'medi','meri'=>'meri','mio8'=>'mio8','mioa'=>'mioa','mo01'=>'mo01','mo02'=>'mo02','mode'=>'mode','modo'=>'modo','mot '=>'mot ','mot-'=>'mot-','mt50'=>'mt50','mtp1'=>'mtp1','mtv '=>'mtv ','mate'=>'mate','maxo'=>'maxo','merc'=>'merc','mits'=>'mits','mobi'=>'mobi','motv'=>'motv','mozz'=>'mozz','n100'=>'n100','n101'=>'n101','n102'=>'n102','n202'=>'n202','n203'=>'n203','n300'=>'n300','n302'=>'n302','n500'=>'n500','n502'=>'n502','n505'=>'n505','n700'=>'n700','n701'=>'n701','n710'=>'n710','nec-'=>'nec-','nem-'=>'nem-','newg'=>'newg','neon'=>'neon','netf'=>'netf','noki'=>'noki','nzph'=>'nzph','o2 x'=>'o2 x','o2-x'=>'o2-x','opwv'=>'opwv','owg1'=>'owg1','opti'=>'opti','oran'=>'oran','p800'=>'p800','pand'=>'pand','pg-1'=>'pg-1','pg-2'=>'pg-2','pg-3'=>'pg-3','pg-6'=>'pg-6','pg-8'=>'pg-8','pg-c'=>'pg-c','pg13'=>'pg13','phil'=>'phil','pn-2'=>'pn-2','pt-g'=>'pt-g','palm'=>'palm','pana'=>'pana','pire'=>'pire','pock'=>'pock','pose'=>'pose','psio'=>'psio','qa-a'=>'qa-a','qc-2'=>'qc-2','qc-3'=>'qc-3','qc-5'=>'qc-5','qc-7'=>'qc-7','qc07'=>'qc07','qc12'=>'qc12','qc21'=>'qc21','qc32'=>'qc32','qc60'=>'qc60','qci-'=>'qci-','qwap'=>'qwap','qtek'=>'qtek','r380'=>'r380','r600'=>'r600','raks'=>'raks','rim9'=>'rim9','rove'=>'rove','s55/'=>'s55/','sage'=>'sage','sams'=>'sams','sc01'=>'sc01','sch-'=>'sch-','scp-'=>'scp-','sdk/'=>'sdk/','se47'=>'se47','sec-'=>'sec-','sec0'=>'sec0','sec1'=>'sec1','semc'=>'semc','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','sk-0'=>'sk-0','sl45'=>'sl45','slid'=>'slid','smb3'=>'smb3','smt5'=>'smt5','sp01'=>'sp01','sph-'=>'sph-','spv '=>'spv ','spv-'=>'spv-','sy01'=>'sy01','samm'=>'samm','sany'=>'sany','sava'=>'sava','scoo'=>'scoo','send'=>'send','siem'=>'siem','smar'=>'smar','smit'=>'smit','soft'=>'soft','sony'=>'sony','t-mo'=>'t-mo','t218'=>'t218','t250'=>'t250','t600'=>'t600','t610'=>'t610','t618'=>'t618','tcl-'=>'tcl-','tdg-'=>'tdg-','telm'=>'telm','tim-'=>'tim-','ts70'=>'ts70','tsm-'=>'tsm-','tsm3'=>'tsm3','tsm5'=>'tsm5','tx-9'=>'tx-9','tagt'=>'tagt','talk'=>'talk','teli'=>'teli','topl'=>'topl','hiba'=>'hiba','up.b'=>'up.b','upg1'=>'upg1','utst'=>'utst','v400'=>'v400','v750'=>'v750','veri'=>'veri','vk-v'=>'vk-v','vk40'=>'vk40','vk50'=>'vk50','vk52'=>'vk52','vk53'=>'vk53','vm40'=>'vm40','vx98'=>'vx98','virg'=>'virg','vite'=>'vite','voda'=>'voda','vulc'=>'vulc','w3c '=>'w3c ','w3c-'=>'w3c-','wapj'=>'wapj','wapp'=>'wapp','wapu'=>'wapu','wapm'=>'wapm','wig '=>'wig ','wapi'=>'wapi','wapr'=>'wapr','wapv'=>'wapv','wapy'=>'wapy','wapa'=>'wapa','waps'=>'waps','wapt'=>'wapt','winc'=>'winc','winw'=>'winw','wonu'=>'wonu','x700'=>'x700','xda2'=>'xda2','xdag'=>'xdag','yas-'=>'yas-','your'=>'your','zte-'=>'zte-','zeto'=>'zeto','acs-'=>'acs-','alav'=>'alav','alca'=>'alca','amoi'=>'amoi','aste'=>'aste','audi'=>'audi','avan'=>'avan','benq'=>'benq','bird'=>'bird','blac'=>'blac','blaz'=>'blaz','brew'=>'brew','brvw'=>'brvw','bumb'=>'bumb','ccwa'=>'ccwa','cell'=>'cell','cldc'=>'cldc','cmd-'=>'cmd-','dang'=>'dang','doco'=>'doco','eml2'=>'eml2','eric'=>'eric','fetc'=>'fetc','hipt'=>'hipt','http'=>'http','ibro'=>'ibro','idea'=>'idea','ikom'=>'ikom','inno'=>'inno','ipaq'=>'ipaq','jbro'=>'jbro','jemu'=>'jemu','java'=>'java','jigs'=>'jigs','kddi'=>'kddi','keji'=>'keji','kyoc'=>'kyoc','kyok'=>'kyok','leno'=>'leno','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-g'=>'lg-g','lge-'=>'lge-','libw'=>'libw','m-cr'=>'m-cr','maui'=>'maui','maxo'=>'maxo','midp'=>'midp','mits'=>'mits','mmef'=>'mmef','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','mwbp'=>'mwbp','mywa'=>'mywa','nec-'=>'nec-','newt'=>'newt','nok6'=>'nok6','noki'=>'noki','o2im'=>'o2im','opwv'=>'opwv','palm'=>'palm','pana'=>'pana','pant'=>'pant','pdxg'=>'pdxg','phil'=>'phil','play'=>'play','pluc'=>'pluc','port'=>'port','prox'=>'prox','qtek'=>'qtek','qwap'=>'qwap','rozo'=>'rozo','sage'=>'sage','sama'=>'sama','sams'=>'sams','sany'=>'sany','sch-'=>'sch-','sec-'=>'sec-','send'=>'send','seri'=>'seri','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','siem'=>'siem','smal'=>'smal','smar'=>'smar','sony'=>'sony','sph-'=>'sph-','symb'=>'symb','t-mo'=>'t-mo','teli'=>'teli','tim-'=>'tim-','tosh'=>'tosh','treo'=>'treo','tsm-'=>'tsm-','upg1'=>'upg1','upsi'=>'upsi','vk-v'=>'vk-v','voda'=>'voda','vx52'=>'vx52','vx53'=>'vx53','vx60'=>'vx60','vx61'=>'vx61','vx70'=>'vx70','vx80'=>'vx80','vx81'=>'vx81','vx83'=>'vx83','vx85'=>'vx85','wap-'=>'wap-','wapa'=>'wapa','wapi'=>'wapi','wapp'=>'wapp','wapr'=>'wapr','webc'=>'webc','whit'=>'whit','winw'=>'winw','wmlb'=>'wmlb','xda-'=>'xda-'))); //Catch all
      $mobile_browser = true;
			$mobile_browser_type = "2"; //Standard Phone
    break;

		default;
			$mobile_browser = false;
			$mobile_browser_type = "0";
		break;
	}
	
	$mobile_browser_status = ($mobile_browser == true) ? "true" : "false";
	
	//Set a persistent client-side value to avoid having to detect again for this visitor
	websitez_set_previous_detection($mobile_browser_status,$mobile_browser_type,$user_agent);
	
	return array('status'=>$mobile_browser_status,'type'=>$mobile_browser_type);
}

/*
If it is a mobile device, lets try and remember to avoid having to detect it again
*/
function websitez_set_previous_detection($status,$type,$user_agent){
	if($status=="true"){
		//This is set to prevent caching mechanisms such as W3 total cache from caching the mobile page
		setcookie("websitez_is_mobile", "true", time()+3600, "/");
	}
	
	$s = setcookie(WEBSITEZ_COOKIE_NAME, $status."|".$type."|".md5($user_agent), time()+3600, "/");
}

/*
Check to see if this mobile device has been previously detected
*/
function websitez_check_previous_detection(){
	if(isset($_COOKIE['websitez_mobile_detector_fullsite']) && isset($_COOKIE[WEBSITEZ_COOKIE_NAME])){
		$obj = explode("|",$_COOKIE[WEBSITEZ_COOKIE_NAME]);
		//Returning a 0 will show the desktop version aka the 'fullsite'
		//This is executed if the user elected to view the 'fullsite' version
		return array('status'=>$obj[0],'type'=>'0');
	}else if(isset($_COOKIE[WEBSITEZ_COOKIE_NAME])){
		$obj = explode("|",$_COOKIE[WEBSITEZ_COOKIE_NAME]);
		if($obj[2] == md5($_SERVER['HTTP_USER_AGENT'])):
			return array('status'=>$obj[0],'type'=>$obj[1]);
		endif;
		
		return false;
	}else{
		return false;
	}
}

/*
Return the current themes in wp-content/themes
*/
function websitez_get_current_themes(){
	$websitez_preinstalled_templates = get_option(WEBSITEZ_USE_PREINSTALLED_THEMES_NAME);
	
	if($websitez_preinstalled_templates == "true"){
		$path = WEBSITEZ_PLUGIN_DIR.'/themes';
		return $wp_themes = websitez_get_themes($path);
	}else{
		if(!function_exists('get_themes'))
			return null;

		return $wp_themes = get_themes();
	}
}

/*
Information about the types of devices that can be detected
*/
function websitez_get_mobile_types(){
	return array(array('name'=>'Basic Mobile Device','option'=>WEBSITEZ_BASIC_THEME,'url_redirect'=>WEBSITEZ_BASIC_URL_REDIRECT),array('name'=>'Advanced Mobile Device','option'=>WEBSITEZ_ADVANCED_THEME,'url_redirect'=>WEBSITEZ_ADVANCED_URL_REDIRECT));
}

function websitez_get_themes($path = null, $only_mobile = false) {
	global $wp_themes, $wp_broken_themes, $wp_theme_directories;

	/*
	Register the default root as a theme directory 
	This was working, but occasionally would load the regular themes in the wp-content/themes
	Oddly enough this seemed to be sporadic.
	*/
	//register_theme_directory( $path );
	
	//Empty out the directory array and add the plugin dir
	if($only_mobile == true){
		$current_theme_directories = $wp_theme_directories;
		$wp_theme_directories = array($path);
	}

	if (!function_exists('search_theme_directories') || !$theme_files = search_theme_directories(true))
		return false;

	asort( $theme_files );

	$wp_themes = array();

	foreach ( (array) $theme_files as $theme_file ) {
		$theme_root = $theme_file['theme_root'];
		$theme_file = $theme_file['theme_file'];

		if ( !is_readable("$theme_root/$theme_file") ) {
			$wp_broken_themes[$theme_file] = array('Name' => $theme_file, 'Title' => $theme_file, 'Description' => __('File not readable.'));
			continue;
		}

		$theme_data = get_theme_data("$theme_root/$theme_file");

		$name        = $theme_data['Name'];
		$title       = $theme_data['Title'];
		$description = wptexturize($theme_data['Description']);
		$version     = $theme_data['Version'];
		$author      = $theme_data['Author'];
		$template    = $theme_data['Template'];
		$stylesheet  = dirname($theme_file);

		$screenshot = false;
		foreach ( array('png', 'gif', 'jpg', 'jpeg') as $ext ) {
			if (file_exists("$theme_root/$stylesheet/screenshot.$ext")) {
				$screenshot = "screenshot.$ext";
				break;
			}
		}

		if ( empty($name) ) {
			$name = dirname($theme_file);
			$title = $name;
		}

		$parent_template = $template;

		if ( empty($template) ) {
			if ( file_exists("$theme_root/$stylesheet/index.php") )
				$template = $stylesheet;
			else
				continue;
		}

		$template = trim( $template );

		if ( !file_exists("$theme_root/$template/index.php") ) {
			$parent_dir = dirname(dirname($theme_file));
			if ( file_exists("$theme_root/$parent_dir/$template/index.php") ) {
				$template = "$parent_dir/$template";
				$template_directory = "$theme_root/$template";
			} else {
				/**
				 * The parent theme doesn't exist in the current theme's folder or sub folder
				 * so lets use the theme root for the parent template.
				 */
				if ( isset($theme_files[$template]) && file_exists( $theme_files[$template]['theme_root'] . "/$template/index.php" ) ) {
					$template_directory = $theme_files[$template]['theme_root'] . "/$template";
				} else {
					if ( empty( $parent_template) )
						$wp_broken_themes[$name] = array('Name' => $name, 'Title' => $title, 'Description' => __('Template is missing.'), 'error' => 'no_template');
					else
						$wp_broken_themes[$name] = array('Name' => $name, 'Title' => $title, 'Description' => sprintf( __('The parent theme is missing. Please install the "%s" parent theme.'),  $parent_template ), 'error' => 'no_parent', 'parent' => $parent_template );
					continue;
				}

			}
		} else {
			$template_directory = trim( $theme_root . '/' . $template );
		}

		$stylesheet_files = array();
		$template_files = array();

		$stylesheet_dir = @ dir("$theme_root/$stylesheet");
		if ( $stylesheet_dir ) {
			while ( ($file = $stylesheet_dir->read()) !== false ) {
				if ( !preg_match('|^\.+$|', $file) ) {
					if ( preg_match('|\.css$|', $file) )
						$stylesheet_files[] = "$theme_root/$stylesheet/$file";
					elseif ( preg_match('|\.php$|', $file) )
						$template_files[] = "$theme_root/$stylesheet/$file";
				}
			}
			@ $stylesheet_dir->close();
		}

		$template_dir = @ dir("$template_directory");
		if ( $template_dir ) {
			while ( ($file = $template_dir->read()) !== false ) {
				if ( preg_match('|^\.+$|', $file) )
					continue;
				if ( preg_match('|\.php$|', $file) ) {
					$template_files[] = "$template_directory/$file";
				} elseif ( is_dir("$template_directory/$file") ) {
					$template_subdir = @ dir("$template_directory/$file");
					if ( !$template_subdir )
						continue;
					while ( ($subfile = $template_subdir->read()) !== false ) {
						if ( preg_match('|^\.+$|', $subfile) )
							continue;
						if ( preg_match('|\.php$|', $subfile) )
							$template_files[] = "$template_directory/$file/$subfile";
					}
					@ $template_subdir->close();
				}
			}
			@ $template_dir->close();
		}

		//Make unique and remove duplicates when stylesheet and template are the same i.e. most themes
		$template_files = array_unique($template_files);
		$stylesheet_files = array_unique($stylesheet_files);

		$template_dir = dirname($template_files[0]);
		$stylesheet_dir = dirname($stylesheet_files[0]);

		if ( empty($template_dir) )
			$template_dir = '/';
		if ( empty($stylesheet_dir) )
			$stylesheet_dir = '/';

		// Check for theme name collision.  This occurs if a theme is copied to
		// a new theme directory and the theme header is not updated.  Whichever
		// theme is first keeps the name.  Subsequent themes get a suffix applied.
		// The Default and Classic themes always trump their pretenders.
		if ( isset($wp_themes[$name]) ) {
			if ( ('WordPress Default' == $name || 'WordPress Classic' == $name) &&
					 ('default' == $stylesheet || 'classic' == $stylesheet) ) {
				// If another theme has claimed to be one of our default themes, move
				// them aside.
				$suffix = $wp_themes[$name]['Stylesheet'];
				$new_name = "$name/$suffix";
				$wp_themes[$new_name] = $wp_themes[$name];
				$wp_themes[$new_name]['Name'] = $new_name;
			} else {
				$name = "$name/$stylesheet";
			}
		}

		$theme_roots[$stylesheet] = str_replace( WP_CONTENT_DIR, '', $theme_root );
		$wp_themes[$name] = array(
			'Name' => $name,
			'Title' => $title,
			'Description' => $description,
			'Author' => $author,
			'Author Name' => $theme_data['AuthorName'],
			'Author URI' => $theme_data['AuthorURI'],
			'Version' => $version,
			'Template' => $template,
			'Stylesheet' => $stylesheet,
			'Template Files' => $template_files,
			'Stylesheet Files' => $stylesheet_files,
			'Template Dir' => $template_dir,
			'Stylesheet Dir' => $stylesheet_dir,
			'Status' => $theme_data['Status'],
			'Screenshot' => $screenshot,
			'Tags' => $theme_data['Tags'],
			'Theme Root' => $theme_root,
			'Theme Root URI' => str_replace( WP_CONTENT_DIR, content_url(), $theme_root ),
		);
	}

	unset($theme_files);

	/* Store theme roots in the DB */
	if ( function_exists('get_site_transient') && get_site_transient( 'theme_roots' ) != $theme_roots )
		set_site_transient( 'theme_roots', $theme_roots, 7200 ); // cache for two hours
	unset($theme_roots);

	/* Resolve theme dependencies. */
	$theme_names = array_keys( $wp_themes );
	foreach ( (array) $theme_names as $theme_name ) {
		$wp_themes[$theme_name]['Parent Theme'] = '';
		if ( $wp_themes[$theme_name]['Stylesheet'] != $wp_themes[$theme_name]['Template'] ) {
			foreach ( (array) $theme_names as $parent_theme_name ) {
				if ( ($wp_themes[$parent_theme_name]['Stylesheet'] == $wp_themes[$parent_theme_name]['Template']) && ($wp_themes[$parent_theme_name]['Template'] == $wp_themes[$theme_name]['Template']) ) {
					$wp_themes[$theme_name]['Parent Theme'] = $wp_themes[$parent_theme_name]['Name'];
					break;
				}
			}
		}
	}
	
	//Empty out the directory array and add the plugin dir
	if($only_mobile == true){
		$wp_theme_directories = $current_theme_directories;
	}
	
	return $wp_themes;
}

/*
Will return a link to place depending on first char of the page filename
*/
function websitez_kpr(){
	// phrase 3 should be your primary keyphrase, as it will come up with index.html
	
	$kpr_phrase = array(
		'<a href="http://websitez.com" title="WordPress Mobile">WordPress Mobile</a>', //0
		'<a href="http://websitez.com" title="WordPress Mobile Themes">WordPress Mobile Themes</a>', //1
		'<a href="http://websitez.com/wordpress-mobile/" title="WordPress Mobile Plugin">WordPress Mobile Plugin</a>', //2
		'<a href="http://websitez.com" title="WordPress Mobile">WordPress Mobile</a>', //3
		'<a href="http://websitez.com" title="WordPress Mobile">WordPress Mobile</a>', //4
		'<a href="http://websitez.com" title="WordPress Mobile">WordPress Mobile</a>', //5
		'<a href="http://websitez.com" title="WordPress Mobile">WordPress Mobile Plugins</a>', //6
		'<a href="http://websitez.com/products-page/mobile-themes/" title="WordPress Mobile Themes">WordPress Mobile Themes</a>', //7
		'<a href="http://websitez.com" title="WordPress Mobile">WordPress Mobile</a>', //8
		'<a href="http://websitez.com" title="WordPress Mobile Plugins">WordPress Mobile Plugins</a>', //9
		'<a href="http://websitez.com" title="WordPress Mobile">WordPress Mobile</a>', //10
	);
	##############################
	# DO NOT EDIT BELOW THIS LINE
	##############################

	// unset variables to get a clean start
	unset($kpr_explode);
	unset($internal_page_filename);
	unset($kpr_char);
	unset($kpr_num);

	// get the page's filename
	$kpr_explode = explode('/', $_SERVER["REQUEST_URI"]);
	if(count($kpr_explode) >=2){
		if($kpr_explode[count($kpr_explode)-1] != ""){
			$internal_page_filename = strtolower($kpr_explode[count($kpr_explode)-1]);
		}else if($kpr_explode[count($kpr_explode)-2] != ""){
			$internal_page_filename = strtolower($kpr_explode[count($kpr_explode)-2]);
		}else{
			$internal_page_filename = "index";
		}
	}else{
		$internal_page_filename = "index";
	}

	// get the first letter of the page's filename and convert it to ascii
	$kpr_char = substr($internal_page_filename, 0, 1);
	$kpr_num = ord($kpr_char);

	// display the appropriate phrase
	if ($kpr_num <= 96) // caps, numbers, some punctuation, and most non printable chars
	{
		return $kpr_phrase[0];
	}
	else if ( ($kpr_num >= 97) && ($kpr_num <= 99) ) // a - c
	{
		return $kpr_phrase[1];
	}
	else if ( ($kpr_num >= 100) && ($kpr_num <= 102) ) // d - f
	{
		return $kpr_phrase[2];
	}
	else if ( ($kpr_num >= 103) && ($kpr_num <= 105) ) // g - i
	{
		return $kpr_phrase[3];
	}
	else if ( ($kpr_num >= 106) && ($kpr_num <= 108) ) // j - l
	{
		return $kpr_phrase[4];
	}
	else if ( ($kpr_num >= 109) && ($kpr_num <= 111) ) // m - o
	{
		return $kpr_phrase[5];
	}
	else if ( ($kpr_num >= 112) && ($kpr_num <= 114) ) // p - r
	{
		return $kpr_phrase[6];
	}
	else if ( ($kpr_num >= 115) && ($kpr_num <= 117) ) // s - u
	{
		return $kpr_phrase[7];
	}
	else if ( ($kpr_num >= 118) && ($kpr_num <= 120) ) // v - x
	{
		return $kpr_phrase[8];
	}
	else if ($kpr_num >= 121) // y +
	{
		return $kpr_phrase[9];
	}
	else // catchall for anything that slipped through the cracks
	{
		return $kpr_phrase[10];
	}
}

/*
Check for CURL
*/
function websitez_iscurlinstalled() {
	if(function_exists('get_loaded_extensions') && in_array ('curl', get_loaded_extensions())) {
		return true;
	}else{
		return false;
	}
}

/*
Perform CURL
*/
function websitez_remote_request($host,$path){
	$fp = curl_init($host);
	curl_setopt($fp, CURLOPT_POST, true);
	curl_setopt($fp, CURLOPT_POSTFIELDS, $path);
	curl_setopt($fp, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($fp, CURLOPT_CONNECTTIMEOUT, 5);
	$page = curl_exec($fp);
	curl_close($fp);
	
	return $page;
}
?>