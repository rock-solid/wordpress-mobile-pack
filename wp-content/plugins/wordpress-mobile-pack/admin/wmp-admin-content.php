<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            
            WMPJSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            WMPJSInterface.init();
        });
    }
</script>
<?php 
    $inactive_categories = unserialize(WMobilePack::wmp_get_setting('inactive_categories'));
	$order_categories = unserialize(WMobilePack::wmp_get_setting('ordered_categories'));
    $categories = get_categories();

	$inactive_pages = unserialize(WMobilePack::wmp_get_setting('inactive_pages'));
	$order_pages = unserialize(WMobilePack::wmp_get_setting('ordered_pages'));
	$pages = get_pages();
	
?>
<div id="wmpack-admin">
	<div class="spacer-60"></div>
    <!-- set title -->
    <h1><?php echo WMP_PLUGIN_NAME;?></h1>
	<div class="spacer-20"></div>
	<div class="content">
        <div class="left-side">
        
            <!-- add nav menu -->
            <?php include_once('sections/wmp-admin-menu.php'); ?>
            <div class="spacer-0"></div>
            
            <!-- add content form -->
            <div class="details">
            	<div class="spacer-10"></div>
                <h2 class="title">Categories</h2>
           		<div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <p>Choose what categories to be displayed in your mobile web application. Click on the rows to below to <em>show/hide</em> categories.</p>
            	<div class="spacer-20"></div>
                <div class="spacer-20"></div>
                
                <!-- start categories list -->
                <?php if (count($categories) > 0): ?>
                
                    <form name="wmp_editcategories_form" id="wmp_editcategories_form" action="" method="post">
                        
                        <div id="wmp_editcategories_warning" class="message-container warning" style="display: <?php echo count($inactive_categories) < count($categories) ? 'none' : 'block'?>;">
                            <div class="wrapper">
                                <div class="relative"><a class="close-x"></a></div>
                                <span>Since you deactivated all your categories, no content will be displayed in your mobile web app!</span> 
                            </div>
                            <div class="spacer-10"></div>
                        </div>
                
                        <ul class="categories" id="categories">
                            <?php
								$arrCategories = array();
								if(is_array($order_categories) && !empty($order_categories)){
									// order categories
									foreach($categories as $category) {
									
										$index = array_search($category->cat_ID,$order_categories);
										
										// create new index for new categories
										$new_index = count($order_categories) + 1;
										$last_key = count($arrCategories) > 0 ? max(array_keys($arrCategories)) : 0;
										
										if(is_numeric($index))
											$arrCategories[$index] = $category;
										elseif($new_index > $last_key)
											$arrCategories[$new_index] = $category;
										else
											$arrCategories[$last_key+1] = $category;
									
									}
									// sort categories	
									ksort($arrCategories);
									
								} else
									$arrCategories = $categories;
									
							?>
							<?php 
                                foreach ($arrCategories as $key => $category):
                            
                                    $status = 'active';
                                    if (in_array($category->cat_ID, $inactive_categories))
                                        $status = 'inactive';
                            ?>
                        	<li data-category-id="<?php echo $category->cat_ID;?>" data-order="<?php echo $key;?>">
                            	<span class="status <?php echo $status;?>"><?php echo $status;?></span>
                                <span class="title"><?php echo $category->name;?></span>
                                <span class="posts"><?php echo $category->category_count != 1 ? $category->category_count.' posts' : '1 post';?> published</span>
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </form>
                    
                <?php else: ?>
                
                    <div class="message-container warning">
                        <div class="wrapper">
                            <div class="title">
                                <h2 class="underlined">No categories to display!</h2>
                            </div>
                            <span>Since you don't have any categories, no content will be displayed in your mobile web app!</span> 
                        </div>
                    </div>
                        
                <?php endif;?>
                
             </div> 
             <div class="spacer-10"></div>  
             <div class="details" id="editpages_container">   
                <div class="spacer-10"></div>
                <h2 class="title">Pages</h2>
           		<div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam sed imperdiet dui. Phasellus nisi justo, posuere eget pharetra in, accumsan a augue. Nulla aliquet, diam non aliquam fermentum, sem libero scelerisque velit, sit amet mollis libero mauris sit amet ligula. Aliquam nec dolor mollis, sollicitudin est vel, faucibus velit. Vivamus justo odio, mollis vel purus non, tempus euismod nibh. Praesent aliquam ornare nisl non facilisis.</p>
            	<p>If you have at least two pages, you can rearrange their order by drag & drop.</p>
                <div class="spacer-20"></div>
                <!-- start pages list -->
                <?php if (count($pages) > 0): ?>
                
                    <form name="wmp_editpages_form" id="wmp_editpages_form" action="" method="post">
                        
                        <div id="wmp_editpages_warning" class="message-container warning" style="display: <?php echo count($inactive_pages) < count($pages) ? 'none' : 'block'?>;">
                            <div class="wrapper">
                                <div class="relative"><a class="close-x"></a></div>
                                <span>You deactivated all your pages, no content will be displayed in your mobile web app!</span> 
                            </div>
                            <div class="spacer-10"></div>
                        </div>
                
                        <ul class="categories pages">
                            <?php
								$arrPages = array();
								if(is_array($order_pages) && !empty($order_pages)){
									// order pages
									foreach($pages as $key => $page) {
											
										$index = array_search($page->ID,$order_pages);
										
										// create new index for new pages
										$new_index = count($order_pages) + 1;
										$last_key = count($arrPages) > 0 ? max(array_keys($arrPages)) : 0;
										
										// set index for pages
										if(is_numeric($index))
											$arrPages[$index] = $page;
										elseif($new_index > $last_key)
											$arrPages[$new_index] = $page;
										else
											$arrPages[$last_key+1] = $page;
									}
									
									// sort pages	
									ksort($arrPages);
								
								} else
									$arrPages = $pages;
								
								
							?>
							<?php 
                                foreach ($arrPages as $key =>  $page):
                            
                                    $status = 'active';
                                    if (in_array($page->ID, $inactive_pages))
                                        $status = 'inactive';
									
                            ?>
                        	<li data-page-id="<?php echo $page->ID;?>" data-order="<?php echo  $key;?>">
                            	<div class="row">
                                    <span class="status <?php echo $status;?>"><?php echo $status;?></span>
                                    <span class="title"><?php echo $page->post_title;?></span>
                                </div>
                                <div class="buttons">
                                    <a href="<?php echo admin_url('admin.php?page=wmp-page-details&id='.$page->ID);?>" class="edit" title="Edit page"></a> 
                                    <span class="delete" title="Delete page" style="display: none;"></span>
                                </div>
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </form>
                <?php else: ?>
                
                    <div class="message-container warning">
                        <div class="wrapper">
                            <div class="title">
                                <h2 class="underlined">No pages to display!</h2>
                            </div>
                            <span>You don't have any pages to be displayed in your mobile web app!</span> 
                        </div>
                    </div>
                        
                <?php endif;?>
                
                
            </div>
            <div class="spacer-10"></div>
            <div class="details">
            	<h2 class="title">Coming Soon</h2>
           		<div class="spacer-15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <div class="spacer-20"></div>
                <div class="more-content">
                 	<p class="left">Want to add more content?</p>
                 	<img src="<?php echo plugins_url()."/".WMP_DOMAIN."/admin/images/content-icons.png";?>" alt="" class="left" />
                </div>
                <?php
                    $joined_content_waitlist = false;
                     
                    $joined_waitlists = unserialize(WMobilePack::wmp_get_setting('joined_waitlists'));
                    
                    if ($joined_waitlists != '' && in_array('content', $joined_waitlists))
                        $joined_content_waitlist = true;
                ?>
                
                <div class="waitlist" id="wmp_waitlist_container">
                
                    <?php if ($joined_content_waitlist == false):?>
                        <div id="wmp_waitlist_action">
                            <a href="javascript:void(0);" id="wmp_waitlist_display_btn" class="btn blue smaller">Join Waitlist</a>
                            <div class="spacer-0"></div>
                            <p>and get notified when available</p>
                        </div>
                    
                        <form name="wmp_waitlist_form" id="wmp_waitlist_form" action="" method="post" style="display: none;">    
                            <div class="info">
                        	   <input name="wmp_waitlist_emailaddress" id="wmp_waitlist_emailaddress" type="text" placeholder="your email" class="small" value="<?php echo get_option( 'admin_email' );?>" />
                               <a href="javascript: void(0);" id="wmp_waitlist_send_btn" class="btn blue smallest">Ok</a>
                               <div class="spacer-5"></div>
                               <div class="field-message error" id="error_emailaddress_container"></div>
                        	   <div class="spacer-15"></div>
                    	   </div>
                        </form>
                    <?php endif;?>
                    
                    <div id="wmp_waitlist_added" class="added" style="display: <?php echo $joined_content_waitlist ? 'block' : 'none'?>;">
                        <div class="switcher blue">
                        	<div class="msg">ADDED TO WAITLIST</div>
                            <div class="check"></div>
                        </div>
                        <div class="spacer-15"></div>
                	</div>
                </div> 
                <div class="spacer-5"></div>
                <div class="grey-line"></div>
                <div class="spacer-20"></div> 
            </div>
        </div>
    
        <div class="right-side">
        
            <!-- add feedback form -->
            <?php include_once('sections/wmp-feedback.php'); ?>
        </div>
	</div>


</div>

<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            
            window.WMPJSInterface.add("UI_editcategories","WMP_EDIT_CATEGORIES",{'DOMDoc':window.document}, window);
            window.WMPJSInterface.add("UI_editpages","WMP_EDIT_PAGES",{'DOMDoc':window.document}, window);
            
            <?php if ($joined_content_waitlist == false):?>
            
                window.WMPJSInterface.add("UI_joinwaitlist",
                    "WMP_WAITLIST",
                    {
                        'DOMDoc':       window.document,
                        'container' :   window.document.getElementById('wmp_waitlist_container'),
                        'submitURL' :   '<?php echo WMP_WAITLIST_PATH;?>',
                        'listType' :    'content'
                    }, 
                    window
                );
            <?php endif;?>
        });
    }
</script>