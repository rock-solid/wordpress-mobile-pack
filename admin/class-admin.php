<?php

    if (!class_exists('PtPwa_Admin')) {

        /**
         *
         * PtPwa_Admin class for managing the admin area for the Wordpress Mobile Pack plugin
         *
         */
        class PtPwa_Admin {

            /**
             *
             * Method used to render the main admin page
             *
             */
            public function whatsnew() {
                $Pt_Pwa_Config = new Pt_Pwa_Config();
                include($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/pages/whats-new.php');
            }

            /**
             *
             * Method used to render the themes selection page from the admin area
             *
             */
            public function themes() {
                $Pt_Pwa_Config = new Pt_Pwa_Config();
                include($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/pages/themes.php');
            }

            /**
             *
             * Method used to render the theme settings page from the admin area
             *
             */
            public function theme_settings() {
                $Pt_Pwa_Config = new Pt_Pwa_Config();
                include($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/pages/theme-settings.php');
            }

            /**
             * Build tree hierarchy for the pages array
             *
             * @param $all_pages
             * @return array
             */
            protected function build_pages_tree($all_pages) {

                $nodes_pages = array();

                foreach ($all_pages as $p) {

                    $nodes_pages[$p->ID] = array(
                        'id'        => $p->ID,
                        'parent_id' => intval($p->post_parent),
                        'obj'       => clone $p
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

                $Pt_Pwa_Config = new Pt_Pwa_Config();

                include($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/pages/content.php');
            }


            /**
             *
             * Method used to render the settings page from the admin area
             *
             */
            public function settings() {

                $Pt_Pwa_Config = new Pt_Pwa_Config();

                include($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/pages/settings.php');
            }

            /**
             *
             * Method used to render a form with a category's details
             *
             */
            public function category_content() {

                $Pt_Pwa_Config = new Pt_Pwa_Config();

                if (isset($_GET) && is_array($_GET) && !empty($_GET)) {

                    if (isset($_GET['id'])) {

                        if (is_numeric($_GET['id'])) {

                            // get category
                            $category = get_category($_GET['id']);

                            if ($category != NULL) {

                                // load view
                                include($Pt_Pwa_Config->PWA_PLUGIN_PATH . 'admin/pages/category-details.php');
                            }
                        }
                    }
                }
            }
        }
    }
