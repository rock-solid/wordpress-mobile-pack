<?php

require_once(PWA_PLUGIN_PATH."export/class-export.php");

class SaveCommentTest extends WP_UnitTestCase
{

    function setUp(){
        parent::setUp();

        update_option('wmpack_inactive_categories', array());
        update_option('wmpack_ordered_categories', array());

        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/6.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/8.0 Mobile/10A5376e Safari/8536.25';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    }


    /**
     * Mock the export class
     *
     * @return mixed
     */
    function mock_export(){

        $WMP_Export_Mock = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_comments_allowed_hosts'))
            ->getMock();
        
        return $WMP_Export_Mock;
    }

    /**
     * Calling save_comment() with a HTTP REFERRER check allowed hosts
     */
    function test_save_comment_with_referrer_checks_hosts()
    {
        $_SERVER['HTTP_REFERER'] = 'app.appticles.com/abcdef';

        $export = $this->mock_export();

        $export->expects($this->once())
            ->method('get_comments_allowed_hosts')
            ->will(
                $this->returnValue(array('app.appticles.com/abcdef'))
            );

        $this->assertEquals($export->save_comment(), null);

        unset($_SERVER['HTTP_REFERER']);
    }


    /**
     * Calling save_comment() without a post id returns null
     */
    function test_save_comment_without_post_id_returns_null()
    {
        $export = $this->mock_export();
        $this->assertEquals($export->save_comment(), null);
    }

    /**
     * Calling save_comment() without a code returns null
     */
    function test_save_comment_without_code_returns_null()
    {
        $post_id = $this->factory->post->create();

        $_GET['articleId'] = $post_id;

        $export = $this->mock_export();
        $this->assertEquals($export->save_comment(), null);

        wp_delete_post($post_id);
    }

    /**
     * Calling save_comment() with invalid code returns null
     */
    function test_save_comment_with_invalid_code_returns_null()
    {
        $post_id = $this->factory->post->create();

        $_GET['articleId'] = $post_id;
        $_GET['code'] = "invalidaccesscode";

        $export = $this->mock_export();
        $this->assertEquals($export->save_comment(), null);

        wp_delete_post($post_id);
    }

    /**
     * Calling save_comment() for a page returns error
     */
    function test_save_comment_for_page_returns_error()
    {
        $post_id = $this->factory->post->create(
            array(
                'post_type' => 'page'
            )
        );

        $_GET['articleId'] = $post_id;
        $_GET['code'] = WMobilePack_Tokens::get_token();

        $export = $this->mock_export();
        $response = json_decode($export->save_comment(), true);

        $this->assertEquals(0, $response['status']);
        $this->assertEquals('Invalid post id', $response['message']);

        wp_delete_post($post_id);
    }

    /**
     * Calling save_comment() with a password protected post returns error
     */
    function test_save_comment_with_password_protected_post_returns_error()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_password' => '123123'
            )
        );

        $_GET['articleId'] = $post_id;
        $_GET['code'] = WMobilePack_Tokens::get_token();

        $export = $this->mock_export();
        $response = json_decode($export->save_comment(), true);

        $this->assertEquals(0, $response['status']);
        $this->assertEquals('Invalid post id', $response['message']);

        wp_delete_post($post_id);
    }

    /**
     * Calling save_comment() with a draft post returns error
     */
    function test_save_comment_with_draft_post_returns_error()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_status' => 'draft'
            )
        );

        $_GET['articleId'] = $post_id;
        $_GET['code'] = WMobilePack_Tokens::get_token();

        $export = $this->mock_export();
        $response = json_decode($export->save_comment(), true);

        $this->assertEquals(0, $response['status']);
        $this->assertEquals('Invalid post id', $response['message']);

        wp_delete_post($post_id);
    }

    /**
     * Calling save_comment() with a post from a hidden category returns error
     */
    function test_save_comment_with_post_from_hidden_category_returns_error()
    {

        $cat_id = $this->factory->category->create(
            array(
                'name' => 'Test Category'
            )
        );

        $post_id = $this->factory->post->create(
            array(
                'post_category' => array($cat_id)
            )
        );

        update_option('wmpack_inactive_categories', array($cat_id));

        $_GET['articleId'] = $post_id;
        $_GET['code'] = WMobilePack_Tokens::get_token();

        $export = $this->mock_export();
        $response = json_decode($export->save_comment(), true);

        $this->assertEquals(0, $response['status']);
        $this->assertEquals('Invalid post id', $response['message']);

        wp_delete_post($post_id);
        wp_delete_term($cat_id, 'category');
    }

    /**
     * Calling save_comment() with a post with closed comments returns error
     */
    function test_save_comment_with_closed_comments_returns_error()
    {

        $post_id = $this->factory->post->create(
            array(
                'comment_status' => 'closed'
            )
        );

        // make request & verify data
        $_GET['articleId'] = $post_id;
        $_GET['code'] = WMobilePack_Tokens::get_token();

        $export = $this->mock_export();
        $response = json_decode($export->save_comment(), true);

        $this->assertEquals(0, $response['status']);
        $this->assertEquals('Comments are closed', $response['message']);

        // clean-up data
        wp_delete_post($post_id);
    }

    /**
     * Calling save_comment() with missing name returns error
     */
    function test_save_comment_with_missing_name_returns_error()
    {

        $post_id = $this->factory->post->create();

        update_option('require_name_email', 1);

        // make request & verify data
        $_GET['articleId'] = $post_id;
        $_GET['code'] = WMobilePack_Tokens::get_token();
        $_GET['email'] = 'dummy@appticles.com';

        $export = $this->mock_export();
        $response = json_decode($export->save_comment(), true);

        $this->assertEquals(0, $response['status']);
        $this->assertEquals('Missing name or email', $response['message']);

        // clean-up data
        wp_delete_post($post_id);
    }

    /**
     * Calling save_comment() with missing email returns error
     */
    function test_save_comment_with_missing_email_returns_error()
    {

        $post_id = $this->factory->post->create();

        update_option('require_name_email', 1);

        // make request & verify data
        $_GET['articleId'] = $post_id;
        $_GET['code'] = WMobilePack_Tokens::get_token();
        $_GET['author'] = 'Comment Author';

        $export = $this->mock_export();
        $response = json_decode($export->save_comment(), true);

        $this->assertEquals(0, $response['status']);
        $this->assertEquals('Missing name or email', $response['message']);

        // clean-up data
        wp_delete_post($post_id);
    }

    /**
     * Calling save_comment() with invalid email returns error
     */
    function test_save_comment_with_invalid_email_returns_error()
    {

        $post_id = $this->factory->post->create();

        update_option('require_name_email', 1);

        // make request & verify data
        $_GET['articleId'] = $post_id;
        $_GET['code'] = WMobilePack_Tokens::get_token();
        $_GET['author'] = 'Comment Author';
        $_GET['email'] = 'This is not an email address';

        $export = $this->mock_export();
        $response = json_decode($export->save_comment(), true);

        $this->assertEquals(0, $response['status']);
        $this->assertEquals('Invalid email address', $response['message']);

        // clean-up data
        wp_delete_post($post_id);
    }

    /**
     * Calling save_comment() with missing message returns error
     */
    function test_save_comment_with_missing_message_returns_error()
    {

        $post_id = $this->factory->post->create();

        update_option('require_name_email', 1);

        // make request & verify data
        $_GET['articleId'] = $post_id;
        $_GET['code'] = WMobilePack_Tokens::get_token();
        $_GET['author'] = 'Comment Author';
        $_GET['email'] = 'dummy@appticles.com';

        $export = $this->mock_export();
        $response = json_decode($export->save_comment(), true);

        $this->assertEquals(0, $response['status']);
        $this->assertEquals('Missing comment', $response['message']);

        // clean-up data
        wp_delete_post($post_id);
    }

    /**
     * Calling save_comment() with valid data returns success
     */
    function test_save_comment_with_valid_data_returns_success()
    {

        $post_id = $this->factory->post->create(
            array('comment_status' => 'open')
        );

        update_option('require_name_email', 1);
        update_option('comment_moderation', 0);
        update_option('comment_whitelist', 0);

        // make request & verify data
        $_GET['articleId'] = $post_id;
        $_GET['code'] = WMobilePack_Tokens::get_token();
        $_GET['author'] = 'Comment Author';
        $_GET['email'] = 'dummy@appticles.com';
        $_GET['comment'] = 'This is a test comment '.time();

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $export = $this->mock_export();
        $response = json_decode($export->save_comment(), true);

        $this->assertEquals(1, $response['status']);
        $this->assertEquals('Your comment was successfully added', $response['message']);

        // clean-up data
        wp_delete_post($post_id);
    }

    /**
     * Calling save_comment() with valid data that needs to be moderated returns success
     */
    function test_save_comment_with_valid_data_and_moderation_returns_success()
    {

        $post_id = $this->factory->post->create();

        update_option('require_name_email', 1);
        update_option('comment_moderation', 1);

        // make request & verify data
        $_GET['articleId'] = $post_id;
        $_GET['code'] = WMobilePack_Tokens::get_token();
        $_GET['author'] = 'Comment Author';
        $_GET['email'] = 'dummy@appticles.com';
        $_GET['comment'] = 'This is a test comment '.time();

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $export = $this->mock_export();
        $response = json_decode($export->save_comment(), true);

        $this->assertEquals(2, $response['status']);
        $this->assertEquals('Your comment is awaiting moderation', $response['message']);

        // clean-up data
        wp_delete_post($post_id);
    }

    /**
     * Calling save_comment() with duplicate data
     *
     * @runInSeparateProcess
     *
     * @todo
     * This test is incomplete because the duplicate comment action will exit instead of calling wp_die()
     */
    function test_save_comment_with_duplicate_data_returns_error()
    {

        $post_id = $this->factory->post->create();

        update_option('require_name_email', 1);
        update_option('comment_moderation', 1);

        // make request & verify data
        $_GET['articleId'] = $post_id;
        $_GET['code'] = WMobilePack_Tokens::get_token();
        $_GET['author'] = 'Comment Author';
        $_GET['email'] = 'dummy@appticles.com';
        $_GET['comment'] = 'This is a test comment '.time();
        $_GET['callback'] = 'Ext.test';

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $export = $this->mock_export();
        $response = json_decode($export->save_comment(), true);

        $this->assertEquals(2, $response['status']);
        $this->assertEquals('Your comment is awaiting moderation', $response['message']);

        // Call save comment the second time, with the same data
        /*ob_start();
        try {
            $export->save_comment();
        } catch (Exception $e) {
            unset( $e );
        }

        $duplicate_response = array('status' => 0, 'message' => 'Duplicate comment');
        $this->assertEquals($_GET['callback'].'('.json_encode($duplicate_response).')', ob_get_clean());*/

        // clean-up data
        wp_delete_post($post_id);
    }

    /**
     * Calling save_comment() with optional name and email returns success
     */
    function test_save_comment_with_optional_name_email_returns_success()
    {

        $post_id = $this->factory->post->create();

        update_option('require_name_email', 0);
        update_option('comment_moderation', 1);

        // make request & verify data
        $_GET['articleId'] = $post_id;
        $_GET['code'] = WMobilePack_Tokens::get_token();
        $_GET['comment'] = 'This is a test comment '.time();

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $export = $this->mock_export();
        $response = json_decode($export->save_comment(), true);

        $this->assertEquals(2, $response['status']);
        $this->assertEquals('Your comment is awaiting moderation', $response['message']);

        // clean-up data
        wp_delete_post($post_id);
    }
}