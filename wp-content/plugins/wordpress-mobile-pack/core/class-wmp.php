<?php

if ( ! class_exists( 'WMobilePack' ) ) { 

    /**
     * WMobilePack
     * 
     * Main class for the Wordpress Mobile Pack plugin. This class handles:
     * 
     * - the install and uninstall of the plugin
     * - setting / getting the plugin's options
     * - loading the admin section, javascript and css files
     * - loading the app in thef frontend 
     * 
     */    
    class WMobilePack {
    
    		
    	/* ----------------------------------*/
    	/* Properties						 */
    	/* ----------------------------------*/
    	
    	public static $wmp_options;
        public static $wmp_allowed_fonts = array('Roboto Condensed', 'Georgia', 'Times New Roman', 'Open Sans');
        public static $wmp_basic_theme = 'base';
        
        // the oldest version that will enable the custom select
        public static $wmp_customselect_enable = 3.6;
    		
    		
    	/* ----------------------------------*/
    	/* Methods							 */
    	/* ----------------------------------*/
    
        /**
         * 
         * Construct method that initializes the plugin's options
         * 
         */
    	public function __construct(){
    	
    		if(!is_array(self::$wmp_options) || empty(self::$wmp_options)){
                
                self::$wmp_options = array(
                
                    'blog_name'             => get_bloginfo( "name" ),
                	'theme'                 => 1,
                	'color_scheme'          => 1,
                	'font_headlines'        => self::$wmp_allowed_fonts[0],
                    'font_subtitles'        => self::$wmp_allowed_fonts[0],
                    'font_paragraphs'       => self::$wmp_allowed_fonts[0],
                    'inactive_categories'   => serialize(array()),
                    'joined_waitlists'      => serialize(array()),
                    'display_mode'          => 'normal',
                	'logo'                  => '',
                	'icon'                  => ''
                    
                );
            }
    	}
    			
    		
    	/**
         * 
    	 * The wmp_install method is called on the activation of the plugin.
    	 * This method adds to the DB the default settings of the application.
    	 *
    	 */
    	public function wmp_install(){
    		
    		// add settings to database
    		$this->wmp_save_settings(self::$wmp_options);
    	}
    		
    	/**
         * 
    	 * The wmp_uninstall method is called on the deactivation of the plugin.
    	 * This method removes from the DB the settings of the application and associated files.
    	 *
    	 */
    	public function wmp_uninstall(){
    		
            // remove uploaded images and uploads folder
            $logo_path = WMobilePack::wmp_get_setting('logo');
            
            if ($logo_path != '' && !file_exists(WMP_FILES_UPLOADS_DIR.$logo_path))
                unlink(WMP_FILES_UPLOADS_DIR.$logo_path);  
            
            $icon_path = WMobilePack::wmp_get_setting('icon');
            
            if ($icon_path != '' && !file_exists(WMP_FILES_UPLOADS_DIR.$icon_path))
                unlink(WMP_FILES_UPLOADS_DIR.$icon_path);  
                
            rmdir( WMP_FILES_UPLOADS_DIR );
            
    		// remove settings from database
    		$this->wmp_delete_settings(self::$wmp_options);
    	}
    	
    		
    	/**
    	 * 
         * The wmp_admin_init method is used to add the admin menu of the plugin, the css and javascript files.
    	 *
    	 */	
    	public function wmp_admin_init(){
    		
    		// add admin menu hook
    		add_action( 'admin_menu', array( &$this, 'wmp_admin_menu' ) );
            
    		// enqueue css and javascript for the admin area
            add_action( 'admin_enqueue_scripts',array( &$this, 'wmp_admin_enqueue_scripts' ) );
    	}
        
    	
    	/**
         * 
    	 * The wmp_admin_enqueue_scripts is used to enqueue scripts and styles for the admin area.
         * The scripts and styles loaded by this method are used on all admin pages.
    	 *
    	 */	
    	public function wmp_admin_enqueue_scripts() {
    		
    		// enqueue styles
    		wp_enqueue_style('css_fonts', plugins_url(WMP_DOMAIN.'/admin/css/fonts.css'), array(), WMP_VERSION);
            wp_enqueue_style('css_ie', plugins_url(WMP_DOMAIN.'/admin/css/ie.css'), array(), WMP_VERSION);
            wp_enqueue_style('css_main', plugins_url(WMP_DOMAIN.'/admin/css/main.css'), array(), WMP_VERSION);	
            //wp_enqueue_style('css_main', 'http://dev.webcrumbz.co/~flori/dashboard-cutting/wp/resources/css/main.css', array(), WMP_VERSION);
			
			wp_enqueue_style('css_scrollbar', plugins_url(WMP_DOMAIN.'/admin/css/perfect-scrollbar.css'), array(), WMP_VERSION);
            
            // enqueue scripts
            wp_enqueue_script('js_validate', plugins_url(WMP_DOMAIN.'/admin/js/UI.Interface/Lib/jquery.validate.min.js'), array('jquery-core', 'jquery-migrate'), '1.11.1');
            wp_enqueue_script('js_validate_additional', plugins_url(WMP_DOMAIN.'/admin/js/UI.Interface/Lib/validate-additional-methods.min.js'), array('jquery-core', 'jquery-migrate'), '1.11.1');
            wp_enqueue_script('js_loader', plugins_url(WMP_DOMAIN.'/admin/js/UI.Interface/Loader.js'), array('jquery-core', 'jquery-migrate'), WMP_VERSION);
            wp_enqueue_script('js_ajax_upload', plugins_url(WMP_DOMAIN.'/admin/js/UI.Interface/AjaxUpload.js'), array('jquery-core', 'jquery-migrate'), WMP_VERSION);
            wp_enqueue_script('js_interface', plugins_url(WMP_DOMAIN.'/admin/js/UI.Interface/JSInterface.js'), array('jquery-core', 'jquery-migrate'), WMP_VERSION);	
    	    wp_enqueue_script('js_scrollbar', plugins_url(WMP_DOMAIN.'/admin/js/UI.Interface/Lib/perfect-scrollbar.js'), array(), WMP_VERSION);	
    		wp_enqueue_script('js_feedback', plugins_url(WMP_DOMAIN.'/admin/js/UI.Modules/Feedback/WMP_SEND_FEEDBACK.js'), array(), WMP_VERSION);	
            wp_enqueue_script('js_newsletter', plugins_url(WMP_DOMAIN.'/admin/js/UI.Modules/Newsletter/WMP_NEWSLETTER.js'), array(), WMP_VERSION);
    	}
    	
    	
    	/**
         * 
         * Load specific javascript files for the admin Content submenu page
         * 
         */
        public function wmp_admin_load_content_js(){
            wp_enqueue_script('js_content_editcategories', plugins_url(WMP_DOMAIN.'/admin/js/UI.Modules/Content/WMP_EDIT_CATEGORIES.js'), array(), WMP_VERSION);
            wp_enqueue_script('js_join_waitlist', plugins_url(WMP_DOMAIN.'/admin/js/UI.Modules/Waitlist/WMP_WAITLIST.js'), array(), WMP_VERSION);
        }
        
        
        /**
         * 
         * Load specific javascript files for the admin Settings submenu page
         * 
         */
        public function wmp_admin_load_settings_js(){
            wp_enqueue_script('js_settings_editdisplay', plugins_url(WMP_DOMAIN.'/admin/js/UI.Modules/Settings/WMP_EDIT_DISPLAY.js'), array(), WMP_VERSION);
            wp_enqueue_script('js_join_waitlist', plugins_url(WMP_DOMAIN.'/admin/js/UI.Modules/Waitlist/WMP_WAITLIST.js'), array(), WMP_VERSION);
        }
        
        /**
         * 
         * Load specific javascript files for the admin Look & Feel submenu page
         * 
         */
        public function wmp_admin_load_theme_js(){
            
            $blog_version = floatval(get_bloginfo('version'));
            
            // activate custom select for newer wp versions
            if ($blog_version >= self::$wmp_customselect_enable) {
                wp_enqueue_style('css_select_box_it', plugins_url(WMP_DOMAIN.'/admin/css/jquery.selectBoxIt.css'), array(), '3.8.1');
                wp_enqueue_script('js_select_box_it', plugins_url(WMP_DOMAIN.'/admin/js/UI.Interface/Lib/jquery.selectBoxIt.min.js'), array('jquery','jquery-ui-core', 'jquery-ui-widget'), '3.8.1');
            }
            
            wp_enqueue_script('js_settings_edittheme', plugins_url(WMP_DOMAIN.'/admin/js/UI.Modules/Theming/WMP_EDIT_THEME.js'), array(), WMP_VERSION);
            wp_enqueue_script('js_settings_editimages', plugins_url(WMP_DOMAIN.'/admin/js/UI.Modules/Theming/WMP_EDIT_IMAGES.js'), array(), WMP_VERSION);
            wp_enqueue_script('js_join_waitlist', plugins_url(WMP_DOMAIN.'/admin/js/UI.Modules/Waitlist/WMP_WAITLIST.js'), array(), WMP_VERSION);
        }
        
        
    	/**
    	 * 
         * Build the admin menu and add all admin pages of the plugin
    	 *
    	 */	
    	public function wmp_admin_menu(){
    		
    		// load admin class
    		require_once(WMP_PLUGIN_PATH.'core/class-admin.php');
    		$WMobilePackAdmin = new WMobilePackAdmin;
    		
    		// add menu and submenu hooks
    		add_menu_page( 'WP Mobile Pack', 'WP Mobile Pack', 'manage_options', 'wmp-options', '', WP_PLUGIN_URL . '/wordpress-mobile-pack/admin/images/appticles-logo.png' );
    		add_submenu_page( 'wmp-options', "What's New", "What's New", 'manage_options', 'wmp-options', array( &$WMobilePackAdmin, 'wmp_options' ) );
    		
            $theme_page = add_submenu_page( 'wmp-options', 'Look & Feel', 'Look & Feel', 'manage_options', 'wmp-options-theme', array( &$WMobilePackAdmin, 'wmp_theme_options') );
            add_action( 'load-' . $theme_page, array( &$this, 'wmp_admin_load_theme_js' ) );   
            
    		$content_page = add_submenu_page( 'wmp-options', 'Content', 'Content', 'manage_options', 'wmp-options-content', array( &$WMobilePackAdmin, 'wmp_content_options') );
            add_action( 'load-' . $content_page, array( &$this, 'wmp_admin_load_content_js' ) );   
            
    		$settings_page = add_submenu_page( 'wmp-options', 'Settings', 'Settings', 'manage_options', 'wmp-options-settings', array( &$WMobilePackAdmin, 'wmp_settings_options') );
            add_action( 'load-' . $settings_page, array( &$this, 'wmp_admin_load_settings_js' ) ); 
            
    		add_submenu_page( 'wmp-options', 'Upgrade', 'Upgrade', 'manage_options', 'wmp-options-upgrade', array( &$WMobilePackAdmin, 'wmp_upgrade_options') ); 
    	}
    		
         	
    	/**
    	 * The wmp_get_setting method is used to read an option value (or options) from the database.
    	 *
    	 * @param $option - array / string 
         * 
    	 * If the $option param is an array, the method will return an array with the values, 
    	 * otherwise it will return only the requested option value.
    	 *
    	 */		
    	public function wmp_get_setting($option) {
    		
    		// if the passed param is an array, return an array with all the settings
    		if (is_array($option)) {
    			
    			foreach($option as $option_name => $option_value)	{
    				if ( get_option( 'wmpack_' . $option_name ) == '')
    					$wmp_settings[$option_name] = self::$wmp_options[$option_name];
    				else
    					$wmp_settings[$option_name] = get_option( 'wmpack_' . $option_name );
    			}
    			
    			// return array
    			return $wmp_settings;
    			
    		} elseif(is_string($option)) { // if option is a string, return the value of the option
    			
                // check if the option is added in the db 
    			if ( get_option( 'wmpack_' . $option ) === false ) { 
    				$wmp_setting = self::$wmp_options[$option];
    			} else
    				$wmp_setting = get_option( 'wmpack_' . $option );
    				
    			return $wmp_setting;
    		}
    	}
    
    
    	/**
         * 
    	 * The wmp_save_settings method is used to save an option value (or options) in the database.
    	 *
    	 * @param $option - array / string 
         * @param $option_value - optional, mandatory only when $option is a string
         * 
         * @return bool
    	 *
    	 */
    	public function wmp_save_settings( $option, $option_value = '' ) {
    		
    		if (is_array($option) && !empty($option)) {
    		
    			// set option not saved variable
    			$option_not_saved = false;
    		
    			foreach($option as $option_name => $option_value) {
    				
    				if (array_key_exists( $option_name , self::$wmp_options))
    					add_option( 'wmpack_' . $option_name, $option_value );
    				else
    					$option_not_saved = true; // there is at least one option not in the default list
    			}
    		
    			if (!$option_not_saved)
    				return true;
    			else
    				return false; // there was an error
    				
    		} elseif (is_string($option) && $option_value != '') {
    
    			if (array_key_exists( $option , self::$wmp_options))
    				return add_option( 'wmpack_' . $option, $option_value );
    			
    		}
    		
    		return false;
    		
    	}
    
        /**
         * 
    	 * The wmp_update_settings method is used to update the setting/settings of the plugin in options table in the database.
    	 *
    	 * @param $option - array / string 
         * @param $option_value - optional, mandatory only when $option is a string
         * 
         * @return bool
    	 *
    	 */
    	public function wmp_update_settings( $option, $option_value = null ) {
    	
    		if (is_array($option) && !empty($option)) {
    			
    			foreach ($option as $option_name => $option_value) {
    				
    				// set option not saved variable
    				$option_not_updated = false;
    				
    				if ( array_key_exists( $option_name , self::$wmp_options ) )
    					update_option( 'wmpack_' . $option_name, $option_value );
    				else
    					$option_not_updated = true; // there is at least one option not in the default list
    					
    				if (!$option_not_updated)
    					return true;
    				else
    					return false; // there was an error
    				
    			}
    		
    			return true;
    			
    		} elseif (is_string($option) && $option_value !== null) {
    			
    			if ( array_key_exists( $option , self::$wmp_options ) )
    				return update_option( 'wmpack_' . $option, $option_value );
    			
    		}
    		
    		return false;
    	}
    	
    
         /**
         * 
    	 * The wmp_delete_settings method is used to delete the setting/settings of the plugin from the options table in the database.
    	 *
    	 * @param $option - array / string 
         * 
         * @return bool
    	 *
    	 */
    	public function wmp_delete_settings( $option ) {
    	
    		if (is_array($option) && !empty($option)) {
    			
    			foreach($option as $option_name => $option_value) {
    				
    				// set option not saved variable
    				$option_not_updated = false;
    				
    				
    				if ( array_key_exists( $option_name , self::$wmp_options ) )
    					delete_option( 'wmpack_' . $option_name );
    				
    			}
    		
    			return true;
    			
    		} elseif (is_string($option)) {
    			
    			if ( array_key_exists( $option , self::$wmp_options ) )
    				return delete_option( 'wmpack_' . $option );
    			
    		}
    	}
    
    
        /**
         * 
         * Method that checks if we can load the mobile web application theme and calls the method that sets the custom theme.
         *
         * The theme is loaded if ALL of the following conditions are met:
         * 
         * - the user comes from a supported mobile device and browser
         * - the user has not deactivate the view of the mobile theme by switching to desktop mode
         * - the display mode of the app is set to 'normal' or is set to 'preview' and an admin is logged in 
         * 
         */		
    	public function wmp_check_load(){
    		
    		$load_app = false;
            
            $desktop_mode = self::wmp_check_desktop_mode();
            
            if ($desktop_mode == false) {
                
                if (self::wmp_check_display_mode()) {
        		
            		if (!isset($_COOKIE["wmp_load_app"])) {
            			
            			// load admin class
            			require_once(WMP_PLUGIN_PATH.'core/mobile-detect.php');
            			$WMobileDetect = new WPMobileDetect;
            			
            			$load_app = $WMobileDetect->wmp_detect_device();
            			
            		} elseif (isset($_COOKIE["wmp_load_app"]) && $_COOKIE["wmp_load_app"] == 1)
            			$load_app = true;	
                        
                    if ($load_app)
                        $this->wmp_load_app();
                }
                
            } else {
                
                // add the option to view the app in the footer of the website
            }
    	}
        
        
        /**
        *
        * Check if the app display is enabled
        * 
        * Returns true if display mode is "normal" (enabled for all mobile users) or
        * if display mode is "preview" and an admin is logged in.
        * 
        * @return bool
        *   
        */
        public function wmp_check_display_mode(){
            
            $display_mode = self::wmp_get_setting('display_mode');
            
            if ($display_mode == 'normal')
                return true;
                
            elseif ($display_mode == 'preview') {
                
                if (is_user_logged_in() && current_user_can('create_users'))
                    return true;
            }
            
            return false;
        }
        
        /**
         * 
         * Check if the user selected to view the desktop mode or we can display the app.
         * 
         * The GET/COOKIE "wmp_theme_mode" can have two values: 'desktop' or 'mobile'.
         * 
         * - Desktop mode can be activated from the app by selecting to return to desktop view.
         * - Mobile mode can be reactivated from the footer of the website.
         * 
         * @return bool
         * 
         */
        public function wmp_check_desktop_mode(){
            
            $desktop_mode = false;
            
            if (isset($_GET['wmp_theme_mode']) && is_string($_GET['wmp_theme_mode'])){
                
                if ($_GET['wmp_theme_mode'] == "desktop" || $_GET['wmp_theme_mode'] == "mobile"){
                    setcookie("wmp_theme_mode", $_GET['wmp_theme_mode'], time()+3600*30*24,'/');
                }
                
                if ($_GET['wmp_theme_mode'] == "desktop")
                    $desktop_mode = true;
                    
            } else {
                
                if (isset($_COOKIE["wmp_theme_mode"]) && is_string($_COOKIE['wmp_theme_mode'])){
                    if ($_COOKIE['wmp_theme_mode'] == "desktop")
                        $desktop_mode = true;
                }
            }
            
            return $desktop_mode;
        }
    		
    	/**
         * 
         * Method that loads the mobile web application theme.
         * 
         * The theme url and theme name from the WP installation are overwritten by the settings below.
         * 
         */
    	public function wmp_load_app(){
    		
    		add_filter("stylesheet", array(&$this, "wmp_app_theme"));
            add_filter("template", array(&$this, "wmp_app_theme"));
        
    		add_filter( 'theme_root', array( &$this, 'wmp_app_theme_root' ) );
    		add_filter( 'theme_root_uri', array( &$this, 'wmp_app_theme_root' ) );			
    	}
        
        /**
         * Return the theme name
         */
        public function wmp_app_theme() {
    		return self::$wmp_basic_theme;
    	}
    	
        /**
         * Return path to the mobile themes folder
         */
    	public function wmp_app_theme_root() {
    		return WMP_PLUGIN_PATH . 'themes';
    	}
     	
    		
         /**
          * 
          * Method used to create a token for the comments form.
          * 
          * The method returns a string formed using the encoded domain and a timestamp.
          * 
          * @return string
          * 
          */
    	public static function wmp_set_token(){
    		
    		$token = md5(md5(get_bloginfo("wpurl")).WMP_CODE_KEY);
    		
    		// encode token again
    		$token = base64_encode($token.'_'.strtotime('+1 hour'));
    		
    		// generate token
    		return $token;
    	}
    		
    		
        /**
          * 
          * Method used to check if a generated token is valid.
          * 
          * The method returns true if the token is valid and false otherwise.
          * 
          * @param $token - string
          * @return bool
          * 
          */
    	public static function wmp_check_token($token){
    		
    		if (base64_decode($token,true)){
    			
    			// decode token to get timestamp and encoded url
    			$decoded_token = base64_decode($token,true);
    			
    			if (strpos($decoded_token, "_") !== FALSE) {
    				
    				// get params
    				$arrParams = explode('_',$decoded_token);
    				
    				if (is_array($arrParams) && !empty($arrParams) && count($arrParams) == 2) {
    					
    					// check timestamp
    					if (time() < $arrParams[1]) {
    						
    						// get the generated encoded domain
    						$generated_url = md5(md5(get_bloginfo("wpurl")).WMP_CODE_KEY);
    						// check encoded domain
    						if($arrParams[0] ==  $generated_url)
    							return true;
    					
    					}
    				}
    			}
    		}
    		
    		// by default return false;
    		return false; 
    	}
    }
}