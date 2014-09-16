<?php
require_once(WMP_PLUGIN_PATH.'libs/safestring/safeString.php'); 

$displayed_category = get_the_category();

if ($displayed_category != null && is_array($displayed_category) && count($displayed_category) > 0 && is_object($displayed_category[0])) {

	// strip all special chars
	$safeString = new safeString();
	$category_name = $safeString::clearString($displayed_category[0]->cat_name);

	header("Location: ".home_url()."/#category/".$category_name."/".$displayed_category[0]->cat_ID);    
} else {
    header("Location: ".home_url());
}
?>