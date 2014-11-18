<?php

//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

delete_option('zem_rp_meta');
delete_option('zem_rp_options');