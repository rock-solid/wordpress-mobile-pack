<?php
require_once(__DIR__.'/../utils.php');

if (class_exists('WPMPTestsUtils')) {

    /**
     * Class RelAttributePremiumWPMPDomainTest
     *
     * @group integrationTests
     *
     */
    class RelAttributePremiumWPMPTest extends WP_UnitTestCase {

        protected static $test_subdomain = 'myapp.domaintest.com';

        function setUp() {

            parent::setUp();

            $premium_config = array(
                'kit_type' => 'wpmp',
                'domain_name' => self::$test_subdomain
            );

            update_option('wmpack_premium_api_key', 'apikey');
            update_option('wmpack_premium_active', 1);
            set_transient('wmp_premium_config_path', json_encode($premium_config));
        }


        /**
         *
         * Rel alternate as {blog_url}/#article/{post_id}
         *
         */
        function test_post() {

            $post_id = 1;
            $request_url = WPMPTestsUtils::get_furl(home_url().'?p='.$post_id);

            $response = WPMPTestsUtils::make_request($request_url, false);

            $rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="http://'.self::$test_subdomain.'/#article/'.$post_id.'" />';

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

            $rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="http://'.self::$test_subdomain.'/#page/'.$page_id.'" />';

            $this->assertContains($rel_tag, $response['content']);
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

            $rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="http://'.self::$test_subdomain.'/#category/child-category-02/'.$category_id.'" />';

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

            $rel_tag = '<link rel="alternate" media="only screen and (max-width: 640px)" href="http://'.self::$test_subdomain.'/#category/alignmentyeaeuu/'.$category_id.'" />';

            $this->assertContains($rel_tag, $response['content']);
        }
    }
}