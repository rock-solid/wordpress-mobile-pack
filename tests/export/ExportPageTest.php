<?php

require_once(WMP_PLUGIN_PATH."export/class-export.php");

class ExportPageTest extends WP_UnitTestCase
{

    /**
     * Calling export_page() without a post id returns error
     */
    function test_export_page_without_id_returns_error()
    {
        $export = new WMP_Export();
        $this->assertEquals($export->export_page(), json_encode(array('error' => 'Invalid post id')));
    }

    /**
     * Calling export_page() for a post returns empty json
     */
    function test_export_page_does_not_retrieve_post()
    {

        $post_id = $this->factory->post->create();

        $_GET['pageId'] = $post_id;

        $export = new WMP_Export();
        $this->assertEquals($export->export_page(), json_encode(array('page' => array())));

        wp_delete_post($post_id);
    }

    /**
     * Calling export_page() with a password protected post returns empty json
     */
    function test_export_page_with_password_protected_post_returns_empty()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_password' => '123123',
                'post_type' => 'page'
            )
        );

        $_GET['pageId'] = $post_id;

        $export = new WMP_Export();
        $this->assertEquals($export->export_page(), json_encode(array('page' => array())));

        wp_delete_post($post_id);
    }

    /**
     * Calling export_page() with a draft post returns empty json
     */
    function test_export_page_with_draft_post_returns_empty()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_status' => 'draft',
                'post_type' => 'page'
            )
        );

        $_GET['pageId'] = $post_id;

        $export = new WMP_Export();
        $this->assertEquals($export->export_page(), json_encode(array('page' => array())));

        wp_delete_post($post_id);
    }

    /**
     * Calling export_page() with a hidden page returns empty json
     */
    function test_export_page_hidden_returns_empty()
    {

        $post_id = $this->factory->post->create(
            array(
                'post_type' => 'page'
            )
        );

        $_GET['pageId'] = $post_id;

        update_option('wmpack_inactive_pages', array($post_id));

        $export = new WMP_Export();
        $this->assertEquals($export->export_page(), json_encode(array('page' => array())));

        wp_delete_post($post_id);
    }

    /**
     * Calling export_page() with a visible page that has an image returns data json
     */
    function test_export_page_with_image_returns_data()
    {

        // mock post
        $post_id = $this->factory->post->create(
            array(
                'post_title' => 'Page Title',
                'post_type' => 'page'
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
        $_GET['pageId'] = $post_id;

        $export = new WMP_Export();
        $data = json_decode($export->export_page(), true);

        $this->assertArrayHasKey('page', $data);

        $this->assertEquals($post_id, $data['page']['id']);
        $this->assertEquals('Page Title', $data['page']['title']);
        $this->assertArrayHasKey('link', $data['page']);
        $this->assertArrayHasKey('content', $data['page']);

        // check image
        $this->assertArrayHasKey('image', $data['page']);
        $this->assertEquals($wp_upload_dir['baseurl'] . '/'.$filename, $data['page']['image']['src']);
        $this->assertTrue(is_numeric($data['page']['image']['width']));
        $this->assertTrue(is_numeric($data['page']['image']['height']));

        // clean-up
        wp_delete_post($post_id);
        wp_delete_attachment($attach_id);
    }

    /**
     * Calling export_page() with a page that has been edited from wpmp returns data
     */
    function test_export_page_with_custom_content()
    {
        // mock post
        $post_id = $this->factory->post->create(
            array(
                'post_title' => 'Page Title',
                'post_type' => 'page',
                'post_content' => 'This is the original content'
            )
        );

        update_option('wmpack_page_'.$post_id, 'This is the modified content');

        // make request and verify response
        $_GET['pageId'] = $post_id;

        $export = new WMP_Export();
        $data = json_decode($export->export_page(), true);

        $this->assertTrue(strpos($data['page']['content'], 'This is the modified content') !== false);

        // clean-up
        wp_delete_post($post_id);

    }
}