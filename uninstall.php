<?php

// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

require_once('core/config.php');
require_once('core/class-wmp.php');

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