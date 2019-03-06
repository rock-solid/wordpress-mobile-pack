<?php

if ( ! class_exists( 'PtPwa_Admin_Ajax' ) ) {

    /**
     *
     * PtPwa_Admin_Ajax class for managing Ajax requests from the admin area of the Wordpress Mobile Pack plugin
     *
     * @todo Test separately the methods of this class
     */
    class PtPwa_Admin_Ajax
    {
        /**
         *
         * Create an uploads management object and return it
         *
         * @return object
         *
         */
        protected function get_uploads_manager()
        {
            return new PtPwa_Uploads();
        }

        /**
         * Resize & copy image using Wordpress methods
         *
         * @param $file_type = icon, logo, cover or category_icon
         * @param $file_path
         * @param $file_name
         * @param string $error_message
         * @return bool
         *
         */
        protected function resize_image($file_type, $file_path, $file_name, &$error_message = '')
        {

            $copied_and_resized = false;

            if (array_key_exists($file_type, PtPwa_Uploads::$allowed_files)) {

                $arrMaximumSize = PtPwa_Uploads::$allowed_files[$file_type];

                $image = wp_get_image_editor($file_path);

                if (!is_wp_error($image)) {

					$image_size = $image->get_size();

					if ($file_type == 'icon') {

						foreach (PtPwa_Uploads::$manifest_sizes as $manifest_size) {

							$manifest_image = wp_get_image_editor($file_path);
							$manifest_image->resize($manifest_size, $manifest_size, true);
							$manifest_image->save(PWA_FILES_UPLOADS_DIR . $manifest_size . $file_name);
						}

					}

                    // if the image exceeds the size limits
                    if ($image_size['width'] > $arrMaximumSize['max_width'] || $image_size['height'] > $arrMaximumSize['max_height']) {

                        // resize and copy to the plugin uploads folder
                        $image->resize($arrMaximumSize['max_width'], $arrMaximumSize['max_height']);
                        $image->save(PWA_FILES_UPLOADS_DIR . $file_name);

                        $copied_and_resized = true;

                    } else {

                        // copy file without resizing to the plugin uploads folder
                        $copied_and_resized = copy($file_path, PWA_FILES_UPLOADS_DIR . $file_name);
                    }

                } else {

                    $error_message = "We encountered a problem resizing your " . ($file_type == 'category_icon' ? 'image' : $file_type) . ". Please choose another image!";
                }

            }

            return $copied_and_resized;
		}


        /**
         *
         * Method used to save the categories  pages status settings in the database
         *
         */
        public function content_status()
        {

            if (current_user_can( 'manage_options' )){

                $status = 0;

                if (isset($_POST) && is_array($_POST) && !empty($_POST)){

                    if (isset($_POST['id']) && isset($_POST['status']) && isset($_POST['type'])){

                        if (is_numeric($_POST['id']) &&
                            ($_POST['status'] == 'active' || $_POST['status'] == 'inactive') &&
                            ($_POST['type'] == 'category' || $_POST['type'] == 'page')){

                            $status = 1;

                            $item_id = intval($_POST['id']);
                            $item_status = strval($_POST['status']);

                            // get inactive items option
                            if ($_POST['type'] == 'category')
                                $inactive_items = PtPwa_Options::get_setting('inactive_categories');
                            else
                                $inactive_items = PtPwa_Options::get_setting('inactive_pages');

                            // add or remove the item from the options array
                            if (in_array($item_id, $inactive_items) && $item_status == 'active')
                                $inactive_items = array_diff($inactive_items, array($item_id));

                            if (!in_array($item_id, $inactive_items) && $item_status == 'inactive')
                                $inactive_items[] = $item_id;

                            // save option
                            if ($_POST['type'] == 'category')
                                PtPwa_Options::update_settings('inactive_categories', $inactive_items);
                            else
                                PtPwa_Options::update_settings('inactive_pages', $inactive_items);
                        }
                    }
                }

                echo $status;
            }

            exit();
        }



        /**
         *
         * Method used to save the order of categories in the database
         *
         */
        public function content_order()
        {

            if (current_user_can( 'manage_options' )){

                $status = 0;

                if (isset($_POST) && is_array($_POST) && !empty($_POST)){

                    if (isset($_POST['ids']) && isset($_POST['type'])){

                        if ($_POST['ids'] != '' && $_POST['type'] == 'categories'){

                            // Retrieve the ids list from the param
                            $items_ids = array_filter(explode(",", $_POST['ids']));

                            if (count($items_ids) > 0) {

                                // Check if the received ids are numeric
                                $valid_ids = true;

                                foreach ($items_ids as $item_id) {

                                    if (!is_numeric($item_id)){
                                        $valid_ids = false;
                                    }
                                }

                                if ($valid_ids) {

                                    $status = 1;

                                    // Save option
                                    PtPwa_Options::update_settings('ordered_categories', $items_ids);
                                }
                            }
                        }
                    }
                }

                echo $status;
            }

            exit();
        }

        /**
         *
         * Method used to save the app settings (display mode, google analytics id, etc.)
         *
         */
        public function settings_app()
        {

            if (current_user_can( 'manage_options' )) {

                $status = 0;

                if (isset($_POST) && is_array($_POST) && !empty($_POST)){

                    if (isset($_POST['wmp_editsettings_displaymode']) && $_POST['wmp_editsettings_displaymode'] != '' &&
						isset($_POST['wmp_editsettings_enable_tablets']) && is_numeric($_POST['wmp_editsettings_enable_tablets']) &&
                        isset($_POST['wmp_editsettings_displaywebsitelink']) && is_numeric($_POST['wmp_editsettings_displaywebsitelink']) &&
                        isset($_POST['wmp_editsettings_postsperpage']) && $_POST['wmp_editsettings_postsperpage'] != ''){

                        if (in_array($_POST['wmp_editsettings_displaymode'], array('normal', 'preview', 'disabled')) &&
                            in_array($_POST['wmp_editsettings_postsperpage'], array('auto', 'single', 'double'))){

                            $status = 1;

                            // save google analytics id
                            if (isset($_POST["wmp_editsettings_ganalyticsid"])) {

                                // validate google analytics id
                                if (preg_match('/^ua-\d{4,9}-\d{1,4}$/i', strval($_POST["wmp_editsettings_ganalyticsid"])))
                                    PtPwa_Options::update_settings('google_analytics_id', $_POST['wmp_editsettings_ganalyticsid']);
                                elseif ($_POST["wmp_editsettings_ganalyticsid"] == "")
                                    PtPwa_Options::update_settings('google_analytics_id', "");
                            }

                            // save other options
                            PtPwa_Options::update_settings('display_mode', $_POST['wmp_editsettings_displaymode']);
							PtPwa_Options::update_settings('enable_tablets', intval($_POST['wmp_editsettings_enable_tablets']));
                            PtPwa_Options::update_settings('display_website_link', intval($_POST['wmp_editsettings_displaywebsitelink']));
                            PtPwa_Options::update_settings('posts_per_page', $_POST['wmp_editsettings_postsperpage']);
                        }
                    }
                }

                echo $status;
            }

            exit();
		}


        /**
         *
         * Save social media and other opt-ins settings
         *
         */
        public function settings_save()
        {

            if (current_user_can( 'manage_options' )) {

                $status = 0;

                if (isset($_POST) && is_array($_POST) && !empty($_POST)) {

                    // handle opt-ins settings
                    foreach (array('enable_facebook', 'enable_twitter', 'enable_google','allow_tracking', 'upgrade_notice_updated', 'service_worker_installed') as $option_name) {

                        if (isset($_POST['wmp_option_'.$option_name]) && $_POST['wmp_option_'.$option_name] != '' && is_numeric($_POST['wmp_option_'.$option_name])) {

                            $enabled_option = intval($_POST['wmp_option_'.$option_name]);

                            if ($enabled_option == 0 || $enabled_option == 1) {

                                $status = 1;

                                // save option
                                PtPwa_Options::update_settings($option_name, $enabled_option);

                                if ($option_name == 'allow_tracking'){

                                    // update cron schedule
                                    PtPwa::schedule_tracking($enabled_option);
                                }
                            }
                        }
                    }
                }

                echo $status;
            }

            exit();
        }

        /**
         *
         * Save waitlist settings
         *
         */
        public function settings_waitlist()
        {

            if (current_user_can('manage_options')) {

                $status = 0;

                if (isset($_POST) && is_array($_POST) && !empty($_POST)) {

                    // handle joined waitlists
                    if (isset($_POST['joined_waitlist']) && $_POST['joined_waitlist'] != '') {

                        if (in_array($_POST['joined_waitlist'], array('content', 'settings', 'lifestyletheme', 'businesstheme', 'themes_features'))) {

                            $option_waitlists = PtPwa_Options::get_setting('joined_waitlists');

                            if ($option_waitlists == null || !is_array($option_waitlists)) {
                                $option_waitlists = array();
                            }

                            if (!in_array($_POST['joined_waitlist'], $option_waitlists)) {

                                $status = 1;

                                $option_waitlists[] = $_POST['joined_waitlist'];

                                // save option
                                PtPwa_Options::update_settings('joined_waitlists', $option_waitlists);
                            }
                        }
                    }
                }

                echo $status;
            }

            exit();
        }

    }
}
