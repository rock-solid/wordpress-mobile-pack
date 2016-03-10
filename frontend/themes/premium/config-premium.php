<?php

// Load JSON data (config json)
$premium_manager = new WMobilePack_Premium();
$json_config_premium = $premium_manager->set_premium_config();

// Unpack JSON data
$arr_config_premium = null;
if ($json_config_premium !== false) {
    $arr_config_premium = json_decode($json_config_premium, true);
}

// Check if we have classic (old) style or wpmp app settings
$kit_type = 'classic';
if (isset($arr_config_premium['kit_type']) && $arr_config_premium['kit_type'] == 'wpmp'){
    $kit_type = 'wpmp';
}

