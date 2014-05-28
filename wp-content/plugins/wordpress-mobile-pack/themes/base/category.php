<?php

$displayed_category = get_the_category();

if ($displayed_category != null && is_array($displayed_category) && count($displayed_category) > 0 && is_object($displayed_category[0])) {
    header("Location: ".home_url()."/#category/".$displayed_category[0]->cat_name."/".$displayed_category[0]->cat_ID);    
} else {
    header("Location: ".home_url());
}
?>