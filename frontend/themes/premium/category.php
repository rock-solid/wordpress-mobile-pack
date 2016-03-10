<?php

require_once('config-premium.php');

$category_id_param = '';
$category_name = single_cat_title("", false);

if ($category_name){

	$category_obj = get_term_by('name', $category_name, 'category');

	if ($category_obj && isset($category_obj->slug) && isset($category_obj->term_id) && is_numeric($category_obj->term_id)){

		if ($kit_type == 'classic')
			$category_id_param = '#categoryWp/'.$category_obj->term_id;
		else
			$category_id_param = "/#category/".$category_obj->slug.'/'.$category_obj->term_id;
	}
}

// check if we have a valid domain
if (isset($arr_config_premium['domain_name']) && filter_var('http://'.$arr_config_premium['domain_name'], FILTER_VALIDATE_URL)) {
	header("Location: http://".$arr_config_premium['domain_name'].$category_id_param);
} else {
	header("Location: ".home_url().$category_id_param);
}
