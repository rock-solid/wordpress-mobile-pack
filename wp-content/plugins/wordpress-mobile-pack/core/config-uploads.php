<?php

function wmp_set_uploads_dir() {
    
	$wp_uploads_dir = wp_upload_dir();
    
    $wmp_uploads_dir = $wp_uploads_dir['basedir']. '/wordpress-mobile-pack/';
    
    // if the directory doesn't exist, create it	
	if (!file_exists($wmp_uploads_dir)) {
		mkdir($wmp_uploads_dir, 0777);
	}
       
    define('WMP_FILES_UPLOADS_DIR',  $wmp_uploads_dir);
    define('WMP_FILES_UPLOADS_URL',  $wp_uploads_dir['baseurl'].'/wordpress-mobile-pack/');
}