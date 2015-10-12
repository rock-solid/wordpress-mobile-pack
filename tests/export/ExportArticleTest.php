<?php

require_once(WMP_PLUGIN_PATH."export/class-export.php");

class ExportArticleTest extends WP_UnitTestCase
{

    function setUp(){
        parent::setUp();

        update_option('wmpack_inactive_categories', serialize(array()));
    }

    /**
     * Calling export_article() without a post id returns error
     */
    function test_export_article_without_id_returns_error()
    {

        $export = new WMP_Export();
        $this->assertEquals($export->export_article(), json_encode(array('error' => 'Invalid post id')));
    }

    /**
     * Calling export_article() for a page returns empty json
     */
    function test_export_article_does_not_retrieve_page()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_type' => 'page'
            )
        );

        $_GET['articleId'] = $post_id;

        $export = new WMP_Export();
        $this->assertEquals($export->export_article(), json_encode(array('article' => array())));

        wp_delete_post($post_id);
    }

    /**
     * Calling export_article() with a password protected post returns empty json
     */
    function test_export_article_with_password_protected_post_returns_empty()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_password' => '123123'
            )
        );

        $_GET['articleId'] = $post_id;

        $export = new WMP_Export();
        $this->assertEquals($export->export_article(), json_encode(array('article' => array())));

        wp_delete_post($post_id);
    }

    /**
     * Calling export_article() with a draft post returns empty json
     */
    function test_export_article_with_draft_post_returns_empty()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_status' => 'draft'
            )
        );

        $_GET['articleId'] = $post_id;

        $export = new WMP_Export();
        $this->assertEquals($export->export_article(), json_encode(array('article' => array())));

        wp_delete_post($post_id);
    }

    /**
     * Calling export_article() with a post from a hidden category returns empty json
     */
    function test_export_article_with_post_from_hidden_category_returns_empty()
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

        $_GET['articleId'] = $post_id;

        update_option('wmpack_inactive_categories', serialize(array($cat_id)));

        $export = new WMP_Export();
        $this->assertEquals($export->export_article(), json_encode(array('article' => array())));

        wp_delete_post($post_id);
        wp_delete_term($cat_id, 'category');
    }

    /**
     * Calling export_article() with a post from a visible category returns data json
     */
    function test_export_article_with_visible_category_returns_data()
    {

        $cat_id = $this->factory->category->create(
            array(
                'name' => 'Visible Category',

            )
        );

        $post_id = $this->factory->post->create(
            array(
                'post_category' => array($cat_id)
            )
        );

        $_GET['articleId'] = $post_id;

        $export = new WMP_Export();

        $data = json_decode($export->export_article(), true);

        $this->assertArrayHasKey('article', $data);
        $this->assertEquals($cat_id, $data['article']['category_id']);
        $this->assertEquals('Visible Category', $data['article']['category_name']);

        wp_delete_post($post_id);
        wp_delete_term($cat_id, 'category');
    }

    /**
     * Calling export_article() with a post with closed comments returns data json
     */
    function test_export_article_with_closed_comments_returns_data()
    {

        // mock post with a comment
        $post_id = $this->factory->post->create(
            array(
                'comment_status' => 'closed'
            )
        );

        $comment_id = $this->factory->comment->create(
            array(
                'comment_post_ID' => $post_id
            )
        );

        // mock different wp options
        update_option('show_avatars', 0);
        update_option('require_name_email', 0);

        // make request & verify data
        $_GET['articleId'] = $post_id;

        $export = new WMP_Export();
        $data = json_decode($export->export_article(), true);

        $this->assertArrayHasKey('article', $data);
        $this->assertEquals('closed', $data['article']['comment_status']);
        $this->assertEquals(1, $data['article']['no_comments']);
        $this->assertEquals(0, $data['article']['show_avatars']);
        $this->assertEquals(0, $data['article']['require_name_email']);

        // clean-up data
        wp_delete_post($post_id);
        wp_delete_comment($comment_id);
    }

    /**
     * Calling export_article() with a post with disabled comments returns data json
     */
    function test_export_article_with_disabled_comments_returns_data()
    {

        // mock post with a comment
        $post_id = $this->factory->post->create(
            array(
                'comment_status' => 'closed'
            )
        );

        // make request & verify data
        $_GET['articleId'] = $post_id;

        $export = new WMP_Export();
        $data = json_decode($export->export_article(), true);

        $this->assertArrayHasKey('article', $data);
        $this->assertEquals('disabled', $data['article']['comment_status']);
        $this->assertEquals(0, $data['article']['no_comments']);

        // clean-up data
        wp_delete_post($post_id);
    }

    /**
     * Calling export_article() with a post that has an image returns data json
     */
    function test_export_article_with_image_returns_data()
    {

        $published = time();

        // mock post
        $post_id = $this->factory->post->create(
            array(
                'post_title' => 'Article Title',
                'post_date' => date('Y-m-d H:i:s', $published)
            )
        );

        // mock an attachment image and link it to the post
        $filename = "test_image.jpg";
        $wp_upload_dir = wp_upload_dir();

        $attachment = array(
            'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
            'post_mime_type' => 'image/jpeg',
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            'post_status'    => 'inherit'
        );

        $attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
        add_post_meta( $post_id, '_thumbnail_id', $attach_id, true );

        // make request and verify response
        $_GET['articleId'] = $post_id;

        $export = new WMP_Export();
        $data = json_decode($export->export_article(), true);

        $this->assertArrayHasKey('article', $data);

        $this->assertEquals($post_id, $data['article']['id']);
        $this->assertEquals('Article Title', $data['article']['title']);
        $this->assertArrayHasKey('link', $data['article']);
        $this->assertArrayHasKey('description', $data['article']);
        $this->assertArrayHasKey('content', $data['article']);

        // check date
        $this->assertEquals($published, $data['article']['timestamp']);
        $this->assertEquals(date('D, F d', $published), $data['article']['date']);

        // check image
        $this->assertArrayHasKey('image', $data['article']);
        $this->assertEquals($wp_upload_dir['baseurl'] . '/'.$filename, $data['article']['image']['src']);
        $this->assertTrue(is_numeric($data['article']['image']['width']));
        $this->assertTrue(is_numeric($data['article']['image']['height']));

        // clean-up
        wp_delete_post($post_id);
        wp_delete_attachment($attach_id);
    }

    /**
     * Calling export_article() with a post that has a valid author returns data json
     */
    function test_export_article_with_author_returns_data()
    {

        $user_id = $this->factory->user->create(
            array(
                'user_login' => 'pauluser',
                'display_name' => 'paul',
                'role' => 'author',
                'first_name' => 'paul',
                'last_name' => 'norris'
            )
        );

        // mock post with a comment
        $post_id = $this->factory->post->create(
            array(
                'post_author' => $user_id
            )
        );

        // make request & verify data
        $_GET['articleId'] = $post_id;

        $export = new WMP_Export();
        $data = json_decode($export->export_article(), true);

        $this->assertArrayHasKey('article', $data);
        $this->assertEquals('paul', $data['article']['author']);

        // clean-up data
        wp_delete_post($post_id);
        wp_delete_user($user_id);
    }
}