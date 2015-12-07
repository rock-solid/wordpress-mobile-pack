<?php
require_once(__DIR__.'/../utils.php');

if (class_exists('WPMPTestsUtils')) {
	
	class RedirectPremiumDomainTest extends WP_UnitTestCase {
	
		protected $old_current_user;
		protected static $test_subdomain = 'myapp.domaintest.com';
		
		function setUp() {
			
			parent::setUp();
			
			// create admin user that can modify the plugin settings
			$this->old_current_user = get_current_user_id();
			
			$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
			wp_set_current_user( $user_id );
			
			// enable connection with the API key
			$arrData = array(
				'premium_api_key' => 'apikeytest',
				'premium_active'  => 1
			);
				
			WMobilePack_Options::update_settings($arrData);
		}
		
		
		
		function tearDown(){
			
			// disable connection with the API key
			$arrData = array(
				'premium_api_key' => '',
				'premium_active'  => 0
			);
			
			// save options
			WMobilePack_Options::update_settings($arrData);
			
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
			
			if (WMobilePack_Options::get_setting('premium_active') == 1 && WMobilePack_Options::get_setting('premium_api_key') != '') {
				
				$request_url = WPMPTestsUtils::get_furl(home_url());
				$response = WPMPTestsUtils::make_request($request_url);
				
				$this->assertEquals($response['redirect'], 'http://'.self::$test_subdomain);				
			}
		}
		
		/**
		 *
		 * A post should redirect to #articleUrl/{encoded_article_url}
		 *
		 */
		function test_post() {
			
			if (WMobilePack_Options::get_setting('premium_active') == 1 && WMobilePack_Options::get_setting('premium_api_key') != '') {
			
				$post_id = 1;
				$request_url = WPMPTestsUtils::get_furl(home_url().'/?p='.$post_id);
	
				$response = WPMPTestsUtils::make_request($request_url);
				
				$encoded_url = rawurlencode($request_url);
				$encoded_url = str_replace('.','%2E',$encoded_url);
				
				$this->assertEquals($response['redirect'], 'http://'.self::$test_subdomain."#articleUrl/".$encoded_url);
			}
		}
		
		
		
		/**
		 *
		 * A single page should redirect to #pageUrl/{encoded_page_url}
		 *
		 */
		function test_page() {
			
			if (WMobilePack_Options::get_setting('premium_active') == 1 && WMobilePack_Options::get_setting('premium_api_key') != '') {
			
				$page_id = 2;
				$request_url = WPMPTestsUtils::get_furl(home_url().'/?page_id='.$page_id);
				
				$response = WPMPTestsUtils::make_request($request_url);
				
				$encoded_url = rawurlencode($request_url);
				$encoded_url = str_replace('.','%2E',$encoded_url);
				
				$this->assertEquals($response['redirect'], 'http://'.self::$test_subdomain."#pageUrl/".$encoded_url);
			}
		}
		
		
		/**
		 *
		 * A category should redirect to #categoryWp/{category_id}
		 *
		 */
		function test_category() {
	 
			if (WMobilePack_Options::get_setting('premium_active') == 1 && WMobilePack_Options::get_setting('premium_api_key') != '') {
			
				$category_id = 38;
				$request_url = WPMPTestsUtils::get_furl(home_url().'?cat='.$category_id);
				
				$response = WPMPTestsUtils::make_request($request_url);
				
				$this->assertEquals($response['redirect'], 'http://'.self::$test_subdomain."#categoryWp/".$category_id);
			}
		}
	}
}

