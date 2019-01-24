<?php

require_once(PWA_PLUGIN_PATH."export/class-export.php");

class ExportCommentsTest extends WP_UnitTestCase
{

    function setUp(){
        parent::setUp();

        update_option('wmpack_inactive_categories', array());
        update_option('wmpack_ordered_categories', array());
    }

    /**
     * Calling export_comments() without a post id returns error
     */
    function test_export_comments_without_id_returns_error()
    {
        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_comments(), json_encode(array('error' => 'Invalid post id')));
    }

    /**
     * Calling export_comments() for a page returns empty json
     */
    function test_export_comments_does_not_retrieve_page()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_type' => 'page'
            )
        );

        $comment_id = $this->factory->comment->create(
            array(
                'comment_post_ID' => $post_id
            )
        );

        $_GET['articleId'] = $post_id;

        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_comments(), json_encode(array('comments' => array())));

        wp_delete_post($post_id);
        wp_delete_comment($comment_id);
    }

    /**
     * Calling export_comments() with a password protected post returns empty json
     */
    function test_export_comments_with_password_protected_post_returns_empty()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_password' => '123123'
            )
        );

        $comment_id = $this->factory->comment->create(
            array(
                'comment_post_ID' => $post_id
            )
        );

        $_GET['articleId'] = $post_id;

        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_comments(), json_encode(array('comments' => array())));

        wp_delete_post($post_id);
        wp_delete_comment($comment_id);
    }

    /**
     * Calling export_comments() with a draft post returns empty json
     */
    function test_export_comments_with_draft_post_returns_empty()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_status' => 'draft'
            )
        );

        $comment_id = $this->factory->comment->create(
            array(
                'comment_post_ID' => $post_id
            )
        );

        $_GET['articleId'] = $post_id;

        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_comments(), json_encode(array('comments' => array())));

        wp_delete_post($post_id);
        wp_delete_comment($comment_id);
    }

    /**
     * Calling export_comments() with a post from a hidden category returns empty json
     */
    function test_export_comments_with_post_from_hidden_category_returns_empty()
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

        $comment_id = $this->factory->comment->create(
            array(
                'comment_post_ID' => $post_id
            )
        );

        $_GET['articleId'] = $post_id;

        update_option('wmpack_inactive_categories', array($cat_id));

        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_comments(), json_encode(array('comments' => array())));

        wp_delete_post($post_id);
        wp_delete_term($cat_id, 'category');
        wp_delete_comment($comment_id);
    }

    /**
     * Calling export_comments() with a post with unapproved comments returns empty json
     */
    function test_export_comments_with_unapproved_comments_returns_empty()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_title' => 'Article Title'
            )
        );

        $comment_id = $this->factory->comment->create(
            array(
                'comment_post_ID' => $post_id,
                'comment_approved' => 0
            )
        );

        // make request & verify data
        $_GET['articleId'] = $post_id;

        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_comments(), json_encode(array('comments' => array())));

        // clean-up data
        wp_delete_post($post_id);
        wp_delete_comment($comment_id);
    }

    /**
     * Calling export_comments() with a post with active comments returns data json
     */
    function test_export_comments_returns_data()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_title' => 'Article Title'
            )
        );

        $comment_id = $this->factory->comment->create(
            array(
                'comment_post_ID' => $post_id,
                'comment_content' => 'test comment',
                'comment_author' => 'test author',
                'comment_author_url' => 'http://dummy.appticles.com'
            )
        );

        // make request & verify data
        $_GET['articleId'] = $post_id;

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_comments(), true);

        $this->assertArrayHasKey('comments', $data);
        $this->assertEquals(1, count($data['comments']));

        $comment_data = $data['comments'][0];
        $this->assertEquals($comment_id, $comment_data['id']);
        $this->assertEquals('Test author', $comment_data['author']);
        $this->assertEquals('http://dummy.appticles.com', $comment_data['author_url']);
        $this->assertEquals('test comment', $comment_data['content']);
        $this->assertEquals($post_id, $comment_data['article_id']);
        $this->assertEquals('Article Title', $comment_data['article_title']);
        $this->assertEquals(date('D, F d'), $comment_data['date']);
        $this->assertArrayHasKey('avatar', $comment_data);

        // clean-up data
        wp_delete_post($post_id);
        wp_delete_comment($comment_id);
    }

    /**
     * Calling export_comments() with descending order returns comments in DESC order
     */
    function test_export_comments_descending_order_returns_data()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_title' => 'Article Title'
            )
        );

        $comment_id = $this->factory->comment->create(
            array(
                'comment_post_ID' => $post_id,
                'comment_content' => 'test comment 1',
                'comment_date' => date('Y-m-01 01:i:s')
            )
        );

        $comment_id2 = $this->factory->comment->create(
            array(
                'comment_post_ID' => $post_id,
                'comment_content' => 'test comment 2',
                'comment_date' => date('Y-m-02 02:i:s')
            )
        );

        // set invalid comment order
        update_option('comment_order', 'desc');

        // make request & verify data
        $_GET['articleId'] = $post_id;

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_comments(), true);

        $this->assertArrayHasKey('comments', $data);
        $this->assertEquals(2, count($data['comments']));

        $this->assertEquals($comment_id2, $data['comments'][0]['id']);
        $this->assertEquals('test comment 2', $data['comments'][0]['content']);

        $this->assertEquals($comment_id, $data['comments'][1]['id']);
        $this->assertEquals('test comment 1', $data['comments'][1]['content']);

        // clean-up data
        wp_delete_post($post_id);
        wp_delete_comment($comment_id);
        wp_delete_comment($comment_id2);
    }

    /**
     * Calling export_comments() with invalid order returns comments in ASC order
     */
    function test_export_comments_invalid_order_returns_data()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_title' => 'Article Title'
            )
        );

        $comment_id = $this->factory->comment->create(
            array(
                'comment_post_ID' => $post_id,
                'comment_content' => 'test comment 1'
            )
        );

        $comment_id2 = $this->factory->comment->create(
            array(
                'comment_post_ID' => $post_id,
                'comment_content' => 'test comment 2'
            )
        );

        // set invalid comment order
        update_option('comment_order', 'invalid');

        // make request & verify data
        $_GET['articleId'] = $post_id;

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_comments(), true);

        $this->assertArrayHasKey('comments', $data);
        $this->assertEquals(2, count($data['comments']));

        $this->assertEquals($comment_id, $data['comments'][0]['id']);
        $this->assertEquals('test comment 1', $data['comments'][0]['content']);

        $this->assertEquals($comment_id2, $data['comments'][1]['id']);
        $this->assertEquals('test comment 2', $data['comments'][1]['content']);

        // clean-up data
        wp_delete_post($post_id);
        wp_delete_comment($comment_id);
        wp_delete_comment($comment_id2);
    }
}