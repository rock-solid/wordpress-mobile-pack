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
     *
     * Calling the get_categories_images method will return array with images
     *
     */
    function test_categories_images_returns_array(){

        $categories_details = array(
            1 => array(
                'icon' => 'icon_path.jpg'
            ),
            2 => array(
                'icon' => 'icon_path2.jpg'
            )
        );
        

        update_option(WMobilePack_Options::$prefix.'categories_details', $categories_details);

        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->disableOriginalConstructor()
            ->setMethods(array('get_uploads_manager'))
            ->getMock();

        // Mock the uploads manager that will check for the file paths
        $uploads_mock = $this->getMockBuilder('Mocked_Uploads')
            ->setMethods(array('get_file_url'))
            ->getMock();

        $uploads_mock->expects($this->exactly(2))
            ->method('get_file_url')
            ->withConsecutive(
                $this->equalTo('icon_path.jpg'),
                $this->equalTo('icon_path2.jpg')
            )
            ->will($this->returnCallback(
                function($parameter) {

                    // only the first icon file will exist
                    if ($parameter == 'icon_path.jpg')
                        return 'http://dummy.mydomain.com/icon_path.jpg';

                    return '';
                }
            ));

        $export_class->expects($this->once())
            ->method('get_uploads_manager')
            ->will($this->returnValue($uploads_mock));

        // Allow the protected method to be accessed
        $method = new ReflectionMethod(
            'WMobilePack_Export', 'get_categories_images'
        );
        $method->setAccessible(true);

        $response = $method->invoke($export_class);
        $expected_data = array(
            1 => array(
                'src' => 'http://dummy.mydomain.com/icon_path.jpg',
                'width' => 500,
                'height' => 500
            )
        );

        $this->assertEquals($response, $expected_data);

        delete_option(WMobilePack_Options::$prefix.'categories_details');
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
    
        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_terms_filter'))
            ->getMock(); 

        $export_class->expects($this->once())
            ->method('get_terms_filter')
            ->will($this->returnValue(array()));    

       
        $this->assertEquals($export_class->export_categories(), json_encode(array('categories' => array(), 'wpmp' => WMP_VERSION)));

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
        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_terms_filter'))
            ->getMock(); 

        $export_class->expects($this->any())
            ->method('get_terms_filter')
            ->will($this->returnValue(array()));  

       
        $this->assertEquals($export_class->export_categories(), json_encode(array('categories' => array(), 'wpmp' => WMP_VERSION)));

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

        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_terms_filter'))
            ->getMock(); 

        $export_class->expects($this->any())
            ->method('get_terms_filter')
            ->will($this->returnValue(array()));  


        update_option('wmpack_inactive_categories', array($cat_id));

        
        $this->assertEquals($export_class->export_categories(), json_encode(array('categories' => array(), 'wpmp' => WMP_VERSION)));

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
        $cat = get_categories();  
        
        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_terms_filter'))
            ->getMock(); 

        $export_class->expects($this->any())
            ->method('get_terms_filter')
            ->will($this->returnValue($cat));  

            
        $data = json_decode($export_class->export_categories(), true);
    
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
        wp_delete_term($visible_cat_id['term_id'], 'category');
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
        wp_update_attachment_metadata( $attach_id, array('width' => 100, 'height' => 100));
       
        $cat = get_categories();

        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_terms_filter'))
            ->getMock(); 

        $export_class->expects($this->any())
            ->method('get_terms_filter')
            ->will($this->returnValue($cat));  

        
        $data = json_decode($export_class->export_categories(), true);

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
        
        $cat = get_categories();  
        
        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_terms_filter'))
            ->getMock(); 

        $export_class->expects($this->any())
            ->method('get_terms_filter')
            ->will($this->returnValue($cat)); 

        
        $data = json_decode($export_class->export_categories(), true);

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

        $cat = get_categories();

        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_terms_filter'))
            ->getMock(); 

        $export_class->expects($this->any())
            ->method('get_terms_filter')
            ->will($this->returnValue($cat));  

        
        $data = json_decode($export_class->export_categories(), true);

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
        wp_delete_term($visible_cat_id['term_id'], 'category');
        wp_delete_term($hidden_cat_id['term_id'], 'category');
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

        $cat = get_categories();

        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_terms_filter'))
            ->getMock(); 

        $export_class->expects($this->any())
            ->method('get_terms_filter')
            ->will($this->returnValue($cat));

        
        $data = json_decode($export_class->export_categories(), true);

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
        wp_delete_term($visible_cat_id['term_id'], 'category');
        wp_delete_term($visible_cat_id2['term_id'], 'category');
        wp_delete_term($hidden_cat_id['term_id'], 'category');
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
        wp_update_attachment_metadata( $attach_id, array('width' => 100, 'height' => 100));

        // make request and check response
        
        $cat = get_categories();

        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_terms_filter'))
            ->getMock(); 

        $export_class->expects($this->any())
            ->method('get_terms_filter')
            ->will($this->returnValue($cat));

        
        $data = json_decode($export_class->export_categories(), true);

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
        wp_delete_term($visible_cat_id['term_id'], 'category');
        wp_delete_term($visible_cat_id2['term_id'], 'category');
    }

    /**
     * Calling export_categories() with post from multiple categories returns data
     */
    function test_export_categories_with_visible_post_multiple_categories_returns_data()
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

        $cat = get_categories();

        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_terms_filter'))
            ->getMock(); 

        $export_class->expects($this->any())
            ->method('get_terms_filter')
            ->will($this->returnValue($cat));

        
        $data = json_decode($export_class->export_categories(), true);


        $this->assertArrayHasKey('categories', $data);
        $this->assertEquals(3, count($data['categories']));

        // check categories names and slugs
        $categories_ids = array(0, $visible_cat_id, $visible_cat_id2);
        $categories_names = array('Latest', 'Visible Test Category', 'Visible Test Category 2');
        $categories_slugs = array('Latest', 'visible-test-category', 'visible-test-category-2');

        foreach ($data['categories'] as $key => $category_data){

            $this->assertEquals($categories_ids[$key], $category_data['id']);
            $this->assertEquals($categories_names[$key], $category_data['name']);
            $this->assertEquals($categories_slugs[$key], $category_data['name_slug']);

            if ($category_data['id'] != 0)
                $this->assertEquals(home_url().'/?cat='.$category_data['id'], $category_data['link']);
        }

        // check the post in each of the 3 categories (Latest and two visible categories)
        for ($i = 0; $i < 3; $i++){

            $this->assertEquals(1, count($data['categories'][$i]['articles']));

            $article_data = $data['categories'][$i]['articles'][0];

            $this->assertEquals($post_id, $article_data['id']);
            $this->assertEquals('Article Title', $article_data['title']);
            $this->assertArrayHasKey('link', $article_data);
            $this->assertArrayHasKey('description', $article_data);
            $this->assertEquals('', $article_data['content']);

            // check date
            $this->assertEquals($published, $article_data['timestamp']);
            $this->assertEquals(date('D, F d', $published), $article_data['date']);

            // check categories array
            $this->assertEquals(array($visible_cat_id, $visible_cat_id2), $article_data['categories']);

            // check category id and name
            if ($i != 0) {

                $this->assertEquals($categories_ids[$i], $article_data['category_id']);
                $this->assertEquals($categories_names[$i], $article_data['category_name']);

            } else {

                // for latest, post will be returned with one of the visible categories
                $this->assertTrue(in_array($article_data['category_id'], array($visible_cat_id, $visible_cat_id2)));
                $this->assertTrue(in_array($article_data['category_name'], array('Visible Test Category', 'Visible Test Category 2')));

            }
        }

        wp_delete_post($post_id);
        wp_delete_term($visible_cat_id['term_id'], 'category');
        wp_delete_term($visible_cat_id2['term_id'], 'category');
        wp_delete_term($hidden_cat_id['term_id'], 'category');

        update_option('wmpack_inactive_categories', array());
    }

    /**
     *
     * Calling the export_categories endpoint with custom images will return data
     *
     */
    function test_export_categories_with_custom_images_returns_data(){

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
                'post_category' => array($visible_cat_id, $visible_cat_id2)
            )
        );

        $categories_images = array(
            $visible_cat_id => array(
                'src' => 'http://dummy.appticles.com/icon_path'.$visible_cat_id.'.jpg',
                'width' => 500,
                'height' => 500
            ),
            $visible_cat_id2 => array(
                'src' => 'http://dummy.appticles.com/icon_path'.$visible_cat_id2.'.jpg',
                'width' => 500,
                'height' => 500
            )
        );
        
        $cat = get_categories();
        
        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_categories_images', 'get_terms_filter'))
            ->getMock();

        $export_class->expects($this->once())
            ->method('get_categories_images')
            ->will($this->returnValue($categories_images));


        $export_class->expects($this->once())
            ->method('get_terms_filter')
            ->will($this->returnValue($cat));

        
        $data = json_decode($export_class->export_categories(), true);
        

        foreach ($data['categories'] as $key => $category_data){

            // skip over the Latest category
            if ($category_data['id'] != 0){
                // verify that the category image was set to the custom image
                $this->assertEquals($category_data['image'], $categories_images[ $category_data['id'] ]);
            }
        }

        // clean-up
        wp_delete_post($post_id);
        wp_delete_term($visible_cat_id['term_id'], 'category');
        wp_delete_term($visible_cat_id2['term_id'], 'category');
    }

    function test_export_categories_returns_correct_if_page_and_rows_are_given () 
    {

       $_GET["page"] = 1;
       $_GET["rows"] = 2;

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

        $cat = get_categories('hide_empty=0');
        unset($cat[0]);
        $cat = array_values($cat);             
    
        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_terms_filter'))
            ->getMock(); 

        $export_class->expects($this->any())
            ->method('get_terms_filter')
            ->will($this->returnValue($cat));

        $test = json_decode ($export_class->export_categories(),true); 
        $this->assertEquals(2, $test['rows']);
        $this->assertEquals(1, $test['page']);
        $this->assertEquals(2,count ($test['categories']));    
        $this->assertEquals('Latest', $test['categories'][0]['name']);
        $this->assertEquals('Visible Test Category 1', $test['categories'][1]['name']);
         
        
        wp_delete_term($visible_cat_id['term_id'], 'category');
        wp_delete_term($visible_cat_id2['term_id'], 'category');
        
    }


    function test_export_categories_returns_empty_and_page_and_rows_as_parameters_in_json_if_page_too_high () 
    {
        $_GET["page"] = 5465;
        $_GET["rows"] = 5;

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

        $visible_cat_id3 = $this->factory->category->create(
            array(
                'name' => 'Visible Test Category 3'
            )
        );
        $cat = get_categories('hide_empty=0');
        unset($cat[0]);
        $cat = array_values($cat);
        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_terms_filter'))
            ->getMock(); 

        $export_class->expects($this->any())
            ->method('get_terms_filter')
            ->will($this->returnValue($cat));

        $this->assertEquals(
            '{"categories":[],"page":"5465","rows":"5","wpmp":"2.2.5"}',
            $export_class->export_categories()
            );

        wp_delete_term($visible_cat_id['term_id'], 'category');
        wp_delete_term($visible_cat_id2['term_id'], 'category');
        wp_delete_term($visible_cat_id3['term_id'], 'category');
    

        }

    function test_if_withArticles_set_different_than_1_returns_categories_with_no_articles () 
    {
        $_GET['withArticles'] = 23;

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

        $cat = get_categories();

        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->setMethods(array('get_terms_filter'))
            ->getMock(); 

        $export_class->expects($this->any())
            ->method('get_terms_filter')
            ->will($this->returnValue($cat));

        $test = json_decode($export_class->export_categories(), true);

        foreach( $test['categories'] as $test_one ){
            $this->assertArrayNotHasKey('articles', $test_one);
        }
        

        wp_delete_post($post_id);
        wp_delete_post($post_id2);
        wp_delete_term($visible_cat_id['term_id'], 'category');
        wp_delete_term($visible_cat_id2['term_id'], 'category');
         
    }    
  
}
