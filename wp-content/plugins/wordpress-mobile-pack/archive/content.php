<?php

require_once("../../../../wp-config.php");
require_once '../libs/htmlpurifier-4.6.0-lite/library/HTMLPurifier.auto.php';
/* -------------------------------------------------------------------------*/
/* Export class with different export 										*/
/* methods for categories, articles and comments							*/
/* -------------------------------------------------------------------------*/

  class Export {
	  
    /* ----------------------------------*/
    /* Attributes						 */
    /* ----------------------------------*/
   	
	private $purifier;
   	
    /* ----------------------------------*/
    /* Methods							 */
    /* ----------------------------------*/

	public function __construct() {
		
		// set HTML Purifier
		//$config = HTMLPurifier_Config::createDefault();
		
		//$config->set('HTML.DefinitionID', 'CONTENT-VALIDATION');
		//$config->set('HTML.DefinitionRev', 1);
		//
		//$config->set('Core.Encoding', 'UTF-8'); 									
		//$config->set('HTML.AllowedElements', 'p','h1','h2','h3','h4','h5','h6','b','strong','em','i','u','sup','ul','ol','li','img','a','br','blockquote','cite','iframe','table','td','tr','th');
		//$config->set('HTML.AllowedAttributes', 'href','src');
		
		//$config->set('URI.SafeIframeRegexp','%^http://(youtube.com|youtube-nocookie.com|vimeo.com|dailymotion.com|soundcloud.com|fast.wistia.net|fast.wistia.com|wi.st)%');
		//$config->set('URI.SafeIframe',1);
		//IframeHostWhitelist -> array();
		$config = HTMLPurifier_Config::createDefault();
		$config->set('HTML.Allowed', 'p,a[href|rel|target|title],img[src],span[style],strong,em,ul,ol,li');
		$purifier = new HTMLPurifier($config);
		
		var_dump($config);exit();
		$this->purifier  = new HTMLPurifier($config); 
		
	}

   
	/**
    * 
    *  - exportCategories method used for the export of every category with a number of articles for each
	*  - this metod returns a JSON with the specific content
	*  - ex : 
	*	{
	*		"categories": [
	*			{
	*				"id": 0,
	*				"order": 1,
	*				"name": "Latest",
	*				"image": {
	*					"src": "http://cdn-kits.appticles.com/others/category-default.jpg",
	*					"width": 480,
	*					"height": 270,
	*					"caption": ""
	*				},
	*				"articles": [
	*					{
	*						"id": "5362972281f58370a69686b7",
	*						"title": "Digital and Social Media Journalist",
	*						"timestamp": 1398969000,
	*						"has_facebook_id": 0,
	*						"author": "Accounts and Legal",
	*						"date": "Thu, May 01, 2014 06:30 PM",
	*						"link": "http://www.journalism.co.uk/media-jobs/digital-and-social-media-journalist/s75/a556628/",
	*						"image": "",
	*						"description": "<p>Accounts and Legal is venture capital backed multi-disciplinary advisory firm on a mission to help small businesses grow. </p><p> We are seeking to take on a journalist to create and edit exciting articles as part of our broader social media and SEO marketing campaign. </p><p> Responsibilities will include interviewing local business owners, researching niche markets and identifying topics pertinent to the small business community. </p><p> A keen interest in business, entrepreneurial spirit and creative flare are preferable. </p>",
	*						"descriptionLength": 538,
	*						"content": [],
	*						"category_id": 3,
	*						"category_name": "Jobs"
	*					}
	*				]
	*			}
	*		]
	*	}
    *
	* - The "Latest" category will be formed from all the visible categories and articles	
    */
	public function exportCategories($limit = 7, $description_length = 200) {
		
		
		$arrCategories = array();
		
		// get categories
		$categories = get_categories();
		
		
		if(count($categories) > 0) {
			 
			 // set latest category with de articles
			 $latest_args = array(
							'numberposts'      => $limit
							);
			
			 $latests_posts = get_posts( $latest_args );
			 
			 if(count($latests_posts) > 0) {
				 
				$arrCategories[] = array(
										'id' => 0,
										'order' => 1,
										'name' => 'Latest'
									  );		
				// get current index of the array
				$current_key = key($arrCategories);
				
				foreach($latests_posts as $post) {
					
					// get post category
					$category = get_the_category($post->ID);
					
					$content = $post->post_content;
					// strip all tags, except for p,a and span
					//var_dump( strip_tags($content, '<p>,<span>,</p>,</span>,<a>,</a>,<br/>'));exit();
					
					var_dump($this->purifier->purify($content));exit();
					var_dump(Export::truncateHtml($content));exit();
					
					// get the purified content and display only one part of it for the description
					$content = $this->purifier->purify($post->post_content);
					$content = apply_filters( 'the_content', $content );
					
					var_dump($content);exit();
					
					// check if features image
					$image_src = '';
					// get features image and add it to the category
					if ( has_post_thumbnail($post->ID) ) { // check if the post has a Post Thumbnail assigned to it.
					  
						$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'full');
						
						if(is_array($image_data) && !empty($image_data)) {
							
							// set image src
							$image_src = $image_data[0];
							
							if(!isset($arrCategories[$current_key]["image"]) ) {
								// set arr category
								$arrCategories[$current_key]["image"] = array(
															  "src" 		=> $image_data[0],
															   "width" 		=> $image_data[1],
															   "height" 	=> $image_data[2],
															   "caption" 	=> ""
															  );
							}
						}
					} 
					
					// set article details
					$arrCategories[$current_key]["articles"][] = array(
																 'id' => $post->ID,
																 "title" => $post->post_title,
																 "timestamp" => strtotime($post->post_date),
																 "author" =>  $post->post_author,
																 "date" =>  $post->post_date,
																 "link" => $post->guid,
																 "image" => $image_src,
																 "description" =>  "<p>Accounts and Legal is venture capital backed multi-disciplinary advisory firm on a mission to help small businesses grow. </p><p> We are seeking to take on a journalist to create and edit exciting articles as part of our broader social media and SEO marketing campaign. </p><p> Responsibilities will include interviewing local business owners, researching niche markets and identifying topics pertinent to the small business community. </p><p> A keen interest in business, entrepreneurial spirit and creative flare are preferable. </p>",
																 "descriptionLength" => 538,
																 "content" => array(),
																 "category_id" => $category[0]->term_id,
																 "category_name" => $category[0]->name	
																 
																 
																 );
				
				
				}
			 }
			 
			
			foreach($categories as $key => $category) {
				
				// add details to category array
				$arrCategories[] = array(
										 	'id' => $category->term_id,
											'order' => $key + 1,
											'name' => $category->name,
										 );
				
				// get current index of the array
				$current_key = key($arrCategories);
				
				// get published articles for each category
				$args = array(
							'numberposts'      => $limit,
							'category'         => $category->term_id
							);
				
				$posts_array = get_posts( $args );
				
				if(count($posts_array > 0)) {
				
					foreach($posts_array as $post) {
						
						// check if features image
						$image_src = '';
						// get features image and add it to the category
						if ( has_post_thumbnail($post->ID) ) { // check if the post has a Post Thumbnail assigned to it.
						  
							$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'full');
							
							if(is_array($image_data) && !empty($image_data)) {
								
								// set image src
								$image_src = $image_data[0];
								
								if(!isset($arrCategories[$current_key]["image"]) ) {
									// set arr category
									$arrCategories[$current_key]["image"] = array(
																  "src" 		=> $image_data[0],
																   "width" 		=> $image_data[1],
																   "height" 	=> $image_data[2],
																   "caption" 	=> ""
																  );
								}
						  	}
						} 
						
						
						// set article details
						$arrCategories[$current_key]["articles"][] = array(
																	 'id' => $post->ID,
																	 "title" => $post->post_title,
																	 "timestamp" => strtotime($post->post_date),
																	 "author" =>  $post->post_author,
																	 "date" =>  $post->post_date,
																	 "link" => $post->guid,
																	 "image" => $image_src,
																	 "description" =>  "<p>Accounts and Legal is venture capital backed multi-disciplinary advisory firm on a mission to help small businesses grow. </p><p> We are seeking to take on a journalist to create and edit exciting articles as part of our broader social media and SEO marketing campaign. </p><p> Responsibilities will include interviewing local business owners, researching niche markets and identifying topics pertinent to the small business community. </p><p> A keen interest in business, entrepreneurial spirit and creative flare are preferable. </p>",
																	 "descriptionLength" => 538,
																	 "content" => array(),
																	 "category_id" => $category->term_id,
																	 "category_name" => $category->name	
																	 
																	 
																	 );
						
					}
				}
			}
			
			
		}
		
		
		var_dump($arrCategories);exit();
		var_dump(json_encode($arrCategories));exit();
		
		
		echo 'here2';
		
		// get the categories
		
		
		
	}
	


	/**
    * 
    *
	* Method
    */
	public static function getDescription($content, $description_length) {

		// first leave only the text between p, a and span tags
		$simplified_content = $content;
		
		// secodnly, remove all tags using strip_tags
		// set HTML Purifier
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding', 'UTF-8'); 									
		$config->set('HTML.Allowed', '');
		$purifier  = new HTMLPurifier($config); 
		
		$no_tags_content = $purifier->purify($simplified_content);
		
		// get only the first characters
		$simple_description = substr($no_tags_content,0,$description_length);
		
		
		
		
		var_dump($content);exit();
		
		

	}
	
	
	/**
	 * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
	 *
	 * @param string $text String to truncate.
	 * @param integer $length Length of returned string, including ellipsis.
	 * @param string $ending Ending to be appended to the trimmed string.
	 * @param boolean $exact If false, $text will not be cut mid-word
	 * @param boolean $considerHtml If true, HTML tags would be handled correctly
	 *
	 * @return string Trimmed string.
	 */
	public static function truncateHtml($text, $length = 200, $ending = '...', $exact = false, $considerHtml = true) {
		if ($considerHtml) {
			// if the plain text is shorter than the maximum length, return the whole text
			if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
				return $text;
			}
			// splits all html-tags to scanable lines
			preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
			$total_length = strlen($ending);
			$open_tags = array();
			$truncate = '';
			foreach ($lines as $line_matchings) {
				// if there is any html-tag in this line, handle it and add it (uncounted) to the output
				if (!empty($line_matchings[1])) {
					// if it's an "empty element" with or without xhtml-conform closing slash
					if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
						// do nothing
					// if tag is a closing tag
					} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
						// delete tag from $open_tags list
						$pos = array_search($tag_matchings[1], $open_tags);
						if ($pos !== false) {
						unset($open_tags[$pos]);
						}
					// if tag is an opening tag
					} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
						// add tag to the beginning of $open_tags list
						array_unshift($open_tags, strtolower($tag_matchings[1]));
					}
					// add html-tag to $truncate'd text
					$truncate .= $line_matchings[1];
				}
				// calculate the length of the plain text part of the line; handle entities as one character
				$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
				if ($total_length+$content_length> $length) {
					// the number of characters which are left
					$left = $length - $total_length;
					$entities_length = 0;
					// search for html entities
					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
						// calculate the real length of all entities in the legal range
						foreach ($entities[0] as $entity) {
							if ($entity[1]+1-$entities_length <= $left) {
								$left--;
								$entities_length += strlen($entity[0]);
							} else {
								// no more characters left
								break;
							}
						}
					}
					$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
					// maximum lenght is reached, so get off the loop
					break;
				} else {
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}
				// if the maximum length is reached, get off the loop
				if($total_length>= $length) {
					break;
				}
			}
		} else {
			if (strlen($text) <= $length) {
				return $text;
			} else {
				$truncate = substr($text, 0, $length - strlen($ending));
			}
		}
		// if the words shouldn't be cut in the middle...
		if (!$exact) {
			// ...search the last occurance of a space...
			$spacepos = strrpos($truncate, ' ');
			if (isset($spacepos)) {
				// ...and cut the text in this position
				$truncate = substr($truncate, 0, $spacepos);
			}
		}
		// add the defined ending to the text
		$truncate .= $ending;
		if($considerHtml) {
			// close all unclosed html-tags
			foreach ($open_tags as $tag) {
				$truncate .= '</' . $tag . '>';
			}
		}
		return $truncate;
	}

	
	
	
	
	
	
	
	
     
  } // Export


?>
