<?php

// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

require_once('core/config.php');
require_once('core/class-wmp.php');

$apiKey = WMobilePack_Options::get_setting('premium_api_key');
$isPremiumActive =  WMobilePack_Options::get_setting('premium_active');

if ($apiKey != '' && $isPremiumActive == 1) {

    // check if we have a https connection
    $is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

    WMobilePack::read_data( ($is_secure ? WMP_APPTICLES_DISCONNECT_SSL : WMP_APPTICLES_DISCONNECT).'?apiKey='.$apiKey);
}

// create uploads folder and define constants
if ( !defined( 'WMP_FILES_UPLOADS_DIR' ) && !defined( 'WMP_FILES_UPLOADS_URL' ) ){
    $WMP_Uploads = new WMobilePack_Uploads();
    $WMP_Uploads->define_uploads_dir();
}

// remove uploaded images and uploads folder
$WMP_Uploads = new WMobilePack_Uploads();
$WMP_Uploads->remove_uploads_dir();

// delete plugin settings
WMobilePack_Options::uninstall();