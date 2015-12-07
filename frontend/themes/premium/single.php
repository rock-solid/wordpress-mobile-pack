<?php
// get the post url
$articleUrlParam = '';

$permalink = get_permalink();

if (is_numeric(get_the_ID()) && filter_var($permalink, FILTER_VALIDATE_URL)){
    $permalink = rawurlencode($permalink);
    $permalink = str_replace('.','%2E',$permalink);
    
    $articleUrlParam = '#articleUrl/'.$permalink;
} 

// load config json for the premium theme
$premium_manager = new WMobilePack_Premium();
$json_config_premium = $premium_manager->set_premium_config();
    
$arr_config_premium = null;
if ($json_config_premium !== false) {
	$arr_config_premium = json_decode($json_config_premium, true);
}

// check if we have a valid domain
if (isset($arr_config_premium['domain_name']) && filter_var('http://'.$arr_config_premium['domain_name'], FILTER_VALIDATE_URL)) {
    header("Location: http://".$arr_config_premium['domain_name'].$articleUrlParam);
} else {
    header("Location: ".home_url().$articleUrlParam);
}

?>