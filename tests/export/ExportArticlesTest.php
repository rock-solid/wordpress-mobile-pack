<?php

require_once(WMP_PLUGIN_PATH."export/class-export.php");

class ExportArticlesTest extends WP_UnitTestCase
{

    function setUp(){
        parent::setUp();

        update_option('wmpack_inactive_categories', array());
    }

    /**
     * Calling export_articles() with password protected posts returns empty
     */
    function test_export_articles_with_password_protected_posts_returns_empty()
    {
        $post_id = $this->factory->post->create(
            array(
                'post_password' => '123123'
            )
        );

        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_articles(), json_encode(array('articles' => array())));

        wp_delete_post($post_id);
    }

    /**
     * Calling export_articles() with draft posts returns empty
     */
    function test_export_articles_with_draft_posts_returns_empty()
    {
        $post_id = $this->factory->post->create(
            array(
                'post_status' => 'draft'
            )
        );

        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_articles(), json_encode(array('articles' => array())));

        wp_delete_post($post_id);
    }

    /**
     * Calling export_articles() with posts from hidden categories returns empty
     */
    function test_export_articles_with_hidden_posts_returns_empty()
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

        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_articles(), json_encode(array('articles' => array())));

        wp_delete_post($post_id);
        wp_delete_term($cat_id, 'category');
    }

    /**
     * Calling export_articles() with posts from visible categories returns data
     */
    function test_export_articles_with_visible_posts_returns_data()
    {
        $published = strtotime('-2 days');

        $visible_cat_id = $this->factory->category->create(
            array(
                'name' => 'Visible Test Category'
            )
        );

        $post_id = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published),
                'post_title' => 'Article Title',
                'post_content' => 'test content',
                'post_category' => array($visible_cat_id)
            )
        );

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_articles(), true);

        $this->assertArrayHasKey('articles', $data);
        $this->assertEquals(1, count($data['articles']));

        $this->assertEquals($post_id, $data['articles'][0]['id']);
        $this->assertEquals('Article Title', $data['articles'][0]['title']);
        $this->assertArrayHasKey('link', $data['articles'][0]);
        $this->assertArrayHasKey('description', $data['articles'][0]);
        $this->assertEquals('', $data['articles'][0]['content']);

        // check category
        $this->assertEquals($visible_cat_id, $data['articles'][0]['category_id']);
        $this->assertEquals('Visible Test Category', $data['articles'][0]['category_name']);
        $this->assertEquals(array($visible_cat_id), $data['articles'][0]['categories']);

        // check date
        $this->assertEquals($published, $data['articles'][0]['timestamp']);
        $this->assertEquals(date('D, F d', $published), $data['articles'][0]['date']);

        wp_delete_post($post_id);
        wp_delete_term($visible_cat_id, 'category');
    }


    /**
     * Calling export_articles() with posts from multiple categories returns data
     */
    function test_export_articles_with_visible_posts_multiple_categories_returns_data()
    {
        $published = strtotime('-2 days');

        $visible_cat_id = $this->factory->category->create(
            array(
                'name' => 'Visible Test Category'
            )
        );

        $visible_cat_id2 = $this->factory->category->create(
            array(
                'name' => 'Visible Test Category 2'
            )
        );

        $hidden_cat_id = $this->factory->category->create(
            array(
                'name' => 'Hidden Test Category'
            )
        );

        $post_id = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published),
                'post_title' => 'Article Title',
                'post_content' => 'test content',
                'post_category' => array($visible_cat_id, $visible_cat_id2, $hidden_cat_id)
            )
        );

        update_option('wmpack_inactive_categories', array($hidden_cat_id));

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_articles(), true);

        $this->assertArrayHasKey('articles', $data);
        $this->assertEquals(1, count($data['articles']));

        $this->assertEquals($post_id, $data['articles'][0]['id']);
        $this->assertEquals('Article Title', $data['articles'][0]['title']);
        $this->assertArrayHasKey('link', $data['articles'][0]);
        $this->assertArrayHasKey('description', $data['articles'][0]);
        $this->assertEquals('', $data['articles'][0]['content']);

        // post will be returned with one of the visible categories
        $this->assertTrue(in_array($data['articles'][0]['category_id'], array($visible_cat_id, $visible_cat_id2)));
        $this->assertTrue(in_array($data['articles'][0]['category_name'], array('Visible Test Category', 'Visible Test Category 2')));

        $this->assertEquals(array($visible_cat_id, $visible_cat_id2), $data['articles'][0]['categories']);

        // check date
        $this->assertEquals($published, $data['articles'][0]['timestamp']);
        $this->assertEquals(date('D, F d', $published), $data['articles'][0]['date']);

        wp_delete_post($post_id);
        wp_delete_term($visible_cat_id, 'category');
        wp_delete_term($visible_cat_id2, 'category');
        wp_delete_term($hidden_cat_id, 'category');

        update_option('wmpack_inactive_categories', array());
    }


    /**
     * Calling export_articles() with posts with images returns data
     */
    function test_export_articles_with_posts_with_images_returns_data()
    {
        $published = strtotime('-2 days');

        $post_id = $this->factory->post->create(
            array(
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
        wp_update_attachment_metadata($attach_id, array('width' => 100, 'height' => 100));

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_articles(), true);

        $this->assertArrayHasKey('articles', $data);
        $this->assertEquals(1, count($data['articles']));
        $this->assertEquals($post_id, $data['articles'][0]['id']);

        // check image
        $this->assertArrayHasKey('image', $data['articles'][0]);
        $this->assertEquals($wp_upload_dir['baseurl'] . '/'.$filename, $data['articles'][0]['image']['src']);
        $this->assertTrue(is_numeric($data['articles'][0]['image']['width']));
        $this->assertTrue(is_numeric($data['articles'][0]['image']['height']));

        wp_delete_post($post_id);
        wp_delete_attachment($attach_id);
    }

    /**
     * Calling export_articles() with posts with author returns data
     */
    function test_export_articles_with_posts_with_author_returns_data()
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

        $published = strtotime('-2 days');

        $post_id = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published),
                'post_author' => $user_id
            )
        );

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_articles(), true);

        $this->assertArrayHasKey('articles', $data);
        $this->assertEquals(1, count($data['articles']));
        $this->assertEquals($post_id, $data['articles'][0]['id']);
        $this->assertEquals('paul', $data['articles'][0]['author']);

        wp_delete_post($post_id);
        wp_delete_user($user_id);
    }

    /**
     * Calling export_articles() with posts from two categories returns the visible one
     */
    function test_export_articles_with_posts_from_different_categories_returns_visible()
    {
        $visible_cat_id = $this->factory->category->create(
            array(
                'name' => 'Visible Test Category'
            )
        );

        $hidden_cat_id = $this->factory->category->create(
            array(
                'name' => 'Hidden Test Category'
            )
        );

        $published = strtotime('-2 days');

        $post_id = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published),
                'post_category' => array($visible_cat_id, $hidden_cat_id)
            )
        );

        update_option('wmpack_inactive_categories', array($hidden_cat_id));

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_articles(), true);

        $this->assertArrayHasKey('articles', $data);
        $this->assertEquals(1, count($data['articles']));
        $this->assertEquals($post_id, $data['articles'][0]['id']);
        $this->assertEquals($visible_cat_id, $data['articles'][0]['category_id']);
        $this->assertEquals('Visible Test Category', $data['articles'][0]['category_name']);

        wp_delete_post($post_id);
        wp_delete_term($visible_cat_id, 'category');
        wp_delete_term($hidden_cat_id, 'category');
    }

    /**
     * Calling export_articles() with posts from a single category returns data
     */
    function test_export_articles_with_posts_from_category_returns_data()
    {
        $cat_id = $this->factory->category->create(
            array(
                'name' => 'Visible Test Category'
            )
        );

        $cat_id2 = $this->factory->category->create(
            array(
                'name' => 'Second Visible Test Category'
            )
        );

        $published = strtotime('-2 days');

        $post_id = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published),
                'post_category' => array($cat_id)
            )
        );

        $post_id2 = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published),
                'post_category' => array($cat_id2)
            )
        );

        $_GET['categoryId'] = $cat_id;

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_articles(), true);

        $this->assertArrayHasKey('articles', $data);
        $this->assertEquals(1, count($data['articles']));
        $this->assertEquals($post_id, $data['articles'][0]['id']);
        $this->assertEquals($cat_id, $data['articles'][0]['category_id']);
        $this->assertEquals('Visible Test Category', $data['articles'][0]['category_name']);

        wp_delete_post($post_id);
        wp_delete_post($post_id2);
        wp_delete_term($cat_id, 'category');
        wp_delete_term($cat_id2, 'category');
    }


    /**
     * Calling export_articles() with posts and lastTimestamp is ordering articles by date
     */
    function test_export_articles_ordered_by_date_last_timestamp()
    {
        $published = strtotime('-2 days');
        $published_new = strtotime('-1 day');

        $visible_cat_id = $this->factory->category->create(
            array(
                'name' => 'Test Category'
            )
        );

        $visible_cat_id2 = $this->factory->category->create(
            array(
                'name' => 'Test Category 2'
            )
        );

        $post_id = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published),
                'post_title' => 'Article Old',
                'post_content' => 'test content',
                'post_category' => array($visible_cat_id)
            )
        );

        $post_id2 = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published_new),
                'post_title' => 'Article New',
                'post_content' => 'test content',
                'post_category' => array($visible_cat_id, $visible_cat_id2)
            )
        );

        $_GET['lastTimestamp'] = $published_new;

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_articles(), true);

        $this->assertArrayHasKey('articles', $data);
        $this->assertEquals(1, count($data['articles']));

        $this->assertEquals($post_id, $data['articles'][0]['id']);
        $this->assertEquals('Article Old', $data['articles'][0]['title']);
        $this->assertArrayHasKey('link', $data['articles'][0]);
        $this->assertArrayHasKey('description', $data['articles'][0]);
        $this->assertEquals('', $data['articles'][0]['content']);

        // check category
        $this->assertTrue(in_array($data['articles'][0]['category_id'], array($visible_cat_id, $visible_cat_id2)));
        $this->assertTrue(in_array($data['articles'][0]['category_name'], array('Test Category', 'Test Category 2')));

        // check date
        $this->assertEquals($published, $data['articles'][0]['timestamp']);
        $this->assertEquals(date('D, F d', $published), $data['articles'][0]['date']);

        wp_delete_post($post_id);
        wp_delete_post($post_id2);
        wp_delete_term($visible_cat_id, 'category');
        wp_delete_term($visible_cat_id2, 'category');
    }

    /**
     * Calling export_articles() with posts with manual excerpts returns data
     */
    function test_export_articles_with_manual_excerpt_returns_data()
    {
        $published = strtotime('-2 days');

        $visible_cat_id = $this->factory->category->create(
            array(
                'name' => 'Visible Test Category'
            )
        );

        $post_id = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published),
                'post_title' => 'Article Title',
                'post_excerpt' => '<p>This is the <strong>post</strong> description right here</p>',
                'post_content' => '<p>This is the <strong>post</strong> content right here</p>',
                'post_category' => array($visible_cat_id)
            )
        );

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_articles(), true);

        $this->assertArrayHasKey('articles', $data);
        $this->assertEquals(1, count($data['articles']));

        $this->assertEquals($post_id, $data['articles'][0]['id']);
        $this->assertEquals("<p>This is the <strong>post</strong> description right here</p>\n", $data['articles'][0]['description']);

        wp_delete_post($post_id);
        wp_delete_term($visible_cat_id, 'category');
    }

    /**
     * Calling export_articles() with posts that don't have excerpts returns data
     */
    function test_export_articles_with_automated_excerpt_returns_data()
    {
        $published = strtotime('-2 days');

        $visible_cat_id = $this->factory->category->create(
            array(
                'name' => 'Visible Test Category'
            )
        );

        $post_id = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published),
                'post_title' => 'Article Title',
                'post_excerpt' => '',
                'post_content' => '<p>This is the <strong>post</strong> content right here</p>',
                'post_category' => array($visible_cat_id)
            )
        );

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_articles(), true);

        $this->assertArrayHasKey('articles', $data);
        $this->assertEquals(1, count($data['articles']));

        $this->assertEquals($post_id, $data['articles'][0]['id']);
        $this->assertEquals("<p>This is the post content right here</p>\n", $data['articles'][0]['description']);

        wp_delete_post($post_id);
        wp_delete_term($visible_cat_id, 'category');
    }
}