<?php

require_once PWA_PLUGIN_PATH.'export/class-export.php';

class ExportCategoryTest extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();

        update_option('wmpack_inactive_categories', array());
        update_option('wmpack_ordered_categories', array());
        update_option('wmpack_categories_details', array());
    }

    public function test_no_category_id_returns_error()
    {
        $export = new WMobilePack_Export();
        $this->assertEquals(
            '{"error":"Invalid category id"}',
            $export->export_category()
        );
    }

    public function test_invalid_category_id_returns_error()
    {
        $export = new WMobilePack_Export();
        $_GET["categoryId"] = 'a';
        $this->assertEquals(
            '{"error":"Invalid category id"}',
            $export->export_category()
        );
    }

    public function test_empty_category_returns_error()
    {
        $export = new WMobilePack_Export();
        $_GET["categoryId"] = '2';
        $this->assertEquals(
            '{"error":"Category does not exist"}',
            $export->export_category()
        );
    }

    public function test_if_returns_category()
    {
        $export = new WMobilePack_Export();
        $category_id = $this->factory->category->create(
            array(
                'name' => 'whatever'

            )
        );

        $_GET["categoryId"] = $category_id;
        $data = json_decode($export->export_category(), true);
        $this->assertArrayHasKey('category', $data);
        $this->assertEquals($category_id, $data['category']['id']);
        $this->assertEquals('whatever', $data['category']['name']);


        wp_delete_term($category_id, 'category');
    }




    public function test_if_categories_details_is_empty_returns_no_image()
    {
        $export = new WMobilePack_Export();
        $category_no_pic_id = $this->factory->category->create(
            array(
                'name' => 'category no pic'
            )
        );
        $_GET['categoryId'] = $category_no_pic_id;
        $data = json_decode($export->export_category(), true);
        $this->assertEquals("", $data['category']['image']);

        wp_delete_term($category_no_pic_id, 'category');

    }

    public function test_if_inactive_category_id_returns_error ()
    {

        $inactive_category_id = $this->factory->category->create(
            array(
                'name' => 'inactive category'
            )
        );


        update_option('wmpack_inactive_categories', array($inactive_category_id));

        $export = new WMobilePack_Export();
        $_GET['categoryId'] = $inactive_category_id;
        $this->assertEquals(
            '{"error":"Category does not exist"}',
            $export->export_category()
        );

        wp_delete_term($inactive_category_id, 'category');
    }



    public function test_if_category_with_custom_image_returns_data ()
    {
        $pic_cat_id = $this->factory->category->create(
            array(
                'name' => 'pic cat'
            )
        );

        $categories_images = array(
            $pic_cat_id => array(
                'icon' => 'http://dummy.appticles.com/icon_path'.$pic_cat_id.'.jpg',
            )
        );

        $expected_image =array(
            'src' => 'http://dummy.appticles.com/icon_path'.$pic_cat_id.'.jpg',
            'width' => WMobilePack_Uploads::$allowed_files['category_icon']['max_width'],
            'height' => WMobilePack_Uploads::$allowed_files['category_icon']['max_height']
        );

        update_option('wmpack_categories_details', $categories_images);

        $export_class = $this->getMockBuilder('WMobilePack_Export')
            ->disableOriginalConstructor()
            ->setMethods(array('get_uploads_manager'))
            ->getMock();


        $uploads_mock = $this->getMockBuilder('Mocked_Uploads')
            ->setMethods(array('get_file_url'))
            ->getMock();

        $uploads_mock->expects($this->once())
            ->method('get_file_url')
            ->with(
                $this->equalTo('http://dummy.appticles.com/icon_path'.$pic_cat_id.'.jpg')
            )
            ->will($this->returnValue($categories_images[$pic_cat_id]['icon'])) ;


        $export_class->expects($this->once())
            ->method('get_uploads_manager')
            ->will($this->returnValue($uploads_mock));


        $export = new WMobilePack_Export();
        $_GET['categoryId'] = $pic_cat_id;

        $data = json_decode($export_class->export_category(), true);
        $this->assertEquals($expected_image, $data['category']['image']);

        wp_delete_term($pic_cat_id, 'category');


    }

    public function test_if_categoryId_is_0_export_category_returns_latest_category ()
    {
        $export = new WMobilePack_Export();
        $expected = array(
            'id' => 0,
            'name' => 'Latest',
            'name_slug' => 'Latest',
            'image' => ""
        );

        $_GET["categoryId"] = 0;
        $this->assertEquals(
            '{"category":' . json_encode($expected) . '}' ,
            $export->export_category()
        );

    }




}
