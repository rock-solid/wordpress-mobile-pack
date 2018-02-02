<?php
require_once(__DIR__.'/../utils.php');

if (class_exists('WPMPTestsUtils')) {

    /**
     * Class RelAttributeTest
     *
     * @group integrationTests
     *
     */
	class RelAttributeTest extends WP_UnitTestCase {

		/**
		 *
		 * Rel alternate as {blog_url}/#article/{post_id}
		 *
		 */
		function test_post() {

			$post_id = 1;
			$request_url = WPMPTestsUtils::get_furl(home_url().'?p='.$post_id);

			$response = WPMPTestsUtils::make_request($request_url, false);

			$rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="'.home_url().'/#article/'.$post_id.'" />';

			$this->assertContains($rel_tag, $response['content']);
		}


        /**
         *
         * Rel alternate as {blog_url}/#article/{post_id} should be the same even if we have other get params
         *
         */
        function test_post_with_dummy_params() {

			$post_id = 1;
			$request_url = WPMPTestsUtils::get_furl(home_url().'?p='.$post_id.'&utm_source=June+1+2016&utm_campaign=4.20.16&utm_medium=email');

			$response = WPMPTestsUtils::make_request($request_url, false);

			$rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="'.home_url().'/#article/'.$post_id.'" />';

			$this->assertContains($rel_tag, $response['content']);
        }


		/**
		 *
		 * Rel alternate as {blog_url}/#page/{page_id}
		 *
		 */
		function test_page() {

			$page_id = 2;
			$request_url = WPMPTestsUtils::get_furl(home_url().'?page_id='.$page_id);
			$response = WPMPTestsUtils::make_request($request_url, false);

			$rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="'.home_url().'/#page/'.$page_id.'" />';

			$this->assertContains($rel_tag, $response['content']);
		}

		/**
		 *
		 * Rel alternate doesn't exist for inactive pages
		 *
		 */
		function test_page_inactive() {

			$page_id = 1088;

			$request_url = WPMPTestsUtils::get_furl(home_url().'?page_id='.$page_id);
			$response = WPMPTestsUtils::make_request($request_url, false);

			$rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="'.home_url().'/#page/'.$page_id.'" />';

			$this->assertFalse(strpos($response['content'], $rel_tag));
		}

		/**
		 *
		 * Rel alternate doesn't exist for page with inactive parent
		 *
		 */
		function test_page_inactive_parent() {

			$page_id = 1090;

			$request_url = WPMPTestsUtils::get_furl(home_url().'?page_id='.$page_id);
			$response = WPMPTestsUtils::make_request($request_url, false);

			$rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="'.home_url().'/#page/'.$page_id.'" />';

			$this->assertFalse(strpos($response['content'], $rel_tag));
		}

		/**
		 *
		 * Rel alternate doesn't exist for page with inactive grandparent
		 *
		 */
		function test_page_inactive_grandparent() {

			$page_id = 1105;

			$request_url = WPMPTestsUtils::get_furl(home_url().'?page_id='.$page_id);
			$response = WPMPTestsUtils::make_request($request_url, false);

			$rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="'.home_url().'/#page/'.$page_id.'" />';

			$this->assertFalse(strpos($response['content'], $rel_tag));
		}


		/**
		 *
		 * Rel alternate as {blog_url}/#category/{category_slug}/{category_id}
		 *
		 */
		function test_category() {

			$category_id = 38;
			$request_url = WPMPTestsUtils::get_furl(home_url().'?cat='.$category_id);
			$response = WPMPTestsUtils::make_request($request_url, false);

			$rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="'.home_url().'/#category/child-category-02/'.$category_id.'" />';

			$this->assertContains($rel_tag, $response['content']);
		}


		/**
		 *
		 * Rel alternate as {blog_url}/#category/{category_slug}/{category_id}
		 *
		 */
		function test_category_specialchars() {

			$category_id = 2;
			$request_url = WPMPTestsUtils::get_furl(home_url().'?cat='.$category_id);
			$response = WPMPTestsUtils::make_request($request_url, false);

			$rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="'.home_url().'/#category/alignmentyeaeuu/'.$category_id.'" />';

			$this->assertContains($rel_tag, $response['content']);
		}
	}
}
