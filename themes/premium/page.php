<?php
// get the page url
$pageUrlParam = '';

$permalink = get_permalink();

if (is_numeric(get_the_ID()) && filter_var($permalink, FILTER_VALIDATE_URL)){
    $permalink = rawurlencode($permalink);
    $permalink = str_replace('.','%2E',$permalink);
    
    $pageUrlParam = '#pageUrl/'.$permalink;
} 

// load config json for the premium theme
$json_config_premium = WMobilePack::wmp_set_premium_config(); 
    
$arrConfig = null;
if ($json_config_premium !== false) {
	$arrConfig = json_decode($json_config_premium, true);
}


// check if front page is a static page
if(get_option('show_on_front') == 'page' && get_option('page_on_front') == get_the_ID()){
	require_once('template.php');// load app
	
} else {
	
	// check if we have a valid domain
	if (isset($arrConfig['domain_name']) && filter_var('http://'.$arrConfig['domain_name'], FILTER_VALIDATE_URL)) {
		header("Location: http://".$arrConfig['domain_name'].$pageUrlParam);
	} else {
		header("Location: ".home_url().$pageUrlParam);
	}	
	
}



?>