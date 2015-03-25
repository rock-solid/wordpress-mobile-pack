<?php

if ( ! class_exists( 'WMobilePackAdmin' ) ) {
     
	/**
	 * WMobilePackAdmin class for creating the admin area for the Wordpress Mobile Pack plugin
	 *
	 */
	class WMobilePackAdmin {

		
		/**
         * 
		 * Method used to render the main admin page (free version)
		 *
		 */
		public function wmp_options() {
			
			global $wmobile_pack;
			
            WMobilePack::wmp_update_settings('whats_new_updated', 0);
            
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-main.php');        
		}
		
		
		/**
         * 
		 * Method used to render the main admin page for the premium dashboard
		 *
		 */
		public function wmp_premium_options() {
			
			global $wmobile_pack;
			 
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-premium.php'); 
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
			
                // check if we have a https connection
                $is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
                
    			// jSON URL which should be requested
    			$json_url = ($is_secure ? WMP_WHATSNEW_UPDATES_HTTPS : WMP_WHATSNEW_UPDATES);
    			
				// get response
				$json_response = self::wmp_read_data($json_url);
				
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
					
				} elseif ($json_response == false) {
					
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
		 * Method used to render the themes selection page from the admin area (free version)
		 *
		 */
		public function wmp_theme_options() {
			
			global $wmobile_pack;
			
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-theme.php');
		}

		
		/**
         * 
		 * Method used to render the content selection page from the admin area (free version)
		 *
		 */
		public function wmp_content_options() {
			
			global $wmobile_pack;
			
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-content.php');
		}
		
		
		/**
         * 
		 * Method used to render a form with a page's details (free version)
		 *
		 */
		public function wmp_page_content() {
			
			global $wmobile_pack;
			
			include(WMP_PLUGIN_PATH.'libs/htmlpurifier-4.6.0/library/HTMLPurifier.safe-includes.php');
			include(WMP_PLUGIN_PATH.'libs/htmlpurifier-html5/htmlpurifier_html5.php');
            
            if (isset($_GET) && is_array($_GET) && !empty($_GET)){
				 
				 if (isset($_GET['id'])) { 
				 
				 	if (is_numeric($_GET['id'])) {
							
						// get page
						$page = get_page($_GET['id']); 
										  
						if($page != null) {
							
							$config = HTMLPurifier_Config::createDefault();
							$config->set('Core.Encoding', 'UTF-8'); 									
							
                            $config->set('HTML.AllowedElements','div,a,p,ol,li,ul,img,blockquote,em,span,h1,h2,h3,h4,h5,h6,i,u,strong,b,sup,br,cite,iframe,small,video,audio,source');
						  	$config->set('HTML.AllowedAttributes', 'class,src, width, height, target, href, name,frameborder,marginheight,marginwidth,scrolling,poster,preload,controls,type');
						    
							$config->set('URI.AllowedSchemes', array ('http' => true, 'https' => true, 'mailto' => true, 'news' => true, 'tel' => true, 'callto' => true));
							
                            $config->set('Attr.AllowedFrameTargets', '_blank, _parent, _self, _top');
							
							$config->set('HTML.SafeIframe',1);
							$config->set('Filter.Custom', array( new HTMLPurifier_Filter_Iframe()));
							
							// disable cache
							$config->set('Cache.DefinitionImpl',null);
							
							$Html5Purifier = new WMPHtmlPurifier();
                            $purifier = $Html5Purifier->wmp_extended_purifier($config);
							
							// first check if the admin edited the content for this page
							if(get_option( 'wmpack_page_' .$page->ID  ) === false)
								$content = apply_filters("the_content",$page->post_content);
							else
								$content = apply_filters("the_content",get_option( 'wmpack_page_' .$page->ID  ));
								
							$content = $purifier->purify(stripslashes($content));
							
							// load view
							include(WMP_PLUGIN_PATH.'admin/wmp-admin-page-details.php');	
						}
					}
				}
			}
		}
		
		
        /**
         * 
         * Method used to save the categories settings in the database (free version)
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
         * Method used to save the pages settings in the database (free version)
         * 
         */
        public function wmp_content_pagestatus() {
            
            if (current_user_can( 'manage_options' )){
                
                global $wmobile_pack;
            	
                $status = 0;
                
                if (isset($_POST) && is_array($_POST) && !empty($_POST)) {
                    
                    if (isset($_POST['id']) && isset($_POST['status'])){
                    
                        if (is_numeric($_POST['id']) && ($_POST['status'] == 'active' || $_POST['status'] == 'inactive')){
                            
                            $status = 1;
                             
                            $page_id = intval($_POST['id']);
                            $page_status = strval($_POST['status']);
                            
                            // get inactive pages option
                            $inactive_pages = unserialize(WMobilePack::wmp_get_setting('inactive_pages'));
                            
                            // add or remove the page from the option
                            if (in_array($page_id, $inactive_pages) && $page_status == 'active')
                                $inactive_pages = array_diff($inactive_pages, array($page_id));
                            
                            if (!in_array($page_id, $inactive_pages) && $page_status == 'inactive')
                                $inactive_pages[] = $page_id;
                                
                            // save option
                            WMobilePack::wmp_update_settings('inactive_pages', serialize($inactive_pages));
                        
                        }      
                    } 
                } 
                
                echo $status;
            }
            
            exit();
        }
		
		
		
		/**
        * 
        * Method used to save the order of pages and categories in the database (free version)
        * 
        */
        public function wmp_content_order() {
            
            if (current_user_can( 'manage_options' )){
                
                global $wmobile_pack;
            	
                $status = 0;
                
                if (isset($_POST) && is_array($_POST) && !empty($_POST)){
                    
                    if (isset($_POST['ids']) && isset($_POST['type'])){
                      
                        if ($_POST['ids'] != '' && ($_POST['type'] == 'pages' || $_POST['type'] == 'categories')){
                             
							// check ids
							$arrPagesIds = array_filter(explode(",", $_POST['ids']));
							
							if (count($arrPagesIds) > 0) {
								
								$valid_ids = true;
							
								foreach ($arrPagesIds as $page_id) {
									
									if (!is_numeric($page_id)) // 4page_is is not numeric
										$valid_ids = false;
								}
		        	
								if ($valid_ids) {
									
									 $status = 1;
									
									// save option
                           			if ($_POST['type'] == 'pages')
										WMobilePack::wmp_update_settings('ordered_pages', serialize($arrPagesIds));
									elseif ($_POST['type'] == 'categories')
										WMobilePack::wmp_update_settings('ordered_categories', serialize($arrPagesIds));
								
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
         * Method used to save the page details content in the database (free version)
         * 
         */
        public function wmp_content_pagedetails() {
            
            if (current_user_can( 'manage_options' )){
                
                global $wmobile_pack;
            	
                $status = 0;
               
                if (isset($_POST) && is_array($_POST) && !empty($_POST)){
                    
                    if (isset($_POST['wmp_pageedit_id']) && isset($_POST['wmp_pageedit_content'])){
                        
                        if (is_numeric($_POST['wmp_pageedit_id']) && trim($_POST['wmp_pageedit_content']) != ''){
                            
							// set HTML Purifier
							include(WMP_PLUGIN_PATH.'libs/htmlpurifier-4.6.0/library/HTMLPurifier.safe-includes.php');
							include(WMP_PLUGIN_PATH.'libs/htmlpurifier-html5/htmlpurifier_html5.php');
                            
                            $config = HTMLPurifier_Config::createDefault();
							$config->set('Core.Encoding', 'UTF-8'); 									
							
                            $config->set('HTML.AllowedElements','div,a,p,ol,li,ul,img,blockquote,em,span,h1,h2,h3,h4,h5,h6,i,u,strong,b,sup,br,cite,iframe,small,video,audio,source');
						  	$config->set('HTML.AllowedAttributes', 'class, src, width, height, target, href, name,frameborder,marginheight,marginwidth,scrolling,poster,preload,controls,type');
						    
							$config->set('URI.AllowedSchemes', array ('http' => true, 'https' => true, 'mailto' => true, 'news' => true, 'tel' => true, 'callto' => true));
							
							$config->set('Attr.AllowedFrameTargets', '_blank, _parent, _self, _top');
							
							$config->set('HTML.SafeIframe',1);
							$config->set('Filter.Custom', array( new HTMLPurifier_Filter_Iframe()));
							
							// disable cache
							$config->set('Cache.DefinitionImpl',null);
							
							$Html5Purifier = new WMPHtmlPurifier();
                            $purifier = $Html5Purifier->wmp_extended_purifier($config);
							
                            $status = 1;
                            
                            $page_id = intval($_POST['wmp_pageedit_id']);
                            $page_content = $purifier->purify(stripslashes($_POST['wmp_pageedit_content']));
                            
                            // save option in the db
							update_option( 'wmpack_page_' . $page_id, $page_content );
                            
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
                                    $message .= "E-mail: ".$admin_email."\r\n \r\n";
    								$message .= "Message: ".strip_tags($_POST["wmp_feedback_message"])."\r\n \r\n";
                                    $message .= "Page: ".stripslashes(strip_tags($_POST['wmp_feedback_page']))."\r\n \r\n";
									
									if (isset($_SERVER['HTTP_HOST']))
										$message .= "Host: ".$_SERVER['HTTP_HOST'];
                                    
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
			
                // check if we have a https connection
                $is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
                
    			// JSON URL that should be requested
    			$json_url = ($is_secure ? WMP_NEWS_UPDATES_HTTPS : WMP_NEWS_UPDATES);
				
				// get response
				$json_response = self::wmp_read_data($json_url);
				
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
		 * Method used to render the settings selection page from the admin area (free version)
		 *
		 */
		public function wmp_settings_options() {
			
			global $wmobile_pack;
			
			// load view
			include(WMP_PLUGIN_PATH.'admin/wmp-admin-settings.php');
		}
        
  
        /**
         * 
         * Method used to save the settings display mode, color schemes and fonts or joined waitlists. (free version)
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
                        
                        if (in_array($_POST['joined_waitlist'], array('content', 'settings', 'lifestyletheme',  'businesstheme','themes_features'))){
                            
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
                    
                    // handle allow tracking (settings page)
                    if (isset($_POST['wmp_allowtracking_box']) && $_POST['wmp_allowtracking_box'] != '' && is_numeric($_POST['wmp_allowtracking_box'])){
                        
                        $allowTracking = intval($_POST['wmp_allowtracking_box']);
                        
                        if ($allowTracking == 0 || $allowTracking == 1){
                            
                            $status = 1;
                            
                            // save option
                            WMobilePack::wmp_update_settings('allow_tracking', $allowTracking);
                            
                            // update cron schedule
                            WMobilePack::wmp_schedule_tracking($allowTracking);
                        }
                    }
                }
                
                echo $status;
            }
            
            exit(); 
        }
		
		/**
         * 
         * Method used to save the api key (connect to premium)
         * 
         */
        public function wmp_premium_save() {
            
            if (current_user_can( 'manage_options' )){
                
                global $wmobile_pack;
            	
                $status = 0;
                
                if (isset($_POST) && is_array($_POST) && !empty($_POST)){
                    
                    if (isset($_POST['api_key'])){
                        
                        if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['api_key']) ){
                        
                            // save options
                            if (WMobilePack::wmp_update_settings('premium_api_key',$_POST['api_key']))
								$status = 1;
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
        public function wmp_premium_connect() {

            if (current_user_can('manage_options')){
                
                global $wmobile_pack;
            	
                $status = 0;
                
                if (isset($_POST) && is_array($_POST) && !empty($_POST)){
                    
                    if (isset($_POST['api_key']) && isset($_POST['valid']) && isset($_POST['config_path'])){
                        
                        if (
								preg_match('/^[a-zA-Z0-9]+$/', $_POST['api_key']) && 
								($_POST['valid'] == '0' || $_POST['valid'] == '1') && 
								$_POST['config_path'] != '' && filter_var($_POST['config_path'], FILTER_VALIDATE_URL)
							){
                            
                            if ($_POST['api_key'] == WMobilePack::wmp_get_setting('premium_api_key')) {
						 
                                $arrData = array(
                                    'premium_api_key' => $_POST['api_key'],
                                    'premium_active'  => $_POST['valid'],
                                    'premium_config_path' => $_POST['config_path']
                                );
                                    
                                if (WMobilePack::wmp_update_settings($arrData)) {
                                    
                                    // attempt to load the settings json
                                    $json_config_premium = WMobilePack::wmp_set_premium_config();
                                    
                                    if ($json_config_premium !== false){
                                        $status = 1;
                                    } else {       
                                        WMobilePack::wmp_update_settings('premium_active', 0);
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
         * Method used to disconnect the dashboard from Appticles and rever to basic theme
         * 
         */
        public function wmp_premium_disconnect() {
            
            if (current_user_can( 'manage_options' )){
                
                global $wmobile_pack;
            	
                $status = 0;
                
                if (isset($_POST) && is_array($_POST) && !empty($_POST)){
                                        
                    if (isset($_POST['api_key']) && isset($_POST['active'])){
                        
                        if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['api_key']) && $_POST['active'] == 0){
                                
							$arrData = array(
								'premium_api_key' => '',
								'premium_active'  => 0,
								'premium_config_path' => ''
							);	
								
							// delete transient with the json config
							if (get_transient("wmp_premium_config_path") !== false)
								delete_transient('wmp_premium_config_path');
							
                            // save options
							if (WMobilePack::wmp_update_settings($arrData))
								$status = 1;
							
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
                            
                            $arrResponse['messages'][] = "Error uploading images, the upload folder ".WMP_FILES_UPLOADS_DIR." is not writable.";
                        
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
                                            
                                            $arrAllowedExtensions = array('jpg', 'jpeg', 'png','gif');
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
                                $arrResponse['messages'][] = "Please upload at least one image!";
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
                
                // echo json with response
                echo json_encode($arrResponse);
            }
            
            exit();
        }
		
		
		/**
         * 
         * Method used to save the cover
         * 
         */
         public function wmp_settings_editcover() {

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
                            
                            $arrResponse['messages'][] = "Error uploading images, the upload folder ".WMP_FILES_UPLOADS_DIR." is not writable.";
                        
                        } else {
                            
                            $has_uploaded_files = false;
                            
                            foreach ($_FILES as $file => $info) {
                                
                                if (!empty($info['name'])){
            
                                    $has_uploaded_files = true;
                                    
                                    if ($info['error'] >= 1 || $info['size'] <= 0) {
            
                                    	$arrResponse['status'] = 0;
                                    	$arrResponse["messages"][] = "We encountered a problem processing your cover. Please choose another image!";
            
                                    } elseif ( $info['size'] > 1048576 ){
            
                                    	$arrResponse['status'] = 0;
                                    	$arrResponse["messages"][] = "Your cover size is greater than 1Mb!";
            
                                    } else {
                                        
                                        /****************************************/
                        				/*										*/
                        				/* SET FILENAME, ALLOWED FORMATS AND SIZE */
                        				/*										*/
                        				/****************************************/
                        
                                        // make unique file name for the image
                                        $arrFilename = explode(".", $info['name']);
                                        $fileExtension = end($arrFilename);
                                        
                                        if ($file == "wmp_editcover_cover") {
                                            
                                            $arrAllowedExtensions = array('jpg', 'jpeg', 'png','gif');
                                            $arrMaximumSize = array('width' => 1000, 'height' => 1000);
                                             
                                        } 
                                           
                                        
                                        // check file extension
                                        if (!in_array(strtolower($fileExtension), $arrAllowedExtensions)) {
                                            
                                            $arrResponse['messages'][] = "Error saving image, please add a ".implode(' or ',$arrAllowedExtensions)." image for your cover!";
                                            
                                        } else {
                                            
                                            /****************************************/
                            				/*										*/
                            				/* UPLOAD IMAGE                         */
                            				/*										*/
                            				/****************************************/
                                        
                                            $uniqueFilename = 'cover_'.time().'.'.$fileExtension;
                                            
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
                                                    
                                                    $arrResponse["messages"][] = "We encountered a problem resizing your cover. Please choose another image!";
                                                }
                                                
                                                /****************************************/
                                				/*										*/
                                				/* DELETE PREVIOUS IMAGE AND SET OPTION */
                                				/*										*/
                                				/****************************************/
                                                
                                                if ($copied_and_resized) {
                                                        
                                                    // delete previous cover
                                                    $previous_file_path = WMobilePack::wmp_get_setting("cover");
                                                    
                                                    if ($previous_file_path != ''){
                                                        unlink(WMP_FILES_UPLOADS_DIR.$previous_file_path);
                                                    }
                                                    
                                                    // save option
                                                    WMobilePack::wmp_update_settings("cover", $uniqueFilename);
                                                    
                                                    // add path in the response
                                                    $arrResponse['status'] = 1;
                                                    $arrResponse['uploaded_cover'] = WMP_FILES_UPLOADS_URL.$uniqueFilename;
                                                }
                                                
                                                // remove file from the default uploads folder
                                                unlink($movefile['file']);
                                            }   
                                        }
                                    }
                                }                      
                            }
                            
                            if ($has_uploaded_files == false){
                                $arrResponse['messages'][] = "Please upload a image!";
                            }
                        }
                    } 
                        
                } elseif ($action == 'delete'){
                    
                    /****************************************/
    				/*										*/
    				/* DELETE ICON / LOGO        			*/
    				/*										*/
    				/****************************************/
                            
                    // delete cover, depending on the 'source' param
                    if (isset($_GET['source'])) {
                        if ($_GET['source'] == 'cover'){
                            
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
			
                // check if we have a https connection
                $is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
                
    			// JSON URL that should be requested
    			$json_url = ($is_secure ? WMP_MORE_UPDATES_HTTPS : WMP_MORE_UPDATES);
                
				// get response
				$json_response = self::wmp_read_data($json_url);
				
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
                    
					// read contents of file
					while (!feof($json_file)) {	
						$json_response .= fgets($json_file);
					}
				}
				
				// return json response
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