<?php

$app_settings = WMobilePack_Application::load_app_settings_premium();

// if we have a valid domain, redirect to it
if (isset($app_settings['domain_name']) && filter_var('http://'.$app_settings['domain_name'], FILTER_VALIDATE_URL)) {
	header("Location: http://".$app_settings['domain_name']);
	exit();
}

// load app
require_once('template.php');
