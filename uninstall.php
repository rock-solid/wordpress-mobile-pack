<?php

    // If uninstall is not called from WordPress, exit
    if (!defined('WP_UNINSTALL_PLUGIN')) {
        exit();
    }

    require_once('core/class-config.php');
    $Pt_Pwa_Config = new Pt_Pwa_Config();
    require_once('core/class-pwa.php');

    // create uploads folder and define constants
    if (!defined('PWA_FILES_UPLOADS_DIR') && !defined('PWA_FILES_UPLOADS_URL')) {
        $WMP_Uploads = new PtPwa_Uploads();
        $WMP_Uploads->define_uploads_dir();
    }

    // remove uploaded images and uploads folder
    $WMP_Uploads = new PtPwa_Uploads();
    $WMP_Uploads->remove_uploads_dir();

    // delete plugin settings
    delete_option('pt_pwa_enabled');
    PtPwa_Options::uninstall();

    //delete service worker and manifest files
