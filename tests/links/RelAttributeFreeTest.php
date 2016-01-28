<?php
require_once(__DIR__.'/../utils.php');

if (class_exists('WPMPTestsUtils')) {

    /**
     * Class RelAttributeFreeTest
     *
     * @group integrationTests
     *
     */
	class RelAttributeFreeTest extends WP_UnitTestCase {
	
		/**
		 *
		 * Rel alternate as {blog_url}/#article/{post_id}
		 *
		 */
		function test_post() {
	 
			if (!WMobilePack_Options::get_setting('premium_active') || WMobilePack_Options::get_setting('premium_api_key') == '') {
			
				$post_id = 1;
				$request_url = WPMPTestsUtils::get_furl(home_url().'?p='.$post_id);
				
				$response = WPMPTestsUtils::make_request($request_url, false);
					
				$rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="'.home_url().'/#article/'.$post_id.'" />';
				
				$this->assertContains($rel_tag, $response['content']);
			}
		}
		
		
		
		/**
		 *
		 * Rel alternate as {blog_url}/#page/{page_id}
		 *
		 */
		function test_page() {
	
			if (!WMobilePack_Options::get_setting('premium_active') || WMobilePack_Options::get_setting('premium_api_key') == '') {
			
				$page_id = 2;
				$request_url = WPMPTestsUtils::get_furl(home_url().'?page_id='.$page_id);
				$response = WPMPTestsUtils::make_request($request_url, false);
					
				$rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="'.home_url().'/#page/'.$page_id.'" />';
				
				$this->assertContains($rel_tag, $response['content']);
			}
		}
		
		
		/**
		 *
		 * Rel alternate as {blog_url}/#category/{category_slug}/{category_id}
		 *
		 */
		function test_category() {
	 
			if (!WMobilePack_Options::get_setting('premium_active') || WMobilePack_Options::get_setting('premium_api_key') == '') {
			
				$category_id = 38;
				$request_url = WPMPTestsUtils::get_furl(home_url().'?cat='.$category_id);
				$response = WPMPTestsUtils::make_request($request_url, false);
				
				$rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="'.home_url().'/#category/child-category-02/'.$category_id.'" />';
				
				$this->assertContains($rel_tag, $response['content']);
			}
		}
		
		
		/**
		 *
		 * Rel alternate as {blog_url}/#category/{category_slug}/{category_id}
		 *
		 */
		function test_category_specialchars() {
	 
			if (!WMobilePack_Options::get_setting('premium_active') || WMobilePack_Options::get_setting('premium_api_key') == '') {
			
				$category_id = 2;
				$request_url = WPMPTestsUtils::get_furl(home_url().'?cat='.$category_id);
				$response = WPMPTestsUtils::make_request($request_url, false);
				
				$rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="'.home_url().'/#category/alignmentyeaeuu/'.$category_id.'" />';
				
				$this->assertContains($rel_tag, $response['content']);
			}
		}
	}
}