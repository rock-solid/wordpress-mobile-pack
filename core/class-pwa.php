<?php

    $Pt_Pwa_Config = new Pt_Pwa_Config();

    if (!class_exists('PtPwa_Options')) {
        require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'inc/class-pt-pwa-options.php');
    }

    if (!class_exists('PtPwa_Uploads')) {
        require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'inc/class-pt-pwa-uploads.php');
    }

    if (!interface_exists('PtPwaManager')) {
        require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'inc/pt-pwa-interface-manager.php');
    }

    if (!interface_exists('RouteMapper')) {
        require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'inc/interface-pt-pwa-route-mapper.php');
    }

    if (!class_exists('PtPwaRouteMapper')) {
        require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'inc/class-pt-pwa-route-mapper.php');
    }

    if (!class_exists('PtPwaTheme')) {
        require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'inc/class-pt-pwa-theme.php');
    }

    if (!class_exists('PtPwaThemeManager')) {
        require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'inc/class-pt-pwa-theme-manager.php');
    }

    if (!class_exists('PtPwaManifest')) {
        require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'inc/class-pt-pwa-manifest.php');
    }

    if (!class_exists('PtPwaManifestManager')) {
        require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'inc/class-pt-pwa-manifest-manager.php');
    }

    if (!class_exists('PtPwaFileHelper')) {
        require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'inc/class-pt-pwa-file-helper.php');
    }

    if (!class_exists('JsonSerializer')) {
        require_once($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'libs/json-serializer/JsonSerializer/JsonSerializer.php');
    }

    if (!class_exists('PtPwa')) {

        /**
         * PtPwa
         *
         * Main class for the Publishers Toolbox PWA plugin. This class handles:
         *
         * - activation / deactivation of the plugin
         * - setting / getting the plugin's options
         * - loading the admin section, javascript and css files
         * - loading the app in the frontend
         *
         */
        class PtPwa {

            /**
             *
             * Construct method that initializes the plugin's options
             *
             */
            public function __construct() {
                // create uploads folder and define constants
                if (!defined('PWA_FILES_UPLOADS_DIR') && !defined('PWA_FILES_UPLOADS_URL')) {
                    $PT_Uploads = new PtPwa_Uploads();
                    $PT_Uploads->define_uploads_dir();
                }

                // we only need notices for admins
                if (is_admin()) {
                    $this->setup_hooks();
                }
            }

            /**
             *
             * The activate() method is called on the activation of the plugin.
             *
             * This method adds to the DB the default settings of the plugin, creates the upload folder and
             * imports settings from the free version.
             *
             */
            public function activate() {
                // add settings to database
                PtPwa_Options::save_settings(PtPwa_Options::$options);

                $PT_Uploads = new PtPwa_Uploads();
                $PT_Uploads->create_uploads_dir();

                $this->backwards_compatibility();
            }

            /**
             *
             * The deactivate() method is called when the plugin is deactivated.
             * This method removes temporary data (transients and cookies).
             *
             */
            public function deactivate() {
                // delete plugin settings
                PtPwa_Options::deactivate();
            }

            /**
             * Init admin notices hook
             */
            public function setup_hooks() {
                add_action('admin_notices', array($this, 'display_admin_notices'));
                //Add svg support
                add_filter('upload_mimes', array($this, 'cc_mime_types'));
            }

            /**
             *
             * Show admin notice if license is not active
             *
             */
            public function display_admin_notices() {
                if (!current_user_can('manage_options')) {
                    wp_die(__('You do not have sufficient permissions to access this page.'));
                }

                if (version_compare(PHP_VERSION, '5.6') < 0) {
                    $Pt_Pwa_Config = new Pt_Pwa_Config();
                    echo '<div class="error"><p><b>Warning!</b> The ' . $Pt_Pwa_Config->PWA_PLUGIN_NAME . ' plugin requires at least PHP 5.6.0!</p></div>';
                }
            }

            /**
             * Transform settings to fit the new plugin structure
             */
            public function backwards_compatibility() {
                //Flush rules to set new pwa urls for MultiSite
                flush_rewrite_rules(false);
            }

            /**
             * @param $mimes
             * @return array
             */
            function cc_mime_types($mimes) {
                $mimes['svg'] = 'image/svg+xml';
                return $mimes;
            }

            /**
             *
             * Method used to check if a specific plugin is installed and active,
             * returns true if the plugin is installed and false otherwise.
             *
             * @param $plugin_name - the name of the plugin
             *
             * @return bool
             */
            public static function is_active_plugin($plugin_name) {

                $active_plugin = false; // by default, the search plugin does not exist

                // if the plugin name is empty return false
                if ($plugin_name != '') {

                    // if function doesn't exist, load plugin.php
                    if (!function_exists('get_plugins')) {
                        require_once ABSPATH . 'wp-admin/includes/plugin.php';
                    }

                    // get active plugins from the DB
                    $apl = get_option('active_plugins');

                    // get list with all the installed plugins
                    $plugins = get_plugins();

                    foreach ($apl as $p) {
                        if (isset($plugins[$p]) && $plugins[$p]['Name'] == $plugin_name) {
                            // check if the active plugin is the searched plugin
                            $active_plugin = true;
                        }
                    }
                }

                return $active_plugin; //return the active plugin variable
            }
        }
    }
