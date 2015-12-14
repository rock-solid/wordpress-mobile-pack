<?php

require_once(WMP_PLUGIN_PATH."export/class-export.php");

class ExportCategoriesTest extends WP_UnitTestCase
{

    function setUp(){
        parent::setUp();

        update_option('wmpack_inactive_categories', array());
        update_option('wmpack_ordered_categories', array());
    }

    /**
     * Calling export_categories() with password protected posts returns empty
     */
    function test_export_categories_with_password_protected_posts_returns_empty()
    {
        $post_id = $this->factory->post->create(
            array(
                'post_password' => '123123',
                'post_category' => array(1)
            )
        );

        $post_id2 = $this->factory->post->create(
            array(
                'post_password' => '123123',
                'post_category' => array(2)
            )
        );

        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_categories(), json_encode(array('categories' => array())));

        wp_delete_post($post_id);
        wp_delete_post($post_id2);
    }


    /**
     * Calling export_categories() with draft posts returns empty
     */
    function test_export_categories_with_draft_posts_returns_empty()
    {
        $post_id = $this->factory->post->create(
            array(
                'post_status' => 'draft'
            )
        );

        $export = new WMobilePack_Export();
        $this->assertEquals($export->export_categories(), json_encode(array('categories' => array())));

        wp_delete_post($post_id);
    }

    /**
     * Calling export_categories() with posts from hidden categories returns empty
     */
    function test_export_categories_with_hidden_posts_returns_empty()
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
        $this->assertEquals($export->export_categories(), json_encode(array('categories' => array())));

        wp_delete_post($post_id);
        wp_delete_term($cat_id, 'category');
    }

    /**
     * Calling export_categories() with posts from visible categories returns data
     */
    function test_export_categories_with_visible_posts_returns_data()
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
        $data = json_decode($export->export_categories(), true);
        
        $this->assertArrayHasKey('categories', $data);
        $this->assertEquals(1, count($data['categories']));

        $this->assertEquals($visible_cat_id, $data['categories'][0]['id']);
        $this->assertEquals('Visible Test Category', $data['categories'][0]['name']);
        $this->assertArrayHasKey('link', $data['categories'][0]);
        $this->assertArrayHasKey('name_slug', $data['categories'][0]);

        $this->assertEquals(1, count($data['categories'][0]['articles']));

        $article_data = $data['categories'][0]['articles'][0];
        $this->assertEquals($post_id, $article_data['id']);
        $this->assertEquals('Article Title', $article_data['title']);
        $this->assertArrayHasKey('link', $article_data);
        $this->assertArrayHasKey('description', $article_data);
        $this->assertEquals('', $article_data['content']);

        // check category
        $this->assertEquals($visible_cat_id, $article_data['category_id']);
        $this->assertEquals('Visible Test Category', $article_data['category_name']);

        // check date
        $this->assertEquals($published, $article_data['timestamp']);
        $this->assertEquals(date('D, F d', $published), $article_data['date']);

        wp_delete_post($post_id);
        wp_delete_term($visible_cat_id, 'category');
    }

    /**
     * Calling export_categories() with posts with images returns data
     */
    function test_export_categories_with_posts_with_images_returns_data()
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

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_categories(), true);

        $this->assertArrayHasKey('categories', $data);
        $this->assertEquals(1, count($data['categories']));

        // check image in category
        $this->assertArrayHasKey('image', $data['categories'][0]);
        $this->assertEquals($wp_upload_dir['baseurl'] . '/'.$filename, $data['categories'][0]['image']['src']);
        $this->assertTrue(is_numeric($data['categories'][0]['image']['width']));
        $this->assertTrue(is_numeric($data['categories'][0]['image']['height']));

        $this->assertEquals(1, count($data['categories'][0]['articles']));

        // check image in article
        $article_data = $data['categories'][0]['articles'][0];

        $this->assertArrayHasKey('image', $article_data);
        $this->assertEquals($wp_upload_dir['baseurl'] . '/'.$filename, $article_data['image']['src']);
        $this->assertTrue(is_numeric($article_data['image']['width']));
        $this->assertTrue(is_numeric($article_data['image']['height']));

        wp_delete_post($post_id);
        wp_delete_attachment($attach_id);
    }

    /**
     * Calling export_categories() with posts with author returns data
     */
    function test_export_categories_with_posts_with_author_returns_data()
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
        $data = json_decode($export->export_categories(), true);

        $this->assertArrayHasKey('categories', $data);
        $this->assertEquals(1, count($data['categories']));
        $this->assertEquals(1, count($data['categories'][0]['articles']));
        $this->assertEquals($post_id, $data['categories'][0]['articles'][0]['id']);
        $this->assertEquals('paul', $data['categories'][0]['articles'][0]['author']);

        wp_delete_post($post_id);
        wp_delete_user($user_id);
    }

    /**
     * Calling export_categories() with posts from two categories returns the visible one
     */
    function test_export_categories_with_posts_from_different_categories_returns_visible()
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
        $data = json_decode($export->export_categories(), true);

        $this->assertArrayHasKey('categories', $data);
        $this->assertEquals(1, count($data['categories']));
        $this->assertEquals($visible_cat_id, $data['categories'][0]['id']);
        $this->assertEquals('Visible Test Category', $data['categories'][0]['name']);

        $this->assertEquals(1, count($data['categories'][0]['articles']));

        $article_data = $data['categories'][0]['articles'][0];
        $this->assertEquals($post_id, $article_data['id']);
        $this->assertEquals($visible_cat_id, $article_data['category_id']);
        $this->assertEquals('Visible Test Category', $article_data['category_name']);

        wp_delete_post($post_id);
        wp_delete_term($visible_cat_id, 'category');
        wp_delete_term($hidden_cat_id, 'category');
    }

    /**
     * Calling export_categories() with posts from two categories returns latest
     */
    function test_export_categories_with_posts_from_different_categories_returns_latest()
    {
        $visible_cat_id = $this->factory->category->create(
            array(
                'name' => 'Visible Test Category 1'
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

        $published = strtotime('-2 days');

        $post_id = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published),
                'post_category' => array($hidden_cat_id, $visible_cat_id)
            )
        );

        $post_id2 = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published),
                'post_category' => array($visible_cat_id2)
            )
        );

        update_option('wmpack_inactive_categories', array($hidden_cat_id));
        update_option('wmpack_ordered_categories', array($visible_cat_id2, $visible_cat_id, $hidden_cat_id));

        $export = new WMobilePack_Export();
        $data = json_decode($export->export_categories(), true);

        $this->assertArrayHasKey('categories', $data);
        $this->assertEquals(3, count($data['categories']));

        // check exported categories names
        $categories_ids = array(0, $visible_cat_id2, $visible_cat_id);
        $categories_names = array('Latest', 'Visible Test Category 2', 'Visible Test Category 1');

        foreach ($data['categories'] as $key => $category_data){

            $this->assertEquals($categories_ids[$key], $category_data['id']);
            $this->assertEquals($categories_names[$key], $category_data['name']);

            $this->assertEquals($key == 0 ? 2 : 1, count($category_data['articles']));

            // check if the posts belong only to the visible categories
            $articles_data = $category_data['articles'];
            foreach ($articles_data as $article_data){
                $this->assertTrue(in_array($article_data['category_id'], $categories_ids));
                $this->assertTrue(in_array($article_data['category_name'], $categories_names));
            }
        }


        wp_delete_post($post_id);
        wp_delete_post($post_id2);
        wp_delete_term($visible_cat_id, 'category');
        wp_delete_term($visible_cat_id2, 'category');
        wp_delete_term($hidden_cat_id, 'category');
    }

    /**
     * Calling export_categories() with posts from two categories returns latest with image
     */
    function test_export_categories_with_posts_from_different_categories_returns_latest_with_image()
    {
        $visible_cat_id = $this->factory->category->create(
            array(
                'name' => 'Visible Test Category 1'
            )
        );

        $visible_cat_id2 = $this->factory->category->create(
            array(
                'name' => 'Visible Test Category 2'
            )
        );

        $published = strtotime('-2 days');

        $post_id = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published),
                'post_category' => array($visible_cat_id)
            )
        );

        $post_id2 = $this->factory->post->create(
            array(
                'post_date' => date('Y-m-d H:i:s', $published),
                'post_category' => array($visible_cat_id2)
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

        // make request and check response
        $export = new WMobilePack_Export();
        $data = json_decode($export->export_categories(), true);

        $this->assertArrayHasKey('categories', $data);
        $this->assertEquals(3, count($data['categories']));

        $categories_ids = array(0, $visible_cat_id, $visible_cat_id2);
        $categories_names = array('Latest', 'Visible Test Category 1', 'Visible Test Category 2');

        foreach ($data['categories'] as $key => $category_data){

            $this->assertEquals($categories_ids[$key], $category_data['id']);
            $this->assertEquals($categories_names[$key], $category_data['name']);

            $this->assertEquals($key == 0 ? 2 : 1, count($category_data['articles']));
        }

        // check image on the latest category
        $this->assertArrayHasKey('image', $data['categories'][0]);
        $this->assertEquals($wp_upload_dir['baseurl'] . '/'.$filename, $data['categories'][0]['image']['src']);
        $this->assertTrue(is_numeric($data['categories'][0]['image']['width']));
        $this->assertTrue(is_numeric($data['categories'][0]['image']['height']));

        // clean-up
        wp_delete_post($post_id);
        wp_delete_post($post_id2);
        wp_delete_term($visible_cat_id, 'category');
        wp_delete_term($visible_cat_id2, 'category');
    }
}