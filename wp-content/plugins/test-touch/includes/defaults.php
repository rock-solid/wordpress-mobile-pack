<?php
// default sttings
global $wmp_default_options;
$wmp_default_options = array(
	'license_key' => '',
	'current_theme' => 'classic',
	'enable_comments' => true,
	'enable_menu' => false,
	'enable_search' => true,
	'analytics_type' => 'none',
	'advertising_type' => 'none',
	'show_thumbnails' => true,
    'show_featured_image_in_post' => false,
	'show_post_author' => true,
	'show_post_tags' => false,
	'show_post_categories' => false,
	'site_title' => get_bloginfo( 'name' ),
	'site_logo' => '',
	'site_font' => 'Helvetica',
	'site_background_color' => '#EFEFEF',
	'header_background_color' => '#E1E1E1',
	'header_trim_color' => '#50BCB6',
	'header_text_color' => '#777777',
	'header_top_bar_color' => '#50BCB6',
	'google_analytics_code' => '',
	'custom_analytics_code' => '',
	'adsense_client_id' => '',
	'custom_advertising_code' => '',
	'pin_header' => true,
	'pin_ad' => false,
	'menu_links' => array(),
	'front_page' => '',
);

// fonts available
global $wmp_fonts;
$wmp_fonts = array( 
	'Arial', 
	'Courier',
	'Georgia', 
	'Helvetica',
	'Times New Roman', 
	'Verdana'
);

// device user agents
global $wmp_user_agents;
$wmp_user_agents = array(
	'iphone',
	'ipod',
	'android',
	'windows phone',
	'windows mobile',
	'blackberry'
);
?>