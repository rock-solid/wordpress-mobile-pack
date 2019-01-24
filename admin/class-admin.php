<?php

if ( ! class_exists( 'WMobilePack_Admin' ) ) {

    /**
     *
     * WMobilePack_Admin class for managing the admin area for the Wordpress Mobile Pack plugin
     *
     */
    class WMobilePack_Admin
    {

        /**
         *
         * Method used to render the main admin page
         *
         */
        public function whatsnew() {

            include(PWA_PLUGIN_PATH.'admin/pages/whats-new.php');
        }

		/**
         *
         * Method used to render the themes selection page from the admin area
         *
         */
        public function themes() {

            include(PWA_PLUGIN_PATH.'admin/pages/themes.php');
        }

        /**
         *
         * Method used to render the theme settings page from the admin area
         *
         */
        public function theme_settings() {

            include(PWA_PLUGIN_PATH.'admin/pages/theme-settings.php');
        }


        /**
         * Build tree hierarchy for the pages array
         *
         * @param $all_pages
         * @return array
         */
        protected function build_pages_tree($all_pages){

            $nodes_pages = array();

            foreach ($all_pages as $p) {

                $nodes_pages[$p->ID] = array(
                    'id' => $p->ID,
                    'parent_id' => intval($p->post_parent),
                    'obj' => clone $p
                );
            }

            $pages_tree = array(0 => array());

            foreach ($nodes_pages as $n) {

                $pid = $n['parent_id'];
                $id = $n['id'];

                if (!isset($pages_tree[$pid]))
                    $pages_tree[$pid] = array('child' => array());

                if (isset($pages_tree[$id]))
                    $child = &$pages_tree[$id]['child'];
                else
                    $child = array();

                $pages_tree[$id] = $n;
                $pages_tree[$id]['child'] = &$child;
                unset($pages_tree[$id]['parent_id']);
                unset($child);

                $pages_tree[$pid]['child'][] = &$pages_tree[$id];
            }

            if (!empty($pages_tree) && !empty($pages_tree[0])) {
                $nodes_pages = $pages_tree[0]['child'];
                unset($pages_tree);
                return $nodes_pages;
            }

            return array();

        }


        /**
         *
         * Method used to render the content selection page from the admin area
         *
         */
        public function content() {

            $all_pages = get_pages(array('sort_column' => 'menu_order,post_title'));
            $pages = $this->build_pages_tree($all_pages);

            include(PWA_PLUGIN_PATH.'admin/pages/content.php');
        }


        /**
         *
         * Method used to render the settings page from the admin area
         *
         */
        public function settings() {

            include(PWA_PLUGIN_PATH.'admin/pages/settings.php');
        }

        /**
         *
         * Method used to render a form with a category's details
         *
         */
        public function category_content() {

            if (isset($_GET) && is_array($_GET) && !empty($_GET)){

                if (isset($_GET['id'])) {

                    if (is_numeric($_GET['id'])) {

                        // get category
                        $category = get_category($_GET['id']);

                        if ($category != null) {

                            // load view
                            include(PWA_PLUGIN_PATH.'admin/pages/category-details.php');
                        }
                    }
                }
            }
        }


        /**
         *
         * Method used to render a form with a page's details
         *
         */
        public function page_content() {

            if (isset($_GET) && is_array($_GET) && !empty($_GET)){

                if (isset($_GET['id'])) {

                    if (is_numeric($_GET['id'])) {

                        // get page
                        $page = get_page($_GET['id']);

                        if ($page != null) {

                            if (!class_exists('WMobilePack_Formatter')) {
                                require_once(PWA_PLUGIN_PATH . 'inc/class-wmp-formatter.php');
                            }

                            $purifier = WMobilePack_Formatter::init_purifier();

                            // first check if the admin edited the content for this page
                            if (get_option(WMobilePack_Options::$prefix.'page_' .$page->ID) === false)
                                $content = apply_filters("the_content", $page->post_content);
                            else
                                $content = apply_filters("the_content", get_option( WMobilePack_Options::$prefix.'page_' .$page->ID  ));

                            $content = $purifier->purify(stripslashes($content));

                            // load view
                            include(PWA_PLUGIN_PATH.'admin/pages/page-details.php');
                        }
                    }
                }
            }
        }
    }
}
