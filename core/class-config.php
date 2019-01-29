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

    public function __construct() {

        $this->PWA_PLUGIN_PATH      = WP_PLUGIN_DIR . '/publishers-toolbox-pwa/';
        $this->PWA_PLUGIN_NAME      = 'PT PWA';
        $this->PWA_DOMAIN           = 'publishers-toolbox-pwa';
        $this->PWA_VERSION          = '1.0';

    }

}