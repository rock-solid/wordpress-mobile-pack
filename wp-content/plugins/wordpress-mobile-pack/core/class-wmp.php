<?php

/**
 * WMobilePack
 *
 * 
 */
class WMobilePack {

		
	/* ----------------------------------*/
	/* Properties						 */
	/* ----------------------------------*/
	
	public static $wmp_options;
		
		
	/* ----------------------------------*/
	/* Methods							 */
	/* ----------------------------------*/


	public function __construct(){
	
	   
		if(!is_array(self::$wmp_options) || empty(self::$wmp_options))
        
			self::$wmp_options = array(
				'theme' => 1,
				'color_scheme' => 1,
				'font' => 'Arial',
				'logo' => '',
				'icon' => '',
				'blog_name' => get_bloginfo( "name" ),
                'inactive_categories' => serialize(array())
		   );
	}
			
		
	/**
	 * Method wmp_install called on the activation of the plugin
	 * This method adds to the DB the default settings of the application
	 *
	 */
	public function wmp_install(){
		
		// add settings to database
		$this->wmp_save_settings(self::$wmp_options);
		
		
	}
		
	/**
	 * Method wmp_uninstall called on the deactivation of the plugin
	 * This method removes from the DB the settings of the application
	 *
	 */
	public function wmp_uninstall(){
		
		// remove settings from database
		$this->wmp_delete_settings(self::$wmp_options);
	}
	
		
	/**
	 * Method wmp_admin_init used to add the admin menu of the plugin and the css and javascript files
	 * 
	 *
	 */	
	public function wmp_admin_init(){
		
		// add admin menu hook
		add_action( 'admin_menu', array( &$this, 'wmp_admin_menu' ) );
        
        wp_enqueue_style('css_fonts', plugins_url(WMP_DOMAIN.'/admin/css/fonts.css'), array(), '2.0');
        wp_enqueue_style('css_ie', plugins_url(WMP_DOMAIN.'/admin/css/ie.css'), array(), '2.0');
        wp_enqueue_style('css_main', plugins_url(WMP_DOMAIN.'/admin/css/main.css'), array(), '2.0');	
        
        // enqueue css and javascript for the admin area
        add_action( 'admin_enqueue_scripts',array( &$this, 'wmp_enqueue_scripts' ) );
        // add_action( 'wp_enqueue_styles',array( &$this, 'wmp_enqueue_styles' ) );
        
	}
    
	/**
	 * Method wmp_enqueue_styles used to enqueue scripts for the admin area
	 * 
	 *
	 */	
	public function wmp_enqueue_styles() {
		
        wp_enqueue_style('css_fonts', plugins_url(WMP_DOMAIN.'/admin/css/fonts.css'), array(), '2.0');
        wp_enqueue_style('css_ie', plugins_url(WMP_DOMAIN.'/admin/css/ie.css'), array(), '2.0');
        wp_enqueue_style('css_main', plugins_url(WMP_DOMAIN.'/admin/css/main.css'), array(), '2.0');		
	}
	
	
	/**
	 * Method wmp_enqueue_scripts used to enqueue scripts for the admin area
	 * 
	 *
	 */	
	public function wmp_enqueue_scripts() {
		
        wp_enqueue_script('js_validate', plugins_url(WMP_DOMAIN.'/admin/js/UI.Interface/Lib/jquery.validate.min.js'), array('jquery-core', 'jquery-migrate'), '1.11.1');
        wp_enqueue_script('js_loader', plugins_url(WMP_DOMAIN.'/admin/js/UI.Interface/Loader.js'), array('jquery-core', 'jquery-migrate'), '2.0');
        wp_enqueue_script('js_ajax_upload', plugins_url(WMP_DOMAIN.'/admin/js/UI.Interface/AjaxUpload.js'), array('jquery-core', 'jquery-migrate'), '2.0');
        wp_enqueue_script('js_interface', plugins_url(WMP_DOMAIN.'/admin/js/UI.Interface/JSInterface.js'), array('jquery-core', 'jquery-migrate'), '2.0');	
	}
	
	
	/**
     * 
     * Load specific javascript files for the Content submenu page
     * 
     */
    public function load_content_js(){
        wp_enqueue_script('js_content_editcategories', plugins_url(WMP_DOMAIN.'/admin/js/UI.Modules/Content/EDIT_CATEGORIES.js'), array(), '2.0');
    }
    
    
    
	/**
	 * Method wmp_admin_menu  used to set the admin menu items of the plug-in
	 *
	 */	
	public function wmp_admin_menu(){
		
		// load admin class
		require_once(WMP_PLUGIN_PATH.'core/class-admin.php');
		$WMobilePackAdmin = new WMobilePackAdmin;
		
		// add menu and submenu hooks
		add_menu_page( 'WP Mobile Pack', 'WP Mobile Pack', 'manage_options', 'wmp-options', '', WP_PLUGIN_URL . '/wordpress-mobile-pack/admin/images/appticles-logo.png',62 );
		add_submenu_page( 'wmp-options', "What's New", "What's New", 'manage_options', 'wmp-options', array( &$WMobilePackAdmin, 'wmp_options' ) );
		add_submenu_page( 'wmp-options', 'Look & Feel', 'Look & Feel', 'manage_options', 'wmp-options-theme', array( &$WMobilePackAdmin, 'wmp_theme_options') );
        
		$content_page = add_submenu_page( 'wmp-options', 'Content', 'Content', 'manage_options', 'wmp-options-content', array( &$WMobilePackAdmin, 'wmp_content_options') );
        add_action( 'load-' . $content_page, array( &$this, 'load_content_js' ) );   
        
		add_submenu_page( 'wmp-options', 'Settings', 'Settings', 'manage_options', 'wmp-options-settings', array( &$WMobilePackAdmin, 'wmp_settings_options') );
		add_submenu_page( 'wmp-options', 'Upgrade', 'Upgrade', 'manage_options', 'wmp-options-upgrade', array( &$WMobilePackAdmin, 'wmp_upgrade_options') );
		
        
	}
		
     	
	/**
	 * Method wmp_get_setting used to return option/options of the plugin
	 *
	 * @param $option - array / string 
	 * If param is array, the method will return an array with the options, 
	 * otherwise it will return only the requested option value
	 *
	 */		
	public function wmp_get_setting($option) {
		
		// if option is array, return an array with settings
		if(is_array($option)) {
			
			foreach($option as $option_name => $option_value)	{
				if( get_option( 'wmpack_' . $option_name ) == '')
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
                echo "aici";
			} else
				$wmp_setting = get_option( 'wmpack_' . $option );
				
			return $wmp_setting;
		}
	}


	/**
	 * Method wmp_save_settings used to save the setting/settings of the plugin in options table in the db
	 *
	 * @param $option - array / string 
	 * @param $option_value - optional, mandatory only when $option is string
	 * 
	 * The method return true in case of success
	 *
	 */	
	public function wmp_save_settings( $option, $option_value = '' ) {
		
		if(is_array($option) && !empty($option)) {
		
			// set option not saved variable
			$option_not_saved = false;
		
			foreach($option as $option_name => $option_value) {
				
				if ( array_key_exists( $option_name , self::$wmp_options ) )
					add_option( 'wmpack_' . $option_name, $option_value );
				else
					$option_not_saved = true; // there is at least one option not in the default list
			}
		
			if(!$option_not_saved)
				return true;
			else
				return false; // there was an error
				
		} elseif(is_string($option) && $option_value != '') {

			if ( array_key_exists( $option , self::$wmp_options ) )
				return add_option( 'wmpack_' . $option, $option_value );
			
		}
		
		return false;
		
	}

	/**
	 * Method wmp_update_settings used to update the setting/settings of the plugin in options table in the db
	 *
	 * @param $option - array / string 
	 * @param $option_value - optional, mandatory only when $option is string
	 *
	 * The method return true in case of success and false otherwise
	 *
	 */	
	function wmp_update_settings( $option, $option_value = '' ) {
	
		if(is_array($option) && !empty($option)) {
			
			foreach($option as $option_name => $option_value) {
				
				// set option not saved variable
				$option_not_updated = false;
				
				if ( array_key_exists( $option_name , self::$wmp_options ) )
					update_option( 'wmpack_' . $option_name, $option_value );
				else
					$option_not_updated = true; // there is at least one option not in the default list
					
				if(!$option_not_updated)
					return true;
				else
					return false; // there was an error
				
			}
		
			return true;
			
		} elseif(is_string($option) && $option_value != '') {
			
			if ( array_key_exists( $option , self::$wmp_options ) )
				return update_option( 'wmpack_' . $option, $option_value );
			
		}
		
		return false;
	}

	
	/**
	 * Method wmp_delete_settings used to delete the setting/settings of the plugin in options table in the db
	 *
	 * @param $option - array / string 
	 *
	 * The method return true in case of success and false otherwise
	 *
	 */	
	function wmp_delete_settings( $option ) {
	
		if(is_array($option) && !empty($option)) {
			
			foreach($option as $option_name => $option_value) {
				
				// set option not saved variable
				$option_not_updated = false;
				
				
				if ( array_key_exists( $option_name , self::$wmp_options ) )
					delete_option( 'wmpack_' . $option_name );
				
			}
		
			return true;
			
		} elseif(is_string($option)) {
			
			if ( array_key_exists( $option , self::$wmp_options ) )
				return delete_option( 'wmpack_' . $option );
			
		}
	}

		
		
		
		
		public function wmp_check_load(){
			
			$load_app = false;
			
			
			if(!isset($_COOKIE["load_app"])) {
				
				// load admin class
				require_once(WMP_PLUGIN_PATH.'core/mobile-detect.php');
				$WMobileDetect = new WPMobileDetect;
				
				$load_app = $WMobileDetect->wmp_detect_device();
				
				
			} elseif(isset($_COOKIE["load_app"]) && $_COOKIE["load_app"] == 1)
				$load_app = true;	
			
			
			if($load_app) 
				// load app
			$this->wmp_load_app();
			
				
		}
		
		
		public function wmp_load_app(){
			
			
			// enqeue javascripts and css for the app
			//send params to the app
			// load application
			
		}
	}

