<?php

require_once("../../../../wp-config.php");
require_once '../libs/htmlpurifier-4.6.0/library/HTMLPurifier.safe-includes.php';
require_once '../libs/htmlpurifier-html5/htmlpurifier_html5.php';
		
/* -------------------------------------------------------------------------*/
/* Export class with different export 										*/
/* methods for categories, articles and comments							*/
/* -------------------------------------------------------------------------*/

class Export {
	
    /* ----------------------------------*/
    /* Attributes						 */
    /* ----------------------------------*/
   	
	private $purifier;
    private $inactive_categories = array();
	private $inactive_pages = array();
   	
    /* ----------------------------------*/
    /* Methods							 */
    /* ----------------------------------*/

	public function __construct() {
		
		// set HTML Purifier
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding', 'UTF-8'); 									
		$config->set('HTML.AllowedElements','div,a,p,ol,li,ul,img,blockquote,em,span,h1,h2,h3,h4,h5,h6,i,u,strong,b,sup,br,cite,iframe,small,video,audio,source');
		$config->set('HTML.AllowedAttributes', 'src, width, height, target, href, name,frameborder,marginheight,marginwidth,scrolling,poster,preload,controls,type,data-type');
		
		$config->set('URI.AllowedSchemes', array ('http' => true, 'https' => true, 'mailto' => true, 'news' => true, 'tel' => true, 'callto' => true, 'skype' => true, 'sms' => true, 'whatsapp' => true));
		
        $config->set('Attr.AllowedFrameTargets', '_blank, _parent, _self, _top');
		
		$config->set('HTML.SafeIframe',1);
		$config->set('Filter.Custom', array( new HTMLPurifier_Filter_Iframe()));
		
		// disable cache
		$config->set('Cache.DefinitionImpl',null);
		
	    // extend purifier
        $Html5Purifier = new WMPHtmlPurifier();
        $this->purifier = $Html5Purifier->wmp_extended_purifier($config);
        
        $this->inactive_categories = unserialize(WMobilePack::wmp_get_setting('inactive_categories'));
		$this->inactive_pages = unserialize(WMobilePack::wmp_get_setting('inactive_pages'));
	}

    /**
	 *
	 * Format an article's or comment's date
	 *
	 */
    protected function formatDate($date_timestamp){
		
		if (date('Y') == date('Y', $date_timestamp))
			return date('D, F jS', $date_timestamp);
			
		return date('F jS, Y', $date_timestamp);
	}
	
	/**
    * 
    *  - exportCategories method used for the export of every category with a number of articles for each
	*  - this method returns a JSON with the specific content
	*  - ex : 
	*	{
	*		"categories": [
	*			{
	*				"id": 0,
	*				"order": 1,
	*				"name": "Latest",
	*				"link": "",
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
				
		if (isset($_GET["content"]) && $_GET["content"] == 'exportcategories') {
		
			// set default limit
			$limit = 7;
			if (isset($_GET["limit"]) && is_numeric($_GET["limit"]))
				$limit = $_GET["limit"];
			
			$descriptionLength = 200;
			if (isset($_GET["descriptionLength"]) && is_numeric($_GET["descriptionLength"]))
				$descriptionLength = $_GET["descriptionLength"];
			
			// get categories
			$categories = get_categories(array('hierarchical' => 0));
            
            // build array with the active categories ids
            $active_categories_ids = array();
            
            foreach ($categories as $category){
                if (!in_array($category->cat_ID, $this->inactive_categories))
                    $active_categories_ids[] = $category->cat_ID;
            }
            
            // init categories array	
			$arrCategories = array();
            
			// remove inline style for the photos types of posts
			add_filter( 'use_default_gallery_style', '__return_false' );
			
			if (count($active_categories_ids) > 0) {
			 
				foreach ($categories as $key => $category) {
					
                    if (in_array($category->cat_ID, $active_categories_ids)){
                        
                        $current_key = $category->cat_ID;
                        
						$arrCategories[$current_key] = array(
							'id' 		=> $category->term_id,
							'order' 	=> false,
							'name' 		=> $category->name,
							'name_slug' => $category->slug,
							'link' 		=> get_category_link($category->term_id),
							'image' 	=> ""
					    );                             
                        
                        // search posts from this category
    					$cat_posts_query = new WP_Query(
                            array(
        						'numberposts'         => $limit,
    							'category__in'	      => $category->cat_ID,
    							'posts_per_page'      => $limit,
        			  			'post_status'         => 'publish',
    							'post_password'       => ''
    						)
                        );
  			     
                    	if ($cat_posts_query->have_posts()) {
							
    						foreach($cat_posts_query->posts as $post) {
    							
                                // check if the post is not password protected
								if ($post->post_password == '') {
								    
									// check if the post has a post thumbnail assigned to it and save it in an array
									$image_details = array();
									
									if (has_post_thumbnail($post->ID)){
    									  
										$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'large');
										
										if (is_array($image_data) && !empty($image_data)) {
											
											// set image details
											$image_details = array(
												"src" 		=> $image_data[0],
												"width" 	=> $image_data[1],
												"height" 	=> $image_data[2]
											);
										}
									}
									
									// if the category doesn't have a featured image yet, use the one from the current post
                                    if (!is_array($arrCategories[$current_key]["image"]) && !empty($image_details)) {
    									$arrCategories[$current_key]["image"] = $image_details;
                                    }
									
									// get & filter content
									$content = apply_filters("the_content", $post->post_content);
									$description = Export::truncateHtml($content, $descriptionLength);
									$description = $this->purifier->purify($description);
								
									// if this is the first article from the category, create the 'articles' array
                                    if (!isset($arrCategories[$current_key]["articles"]))
                                        $arrCategories[$current_key]["articles"] = array();
                                        
									$date_timestamp = strtotime($post->post_date);
										
									// add article in the array
									$arrCategories[$current_key]["articles"][] = array(
										'id' 				=> $post->ID,
										"title" 			=> $post->post_title,
										"timestamp" 		=> $date_timestamp,
										"author" 			=> get_the_author_meta('display_name', $post->post_author ),
										"date" 				=> $this->formatDate($date_timestamp),
										"link" 				=> get_permalink($post->ID),
										"image" 			=> !empty($image_details) ? $image_details : "",
										"description"		=> $description,
										"content" 			=> '',
										"category_id" 		=> $category->term_id,
										"category_name" 	=> $category->name	
									);
								}
							}
    					}
                        
						// check if the category has at least one post, otherwise delete it from the export array
						if (!isset($arrCategories[$current_key]["articles"]) || empty($arrCategories[$current_key]["articles"]))
							unset($arrCategories[$current_key]);
                    }
				}
                
                // activate latest category only if we have at least 2 visible categories
                if (count($arrCategories) > 1){
                    
                    // read posts for the latest category
                    $posts_query = new WP_Query ( 
                        array(
                            'numberposts'  => $limit,
                            'cat' 		   => implode(', ', $active_categories_ids),
    						"posts_per_page" => $limit,
        			  		'post_status' => 'publish',
    						'post_password' => ''
                        )
                   );
                   
                   if ($posts_query->have_posts()) {
    					
                        $arrCategories[0] = array(
                            'id' 		=> 0,
                            'order' 	=> false,
                            'name' 		=> 'Latest',
							'name_slug' => 'Latest',
                            'image' 	=> ""
                        );		
                        
                        foreach ($posts_query->posts as $post) {
    						
							// check if the post is not password protected
							if ($post->post_password == '') {
							
								// get post category
								$category = get_the_category($post->ID);
								
								// get & filter content
								$content = apply_filters("the_content",$post->post_content);    						
								$description = Export::truncateHtml($content, $descriptionLength);    						
								$description = $this->purifier->purify($description);
								
								// check if the post has a post thumbnail assigned to it and save it in an array
								$image_details = array();
    								
								if (has_post_thumbnail($post->ID)) {
								  
									$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large');
									
									if (is_array($image_data) && !empty($image_data)) {
										
										// set image details
										$image_details = array(
											"src" 		=> $image_data[0],
											"width" 	=> $image_data[1],
											"height" 	=> $image_data[2]
										);
									}
								} 
								
								// if the category doesn't have a featured image yet, use the one from the current post
                                if (!is_array($arrCategories[0]["image"]) && !empty($image_details)) {
    								$arrCategories[0]["image"] = $image_details;
								}
                                
								// set article details
                                if (!isset($arrCategories[0]["articles"]))
                                    $arrCategories[0]["articles"] = array();
                                    
								$date_timestamp = strtotime($post->post_date);
								
								$arrCategories[0]["articles"][] = array(
									'id' 				=> $post->ID,
									"title" 			=> $post->post_title,
									"timestamp" 		=> $date_timestamp,
									"author" 			=> get_the_author_meta( 'display_name' , $post->post_author ),
									"date" 				=> $this->formatDate($date_timestamp),
									"link" 				=> get_permalink($post->ID),
									"image" 			=> !empty($image_details) ? $image_details : "",
									"description"		=> $description,
									"content" 			=> '',
									"category_id" 		=> $category[0]->term_id,
									"category_name" 	=> $category[0]->name
								);
							}
    					}
                    }
                    
                    // check if the category has at least one post
					if (!isset($arrCategories[0]["articles"]) || empty($arrCategories[0]["articles"]))
						unset($arrCategories[0]);
                }
			}
            
            // ------------------------------------ //
            
            // build array with the ordered categories
            $arrOrderedCategories = array();
            
            // check if the categories were orderd
            $order_categories = unserialize(WMobilePack::wmp_get_setting('ordered_categories'));
            
            // check if we have a latest category (should be the first one to appear)
            $has_latest = 0;
            if (isset($arrCategories[0])){
                
                // set order for the latest category and add it in the list
                $arrCategories[0]['order'] = 1;
                $has_latest = 1;
                
                $arrOrderedCategories[] = $arrCategories[0];
            }
            
            // if the categories have been ordered
            if (!empty($order_categories)) {

                // last ordered used for a category
                $last_order = 1;
                
                foreach ($order_categories as $category_id){
                    
                    // inactive categories & latest will be skipped
                    if (array_key_exists($category_id, $arrCategories)){
                        
                        // set the order for the category and add it in the list
                        $arrCategories[$category_id]['order'] = $last_order + $has_latest;
                                
                        $arrOrderedCategories[] = $arrCategories[$category_id];
                        $last_order++;
                    }
                }
                
                foreach ($arrCategories as $key => $category){
                    if ($category['order'] === false) {
                        
                        $arrCategories[$key]['order'] = $last_order + $has_latest;
                                
                        $arrOrderedCategories[] = $arrCategories[$key];
                        $last_order++;   
                    }
                }
                
            } else {
                
                // last ordered used for a category
                $last_order = 1;
                
                // set order for all the categories besides latest
                foreach ($arrCategories as $key => $category){
                    
                    if ($category['id'] != 0) {
                        
                        // set the order for the category and add it in the list
                        $arrCategories[$key]['order'] = $last_order + $has_latest;
                        
                        $arrOrderedCategories[] = $arrCategories[$key];
                        $last_order++;
                    }
                }
            }
            
			return '{"categories":'.json_encode($arrOrderedCategories)."}";
		
		} else
			return '{"error":""}';
	}
	


	/**
    * 
    *  - exportArticles method used for the export of a number of articles for each category
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
		
		if (isset($_GET["content"]) && $_GET["content"] == 'exportarticles') {
		
			// init articles array
			$arrArticles = array();
			
			// set last timestamp
			$lastTimestamp = date("Y-m-d H:i:s");
			if (isset($_GET["lastTimestamp"]) && is_numeric($_GET["lastTimestamp"]))
				$lastTimestamp = date("Y-m-d H:i:s",$_GET["lastTimestamp"]);
			
			// set category id
			$categoryId = 0;
			if (isset($_GET["categoryId"]) && is_numeric($_GET["categoryId"]))
				$categoryId = $_GET["categoryId"];
			
			$descriptionLength = 200;
			if (isset($_GET["descriptionLength"]) && is_numeric($_GET["descriptionLength"]))
				$descriptionLength = $_GET["descriptionLength"];
			
			// set limit
			$limit = 7;
			if (isset($_GET["limit"]) && is_numeric($_GET["limit"]))
				$limit = $_GET["limit"];
			
			// set args for posts
			$args = array(
				'date_query' => array('before' => $lastTimestamp),
				'numberposts' => $limit,
				'posts_per_page' => $limit,
				'post_status' => 'publish',
				'post_password' => ''
            );
			
            // if the selected category is active
            $activeCategory = false;
            
			// remove inline style for the photos types of posts
			add_filter( 'use_default_gallery_style', '__return_false' );
			
			if ($categoryId != 0) {
			 
				$args["cat"] = $categoryId;
                
                // check if this category was not deactivated
                if (!in_array($categoryId, $this->inactive_categories))
                    $activeCategory = true;
                                
			} else {
            
                // latest category will always be active    
                $activeCategory = true;
                
                // check if we must exclude some categories ids from the seach
                if (count($this->inactive_categories) > 0){
                    
                    $args["cat"] = "";
                    
                    // categories with '-' in front will be excluded
                    foreach ($this->inactive_categories as $inactive_category)
                        $args["cat"] .= '-'.$inactive_category.',';
                        
                    $args["cat"] = substr($args["cat"],0,-1);
                    
                }
			}
            
            if ($activeCategory){
                
    			$posts_query = new WP_Query ( $args );
    			
    			if ($posts_query->have_posts() ) {
    				
    				foreach($posts_query->posts as $post) {
    				    
    					// add only the posts that are not password protected
						if ($post->post_password == '') {
						
							// check if a featured image exists
							$image_details = array();
                            
							// get featured image
							if (has_post_thumbnail($post->ID)){ // check if the post has a Post Thumbnail assigned to it.
							  
								$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'large');
								
								if (is_array($image_data) && !empty($image_data)) {
									
									$image_details = array(
										"src" 		=> $image_data[0],
										"width" 		=> $image_data[1],
										"height" 	=> $image_data[2]
									);
								}
							} 
							
							// get post category
							if ($categoryId > 0) {
								$category = get_category($categoryId);
							} else {
								$cat = get_the_category($post->ID);
								$category = $cat[0];
							}
							
							// get content
							$content = apply_filters("the_content",$post->post_content);
							$description = Export::truncateHtml($content,$descriptionLength);
							$description = $this->purifier->purify($description);
								
							$date_timestamp = strtotime($post->post_date);
							
							$arrArticles[] = array(
								'id' 				=> $post->ID,
								"title" 			=> $post->post_title,
								"timestamp" 		=> $date_timestamp,
								"author" 			=> get_the_author_meta( 'display_name' , $post->post_author ),
								"date" 				=> $this->formatDate($date_timestamp),
								"link" 				=> get_permalink($post->ID),
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
    *       "no_comments": 2,	
	*       "show_avatars" : true,
	*		"require_name_email" : true,	
	*		"category_id": 5,
	*		"category_name": "News".
    *       "related_posts" :"",
    *       "related_web_posts":""
	*	  }
	*	}
    *  
	*   @params $articleId - the id of the article
	*    
    */
	public function exportArticle() {
		
        global $post;
        
		// check if the export call is correct
		if (isset($_GET["content"]) && $_GET["content"] == 'exportarticle' ) {
		
			// set articleId
			$articleId = 0;			
			if (isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
				$articleId = $_GET["articleId"];
			}
			
			$descriptionLength = 200;
			if (isset($_GET["descriptionLength"]) && is_numeric($_GET["descriptionLength"]))
				$descriptionLength = $_GET["descriptionLength"];
			
			// init articles array
			$arrArticle = array();
			
			// get post by id
		    $post = get_post($articleId);
			
			if ($post != null && $post->post_type == 'post' && $post->post_password == '' && $post->post_status == 'publish') {
				
                // get post categories
				$categories = get_the_category($post->ID);
                          
                // check if at least one of the categories is visible
                $is_visible = false;
                $visible_category = null;
                
                foreach ($categories as $category){
                    
                    if (!in_array($category->cat_ID, $this->inactive_categories)) {
                        $is_visible = true;
                        $visible_category = clone $category;
                    }
                }
                
                if ($is_visible){
                
    				// featured image details
    				$image_details = array();
                    				
    				// get featured image
    				if ( has_post_thumbnail($post->ID) ) { // check if the post has a Post Thumbnail assigned to it.
    				  
    					$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'large');
    					
    					if (is_array($image_data) && !empty($image_data)) {
    					   
    						$image_details = array(
    												   "src" 		=> $image_data[0],
    												   "width" 		=> $image_data[1],
    												   "height" 	=> $image_data[2]
    												 );
    					}
    				}
					
                    // filter the content
    				$content = apply_filters('the_content', $post->post_content);
					
					$related_posts = '';
                    $related_web_posts = '';
                    $zemanta = false;
					
                    /* ZEMANTA RELATED POSTS AND POSTS FROM AROUND THE WEB */
                    if (WMobilePack::wmp_active_plugin('Related Posts by Zemanta')) {
                    
                        // check if class exists and specific function that are used
                        if (class_exists('WPRPZemanta')) {
                            
                            // set zemanta purifier
                            $zemanta_purifier = $this->wmp_zemanta_purifier();
                            
                            // check if related posts should be displayed
                            if (function_exists('zem_rp_get_options')) {
								
                                // get options 
                                $options = zem_rp_get_options();
								
                                if ($options['display_zemanta_linky'])
                                    $zemanta = true;      
                                
                                if ($post->post_content != "" && $post->post_type === 'post' && $options["on_single_post"]){
                                  
                                  if (function_exists('zem_rp_get_related_posts')) {
                                
                                        $related_posts = zem_rp_get_related_posts() != null ? zem_rp_get_related_posts() : '';
                                        
                                        // there are related posts set
                                        if ($related_posts != ''){
                                            
                                            // parse the urls in order to obtain the correct path
                                            $related_posts = $this->wmp_replace_internal_links($related_posts);
                                            
                                            // remove inline styling
                                            $related_posts = $zemanta_purifier->purify($related_posts);
                                        }
                                    }
                                }
                            }
                            
                            // get related posts from around the web
                            $related_web_posts = $this->wmp_related_web_posts($content);
                            $related_web_posts =$zemanta_purifier->purify($related_web_posts);
                            
                        }
                        
                    }  
                    
                    if ($related_web_posts == '') {
                        
                        // set zemanta purifier
                        $zemanta_purifier = $this->wmp_zemanta_purifier();
                        
                        /* ZEMANTA EDITORIAL ASISTANT AND POSTS FROM AROUND THE WEB */                  
                        if (WMobilePack::wmp_active_plugin('Editorial Assistant by Zemanta')) {
							
							// get related posts from around the web
							$related_web_posts = $this->wmp_related_web_posts($content);
							
							// parse the urls in order to obtain the correct path
							$related_web_posts = $this->wmp_replace_internal_links($related_web_posts);
							$related_web_posts = $zemanta_purifier->purify($related_web_posts);
                        }
                    }
    				
					// remove script tags
					$content = self::removeScriptTags($content);
					
    				$content = $this->purifier->purify($content);
    			 
					// remove all url's from attachment images
					$content = preg_replace( array('{<a(.*?)(wp-att|wp-content\/uploads|attachment)[^>]*><img}', '{ wp-image-[0-9]*" /></a>}'), array('<img','" />'), $content);
					
    				// get the description
    				$description = Export::truncateHtml($content,$descriptionLength);
					
    				// get comments status
                    $comment_status = $this->comment_closed($post);
                    
                    // check if there is at least a  comment
					$comment_count = wp_count_comments( $articleId );	
                    $no_comments = $comment_count->approved;
                    
					if ($comment_status == 'closed') {
						
						if ($comment_count) 
							if ($comment_count->approved == 0) 
								$comment_status = 'disabled';
					}
					
					$date_timestamp = strtotime($post->post_date);
					
    				$arrArticle = array(
                        'id' 					=> $post->ID,
                        "title" 				=> $post->post_title,
                        "timestamp" 			=> $date_timestamp,
                        "author" 				=> get_the_author_meta( 'display_name' , $post->post_author ),
                        "date" 			    	=> $this->formatDate($date_timestamp),
                        "link" 			    	=> get_permalink($post->ID),
                        "image" 				=> !empty($image_details) ? $image_details : "",
                        "description"	    	=> $description,
                        "content" 				=> $content,
                        "comment_status"    	=> $comment_status,
                        "no_comments"           => $no_comments,
                        "show_avatars"			=> get_option("show_avatars") == 1 ? true : false,// false
						"require_name_email"	=> get_option("require_name_email") == 1 ? true : false,
						"category_id" 			=> $visible_category->term_id,
                        "category_name" 		=> $visible_category->name,
                        "related_posts"         => trim($related_posts),
                        "related_web_posts"     => trim($related_web_posts),
                        "zemanta"               => $zemanta
					 );
				}
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
		if (isset($_GET["content"]) && $_GET["content"] == 'exportcomments' ) {
		
			// set articleId
			$articleId = 0;
			
			if (isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
				$articleId = $_GET["articleId"];
				
			}
				
			// init articles array
			$arrComments = array();
			
			$args = array(
							'parent' 	=> '',
							'post_id' 	=> $articleId,
							'post_type' => 'post',
							'status' 	=> 'approve',
						);
			
			// order comments
			if (WMP_BLOG_VERSION >= 3.6) {
				$args['orderby'] = 'comment_date_gmt';
				$args['order'] = 'ASC';
			}
			
			// get post by id
			$comments = get_comments( $args);
			
			
			if (is_array($comments) && !empty($comments)) {
				
				foreach($comments as $comment) {
					$get_avatar = '';
					$avatar = '';
					// get avatar only if the author wants it displayed
					if (get_option("show_avatars")) {
						
						$get_avatar = get_avatar( $comment, 50);
						preg_match("/src='(.*?)'/i", $get_avatar, $matches);
						if (isset($matches[1]))
							$avatar = $matches[1];
					} 
						
					$arrComments[] = array(
						'id' => $comment->comment_ID,
						'author' => $comment->comment_author != '' ? ucfirst($comment->comment_author) : 'Anonymous',
						'author_url' => $comment->comment_author_url,
						'date' => $this->formatDate(strtotime($comment->comment_date)),
						'content' => $this->purifier->purify($comment->comment_content),
						'article_id' => $comment->ID,
						'article_title'=>$comment->post_title,
						'avatar' => $avatar
					);
					
				}
			}
				
			// return comments json
			return '{"comments":'.json_encode($arrComments)."}";
			
		} else
			// return error
			return '{"error":""}';
	}
	
	/**
    * 
    *  - saveComment method used to add a comment to an article
	*  - this metod returns a JSON with the success/ error message
	*  - ex of get request : 
	*	
	*	"author": "Flori",
	*	"email": "florentina@appticles.com",
	*	"url": http://appticles.com,
	*	"comment": " love the pohotos of the cats!!",
	*	"comment_parent": "1",
	*	"code": "7841da44befc5b8fa00a0c8daab49d21_1400771121",	
	*	 
	*	
    *  
	*   
	*    
    */
	public function saveComment() {
		
		// check if the export call is correct
		if (isset($_GET["content"]) && $_GET["content"] == 'savecomment' ) {
			
			if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'],$_SERVER["HTTP_HOST"]) !== false) {
		
				// set articleId
				$articleId = 0;			
				if (isset($_GET["articleId"]) && is_numeric($_GET["articleId"])) {
					$articleId = $_GET["articleId"];
				}
					
				// check token
				if (isset($_GET['code']) && $_GET["code"] !== '') {
					
					// if the token is valid, go ahead and save comment to the DB
					if (WMobilePack::wmp_check_token($_GET['code'])) {
						
						// get post by id
						$post = get_post( $articleId);
						
						if ($post != null && $post->post_type == 'post') {
							
							if ($post->post_status == 'publish') {
							
								// check if the post accepts comments
								if (comments_open( $articleId )) {
									
									// get post variables
									$comment_post_ID = 		$articleId;		
									$comment_author   = 	( isset($_GET['author']) )  ? trim(strip_tags($_GET['author'])) : '';
									$comment_author_email = ( isset($_GET['email']) )   ? trim($_GET['email']) : '';
									$comment_author_url   = ( isset($_GET['url']) )     ? trim($this->purifier->purify($_GET['url'])) : '';
									$comment_content      = ( isset($_GET['comment']) ) ? trim($this->purifier->purify($_GET['comment'])) : '';
									$comment_type = 		'comment';
									$comment_parent = 		isset($_GET['comment_parent']) ? absint($_GET['comment_parent']) : 0;
									
									// return errors for empty fields
									if (get_option('require_name_email')) {
											
										if ( $comment_author_email == '' || $comment_author == '' )
											return '{"status":0}'; //Please fill the required fields (name, email).
										elseif ( !is_email($comment_author_email))
											return '{"status":0}'; // Please enter a valid e-mail address.
									}
									
									if ( $comment_content == '' )
										return '{"status":0}'; // Please type a comment
									
									// check if comment will be approved directly or will await moderation
									$approved_comment = check_comment($comment_author,$comment_author_email,$comment_author_url,$comment_content,$_SERVER["REMOTE_ADDR"],$_SERVER['HTTP_USER_AGENT'],'user');
									
									if (wp_blacklist_check($comment_author,$comment_author_email,$comment_author_url,$comment_content,$_SERVER["REMOTE_ADDR"],$_SERVER['HTTP_USER_AGENT']))
										$approved_comment = false;
									// set comment data
									$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');
									
									// add a hook for duplicate comments
									add_action("comment_duplicate_trigger",array(&$this,'wmp_duplicate'));
										
									// get comment id
									$comment_id = wp_new_comment( $commentdata );
									
									// get status
									if (is_numeric($comment_id)) {
										
										// get comment
										$comment = get_comment($comment_id);
										// set status by comment status
										if ($comment->comment_approved == 1)
											return '{"status":1}';//Your comment was successfully added
										else

											return '{"status":2}'; // Your comment is awaiting moderation.
									
									}
									
								} else // return error
									return '{"status":0}'; // Sorry, comments are closed for this item.
									
							}else
								// return error
								return '{"status":0}'; // Sorry, the post is not visible
							
						} else
							// return error
							return '{"status":0}'; // Sorry, the post is not available
							
					}
				}
			}
		} 
		// return error
		return '{"status":0}'; // error status
	}
	
	
	
	
	/**
    * 
    *  - exportPages method used for the export of a number of articels for each category
	*  - this metod returns a JSON with the specific content
	*  - ex : 
	*	{
	*		"pages": [
	*			{
	*			  "id": "53624b6981f58370a6968678",
	*			  "title": "#IJF14: Global developments in data journalism",
	*			  "timestamp": 1398950385,
	*			  "author": "",
	*			  "date": "Thu, May 01, 2014 01:19",
	*			  "link": "http://www.journalism.co.uk/news/-ijf14-global-patterns-in-data-journalism-/s2/a556612/",
	*			  "image": "",
	*			  "description":"<p><b>Sport</b> (or <b>sports</b>) is all forms of usually <a href=\"http://en.wikipedia.org/wiki/Competition\">competitive</a> <a href=\"http://en.wikipedia.org/wiki/Physical_activity\">physical activity</a> which,<sup><a href=\"http://en.wikipedia.org/wiki/Sport#cite_note-sportaccord-1\">[1]</a></sup> through casual or organised participation, aim to use, maintain or improve physical ability and skills while...</p>",				  
	*			  "content": ''
	*			},
	*		]
	*	}
    *
	*    
    */
	public function exportPages() {
		
		if (isset($_GET["content"]) && $_GET["content"] == 'exportpages') {
		
			// init pages array
			$arrPages = array();
			
			// set last timestamp
			$lastTimestamp = date("Y-m-d H:i:s");
			if (isset($_GET["lastTimestamp"]) && is_numeric($_GET["lastTimestamp"]))
				$lastTimestamp = date("Y-m-d H:i:s",$_GET["lastTimestamp"]);
			
			
			$descriptionLength = 200;
			if (isset($_GET["descriptionLength"]) && is_numeric($_GET["descriptionLength"]))
				$descriptionLength = $_GET["descriptionLength"];
			
			// set limit
			$limit = 7;
			if (isset($_GET["limit"]) && is_numeric($_GET["limit"]))
				$limit = $_GET["limit"];
			
			
			// set args for pages
			$args = array(
    			  'post__not_in' => $this->inactive_pages,
    			  'numberposts' => $limit,
    			  'posts_per_page' => $limit,
    			  'post_status' => 'publish',
				  'post_type' => 'page',
				  'post_password'	 => ''
            );
			
           if (WMP_BLOG_VERSION >= 3.6) {
				$args['orderby'] = 'title';
				$args['order'] = 'ASC';
			}
			
			
		    // get pages order
			$order_pages = unserialize(WMobilePack::wmp_get_setting('ordered_pages'));
		   
			// remove inline style for the photos types of posts
			add_filter( 'use_default_gallery_style', '__return_false' );
			
			$pages_query = new WP_Query ( $args );
            
    		if ($pages_query->have_posts()) {
    				
    			foreach ($pages_query->posts as $page) {
    					
					// add only the pages that are not password protected
					if ($page->post_password == '' && strip_tags(trim($page->post_title)) != '') {
					
						// check if featured image
						$image_details = array();
                        
						// get featured image and add it to the category
						if ( has_post_thumbnail($page->ID) ) { // check if the post has a Post Thumbnail assigned to it.
						  
							$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ),'large');
							
							if (is_array($image_data) && !empty($image_data)) { 
								
								$image_details = array(
                                    "src" 		=> $image_data[0],
                                    "width" 	=> $image_data[1],
                                    "height" 	=> $image_data[2]
                                );
							}
						} 
						
						$index_order = array_search($page->ID, $order_pages);
						
						// create new index for new categories
						$new_index = count($order_pages) + 1;
						$last_key = count($arrPages) > 0 ? max(array_keys($arrPages)) : 0;
						
						if (is_numeric($index_order))
							$current_key = $index_order;
						elseif ($new_index > $last_key)
							$current_key = $new_index;
						else
							$current_key = $last_key+1;
						
						
						$arrPages[$current_key] = array(
							'id' 				=> $page->ID,	
							'order'				=> $current_key,
							"title" 			=> strip_tags(trim($page->post_title)),							
							"image" 			=> !empty($image_details) ? $image_details : "",
							"content" 			=> ''
						);
					}
				}
			}
			
			// sort pages by key
            ksort($arrPages);
			$arrPages = array_values($arrPages);
            
			return '{"pages":'.json_encode($arrPages)."}";
		
		} else
			return '{"error":""}';
	}
	
	
	/**
    * 
    *  - exportPage method used for the export of a page
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
    *       "related_web_posts":"" 
    *       }
	*	}
    *  
	*   @params $pageId - the id of the page
	*    
    */
	public function exportPage() {
		
		// check if the export call is correct
		if (isset($_GET["content"]) && $_GET["content"] == 'exportpage' ) {
		
			// set pageId
			$pageId = 0;			
			if (isset($_GET["pageId"]) && is_numeric($_GET["pageId"])) {
				$pageId = $_GET["pageId"];
			}
			
			$descriptionLength = 200;
			if (isset($_GET["descriptionLength"]) && is_numeric($_GET["descriptionLength"]))
				$descriptionLength = $_GET["descriptionLength"];
			
			// init page array
			$arrPage = array();
			
			// get page by id
		    $page = get_page( $pageId);
			
			if ($page != null && $page->post_type == 'page' && $page->post_password == '' && strip_tags(trim($page->post_title)) != '') {
				
			  	// check if page is visible
			    $is_visible = false;
                   
				if (!in_array($page->ID, $this->inactive_pages))
					$is_visible = true;
                
                if ($is_visible){
                
    				// featured image details
    				$image_details = array();
                    				
    				// get featured image
    				if ( has_post_thumbnail($page->ID) ) { // check if the post has a Post Thumbnail assigned to it.
    				  
    					$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ),'large');
    					
    					if (is_array($image_data) && !empty($image_data)) {
    					   
    						$image_details = array(
                                "src" 		=> $image_data[0],
                                "width" 	=> $image_data[1],
                                "height" 	=> $image_data[2]
                            );
    					}
    				} 
    				
					
					// for the content, first check if the admin edited the content for this page
					if (get_option('wmpack_page_'.$page->ID) === false)
						$content = apply_filters("the_content", $page->post_content);
					else
						$content = apply_filters("the_content", get_option( 'wmpack_page_' .$page->ID  ));
    				
                    
                    $related_web_posts = '';                    
                    /* ZEMANTA RELATED POSTS AND POSTS FROM AROUND THE WEB */
                    if (WMobilePack::wmp_active_plugin('Related Posts by Zemanta')) {
                    
                        // check if class exists and specific function that are used
                         if (class_exists('WPRPZemanta')) {
                            // set zemanta purifier
                            $zemanta_purifier = $this->wmp_zemanta_purifier();
                            // get related posts from around the web
                            $related_web_posts = $this->wmp_related_web_posts($content);
                            $related_web_posts = $zemanta_purifier->purify($related_web_posts);
                         }
                    }    
                    if ($related_web_posts == '') {
                        
                        /* ZEMANTA EDITORIAL ASISTANT AND POSTS FROM AROUND THE WEB */                  
                        if (WMobilePack::wmp_active_plugin('Editorial Assistant by Zemanta')) {
                             // set zemanta purifier
                             $zemanta_purifier = $this->wmp_zemanta_purifier();
                            
                             // get related posts from around the web
                             $related_web_posts = $this->wmp_related_web_posts($content);
                            
                             // parse the urls in order to obtain the correct path
                             $related_web_posts = $this->wmp_replace_internal_links($related_web_posts);
                             $related_web_posts = $zemanta_purifier->purify($related_web_posts);
                            
                        }
                    }
                    
					// remove script tags
					$content = self::removeScriptTags($content);
					
    				$content = $this->purifier->purify($content);
    				
					// remove all url's from attachment images
					$content = preg_replace( array('{<a(.*?)(wp-att|wp-content\/uploads|attachment)[^>]*><img}', '{ wp-image-[0-9]*" /></a>}'), array('<img','" />'), $content);
					
    				// get the description
    				$description = Export::truncateHtml($content,$descriptionLength);
    				
    				$arrPage = array(
                        'id' 					=> $page->ID,
                        "title" 				=> $page->post_title,
                        "link" 			    	=> get_permalink($page->ID),
                        "image" 				=> !empty($image_details) ? $image_details : "",
                        "content" 				=> $content,
                        "related_web_posts"     => trim($related_web_posts),
					 );
				}
			}
				
			// return page json
			return '{"page":'.json_encode($arrPage)."}";
			
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
			
			// remove all unwanted script tags
			$text = self::removeScriptTags($text);
			
			// if no_images is true, remove all images from the content
			if ($stripTags)
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
				if ($total_length>= $length) {
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
		if ($considerHtml) {
			// close all unclosed html-tags
			foreach ($open_tags as $tag) {
				$truncate .= '</' . $tag . '>';
			}
		}
		return $truncate;
	}
	
    
	/**
	 * Method used to remove script tags and everything in between them
	 */
	public static function removeScriptTags($text) {
     
        $text = preg_replace("/<\s*script[^>]*>[\s\S]*?(<\s*\/script[^>]*>|$)/i"," ",$text);
        // return clean text
        return $text;
	}
	
	
	/**
	 * 
	 * Method wmp_duplicate called when a duplicate comment is detected.
	 *
	 * The method is used to echo a JSON with and error and applies an exit to prevent wp_die()
     * 
	 */
	 public function wmp_duplicate(){
		 
		// display the json 
		echo $_GET['callback'] . '({"status":0})';
		
		// end 
		exit();
	}
	 
     /**
	 * 
	 * Method comment_closed used to determine the comment status for an article
	 *
	 *  The method returns 'open' if the users can comment and 'closed' otherwise
	 *
	 * @param $post - object containing the post details 
	 */
	 public function comment_closed( $post ) {
        
        // set initial status for comments
        if ($post->comment_status == 'open' && get_option('comment_registration') == 0)
            $comment_status ='open';
        else 
            $comment_status = 'closed';
            
        // if the option close_comments_for_old_posts is not set, teturn the status for comments
    	if ( !get_option('close_comments_for_old_posts') )
    		return $comment_status;
    
        // if the number of ol days is not set return comment_status
    	$days_old = (int) get_option('close_comments_days_old');
    	if ( !$days_old )
    		return $comment_status;
    
    	$post = get_post($post->ID);
    
    	/** This filter is documented in wp-includes/comment.php */
    	$post_types = apply_filters( 'close_comments_for_post_types', array( 'post' ) );
    	if ( ! in_array( $post->post_type, $post_types ) )
    		$comment_status ='open';
        
        // if the post is older than the number of days set, change comment_status to false
    	if ( time() - strtotime( $post->post_date_gmt ) > ( $days_old * DAY_IN_SECONDS ) )
    		$comment_status = 'closed';
          
		 // return comment status 
    	return $comment_status;
    }
		
	
    /**
     * 
     * Export manifest files for Android or Mozilla
     * 
     * These manifest files will be used only if the index file is loaded from the plugin (free or premium).
     * 
     */
    public function exportManifest(){
        
        if (isset($_GET["content"]) && ($_GET["content"] == 'androidmanifest' || $_GET["content"] == 'mozillamanifest')) {
            
            // Check if the premium version is enabled
            $is_premium = false;
            $arrPremiumConfig = null;
            
            // Check premium configuration
            if (WMobilePack::wmp_get_setting('premium_active') == 1 && WMobilePack::wmp_get_setting('premium_api_key') != '') {
		 
				$is_premium = true; 
                
                $json_config_premium = WMobilePack::wmp_set_premium_config(); 
                
            	if ($json_config_premium !== false) {
            		$arrPremiumConfig = json_decode($json_config_premium, true);
            	}
            }
            
            // set blog name
            $blog_name = get_bloginfo("name");
            
            if ($is_premium && $arrPremiumConfig != null && isset($arrPremiumConfig['title']))
                $blog_name = $arrPremiumConfig['title'];
                
            // init response depending on the manifest type
            if ($_GET['content'] == 'androidmanifest') {
                
                $arrManifest = array(
                	'name' 			=> $blog_name,
                	'start_url' 	=> home_url(), 
                	'display' 		=> 'standalone'
                );
                
            } else {
                
                // remove domain name from the launch path
                $launch_path = home_url();
                $launch_path = str_replace('http://'.$_SERVER['HTTP_HOST'],'',$launch_path);
                $launch_path = str_replace('https://'.$_SERVER['HTTP_HOST'],'',$launch_path);
                
                $arrManifest = array(
                	'name' 			=> $blog_name, 
                	'launch_path' 	=> $launch_path, 
                	'developer'		=> array(
	  					"name"		=> $blog_name
     				)
                );
            }
            
            // check if icon exists
            $icon_path = false;
            
            if ($is_premium) {
                
                // load icon from the premium config json
                if ($arrPremiumConfig != null && isset($arrPremiumConfig['icon_path']) && $arrPremiumConfig['icon_path'] != ''){
                    
                    // compose icon path
                    $is_secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
                    $cdn_apps = ($is_secure ? $arrPremiumConfig['cdn_apps_https'] : $arrPremiumConfig['cdn_apps']);
                    
                    $icon_path = $cdn_apps."/".$arrPremiumConfig['shorten_url'].'/'.$arrPremiumConfig['icon_path'];
                }

            } else {
                
                // load icon from the local settings and folder
                $icon_path = WMobilePack::wmp_get_setting('icon');
            
                if ($icon_path == '' || !file_exists(WMP_FILES_UPLOADS_DIR.$icon_path)) {
                    $icon_path = false;
                } else {
                    $icon_path = WMP_FILES_UPLOADS_URL.$icon_path;
                }
            }
            
            // set icon depending on the manifest file type
            if ($icon_path != false) {
                
                if ($_GET['content'] == 'androidmanifest') {
                    
                    $arrManifest['icons'] = array(
        				array(
        					"src"		=> $icon_path,
        					"sizes"		=> "192x192"
        				)
                    );
                    
                } else {
                    $arrManifest['icons'] = array(
        				'152' => $icon_path,
                    );
                }
            }
    
            echo json_encode($arrManifest);
        }
    }
    
    
	/**
    * 
    *  - exportSettings method used for the export of the main settings
	*  - This method returns a JSON with the specific content
	*  - ex : 	
	*		{
	*			"logo": "",
	*			"icon": "",
	*			"cover": "",
    *           "status": 0/1
	*		}
	*				
    *
    */
	public function exportSettings() {
			
		if (isset($_GET["content"]) && $_GET["content"] == 'exportsettings') {
			
			$arrSettings = array();
			
			if (isset($_POST["apiKey"]) && $_POST["apiKey"] == WMobilePack::wmp_get_setting('premium_api_key')) {
				
				if (WMobilePack::wmp_get_setting('premium_active') == 0) {
					
					// check if logo exists
					$logo_path = WMobilePack::wmp_get_setting('logo');					
					if ($logo_path == '' || !file_exists(WMP_FILES_UPLOADS_DIR.$logo_path))
						$logo_path = '';    
					else
						$logo_path = WMP_FILES_UPLOADS_URL.$logo_path;
						
					// check if icon exists
					$icon_path = WMobilePack::wmp_get_setting('icon');					
					if ($icon_path == '' || !file_exists(WMP_FILES_UPLOADS_DIR.$icon_path))
						$icon_path = ''; 
					else
						$icon_path = WMP_FILES_UPLOADS_URL.$icon_path;
						
					// check if cover exists
					$cover_path = WMobilePack::wmp_get_setting('cover');					
					if ($cover_path == '' || !file_exists(WMP_FILES_UPLOADS_DIR.$cover_path))
						$cover_path = ''; 
					else
						$cover_path = WMP_FILES_UPLOADS_URL.$cover_path;
					
					// check if google analytics id is set
                    $google_analytics_id = WMobilePack::wmp_get_setting('google_analytics_id');
                    
					// set settings
					$arrSettings = array(
                        'logo' => $logo_path,
                        'icon' => $icon_path,
                        'cover' => $cover_path,
                        'google_analytics_id' => $google_analytics_id,
                        'status' => 1
                    );
				
					// return json
					return json_encode($arrSettings);
				
				} else 
                    return '{"error":"Premium plugin is not active.","status" : 0}';
                
			} else 
                return '{"error":"Missing post data (API Key) or mismatch.","status" : 0}';
			
		} else
			return '{"error":"","status" : 0}';
	}
    
    
    /**
     * 
     * Method wmp_replace_internal_links called when related posts are found for an article
     *
     * The method is used to replace the standard urls of the related posts with internal urls
     * 
     * @param $content - the content to be parsed
     * @param $type - can be post or page, in order to rebuild the correct url
     * 
     * 
     */
    public function wmp_replace_internal_links($content,$type='post'){
		
        if ($content != '' && in_array($type,array('post','page'))) { 
            
            // check if url exist
            $Match = preg_match_all('%href=\"(https?:)?(http?:)?//'.$_SERVER['HTTP_HOST'].'.*\"%siU',$content,$matches);
        
            // if there was at least a match fount
            if ($Match) {
            
                if (!empty($matches) && is_array($matches)) {
                
                    if (!empty($matches[0]) && is_array($matches[0])) {
                    
                        // replace links
                        foreach ($matches[0] as $match) {
                        
                            if ($match != '') {
                        
                                // get url
                                $post_url = substr($match,6,-1);
                            
                                // get post id using the url
                                $post_id = url_to_postid($post_url);
                            
                                if (is_numeric($post_id) && $post_id > 0) {
                                    
                                    // recreate new url                                   
                                    $new_url = 'href="#'.$post_id.'"';
                                    
                                    // update content and add new url
                                    $content = str_replace($match,$new_url,$content);
                                }
                            }                    
                        }                
                    }            
                }
            }
        }
        
        return $content;
	}
    
    
    
    /**
     * 
     * Method wmp_related_web_posts called when we want to fetch zemanta's related posts around the web
     *
     * The method is used to remove the related posts around the web from the content
     * 
     * @param $content - the content to be parsed, the related posts will be removed
     * 
     * The method return the html with the related posts around the web, or empty if there are none
     * 
     */
    public function wmp_related_web_posts(&$content){
		
        // by default, related posts are emtpy
        $related_posts = '';
        
        if ($content != '') {
            
            // remove the title    
            $content = preg_replace('/<h.* class="zemanta-related-title".*>(.*?)<\/(.*)>/i', '', $content);
           
            // remove and get the content
            $content_match = preg_match('%<ul class=\"zemanta-article-ul zemanta-article-ul-image\".*>(.*?)<\/ul>%siU',$content,$matches);
            
            if ($content_match) {
                
                if (is_array($matches) && !empty($matches)){
                    
                    $related_posts = $matches[0];
                    
                    // remove zemnata list from content
                    $content = preg_replace('%<ul class=\"zemanta-article-ul zemanta-article-ul-image\".*>(.*?)<\/ul>%siU', '', $content);
           
                }
            }
        }
        
        
        return $related_posts;
	}
    
    
    /**
     * 
     * Method wmp_zemanta_purifier called when he want to keep the class attribute for the content, for zemanta tags
     * 
     */
    public function wmp_zemanta_purifier(){
        
        // set HTML Purifier
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding', 'UTF-8'); 									
		$config->set('HTML.AllowedElements','div,a,p,ol,li,ul,img,blockquote,em,span,h1,h2,h3,h4,h5,h6,i,u,strong,b,sup,br,cite,iframe,small,video,audio,source');
		$config->set('HTML.AllowedAttributes', 'class, src, width, height, target, href, name,frameborder,marginheight,marginwidth,scrolling,poster,preload,controls,type');
						    
        $config->set('Attr.AllowedFrameTargets', '_blank, _parent, _self, _top');
		
		$config->set('HTML.SafeIframe',1);
		$config->set('Filter.Custom', array( new HTMLPurifier_Filter_Iframe()));
		
		// disable cache
		$config->set('Cache.DefinitionImpl',null);
		
	   // extend purifier
        $Html5Purifier = new WMPHtmlPurifier();
        $purifier = $Html5Purifier->wmp_extended_purifier($config);
        
        return $purifier;
    }

  } // Export
  
?>