<?php
if ( ! class_exists( 'WMobilePackAdmin' ) ) {
	/**
	 * WMobilePackAdmin class for creating the admin area
	 *
	 * @package WMobilePackAdmin
	 * @since 2.0
	 */
	class WMobilePackAdmin {

		
		/**
		 * Method used to render the main admin page
		 *
		 *
		 */
		public function wmp_options() {
			
			global $wmobile_pack;
			
			
			// set settings
			
			
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-main.php');

			
		}
		
		/**
		 * Method used to render the themes selection page from the admin area
		 *
		 *
		 */
		public function wmp_theme_options() {
			
			global $wmobile_pack;
			
			
			// set settings
			
			
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-theme.php');

			
		}

		
		/**
		 * Method used to render the content selection page from the admin area
		 *
		 *
		 */
		public function wmp_content_options() {
			
			global $wmobile_pack;
			
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-content.php');

		}
		
        /**
         * 
         * Method used to save the categories settings in the database
         * 
         * Handle request then generate response using WP_Ajax_Response
         * 
         */
        public function wmp_content_save() {
            
            global $wmobile_pack;
        	
            $status = 0;
            
            if (isset($_POST) && is_array($_POST) && !empty($_POST)){
                
                if (isset($_POST['id']) && isset($_POST['status'])){
                    
                    if (is_numeric($_POST['id']) && ($_POST['status'] == 'active' || $_POST['status'] == 'inactive')){
                        
                        $status = 1;
                         
                        $category_id = intval($_POST['id']);
                        $category_status = strval($_POST['status']);
                        
                        // get inactive categories option
                        $inactive_categories = unserialize(WMobilePack::wmp_get_setting('inactive_categories'));
                        
                        // add or remove the category from the option
                        if (in_array($category_id, $inactive_categories) && $category_status == 'active')
                            $inactive_categories = array_diff($inactive_categories, array($category_id));
                        
                        if (!in_array($category_id, $inactive_categories) && $category_status == 'inactive')
                            $inactive_categories[] = $category_id;
                            
                        // save option
                        WMobilePack::wmp_update_settings('inactive_categories', serialize($inactive_categories));
                    }
                }    
            }
            
            echo $status;
            exit();
        	// die($status);
        }
		
		/**
		 * Method used to render the settings selection page from the admin area
		 *
		 *
		 */
		public function wmp_settings_options() {
			
			global $wmobile_pack;
			
			
			// set settings
			
			
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-settings.php');

			
		}
		
		
		/**
		 * Method used to render the upgrade page from the admin area
		 *
		 *
		 */
		public function wmp_upgrade_options() {
			
			global $wmobile_pack;
			
			
			// set settings
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-upgrade.php'); 

			
		}
		
		
		
		/**
		 * Method set the upload folder of the site logo
		 * This method returns true if the folder is writable and false if it is not
		 *
		 */
		public function wmp_upload_path() {
			
			//directory to import to	
			$uploadDir = 'wp-content/uploads/wp-mobile-pack/';
			
			//if the directory doesn't exist, create it	
			if(!file_exists(ABSPATH.$uploadDir)) {
				mkdir(ABSPATH.$uploadDir,0777);
			}
			
			return is_writable(ABSPATH.$uploadDir);
			
			
			
		}
		
		
		/**
		 * Method used to upload the logo on the set folder
		 * This method returns true if the file was saved and false otherwise
		 *
		 */
		public function wmp_save_image() {
			
			//
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			
			//directory to import to	
			$uploadDir = ABSPATH.'wp-content/uploads/wp-mobile-pack/';
			
			
			$file = $_FILES['site_logo'];
			
			
			// set blog version
			$blog_version = get_bloginfo('version');
			
			// to be finished
			
			// resize image
			if($blog_version < 3.5) {
				image_resize($file['tmp_name'], 512, 512, false, '', '', 100);
			} 
			
			add_filter('upload_dir', uploadDir);
			$uploaded_file = wp_handle_upload( $file, array('test_form' => false), '' );
			remove_filter('upload_dir', 'wps_upload_dir');
			
			// return uploaded file
			if($blog_version > 3.5) {
				
				$this->wmp_resize_image();
			
			}
		}
		
		public function wmp_resize_image($image_path){
			
		
			/*$image = wp_get_image_editor( i$mage_path); // Return an implementation that extends <tt>WP_Image_Editor</tt>

				if ( ! is_wp_error( $image ) ) {
					
					$image->resize( 512, 512, false );
					$image->save();
				}
		*/
			
		}
		
		
		
	}

}