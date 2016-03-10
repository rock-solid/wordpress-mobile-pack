<?php

// Load JSON data (config json)
$premium_manager = new WMobilePack_Premium();
$arr_config_premium = $premium_manager->get_premium_config();

// @todo Test this
if ($arr_config_premium == null)
    exit();

// Check if we have classic (old) style or wpmp app settings
$kit_type = 'classic';
if (isset($arr_config_premium['kit_type']) && $arr_config_premium['kit_type'] == 'wpmp'){
    $kit_type = 'wpmp';
}