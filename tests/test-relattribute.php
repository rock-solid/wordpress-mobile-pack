<?php

class RelAttributeTest extends WP_UnitTestCase {

	function test_page() {
 
		$string = 'Unit tests are sweet';
	 
		$this->assertEquals( 'Unit tests are sweet', $string );
		
		$args = array(
			'numberposts' => 1,
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_password'	 => ''
		);
		
		$pages_query = new WP_Query ( $args );
            
		var_dump($pages_query->have_posts());
		if ($pages_query->have_posts()) {
				
			foreach ($pages_query->posts as $page) {
				echo $page->post_title;
			}
		}
		
	}
}

