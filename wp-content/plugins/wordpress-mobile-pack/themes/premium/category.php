<?php
$categoryIdParam = '';

// get the category id
$displayed_category = get_the_category();

if ($displayed_category != null && is_array($displayed_category) && count($displayed_category) > 0 && is_object($displayed_category[0])){
    $categoryIdParam = '#categoryWp/'.$displayed_category[0]->cat_ID;
} 

// load config json for the premium theme
$json_config_premium = WMobilePack::wmp_set_premium_config(); 
    
$arrConfig = null;
if ($json_config_premium !== false) {
	$arrConfig = json_decode($json_config_premium, true);
}

// check if we have a valid domain
if (isset($arrConfig['domain_name']) && filter_var('http://'.$arrConfig['domain_name'], FILTER_VALIDATE_URL)) {
    header("Location: http://".$arrConfig['domain_name'].$categoryIdParam);
} else {
    header("Location: ".home_url().$categoryIdParam);
}
?>