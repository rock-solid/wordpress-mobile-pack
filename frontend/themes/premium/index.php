<?php

require_once('config-premium.php');

// if we have a valid domain, redirect to it
if (isset($arr_config_premium['domain_name']) && filter_var('http://'.$arr_config_premium['domain_name'], FILTER_VALIDATE_URL)) {
	header("Location: http://".$arr_config_premium['domain_name']);
	exit();
}

// load app
if ($kit_type == 'classic')
	require_once('template-classic.php');
else
	require_once('template-wpmp.php');
