<script type="text/javascript">
    if (window.WMPJSInterface && window.WMPJSInterface != null){
        jQuery(document).ready(function(){
            
            WMPJSInterface.localpath = "<?php echo plugins_url()."/".WMP_DOMAIN."/"; ?>";
            WMPJSInterface.init();
        });
    }
</script>
<?php
	$categories = get_categories();
	$order_categories = unserialize(WMobilePack::wmp_get_setting('ordered_categories'));
	
	// Depending on the language settings, not all categories might be visible at the same time
    $setting_inactive_categories = unserialize(WMobilePack::wmp_get_setting('inactive_categories'));
	$inactive_categories = array();
	
	// Compose inactive pages array with only the visible pages
	foreach ($categories as $category){
		if (in_array($category->cat_ID, $setting_inactive_categories))
			$inactive_categories[] = $category->cat_ID;
	}
	
	// ------------------------------------ //
	
	$pages = get_pages();
	$order_pages = unserialize(WMobilePack::wmp_get_setting('ordered_pages'));
	
	// Depending on the language settings, not all pages might be visible at the same time
	$setting_inactive_pages = unserialize(WMobilePack::wmp_get_setting('inactive_pages'));
	$inactive_pages = array();
	
	// Compose inactive pages array with only the visible pages
	foreach($pages as $key => $page) {
		if (in_array($page->ID, $setting_inactive_pages))
			$inactive_pages[] = $page->ID;
	}
	
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
                <p>Choose what categories to be displayed in your mobile web application. Click on the rows to below to show/hide categories and order them by dragging the corresponding row on the desired position.</p>
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
                                // list with displayed categories
                                $arrOrderedCategories = array();
                                
                            	if (is_array($order_categories) && !empty($order_categories)){
                            	   
                                    // add categories in the array in their order 
                                    foreach ($order_categories as $category_id){
                                        
                                        foreach ($categories as $category){
                                            if ($category->cat_ID == $category_id){
                                                $arrOrderedCategories[] = clone $category;
                                                break;
                                            }
                                        }
                                    }    
                                    
                                    // copy categories that don't have an order yet (they were added after the categories were sorted)
                                    foreach($categories as $category){
                                        
                                        $added = false;
                                        
                                        foreach ($arrOrderedCategories as $key => $ordered_category){
                                            if ($category->cat_ID == $ordered_category->cat_ID){
                                                $added = true;
                                                break;
                                            }
                                        }
                                        
                                        if (!$added){
                                            $arrOrderedCategories[] = clone $category;
                                        }
                                    }
                                    
                            	} else
                            		$arrOrderedCategories = $categories;
        
                                foreach ($arrOrderedCategories as $key => $category):
                            
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
                <p>Choose what pages you want to display on your mobile web application. You can edit, show/hide different pages and, if you have at least two pages, you can rearrange their order by drag &amp; drop.</p>
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
                                
								if (is_array($order_pages) && !empty($order_pages)){
								    
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
        </div>
    
        <div class="right-side">
        	<!-- add waitlist form -->
            <?php include_once('sections/wmp-waitlist.php'); ?>
            
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
        });
    }
</script>