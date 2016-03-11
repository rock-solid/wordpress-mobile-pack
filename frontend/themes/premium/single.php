<?php

require_once('config-premium.php');

// get the post url
$articleUrlParam = '';

if (is_numeric(get_the_ID())){

    if ($kit_type == 'classic') {

        $permalink = get_permalink();

        if (filter_var($permalink, FILTER_VALIDATE_URL)) {

            $permalink = rawurlencode($permalink);
            $permalink = str_replace('.', '%2E', $permalink);

            $articleUrlParam = '#articleUrl/' . $permalink;
        }

    } else {
        $articleUrlParam = '/#article/' . get_the_ID();
    }
}

// check if we have a valid domain
if (isset($arr_config_premium['domain_name']) && filter_var('http://'.$arr_config_premium['domain_name'], FILTER_VALIDATE_URL)) {
    header("Location: http://".$arr_config_premium['domain_name'].$articleUrlParam);
} else {
    header("Location: ".home_url().$articleUrlParam);
}

?>