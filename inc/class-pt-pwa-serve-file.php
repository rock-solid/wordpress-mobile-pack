<?php if (!class_exists('FileEndpoint')) {

    class FileEndpoint {
        const ENDPOINT_QUERY_NAME = 'api';
        const ENDPOINT_QUERY_PARAM = 'pwa_files';

        /**
         * WordPress hooks
         */
        public function init() {
            add_filter('query_vars', array($this, 'add_query_vars'), 0);
            add_action('parse_request', array($this, 'sniff_requests'), 0);
            add_action('init', array($this, 'add_endpoint'), 0);
        }

        /**
         * Add public query vars
         *
         * @param $vars
         * @return array
         */
        public function add_query_vars($vars) {
            // add all the things we know we'll use
            $vars[] = static::ENDPOINT_QUERY_PARAM;
            return $vars;
        }

        /**
         * Add API Endpoint
         */
        public function add_endpoint() {
            add_rewrite_rule('[^\/"]+\.(json|js)', 'index.php?' . static::ENDPOINT_QUERY_PARAM . '=$matches[1]', 'top');

            // add_rewrite_rule('^' . static::ENDPOINT_QUERY_NAME . '/([^/]*)/?', 'index.php?' . static::ENDPOINT_QUERY_PARAM . '=$matches[1]', 'top');

            //////////////////////////////////
            flush_rewrite_rules(false); //// <---------- REMOVE THIS WHEN DONE
            //////////////////////////////////
        }

        /**
         * Sniff Requests
         *
         * @param $wp_query
         */
        public function sniff_requests($wp_query) {
            global $wp;

            if (isset($wp->query_vars[static::ENDPOINT_QUERY_PARAM])) {
                $this->handle_file_request(); // handle it
            }
        }

        /**
         * Handle Requests
         */
        protected function handle_file_request() {
            global $wp;

            /*
            global $wp_rewrite;
            echo '<pre style="clear:both;position:relative;z-index:9999;background-color:lightgrey;color:red;border:1px orange solid;padding:10px;">';
            print_r($wp_rewrite->rules);
            echo '</pre>';
            */

            $file = $wp->query_vars['pwa_files'];
            $filepath = '';

            switch ($file) {

                // example.com/files/xyz
                case 'json':
                    if ($wp->request == 'theme.json') {
                        $filepath = PWA_FILES_UPLOADS_DIR . 'theme.json';
                    } elseif ($wp->request == 'manifest.json') {
                        $filepath = PWA_FILES_UPLOADS_DIR . 'manifest.json';
                    }
                    break;
                case 'js':
                    if ($wp->request == 'service-worker.js') {
                        $filepath = PWA_FILES_UPLOADS_DIR . 'service-worker.js';
                    }
                    break;
            }

            if (!empty($filepath)) {

                // Make sure this is an accessible file
                // If we can't read it throw an Error
                if (!is_readable($filepath)) {
                    $err = new WP_Error("Forbidden", "Access is not allowed for this request.", 403);
                    wp_die($err->get_error_message(), $err->get_error_code());
                }

                // We can read it, so let's render it
                $this->serve_file($filepath);
            }

            // Nothing happened, just give some feedback
            $err = new WP_Error("Bad Request", "Invalid Request.", 400);
            wp_die($err->get_error_message(), $err->get_error_code());
        }

        /**
         * Output the file
         *
         * @param $filepath
         * @param bool $headers
         * @return bool
         */
        protected function serve_file($filepath, $headers = true) {

            if (!empty ($filepath)) {

                if ($headers) {
                    // Write some headers
                    $extension = end(explode(".", $filepath));
                    switch ($extension) {
                        case 'js' :
                            header('Content-Type: application/javascript');
                            break;
                        case 'json' :
                            header('Content-Type: application/json');
                            break;
                    }

                    header("Cache-control: private");
                    header("Content-transfer-encoding: binary\n");
                    header("Content-Length: " . filesize($filepath));
                }

                // render the contents of the file
                readfile($filepath);

                // kill the request. Nothing else to do now.
                exit;
            }

            // nothing happened, :(
            return false;
        }
    }

    $wpFileEndpoint = new FileEndpoint();
    $wpFileEndpoint->init();

}
