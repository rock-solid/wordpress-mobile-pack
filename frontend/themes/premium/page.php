<?php

// check if the front page is a static page
if (get_option('show_on_front') == 'page' && get_option('page_on_front') == get_the_ID()){

	// load app
	$app_settings = WMobilePack_Application::load_app_settings_premium();

    // if we have a valid domain, redirect to it
    if (isset($app_settings['domain_name']) && filter_var('http://'.$app_settings['domain_name'], FILTER_VALIDATE_URL)) {
        header("Location: http://".$app_settings['domain_name']);
        exit();
    }

	$theme = $app_settings['theme'];

	if ($app_settings['kit_type'] && ($theme == 6 || $theme == 7)) {
		require_once('template2.php');
	} else {
		require_once('template.php');
	}

} else {

	require_once('config-premium.php');

	// get the page url
	$pageUrlParam = '';

	if (is_numeric(get_the_ID())) {

		if ($kit_type == 'classic') {

			$permalink = get_permalink();

			if (filter_var($permalink, FILTER_VALIDATE_URL)) {

				$permalink = rawurlencode($permalink);
				$permalink = str_replace('.', '%2E', $permalink);

				$pageUrlParam = '#pageUrl/' . $permalink;
			}

		} else {

			$pageUrlParam = '/#page/' . get_the_ID();
		}
	}

	// check if we have a valid domain
	if (isset($arr_config_premium['domain_name']) && filter_var('http://' . $arr_config_premium['domain_name'], FILTER_VALIDATE_URL)) {
		header("Location: http://" . $arr_config_premium['domain_name'] . $pageUrlParam);
	} else {
		header("Location: " . home_url() . $pageUrlParam);
	}
}
