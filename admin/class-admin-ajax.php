<?php

if ( ! class_exists( 'WMobilePack_Themes_Config' )) {
    require_once(WMP_PLUGIN_PATH.'inc/class-wmp-themes-config.php');
}

if ( ! class_exists( 'WMobilePack_Admin_Ajax' ) ) {

    /**
     *
     * WMobilePack_Admin_Ajax class for managing Ajax requests from the admin area of the Wordpress Mobile Pack plugin
     *
     * @todo Test separately the methods of this class
     */
    class WMobilePack_Admin_Ajax
    {

        /**
         *
         * Create a theme management object and return it
         *
         * @return object
         *
         */
        protected function get_theme_manager()
        {
            if ( ! class_exists( 'WMobilePack_Themes_Compiler' ) && version_compare(PHP_VERSION, '5.3') >= 0 ) {
                require_once(WMP_PLUGIN_PATH.'inc/class-wmp-themes-compiler.php');
            }

            if (class_exists('WMobilePack_Themes_Compiler')) {
                return new WMobilePack_Themes_Compiler();
            }

            return false;
        }


        /**
         *
         * Create an uploads management object and return it
         *
         * @return object
         *
         */
        protected function get_uploads_manager()
        {
            return new WMobilePack_Uploads();
        }


        /**
         *
         * Create a premium management object and return it
         *
         * @return object
         *
         */
        protected function get_premium_manager()
        {
            // attempt to load the settings json
            if (!class_exists('WMobilePack_Premium')) {
                require_once(WMP_PLUGIN_PATH . 'inc/class-wmp-premium.php');
            }

            return new WMobilePack_Premium();
        }


		/**
         * Validate the new font settings, using the theme's configuration.
         *
         * @param $data = array with POST data
         * @param $allowed_font_settings = array with the allowed font settings from the theme's configuration
		 *
         * @return bool
         */
		protected function validate_theme_fonts($data, $allowed_font_settings)
		{

			foreach (array('headlines', 'subtitles', 'paragraphs') as $font_type){

				if (array_key_exists($font_type.'-font', $allowed_font_settings)) {

					if (!isset($data['wmp_edittheme_font'.$font_type]) ||
						!in_array($data['wmp_edittheme_font'.$font_type] - 1, array_keys(WMobilePack_Themes_Config::$allowed_fonts))){

						return false;
					}
				}
			}

			return true;
		}


        /**
         * Save new font settings into the database. Returns true if we need to compile the css file.
         *
         * @param $data = array with POST data
		 * @param $allowed_font_settings = array with the allowed font settings from the theme's configuration
         *
         * @return array with the following properties:
         * - scss - If we need to compile the theme
         * - updated - If any of the font settings have changed
         *
         */
        protected function update_theme_fonts($data, $allowed_font_settings)
        {

            // check if we have to compile the scss file
            $response = array(
                'scss' => false,
                'updated' => false
            );

			foreach (array('headlines', 'subtitles', 'paragraphs') as $font_type) {

                if (isset($data['wmp_edittheme_font'.$font_type]) && array_key_exists($font_type.'-font', $allowed_font_settings)) {

                    // check if the font settings have changed
                    if ($data['wmp_edittheme_font'.$font_type] != WMobilePack_Options::get_setting('font_'.$font_type)) {

                        WMobilePack_Options::update_settings('font_' . $font_type, $data['wmp_edittheme_font' . $font_type]);
                        $response['updated'] = true;
                    }

                    // if a font different from the default one was selected, we need to compile the css file
                    if ($data['wmp_edittheme_font'.$font_type] != 1) {
                        $response['scss'] = true;
                    }
                }
            }

            return $response;
        }


        /**
         *
         * Save new color scheme setting into the database. Returns true if we need to compile the css file.
         *
         * @param $data = array with POST data
         *
         * @return array with the following properties:
         * - scss - If we need to compile the theme
         * - updated - If the color scheme setting has changed
         *
         */
        protected function update_theme_color_scheme($data)
        {

            // check if we have to compile the scss file
            $response = array(
                'scss' => false,
                'updated' => false
            );

            if (isset($data['wmp_edittheme_colorscheme'])) {

                if (WMobilePack_Options::get_setting('color_scheme') != $data['wmp_edittheme_colorscheme']) {

                    WMobilePack_Options::update_settings('color_scheme', $data['wmp_edittheme_colorscheme']);
                    $response['updated'] = true;
                }

                // enable compiling for the second & third color schemes
                if ($data['wmp_edittheme_colorscheme'] != 1) {
                    $response['scss'] = true;
                }
            }

            return $response;
        }


        /**
         * Save new colors settings into the database. Returns true if we need to compile the css file.
         *
         * @param $data = array with POST data
		 * @param $colors_variables = array with the color variables names from the theme configuration
         *
         * @return array with the following properties:
         * - scss - If we need to compile the theme
         * - error = If set to true if we have invalid color codes or the number of colors is not the same
         * with the one from the theme.
         *
         *
         */
        protected function update_theme_colors($data, $colors_variables)
        {

            $response = array(
                'scss' => false,
                'error' => false
            );

            $arr_custom_colors = array();

            // read theme and custom colors options
            $selected_custom_colors = WMobilePack_Options::get_setting('custom_colors');

            // how many colors does the theme have
            $no_theme_colors = count($colors_variables);

            for ($i = 0; $i < $no_theme_colors; $i++) {

                // validate color code format
                if (isset($data['wmp_edittheme_customcolor' . $i]) &&
                    trim($data['wmp_edittheme_customcolor' . $i]) != '' &&
                    preg_match('/^#[a-f0-9]{6}$/i', trim($data['wmp_edittheme_customcolor' . $i]))) {

                    $arr_custom_colors[] = strtolower($data['wmp_edittheme_customcolor' . $i]);

                    // if the color settings have changed, we need to recompile the css file
                    if (empty($selected_custom_colors) ||
                        (isset($selected_custom_colors[$i]) && strtolower($data['wmp_edittheme_customcolor' . $i]) != $selected_custom_colors[$i])){

                        $response['scss'] = true;
                    }

                } else {
                    $response['error'] = true;
                    break;
                }
            }

            // save colors only if all the colors from the theme have been set
            if (count($arr_custom_colors) == $no_theme_colors){

                WMobilePack_Options::update_settings('custom_colors', $arr_custom_colors);

            } else {

                $response['error'] = true;
                $response['scss'] = false;
            }

            return $response;
        }

		/**
         *
         * Reset all theme settings when a theme is changed
         *
         */
        protected function reset_theme_settings()
        {

            // reset color schemes and fonts
            WMobilePack_Options::update_settings('color_scheme', 1);
            WMobilePack_Options::update_settings('custom_colors', array());
            WMobilePack_Options::update_settings('font_headlines', 1);
            WMobilePack_Options::update_settings('font_subtitles', 1);
            WMobilePack_Options::update_settings('font_paragraphs', 1);
            WMobilePack_Options::update_settings('font_size', 1);

            $this->remove_custom_theme();
        }


        /**
         *
         * Delete custom theme file and reset option
         */
        protected function remove_custom_theme(){

            // remove compiled css file (if it exists)
            $theme_timestamp = WMobilePack_Options::get_setting('theme_timestamp');

            if ($theme_timestamp != ''){

                $wmp_themes_compiler = $this->get_theme_manager();

                if ($wmp_themes_compiler !== false) {

                    $wmp_themes_compiler->remove_css_file($theme_timestamp);
                    WMobilePack_Options::update_settings('theme_timestamp', '');
                }
            }
        }


		/**
         *
         * Method used to switch to a new theme
         *
         */
        public function theme_switch()
        {

            if (current_user_can('manage_options')) {

                $status = 0;

                if (!empty($_GET) && isset($_GET['theme'])){
                    if (in_array($_GET['theme'], array_keys(WMobilePack_Themes_Config::get_allowed_themes()) )) {

                        $new_theme = $_GET['theme'];

                        if (WMobilePack_Options::get_setting('theme') != $new_theme){

                            $status = 1;
                            WMobilePack_Options::update_settings('theme', $new_theme);
                            $this->reset_theme_settings();
                        }
                    }
                }

                echo $status;
            }

            exit();
        }

        /**
         *
         * Method used to save the custom settings for a theme.
         *
         * Displays a JSON response with the following fields:
         *
         * - status = 0 if an error has occurred, 1 otherwise
         * - messages = array with error messages, possible values are:
         *
         * - invalid custom colors format
         * - settings were not changed
         * - other error messages resulted from compiling the theme
         *
         */
        public function theme_settings()
        {
            if (current_user_can('manage_options')) {

                $arr_response = array(
                    'status' => 0,
                    'messages' => array()
                );

				// get the theme's  configuration
            	$theme_config = WMobilePack_Themes_Config::get_theme_config();

            	if ($theme_config !== false) {

					// build array with the allowed fonts sizes
					$allowed_fonts_sizes = array();
					foreach (WMobilePack_Themes_Config::$allowed_fonts_sizes as $allowed_font_size) {
						$allowed_fonts_sizes[] = $allowed_font_size['size'];
					}

					// handle color schemes and fonts (look & feel page)
					if ($this->validate_theme_fonts($_POST, $theme_config['fonts']) &&
						isset($_POST['wmp_edittheme_colorscheme']) && in_array($_POST['wmp_edittheme_colorscheme'], array(0,1,2,3))){

                        // check if the theme compiler can be successfully loaded
                        $wmp_themes_compiler = $this->get_theme_manager();

                        if ($wmp_themes_compiler === false) {

                            $arr_response['messages'][] = 'Unable to load theme compiler. Please check your PHP version, should be at least 5.3.';

                        } else {

                            // save custom colors first
                            $updated_colors = array('scss' => false, 'error' => false);

                            if ($_POST['wmp_edittheme_colorscheme'] == 0) {

                                $updated_colors = $this->update_theme_colors($_POST, $theme_config['vars']);

                                // if the colors were not successfully processed, display error message and exit
                                if ($updated_colors['error']) {

                                    $arr_response['messages'][] = 'Please select all colors before saving the custom color scheme!';
                                    echo json_encode($arr_response);

                                    wp_die();
                                }
                            }

                            // update fonts and check if we need to compile the scss file
                            $updated_fonts = $this->update_theme_fonts($_POST, $theme_config['fonts']);

                            // update color scheme
                            $updated_color_scheme = $this->update_theme_color_scheme($_POST);

                            // the settings haven't changed, so return error status
                            if (!$updated_colors['scss'] && !$updated_fonts['updated'] && !$updated_color_scheme['updated']) {

                                $arr_response['messages'][] = 'Your application\'s settings have not changed!';

                            } else {

                                if ($updated_colors['scss'] || $updated_fonts['scss'] || $updated_color_scheme['scss']) {

                                    $theme_timestamp = time();

                                    // create new css theme file
                                    $theme_compiled = $wmp_themes_compiler->compile_css_file($theme_timestamp);

                                    if (!$theme_compiled['compiled']) {
                                        $arr_response['messages'][] = $theme_compiled['error'];
                                    } else {

                                        // delete old css file (if it exists)
                                        $old_theme_timestamp = WMobilePack_Options::get_setting('theme_timestamp');

                                        // update theme timestamp
                                        WMobilePack_Options::update_settings('theme_timestamp', $theme_timestamp);

                                        if ($old_theme_timestamp != '') {
                                            $wmp_themes_compiler->remove_css_file($old_theme_timestamp);
                                        }

                                        // the theme was successfully compiled and saved
                                        $arr_response['status'] = 1;
                                    }


                                } else {

                                    // we have reverted to the default theme settings, remove custom theme file
                                    $this->remove_custom_theme();
                                    $arr_response['status'] = 1;
                                }
                            }
                        }
                    }
                }

                echo json_encode($arr_response);
            }

            wp_die();
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

            if (array_key_exists($file_type, WMobilePack_Uploads::$allowed_files)) {

                $arrMaximumSize = WMobilePack_Uploads::$allowed_files[$file_type];

                $image = wp_get_image_editor($file_path);

                if (!is_wp_error($image)) {

					$image_size = $image->get_size();

					if ($file_type == 'icon') {

						foreach (WMobilePack_Uploads::$manifest_sizes as $manifest_size) {

							$manifest_image = wp_get_image_editor($file_path);
							$manifest_image->resize($manifest_size, $manifest_size, true);
							$manifest_image->save(WMP_FILES_UPLOADS_DIR . $manifest_size . $file_name);
						}

					}

                    // if the image exceeds the size limits
                    if ($image_size['width'] > $arrMaximumSize['max_width'] || $image_size['height'] > $arrMaximumSize['max_height']) {

                        // resize and copy to the plugin uploads folder
                        $image->resize($arrMaximumSize['max_width'], $arrMaximumSize['max_height']);
                        $image->save(WMP_FILES_UPLOADS_DIR . $file_name);

                        $copied_and_resized = true;

                    } else {

                        // copy file without resizing to the plugin uploads folder
                        $copied_and_resized = copy($file_path, WMP_FILES_UPLOADS_DIR . $file_name);
                    }

                } else {

                    $error_message = "We encountered a problem resizing your " . ($file_type == 'category_icon' ? 'image' : $file_type) . ". Please choose another image!";
                }

            }

            return $copied_and_resized;
		}


        /**
         *
         * Remove image using the corresponding option's value for the filename
         *
         * @param $file_type = icon, logo or cover
         * @return bool
         */
        protected function remove_image($file_type)
        {

            // get previous image filename
            $previous_file_path = WMobilePack_Options::get_setting($file_type);

            // check the file exists and remove it
            if ($previous_file_path != '') {
				$WMP_Uploads = $this->get_uploads_manager();

				if ($file_type == 'icon') {

					foreach (WMobilePack_Uploads::$manifest_sizes as $manifest_size) {
						$WMP_Uploads->remove_uploaded_file($manifest_size . $previous_file_path);
					}

				}

                return $WMP_Uploads->remove_uploaded_file($previous_file_path);
            }

            return false;
        }


        /**
         * Remove a category's icon file
         *
         * @param $category_id
         * @return bool
         */
        protected function remove_image_category($category_id){

            $categories_details = WMobilePack_Options::get_setting('categories_details');

            if (is_array($categories_details)) {

                if (array_key_exists($category_id, $categories_details)) {

                    if (is_array($categories_details[$category_id])) {

                        if (array_key_exists('icon', $categories_details[$category_id])) {

                            $previous_file_path = $categories_details[$category_id]['icon'];

                            // check the file exists and remove it
                            $WMP_Uploads = $this->get_uploads_manager();
                            return $WMP_Uploads->remove_uploaded_file($previous_file_path);
                        }
                    }
                }
            }

            return false;
        }

        /**
         *
         * Method used to save the icon, logo, cover or category image
         *
         */
        public function theme_editimages()
        {

            if (current_user_can( 'manage_options' )){

                $action = null;

                if (!empty($_GET) && isset($_GET['type']))
                    if ($_GET['type'] == 'upload' || $_GET['type'] == 'delete')
                        $action = $_GET['type'];

                $arr_response = array(
                    'status' => 0,
                    'messages' => array()
                );

                if ($action == 'upload'){

                    if (!empty($_FILES) && sizeof($_FILES) > 0){

                        require_once(ABSPATH . 'wp-admin/includes/image.php');

                        if (!function_exists( 'wp_handle_upload' ))
                            require_once( ABSPATH . 'wp-admin/includes/file.php' );

                        $default_uploads_dir = wp_upload_dir();

                        // check if the upload folder is writable
                        if (!is_writable(WMP_FILES_UPLOADS_DIR)){

                            $arr_response['messages'][] = "Error uploading image, the upload folder ".WMP_FILES_UPLOADS_DIR." is not writable.";

                        } elseif (!is_writable($default_uploads_dir['path'])) {

                            $arr_response['messages'][] = "Error uploading image, the upload folder ".$default_uploads_dir['path']." is not writable.";

                        } else {

                            $has_uploaded_files = false;

                            foreach ($_FILES as $file => $info) {

                                if (!empty($info['name'])){

                                    $has_uploaded_files = true;

                                    $file_type = null;

                                    if ($file == 'wmp_editimages_icon') {
                                        $file_type = 'icon';
                                    } elseif ($file == 'wmp_editimages_logo'){
                                        $file_type = 'logo';
                                    } elseif ($file == 'wmp_editcover_cover'){
                                        $file_type = 'cover';
                                    } elseif ($file == 'wmp_categoryedit_icon') {
                                        $file_type = 'category_icon';
                                    }

                                    if ($info['error'] >= 1 || $info['size'] <= 0 && array_key_exists($file_type, WMobilePack_Uploads::$allowed_files)) {

                                        $arr_response['status'] = 0;
                                        $arr_response["messages"][] = "We encountered a problem processing your ".($file_type == 'category_icon' ? 'image' : $file_type).". Please choose another image!";

                                    } elseif ($info['size'] > 1048576){

                                        $arr_response['status'] = 0;
                                        $arr_response["messages"][] = "Do not exceed the 1MB file size limit when uploading your custom ".($file_type == 'category_icon' ? 'image' : $file_type).".";

                                    } elseif ($file_type == 'category_icon' && (!isset($_POST['wmp_categoryedit_id']) || !is_numeric($_POST['wmp_categoryedit_id']))) {

                                        // If the category icon file is NOT accompanied by the category ID, default to the error message
                                        $arr_response['status'] = 0;

                                    } else {

                                        /****************************************/
                                        /*										*/
                                        /* SET FILENAME, ALLOWED FORMATS AND SIZE */
                                        /*										*/
                                        /****************************************/

                                        // make unique file name for the image
                                        $arrFilename = explode(".", $info['name']);
                                        $fileExtension = end($arrFilename);

                                        $arrAllowedExtensions = WMobilePack_Uploads::$allowed_files[$file_type]['extensions'];

                                        // check file extension
                                        if (!in_array(strtolower($fileExtension), $arrAllowedExtensions)) {

                                            $arr_response['messages'][] = "Error saving image, please add a ".implode(' or ',$arrAllowedExtensions)." image for your ".($file_type == 'category_icon' ? 'category' : $file_type)."!";

                                        } else {

                                            /****************************************/
                                            /*										*/
                                            /* UPLOAD IMAGE                         */
                                            /*										*/
                                            /****************************************/

                                            $uniqueFilename = $file_type.'_'.time().'.'.$fileExtension;

                                            // upload to the default uploads folder
                                            $upload_overrides = array( 'test_form' => false );
                                            $movefile = wp_handle_upload( $info, $upload_overrides );

                                            if (is_array($movefile)) {

                                                if (isset($movefile['error'])) {

                                                    $arr_response['messages'][] = $movefile['error'];

                                                } else {

                                                    /****************************************/
                                                    /*										*/
                                                    /* RESIZE AND COPY IMAGE                */
                                                    /*										*/
                                                    /****************************************/

                                                    $copied_and_resized = $this->resize_image($file_type, $movefile['file'], $uniqueFilename, $error_message);

                                                    if ($error_message != ''){
                                                        $arr_response["messages"][] = $error_message;
                                                    }

                                                    /****************************************/
                                                    /*										*/
                                                    /* DELETE PREVIOUS IMAGE AND SET OPTION */
                                                    /*										*/
                                                    /****************************************/

                                                    if ($copied_and_resized) {

                                                        if ($file_type == 'category_icon') {

                                                            // delete previous image
                                                            $this->remove_image_category($_POST['wmp_categoryedit_id']);

                                                            // update categories settings array
                                                            $categories_details = WMobilePack_Options::get_setting('categories_details');
                                                            $categories_details[$_POST['wmp_categoryedit_id']] = array('icon' => $uniqueFilename);

                                                            WMobilePack_Options::update_settings('categories_details', $categories_details);

                                                        } else {

                                                            // delete previous image
                                                            $this->remove_image($file_type);

                                                            // save option
                                                            WMobilePack_Options::update_settings($file_type, $uniqueFilename);
                                                        }

                                                        // add path in the response
                                                        $arr_response['status'] = 1;
                                                        $arr_response['uploaded_' . $file_type] = WMP_FILES_UPLOADS_URL . $uniqueFilename;
                                                    }

                                                    // remove file from the default uploads folder
                                                    if (file_exists($movefile['file']))
                                                        unlink($movefile['file']);
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if ($has_uploaded_files == false){
                                $arr_response['messages'][] = "Please upload an image!";
                            }
                        }
                    }

                } elseif ($action == 'delete'){

                    /****************************************/
                    /*										*/
                    /* DELETE ICON / LOGO / COVER       	*/
                    /*										*/
                    /****************************************/

                    // delete icon, logo or cover, depending on the 'source' param
                    if (isset($_GET['source'])) {

                        if (array_key_exists($_GET['source'], WMobilePack_Uploads::$allowed_files)){

                            $file_type = $_GET['source'];

                            if ($file_type == 'category_icon' && isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {

                                // delete previous image
                                $this->remove_image_category($_GET['category_id']);

                                // update categories settings array
                                $categories_details = WMobilePack_Options::get_setting('categories_details');
                                unset($categories_details[ $_GET['category_id'] ]);

                                WMobilePack_Options::update_settings('categories_details', $categories_details);

                                $arr_response['status'] = 1;

                            } elseif (in_array($file_type, array('icon', 'logo', 'cover'))) {

                                // get the previous file name from the options table
                                $this->remove_image($file_type);

                                // save option with an empty value
                                WMobilePack_Options::update_settings($file_type, '');

                                $arr_response['status'] = 1;
                            }
                        }
                    }
                }

                // echo json with response
                echo json_encode($arr_response);
            }

            exit();
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
                                $inactive_items = WMobilePack_Options::get_setting('inactive_categories');
                            else
                                $inactive_items = WMobilePack_Options::get_setting('inactive_pages');

                            // add or remove the item from the options array
                            if (in_array($item_id, $inactive_items) && $item_status == 'active')
                                $inactive_items = array_diff($inactive_items, array($item_id));

                            if (!in_array($item_id, $inactive_items) && $item_status == 'inactive')
                                $inactive_items[] = $item_id;

                            // save option
                            if ($_POST['type'] == 'category')
                                WMobilePack_Options::update_settings('inactive_categories', $inactive_items);
                            else
                                WMobilePack_Options::update_settings('inactive_pages', $inactive_items);
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
                                    WMobilePack_Options::update_settings('ordered_categories', $items_ids);
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
         * Method used to save the page details content in the database
         *
         */
        public function content_pagedetails()
        {

            if (current_user_can( 'manage_options' )){

                $status = 0;

                if (isset($_POST) && is_array($_POST) && !empty($_POST)){

                    if (isset($_POST['wmp_pageedit_id']) && isset($_POST['wmp_pageedit_content'])){

                        if (is_numeric($_POST['wmp_pageedit_id'])){

                            if (trim($_POST['wmp_pageedit_content']) != '') {

                                // load HTML purifier / formatter
                                if (!class_exists('WMobilePack_Formatter')) {
                                    require_once(WMP_PLUGIN_PATH . 'inc/class-wmp-formatter.php');
                                }

                                $purifier = WMobilePack_Formatter::init_purifier();

                                $page_id = intval($_POST['wmp_pageedit_id']);
                                $page_content = $purifier->purify(stripslashes($_POST['wmp_pageedit_content']));

                                // save option in the db
                                update_option(WMobilePack_Options::$prefix . 'page_' . $page_id, $page_content);

                                $status = 1;

                            } else {
                                $status = 2;
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
                                    WMobilePack_Options::update_settings('google_analytics_id', $_POST['wmp_editsettings_ganalyticsid']);
                                elseif ($_POST["wmp_editsettings_ganalyticsid"] == "")
                                    WMobilePack_Options::update_settings('google_analytics_id', "");
                            }

                            // save other options
                            WMobilePack_Options::update_settings('display_mode', $_POST['wmp_editsettings_displaymode']);
							WMobilePack_Options::update_settings('enable_tablets', intval($_POST['wmp_editsettings_enable_tablets']));
                            WMobilePack_Options::update_settings('display_website_link', intval($_POST['wmp_editsettings_displaywebsitelink']));
                            WMobilePack_Options::update_settings('posts_per_page', $_POST['wmp_editsettings_postsperpage']);
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
                                WMobilePack_Options::update_settings($option_name, $enabled_option);

                                if ($option_name == 'allow_tracking'){

                                    // update cron schedule
                                    WMobilePack::schedule_tracking($enabled_option);
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

                            $option_waitlists = WMobilePack_Options::get_setting('joined_waitlists');

                            if ($option_waitlists == null || !is_array($option_waitlists)) {
                                $option_waitlists = array();
                            }

                            if (!in_array($_POST['joined_waitlist'], $option_waitlists)) {

                                $status = 1;

                                $option_waitlists[] = $_POST['joined_waitlist'];

                                // save option
                                WMobilePack_Options::update_settings('joined_waitlists', $option_waitlists);
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
         * Method used to validate and save the api key in the options table.
         *
         */
        public function premium_save(){

            if (current_user_can( 'manage_options' )){

                $status = 0;

                if (isset($_POST) && is_array($_POST) && !empty($_POST)){

                    if (isset($_POST['api_key'])){

                        if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['api_key'])){

                            // save options
                            $status = 1;

                            WMobilePack_Options::update_settings('premium_api_key',$_POST['api_key']);
                        }
                    }
                }

                echo $status;
            }

            exit();
        }

        /**
         *
         * Method used to save the premium settings
         *
         */
        public function premium_connect(){

            if (current_user_can('manage_options')){

                $status = 0;

                if (isset($_POST) && is_array($_POST) && !empty($_POST)){

                    if (isset($_POST['api_key']) && isset($_POST['valid']) && isset($_POST['config_path'])){

                        if (
                            preg_match('/^[a-zA-Z0-9]+$/', $_POST['api_key']) &&
                            ($_POST['valid'] == '0' || $_POST['valid'] == '1') &&
                            $_POST['config_path'] != '' && filter_var($_POST['config_path'], FILTER_VALIDATE_URL)
                        ){

                            if ($_POST['api_key'] == WMobilePack_Options::get_setting('premium_api_key')) {

                                $arr_data = array(
                                    'premium_api_key' => $_POST['api_key'],
                                    'premium_active'  => $_POST['valid'],
                                    'premium_config_path' => $_POST['config_path']
                                );

                                if (WMobilePack_Options::update_settings($arr_data)) {

                                    // attempt to load the settings json
                                    $premium_manager = $this->get_premium_manager();
                                    $json_config_premium = $premium_manager->set_premium_config();

                                    if ($json_config_premium !== false){
                                        $status = 1;
                                    } else {
                                        WMobilePack_Options::update_settings('premium_active', 0);
                                    }
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
         * Method used to disconnect the dashboard from Appticles and revert to basic theme
         *
         */
        public function premium_disconnect(){

            if (current_user_can('manage_options')){

                $status = 0;

                if (isset($_POST) && is_array($_POST) && !empty($_POST)){

                    if (isset($_POST['api_key']) && isset($_POST['active'])){

                        if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['api_key']) && $_POST['active'] == 0){

                            $status = 1;

                            // delete transient with the json config
                            if (get_transient(WMobilePack_Options::$transient_prefix."premium_config_path") !== false)
                                delete_transient(WMobilePack_Options::$transient_prefix.'premium_config_path');

                            $arr_data = array(
                                'premium_api_key' => '',
                                'premium_active'  => 0,
                                'premium_config_path' => ''
                            );

                            // save options
                            WMobilePack_Options::update_settings($arr_data);
                        }
                    }
                }

                echo $status;
            }

            exit();
        }

        /**
         *
         * Method used to send a feedback e-mail from the admin
         *
         * Handle request, then display 1 for success and 0 for error.
         *
         */
        public function send_feedback()
        {

            if (current_user_can('manage_options')){

                $status = 0;

                if (isset($_POST) && is_array($_POST) && !empty($_POST)){

                    if (isset($_POST['wmp_feedback_page']) &&
                        isset($_POST['wmp_feedback_name']) &&
                        isset($_POST['wmp_feedback_email']) &&
                        isset($_POST['wmp_feedback_message'])){

                        if ($_POST['wmp_feedback_page'] != '' &&
                            $_POST['wmp_feedback_name'] != '' &&
                            $_POST['wmp_feedback_email'] != '' &&
                            $_POST['wmp_feedback_message'] != ''){

                            $admin_email = $_POST['wmp_feedback_email'];

                            // filter e-mail
                            if (filter_var($admin_email, FILTER_VALIDATE_EMAIL) !== false ){

                                // set e-mail variables
                                $message = "Name: ".strip_tags($_POST["wmp_feedback_name"])."\r\n \r\n";
                                $message .= "E-mail: ".$admin_email."\r\n \r\n";
                                $message .= "Message: ".strip_tags($_POST["wmp_feedback_message"])."\r\n \r\n";
                                $message .= "Page: ".stripslashes(strip_tags($_POST['wmp_feedback_page']))."\r\n \r\n";

                                if (isset($_SERVER['HTTP_HOST']))
                                    $message .= "Host: ".$_SERVER['HTTP_HOST']."\r\n \r\n";

                                $subject = WMP_PLUGIN_NAME.' Feedback';
                                $to = WMP_FEEDBACK_EMAIL;

                                // set headers
                                $headers = 'From:'.$admin_email."\r\nReply-To:".$admin_email;

                                // send e-mail
                                if (mail($to, $subject, $message, $headers))
                                    $status = 1;
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
