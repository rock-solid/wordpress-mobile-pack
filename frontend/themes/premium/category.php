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
$premium_manager = new WMobilePack_Premium();
$json_config_premium = $premium_manager->set_premium_config();
    
$arr_config_premium = null;
if ($json_config_premium !== false) {
	$arr_config_premium = json_decode($json_config_premium, true);
}

// check if we have a valid domain
if (isset($arr_config_premium['domain_name']) && filter_var('http://'.$arr_config_premium['domain_name'], FILTER_VALIDATE_URL)) {
    header("Location: http://".$arr_config_premium['domain_name'].$category_id_param);
} else {
    header("Location: ".home_url().$category_id_param);
}
?>