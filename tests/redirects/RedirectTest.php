<?php
require_once(__DIR__.'/../utils.php');

if (class_exists('WPMPTestsUtils')) {

    /**
     * Class RedirectTest
     *
     * @group integrationTests
     */
	class RedirectTest extends WP_UnitTestCase {
	
		/**
		 *
		 * Home page should not redirect
		 *
		 */
		function test_home() {

			$request_url = WPMPTestsUtils::get_furl(home_url());
			$response = WPMPTestsUtils::make_request($request_url);
			
			$this->assertEquals($response['redirect'], '');				
		}
		
		/**
		 *
		 * A post should redirect to #article/{article_id}
		 *
		 */
		function test_post() {
			
			$post_id = 1;
			$request_url = WPMPTestsUtils::get_furl(home_url().'?p='.$post_id);

			$response = WPMPTestsUtils::make_request($request_url);

			$this->assertEquals($response['redirect'], home_url()."/#article/".$post_id);
		}
		
		
		
		/**
		 *
		 * A single page should redirect to #page/{page_id}
		 *
		 */
		function test_page() {
			
			$page_id = 2;
			$request_url = WPMPTestsUtils::get_furl(home_url().'?page_id='.$page_id);
			$response = WPMPTestsUtils::make_request($request_url);
				
			$this->assertEquals($response['redirect'], home_url()."/#page/".$page_id);
		}
		
		
		/**
		 *
		 * A category should redirect to #category/{category_slug}/{category_id}
		 *
		 */
		function test_category() {
			
			$category_id = 1;
			$request_url = WPMPTestsUtils::get_furl(home_url().'?cat='.$category_id);
			$response = WPMPTestsUtils::make_request($request_url);
				
			$this->assertEquals($response['redirect'], home_url()."/#category/uncategorized/".$category_id);
		}
		
		/**
		 *
		 * A category should redirect to #category/{category_slug}/{category_id}
		 * Test category with special chars
		 */
		function test_category_specialchars() {
			
			$category_id = 2;
			$request_url = WPMPTestsUtils::get_furl(home_url().'?cat='.$category_id);
			$response = WPMPTestsUtils::make_request($request_url);
				
			$this->assertEquals($response['redirect'], home_url()."/#category/alignmentyeaeuu/".$category_id);
		}
	}
}

