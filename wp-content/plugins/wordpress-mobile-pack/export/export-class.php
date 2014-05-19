<?php

require_once("../../../../wp-config.php");
require_once '../libs/htmlpurifier-4.6.0/library/HTMLPurifier.auto.php';
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
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding', 'UTF-8'); 									
		$config->set('HTML.Allowed','a[href],p,ol,li,ul,img[src],blockquote,em,span,h1,h2,h3,h4,h5,h6,i,u,strong,b,sup,br,cite,iframe[frameborder|marginheight|marginwidth|scrolling|src|width|height]');
		$config->set('Attr.AllowedFrameTargets', '_blank, _parent, _self, _top');
		$config->set('HTML.ForbiddenElements', 'style,class');
		
		$config->set('HTML.SafeIframe',1);
		$config->set('URI.SafeIframeRegexp','%^(https?:)?(http?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player.vimeo.com|www\.dailymotion.com|w.soundcloud.com|fast.wistia.net|fast.wistia.com|wi.st)%');
			   
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
	*					"height": 270
	*				},
	*				"articles": [
	*					{
	*						"id": "5362972281f58370a69686b7",
	*						"title": "Digital and Social Media Journalist",
	*						"timestamp": 1398969000,
	*						"has_facebook_id": 0,
	*						"author": "Accounts and Legal",
	*						"date": "Thu, May 01, 2014 06:30",
	*						"link": "http://www.journalism.co.uk/media-jobs/digital-and-social-media-journalist/s75/a556628/",
	*						"image": "",
	*						"description" : "<p>Lorem ipsum sit dolor amet..</p>",
	* 						"content": '',
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
	public function exportCategories() {
				
		if(isset($_GET["content"]) && $_GET["content"] == 'exportcategories') {
		
			// set default limit
			$limit = 7;
			if(isset($_GET["limit"]) && is_numeric($_GET["limit"]))
				$limit = $_GET["limit"];
			
			$descriptionLength = 200;
			if(isset($_GET["descriptionLength"]) && is_numeric($_GET["descriptionLength"]))
				$descriptionLength = $_GET["descriptionLength"];
			
			// init categories array	
			$arrCategories = array();
			
			// set path to default image
			$default_image = get_bloginfo('url').'/wp-content/plugins/wordpress-mobile-pack/img/category-default.jpg';
			
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
											'name' => 'Latest',
											'image' => array(
															 'src' 		=> $default_image,
															 'width' 	=> 480,
															 'height' 	=> 270
															 )
										  );		
					// get current index of the array
					$current_key = key($arrCategories);
					
					foreach($latests_posts as $post) {
						
						// get post category
						$category = get_the_category($post->ID);
						
						// get content
						$content = apply_filters("the_content",$this->purifier->purify($post->post_content));
						$description = Export::truncateHtml($content,$descriptionLength);
						
						// check if features image
						$image_details = array();
						// get features image and add it to the category
						if ( has_post_thumbnail($post->ID) ) { // check if the post has a Post Thumbnail assigned to it.
						  
							$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'large');
							
							if(is_array($image_data) && !empty($image_data)) {
								
								// set image details
								$image_details = array(
											   "src" 		=> $image_data[0],
											   "width" 		=> $image_data[1],
											   "height" 	=> $image_data[2]
											 );
								
								if($arrCategories[$current_key]["image"]["src"] == $default_image ) {
									// set arr category
									$arrCategories[$current_key]["image"] = $image_details;
								}
							}
						} 
						
						// set article details
						$arrCategories[$current_key]["articles"][] = array(
																	 'id' 				=> $post->ID,
																	 "title" 			=> $post->post_title,
																	 "timestamp" 		=> strtotime($post->post_date),
																	 "author" 			=>  get_the_author_meta( 'user_nicename' , $post->post_author ),
																	 "date" 			=>  date("D, M d, Y, H:i", strtotime($post->post_date)),
																	 "link" 			=> $post->guid,
																	 "image" 			=> !empty($image_details) ? $image_details : "",
																	 "description"		=> $description,
																	 "content" 			=> '',
																	 "category_id" 		=> $category[0]->term_id,
																	 "category_name" 	=> $category[0]->name
																	 );
					
					
					}
				 }
				 
				
				// reset array keys
				$categories = array_values($categories);
				
				foreach($categories as $key => $category) {
					
					// add details to category array
					$arrCategories[] = array(
												'id' 	=> $category->term_id,
												'order' => $key + 1,
												'name' 	=> $category->name,
												'image' => array(
																	 'src' 		=> get_bloginfo('url').'/wp-content/plugins/wordpress-mobile-pack/img/category-default.jpg',
																	 'width' 	=> 480,
																	 'height' 	=> 270
																 )
											 );
					
					// get published articles for each category
					$args = array(
								'numberposts'      => $limit,
								'category'         => $category->term_id
								);
					
					$posts_array = get_posts( $args );
					
					if(count($posts_array > 0)) {
					
						foreach($posts_array as $post) {
							
							// check if features image
							$image_details = array();
							// get features image and add it to the category
							if ( has_post_thumbnail($post->ID) ) { // check if the post has a Post Thumbnail assigned to it.
							  
								$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'large');
								
								if(is_array($image_data) && !empty($image_data)) {
									
									// set image details
									$image_details = array(
												   "src" 		=> $image_data[0],
												   "width" 		=> $image_data[1],
												   "height" 	=> $image_data[2]
												 );
									
									if($arrCategories[$key + 1]["image"]["src"] == $default_image ) 
										// set arr category
										$arrCategories[$key + 1]["image"] = $image_details;
									
								}
							} 
							
							
							// get content
							$content = apply_filters("the_content",$this->purifier->purify($post->post_content));
							$description = Export::truncateHtml($content,$descriptionLength);
						
							
							// set article details
							$arrCategories[$key + 1]["articles"][] = array(
																		 'id' 				=> $post->ID,
																		 "title" 			=> $post->post_title,
																		 "timestamp" 		=> strtotime($post->post_date),
																		 "author" 			=> get_the_author_meta( 'user_nicename' , $post->post_author ),
																		 "date" 			=> date("D, M d, Y, H:i", strtotime($post->post_date)),
																		 "link" 			=> $post->guid,
																		 "image" 			=> !empty($image_details) ? $image_details : "",
																		 "description"		=> $description,
																		 "content" 			=> '',
																		 "category_id" 		=> $category->term_id,
																		 "category_name" 	=> $category->name	
																		 
																		 
																		 );
							
						}
					}
				}
			}
			
			return '{"categories":'.json_encode($arrCategories)."}";
		
		} else
			return '{"error":""}';
	}
	


	/**
    * 
    *  - exportArticles method used for the export of a number of articels for each category
	*  - this metod returns a JSON with the specific content
	*  - ex : 
	*	{
	*		"articles": [
	*			{
	*			  "id": "53624b6981f58370a6968678",
	*			  "title": "#IJF14: Global developments in data journalism",
	*			  "timestamp": 1398950385,
	*			  "has_facebook_id": 0,
	*			  "author": "",
	*			  "date": "Thu, May 01, 2014 01:19",
	*			  "link": "http://www.journalism.co.uk/news/-ijf14-global-patterns-in-data-journalism-/s2/a556612/",
	*			  "image": "",
	*			  "description":"<p><b>Sport</b> (or <b>sports</b>) is all forms of usually <a href=\"http://en.wikipedia.org/wiki/Competition\">competitive</a> <a href=\"http://en.wikipedia.org/wiki/Physical_activity\">physical activity</a> which,<sup><a href=\"http://en.wikipedia.org/wiki/Sport#cite_note-sportaccord-1\">[1]</a></sup> through casual or organised participation, aim to use, maintain or improve physical ability and skills while...</p>",				  
	*			  "content": '',
	*			  "category_id": 5,
	*			  "category_name": "News"
	*			},
	*		]
	*	}
    *
	*    
    */
	public function exportArticles() {
		
		if(isset($_GET["content"]) && $_GET["content"] == 'exportarticles') {
		
			// init articles array
			$arrArticles = array();
			
			// set last timestamp
			$lastTimestamp = date("Y-m-d H:i:s");
			if(isset($_GET["lastTimestamp"]) && is_numeric($_GET["lastTimestamp"]))
				$lastTimestamp = date("Y-m-d H:i:s",$_GET["lastTimestamp"]);
			
			// set category id
			$categoryId = 0;
			if(isset($_GET["categoryId"]) && is_numeric($_GET["categoryId"]))
				$categoryId = $_GET["categoryId"];
			
			$descriptionLength = 200;
			if(isset($_GET["descriptionLength"]) && is_numeric($_GET["descriptionLength"]))
				$descriptionLength = $_GET["descriptionLength"];
			
			// set limit
			$limit = 7;
			if(isset($_GET["limit"]) && is_numeric($_GET["limit"]))
				$limit = $_GET["limit"];
			
			// set args for posts
			$args = array(
						  'date_query' => array('before' => $lastTimestamp),
						  'numberposts' => $limit,
						  "posts_per_page" => $limit,
						  'post_status' => 'publish'
						  );
			
			if($categoryId != 0)
				$args["cat"] = $categoryId;
			
			$posts_query = new WP_Query ( $args );
			
			
			if($posts_query->have_posts() ) {
				
				foreach($posts_query->posts as $post) {
					
					// check if features image
					$image_details = array();
					// get features image and add it to the category
					if ( has_post_thumbnail($post->ID) ) { // check if the post has a Post Thumbnail assigned to it.
					  
						$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'large');
						
						if(is_array($image_data) && !empty($image_data)) 
							// set image details
							$image_details = array(
												   "src" 		=> $image_data[0],
												   "width" 		=> $image_data[1],
												   "height" 	=> $image_data[2]
												 );
						
					} 
					
					// get post category
					$category = get_the_category($post->ID);
						
					// get content
					$content = apply_filters("the_content",$this->purifier->purify($post->post_content));
					$description = Export::truncateHtml($content,$descriptionLength);	
						
					$arrArticles[] = array(
											 'id' 				=> $post->ID,
											 "title" 			=> $post->post_title,
											 "timestamp" 		=> strtotime($post->post_date),
											 "author" 			=> get_the_author_meta( 'user_nicename' , $post->post_author ),
											 "date" 			=> date("D, M d, Y, H:i", strtotime($post->post_date)),
											 "link" 			=> $post->guid,
											 "image" 			=> !empty($image_details) ? $image_details : "",
											 "description"		=> $description,
											 "content" 			=> '',
											 "category_id" 		=> $category[0]->term_id,
											 "category_name" 	=> $category[0]->name
											 );
					
				}
			}
			
			return '{"articles":'.json_encode($arrArticles)."}";
		
		} else
			return '{"error":""}';
	}
	
	
	/**
    * 
    *  - exportArticle method used for the export of an article
	*  - this metod returns a JSON with the specific content
	*  - ex : 
	*	{
	*	  "article": {
	*		"id": "53624b6981f58370a6968678",
	*		"title": "#IJF14: Global developments in data journalism",
	*		"timestamp": 1398960437,
	*		"author": "",
	*		"date": "Thu, May 01, 2014 04:07",
	*		"link": "http://www.journalism.co.uk/news/-ijf14-global-patterns-in-data-journalism-/s2/a556612/",
	*		"image": "",
	*		"description":"<p><b>Sport</b> (or <b>sports</b>) is all forms of usually <a href=\"http://en.wikipedia.org/wiki/Competition\">competitive</a> <a href=\"http://en.wikipedia.org/wiki/Physical_activity\">physical activity</a> which,<sup><a href=\"http://en.wikipedia.org/wiki/Sport#cite_note-sportaccord-1\">[1]</a></sup> through casual or organised participation, aim to use, maintain or improve physical ability and skills while...</p>",				  
	*	    "content": "<p>On the second day of the International Journalism Festival in Perugia, delegates were treated to a round up of data journalism trends and developments from around the world.</p>",
	*		"comment_status": "open",	** the values can be opened or closed	
	*		"category_id": 5,
	*		"category_name": "News"
	*	  }
	*	}
    *  
	*   @params $articleId - the id of the article
	*    
    */
	public function exportArticle() {
		
		// check if the export call is correct
		if(isset($_GET["content"]) && $_GET["content"] == 'exportarticle' ) {
		
			// set articleId
			$articleId = 0;			
			if(isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
				$articleId = $_GET["articleId"];
			}
			
			$descriptionLength = 200;
			if(isset($_GET["descriptionLength"]) && is_numeric($_GET["descriptionLength"]))
				$descriptionLength = $_GET["descriptionLength"];
			
		
			// init articles array
			$arrArticle = array();
			
			// get post by id
			$post = get_post( $articleId);
			
			
			if($post != null && $post->post_type == 'post') {
				
				// check if features image
				$image_details = array();				
				// get features image and add it to the category
				if ( has_post_thumbnail($post->ID) ) { // check if the post has a Post Thumbnail assigned to it.
				  
					$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'large');
					
					if(is_array($image_data) && !empty($image_data)) 
						// set image src
						$image_details = array(
												   "src" 		=> $image_data[0],
												   "width" 		=> $image_data[1],
												   "height" 	=> $image_data[2]
												 );
					
				} 
				
				// filter the content
				$content = apply_filters( 'the_content', $post->post_content );
				$content = $this->purifier->purify($content);
				
				// get the description
				$description = Export::truncateHtml($content,$descriptionLength);
				
				// get post category
				$category = get_the_category($post->ID);
				
				$arrArticle = array(
										 'id' 				=> $post->ID,
										 "title" 			=> $post->post_title,
										 "timestamp" 		=> strtotime($post->post_date),
										 "author" 			=> get_the_author_meta( 'user_nicename' , $post->post_author ),
										 "date" 			=> date("D, M d, Y, H:i", strtotime($post->post_date)),
										 "link" 			=> $post->guid,
										 "image" 			=> !empty($image_details) ? $image_details : "",
										 "description"	    => $description,
										 "content" 			=> $content,
										 'comment_status'   => $post->comment_status,
										 "category_id" 		=> $category[0]->term_id,
										 "category_name" 	=> $category[0]->name
										 );
				
			}
				
			// return article json
			return '{"article":'.json_encode($arrArticle)."}";
			
		} else
			// return error
			return '{"error":""}';
	}
	
	
	
	/**
    * 
    *  - exportComments method used for the export of the comments for an article
	*  - this metod returns a JSON with the specific content
	*  - ex : 
	*	{
	*	  "comments": {
	*		"id": "53624b6981f58370a6968678",
	*		"title": "#IJF14: Global developments in data journalism",
	*		"timestamp": 1398960437,
	*		"author": "",
	*		"date": "Thu, May 01, 2014 04:07",
	*		"link": "http://www.journalism.co.uk/news/-ijf14-global-patterns-in-data-journalism-/s2/a556612/",
	*		"image": "",
	*		"content": "<p>On the second day of the International Journalism Festival in Perugia, delegates were treated to a round up of data journalism trends and developments from around the world.</p>",
	*		"category_id": 5,
	*		"category_name": "News"
	*	  }
	*	}
    *  
	*   @params $articleId - the id of the article
	*    
    */
	public function exportComments() {
		
		// check if the export call is correct
		if(isset($_GET["content"]) && $_GET["content"] == 'exportcomments' ) {
		
			// set articleId
			$articleId = 0;
			
			if(isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
				$articleId = $_GET["articleId"];
				
			}
				
			// init articles array
			$arrComments = array();
			
			$args = array(
							'parent' => '',
							'post_id' => $articleId,
							'post_type' => 'post',
							'status' => 'approve',
						);
			
			// get post by id
			$comments = get_comments( $args);
			
			if(is_array($comments) && !empty($comments)) {
				
				foreach($comments as $comment) {
					
					$arrComments[] = array(
										   	'id' => $comment->comment_ID,
											'author' => ucfirst($comment->comment_author),
											'author_url' => $comment->comment_author_url,
											'date' => date("D, M d, Y, H:i", strtotime($comment->comment_date)),
											'content' => $this->purifier->purify($comment->comment_content),
											'article_id' => $comment->ID,
											'article_title'=>$comment->post_title
										   );
					
				}
			}
				
			// return article json
			return '{"comments":'.json_encode($arrComments)."}";
			
		} else
			// return error
			return '{"error":""}';
	}
	
	/**
    * 
    *  - saveComment method used to add a comment to an article
	*  - this metod returns a JSON with the success/ error message
	*  - ex of post request : 
	*	{
	*	  "comments": {
	*		"id": "53624b6981f58370a6968678",
	*		"title": "#IJF14: Global developments in data journalism",
	*		"timestamp": 1398960437,
	*		"author": "",
	*		"date": "Thu, May 01, 2014 04:07",
	*		"link": "http://www.journalism.co.uk/news/-ijf14-global-patterns-in-data-journalism-/s2/a556612/",
	*		"image": "",
	*		"content": "<p>On the second day of the International Journalism Festival in Perugia, delegates were treated to a round up of data journalism trends and developments from around the world.</p>",
	*		"category_id": 5,
	*		"category_name": "News"
	*	  }
	*	}
    *  
	*   
	*    
    */
	public function saveComment() {
		
		// check if the export call is correct
		if(isset($_GET["content"]) && $_GET["content"] == 'savecomment' ) {
		
			// set articleId
			$articleId = 0;			
			if(isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
				$articleId = $_GET["articleId"];
				
			}
				
			// get post by article id
			// get post by id
			$post = get_post( $articleId);
			
			
			if($post != null && $post->post_type == 'post') {
				
				if($post->post_status == 'publish') {
				
					// check if the post accepts comments
					if(comments_open( $articleId )) {
						
						// get post variables
						$comment_post_ID = 		$articleId;		
						$comment_author   = 	( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : '';
						$comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : '';
						$comment_author_url   = ( isset($_POST['url']) )     ? trim($this->purifier->purify($_POST['url'])) : '';
						$comment_content      = ( isset($_POST['comment']) ) ? trim($this->purifier->purify($_POST['comment'])) : '';
						$comment_parent = 		isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;
						
						// return errors for empty fields
						if(get_option('require_name_email')) {
								
							if ( $comment_author_email == '' || $comment_author == '' )
								return '{"error":"Please fill the required fields (name, email)."}';
							elseif ( !is_email($comment_author_email))
								return '{"error":"Please enter a valid e-mail address."}';
						}
						
						if ( $comment_content == '' )
							return '{"error":"Please type a comment."}';
						
						// set comment data
						$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');
						// get comment id
						$comment_id = wp_new_comment( $commentdata );
						
						if(is_numeric($comment_id))
							return '{"success" : "Your comment is awaiting moderation."}';
						
						//$comment = get_comment($comment_id);
						
						
					} else // return error
						return '{"error":"Sorry, comments are closed for this item."}';
						
				}else
					// return error
					return '{"error":"Sorry, the post is not visible"}';
				
			} else
				// return error
				return '{"error":"Sorry, the post is not available"}';
				
			// init articles array
			$arrComments = array();
			
			$args = array(
							'parent' => '',
							'post_id' => $articleId,
							'post_type' => 'post',
							'status' => 'approve',
						);
			
			// get post by id
			$comments = get_comments( $args);
			
			if(is_array($comments) && !empty($comments)) {
				
				foreach($comments as $comment) {
					
					$arrComments[] = array(
										   	'id' => $comment->comment_ID,
											'author' => ucfirst($comment->comment_author),
											'author_url' => $comment->comment_author_url,
											'date' => date("D, M d, Y, H:i", strtotime($comment->comment_date)),
											'content' => $this->purifier->purify($comment->comment_content),
											'article_id' => $comment->ID,
											'article_title'=>$comment->post_title
										   );
					
				}
			}
				
			// return article json
			return '{"comments":'.json_encode($arrComments)."}";
			
		} else
			// return error
			return '{"error":""}';
	}
	
	
	
	
	
	/**
	 * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
	 *
	 * @param string $text String to truncate.
	 * @param integer $length Length of returned string, including ellipsis.
	 * @param string $ending Ending to be appended to the trimmed string.
	 * @param boolean $exact If false, $text will not be cut mid-word
	 * @param boolean $considerHtml If true, HTML tags would be handled correctly
	 * @param boolean $stripTags If true, all the tags except some allowed tags will be removed
	 *
	 * @return string Trimmed string.
	 */
	public static function truncateHtml($text, $length = 200, $ending = '...', $exact = false, $considerHtml = true, $stripTags = true) {
		
		if ($considerHtml) {
			
			// if no_images is true, remove all images from the content
			if($stripTags)
				$text = strip_tags( $text, '<p><a><span><br><i><u><strong><b><sup><em>');
			
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
