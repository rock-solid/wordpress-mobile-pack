<?php if (!class_exists('PtPwa_Uploads')) {

    /**
     * Overall Uploads Management class
     *
     * Instantiates all the uploads and offers a number of utility methods to work with the options
     *
     */
    class PtPwa_Uploads {

        /* ----------------------------------*/
        /* Properties						 */
        /* ----------------------------------*/

        public static $allowed_files = array(
            'logo'          => array(
                'max_width'  => 120,
                'max_height' => 120,
                'extensions' => array('png')
            ),
            'icon'          => array(
                'max_width'  => 512,
                'max_height' => 512,
                'extensions' => array('jpg', 'jpeg', 'png', 'gif')
            ),
            'cover'         => array(
                'max_width'  => 1000,
                'max_height' => 1000,
                'extensions' => array('jpg', 'jpeg', 'png', 'gif')
            ),
            'category_icon' => array(
                'max_width'  => 500,
                'max_height' => 500,
                'extensions' => array('jpg', 'jpeg', 'png', 'gif')
            ),
        );

        public static $manifest_sizes = array(48, 96, 144, 196, 512);

        protected static $htaccess_template = 'frontend/sections/htaccess-template.txt';

        /**
         *
         * Define constants with the uploads dir paths
         *
         */
        public function define_uploads_dir() {

            $Pt_Pwa_Config = new Pt_Pwa_Config();
            $wp_uploads_dir = wp_upload_dir();

            define('PWA_FILES_UPLOADS_DIR', $wp_uploads_dir['basedir'] . '/' . $Pt_Pwa_Config->PWA_DOMAIN . '/');
            define('PWA_FILES_UPLOADS_URL', $wp_uploads_dir['baseurl'] . '/' . $Pt_Pwa_Config->PWA_DOMAIN . '/');

            /**
             * Fix for multi site option
             * If folder doesnt exist, create it now!
             */
            if (!file_exists(PWA_FILES_UPLOADS_DIR)) {
                mkdir(PWA_FILES_UPLOADS_DIR, 0777, true);
            }

            add_action('admin_notices', array($this, 'display_admin_notices'));
        }

        /**
         *
         * Display uploads folder specific admin notices.
         *
         */
        public function display_admin_notices() {

            $Pt_Pwa_Config = new Pt_Pwa_Config();

            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // If the directory doesn't exist, display notice
            if (!file_exists(PWA_FILES_UPLOADS_DIR)) {
                echo '<div class="error"><p><b>Warning!</b> The ' . $Pt_Pwa_Config->PWA_PLUGIN_NAME . ' uploads folder does not exist: ' . PWA_FILES_UPLOADS_DIR . '</p></div>';
            }

            if (!is_writable(PWA_FILES_UPLOADS_DIR)) {
                echo '<div class="error"><p><b>Warning!</b> The ' . $Pt_Pwa_Config->PWA_PLUGIN_NAME . ' uploads folder is not writable: ' . PWA_FILES_UPLOADS_DIR . '</p></div>';
            }
        }

        /**
         *
         * Create uploads folder
         *
         */
        public function create_uploads_dir() {

            $Pt_Pwa_Config = new Pt_Pwa_Config();

            $wp_uploads_dir = wp_upload_dir();

            $pt_pwa_uploads_dir = $wp_uploads_dir['basedir'] . '/' . $Pt_Pwa_Config->PWA_DOMAIN . '/';

            // check if the uploads folder exists and is writable
            if (file_exists($wp_uploads_dir['basedir']) && is_dir($wp_uploads_dir['basedir']) && is_writable($wp_uploads_dir['basedir'])) {

                // if the directory doesn't exist, create it
                if (!file_exists($pt_pwa_uploads_dir)) {

                    if (!mkdir($pt_pwa_uploads_dir, 0777) && !is_dir($pt_pwa_uploads_dir)) {
                        throw new InvalidArgumentException(sprintf('Directory "%s" was not created', $pt_pwa_uploads_dir));
                    }

                    // add .htaccess file in the uploads folder
                    $this->set_htaccess_file();
                }
            }
        }

        /**
         *
         * Clean up the uploads dir when the plugin is uninstalled
         *
         */
        public function remove_uploads_dir() {

            foreach (array('icon', 'logo', 'cover') as $image_type) {

                $image_path = PtPwa_Options::get_setting($image_type);

                if ($image_path != '' && $image_type == 'icon') {
                    foreach (self::$manifest_sizes as $manifest_size) {
                        $this->remove_uploaded_file($manifest_size . $image_path);
                    }
                }

                $this->remove_uploaded_file($image_path);
            }

            // remove categories images
            $categories_details = PtPwa_Options::get_setting('categories_details');

            if (is_array($categories_details) && !empty($categories_details)) {
                foreach ($categories_details as $category_details) {
                    if (is_array($category_details) && array_key_exists('icon', $category_details)) {
                        $this->remove_uploaded_file($category_details['icon']);
                    }
                }
            }

            // remove htaccess file
            $this->remove_htaccess_file();

            // delete folder
            rmdir(PWA_FILES_UPLOADS_DIR);
        }

        /**
         * Check if a file path exists in the uploads folder and returns its url.
         *
         * @param $file_path
         * @return string
         */
        public function get_file_url($file_path) {
            if (file_exists(PWA_FILES_UPLOADS_DIR . $file_path)) {
                return PWA_FILES_UPLOADS_URL . $file_path;
            }
            return '';
        }

        /**
         * Delete an uploaded file
         *
         * @param $file_path
         * @return bool
         *
         */
        public function remove_uploaded_file($file_path) {
            // Check the file exists and remove it
            if ($file_path != '' && file_exists(PWA_FILES_UPLOADS_DIR . $file_path)) {
                return unlink(PWA_FILES_UPLOADS_DIR . $file_path);
            }
            return true;
        }

        /**
         *
         * Create a .htaccess file with rules for compressing and caching static files for the plugin's upload folder
         * (css, images)
         *
         * @return bool
         *
         */
        protected function set_htaccess_file() {

            $Pt_Pwa_Config = new Pt_Pwa_Config();

            $file_path = PWA_FILES_UPLOADS_DIR . '.htaccess';

            if (!file_exists($file_path) && is_writable(PWA_FILES_UPLOADS_DIR)) {

                $template_path = $Pt_Pwa_Config->PWA_PLUGIN_PATH . self::$htaccess_template;

                if (file_exists($template_path)) {

                    $fp = @fopen($file_path, "w");
                    fwrite($fp, file_get_contents($template_path));
                    fclose($fp);

                    return true;
                }
            }

            return false;
        }

        /**
         *
         * Remote .htaccess file with rules for compressing and caching static files for the plugin's upload folder
         * (css, images)
         *
         * @return bool
         *
         */
        protected function remove_htaccess_file() {
            $file_path = PWA_FILES_UPLOADS_DIR . '.htaccess';

            if (file_exists($file_path)) {
                unlink($file_path);
            }

            return true;
        }
    }
}
