<?php
require_once(__DIR__.'/../utils.php');

if (class_exists('WPMPTestsUtils')) {
	
	class RedirectPremiumTest extends WP_UnitTestCase {
	
		var $old_current_user;
		
		function setUp() {
			
			parent::setUp();
			
			// create admin user that can modify the plugin settings
			$this->old_current_user = get_current_user_id();
			
			$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
			wp_set_current_user( $user_id );
			
			// enable connection with the API key
			$arrData = array(
				'premium_api_key' => 'apikeytest',
				'premium_active'  => 1,
				// 'premium_config_path' => home_url().'/wp-content/plugins/wordpress-mobile-pack/tests/config_premium.json'
			);
				
			WMobilePack::wmp_update_settings($arrData);
			
			// switch theme to the one from the plugin
			/*register_theme_directory('plugins/wordpress-mobile-pack/themes');
			
			add_filter('theme_root', array(&$this, "_theme_root"));
    		add_filter('theme_root_uri', array(&$this, "_theme_root"));
			
			add_filter("stylesheet", "WMobilePack::wmp_app_theme");
            add_filter("template", "WMobilePack::wmp_app_theme");*/
		}
		
		
		
		function tearDown(){
			
			// disable connection with the API key
			$arrData = array(
				'premium_api_key' => '',
				'premium_active'  => 0,
				// 'premium_config_path' => ''
			);
			
			// save options
			WMobilePack::wmp_update_settings($arrData);
			
			wp_set_current_user( $this->old_current_user );
			
			parent::tearDown();
		}
	
		/**
		 * Return path to the WPMP theme folder
		 */
		function _theme_root() {
			return WP_CONTENT_DIR.'/plugins/wordpress-mobile-pack/themes';
		}
		
		
		/**
		 *
		 * Home page shouldn't redirect
		 *
		 */
		function test_home() {
			
			$themes = wp_get_theme();
			
			if (WMobilePack::wmp_get_setting('premium_active') == 1 && WMobilePack::wmp_get_setting('premium_api_key') != '') {
				
				$request_url = WPMPTestsUtils::get_furl(home_url());
				$response = WPMPTestsUtils::make_request($request_url);
				
				$this->assertEquals($response['redirect'], '');				
			}
		}
		
		/**
		 *
		 * A post should redirect to #articleUrl/{encoded_article_url}
		 *
		 */
		function test_post() {
			
			if (WMobilePack::wmp_get_setting('premium_active') == 1 && WMobilePack::wmp_get_setting('premium_api_key') != '') {
			
				$post_id = 1241;
				$request_url = WPMPTestsUtils::get_furl(home_url().'/?p='.$post_id);
	
				$response = WPMPTestsUtils::make_request($request_url);
				
				$encoded_url = rawurlencode($request_url);
				$encoded_url = str_replace('.','%2E',$encoded_url);
				
				$this->assertEquals($response['redirect'], home_url()."#articleUrl/".$encoded_url);
			}
		}
		
		
		
		/**
		 *
		 * A single page should redirect to #pageUrl/{encoded_page_url}
		 *
		 */
		function test_page() {
			
			if (WMobilePack::wmp_get_setting('premium_active') == 1 && WMobilePack::wmp_get_setting('premium_api_key') != '') {
			
				$page_id = 1086;
				$request_url = WPMPTestsUtils::get_furl(home_url().'/?page_id='.$page_id);
				
				$response = WPMPTestsUtils::make_request($request_url);
				
				$encoded_url = rawurlencode($request_url);
				$encoded_url = str_replace('.','%2E',$encoded_url);
				
				$this->assertEquals($response['redirect'], home_url()."#pageUrl/".$encoded_url);
			}
		}
		
		
		/**
		 *
		 * A category should redirect to #categoryWp/{category_id}
		 *
		 */
		function test_category() {
	 
			if (WMobilePack::wmp_get_setting('premium_active') == 1 && WMobilePack::wmp_get_setting('premium_api_key') != '') {
			
				$category_id = 38;
				$request_url = WPMPTestsUtils::get_furl(home_url().'?cat='.$category_id);
				
				$response = WPMPTestsUtils::make_request($request_url);
				
				$this->assertEquals($response['redirect'], home_url()."#categoryWp/".$category_id);
			}
		}
	}
}

