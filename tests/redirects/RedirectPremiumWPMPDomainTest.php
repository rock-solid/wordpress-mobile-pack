<?php
require_once(__DIR__ . '/../utils.php');

if (class_exists('WPMPTestsUtils')) {

    /**
     * Class RedirectPremiumWPMPDomainTest
     *
     * @group integrationTests
     */
    class RedirectPremiumWPMPDomainTest extends WP_UnitTestCase
    {

        protected static $test_subdomain = 'myapp.domaintest.com';

        function setUp()
        {

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
         * Home page should redirect
         *
         */
        function test_home()
        {
            if (WMobilePack_Options::get_setting('premium_active') == 1 && WMobilePack_Options::get_setting('premium_api_key') != '') {

                $request_url = WPMPTestsUtils::get_furl(home_url());
                $response = WPMPTestsUtils::make_request($request_url);

                $this->assertEquals($response['redirect'], 'http://' . self::$test_subdomain);
            }
        }

        /**
         *
         * A post should redirect to #article/{article_id}
         *
         */
        function test_post()
        {

            $post_id = 1;
            $request_url = WPMPTestsUtils::get_furl(home_url() . '?p=' . $post_id);

            $response = WPMPTestsUtils::make_request($request_url);

            $this->assertEquals($response['redirect'], 'http://'.self::$test_subdomain . "/#article/" . $post_id);
        }


        /**
         *
         * A single page should redirect to #page/{page_id}
         *
         */
        function test_page()
        {

            $page_id = 2;
            $request_url = WPMPTestsUtils::get_furl(home_url() . '?page_id=' . $page_id);
            $response = WPMPTestsUtils::make_request($request_url);

            $this->assertEquals($response['redirect'], 'http://'.self::$test_subdomain . "/#page/" . $page_id);

        }


        /**
         *
         * A category should redirect to #category/{category_slug}/{category_id}
         *
         */
        function test_category()
        {

            $category_id = 1;
            $request_url = WPMPTestsUtils::get_furl(home_url() . '?cat=' . $category_id);
            $response = WPMPTestsUtils::make_request($request_url);

            $this->assertEquals($response['redirect'], 'http://'.self::$test_subdomain . "/#category/uncategorized/" . $category_id);

        }

        /**
         *
         * A category should redirect to #category/{category_slug}/{category_id}
         * Test category with special chars
         */
        function test_category_specialchars()
        {

            $category_id = 2;
            $request_url = WPMPTestsUtils::get_furl(home_url() . '?cat=' . $category_id);
            $response = WPMPTestsUtils::make_request($request_url);

            $this->assertEquals($response['redirect'], 'http://'.self::$test_subdomain . "/#category/alignmentyeaeuu/" . $category_id);

        }
    }
}

