<?php

class RedirectFreeTest extends WP_UnitTestCase {

	private static $UserAgent = "Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3";
    
	/**
	 *
	 * Home page shouldn't redirect
	 *
	 */
	function test_home() {
 
		/*if (!WMobilePack::wmp_get_setting('premium_active') || WMobilePack::wmp_get_setting('premium_api_key') == '') {
		
			$request_url = home_url();
			$response = self::make_request($request_url);
			
			$this->assertEquals($response['redirect'], '');				
		}*/
	}
	
	/**
	 *
	 * A single page should redirect to #page/{page_id}
	 *
	 */
	function test_page() {
 
		// check if curl is enabled
		if (!WMobilePack::wmp_get_setting('premium_active') || WMobilePack::wmp_get_setting('premium_api_key') == '') {
		
			$args = array(
    			  'post_status' => 'publish',
				  // 'post_type' => 'page',
				  // 'post_password' => ''
            );
			
			$pages_query = new WP_Query ($args);
			var_dump($pages_query->have_posts());
			
			print_r($pages_query->get_posts($args));
			
			if ($pages_query->have_posts()) {
    				
				echo "aici";
    			foreach ($pages_query->posts as $page) {
					
					echo $page->post_title;
					break;
					$page_id = 1086;
					
					$request_url = home_url().'?page_id='.$page_id;
					$response = self::make_request($request_url);
					
					$this->assertEquals($response['redirect'], home_url()."/#page/".$page_id);
				}
			}
		}
	}
	
	private function make_request($request_url){
		
		// check if curl is enabled
		if (extension_loaded('curl')) {
			
			$response = array(
				'content' => '',
				'redirect' => ''
			);
			
			$send_curl = curl_init();
				
			// set curl options
			curl_setopt($send_curl, CURLOPT_URL, $request_url);
			curl_setopt($send_curl, CURLOPT_USERAGENT, self::$UserAgent);
			curl_setopt($send_curl, CURLOPT_HEADER, true);
			curl_setopt($send_curl, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($send_curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($send_curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($send_curl, CURLOPT_SSL_VERIFYHOST, false);
			$response['content'] = curl_exec($send_curl);
			
			echo "aici";
			print_r($response['content']);
				
			// get request status
			$status = curl_getinfo($send_curl, CURLINFO_HTTP_CODE);
			curl_close($send_curl);
				
			 // line endings is the wonkiest piece of this whole thing
			$out = str_replace("\r", "", $response['content']);
			
			// only look at the headers
			$headers_end = strpos($out, "\n\n");
			if( $headers_end !== false ) { 
				$out = substr($out, 0, $headers_end);
			}   
			
				
			$headers = explode("\n", $out);
			
			print_r($headers);
			foreach ($headers as $header) {
				if( substr($header, 0, 10) == "Location: " ) { 
					$target = substr($header, 10);
					echo "redirect to ".$target;
					$response['redirect'] = $target;
				}   
			}
			
			return $response;
		}
	}
}

