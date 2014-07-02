<?php
if ( ! class_exists( 'WMobilePackAdmin' ) ) {
    
	/**
	 * WMobilePackAdmin class for creating the admin area for the Wordpress Mobile Pack plugin
	 *
	 */
	class WMobilePackAdmin {

		
		/**
         * 
		 * Method used to render the main admin page
		 *
		 */
		public function wmp_options() {
			
			global $wmobile_pack;
			
            WMobilePack::wmp_update_settings('whats_new_updated', 0);
            
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-main.php');
		}
		
         
        /**
		 * Static method used to request the content for the What's New page.
		 * The method returns an array containing the latest content or an empty array by default.
		 *
		 */
		public static function wmp_whatsnew_updates() {
			
			$json_data = get_transient("wmp_whats_new_updates"); 
            
			// the transient is not set or expired
			if (!$json_data) {
			
    			// jSON URL which should be requested
    			$json_url = WMP_WHATSNEW_UPDATES;
    			
				// get response
				$json_response = self::wmp_read_data(WMP_WHATSNEW_UPDATES);
				
				if ($json_response !== false && $json_response != '') {
					
					// Store this data in a transient
					set_transient( 'wmp_whats_new_updates', $json_response, 3600*24*2 );
					
					// get response
					$response = json_decode($json_response, true);
			
					if (isset($response["content"]) && is_array($response["content"]) && !empty($response["content"])){
					   
                        if (isset($response['content']['last_updated']) && is_numeric($response['content']['last_updated'])){
                            
                            $last_updated = intval($response['content']['last_updated']);  
                            $option_last_updated = intval(WMobilePack::wmp_get_setting('whats_new_last_updated'));
                            
                            if ($last_updated > $option_last_updated){
                                
                                WMobilePack::wmp_update_settings('whats_new_last_updated', $last_updated);
                                WMobilePack::wmp_update_settings('whats_new_updated', 1);
                            }
                        }
                        
						// return response
						return $response["content"];
                    }
					
				} elseif($json_response == false) {
					
					// Store this data in a transient
					set_transient('wmp_whats_new_updates', 'warning', 3600*24*2 );
                    
					// return message
					return 'warning';	
				}
				
			} else {
					
                if ($json_data == 'warning')
                    return $json_data;
                    
				// get response
				$response = json_decode($json_data, true);
			
				if (isset($response["content"]) && is_array($response["content"]) && !empty($response["content"]))
					return $response["content"];
			}
            
			// by default return empty array
			return array();
		}
		

		/**
         * 
		 * Method used to render the themes selection page from the admin area
		 *
		 */
		public function wmp_theme_options() {
			
			global $wmobile_pack;
			
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-theme.php');
		}

		
		/**
         * 
		 * Method used to render the content selection page from the admin area
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
         */
        public function wmp_content_save() {
            
            if (current_user_can( 'manage_options' )){
                
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
            }
            
            exit();
        }
		
		/**
         * 
         * Method used to send a feedback e-mail from the admin 
         * 
         * Handle request then display 1 for success and 0 for error.
         * 
         */
        public function wmp_send_feedback() {
            
            if (current_user_can( 'manage_options' )){
                
                $status = 0;
               
                if (isset($_POST) && is_array($_POST) && !empty($_POST)){
                     
                    if (isset($_POST['wmp_feedback_page']) && isset($_POST['wmp_feedback_name']) && isset($_POST['wmp_feedback_email']) && isset($_POST['wmp_feedback_message'])){
                        
                        if (is_string($_POST['wmp_feedback_page']) && $_POST['wmp_feedback_page'] != '' && $_POST['wmp_feedback_name'] != "" && $_POST['wmp_feedback_email'] && $_POST['wmp_feedback_message'] != '' ){
                          
    					  	// get admin e-mail and name
    					  	if (is_admin()) {
    							
    							$admin_email = $_POST['wmp_feedback_email'];
                                
    							// filter e-mail														
    							if (filter_var($admin_email, FILTER_VALIDATE_EMAIL) !== false ){
    								 
    								// set e-mail variables
                                    $message = "Name: ".strip_tags($_POST["wmp_feedback_name"])."\r\n \r\n";
                                    $message .= "E-mail: ".$_POST["wmp_feedback_email"]."\r\n \r\n";
    								$message .= "Message: ".strip_tags($_POST["wmp_feedback_message"])."\r\n \r\n";
                                    $message .= "Page: ".stripslashes(strip_tags($_POST['wmp_feedback_page']));
                                    
    								$subject = 'WP Mobile Pack Feeback';
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
                }
                
                echo $status;
            }
            
            exit();
        }
		
		
		/**
		 * Static method used to request the news and updates from an endpoint on a different domain.
         * 
		 * The method returns an array containing the latest news and updates or an empty array by default.
		 *
		 */ 
		public static function wmp_news_updates() {
			
			$json_data =  get_transient("wmp_newsupdates");
            
            if (!$json_data) {
			
				// jSON URL which should be requested
				$json_url = WMP_NEWS_UPDATES;
				
				// get response
				$json_response = self::wmp_read_data(WMP_NEWS_UPDATES);
				
				if($json_response !== false && $json_response != '') {
					
					// Store this data in a transient
					set_transient('wmp_newsupdates', $json_response, 3600*24*2);
					
					// get response
					$response = json_decode($json_response, true);
					
					if ( (isset($response["news"]) && is_array($response["news"]) && !empty($response["news"])) || 
						(isset($response["whitepaper"]) && is_array($response["whitepaper"]) && !empty($response["whitepaper"])) ) {
							
						return $response;    
					}
				} 
			
			} else {
					
				// get response
				$response = json_decode($json_data, true);
				
                if ( (isset($response["news"]) && is_array($response["news"]) && !empty($response["news"])) || 
                    (isset($response["whitepaper"]) && is_array($response["whitepaper"]) && !empty($response["whitepaper"])) ) {
                    
                    return $response;
                }
			}
			
			// by default return empty array
			return array();
		}
		
		
		/**
         * 
		 * Method used to render the settings selection page from the admin area
		 *
		 */
		public function wmp_settings_options() {
			
			global $wmobile_pack;
			
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-settings.php');
		}
        
  
        /**
         * 
         * Method used to save the settings display mode, color schemes and fonts or joined waitlists.
         * 
         */
        public function wmp_settings_save() {
            
            if (current_user_can( 'manage_options' )) {
                
                global $wmobile_pack;
            	
                $status = 0;
                
                if (isset($_POST) && is_array($_POST) && !empty($_POST)){
                    
                    // handle display mode (settings page)
                    if (isset($_POST['wmp_editsettings_displaymode']) && $_POST['wmp_editsettings_displaymode'] != ''){
                        if (in_array($_POST['wmp_editsettings_displaymode'], array('normal', 'preview', 'disabled'))){
                            
                            $status = 1;
                            // save google analytics id
    						if (isset($_POST["wmp_editsettings_ganalyticsid"])) {
    							
    							// validate google analytics id
    							if (preg_match('/^ua-\d{4,9}-\d{1,4}$/i', strval($_POST["wmp_editsettings_ganalyticsid"])))
    								WMobilePack::wmp_update_settings('google_analytics_id', $_POST['wmp_editsettings_ganalyticsid']);
                                elseif ($_POST["wmp_editsettings_ganalyticsid"] == "")
                                    WMobilePack::wmp_update_settings('google_analytics_id', "");
    							
    						}
                            // save option
                            WMobilePack::wmp_update_settings('display_mode', $_POST['wmp_editsettings_displaymode']);
                        }
                    }
                    
                    // handle color schemes and fonts (look & feel page)
                    if (isset($_POST['wmp_edittheme_colorscheme']) && $_POST['wmp_edittheme_colorscheme'] != '' &&
                        isset($_POST['wmp_edittheme_fontheadlines']) && $_POST['wmp_edittheme_fontheadlines'] != '' &&
                        isset($_POST['wmp_edittheme_fontsubtitles']) && $_POST['wmp_edittheme_fontsubtitles'] != '' &&
                        isset($_POST['wmp_edittheme_fontparagraphs']) && $_POST['wmp_edittheme_fontparagraphs'] != ''){
                        
                        if (in_array($_POST['wmp_edittheme_colorscheme'], array(1,2,3)) && 
                            in_array($_POST['wmp_edittheme_fontheadlines'], WMobilePack::$wmp_allowed_fonts) && 
                            in_array($_POST['wmp_edittheme_fontsubtitles'], WMobilePack::$wmp_allowed_fonts) &&
                            in_array($_POST['wmp_edittheme_fontparagraphs'], WMobilePack::$wmp_allowed_fonts)){
                            
                            $status = 1;
                            
                            // save options
                            WMobilePack::wmp_update_settings('color_scheme', $_POST['wmp_edittheme_colorscheme']);
                            WMobilePack::wmp_update_settings('font_headlines', $_POST['wmp_edittheme_fontheadlines']);
                            WMobilePack::wmp_update_settings('font_subtitles', $_POST['wmp_edittheme_fontsubtitles']);
                            WMobilePack::wmp_update_settings('font_paragraphs', $_POST['wmp_edittheme_fontparagraphs']);
                        }
                    }
                    
                    // handle joined waitlists
                    if (isset($_POST['joined_waitlist']) && $_POST['joined_waitlist'] != ''){
                        
                        if (in_array($_POST['joined_waitlist'], array('content', 'settings', 'lifestyletheme',  'businesstheme'))){
                            
                            $option_waitlists = WMobilePack::wmp_get_setting('joined_waitlists');
                            
                            if ($option_waitlists != '')
                                $joined_waitlists = unserialize(WMobilePack::wmp_get_setting('joined_waitlists'));
                            
                            if ($joined_waitlists == null || !is_array($joined_waitlists))
                                $joined_waitlists = array();
                                
                            if (!in_array($_POST['joined_waitlist'], $joined_waitlists)) {
                                
                                $status = 1;
                                
                                $joined_waitlists[] = $_POST['joined_waitlist'];
                                
                                // save option
                                WMobilePack::wmp_update_settings('joined_waitlists', serialize($joined_waitlists));
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
         * Method used to save the icon and logo
         * 
         */
         public function wmp_settings_editimages() {
		
            if (current_user_can( 'manage_options' )){
                
                $action = null;
                
                if (!empty($_GET) && isset($_GET['type']))
                    if ($_GET['type'] == 'upload' || $_GET['type'] == 'delete')
                        $action = $_GET['type'];
                        
                $arrResponse = array(
                    'status' => 0,
                    'messages' => array()
                );
                
                if ($action == 'upload'){
              
                    if (!empty($_FILES) && sizeof($_FILES) > 0){
                           
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        
                        if (!function_exists( 'wp_handle_upload' ) ) 
                            require_once( ABSPATH . 'wp-admin/includes/file.php' );
                        
                        // check if the upload folder is writable
            			if (!is_writable(WMP_FILES_UPLOADS_DIR)){
                            
                            $arrResponse['messages'][] = "Error uploading images, the upload folder is not writable.";
                        
                        } else {
                            
                            $has_uploaded_files = false;
                            
                            foreach ($_FILES as $file => $info) {
                                
                                if (!empty($info['name'])){
            
                                    $has_uploaded_files = true;
                                    
                                    if ($info['error'] >= 1 || $info['size'] <= 0) {
            
                                    	$arrResponse['status'] = 0;
                                    	$arrResponse["messages"][] = "We encountered a problem processing your ".($file == "wmp_editimages_icon" ? "icon" : "logo").". Please choose another image!";
            
                                    } elseif ( $info['size'] > 1048576 ){
            
                                    	$arrResponse['status'] = 0;
                                    	$arrResponse["messages"][] = "Your ".($file == "wmp_editimages_icon" ? "icon" : "logo")." size is greater than 1Mb!";
            
                                    } else {
                                        
                                        /****************************************/
                        				/*										*/
                        				/* SET FILENAME, ALLOWED FORMATS AND SIZE */
                        				/*										*/
                        				/****************************************/
                        
                                        // make unique file name for the image
                                        $arrFilename = explode(".", $info['name']);
                                        $fileExtension = end($arrFilename);
                                        
                                        if ($file == "wmp_editimages_icon") {
                                            
                                            $arrAllowedExtensions = array('jpg', 'jpeg', 'png');
                                            $arrMaximumSize = array('width' => 256, 'height' => 256);
                                             
                                        } else {
                                            
                                            $arrAllowedExtensions = array('png');
                                            $arrMaximumSize = array('width' => 120, 'height' => 120);
                                        }
                                        
                                        // check file extension
                                        if (!in_array(strtolower($fileExtension), $arrAllowedExtensions)) {
                                            
                                            $arrResponse['messages'][] = "Error saving image, please add a ".implode(' or ',$arrAllowedExtensions)." image for your ".($file == "wmp_editimages_icon" ? "icon" : "logo")."!";
                                            
                                        } else {
                                            
                                            /****************************************/
                            				/*										*/
                            				/* UPLOAD IMAGE                         */
                            				/*										*/
                            				/****************************************/
                                        
                                            $uniqueFilename = ($file == "wmp_editimages_icon" ? "icon" : "logo").'_'.time().'.'.$fileExtension;
                                            
                                            // upload to the default uploads folder
                                            $upload_overrides = array( 'test_form' => false );
                                            $movefile = wp_handle_upload( $info, $upload_overrides );
                                            
                                            if ($movefile) {
                                                
                                                /****************************************/
                                				/*										*/
                                				/* RESIZE AND COPY IMAGE                */
                                				/*										*/
                                				/****************************************/
                                            
                                                $copied_and_resized = false;
                                                
                                                $image = wp_get_image_editor( $movefile['file'] );
                                                
                                                if (!is_wp_error( $image ) ) {
                                                    
                                                    $image_size = $image->get_size();
                                                    
                                                    // if the image exceeds the size limits
                                                    if ($image_size['width'] > $arrMaximumSize['width'] || $image_size['height'] > $arrMaximumSize['height']) {
                                                        
                                                        // resize and copy to the wmp uploads folder
                                                        $image->resize( $arrMaximumSize['width'], $image_size['height'] );
                                                        $image->save( WMP_FILES_UPLOADS_DIR.$uniqueFilename );
                                                        
                                                        $copied_and_resized = true;
                                                        
                                                    } else {
                                                    
                                                        // copy file without resizing to the wmp uploads folder
                                                        $copied_and_resized = copy($movefile['file'], WMP_FILES_UPLOADS_DIR.$uniqueFilename);
                                                    }
                                                    
                                                } else {
                                                    
                                                    $arrResponse["messages"][] = "We encountered a problem resizing your ".($file == "wmp_editimages_icon" ? "icon" : "logo").". Please choose another image!";
                                                }
                                                
                                                /****************************************/
                                				/*										*/
                                				/* DELETE PREVIOUS IMAGE AND SET OPTION */
                                				/*										*/
                                				/****************************************/
                                                
                                                if ($copied_and_resized) {
                                                        
                                                    // delete previous icon / logo
                                                    $previous_file_path = WMobilePack::wmp_get_setting($file == "wmp_editimages_icon" ? "icon" : "logo");
                                                    
                                                    if ($previous_file_path != ''){
                                                        unlink(WMP_FILES_UPLOADS_DIR.$previous_file_path);
                                                    }
                                                    
                                                    // save option
                                                    WMobilePack::wmp_update_settings($file == "wmp_editimages_icon" ? "icon" : "logo", $uniqueFilename);
                                                    
                                                    // add path in the response
                                                    $arrResponse['status'] = 1;
                                                    $arrResponse['uploaded_'.($file == "wmp_editimages_icon" ? "icon" : "logo")] = WMP_FILES_UPLOADS_URL.$uniqueFilename;
                                                }
                                                
                                                // remove file from the default uploads folder
                                                unlink($movefile['file']);
                                            }   
                                        }
                                    }
                                }                      
                            }
                            
                            if ($has_uploaded_files == false){
                                $arrResponse['messages'][] = "Please add at least one image!";
                            }
                        }
                    } 
                        
                } elseif ($action == 'delete'){
                    
                    /****************************************/
    				/*										*/
    				/* DELETE ICON / LOGO        			*/
    				/*										*/
    				/****************************************/
                            
                    // delete icon or logo, depending on the 'source' param
                    if (isset($_GET['source'])) {
                        if ($_GET['source'] == 'icon' || $_GET['source'] == 'logo'){
                            
                            $file = $_GET['source'];
                            
                            // get the previous file name from the options table
                            $previous_file_path = WMobilePack::wmp_get_setting($file);
                                            
                            // check if we have to delete the file and remove it
                            if ($previous_file_path != ''){
                                if (file_exists(WMP_FILES_UPLOADS_DIR.$previous_file_path))
                                    unlink(WMP_FILES_UPLOADS_DIR.$previous_file_path);
                            }
                            
                            // save option with an empty value
                            WMobilePack::wmp_update_settings($file, '');
                            
                            $arrResponse['status'] = 1;
                        }
                    }
                }
                
                echo json_encode($arrResponse);
            }
            
            exit();
        }
        
		/**
		 * Method used to render the upgrade page from the admin area
		 */
		public function wmp_upgrade_options() {
			
			global $wmobile_pack;
			
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-upgrade.php'); 
		}
        
        /**
		 * Static method used to request the content for the More page.
		 * The method returns an array containing the latest content or an empty array by default.
		 *
		 */
		public static function wmp_more_updates() {
			
			$json_data = get_transient("wmp_more_updates");
            
			// the transient is not set or expired
			if (!$json_data) {
			
    			// jSON URL which should be requested
    			$json_url = WMP_MORE_UPDATES;
    		
				// get response
				$json_response = self::wmp_read_data(WMP_MORE_UPDATES);
				
				if($json_response !== false && $json_response != '') {
					
					// Store this data in a transient
					set_transient( 'wmp_more_updates', $json_response, 3600*24*2 );
					
					// get response
					$response = json_decode($json_response, true);
			
					if (isset($response["content"]) && is_array($response["content"]) && !empty($response["content"])){
					   
						// return response
						return $response["content"];
                    }
					
				} elseif($json_response == false) {
					
					// Store this data in a transient
					set_transient('wmp_more_updates', 'warning', 3600*24*2 );
                    
					// return message
					return 'warning';	
				}
				
			} else {
			     
                if ($json_data == 'warning')
                    return $json_data;
                    
				// get response
				$response = json_decode($json_data, true);
			
				if (isset($response["content"]) && is_array($response["content"]) && !empty($response["content"]))
					return $response["content"];
			}
            
			// by default return empty array
			return array();
		}
	
	
	
		/**
		 * Static method used to request the content of different pages using curl or fopen
		 * This method returns false if both curl and fopen are dissabled and an empty string ig the json could not be read
		 *
		 */
		public static function wmp_read_data($json_url) {

			// check if curl is enabled
			if (extension_loaded('curl')) {
				
				$send_curl = curl_init($json_url);
			
				// set curl options
				curl_setopt($send_curl, CURLOPT_URL, $json_url);
				curl_setopt($send_curl, CURLOPT_HEADER, false);
				curl_setopt($send_curl, CURLOPT_CONNECTTIMEOUT, 2);
				curl_setopt($send_curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($send_curl, CURLOPT_HTTPHEADER,array('Accept: application/json', "Content-type: application/json"));
				curl_setopt($send_curl, CURLOPT_FAILONERROR, FALSE);
				curl_setopt($send_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($send_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
				$json_response = curl_exec($send_curl);
				
				// get request status
				$status = curl_getinfo($send_curl, CURLINFO_HTTP_CODE);
				curl_close($send_curl);
				
				// return json if success
				if ($status == 200)
					return $json_response;
				
			} elseif (ini_get( 'allow_url_fopen' )) { // check if allow_url_fopen is enabled
				
				// open file
				$json_file = fopen( $json_url, 'rb' );
				
				if($json_file) {
					
					$json_response = '';
					// read conetnts of file
					while (!feof($json_file)) {
						
						$json_response .= fgets($json_file);
					}
				}
				
				/// return json response
				if($json_response)
					return $json_response;
					
			} else 
				// both curl and fopen are disabled
				return false;
			
			// by default return an empty string
    		return '';	
    		
		}
	}
	

}