<?php

	$json_config_premium = WMobilePack::wmp_set_premium_config(); 
    
    $arrConfig = null;
	if ($json_config_premium !== false) {
		$arrConfig = json_decode($json_config_premium, true);
	}
    
    // check if we have a valid domain
    if (isset($arrConfig['domain_name']) && filter_var('http://'.$arrConfig['domain_name'], FILTER_VALIDATE_URL)) {
        header("Location: http://".$arrConfig['domain_name']);
        exit();
    }
    
    // check if we have a secure https connection
    $is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    
    // Check if the browser supports the loading of gzipped files
    $supported_gzip = false;
    if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	   $supported_gzip = true;
       
    // Gzip is not support for older kit version
    if ($arrConfig['kit_version'] == 'v2.5.0')
        $supported_gzip = false;
    
	// check if it is tablet 
	$is_tablet = WMobilePack::wmp_is_tablet();
    
    $cdn_kits = ($is_secure ? $arrConfig['cdn_kits_https'] : $arrConfig['cdn_kits']);
    $cdn_apps = ($is_secure ? $arrConfig['cdn_apps_https'] : $arrConfig['cdn_apps']);

    if ($arrConfig['kit_version'] == 'v2.5.0')
        require_once('template_250.php');  
    else
        require_once('template_260.php');
?>
