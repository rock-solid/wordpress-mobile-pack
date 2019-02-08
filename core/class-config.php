<?php

/**
 *
 * Define global constants
 *
 */

class Pt_Pwa_Config {

    public $PWA_PLUGIN_PATH;
    public $PWA_PLUGIN_NAME;
    public $PWA_DOMAIN;
    public $PWA_VERSION;
    public $PWA_ENABLED;

    public function __construct() {

        $this->PWA_PLUGIN_PATH      = WP_PLUGIN_DIR . '/publishers-toolbox-pwa/';
        $this->PWA_PLUGIN_NAME      = 'PT PWA';
        $this->PWA_DOMAIN           = 'publishers-toolbox-pwa';
        $this->PWA_VERSION          = '1.5.2';
        $this->PWA_ENABLED          = get_option('pt_pwa_enabled');
    }

    public function enable_pwa() {

        update_option('pt_pwa_enabled', TRUE);
        
    }

    public function disable_pwa() {

        update_option('pt_pwa_enabled', FALSE);
    
    }

}
