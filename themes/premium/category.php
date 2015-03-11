<?php
$category_id_param = '';
$category_name = single_cat_title("", false);

if ($category_name){
	
	$category_obj = get_term_by('name', $category_name, 'category');
	
	if ($category_obj && isset($category_obj->term_id) && is_numeric($category_obj->term_id)){
		
		$category_id_param = '#categoryWp/'.$category_obj->term_id;
	}
}

// load config json for the premium theme
$json_config_premium = WMobilePack::wmp_set_premium_config(); 
    
$arrConfig = null;
if ($json_config_premium !== false) {
	$arrConfig = json_decode($json_config_premium, true);
}

// check if we have a valid domain
if (isset($arrConfig['domain_name']) && filter_var('http://'.$arrConfig['domain_name'], FILTER_VALIDATE_URL)) {
    header("Location: http://".$arrConfig['domain_name'].$category_id_param);
} else {
    header("Location: ".home_url().$category_id_param);
}
?>